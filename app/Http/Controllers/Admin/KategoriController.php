<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::withCount('peraturan')
            ->when(request('search'), function ($q) {
                $q->where('nama', 'like', '%' . request('search') . '%')
                ->orWhere('singkatan', 'like', '%' . request('search') . '%');
            })
            ->orderBy('nama');

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $kategori = $query->latest()->get();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'singkatan' => 'required|string|max:20',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);

        Kategori::create($validated);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'singkatan' => 'required|string|max:20',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);

        $kategori->update($validated);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->peraturan()->exists()) {
            return redirect()
                ->route('admin.kategori.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki peraturan terkait.');
        }

        $kategori->delete();

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}