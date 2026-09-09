@extends('layouts.app')

@section('title', 'Edit Peraturan')

@section('content')

    <nav class="text-sm text-ink-500 mb-4">
        <a href="{{ route('admin.peraturan.index') }}" class="hover:text-ink-900">Kelola Peraturan</a>
        <span class="mx-1">/</span>
        <span class="text-ink-700">Edit</span>
    </nav>

    <h1 class="font-display text-2xl font-semibold text-ink-900 mb-2">Edit Peraturan</h1>
    <p class="text-sm text-ink-500 mb-6 font-mono">
        {{ $peraturan->kategori->singkatan }} No. {{ $peraturan->nomor }}/{{ $peraturan->tahun }}
    </p>

    <form
        action="{{ route('admin.peraturan.update', $peraturan) }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white border border-ink-900/10 rounded-lg p-6 space-y-5 max-w-2xl"
        x-data="{
            fileName: '',
            status: @js(old('status', $peraturan->status))
        }"
    >
        @csrf
        @method('PUT')

        {{-- Kategori --}}
        <div>
            <label for="kategori_id" class="block text-sm font-medium text-ink-700 mb-1.5">
                Kategori <span class="text-rose-600">*</span>
            </label>
            <select
                id="kategori_id"
                name="kategori_id"
                class="w-full border border-ink-900/20 rounded-md pl-3 pr-10 py-2 text-sm bg-white outline-none focus:border-brass @error('kategori_id') border-rose-400 @enderror"
                required>
                <option value="">Pilih kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" @selected(old('kategori_id', $peraturan->kategori_id) == $k->id)>
                        {{ $k->nama }} ({{ $k->singkatan }})
                    </option>
                @endforeach
            </select>
            @error('kategori_id')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor & Tahun --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="nomor" class="block text-sm font-medium text-ink-700 mb-1.5">
                    Nomor <span class="text-rose-600">*</span>
                </label>
                <input
                    type="text"
                    id="nomor"
                    name="nomor"
                    value="{{ old('nomor', $peraturan->nomor) }}"
                    class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm font-mono outline-none focus:border-brass @error('nomor') border-rose-400 @enderror"
                    required>
                @error('nomor')
                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="tahun" class="block text-sm font-medium text-ink-700 mb-1.5">
                    Tahun <span class="text-rose-600">*</span>
                </label>
                <input
                    type="number"
                    id="tahun"
                    name="tahun"
                    value="{{ old('tahun', $peraturan->tahun) }}"
                    min="1900"
                    max="{{ date('Y') }}"
                    class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm font-mono outline-none focus:border-brass @error('tahun') border-rose-400 @enderror"
                    required>
                @error('tahun')
                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Tentang --}}
        <div>
            <label for="tentang" class="block text-sm font-medium text-ink-700 mb-1.5">
                Tentang <span class="text-rose-600">*</span>
            </label>
            <textarea
                id="tentang"
                name="tentang"
                rows="3"
                class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass resize-y @error('tentang') border-rose-400 @enderror"
                required>
                {{ old('tentang', $peraturan->tentang) }}</textarea>
            @error('tentang')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanggal --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="tanggal_penetapan" class="block text-sm font-medium text-ink-700 mb-1.5">
                    Tanggal Penetapan
                </label>
                <input
                    type="date"
                    id="tanggal_penetapan"
                    name="tanggal_penetapan"
                    value="{{ old('tanggal_penetapan', optional($peraturan->tanggal_penetapan)->format('Y-m-d')) }}"
                    class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass @error('tanggal_penetapan') border-rose-400 @enderror">
                @error('tanggal_penetapan')
                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="tanggal_diundangkan" class="block text-sm font-medium text-ink-700 mb-1.5">
                    Tanggal Diundangkan
                </label>
                <input
                    type="date"
                    id="tanggal_diundangkan"
                    name="tanggal_diundangkan"
                    value="{{ old('tanggal_diundangkan', optional($peraturan->tanggal_diundangkan)->format('Y-m-d')) }}"
                    class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass @error('tanggal_diundangkan') border-rose-400 @enderror">
                @error('tanggal_diundangkan')
                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-ink-700 mb-2">
                Status <span class="text-rose-600">*</span>
            </label>
            <div class="flex flex-wrap gap-2">
                @foreach (['berlaku' => 'Berlaku', 'diubah' => 'Diubah', 'dicabut' => 'Dicabut'] as $value => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="{{ $value }}" class="peer sr-only"
                               @checked(old('status', $peraturan->status) === $value)
                               x-model="status">
                        <span @class([
                            'inline-block px-3 py-1.5 text-xs rounded-full border transition',
                            'border-emerald-200 text-emerald-700 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600' => $value === 'berlaku',
                            'border-amber-200 text-amber-700 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500' => $value === 'diubah',
                            'border-rose-200 text-rose-700 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-600' => $value === 'dicabut',
                        ])>
                            {{ $label }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('status')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Sumber --}}
        <div>
            <label for="sumber" class="block text-sm font-medium text-ink-700 mb-1.5">Sumber</label>
            <input
                type="text"
                id="sumber"
                name="sumber"
                value="{{ old('sumber', $peraturan->sumber) }}"
                placeholder="Contoh: LN 2024 (28)"
                class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass @error('sumber') border-rose-400 @enderror">
            @error('sumber')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- File PDF --}}
        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1.5">File PDF</label>

            @if ($peraturan->file_path)
                <div class="mb-3 flex flex-wrap items-center gap-3 text-sm">
                    <span class="inline-flex items-center gap-1.5 text-emerald-700 text-xs font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        File saat ini: {{ basename($peraturan->file_path) }}
                    </span>
                    <a href="{{ asset('storage/'.$peraturan->file_path) }}" target="_blank" rel="noopener"
                       class="text-xs text-seal hover:underline">
                        Lihat
                    </a>
                </div>
            @endif

            <div class="border border-dashed border-ink-900/20 rounded-md px-4 py-6 text-center hover:border-brass transition-colors">
                <input
                    type="file"
                    id="file"
                    name="file"
                    accept=".pdf,application/pdf"
                    class="hidden"
                    @change="fileName = $event.target.files[0]?.name || ''">
                <label for="file" class="cursor-pointer">
                    <div class="text-ink-500 text-sm">
                        <span class="font-medium text-ink-800">
                            {{ $peraturan->file_path ? 'Ganti file PDF' : 'Pilih file PDF' }}
                        </span>
                        <span class="block text-xs mt-1 text-ink-400">Maks. 10 MB · kosongkan jika tidak diganti</span>
                    </div>
                    <p class="text-xs text-ink-600 mt-2 font-mono" x-text="fileName || 'Tidak ada file baru'"></p>
                </label>
            </div>
            @error('file')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-wrap gap-3 pt-2 border-t border-ink-900/5">
            <button type="submit"
                    class="bg-ink-900 text-paper px-6 py-2.5 rounded-md text-sm font-medium hover:bg-teal-700 transition-colors">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.peraturan.index') }}"
               class="border border-ink-900/20 px-6 py-2.5 rounded-md text-sm text-ink-700 hover:bg-paper-alt transition-colors">
                Batal
            </a>
        </div>
    </form>

@endsection