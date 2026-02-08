<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Customer;
use App\Models\KelasExt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KelasExtController extends Controller
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

        $qryExt = KelasExt::orderBy('name', 'asc');
        if ($level == 'administrator' and $IDprsh == 0) {
            $queryExt = $qryExt->where('perusahaan_id', $IDprsh);
        } else if ($level == 'guru') {
            $queryExt = $qryExt
                ->whereIn('user_id', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
        } else {
            $queryExt = $qryExt
                ->whereIn('user_id', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
        }
        $data['kelasExt'] = $queryExt->get();
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
        ]);
        $data = KelasExt::create([
            'perusahaan_id' => Auth::user()->idprsh,
            'user_id' => Auth::user()->idx,
            'name' => $request->name,
        ]);
        if ($data) {
            DB::commit();
            return response()->json([
                'message' => 'Data created successfully',
                'kelasExt' => $data,
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
    public function show(KelasExt $kelasExt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KelasExt $kelasExt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KelasExt $kelasExt)
    {
        $data = $request->all();
        $data['name'] = $request->name;
        $kelasExt->update($data);
        return response()->json([
            'message' => 'Data updated successfully',
            'kelasExt' => $kelasExt,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KelasExt $kelasExt)
    {
        $cek = Customer::where('kelas', $kelasExt->name)->where('iduser', Auth::user()->idprsh)->count();
        if ($cek == 0) {
            $kelasExt->delete();
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
