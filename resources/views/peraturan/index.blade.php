@extends('layouts.app')

@section('title', 'Daftar Peraturan')

@section('content')

<div
    class="flex flex-col md:flex-row gap-6 items-start"
    x-data="{
        loading: false,
        filterOpen: false,
        submitFilter() {
            this.loading = true;
            this.$refs.filterForm.submit();
        }
    }"
    x-effect="
        document.body.classList.toggle('overflow-hidden', filterOpen);
    ">

    {{-- Tombol Filter Mobile --}}
    <div class="md:hidden">
        <button
            type="button"
            @click="filterOpen = true"
            class="w-full flex items-center justify-center gap-2 bg-white border border-ink-900/15 rounded-lg px-4 py-2.5 text-sm font-medium text-ink-800 hover:border-brass transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filter & Urutkan
        </button>
    </div>

    {{-- Overlay Mobile --}}
    <div
        x-show="filterOpen"
        x-transition.opacity
        @click="filterOpen = false"
        class="fixed inset-0 bg-black/40 z-[20] md:hidden"
        :class="filterOpen ? 'pointer-events-auto' : 'pointer-events-none'"
        style="display: none;"
    ></div>

    {{-- Sidebar / Drawer Filter --}}
    <aside
        class="fixed md:static inset-y-0 left-0 z-[20] w-80 md:w-64 md:sticky md:top-20 md:self-start flex-shrink-0
               transform transition-transform duration-300 ease-out
               md:translate-x-0"
        :class="filterOpen
        ? 'translate-x-0 pointer-events-auto'
        : '-translate-x-full pointer-events-none md:translate-x-0 md:pointer-events-auto'"
    >
        <div class="h-full md:h-auto bg-white border-r md:border border-ink-900/10 md:rounded-lg overflow-y-auto">

            {{-- Header Drawer Mobile --}}
            <div class="flex items-center justify-between p-4 border-b border-ink-900/10 md:hidden">
                <h2 class="font-semibold text-ink-900">Filter</h2>
                <button @click="filterOpen = false" class="p-1.5 rounded-md hover:bg-ink-50 text-ink-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form
                x-ref="filterForm"
                action="{{ route('peraturan.index') }}"
                method="GET"
                class="p-4 space-y-5"
            >
                {{-- Cari --}}
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Cari</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nomor / judul..."
                        class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass"
                        @keydown.enter.prevent="submitFilter()"
                    >
                    <p class="text-[11px] text-ink-400 mt-1">Tekan Enter untuk mencari</p>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Kategori</label>
                    <div class="space-y-2 max-h-44 overflow-y-auto p-1">
                        @foreach ($kategori as $kat)
                            <label class="flex items-center gap-2 text-sm text-ink-700 hover:text-ink-900 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="kategori[]"
                                    value="{{ $kat->slug }}"
                                    {{ in_array($kat->slug, (array) request('kategori', [])) ? 'checked' : '' }}
                                    class="rounded border-ink-900/30 text-ink-900 focus:ring-brass"
                                    @change="submitFilter()"
                                >
                                <span class="flex-1">{{ $kat->nama . ' (' . $kat->peraturan_count . ')' }}</span>
                                <!-- <span class="text-xs text-ink-400 font-mono">{{ $kat->peraturan_count }}</span> -->
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Status</label>
                    <div class="flex flex-wrap gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="" class="peer sr-only"
                                   {{ !request('status') ? 'checked' : '' }} @change="submitFilter()">
                            <span class="inline-block px-3 py-1.5 text-xs rounded-full border border-ink-900/20 text-ink-600
                                         peer-checked:bg-ink-900 peer-checked:text-paper peer-checked:border-ink-900 transition">
                                Semua
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="berlaku" class="peer sr-only"
                                   {{ request('status') == 'berlaku' ? 'checked' : '' }} @change="submitFilter()">
                            <span class="inline-block px-3 py-1.5 text-xs rounded-full border border-emerald-200 text-emerald-700
                                         peer-checked:bg-emerald-700 peer-checked:text-white peer-checked:border-emerald-600 transition">
                                Berlaku
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="diubah" class="peer sr-only"
                                   {{ request('status') == 'diubah' ? 'checked' : '' }} @change="submitFilter()">
                            <span class="inline-block px-3 py-1.5 text-xs rounded-full border border-amber-200 text-amber-700
                                         peer-checked:bg-amber-700 peer-checked:text-white peer-checked:border-amber-600 transition">
                                Diubah
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="dicabut" class="peer sr-only"
                                   {{ request('status') == 'dicabut' ? 'checked' : '' }} @change="submitFilter()">
                            <span class="inline-block px-3 py-1.5 text-xs rounded-full border border-rose-200 text-rose-700
                                         peer-checked:bg-rose-700 peer-checked:text-white peer-checked:border-rose-600 transition">
                                Dicabut
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Tahun --}}
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Tahun</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="tahun_dari" value="{{ request('tahun_dari') }}"
                               placeholder="{{ $tahunMin ?? 'Dari' }}"
                               class="w-full border border-ink-900/20 rounded-md px-2 py-2 text-sm outline-none focus:border-brass"
                               @change="submitFilter()">
                        <span class="text-ink-500 text-sm">—</span>
                        <input type="number" name="tahun_sampai" value="{{ request('tahun_sampai') }}"
                               placeholder="{{ $tahunMax ?? 'Sampai' }}"
                               class="w-full border border-ink-900/20 rounded-md px-2 py-2 text-sm outline-none focus:border-brass"
                               @change="submitFilter()">
                    </div>
                    @if ($tahunMin && $tahunMax)
                        <p class="text-xs text-ink-500 mt-1">Data tersedia: {{ $tahunMin }}–{{ $tahunMax }}</p>
                    @endif
                </div>

                {{-- Urutan --}}
                <div>
                    <label class="block text-sm font-medium text-ink-700 mb-2">Urutkan</label>
                    <select name="urutan"
                            class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass"
                            @change="submitFilter()">
                        <option value="terbaru" {{ ($urutan ?? '') == 'terbaru' ? 'selected' : '' }}>Tahun Terbaru</option>
                        <option value="terlama" {{ ($urutan ?? '') == 'terlama' ? 'selected' : '' }}>Tahun Terlama</option>
                        <option value="nomor" {{ ($urutan ?? '') == 'nomor' ? 'selected' : '' }}>Nomor Urut</option>
                    </select>
                </div>

                {{-- Reset --}}
                @if(request()->hasAny(['search', 'kategori', 'status', 'tahun_dari', 'tahun_sampai', 'urutan']))
                    <a href="{{ route('peraturan.index') }}"
                       class="block w-full text-center py-2.5 rounded-lg text-paper text-sm font-medium border border-ink-900/15 bg-ink-900 text-ink-600 hover:bg-teal-700 transition">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>
    </aside>

    {{-- List Peraturan --}}
    <div class="flex-1 min-w-0 min-h-screen">

        <div class="flex flex-wrap justify-between items-center gap-2 mb-4 py-2">
            <h1 class="text-xl font-semibold text-ink-900">Daftar Peraturan</h1>
            <span class="text-sm text-ink-500">{{ $peraturan->total() }} dokumen ditemukan</span>
        </div>

        <div class="space-y-3">
            {{-- Skeleton --}}
            <template x-if="loading">
                <div class="space-y-3">
                    <template x-for="i in 5" :key="i">
                        <div class="bg-white border border-ink-900/10 rounded-lg p-5 animate-pulse">
                            <div class="flex justify-between mb-3">
                                <div class="h-5 w-16 bg-ink-900/10 rounded"></div>
                                <div class="h-5 w-14 bg-ink-900/10 rounded-full"></div>
                            </div>
                            <div class="h-4 w-2/3 bg-ink-900/10 rounded mb-2"></div>
                            <div class="h-3 w-full bg-ink-900/5 rounded mb-1"></div>
                            <div class="h-3 w-4/5 bg-ink-900/5 rounded"></div>
                        </div>
                    </template>
                </div>
            </template>
            <div x-show="!loading" class="space-y-3" x-cloak>
                @forelse ($peraturan as $p)
                    <a href="{{ route('peraturan.show', $p->slug) }}"
                    class="group block bg-white border border-ink-900/10 rounded-lg p-5 transition-all duration-300
                            hover:shadow-md hover:border-brass/60 hover:-translate-y-0.5">

                        {{-- Badge --}}
                        <div class="flex justify-between items-start gap-3 mb-2">
                            <span class="inline-block bg-ink-900 text-paper text-[10px] font-mono uppercase tracking-wider px-2 py-1 rounded">
                                {{ $p->kategori->singkatan }}
                            </span>
                            <x-status-pill :status="$p->status" />
                        </div>

                        {{-- Judul --}}
                        <h3 class="text-lg font-google font-semibold text-ink-900 group-hover:text-teal-700 transition-colors">
                            {{ $p->kategori->singkatan }} No. {{ $p->nomor }} Tahun {{ $p->tahun }}
                        </h3>

                        {{-- Tentang --}}
                        <p class="text-sm text-ink-600 mt-1.5 leading-relaxed line-clamp-2">
                            {{ $p->tentang }}
                        </p>

                        {{-- Meta info --}}
                        <div class="mt-4 pt-3 border-t border-ink-900/5">
                            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-ink-500 font-mono">

                                @if ($p->tanggal_penetapan)
                                    <div class="inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ \Carbon\Carbon::parse($p->tanggal_penetapan)->translatedFormat('d M Y') }}</span>
                                    </div>
                                @endif

                                <div class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>{{ number_format($p->views) }}x dilihat</span>
                                </div>

                                @if ($p->sumber)
                                    <div class="inline-flex items-center gap-1.5 min-w-0">
                                        <svg class="w-3.5 h-3.5 opacity-60 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="truncate" title="{{ $p->sumber }}">{{ $p->sumber }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="bg-white border border-ink-900/10 rounded-lg p-10 text-center">
                        <p class="text-ink-700 font-medium">Tidak ada peraturan yang cocok</p>
                        <p class="text-sm text-ink-500 mt-1">Coba ubah atau hapus beberapa filter.</p>
                        <a href="{{ route('peraturan.index') }}" class="inline-block mt-4 text-sm text-seal hover:underline">
                            Reset Filter
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        @if ($peraturan->hasPages())
            <div class="mt-6">
                {{ $peraturan->withQueryString()->links() }}
            </div>
        @endif

    </div>
</div>

@endsection