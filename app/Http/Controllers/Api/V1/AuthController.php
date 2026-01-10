<?php

namespace App\Http\Controllers\Api\V1;

use Ramsey\Uuid\Uuid;
use App\Models\UserOld;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);


        $user = UserOld::where('username', $request->username)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        if ($user->password_new == null) {
            $passwd = substr(md5($request->password), 0, 20);
            if ($passwd != $user->password) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ], 401);
            }
        } else {
            if (!Hash::check($request->password, $user->password_new)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ], 401);
            }
        }


        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => $user
        ], 200);
    }
    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->currentAccessToken()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated'
            ], 401);
        }

        $user->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out'
        ], 200);
    }


    public function register(Request $request)
    {
        DB::beginTransaction();
        $apps = Perusahaan::first();
        $request->validate([
            'nama' => 'required',
            'username' => 'required|string|min:4|unique:user,username',
            'email' => 'required|email|unique:user,email',
            'password' => 'required',
            'mpassword' => 'required',
            'npsn' => 'required|string|unique:perusahaan,Signature',
        ]);

        $jamNow = date('Y-m-d H:i:s');
        $save = Perusahaan::create([
            'NamaClient' => $request->instansi,
            'AlamatClient' => $request->alamat,
            'Signature' => $request->npsn,
            'email' => $request->email,
            'jam' => $jamNow,
            'NamaApp' => $apps->NamaApp,
            'VersiApp' => $apps->VersiApp,
            'DescApp' => $apps->DescApp,
        ]);
        $kodeactivasi = substr(Uuid::uuid4()->toString(), 4, 4);
        $data = UserOld::create([
            'idprsh' => $save->idx,
            'username' => $request->username,
            'password' => substr(md5($request->password), 0, 20),
            'password_new' => Hash::make($request->password),
            'nama' => $request->nama,
            'level' => 'administrator',
            'status' => 'Non Aktif',
            'email' => $request->email,
            'telp' => $request->username,
            'q1' => 'Siapa Nama lengkap Anda?',
            'q2' => 'Buah favorit Anda?',
            'a1' => $request->nama,
            'a2' => $request->username,
            'jam' => $jamNow,
            'akses' => '1,2,8,15,3,102,101,110,112,111,6',
            'kodeact' => $kodeactivasi
        ]);
        if ($data) {
            DB::commit();
            return response()->json([
                'message' => 'User created successfully',
                'user' => $data,
            ], 201);
        } else {
            DB::rollBack();
            return response()->json([
                'message' => 'User created failed',
            ], 422);
        }
    }

    public function lupa(Request $request)
    {
        DB::beginTransaction();
        $apps = UserOld::where('username', $request->username)->where('a1', $request->a1)->where('a2', $request->a2)->first();
        if ($apps) {
            $passbaru = substr(md5($request->username . date("YmdHis")), 0, 6);
            $data = array('password' => substr(md5($passbaru), 0, 20), 'password_new' => Hash::make($passbaru));
            // $data = array('password' => Hash::make($passbaru), 'password_new' => Hash::make($passbaru));
            $apps->update($data);
            DB::commit();
            return response()->json([
                'message' => 'Password baru: ' . $passbaru,
                'apps' => $apps,
            ], 201);
        } else {
            DB::rollBack();
            return response()->json([
                'message' => 'Data tidak ditemukan. Silakan ulangi proses Lupa Password lagi!',
            ], 422);
        }
    }
}
