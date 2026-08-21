@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1 class="font-display text-2xl font-semibold text-ink-900 mb-1">Dashboard</h1>
    <p class="text-ink-500 text-sm mb-8">Selamat datang, {{ Auth::user()->name }}.</p>

    @auth
        @if (Auth::user()->role === 'admin')

            {{-- Ringkasan --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <div class="text-3xl font-display font-semibold text-ink-900">
                        {{ \App\Models\Peraturan::count() }}
                    </div>
                    <div class="text-xs text-ink-500 font-mono uppercase tracking-wide mt-1">Total Peraturan</div>
                </div>
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <div class="text-3xl font-display font-semibold text-ink-900">
                        {{ \App\Models\Kategori::count() }}
                    </div>
                    <div class="text-xs text-ink-500 font-mono uppercase tracking-wide mt-1">Total Kategori</div>
                </div>
                <div class="bg-white border border-ink-900/10 rounded-lg p-5">
                    <div class="text-3xl font-display font-semibold text-ink-900">
                        {{ \App\Models\Peraturan::where('status', 'berlaku')->count() }}
                    </div>
                    <div class="text-xs text-ink-500 font-mono uppercase tracking-wide mt-1">Peraturan Berlaku</div>
                </div>
            </div>

            {{-- Grafik --}}
            <div class="grid md:grid-cols-3 gap-4 mb-10">

                <div class="md:col-span-2 bg-white border border-ink-900/10 rounded-lg p-6">
                    <h3 class="font-medium text-ink-900 mb-4 text-sm">Peraturan per Kategori</h3>
                    <canvas id="chartKategori" height="220"></canvas>
                </div>

                <div class="bg-white border border-ink-900/10 rounded-lg p-6">
                    <h3 class="font-medium text-ink-900 mb-4 text-sm">Status Peraturan</h3>
                    <canvas id="chartStatus" height="220"></canvas>
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
                            <p class="text-sm text-ink-500 mt-1">Tambah, ubah, atau hapus data peraturan</p>
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
                            <p class="text-sm text-ink-500 mt-1">Atur jenis-jenis kategori peraturan</p>
                        </div>
                        <svg class="w-5 h-5 text-ink-500 group-hover:text-brass group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>

            {{-- Script Chart.js --}}
            <script>
                document.addEventListener('DOMContentLoaded', function () {

                    // Bar chart — peraturan per kategori
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

                    // Doughnut chart — tetap sama, ini udah berhasil
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
                                backgroundColor: ['#2F6B4A', '#9C824A', '#A6303A'],
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
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