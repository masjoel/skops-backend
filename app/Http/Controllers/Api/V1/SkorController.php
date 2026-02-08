<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Skor;
use App\Models\Kontrol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SkorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cari = $request->input('search');

        $level = Auth::user()->level;
        $IDprsh = Auth::user()->idprsh;
        $IDopr = Auth::user()->idopr;
        $IDuser = Auth::user()->idx;

        $userIds = DB::table('user')
            ->where('idprsh', $IDprsh)
            ->pluck('idx');

        $skor = Skor::orderBy('id', 'desc');
        $qrySkor = $skor->when($cari, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('jenis', 'like', "%$search%")
                    ->orWhere('deskripsi', 'like', "%$search%")
                    ->orWhere('tipe', 'like', "%$search%")
                    ->orWhere('skor', 'like', "%$search%")
                    ->orWhere('tindakan', 'like', "%$search%")
                    ->orWhere('kode', 'like', "%$search%");
            });
        });

        if ($level == 'administrator' and $IDprsh == 0) {
            $querySkor = $qrySkor;
        } else if ($level == 'administrator' and $IDprsh > 0) {
            $querySkor = $qrySkor->whereIn('iduser', $userIds);
        } else if ($level == 'guru') {
            $querySkor = $qrySkor->whereIn('iduser', $userIds);
        } else {
            $querySkor = $qrySkor->whereIn('iduser', $userIds);
        }

        $data['skor'] = $querySkor->orderByDesc('id')->get();
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'urut' => 'required',
            'jenis' => 'required',
            'skor' => 'required|numeric',
        ]);
        $data = Skor::create([
            'iduser' => Auth::user()->idx,
            'urut' => $request->urut,
            'kode' => $request->kode,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'skor' => $request->skor,
            'tipe' => $request->tipe,
            'tindakan' => $request->tindakan,
            'jam' => date('Y-m-d H:i:s'),
        ]);
        if ($data) {
            DB::commit();
            return response()->json([
                'message' => 'Data created successfully',
                'skor' => $data,
            ], 201);
        } else {
            DB::rollBack();
            return response()->json([
                'message' => 'Data created failed',
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skor $skor)
    {
        logger('RAW BODY: ' . $request->getContent());
        logger('REQUEST DATA:', $request->all());
        $data = $request->all();
        $skor->update($data);
        return response()->json([
            'message' => 'Data updated successfully',
            'skor' => $skor,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skor $skor)
    {
        $cek = Kontrol::where('idskor', $skor->id)->count();
        if ($cek == 0) {
            $skor->delete();
            return response()->json([
                'message' => 'Data deleted successfully',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Data gagal dihapus, sudah digunakan di database lain!',
            ], 201);
        }
    }
}
