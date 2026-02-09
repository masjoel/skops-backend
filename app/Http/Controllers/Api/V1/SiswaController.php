<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Kontrol;
use App\Models\Customer;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
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

        $siswa = Customer::where('level', 'siswa')->orderBy('id', 'desc');
        $qrySiswa = $siswa->when($cari, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('kelas', 'like', "%$search%")
                    ->orWhere('jurusan', 'like', "%$search%")
                    ->orWhere('nis', 'like', "%$search%")
                    ->orWhere('nisn', 'like', "%$search%")
                    ->orWhere('alamat', 'like', "%$search%");
            });
        });

        if ($level == 'administrator' and $IDprsh == 0) {
            $querySiswa = $qrySiswa;
        } else if ($level == 'administrator' and $IDprsh > 0) {
            $querySiswa = $qrySiswa->whereIn('iduser', $userIds);
        } else if ($level == 'guru') {
            $querySiswa = $qrySiswa->whereIn('iduser', $userIds);
        } else {
            $querySiswa = $qrySiswa->whereIn('iduser', $userIds);
        }
        $data['siswa'] = $querySiswa->paginate(20);
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }
    public function list(Request $request)
    {
        $cari = $request->input('search');

        $level = Auth::user()->level;
        $IDprsh = Auth::user()->idprsh;
        $IDopr = Auth::user()->idopr;
        $IDuser = Auth::user()->idx;

        $userIds = DB::table('user')
            ->where('idprsh', $IDprsh)
            ->pluck('idx');

        $siswa = Customer::where('level', 'siswa')->orderBy('id', 'desc');
        $qrySiswa = $siswa->when($cari, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('kelas', 'like', "%$search%")
                    ->orWhere('jurusan', 'like', "%$search%")
                    ->orWhere('nis', 'like', "%$search%")
                    ->orWhere('status', $search)
                    ->orWhere('alamat', 'like', "%$search%");
            });
        });
        if ($level == 'administrator' and $IDprsh == 0) {
            $querySiswa = $qrySiswa;
        } else if ($level == 'administrator' and $IDprsh > 0) {
            $querySiswa = $qrySiswa->whereIn('iduser', $userIds);
        } else if ($level == 'guru') {
            $querySiswa = $qrySiswa->whereIn('iduser', $userIds);
        } else {
            $querySiswa = $qrySiswa->whereIn('iduser', $userIds);
        }
        $data['siswa'] = $querySiswa->get();
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
            'nama' => 'required',
            'kelas' => 'required',
        ]);
        $data = Customer::create([
            'level' => 'siswa',
            'iduser' => Auth::user()->idx,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'ext' => $request->ext,
        ]);
        if ($data) {
            DB::commit();
            return response()->json([
                'message' => 'Data created successfully',
                'siswa' => $data,
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
    public function update(Request $request, Customer $siswa)
    {
        $siswa->update($request->all());
        return response()->json([
            'message' => 'Data updated successfully',
            'siswa' => $siswa,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $siswa)
    {
        $cek = Kontrol::where('idsiswa', $siswa->id)->count();
        if ($cek == 0) {
            $siswa->delete();
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
