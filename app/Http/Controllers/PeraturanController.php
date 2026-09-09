<?php

namespace App\Http\Controllers;
use App\Models\Kategori;
use App\Models\Peraturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PeraturanController extends Controller
{
    public function home()
    {
        $kategori = Kategori::withCount('peraturan')->get();
        $terbaru = Peraturan::with('kategori')->latest()->take(6)->get();
        $stats = [
            'total'   => Peraturan::count(),
            'berlaku' => Peraturan::where('status', 'berlaku')->count(),
            'diubah'  => Peraturan::where('status', 'diubah')->count(),
            'dicabut' => Peraturan::where('status', 'dicabut')->count(),
        ];
        return view('home', compact('kategori', 'terbaru', 'stats'));
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
        $kategori = Kategori::withCount('peraturan')->orderBy('nama')->get();

        $tahunMin = Peraturan::min('tahun');
        $tahunMax = Peraturan::max('tahun');

        return view('peraturan.index', compact('peraturan', 'kategori', 'urutan', 'tahunMin', 'tahunMax'));
    }

    public function show(Peraturan $peraturan)
    {
        $viewedKey = 'viewed_peraturan_' . $peraturan->id;
        $terkait = Peraturan::with('kategori')
            ->where('kategori_id', $peraturan->kategori_id)
            ->where('id', '!=', $peraturan->id)
            ->latest('tahun')
            ->limit(5)
            ->get();

        if (!session()->has($viewedKey)) {
            $peraturan->increment('views');
            session()->put($viewedKey, true);
        }
        // $peraturan->increment('views');

        return view('peraturan.show', compact('peraturan', 'terkait'));
    }
    
    public function unduh(Peraturan $peraturan)
    {
        if (!$peraturan->file_path || !Storage::disk('public')->exists($peraturan->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $peraturan->increment('downloads');

        return Storage::disk('public')->download(
            $peraturan->file_path,
            basename($peraturan->file_path)
        );
    }

    public function suggest(Request $request)
    {
        $q = $request->get('q');

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $data = Peraturan::with('kategori')
            ->where('nomor', 'like', "%{$q}%")
            ->orWhere('tahun', 'like', "%{$q}%")
            ->orWhere('tentang', 'like', "%{$q}%")
            ->limit(6)
            ->get()
            ->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'nomor_lengkap' => $item->kategori->singkatan . ' No. ' . $item->nomor . '/' . $item->tahun,
                    'tentang'       => $item->tentang,
                    'url'           => route('peraturan.show', $item->slug),
                ];
            });

        return response()->json($data);
    }
}
