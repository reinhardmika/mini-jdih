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
        <span class="text-ink-500">Detail</span>
    </nav>

    {{-- Header --}}
    <div class="bg-white border border-ink-900/10 rounded-lg p-6 mb-6">
        <div class="flex items-center gap-2 mb-3">
            <span class="bg-ink-900 text-paper text-xs font-mono uppercase px-2 py-1 rounded">
                {{ $peraturan->kategori->singkatan }}
            </span>
            <x-status-badge :status="$peraturan->status" />
        </div>

        <h1 class="font-display text-2xl font-bold text-ink-900 leading-snug">
            {{ $peraturan->kategori->nama }} Nomor {{ $peraturan->nomor }} Tahun {{ $peraturan->tahun }}
        </h1>
        <p class="text-ink-700 mt-2">{{ $peraturan->tentang }}</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        {{-- Kolom kiri — Tabel Spesifikasi + PDF --}}
        <div class="md:col-span-2 space-y-6">

            {{-- Tabel Spesifikasi --}}
            <div class="bg-white border border-ink-900/10 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-ink-900/10">
                    <h2 class="font-semibold text-ink-900">Spesifikasi Dokumen</h2>
                </div>
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-ink-900/5">
                        <tr class="bg-paper-alt/50">
                            <td class="px-6 py-3 font-medium text-ink-700 w-1/3 align-top">Judul</td>
                            <td class="px-6 py-3 text-ink-900">
                                {{ $peraturan->kategori->nama }} Nomor {{ $peraturan->nomor }} Tahun {{ $peraturan->tahun }} tentang {{ $peraturan->tentang }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 font-medium text-ink-700 align-top">Jenis Dokumen</td>
                            <td class="px-6 py-3 text-ink-900">{{ $peraturan->kategori->nama }}</td>
                        </tr>
                        <tr class="bg-paper-alt/50">
                            <td class="px-6 py-3 font-medium text-ink-700 align-top">Nomor</td>
                            <td class="px-6 py-3 text-ink-900 font-mono">{{ $peraturan->nomor }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 font-medium text-ink-700 align-top">Tahun Terbit</td>
                            <td class="px-6 py-3 text-ink-900 font-mono">{{ $peraturan->tahun }}</td>
                        </tr>
                        <tr class="bg-paper-alt/50">
                            <td class="px-6 py-3 font-medium text-ink-700 align-top">Tanggal Penetapan</td>
                            <td class="px-6 py-3 text-ink-900">
                                {{ $peraturan->tanggal_penetapan ? \Carbon\Carbon::parse($peraturan->tanggal_penetapan)->translatedFormat('d F Y') : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 font-medium text-ink-700 align-top">Tanggal Diundangkan</td>
                            <td class="px-6 py-3 text-ink-900">
                                {{ $peraturan->tanggal_diundangkan ? \Carbon\Carbon::parse($peraturan->tanggal_diundangkan)->translatedFormat('d F Y') : '-' }}
                            </td>
                        </tr>
                        <tr class="bg-paper-alt/50">
                            <td class="px-6 py-3 font-medium text-ink-700 align-top">Status</td>
                            <td class="px-6 py-3">
                                <x-status-badge :status="$peraturan->status" />
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 font-medium text-ink-700 align-top">Sumber</td>
                            <td class="px-6 py-3 text-ink-900">{{ $peraturan->sumber ?? '-' }}</td>
                        </tr>
                        <tr class="bg-paper-alt/50">
                            <td class="px-6 py-3 font-medium text-ink-700 align-top">Dilihat</td>
                            <td class="px-6 py-3 text-ink-900 font-mono">{{ number_format($peraturan->views) }}x</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 font-medium text-ink-700 align-top">Terakhir Diperbarui</td>
                            <td class="px-6 py-3 text-ink-900">
                                {{ $peraturan->updated_at->translatedFormat('d F Y, H:i') }} WIB
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- PDF Viewer / Download --}}
            <div class="bg-white border border-ink-900/10 rounded-lg p-6">
                <h2 class="font-semibold text-ink-900 mb-4">Dokumen Peraturan</h2>

                @if ($peraturan->file_path)
                    <div class="border border-ink-900/10 rounded-md overflow-hidden mb-4 h-[500px] md:h-[700px]">
                        <iframe src="{{ asset('storage/' . $peraturan->file_path) }}" class="w-full h-full"></iframe>
                    </div>
                    <a href="{{ asset('storage/' . $peraturan->file_path) }}" target="_blank"
                       class="inline-block bg-ink-900 text-paper px-5 py-2.5 rounded-md text-sm hover:bg-seal transition-colors">
                        Unduh PDF
                    </a>
                @else
                    <p class="text-ink-500 text-sm italic">Dokumen belum tersedia.</p>
                @endif
            </div>

        </div>

        {{-- Kolom kanan — Bagikan + Navigasi --}}
        <div class="space-y-4">

            <div x-data="{ copied: false }" class="bg-white border border-ink-900/10 rounded-lg p-5">
                <h3 class="font-semibold text-ink-900 mb-3 text-sm">Bagikan</h3>
                <button
                    @click="
                        navigator.clipboard.writeText(window.location.href);
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                    "
                    class="w-full flex items-center justify-center gap-2 border border-ink-900/20 rounded-md py-2.5 text-sm text-ink-700 hover:bg-paper-alt transition-colors"
                >
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span x-text="copied ? 'Tautan tersalin!' : 'Salin Tautan'"></span>
                </button>
            </div>

            <a href="{{ route('peraturan.index') }}"
               class="block text-center border border-ink-900/20 rounded-md py-2.5 text-sm text-ink-700 hover:bg-paper-alt transition-colors">
                ← Kembali ke Daftar
            </a>

        </div>

    </div>

@endsection