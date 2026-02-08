<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Auth;

class WaliKelasController extends Controller
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

        $walikelas = WaliKelas::orderBy('id', 'desc');
        $qryWalikelas = $walikelas->when($cari, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('kelas', 'like', "%$search%")
                    ->orWhere('jurusan', 'like', "%$search%")
                    ->orWhere('nip', 'like', "%$search%")
                    ->orWhere('tahun', 'like', "%$search%");
            });
        });

        if ($level == 'administrator' and $IDprsh == 0) {
            $queryWalikelas = $qryWalikelas;
        } else if ($level == 'administrator' and $IDprsh > 0) {
            $queryWalikelas = $qryWalikelas->whereIn('iduser', $userIds);
        } else if ($level == 'guru') {
            $queryWalikelas = $qryWalikelas->whereIn('iduser', $userIds);
        } else {
            $queryWalikelas = $qryWalikelas->whereIn('iduser', $userIds);
        }
        $data['walikelas'] = $queryWalikelas->paginate(20);
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
            'kelas' => 'required',
            'tahun' => 'required',
        ]);
        $guru = Customer::where('id', $request->idguru)->first();
        $data = WaliKelas::create([
            'iduser' => Auth::user()->idx,
            'idguru' => $request->idguru,
            'nip' => $guru->nis,
            'nama' => $guru->nama,
            'kelas' => $request->kelas,
            'ext' => $request->ext,
            'jurusan' => $request->jurusan,
            'tahun' => $request->tahun,
        ]);
        if ($data) {
            DB::commit();
            return response()->json([
                'message' => 'Data created successfully',
                'walikelas' => $data,
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
    public function update(Request $request, WaliKelas $walikela)
    {
        $data = $request->all();
        $guru = Customer::where('id', $request->idguru)->first();
        $data['nama'] = $guru->nama;
        $data['nip'] = $guru->nis;
        $walikela->update($data);
        return response()->json([
            'message' => 'Data updated successfully',
            'walikelas' => $walikela,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaliKelas $walikela)
    {
        $cek = Customer::where('kelas', $walikela->kelas)->count();
        if ($cek == 0) {
            $walikela->delete();
            return response()->json([
                'message' => 'Data deleted successfully',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Data gagal dihapus, sudah digunakan di database lain!',
            ], 200);
        }
    }
}
