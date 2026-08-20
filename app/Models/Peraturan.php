<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    protected $table = 'peraturan';

    protected $fillable = [
        'kategori_id', 'nomor', 'tahun', 'tentang', 'slug',
        'tanggal_penetapan', 'tanggal_diundangkan', 'status',
        'views', 'file_path', 'sumber'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }
}