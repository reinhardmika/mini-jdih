<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama' => 'Undang-Undang', 'singkatan' => 'UU'],
            ['nama' => 'Peraturan Pemerintah', 'singkatan' => 'PP'],
            ['nama' => 'Peraturan Presiden', 'singkatan' => 'Perpres'],
            ['nama' => 'Peraturan Menteri', 'singkatan' => 'Permen'],
            ['nama' => 'Peraturan Daerah', 'singkatan' => 'Perda'],
        ];

        foreach ($data as $item) {
            Kategori::create([
                'nama' => $item['nama'],
                'singkatan' => $item['singkatan'],
                'slug' => Str::slug($item['nama']),
            ]);
        }
    }
}