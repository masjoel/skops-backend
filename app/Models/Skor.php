<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skor extends Model
{
    protected $guarded = '[id]';

    public function kontrol()
    {
        return $this->hasMany(Kontrol::class, 'skor_id');
    }
}
