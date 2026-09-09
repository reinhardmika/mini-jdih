@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')

    <nav class="text-sm text-ink-500 mb-4">
        <a href="{{ route('admin.kategori.index') }}" class="hover:text-ink-900">Kelola Kategori</a>
        <span class="mx-1">/</span>
        <span class="text-ink-700">Tambah</span>
    </nav>

    <h1 class="font-display text-2xl font-semibold text-ink-900 mb-6">Tambah Kategori</h1>

    <form
        action="{{ route('admin.kategori.store') }}"
        method="POST"
        class="bg-white border border-ink-900/10 rounded-lg p-6 space-y-5 max-w-lg"
        x-data="{
            nama: @js(old('nama', '')),
            singkatan: @js(old('singkatan', '')),
            _lastAuto: '',
            autoSingkatan() {
                if (this.singkatan.length > 0 && this.singkatan !== this._lastAuto) return;
                const words = this.nama.trim().split(/\s+/).filter(Boolean);
                let result = words.map(w => w.charAt(0).toUpperCase()).join('');
                if (result.length < 2 && words[0]) {
                    result = words[0].substring(0, 3).toUpperCase();
                }
                this.singkatan = result.substring(0, 6);
                this._lastAuto = this.singkatan;
            }
        }"
    >
        @csrf

        <div>
            <label for="nama" class="block text-sm font-medium text-ink-700 mb-1.5">
                Nama Kategori <span class="text-rose-600">*</span>
            </label>
            <input
                type="text"
                id="nama"
                name="nama"
                x-model="nama"
                @input="autoSingkatan()"
                value="{{ old('nama') }}"
                placeholder="Contoh: Undang-Undang"
                class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass @error('nama') border-rose-400 @enderror"
                required
            >
            @error('nama')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="singkatan" class="block text-sm font-medium text-ink-700 mb-1.5">
                Singkatan <span class="text-rose-600">*</span>
            </label>
            <input
                type="text"
                id="singkatan"
                name="singkatan"
                x-model="singkatan"
                value="{{ old('singkatan') }}"
                placeholder="Contoh: UU"
                maxlength="10"
                class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm font-mono uppercase outline-none focus:border-brass @error('singkatan') border-rose-400 @enderror"
                required
            >
            <p class="text-[11px] text-ink-400 mt-1">
                Otomatis dari nama. Bisa diubah manual. Max 10 karakter.
            </p>
            @error('singkatan')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-md border border-ink-900/10 bg-paper-alt/50 px-4 py-3">
            <p class="text-xs text-ink-500 mb-2">Preview badge</p>
            <span
                class="inline-block bg-ink-900 text-paper text-[10px] font-mono uppercase tracking-wider px-2 py-1 rounded"
                x-text="singkatan || '…'"
            ></span>
        </div>

        <div class="flex flex-wrap gap-3 pt-2 border-t border-ink-900/5">
            <button type="submit"
                    class="bg-ink-900 text-paper px-6 py-2.5 rounded-md text-sm font-medium hover:bg-teal-700 transition-colors">
                Simpan
            </button>
            <a href="{{ route('admin.kategori.index') }}"
               class="border border-ink-900/20 px-6 py-2.5 rounded-md text-sm text-ink-700 hover:bg-paper-alt transition-colors">
                Batal
            </a>
        </div>
    </form>

@endsection