<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaliKelas extends Model
{
    protected $guarded = ['id'];
    protected $table = 'walikelas';
    public $timestamps = false;

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'idguru');
    }

}
