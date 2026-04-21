<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;

// Auth Routes (Login, Logout, dll)
Auth::routes();

// --- Redirect Halaman Utama ---
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home');

// --- Semua rute di bawah wajib Login ---
Route::middleware(['auth'])->group(function () {

    // --- MANAJEMEN SEPATU (LIHAT DAFTAR) ---
    Route::get('/shoes', [ShoeController::class, 'index'])->name('shoes.index');

    // --- MANAJEMEN PEMINJAMAN ---
    // 1. Tampilan Daftar Sewa Aktif (Index)
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    
    // 2. Simpan Sewa Baru (Store)
    Route::post('/rentals/store', [RentalController::class, 'store'])->name('rentals.store');

    // 3. PROSES PENGEMBALIAN (Klik Tombol Selesai)
    // Note: URL harus unik dan menggunakan method PUT karena mengupdate data
    Route::put('/rentals/{id}/return-process', [RentalController::class, 'return'])->name('rentals.return');

    // 4. HALAMAN RIWAYAT / RETURN HISTORY (Tampilan return.blade.php)
    // URL dibedakan menjadi '/rentals-history' agar tidak bentrok dengan proses update
    Route::get('/rentals-history', [RentalController::class, 'returnHistory'])->name('rentals.returnHistory');


    // --- KHUSUS ADMIN (KELOLA STOK) ---
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/shoes/store', [ShoeController::class, 'store'])->name('shoes.store');
        Route::put('/shoes/update/{id}', [ShoeController::class, 'update'])->name('shoes.update');
        Route::delete('/shoes/delete/{id}', [ShoeController::class, 'destroy'])->name('shoes.destroy');
    });

    // --- MANAJEMEN PEMINJAMAN ---
    Route::middleware(['auth'])->group(function () {
        // Halaman Utama Transaksi Aktif
        Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
        
        // Proses Simpan
        Route::post('/rentals/store', [RentalController::class, 'store'])->name('rentals.store');

        // HALAMAN RIWAYAT (File return.blade.php)
        // Nama route ini harus dipanggil di Sidebar / Link Navigasi
        Route::get('/rentals-history', [RentalController::class, 'returnHistory'])->name('rentals.returnHistory');

        // PROSES SELESAI (Klik Tombol)
        Route::put('/rentals/{id}/return-process', [RentalController::class, 'return'])->name('rentals.return');
    });

});