<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Peraturan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PeraturanSeeder extends Seeder
{
    public function run()
    {
        $kategoriIds = Kategori::pluck('id')->toArray();
        $status = ['berlaku', 'dicabut', 'diubah'];

        for ($i = 1; $i <= 20; $i++) {
            $tentang = "Tentang " . fake()->words(5, true);
            $tahun = rand(2015, 2024);
            $nomor = rand(1, 50);

            Peraturan::create([
                'kategori_id' => fake()->randomElement($kategoriIds),
                'nomor' => $nomor,
                'tahun' => $tahun,
                'tentang' => ucfirst($tentang),
                'slug' => Str::slug("nomor-$nomor-tahun-$tahun-$tentang") . '-' . $i,
                'tanggal_penetapan' => fake()->date(),
                'tanggal_diundangkan' => fake()->date(),
                'status' => fake()->randomElement($status),
                'sumber' => "Lembaran Negara No. " . rand(1, 100) . " Tahun $tahun",
            ]);
        }
    }
}