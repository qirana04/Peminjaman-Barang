@extends('layouts.admin')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Riwayat Pengembalian</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Laporan Pengembalian</h4>
                {{-- Fitur Reset Search --}}
                @if(request('search'))
                    <div class="card-header-action">
                        <a href="{{ route('rentals.returnHistory') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-undo"></i> Tampilkan Semua Data
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-md">
                        <thead>
                            <tr>
                                <th>Nama Peminjam</th>
                                <th>Sepatu</th>
                                <th>Jam Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Status / Sanksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($returns as $r)
                                @php
                                    // 1. Ambil Waktu Pinjam & Waktu Klik Selesai
                                    $start = \Carbon\Carbon::parse($r->tgl_pinjam);
                                    $jam_diklik = \Carbon\Carbon::parse($r->tgl_kembali);
                                    
                                    // 2. Ambil Durasi & Hitung Batas Kembali
                                    $durasi_angka = (int) filter_var($r->durasi, FILTER_SANITIZE_NUMBER_INT);
                                    $batas_kembali = $start->copy()->addHours($durasi_angka);

                                    // 3. Hitung Selisih Menit Secara Real-Time (untuk memastikan tampilan akurat)
                                    // false = jika jam diklik > batas kembali, hasilnya positif
                                    $selisih = $batas_kembali->diffInMinutes($jam_diklik, false);
                                @endphp
                                <tr>
                                    <td class="font-weight-bold text-uppercase">{{ $r->customer_name }}</td>
                                    <td>{{ $r->shoe->merk }} (Size {{ $r->shoe->ukuran }})</td>
                                    <td>{{ $start->format('H:i') }}</td>
                                    <td>{{ $batas_kembali->format('H:i') }}</td>
                                    <td>
                                        {{-- LOGIKA TAMPILAN: Kita cek selisih menitnya langsung di sini --}}
                                        @if($selisih > 360) {{-- Lebih dari 6 Jam --}}
                                            <span class="badge badge-danger" style="border-radius:20px; padding: 5px 15px;">
                                                Terlambat Parah! Sanksi 100.000
                                            </span>
                                        @elseif($selisih > 60) {{-- Lebih dari 1 Jam s/d 5 Jam --}}
                                            <span class="badge badge-warning" style="border-radius:20px; padding: 5px 15px; color:white;">
                                                Terlambat! Sanksi 50.000
                                            </span>
                                        @elseif($selisih > 3) {{-- Lebih dari 3 Menit s/d 1 Jam --}}
                                            <span class="badge badge-info" style="border-radius:20px; padding: 5px 15px;">
                                                Terlambat! Sanksi 10.000
                                            </span>
                                        @else
                                            <span class="badge badge-success" style="border-radius:20px; padding: 5px 15px;">
                                                Tepat Waktu
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center p-4">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection