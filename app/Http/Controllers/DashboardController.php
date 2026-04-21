<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental; // Pastikan Model Rental sudah ada

class DashboardController extends Controller
{
    public function index()
    {
       // Menghitung total baris di tabel rentals
        $totalPeminjaman = \App\Models\Rental::where('status', 'dipinjam')->count();

        // Menghitung total sepatu (opsional, untuk kartu lain)
        $totalSepatu = \App\Models\Shoe::count();

        // Di DashboardController.php
        $peminjamanAktif = \App\Models\Rental::where('status', 'dipinjam')->count();

        // Kirim variabel ke view dashboard
        return view('dashboard', compact('totalPeminjaman', 'totalSepatu', 'peminjamanAktif'));
    }
}