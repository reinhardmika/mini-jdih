@extends('layouts.app')

@section('title', 'Kelola Peraturan')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <h1 class="font-display text-2xl font-semibold text-ink-900">Kelola Peraturan</h1>
        <a href="{{ route('admin.peraturan.create') }}"
           class="bg-ink-900 text-paper px-5 py-2.5 rounded-md text-sm font-medium hover:bg-seal transition-colors duration-300">
            + Tambah Peraturan
        </a>
    </div>

    <x-flash-message />

    {{-- Search & Filter --}}
    <form action="{{ route('admin.peraturan.index') }}" method="GET" class="bg-white border border-ink-900/10 rounded-lg p-4 mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor atau judul..."
               class="flex-1 min-w-[200px] border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">

        <select name="kategori" class="border border-ink-900/20 rounded-md px-7 py-2 text-sm">
            <option value="">Semua Kategori</option>
            @foreach ($kategori as $k)
                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama }}
                </option>
            @endforeach
        </select>

        <select name="status" class="border border-ink-900/20 rounded-md px-7 py-2 text-sm">
            <option value="">Semua Status</option>
            <option value="berlaku" {{ request('status') == 'berlaku' ? 'selected' : '' }}>Berlaku</option>
            <option value="diubah" {{ request('status') == 'diubah' ? 'selected' : '' }}>Diubah</option>
            <option value="dicabut" {{ request('status') == 'dicabut' ? 'selected' : '' }}>Dicabut</option>
        </select>

        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $direction }}">

        <button type="submit" class="bg-ink-900 text-paper px-5 py-2 rounded-md text-sm hover:bg-seal transition-colors">
            Cari
        </button>

        @if (request()->hasAny(['search', 'kategori', 'status']))
            <a href="{{ route('admin.peraturan.index') }}" class="flex items-center text-sm text-ink-500 hover:text-seal">
                Reset
            </a>
        @endif
    </form>

    @php
        $sortLink = function ($field) use ($sort, $direction) {
            $newDirection = ($sort === $field && $direction === 'asc') ? 'desc' : 'asc';
            return request()->fullUrlWithQuery(['sort' => $field, 'direction' => $newDirection]);
        };
    @endphp

    <div class="bg-white border border-ink-900/10 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
                <thead class="bg-paper-alt text-ink-700 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">Kategori</th>
                        <th class="px-4 py-3 font-medium">
                            <a href="{{ $sortLink('nomor') }}" class="flex items-center gap-1 hover:text-brass">
                                Nomor/Tahun
                                @if ($sort === 'nomor')
                                    <span class="text-brass">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 font-medium">Tentang</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">
                            <a href="{{ $sortLink('views') }}" class="flex items-center gap-1 hover:text-brass">
                                Dilihat
                                @if ($sort === 'views')
                                    <span class="text-brass">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 font-medium">
                            <a href="{{ $sortLink('updated_at') }}" class="flex items-center gap-1 hover:text-brass">
                                Diperbarui
                                @if ($sort === 'updated_at')
                                    <span class="text-brass">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-900/5">
                    @forelse ($peraturan as $p)
                        <tr class="hover:bg-paper-alt/50">
                            <td class="px-4 py-3">
                                <span class="bg-ink-900 text-paper text-[10px] font-mono uppercase px-2 py-1 rounded">
                                    {{ $p->kategori->singkatan }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-ink-900">{{ $p->nomor }}/{{ $p->tahun }}</td>
                            <td class="px-4 py-3 text-ink-700">{{ Str::limit($p->tentang, 60) }}</td>
                            <td class="px-4 py-3">
                                <x-status-badge :status="$p->status" />
                            </td>
                            <td class="px-4 py-3 font-mono text-ink-700">{{ number_format($p->views) }}</td>
                            <td class="px-4 py-3 text-ink-500 text-xs">{{ $p->updated_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.peraturan.edit', $p) }}"
                                   class="text-xs text-ink-700 hover:text-brass font-medium">Edit</a>
                                <form action="{{ route('admin.peraturan.destroy', $p) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus peraturan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-seal hover:underline font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-ink-500">Tidak ada data yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $peraturan->links() }}
    </div>

@endsection