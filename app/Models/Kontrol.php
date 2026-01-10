<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontrol extends Model
{
    protected $table = 'kontrol';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function skor()
    {
        return $this->belongsTo(Skor::class, 'idskor');
    }
    public function siswa()
    {
        return $this->belongsTo(Customer::class, 'idsiswa', 'id');
    }
}
