<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $guarded = ['id'];
    public function kontrols()
    {
        return $this->hasMany(Kontrol::class, 'guru_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // shortcut agar langsung bisa akses nama/email/hp
    public function getNamaAttribute()
    {
        return $this->customer?->nama;
    }

    public function getHpAttribute()
    {
        return $this->customer?->hp;
    }

    public function getEmailAttribute()
    {
        return $this->customer?->email;
    }
}
