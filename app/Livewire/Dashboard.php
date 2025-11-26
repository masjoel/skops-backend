<?php

namespace App\Livewire;

use App\Models\Guru;
use App\Models\Skor;
use App\Models\Siswa;
use App\Models\Kontrol;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalSiswa;
    public $totalGuru;
    public $totalSkor;
    public $totalKontrol;

    public function mount()
    {
        $this->totalSiswa = Siswa::count();
        $this->totalGuru = Guru::count();
        $this->totalSkor = Skor::count();
        $this->totalKontrol = Kontrol::count();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
