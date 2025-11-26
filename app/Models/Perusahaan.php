<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
