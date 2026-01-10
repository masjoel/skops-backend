<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skor extends Model
{
    protected $table = 'skor';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function kontrol()
    {
        return $this->hasMany(Kontrol::class, 'skor_id');
    }
}
