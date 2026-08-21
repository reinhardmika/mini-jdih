<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeraturanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PeraturanController::class, 'home'])->name('home');
Route::get('/peraturan', [PeraturanController::class, 'index'])->name('peraturan.index');
Route::get('/peraturan/{peraturan:slug}', [PeraturanController::class, 'show'])->name('peraturan.show');

Route::get('/dashboard', function () {
    $kategoriChart = \App\Models\Kategori::withCount('peraturan')->get();

    $kategoriLabels = $kategoriChart->pluck('singkatan');
    $kategoriData = $kategoriChart->pluck('peraturan_count')->map(fn($v) => (int) $v);

    $statusChart = [
        'berlaku' => (int) \App\Models\Peraturan::where('status', 'berlaku')->count(),
        'diubah' => (int) \App\Models\Peraturan::where('status', 'diubah')->count(),
        'dicabut' => (int) \App\Models\Peraturan::where('status', 'dicabut')->count(),
    ];

    return view('dashboard', compact('kategoriLabels', 'kategoriData', 'statusChart', 'kategoriChart'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('peraturan', \App\Http\Controllers\Admin\PeraturanController::class);
    Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class);
});

require __DIR__.'/auth.php';