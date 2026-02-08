<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Skor;
use App\Models\Kontrol;
use App\Models\Customer;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['bln'] = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        $data['namabln'] = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des");
        $data['barcolor'] = array("", "#00a65a", "#f56954", "#000077");
        $level = Auth::user()->level;
        $IDprsh = Auth::user()->idprsh;
        $IDopr = Auth::user()->idopr;
        $IDuser = Auth::user()->idx;

        $qryGuru = Customer::where('level', 'guru');
        $qrySiswa = Customer::where('level', 'siswa');
        $qrySkor = Skor::where('id', '>', 0);
        $qryPelanggaran = Kontrol::where('tipe', 'pelanggaran');
        $qryReward = Kontrol::where('tipe', 'reward');

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
        $qryTopSkor = Kontrol::select(
            'idskor',
            'jenis',
            'skor',
            'tipe',
            DB::raw('COUNT(idsiswa) AS jumlah')
        )
            ->where('idskor', '>', 0)
            ->where('idsiswa', '>', 0)
            ->groupBy(
                'idskor',
                'jenis',
                'skor',
                'tipe'
            );

        if ($level == 'administrator' and $IDprsh == 0) {
            $queryGuru = $qryGuru;
            $querySiswa = $qrySiswa;
            $querySkor = $qrySkor;
            $queryPelanggaran = $qryPelanggaran;
            $queryReward = $qryReward;
            $queryKontrol = $qryKontrol;
            $queryTopSkor = $qryTopSkor;
        } else if ($level == 'guru') {
            $queryGuru = $qryGuru
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $querySiswa = $qrySiswa
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $querySkor = $qrySkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
            $queryPelanggaran = $qryPelanggaran->where('iduser', $IDuser);
            $queryReward = $qryReward->where('iduser', $IDuser);
            $queryKontrol = $qryKontrol->whereIn('kontrol.iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
            $queryTopSkor = $qryTopSkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        } else {
            $queryGuru = $qryGuru
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $querySiswa = $qrySiswa
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $querySkor = $qrySkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
            $queryPelanggaran = $qryPelanggaran
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $queryReward = $qryReward
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $queryKontrol = $qryKontrol->whereIn('kontrol.iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
            $queryTopSkor = $qryTopSkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        }

        $data['jGuru'] = $queryGuru->count();
        $data['jSiswa'] = $querySiswa->count();
        $data['jJenis'] = $querySkor->count();
        $data['jPoin'] = $queryPelanggaran->sum('skor');
        $data['jRew'] = $queryReward->sum('skor');
        // $data['records'] = $queryKontrol->orderByRaw('SUM(kontrol.skor) DESC')
        $data['kontrol'] = $queryKontrol->orderByDesc('totSkor')->limit(10)->get();
        $data['topSkor'] = $queryTopSkor->orderByDesc('jumlah')->limit(10)->get();
        $data['skor'] = Skor::select('id', 'kode', 'deskripsi', 'jenis', 'skor', 'tindakan', 'tipe')->groupBy('id', 'kode', 'deskripsi', 'jenis', 'skor', 'tindakan', 'tipe')->where('jenis', '!=', '')->orderByDesc('id')->limit(10)->get();

        $data['title'] = 'Dashboard';
        $data['klien'] = Perusahaan::where('idx', $IDprsh)->first();
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }
    public function top10skor()
    {
        $level = Auth::user()->level;
        $IDprsh = Auth::user()->idprsh;

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
        $qryTopSkor = Kontrol::select(
            'idskor',
            'jenis',
            'skor',
            'tipe',
            DB::raw('COUNT(idsiswa) AS jumlah')
        )
            ->where('idskor', '>', 0)
            ->where('idsiswa', '>', 0)
            ->groupBy(
                'idskor',
                'jenis',
                'skor',
                'tipe'
            );

        if ($level == 'administrator' and $IDprsh == 0) {
            $queryKontrol = $qryKontrol;
            $queryTopSkor = $qryTopSkor;
        } else if ($level == 'guru') {
            $queryKontrol = $qryKontrol->whereIn('kontrol.iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
            $queryTopSkor = $qryTopSkor->whereIn('iduser', function ($query) use ($IDprsh) {
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
            $queryTopSkor = $qryTopSkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        }

        $topSkor = $queryTopSkor->orderByDesc('jumlah')->limit(10)->get();
        return response()->json([
            'message' => 'success',
            'data' => $topSkor,
        ]);
    }
    public function top10poin()
    {
        $level = Auth::user()->level;
        $IDprsh = Auth::user()->idprsh;

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
        $qryTopSkor = Kontrol::select(
            'idskor',
            'jenis',
            'skor',
            'tipe',
            DB::raw('COUNT(idsiswa) AS jumlah')
        )
            ->where('idskor', '>', 0)
            ->where('idsiswa', '>', 0)
            ->groupBy(
                'idskor',
                'jenis',
                'skor',
                'tipe'
            );

        if ($level == 'administrator' and $IDprsh == 0) {
            $queryKontrol = $qryKontrol;
            $queryTopSkor = $qryTopSkor;
        } else if ($level == 'guru') {
            $queryKontrol = $qryKontrol->whereIn('kontrol.iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
            $queryTopSkor = $qryTopSkor->whereIn('iduser', function ($query) use ($IDprsh) {
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
            $queryTopSkor = $qryTopSkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        }

        $top10poin = $queryKontrol->orderByDesc('totSkor')->limit(10)->get();
        return response()->json([
            'message' => 'success',
            'data' => $top10poin,
        ]);
    }
    public function jenispoin()
    {
        $level = Auth::user()->level;
        $IDprsh = Auth::user()->idprsh;

        $qrySkor = Skor::where('id', '>', 0);

        if ($level == 'administrator' and $IDprsh == 0) {
            $querySkor = $qrySkor;
        } else if ($level == 'guru') {
            $querySkor = $qrySkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        } else {
            $querySkor = $qrySkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        }

        $skor = $querySkor->orderByDesc('id')->limit(5)->get();
        return response()->json([
            'message' => 'success',
            'data' => $skor,
        ]);
    }
    public function totalpoin()
    {
        $level = Auth::user()->level;
        $IDprsh = Auth::user()->idprsh;
        $IDopr = Auth::user()->idopr;
        $IDuser = Auth::user()->idx;

        $qryGuru = Customer::where('level', 'guru');
        $qryWaliKelas = WaliKelas::where('status', 'Aktif');
        $qrySiswa = Customer::where('level', 'siswa');
        $qrySkor = Skor::where('id', '>', 0);
        $qryPelanggaran = Kontrol::where('tipe', 'pelanggaran');
        $qryReward = Kontrol::where('tipe', 'reward');

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
        $qryTopSkor = Kontrol::select(
            'idskor',
            'jenis',
            'skor',
            'tipe',
            DB::raw('COUNT(idsiswa) AS jumlah')
        )
            ->where('idskor', '>', 0)
            ->where('idsiswa', '>', 0)
            ->groupBy(
                'idskor',
                'jenis',
                'skor',
                'tipe'
            );

        if ($level == 'administrator' and $IDprsh == 0) {
            $queryGuru = $qryGuru;
            $queryWaliKelas = $qryWaliKelas;
            $querySiswa = $qrySiswa;
            $querySkor = $qrySkor;
            $queryPelanggaran = $qryPelanggaran;
            $queryReward = $qryReward;
            $queryKontrol = $qryKontrol;
            $queryTopSkor = $qryTopSkor;
        } else if ($level == 'guru') {
            $queryGuru = $qryGuru
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $queryWaliKelas = $qryWaliKelas
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $querySiswa = $qrySiswa
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $querySkor = $qrySkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
            $queryPelanggaran = $qryPelanggaran->where('iduser', $IDuser);
            $queryReward = $qryReward->where('iduser', $IDuser);
            $queryKontrol = $qryKontrol->whereIn('kontrol.iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
            $queryTopSkor = $qryTopSkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        } else {
            $queryGuru = $qryGuru
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $queryWaliKelas = $qryWaliKelas
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $querySiswa = $qrySiswa
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $querySkor = $qrySkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
            $queryPelanggaran = $qryPelanggaran
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $queryReward = $qryReward
                ->whereIn('iduser', function ($query) use ($IDprsh) {
                    $query->select('idx')
                        ->from('user')
                        ->where('idprsh', $IDprsh);
                });
            $queryKontrol = $qryKontrol->whereIn('kontrol.iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
            $queryTopSkor = $qryTopSkor->whereIn('iduser', function ($query) use ($IDprsh) {
                $query->select('idx')
                    ->from('user')
                    ->where('idprsh', $IDprsh);
            });
        }

        $data['jGuru'] = $queryGuru->count();
        $data['jWaliKelas'] = $queryWaliKelas->count();
        $data['jSiswa'] = $querySiswa->count();
        $data['jJenis'] = $querySkor->count();
        $data['jPoin'] = $queryPelanggaran->sum('skor');
        $data['jRew'] = $queryReward->sum('skor');

        $data['klien'] = Perusahaan::where('idx', $IDprsh)->first();
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
