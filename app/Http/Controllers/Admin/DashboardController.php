<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peraturan;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return view('dashboard');
        }

        $stats = [
            'total'     => Peraturan::count(),
            'kategori'  => Kategori::count(),
            'berlaku'   => Peraturan::where('status', 'berlaku')->count(),
            'diubah'    => Peraturan::where('status', 'diubah')->count(),
            'dicabut'   => Peraturan::where('status', 'dicabut')->count(),
            'views'     => (int) Peraturan::sum('views'),
            'downloads' => (int) Peraturan::sum('downloads'),
            'tanpa_pdf' => Peraturan::whereNull('file_path')->orWhere('file_path', '')->count(),
        ];

        $kategoriChart = Kategori::withCount('peraturan')->orderBy('nama')->get();
        $kategoriLabels = $kategoriChart->pluck('singkatan');
        $kategoriData   = $kategoriChart->pluck('peraturan_count');

        $statusChart = [
            'berlaku' => $stats['berlaku'],
            'diubah'  => $stats['diubah'],
            'dicabut' => $stats['dicabut'],
        ];

        $terbaru = Peraturan::with('kategori')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        $tanpaPdf = Peraturan::with('kategori')
            ->where(function ($q) {
                $q->whereNull('file_path')->orWhere('file_path', '');
            })
            ->latest('updated_at')
            ->limit(5)
            ->get();

        $topViews = Peraturan::with('kategori')
            ->orderByDesc('views')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'kategoriLabels',
            'kategoriData',
            'statusChart',
            'terbaru',
            'tanpaPdf',
            'topViews'
        ));
    }
}