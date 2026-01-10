<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    protected $waToken;
    protected $phoneNumberId;
    protected $graphVersion;

    public function __construct()
    {
        $this->waToken = config('services.whatsapp.token'); // or env
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->graphVersion = config('services.whatsapp.version', 'v17.0');
    }

    public function requestOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=>'Invalid input','errors'=>$validator->errors()], 422);
        }

        $phone = $request->phone;

        // Rate-limit per phone: max 5 requests per hour
        $limKey = "otp_request_count_{$phone}";
        $count = Cache::get($limKey, 0);
        if ($count >= 5) {
            return response()->json(['message'=>'Too many OTP requests. Try later.'], 429);
        }
        Cache::put($limKey, $count + 1, 3600);

        // generate OTP 6-digit
        $otp = random_int(100000, 999999);
        $hash = Hash::make($otp);

        // expiry 5 minutes
        $expiresAt = now()->addMinutes(5);

        // upsert otp record for phone
        Otp::updateOrCreate(
            ['phone' => $phone],
            ['otp_hash' => $hash, 'expires_at' => $expiresAt, 'attempts' => 0]
        );

        // Send via WhatsApp Cloud API
        $sent = $this->sendWhatsAppMessage($phone, "Kode verifikasi kamu: {$otp}. Berlaku 5 menit.");

        if (! $sent['ok']) {
            // Optionally: log $sent['error']
            return response()->json(['message'=>'Gagal mengirim OTP via WhatsApp', 'detail'=>$sent['error']], 500);
        }

        return response()->json(['message'=>'OTP terkirim via WhatsApp (jika nomor terdaftar di WA)']);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'otp' => 'required|digits:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=>'Invalid input','errors'=>$validator->errors()], 422);
        }

        $phone = $request->phone;
        $otpInput = $request->otp;

        $otpRow = Otp::where('phone', $phone)->first();
        if (! $otpRow) {
            return response()->json(['message'=>'OTP tidak ditemukan. Minta OTP lagi.'], 404);
        }

        if ($otpRow->isExpired()) {
            return response()->json(['message'=>'OTP sudah kadaluarsa. Minta OTP lagi.'], 410);
        }

        // attempts limit
        if ($otpRow->attempts >= 5) {
            return response()->json(['message'=>'Terlalu banyak percobaan. Minta OTP lagi.'], 429);
        }

        // check hash
        if (! Hash::check($otpInput, $otpRow->otp_hash)) {
            $otpRow->increment('attempts');
            return response()->json(['message'=>'OTP salah.'], 401);
        }

        // success: delete otp or mark used
        $otpRow->delete();

        // create user or return token — contoh sederhana: return success
        // di real app: buat user, generate JWT/api token, dll.
        return response()->json(['message'=>'Verifikasi berhasil.']);
    }

    protected function sendWhatsAppMessage(string $toPhone, string $message)
    {
        // gunakan WhatsApp Cloud API (Meta)
        // env needed: WHATSAPP_TOKEN, WHATSAPP_PHONE_NUMBER_ID, WHATSAPP_VERSION
        try {
            $client = new Client();
            $url = "https://graph.facebook.com/{$this->graphVersion}/{$this->phoneNumberId}/messages";

            $body = [
                'messaging_product' => 'whatsapp',
                'to' => $toPhone,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ],
            ];

            $resp = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->waToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $body,
                'timeout' => 10,
            ]);

            $code = $resp->getStatusCode();
            $json = json_decode($resp->getBody()->getContents(), true);

            if ($code >= 200 && $code < 300) {
                return ['ok' => true, 'response' => $json];
            } else {
                return ['ok' => false, 'error' => $json ?? 'unknown'];
            }
        } catch (\Exception $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }
}
