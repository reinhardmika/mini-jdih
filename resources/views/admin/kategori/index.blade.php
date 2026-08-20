@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <h1 class="font-display text-2xl font-semibold text-ink-900">Kelola Kategori</h1>
        <a href="{{ route('admin.kategori.create') }}"
           class="bg-ink-900 text-paper px-5 py-2.5 rounded-md text-sm font-medium hover:bg-seal transition-colors duration-300">
            + Tambah Kategori
        </a>
    </div>

    <x-flash-message />

    <form action="{{ route('admin.kategori.index') }}" method="GET" class="mb-6 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori..."
            class="flex-1 max-w-sm border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">
        <button type="submit" class="bg-ink-900 text-paper px-5 py-2 rounded-md text-sm hover:bg-seal transition-colors">
            Cari
        </button>
    </form>

    <div class="bg-white border border-ink-900/10 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[600px]">
                <thead class="bg-paper-alt text-ink-700 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">Nama</th>
                        <th class="px-4 py-3 font-medium">Singkatan</th>
                        <th class="px-4 py-3 font-medium">Jumlah Peraturan</th>
                        <th class="px-4 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-900/5">
                    @forelse ($kategori as $k)
                        <tr class="hover:bg-paper-alt/50">
                            <td class="px-4 py-3 text-ink-900">{{ $k->nama }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-ink-900 text-paper text-[10px] font-mono uppercase px-2 py-1 rounded">
                                    {{ $k->singkatan }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-ink-700">{{ $k->peraturan_count }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.kategori.edit', $k) }}"
                                class="text-xs text-ink-700 hover:text-brass font-medium">Edit</a>
                                <form action="{{ route('admin.kategori.destroy', $k) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-seal hover:underline font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-ink-500">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection