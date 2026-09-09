@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')

<div
    x-data="{
        deleteOpen: false,
        deleteAction: '',
        deleteLabel: '',
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
            <h1 class="font-display text-2xl font-semibold text-ink-900">Kelola Kategori</h1>
            <p class="text-sm text-ink-500 mt-0.5">
                {{ method_exists($kategori, 'total') ? $kategori->total() : $kategori->count() }} kategori
            </p>
        </div>
        <a href="{{ route('admin.kategori.create') }}"
           class="bg-ink-900 text-paper px-5 py-2.5 rounded-md text-sm font-medium hover:bg-teal-700 transition-colors">
            + Tambah Kategori
        </a>
    </div>

    <x-flash-message />

    {{-- Search --}}
    <form action="{{ route('admin.kategori.index') }}" method="GET"
          class="mb-6 flex flex-wrap gap-3 items-center">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama atau singkatan..."
            class="flex-1 min-w-[200px] max-w-sm border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass">
        <button type="submit"
                class="bg-ink-900 text-paper px-5 py-2 rounded-md text-sm hover:bg-teal-700 transition-colors">
            Cari
        </button>
        @if (request('search'))
            <a href="{{ route('admin.kategori.index') }}"
               class="text-sm text-ink-500 hover:text-seal transition-colors">
                Reset
            </a>
        @endif
    </form>

    {{-- Tabel --}}
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
                        <tr class="hover:bg-paper-alt/50 transition-colors">
                            {{-- Nama --}}
                            <td class="px-4 py-3 text-ink-900 font-medium">
                                {{ $k->nama }}
                            </td>

                            {{-- Singkatan --}}
                            <td class="px-4 py-3">
                                <span class="inline-block bg-ink-900 text-paper text-[10px] font-mono uppercase tracking-wider px-2 py-1 rounded">
                                    {{ $k->singkatan }}
                                </span>
                            </td>

                            {{-- Jumlah + link ke filter peraturan --}}
                            <td class="px-4 py-3">
                                <span class="font-mono text-ink-700">{{ $k->peraturan_count }}</span>
                                @if ($k->peraturan_count > 0)
                                    <a href="{{ route('admin.peraturan.index', ['kategori' => $k->id]) }}"
                                       class="ml-2 text-xs text-ink-400 hover:text-seal transition-colors">
                                        Lihat →
                                    </a>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.kategori.edit', $k) }}"
                                       class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium
                                              text-ink-700 border border-ink-900/15 hover:border-brass hover:text-brass transition-colors">
                                        Edit
                                    </a>

                                    @if ($k->peraturan_count > 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium
                                                   text-ink-300 border border-ink-900/10 cursor-not-allowed"
                                            title="Tidak bisa dihapus karena masih ada peraturan">
                                            Hapus
                                        </span>
                                    @else
                                        <button
                                            type="button"
                                            @click="openDelete(
                                                '{{ route('admin.kategori.destroy', $k) }}',
                                                '{{ $k->nama }} ({{ $k->singkatan }})'
                                            )"
                                            class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium
                                                   text-rose-700 border border-rose-200 hover:bg-rose-50 transition-colors">
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center">
                                <p class="font-medium text-ink-700">Belum ada data kategori</p>
                                <p class="text-sm text-ink-500 mt-1">
                                    Tambah kategori pertama untuk mengelompokkan peraturan.
                                </p>
                                <a href="{{ route('admin.kategori.create') }}"
                                   class="inline-block mt-3 text-sm text-seal hover:underline">
                                    + Tambah Kategori
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (method_exists($kategori, 'hasPages') && $kategori->hasPages())
        <div class="mt-6">
            {{ $kategori->withQueryString()->links() }}
        </div>
    @endif

    {{-- Modal delete --}}
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
            <h3 class="font-semibold text-ink-900 text-lg">Hapus Kategori?</h3>
            <p class="text-sm text-ink-600 mt-2">
                Kategori
                <span class="font-medium text-ink-900" x-text="deleteLabel"></span>
                akan dihapus permanen.
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