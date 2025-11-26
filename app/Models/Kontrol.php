<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontrol extends Model
{
    protected $guarded = ['id'];

    public function skor()
    {
        return $this->belongsTo(Skor::class, 'skor_id');
    }
}
