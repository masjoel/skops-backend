<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
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

        $qryProfil = Perusahaan::from('perusahaan as a');
        if ($level == 'administrator' and $IDprsh == 0) {
            $queryProfil = $qryProfil;
        } else {
            $queryProfil = $qryProfil->where('idx', $IDprsh);
        }

        $data['perusahaan'] = $queryProfil->first();
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ], 201);
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
        //
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
    public function update(Request $request, Perusahaan $profil)
    {
        DB::beginTransaction();
        $request->validate([
            'NamaClient' => 'required',
            'AlamatClient' => 'required|string',
            'Signature' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('perusahaan', 'email')->ignore($profil->idx, 'idx'),
            ],
        ]);

        $profil->update([
            'NamaClient' => $request->NamaClient,
            'AlamatClient' => $request->AlamatClient,
            'Signature' => $request->Signature,
            'email' => $request->email
        ]);
        DB::commit();
        return response()->json([
            'message' => 'Data updated successfully',
            'profil' => $profil,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
