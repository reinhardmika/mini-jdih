<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peraturan;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PeraturanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peraturan::with('kategori');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('tentang', 'like', '%' . $request->search . '%')
                ->orWhere('nomor', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['tahun', 'nomor', 'views', 'created_at', 'updated_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');

        $peraturan = $query->paginate(10)->withQueryString();
        $kategori = Kategori::all();

        return view('admin.peraturan.index', compact('peraturan', 'kategori', 'sort', 'direction'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.peraturan.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nomor' => 'required|string|max:50',
            'tahun' => 'required|integer|min:1900|max:' . date('Y'),
            'tentang' => 'required|string',
            'tanggal_penetapan' => 'nullable|date',
            'tanggal_diundangkan' => 'nullable|date',
            'status' => 'required|in:berlaku,dicabut,diubah',
            'sumber' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:10240', // maksimal 10MB
        ]);

        $validated['slug'] = Str::slug($validated['nomor'] . '-' . $validated['tahun'] . '-' . $validated['tentang']) . '-' . uniqid();

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('peraturan', 'public');
        }

        Peraturan::create($validated);

        return redirect()->route('admin.peraturan.index')->with('success', 'Peraturan berhasil ditambahkan.');
    }

    public function edit(Peraturan $peraturan)
    {
        $kategori = Kategori::all();
        return view('admin.peraturan.edit', compact('peraturan', 'kategori'));
    }

    public function update(Request $request, Peraturan $peraturan)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nomor' => 'required|string|max:50',
            'tahun' => 'required|integer|min:1900|max:' . date('Y'),
            'tentang' => 'required|string',
            'tanggal_penetapan' => 'nullable|date',
            'tanggal_diundangkan' => 'nullable|date',
            'status' => 'required|in:berlaku,dicabut,diubah',
            'sumber' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file')) {
            if ($peraturan->file_path) {
                Storage::disk('public')->delete($peraturan->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('peraturan', 'public');
        }

        $peraturan->update($validated);

        return redirect()->route('admin.peraturan.index')->with('success', 'Peraturan berhasil diperbarui.');
    }

    public function destroy(Peraturan $peraturan)
    {
        if ($peraturan->file_path) {
            Storage::disk('public')->delete($peraturan->file_path);
        }
        $peraturan->delete();

        return redirect()->route('admin.peraturan.index')->with('success', 'Peraturan berhasil dihapus.');
    }
}