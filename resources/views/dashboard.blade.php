@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1 class="font-display text-2xl font-semibold text-ink-900 mb-1">Dashboard</h1>
    <p class="text-ink-500 text-sm mb-8">Selamat datang, {{ Auth::user()->name }}.</p>

    @auth
        @if (Auth::user()->role === 'admin')

            {{-- Kartu statistik --}}
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <div class="text-2xl md:text-3xl font-display font-semibold text-ink-900">{{ $stats['total'] }}</div>
                    <div class="text-[10px] md:text-xs text-ink-500 font-mono uppercase tracking-wide mt-1">Total Peraturan</div>
                </div>
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <div class="text-2xl md:text-3xl font-display font-semibold text-ink-900">{{ $stats['kategori'] }}</div>
                    <div class="text-[10px] md:text-xs text-ink-500 font-mono uppercase tracking-wide mt-1">Kategori</div>
                </div>
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <div class="text-2xl md:text-3xl font-display font-semibold text-emerald-700">{{ $stats['berlaku'] }}</div>
                    <div class="text-[10px] md:text-xs text-ink-500 font-mono uppercase tracking-wide mt-1">Berlaku</div>
                </div>
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <div class="text-2xl md:text-3xl font-display font-semibold text-amber-600">{{ $stats['diubah'] }}</div>
                    <div class="text-[10px] md:text-xs text-ink-500 font-mono uppercase tracking-wide mt-1">Diubah</div>
                </div>
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <div class="text-2xl md:text-3xl font-display font-semibold text-rose-600">{{ $stats['dicabut'] }}</div>
                    <div class="text-[10px] md:text-xs text-ink-500 font-mono uppercase tracking-wide mt-1">Dicabut</div>
                </div>
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <div class="text-2xl md:text-3xl font-display font-semibold text-ink-900">{{ number_format($stats['views']) }}</div>
                    <div class="text-[10px] md:text-xs text-ink-500 font-mono uppercase tracking-wide mt-1">Total Views</div>
                </div>
            </div>

            {{-- Chart --}}
            <div class="grid md:grid-cols-3 gap-4 mb-8">
                <div class="md:col-span-2 bg-white border border-ink-900/10 rounded-lg p-6">
                    <h3 class="font-medium text-ink-900 mb-4 text-sm">Peraturan per Kategori</h3>
                    <canvas id="chartKategori" height="220"></canvas>
                </div>
                <div class="bg-white border border-ink-900/10 rounded-lg p-6">
                    <h3 class="font-medium text-ink-900 mb-4 text-sm">Status Peraturan</h3>
                    <canvas id="chartStatus" height="220"></canvas>
                </div>
            </div>

            {{-- 3 kolom: terbaru, tanpa PDF, top views --}}
            <div class="grid md:grid-cols-3 gap-4 mb-10">

                {{-- Aktivitas terbaru --}}
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <h3 class="font-medium text-ink-900 text-sm mb-4">Baru Diperbarui</h3>
                    <div class="space-y-3">
                        @forelse ($terbaru as $p)
                            <a href="{{ route('admin.peraturan.edit', $p) }}"
                               class="block group">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-ink-900 group-hover:text-seal truncate">
                                            {{ $p->kategori->singkatan }} {{ $p->nomor }}/{{ $p->tahun }}
                                        </p>
                                        <p class="text-xs text-ink-500 truncate">{{ $p->tentang }}</p>
                                    </div>
                                    <span class="text-[10px] text-ink-400 whitespace-nowrap">
                                        {{ $p->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-ink-500">Belum ada data.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Peringatan tanpa PDF --}}
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-medium text-ink-900 text-sm">Tanpa File PDF</h3>
                        @if ($stats['tanpa_pdf'] > 0)
                            <span class="text-[10px] font-mono bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full">
                                {{ $stats['tanpa_pdf'] }}
                            </span>
                        @endif
                    </div>
                    <div class="space-y-3">
                        @forelse ($tanpaPdf as $p)
                            <a href="{{ route('admin.peraturan.edit', $p) }}"
                               class="block group">
                                <p class="text-sm font-medium text-ink-900 group-hover:text-seal">
                                    {{ $p->kategori->singkatan }} {{ $p->nomor }}/{{ $p->tahun }}
                                </p>
                                <p class="text-xs text-ink-500 truncate">{{ $p->tentang }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-emerald-700">Semua peraturan sudah punya PDF.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Top views --}}
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <h3 class="font-medium text-ink-900 text-sm mb-4">Paling Banyak Dilihat</h3>
                    <div class="space-y-3">
                        @forelse ($topViews as $i => $p)
                            <a href="{{ route('peraturan.show', $p->slug) }}"
                               target="_blank"
                               class="flex items-center gap-3 group">
                                <span class="text-xs font-mono text-ink-400 w-4">{{ $i + 1 }}</span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-ink-900 group-hover:text-seal truncate">
                                        {{ $p->kategori->singkatan }} {{ $p->nomor }}/{{ $p->tahun }}
                                    </p>
                                </div>
                                <span class="text-xs font-mono text-ink-500">{{ number_format($p->views) }}</span>
                            </a>
                        @empty
                            <p class="text-sm text-ink-500">Belum ada data.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Menu kelola --}}
            <h2 class="font-display text-lg font-semibold text-ink-900 mb-4">Kelola Data</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <a href="{{ route('admin.peraturan.index') }}"
                   class="group bg-white border border-ink-900/10 rounded-lg p-6 hover:border-brass hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-ink-900 group-hover:text-seal transition-colors">Kelola Peraturan</h3>
                            <p class="text-sm text-ink-500 mt-1">{{ $stats['total'] }} peraturan tersimpan</p>
                        </div>
                        <svg class="w-5 h-5 text-ink-500 group-hover:text-brass group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
                <a href="{{ route('admin.kategori.index') }}"
                   class="group bg-white border border-ink-900/10 rounded-lg p-6 hover:border-brass hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-ink-900 group-hover:text-seal transition-colors">Kelola Kategori</h3>
                            <p class="text-sm text-ink-500 mt-1">{{ $stats['kategori'] }} kategori aktif</p>
                        </div>
                        <svg class="w-5 h-5 text-ink-500 group-hover:text-brass group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    new Chart(document.getElementById('chartKategori'), {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($kategoriLabels) !!},
                            datasets: [{
                                label: 'Jumlah Peraturan',
                                data: {!! json_encode($kategoriData) !!},
                                backgroundColor: '#14213D',
                                borderRadius: 4,
                                maxBarThickness: 48,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
                            }
                        }
                    });

                    new Chart(document.getElementById('chartStatus'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Berlaku', 'Diubah', 'Dicabut'],
                            datasets: [{
                                data: [
                                    {{ $statusChart['berlaku'] }},
                                    {{ $statusChart['diubah'] }},
                                    {{ $statusChart['dicabut'] }}
                                ],
                                backgroundColor: ['#059669', '#D97706', '#E11D48'],
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { boxWidth: 12, font: { size: 11 } }
                                }
                            }
                        }
                    });
                });
            </script>

        @else
            <div class="bg-white border border-ink-900/10 rounded-lg p-6">
                <p class="text-ink-700">Akun kamu belum memiliki akses admin. Hubungi administrator sistem untuk mendapatkan akses kelola data.</p>
            </div>
        @endif
    @endauth

@endsection