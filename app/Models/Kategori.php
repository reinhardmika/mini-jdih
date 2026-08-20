<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = ['nama', 'singkatan', 'slug'];

    public function peraturan()
    {
        return $this->hasMany(Peraturan::class);
    }
}