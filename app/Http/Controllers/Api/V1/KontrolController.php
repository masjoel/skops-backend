<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Skor;
use App\Models\Kontrol;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KontrolController extends Controller
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

        $qryKontrol = Kontrol::from('kontrol as a')
            ->select(
                'a.*',
                'b.nama as namaguru',
                'c.nama as namasiswa',
                'c.kelas',
                'c.ext',
                'c.jurusan',
                'd.nama as opr'
            )
            ->leftJoin('customer as b', 'b.id', '=', 'a.idguru')
            ->leftJoin('customer as c', 'c.id', '=', 'a.idsiswa')
            ->leftJoin('user as d', 'd.idx', '=', 'a.iduser');

        if ($level == 'administrator' and $IDprsh == 0) {
            $queryKontrol = $qryKontrol;
        } else if ($level == 'guru') {
            $queryKontrol = $qryKontrol->whereIn('a.iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        } else {
            $queryKontrol = $qryKontrol->whereIn('a.iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        }

        $data['kontrol'] = $queryKontrol->orderByDesc('a.id')->limit(10)->get();
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ], 201);
    }
    public function rekap()
    {
        $level = Auth::user()->level;
        $IDprsh = Auth::user()->idprsh;
        $IDopr = Auth::user()->idopr;
        $IDuser = Auth::user()->idx;

        $qryKontrol = Kontrol::select(
            'kontrol.idsiswa',
            DB::raw("SUM(CASE WHEN kontrol.tipe = 'pelanggaran' THEN kontrol.skor ELSE 0 END) AS totpoin"),
            DB::raw("SUM(CASE WHEN kontrol.tipe = 'reward' THEN kontrol.skor ELSE 0 END) AS totreward"),
            DB::raw("
                (
                    SUM(CASE WHEN kontrol.tipe = 'reward' THEN kontrol.skor ELSE 0 END)
                    -
                    SUM(CASE WHEN kontrol.tipe = 'pelanggaran' THEN kontrol.skor ELSE 0 END)
                ) AS totSkor
            "),
            'customer.nama',
            'customer.kelas',
            'customer.jurusan',
            'customer.ext',
            'customer.nis',
            'customer.nisn'
        )
            ->join('customer', 'customer.id', '=', 'kontrol.idsiswa')
            ->where('kontrol.skor', '>', 0)
            ->where('kontrol.idsiswa', '>', 0)
            ->groupBy(
                'kontrol.idsiswa',
                'customer.nama',
                'customer.kelas',
                'customer.jurusan',
                'customer.ext',
                'customer.nis',
                'customer.nisn'
            );

        if ($level == 'administrator' and $IDprsh == 0) {
            $queryKontrol = $qryKontrol;
        } else if ($level == 'guru') {
            $queryKontrol = $qryKontrol->where('idguru', $IDopr)
                ->whereIn('kontrol.iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
        } else {
            $queryKontrol = $qryKontrol->whereIn('kontrol.iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        }

        $data['kontrol'] = $queryKontrol->orderByDesc('totSkor')->limit(10)->get();
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
        DB::beginTransaction();
        $request->validate([
            'skor' => 'required|numeric',
        ]);
        $skor = Skor::find($request->idskor);
        $data = Kontrol::create([
            'iduser' => Auth::user()->idx,
            'tgl' => $request->tgl,
            'idguru' => $request->idguru,
            'idsiswa' => $request->idsiswa,
            'idskor' => $request->idskor,
            'tindakan' => $request->tindakan,
            'skor' => $skor->skor,
            'jenis' => $skor->jenis,
            'deskripsi' => $skor->deskripsi,
            'tipe' => $skor->tipe,
        ]);
        if ($data) {
            DB::commit();
            return response()->json([
                'message' => 'Data created successfully',
                'kontrol' => $data,
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
    public function show(Kontrol $kontrol)
    {
        $level = Auth::user()->level;
        $IDprsh = Auth::user()->idprsh;
        $IDopr = Auth::user()->idopr;
        $IDuser = Auth::user()->idx;

        $qryKontrol = Kontrol::from('kontrol as a')
            ->select(
                'a.*',
                'b.nama as namaguru',
                'c.nama as namasiswa',
                'c.kelas',
                'c.ext',
                'c.jurusan',
                'd.nama as opr'
            )
            ->leftJoin('customer as b', 'b.id', '=', 'a.idguru')
            ->leftJoin('customer as c', 'c.id', '=', 'a.idsiswa')
            ->leftJoin('user as d', 'd.idx', '=', 'a.iduser');

        $queryKontrol = $qryKontrol->where('a.idsiswa', $kontrol->idsiswa)
            ->whereIn('a.iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        $data['siswa'] = Customer::find($kontrol->idsiswa);
        $data['detail'] = $queryKontrol->orderByDesc('a.id')->limit(10)->get();
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ], 201);
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
    public function update(Request $request, Kontrol $kontrol)
    {
        $kontrol->update($request->all());
        return response()->json([
            'message' => 'Data updated successfully',
            'kontrol' => $kontrol,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kontrol $kontrol)
    {
        $kontrol->delete();
        return response()->json([
            'message' => 'Data deleted successfully',
        ], 201);
    }
}
