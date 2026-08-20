@extends('layouts.app')

@section('title', 'Edit Peraturan')

@section('content')

    <h1 class="font-display text-2xl font-semibold text-ink-900 mb-6">Edit Peraturan</h1>

    <form action="{{ route('admin.peraturan.update', $peraturan) }}" method="POST" enctype="multipart/form-data"
          class="bg-white border border-ink-900/10 rounded-lg p-6 space-y-5 max-w-2xl">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1.5">Kategori</label>
            <select name="kategori_id" class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id', $peraturan->kategori_id) == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id') <p class="text-seal text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1.5">Nomor</label>
                <input type="text" name="nomor" value="{{ old('nomor', $peraturan->nomor) }}"
                       class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">
                @error('nomor') <p class="text-seal text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1.5">Tahun</label>
                <input type="number" name="tahun" value="{{ old('tahun', $peraturan->tahun) }}"
                       class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">
                @error('tahun') <p class="text-seal text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1.5">Tentang</label>
            <textarea name="tentang" rows="3"
                      class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">{{ old('tentang', $peraturan->tentang) }}</textarea>
            @error('tentang') <p class="text-seal text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1.5">Tanggal Penetapan</label>
                <input type="date" name="tanggal_penetapan" value="{{ old('tanggal_penetapan', $peraturan->tanggal_penetapan) }}"
                       class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1.5">Tanggal Diundangkan</label>
                <input type="date" name="tanggal_diundangkan" value="{{ old('tanggal_diundangkan', $peraturan->tanggal_diundangkan) }}"
                       class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1.5">Status</label>
            <select name="status" class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">
                @foreach (['berlaku', 'diubah', 'dicabut'] as $status)
                    <option value="{{ $status }}" {{ old('status', $peraturan->status) == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1.5">Sumber</label>
            <input type="text" name="sumber" value="{{ old('sumber', $peraturan->sumber) }}"
                   class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1.5">File PDF</label>
            @if ($peraturan->file_path)
                <p class="text-xs text-ink-500 mb-2">File saat ini: {{ basename($peraturan->file_path) }} — upload file baru untuk mengganti.</p>
            @endif
            <input type="file" name="file" accept=".pdf"
                   class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-ink-900 file:text-paper file:text-xs">
            @error('file') <p class="text-seal text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-ink-900 text-paper px-6 py-2.5 rounded-md text-sm font-medium hover:bg-seal transition-colors">
                Perbarui
            </button>
            <a href="{{ route('admin.peraturan.index') }}" class="border border-ink-900/20 px-6 py-2.5 rounded-md text-sm text-ink-700 hover:bg-paper-alt">
                Batal
            </a>
        </div>

    </form>

@endsection