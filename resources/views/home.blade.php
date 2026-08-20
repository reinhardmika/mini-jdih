@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

    {{-- Hero --}}
    <div class="text-center py-12">
        <h1 class="text-4xl font-display font-semibold text-ink-900">
            Jaringan Dokumentasi dan Informasi Hukum
        </h1>
        <p class="mt-3 text-gray-600 max-w-xl mx-auto">
            Kejaksaan Negeri Trenggalek — akses peraturan perundang-undangan dengan mudah dan cepat.
        </p>

        <form action="{{ route('peraturan.index') }}" method="GET" class="mt-6 max-w-lg mx-auto">
            <div class="flex shadow-sm rounded-lg overflow-hidden border-2 border-ink-900/10 transition-colors focus-within:border-brass">
            <input
                type="text"
                name="search"
                placeholder="Cari nomor atau judul peraturan..."
                class="flex-1 px-4 py-3 outline-none font-sans"
            >
            <button type="submit" class="bg-ink-900 text-paper px-6 hover:bg-seal transition-colors duration-300">
                Cari
            </button>
        </div>
        </form>
    </div>

    {{-- Kategori --}}
    <div class="mt-10">
        <h2 class="text-xl font-semibold text-ink-900 mb-4">Kategori Peraturan</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach ($kategori as $k)
                <a href="{{ route('peraturan.index', ['kategori' => $k->slug]) }}"
                    class="group relative bg-white border border-ink-900/10 rounded-lg p-5 text-center overflow-hidden transition-all duration-300 hover:border-brass hover:shadow-lg hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1 bg-brass scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                    <div class="font-display text-2xl font-semibold text-ink-900 group-hover:text-seal transition-colors">
                        {{ $k->singkatan }}
                    </div>
                    <div class="text-xs text-ink-500 mt-1 font-mono">{{ $k->peraturan_count }} dokumen</div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Peraturan terbaru --}}
    <div class="mt-12">
        <h2 class="text-xl font-semibold text-ink-900 mb-4">Peraturan Terbaru</h2>
        <div class="space-y-3">
            @forelse ($terbaru as $peraturan)
                <a href="{{ route('peraturan.show', $peraturan->slug) }}"
                    class="group block bg-white border border-ink-900/10 rounded-lg p-5 transition-all duration-300 hover:border-ink-900/30 hover:shadow-md">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <span class="inline-block bg-ink-900 text-paper text-[10px] font-mono uppercase tracking-wider px-2 py-1 rounded mb-2">
                                {{ $peraturan->kategori->singkatan }}
                            </span>
                            <h3 class="font-mono text-sm font-medium text-ink-900 tracking-tight group-hover:text-seal transition-colors">
                                {{ $peraturan->kategori->singkatan }} No. {{ $peraturan->nomor }}/{{ $peraturan->tahun }}
                            </h3>
                            <p class="text-sm text-ink-700 mt-1.5 leading-relaxed">
                                {{ Str::limit($peraturan->tentang, 110) }}
                            </p>
                        </div>
                        <x-status-badge :status="$peraturan->status" class="flex-shrink-0" />
                    </div>

                    <div class="mt-3 pt-3 border-t border-ink-900/5 flex items-center text-xs text-ink-500 font-mono">
                        <span class="group-hover:text-seal group-hover:translate-x-1 transition-all duration-300 inline-flex items-center gap-1">
                            Lihat detail
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </a>
            @empty
                <p class="text-gray-500">Belum ada data peraturan.</p>
            @endforelse
        </div>
    </div>

@endsection