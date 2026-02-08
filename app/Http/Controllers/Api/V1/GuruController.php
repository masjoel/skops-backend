<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Kontrol;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
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

        $guru = Customer::where('level', 'guru')->orderBy('id', 'desc');
        $qryGuru = $guru->when($cari, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('kelas', 'like', "%$search%")
                    ->orWhere('jurusan', 'like', "%$search%")
                    ->orWhere('nis', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('alamat', 'like', "%$search%");
            });
        });
        if ($level == 'administrator' and $IDprsh == 0) {
            $queryGuru = $qryGuru;
        } else if ($level == 'administrator' and $IDprsh > 0) {
            $queryGuru = $qryGuru->whereIn('iduser', $userIds);
        } else if ($level == 'guru') {
            $queryGuru = $qryGuru->whereIn('iduser', $userIds);
        } else {
            $queryGuru = $qryGuru->whereIn('iduser', $userIds);
        }
        $data['guru'] = $queryGuru->paginate(20);
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

        $guru = Customer::where('level', 'guru')->orderBy('id', 'desc');
        $qryGuru = $guru->when($cari, function ($query, $search) {
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
            $queryGuru = $qryGuru;
        } else if ($level == 'administrator' and $IDprsh > 0) {
            $queryGuru = $qryGuru->whereIn('iduser', $userIds);
        } else if ($level == 'guru') {
            $queryGuru = $qryGuru->whereIn('iduser', $userIds);
        } else {
            $queryGuru = $qryGuru->whereIn('iduser', $userIds);
        }
        $data['guru'] = $queryGuru->get();
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
            'level' => 'guru',
            'iduser' => Auth::user()->idx,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'ext' => $request->ext,
            'status' => 'Aktif',
        ]);
        if ($data) {
            DB::commit();
            return response()->json([
                'message' => 'Data created successfully',
                'guru' => $data,
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
    public function update(Request $request, Customer $guru)
    {
        $guru->update($request->all());
        return response()->json([
            'message' => 'Data updated successfully',
            'guru' => $guru,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $guru)
    {
        $cek = Kontrol::where('idguru', $guru->id)->count();
        if ($cek == 0) {
            $guru->delete();
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
