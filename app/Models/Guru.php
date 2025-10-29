<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $guarded = '[id]';
    public function kontrols()
    {
        return $this->hasMany(Kontrol::class, 'guru_id');
    }

    public function wali_kelas()
    {
        return $this->hasMany(WaliKelas::class, 'guru_id');
    }
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'guru_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
