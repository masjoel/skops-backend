<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';
    protected $primaryKey = 'idx';
    protected $guarded = ['idx'];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
