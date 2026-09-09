@extends('layouts.app')

@section('title', 'Kelola Peraturan')

@section('content')

<div
    x-data="{
        deleteOpen: false,
        deleteAction: '',
        deleteLabel: '',
        submitFilter() {
            this.$refs.filterForm.submit();
        },
        openDelete(action, label) {
            this.deleteAction = action;
            this.deleteLabel = label;
            this.deleteOpen = true;
        }
    }"
>
    {{-- Header --}}
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h1 class="font-display text-2xl font-semibold text-ink-900">Kelola Peraturan</h1>
            <p class="text-sm text-ink-500 mt-0.5">{{ $peraturan->total() }} peraturan</p>
        </div>
        <a href="{{ route('admin.peraturan.create') }}"
           class="bg-ink-900 text-paper px-5 py-2.5 rounded-md text-sm font-medium hover:bg-teal-700 transition-colors">
            + Tambah Peraturan
        </a>
    </div>

    <x-flash-message />

    {{-- Filter --}}
    <form
        x-ref="filterForm"
        action="{{ route('admin.peraturan.index') }}"
        method="GET"
        class="bg-white border border-ink-900/10 rounded-lg p-4 mb-6 flex flex-wrap gap-3 items-center">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nomor atau judul..."
            class="flex-1 min-w-[180px] border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass"
            @keydown.enter.prevent="submitFilter()">

        <select
            name="kategori"
            @change="submitFilter()"
            class="border border-ink-900/20 rounded-md pl-3 pr-10 py-2 text-sm bg-white outline-none focus:border-brass">
            <option value="">Semua Kategori</option>
            @foreach ($kategori as $k)
                <option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>
                    {{ $k->nama }}
                </option>
            @endforeach
        </select>

        <select
            name="status"
            @change="submitFilter()"
            class="border border-ink-900/20 rounded-md pl-3 pr-10 py-2 text-sm bg-white outline-none focus:border-brass">
            <option value="">Semua Status</option>
            <option value="berlaku" @selected(request('status') === 'berlaku')>Berlaku</option>
            <option value="diubah" @selected(request('status') === 'diubah')>Diubah</option>
            <option value="dicabut" @selected(request('status') === 'dicabut')>Dicabut</option>
        </select>

        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $direction }}">

        <button type="submit"
                class="bg-ink-900 text-paper px-5 py-2 rounded-md text-sm hover:bg-teal-700 transition-colors">
            Cari
        </button>

        @if (request()->hasAny(['search', 'kategori', 'status']))
            <a href="{{ route('admin.peraturan.index') }}"
               class="text-sm text-ink-500 hover:text-seal transition-colors">
                Reset
            </a>
        @endif
    </form>

    @php
        $sortLink = function (string $field) use ($sort, $direction) {
            $newDirection = ($sort === $field && $direction === 'asc') ? 'desc' : 'asc';
            return request()->fullUrlWithQuery(['sort' => $field, 'direction' => $newDirection]);
        };
    @endphp

    {{-- Tabel --}}
    <div class="bg-white border border-ink-900/10 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[900px]">
                <thead class="bg-paper-alt text-ink-700 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium whitespace-nowrap">Kategori</th>
                        <th class="px-4 py-3 font-medium whitespace-nowrap">
                            <a href="{{ $sortLink('nomor') }}" class="inline-flex items-center gap-1 hover:text-brass">
                                Nomor/Tahun
                                @if ($sort === 'nomor')
                                    <span class="text-brass">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 font-medium">Tentang</th>
                        <th class="px-4 py-3 font-medium whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 font-medium text-center whitespace-nowrap">File</th>
                        <th class="px-4 py-3 font-medium whitespace-nowrap">
                            <a href="{{ $sortLink('views') }}" class="inline-flex items-center gap-1 hover:text-brass">
                                Dilihat
                                @if ($sort === 'views')
                                    <span class="text-brass">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 font-medium whitespace-nowrap">
                            <a href="{{ $sortLink('downloads') }}" class="inline-flex items-center gap-1 hover:text-brass">
                                Diunduh
                                @if ($sort === 'downloads')
                                    <span class="text-brass">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 font-medium whitespace-nowrap">
                            <a href="{{ $sortLink('updated_at') }}" class="inline-flex items-center gap-1 hover:text-brass">
                                Diperbarui
                                @if ($sort === 'updated_at')
                                    <span class="text-brass">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 font-medium text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-ink-900/5">
                    @forelse ($peraturan as $p)
                        <tr class="hover:bg-paper-alt/50 transition-colors">
                            {{-- Kategori --}}
                            <td class="px-4 py-3">
                                <span class="inline-block bg-ink-900 text-paper text-[10px] font-mono uppercase tracking-wider px-2 py-1 rounded">
                                    {{ $p->kategori->singkatan }}
                                </span>
                            </td>

                            {{-- Nomor/Tahun --}}
                            <td class="px-4 py-3 font-mono text-ink-900 whitespace-nowrap">
                                {{ $p->nomor }}/{{ $p->tahun }}
                            </td>

                            {{-- Tentang --}}
                            <td class="px-4 py-3 text-ink-700 max-w-[280px]">
                                <span class="line-clamp-1" title="{{ $p->tentang }}">
                                    {{ $p->tentang }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <x-status-pill :status="$p->status" />
                            </td>

                            {{-- File --}}
                            <td class="px-4 py-3 text-center">
                                @if ($p->file_path)
                                    <span class="inline-flex items-center gap-1 text-emerald-700 text-xs font-medium" title="PDF tersedia">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        PDF
                                    </span>
                                @else
                                    <span class="text-ink-400 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Dilihat --}}
                            <td class="px-4 py-3 font-mono text-ink-700 whitespace-nowrap">
                                {{ number_format($p->views) }}
                            </td>

                            {{-- Diunduh --}}
                            <td class="px-4 py-3 font-mono text-ink-700 whitespace-nowrap">
                                {{ number_format($p->downloads ?? 0) }}
                            </td>

                            {{-- Diperbaharui --}}
                            <td class="px-4 py-3 text-ink-500 text-xs whitespace-nowrap">
                                {{ $p->updated_at->diffForHumans() }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.peraturan.edit', $p) }}"
                                       class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium
                                              text-ink-700 border border-ink-900/15 hover:border-brass hover:text-brass transition-colors">
                                        Edit
                                    </a>
                                    <button
                                        type="button"
                                        @click="openDelete(
                                            '{{ route('admin.peraturan.destroy', $p) }}',
                                            '{{ $p->kategori->singkatan }} No. {{ $p->nomor }}/{{ $p->tahun }}'
                                        )"
                                        class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium
                                               text-rose-700 border border-rose-200 hover:bg-rose-50 transition-colors">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center">
                                <p class="font-medium text-ink-700">Tidak ada data yang cocok</p>
                                <p class="text-sm text-ink-500 mt-1">Coba ubah filter atau reset pencarian.</p>
                                @if (request()->hasAny(['search', 'kategori', 'status']))
                                    <a href="{{ route('admin.peraturan.index') }}"
                                       class="inline-block mt-3 text-sm text-seal hover:underline">
                                        Reset Filter
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($peraturan->hasPages())
        <div class="mt-6">
            {{ $peraturan->withQueryString()->links() }}
        </div>
    @endif

    {{-- Modal hapus --}}
    <div
        x-show="deleteOpen"
        x-cloak
        class="fixed inset-0 z-[80] flex items-center justify-center p-4"
        style="display: none;">
        <div class="absolute inset-0 bg-black/40"
             @click="deleteOpen = false"
             x-show="deleteOpen"
             x-transition.opacity></div>

        <div class="relative bg-white rounded-lg shadow-xl border border-ink-900/10 w-full max-w-md p-6"
             @click.stop
             x-show="deleteOpen"
             x-transition>
            <h3 class="font-semibold text-ink-900 text-lg">Hapus Peraturan?</h3>
            <p class="text-sm text-ink-600 mt-2">
                Data
                <span class="font-medium text-ink-900" x-text="deleteLabel"></span>
                akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.
            </p>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button"
                        @click="deleteOpen = false"
                        class="px-4 py-2 text-sm rounded-md border border-ink-900/15 text-ink-700 hover:bg-paper-alt transition-colors">
                    Batal
                </button>
                <form :action="deleteAction" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 text-sm rounded-md bg-rose-600 text-white hover:bg-rose-700 transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection