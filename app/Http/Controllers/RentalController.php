<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Shoe; 
use Carbon\Carbon;

class RentalController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $rentals = Rental::with('shoe')
                ->where('status', 'dipinjam')
                ->latest()
                ->get()
                ->groupBy(function($item) {
                    return $item->customer_name . '|' . $item->tgl_pinjam;
                });
        $shoes = Shoe::where('stok', '>', 0)->get(); 
        return view('rentals.index', compact('rentals', 'shoes'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama_peminjam' => 'required',
            'shoe_id'       => 'required|array',
            'durasi'        => 'required|array',
            'tgl_pinjam'    => 'required|date',
            'jam_mulai'     => 'required', 
        ]);

        foreach ($request->shoe_id as $key => $id) {
            $shoe = Shoe::find($id);
            if ($shoe && $shoe->stok > 0) {
                $datetime_mulai = $request->tgl_pinjam . ' ' . $request->jam_mulai . ':00';
                
                Rental::create([
                    'customer_name' => $request->nama_peminjam,
                    'shoe_id'       => $id,
                    'durasi'        => $request->durasi[$key],
                    'tgl_pinjam'    => $datetime_mulai,
                    'status'        => 'dipinjam'
                ]);
                $shoe->decrement('stok');
            }
        }
        return redirect()->route('rentals.index')->with('success', 'Peminjaman berhasil disimpan!');
    }

    // FUNGSI PROSES SELESAI (Klik Tombol Selesaikan Sewa)
    public function return($id)
    {
        $rental = Rental::findOrFail($id);
        
        // 1. Set Waktu Sekarang & Jam Pinjam (Timezone Jakarta)
        $sekarang = Carbon::now('Asia/Jakarta');
        $start = Carbon::parse($rental->tgl_pinjam, 'Asia/Jakarta');
        
        // 2. Hitung Batas Kembali Berdasarkan Durasi
        $jam_durasi = (int) filter_var($rental->durasi, FILTER_SANITIZE_NUMBER_INT);
        $batas_kembali = $start->copy()->addHours($jam_durasi);

        // 3. Logika Denda Bertingkat dengan Toleransi 3 Menit
        $denda = 0;
        
        // Cek selisih menit antara jam sekarang dengan batas kembali
        // false artinya jika sekarang sudah lewat batas, hasilnya positif
        $selisih_menit = $batas_kembali->diffInMinutes($sekarang, false);

        if ($selisih_menit > 3) { // Hanya denda jika lewat dari 3 menit
            if ($selisih_menit <= 60) {
                // Terlambat s/d 1 Jam
                $denda = 10000;
            } elseif ($selisih_menit <= 300) {
                // Terlambat 1 jam s/d 5 jam (300 menit)
                $denda = 50000;
            } else {
                // Terlambat di atas 6 jam (360 menit) s/d Seharian
                $denda = 100000;
            }
        }

        // 4. Update Database
        $rental->update([
            'status' => 'kembali',
            'tgl_kembali' => $sekarang,
            'denda' => $denda 
        ]);

        $rental->shoe->increment('stok');

        return redirect()->back()->with('success', 'Data Berhasil Diperbarui! Denda: Rp ' . number_format($denda, 0, ',', '.'));
    }

    public function returnHistory(Request $request) 
    {
        $query = Rental::with('shoe')->where('status', 'kembali');

        if ($request->filled('search')) {
            $query->where('customer_name', 'like', '%' . $request->search . '%');
        }

        $returns = $query->orderBy('tgl_kembali', 'desc')->get();
        return view('rentals.return', compact('returns'));
    }
}