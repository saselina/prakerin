<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

// Kalau buka halaman utama (/) langsung ke /items
Route::get('/', function () {
    return redirect('/items');
});

// Dashboard bawaan Breeze (boleh dihapus kalau nggak dipakai)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Semua route di bawah ini hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {

    // ✅ ROUTE UNTUK PROFILE (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ ROUTE UNTUK ITEM (halaman Daftar Barang kamu)
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // kalau kamu punya fitur ambil ruangan dari gedung
    Route::get('/get-rooms/{building_id}', [ItemController::class, 'getRooms'])->name('getRooms');
});

// auth route bawaan Breeze
require __DIR__.'/auth.php';
