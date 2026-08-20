<?php

namespace App\Http\Controllers;
use App\Models\Kategori;
use App\Models\Peraturan;
use Illuminate\Http\Request;

class PeraturanController extends Controller
{
    public function home()
    {
        $kategori = Kategori::withCount('peraturan')->get();
        $terbaru = Peraturan::with('kategori')->latest()->take(6)->get();

        return view('home', compact('kategori', 'terbaru'));
    }

    public function index(Request $request)
    {
        $query = Peraturan::with('kategori');

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->whereIn('slug', (array) $request->kategori);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('tentang', 'like', '%' . $request->search . '%')
                ->orWhere('nomor', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('tahun_dari')) {
            $query->where('tahun', '>=', $request->tahun_dari);
        }

        if ($request->filled('tahun_sampai')) {
            $query->where('tahun', '<=', $request->tahun_sampai);
        }

        $urutan = $request->get('urutan', 'terbaru');

        match ($urutan) {
            'terlama' => $query->orderBy('tahun', 'asc')->orderBy('nomor', 'asc'),
            'nomor' => $query->orderBy('nomor', 'asc'),
            default => $query->orderBy('tahun', 'desc')->orderBy('created_at', 'desc'),
        };

        $peraturan = $query->latest()->paginate(10)->withQueryString();
        $kategori = Kategori::all();

        $tahunMin = Peraturan::min('tahun');
        $tahunMax = Peraturan::max('tahun');

        return view('peraturan.index', compact('peraturan', 'kategori', 'urutan', 'tahunMin', 'tahunMax'));
    }

    public function show(Peraturan $peraturan)
    {
        // $viewedKey = 'viewed_peraturan_' . $peraturan->id;

        // if (!session()->has($viewedKey)) {
        //     $peraturan->increment('views');
        //     session()->put($viewedKey, true);
        // }
        $peraturan->increment('views');

        return view('peraturan.show', compact('peraturan'));
    }
}
