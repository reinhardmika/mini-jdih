@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')

    <h1 class="font-display text-2xl font-semibold text-ink-900 mb-6">Tambah Kategori</h1>

    <form action="{{ route('admin.kategori.store') }}" method="POST"
          class="bg-white border border-ink-900/10 rounded-lg p-6 space-y-5 max-w-lg">
        @csrf

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1.5">Nama Kategori</label>
            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="misal: Undang-Undang"
                   class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">
            @error('nama') <p class="text-seal text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1.5">Singkatan</label>
            <input type="text" name="singkatan" value="{{ old('singkatan') }}" placeholder="misal: UU"
                   class="w-full border border-ink-900/20 rounded-md px-3 py-2 text-sm focus:border-brass outline-none">
            @error('singkatan') <p class="text-seal text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-ink-900 text-paper px-6 py-2.5 rounded-md text-sm font-medium hover:bg-seal transition-colors">
                Simpan
            </button>
            <a href="{{ route('admin.kategori.index') }}" class="border border-ink-900/20 px-6 py-2.5 rounded-md text-sm text-ink-700 hover:bg-paper-alt">
                Batal
            </a>
        </div>

    </form>

@endsection