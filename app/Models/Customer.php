<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = '[id]';

    public function wali_murid()
    {
        return $this->hasMany(WaliMurid::class, 'customer_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'customer_id');
    }

    public function guru()
    {
        return $this->hasMany(Guru::class, 'customer_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }
}
