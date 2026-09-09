@extends('layouts.app')

@section('title', $peraturan->kategori->singkatan . ' No. ' . $peraturan->nomor . '/' . $peraturan->tahun)

@section('content')

    {{-- Breadcrumb --}}
    <nav class="text-sm text-ink-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-ink-900">Beranda</a>
        <span class="mx-1">/</span>
        <a href="{{ route('peraturan.index') }}" class="hover:text-ink-900">Peraturan</a>
        <span class="mx-1">/</span>
        <a href="{{ route('peraturan.index', ['kategori' => [$peraturan->kategori->slug]]) }}" class="hover:text-ink-900">
            {{ $peraturan->kategori->singkatan }}
        </a>
        <span class="mx-1">/</span>
        <span class="text-ink-700">Detail</span>
    </nav>

    {{-- Header --}}
    <div class="bg-white border border-ink-900/10 rounded-lg p-6 mb-6">
        <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="bg-ink-900 text-paper text-xs font-mono uppercase px-2 py-1 rounded">
                {{ $peraturan->kategori->singkatan }}
            </span>
            <x-status-badge :status="$peraturan->status" />
        </div>

        <h1 class="font-display text-2xl font-bold text-ink-900 leading-snug">
            {{ $peraturan->kategori->nama }} Nomor {{ $peraturan->nomor }} Tahun {{ $peraturan->tahun }}
        </h1>
        <p class="text-ink-700 mt-2 leading-relaxed">{{ $peraturan->tentang }}</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        {{-- Kolom kiri --}}
        <div class="md:col-span-2 space-y-6">

            {{-- Tabel Spesifikasi --}}
            <div class="bg-white border border-ink-900/10 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-ink-900/10">
                    <h2 class="font-semibold text-ink-900">Spesifikasi Dokumen</h2>
                </div>

                <dl class="divide-y divide-ink-900/5 text-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 px-6 py-3 bg-paper-alt/50">
                        <dt class="font-medium text-ink-700">Jenis Dokumen</dt>
                        <dd class="sm:col-span-2 text-ink-900">{{ $peraturan->kategori->nama }}</dd>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 px-6 py-3">
                        <dt class="font-medium text-ink-700">Nomor</dt>
                        <dd class="sm:col-span-2 text-ink-900 font-mono">{{ $peraturan->nomor }}</dd>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 px-6 py-3 bg-paper-alt/50">
                        <dt class="font-medium text-ink-700">Tahun Terbit</dt>
                        <dd class="sm:col-span-2 text-ink-900 font-mono">{{ $peraturan->tahun }}</dd>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 px-6 py-3">
                        <dt class="font-medium text-ink-700">Tanggal Penetapan</dt>
                        <dd class="sm:col-span-2 text-ink-900">
                            {{ $peraturan->tanggal_penetapan ? \Carbon\Carbon::parse($peraturan->tanggal_penetapan)->translatedFormat('d F Y') : '-' }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 px-6 py-3 bg-paper-alt/50">
                        <dt class="font-medium text-ink-700">Tanggal Diundangkan</dt>
                        <dd class="sm:col-span-2 text-ink-900">
                            {{ $peraturan->tanggal_diundangkan ? \Carbon\Carbon::parse($peraturan->tanggal_diundangkan)->translatedFormat('d F Y') : '-' }}
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 px-6 py-3">
                        <dt class="font-medium text-ink-700">Status</dt>
                        <dd class="sm:col-span-2">
                            <x-status-badge :status="$peraturan->status" />
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 px-6 py-3 bg-paper-alt/50">
                        <dt class="font-medium text-ink-700">Sumber</dt>
                        <dd class="sm:col-span-2 text-ink-900 break-all">{{ $peraturan->sumber ?? '-' }}</dd>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 px-6 py-3">
                        <dt class="font-medium text-ink-700">Diunduh</dt>
                        <dd class="sm:col-span-2 text-ink-900 font-mono">
                            {{ number_format($peraturan->downloads) }}x
                        </dd>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 px-6 py-3">
                        <dt class="font-medium text-ink-700">Dilihat</dt>
                        <dd class="sm:col-span-2 text-ink-900 font-mono">{{ number_format($peraturan->views) }}x</dd>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 px-6 py-3 bg-paper-alt/50">
                        <dt class="font-medium text-ink-700">Terakhir Diperbarui</dt>
                        <dd class="sm:col-span-2 text-ink-900">
                            {{ $peraturan->updated_at->translatedFormat('d F Y, H:i') }} WIB
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Dokumen PDF --}}
            <div class="bg-white border border-ink-900/10 rounded-lg p-6">
                <h2 class="font-semibold text-ink-900 mb-4">Dokumen Peraturan</h2>

                @if ($peraturan->file_path)
                    @php
                        $fileUrl = asset('storage/' . $peraturan->file_path);
                        $fullPath = storage_path('app/public/' . $peraturan->file_path);
                        $fileSize = file_exists($fullPath) ? round(filesize($fullPath) / 1048576, 2) : null;
                    @endphp

                    {{-- Info file --}}
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-ink-500 font-mono mb-4">
                        <span>PDF</span>
                        @if ($fileSize)
                            <span>{{ $fileSize }} MB</span>
                        @endif
                    </div>

                    <div class="border border-ink-900/10 rounded-md overflow-hidden mb-4 h-[500px] md:h-[700px] bg-ink-50">
                        <iframe src="{{ $fileUrl }}" class="w-full h-full" title="Preview PDF"></iframe>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('peraturan.unduh', $peraturan) }}"
                           class="inline-flex items-center gap-2 bg-ink-900 text-paper px-5 py-2.5 rounded-md text-sm hover:bg-teal-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Unduh PDF
                        </a>
                        <a href="{{ $fileUrl }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 border border-ink-900/20 text-ink-700 px-5 py-2.5 rounded-md text-sm hover:bg-paper-alt transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Buka di Tab Baru
                        </a>
                    </div>
                @else
                    <p class="text-ink-500 text-sm italic">Dokumen belum tersedia.</p>
                @endif
            </div>

            {{-- Peraturan terkait (opsional) --}}
            @if(isset($terkait) && $terkait->count())
                <div class="bg-white border border-ink-900/10 rounded-lg p-6">
                    <h2 class="font-semibold text-ink-900 mb-4">Peraturan Terkait</h2>
                    <div class="space-y-3">
                        @foreach ($terkait as $item)
                            <a href="{{ route('peraturan.show', $item->slug) }}"
                               class="block p-3 rounded-md border border-ink-900/10 hover:border-brass hover:bg-paper-alt transition-colors">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-mono uppercase bg-ink-900 text-paper px-1.5 py-0.5 rounded">
                                        {{ $item->kategori->singkatan }}
                                    </span>
                                    <x-status-badge :status="$item->status" />
                                </div>
                                <p class="text-sm font-medium text-ink-900">
                                    {{ $item->kategori->singkatan }} No. {{ $item->nomor }} Tahun {{ $item->tahun }}
                                </p>
                                <p class="text-xs text-ink-500 mt-0.5 line-clamp-1">{{ $item->tentang }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Sidebar kanan --}}
        <div class="space-y-4 md:sticky md:top-6 md:self-start">

            {{-- Bagikan --}}
            <div x-data="{ copied: false }" class="bg-white border border-ink-900/10 rounded-lg p-5">
                <h3 class="font-semibold text-ink-900 mb-3 text-sm">Bagikan</h3>

                <div class="space-y-2">
                    {{-- Salin tautan --}}
                    <button
                        type="button"
                        @click="
                            navigator.clipboard.writeText(window.location.href);
                            copied = true;
                            setTimeout(() => copied = false, 2000);
                        "
                        class="w-full flex items-center justify-center gap-2 border border-ink-900/20 rounded-md py-2.5 text-sm text-ink-700 hover:bg-paper-alt transition-colors">
                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="copied ? 'Tautan tersalin!' : 'Salin Tautan'"></span>
                    </button>

                    {{-- WhatsApp --}}
                    <a href="https://wa.me/?text={{ urlencode($peraturan->kategori->singkatan . ' No. ' . $peraturan->nomor . ' Tahun ' . $peraturan->tahun . ' — ' . url()->current()) }}"
                       target="_blank"
                       rel="noopener"
                       class="w-full flex items-center justify-center gap-2 border border-ink-900/20 rounded-md py-2.5 text-sm text-ink-700 hover:bg-paper-alt transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Bagikan via WhatsApp
                    </a>
                </div>
            </div>

            <a href="{{ route('peraturan.index') }}"
               class="block text-center border border-ink-900/20 rounded-md py-2.5 text-sm text-ink-700 hover:bg-paper-alt transition-colors">
                ← Kembali ke Daftar
            </a>

        </div>
    </div>

@endsection