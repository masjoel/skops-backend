<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Jurusan;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $level = Auth::user()->level;
        $IDprsh = Auth::user()->idprsh;
        $IDopr = Auth::user()->idopr;
        $IDuser = Auth::user()->idx;

        $qryJurusan = Jurusan::orderBy('name', 'asc');
        if ($level == 'administrator' and $IDprsh == 0) {
            $queryJurusan = $qryJurusan->where('perusahaan_id', $IDprsh);
        } else if ($level == 'guru') {
            $queryJurusan = $qryJurusan
                ->whereIn('user_id', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
        } else {
            $queryJurusan = $qryJurusan
                ->whereIn('user_id', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
        }
        $data['jurusan'] = $queryJurusan->get();
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
            'name' => 'required',
            'kode' => 'required',
        ]);
        $data = Jurusan::create([
            'perusahaan_id' => Auth::user()->idprsh,
            'user_id' => Auth::user()->idx,
            'name' => $request->name,
            'kode' => $request->kode,
        ]);
        if ($data) {
            DB::commit();
            return response()->json([
                'message' => 'Data created successfully',
                'jurusan' => $data,
            ]);
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
    public function show(Jurusan $jurusan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurusan $jurusan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jurusan $jurusan)
    {
        $data = $request->all();
        $jurusan->update($data);
        return response()->json([
            'message' => 'Data updated successfully',
            'jurusan' => $jurusan,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurusan $jurusan)
    {
        $cek = Customer::where('kelas', $jurusan->name)->where('iduser', Auth::user()->idprsh)->count();
        if ($cek == 0) {
            $jurusan->delete();
            return response()->json([
                'message' => 'Data deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data gagal dihapus, sudah digunakan di database lain!',
            ]);
        }
    }
}
