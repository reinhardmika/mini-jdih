@extends('layouts.app')

@section('title', 'Daftar Peraturan')

@section('content')

    <div class="flex flex-col md:flex-row gap-6">

        {{-- Sidebar Filter --}}
        <aside class="w-full md:w-64 flex-shrink-0">
            <form action="{{ route('peraturan.index') }}" method="GET" class="bg-white border border-gray-200 rounded-lg p-4 space-y-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nomor / judul..."
                        class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none transition-colors focus:border-brass"
                    >
                </div>

                {{-- Multi-kategori pakai checkbox --}}
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Kategori</label>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach ($kategori as $kat)
                            <label class="flex items-center gap-2 text-sm text-ink-700">
                                <input
                                    type="checkbox"
                                    name="kategori[]"
                                    value="{{ $kat->slug }}"
                                    {{ in_array($kat->slug, request('kategori', [])) ? 'checked' : '' }}
                                    class="rounded border-ink-900/30 text-ink-900 focus:ring-brass"
                                >
                                {{ $kat->nama }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none transition-colors focus:border-brass">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->slug }}" {{ request('kategori') == $k->slug ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div> -->

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none transition-colors focus:border-brass">
                        <option value="">Semua Status</option>
                        <option value="berlaku" {{ request('status') == 'berlaku' ? 'selected' : '' }}>Berlaku</option>
                        <option value="diubah" {{ request('status') == 'diubah' ? 'selected' : '' }}>Diubah</option>
                        <option value="dicabut" {{ request('status') == 'dicabut' ? 'selected' : '' }}>Dicabut</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Tahun</label>
                    <div class="flex items-center gap-2">
                        <input
                            type="number"
                            name="tahun_dari"
                            value="{{ request('tahun_dari') }}"
                            placeholder="{{ $tahunMin ?? 'Dari' }}"
                            class="w-full border border-ink-900/20 rounded-md px-2 py-2 text-sm outline-none focus:border-brass"
                        >
                        <span class="text-ink-500 text-sm">—</span>
                        <input
                            type="number"
                            name="tahun_sampai"
                            value="{{ request('tahun_sampai') }}"
                            placeholder="{{ $tahunMax ?? 'Sampai' }}"
                            class="w-full border border-ink-900/20 rounded-md px-2 py-2 text-sm outline-none focus:border-brass"
                        >
                    </div>
                    @if ($tahunMin && $tahunMax)
                        <p class="text-xs text-ink-500 mt-1">Data tersedia: {{ $tahunMin }}–{{ $tahunMax }}</p>
                    @endif
                </div>

                {{-- Urutan --}}
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Urutkan</label>
                    <select name="urutan" class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm">
                        <option value="terbaru" {{ $urutan == 'terbaru' ? 'selected' : '' }}>Tahun Terbaru</option>
                        <option value="terlama" {{ $urutan == 'terlama' ? 'selected' : '' }}>Tahun Terlama</option>
                        <option value="nomor" {{ $urutan == 'nomor' ? 'selected' : '' }}>Nomor Urut</option>
                    </select>
                </div>
                
                <button type="submit" class="w-full bg-ink-900 text-paper py-2.5 rounded-md text-sm font-medium hover:bg-seal transition-colors duration-300">
                    Terapkan Filter
                </button>

                @if(request()->hasAny(['search', 'kategori', 'status']))
                    <a href="{{ route('peraturan.index') }}" class="block text-center text-sm text-gray-500 hover:text-ink-900">
                        Reset Filter
                    </a>
                @endif

            </form>
        </aside>

        {{-- List Peraturan --}}
        <div class="flex-1">

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold text-ink-900">Daftar Peraturan</h1>
                <span class="text-sm text-gray-500">{{ $peraturan->total() }} dokumen ditemukan</span>
            </div>

            <div class="space-y-3">
                @forelse ($peraturan as $p)
                    <a href="{{ route('peraturan.show', $p->slug) }}"
                       class="block bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <span class="inline-block bg-ink-900 text-white text-xs px-2 py-1 rounded mb-2">
                                    {{ $p->kategori->singkatan }}
                                </span>
                                <h3 class="font-medium text-ink-900">
                                    {{ $p->kategori->singkatan }} No. {{ $p->nomor }} Tahun {{ $p->tahun }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($p->tentang, 120) }}</p>
                            </div>
                            <x-status-badge :status="$p->status" />
                        </div>
                    </a>
                @empty
                    <div class="bg-white border border-gray-200 rounded-lg p-8 text-center text-gray-500">
                        Tidak ada peraturan yang cocok dengan filter.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $peraturan->links() }}
            </div>

        </div>

    </div>

@endsection