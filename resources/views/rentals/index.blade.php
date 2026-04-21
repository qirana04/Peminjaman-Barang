@extends('layouts.admin')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Peminjaman Ice Skating</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Transaksi</h4>
                        <div class="card-header-action">
                            {{-- Tombol ini sekarang memanggil Modal Global di admin.blade.php --}}
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPinjam">
                                <i class="fas fa-plus"></i> Tambah Sewa
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Peminjam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rentals as $groupKey => $items)
                                        @php
                                            $customerName = explode('|', $groupKey)[0];
                                            $tglPinjam = $items->first()->tgl_pinjam;
                                            $groupId = Str::slug($customerName) . '-' . $loop->index;
                                        @endphp

                                        <tr class="bg-white shadow-sm" style="cursor: pointer; border-left: 5px solid #ffa426;" data-toggle="collapse" data-target="#details-{{ $groupId }}">
                                            <td colspan="6" class="p-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="fas fa-folder-open text-warning fa-lg mr-3"></i>
                                                        <span class="h6 mb-0 font-weight-bold text-dark">{{ strtoupper($customerName) }}</span>
                                                        <span class="badge badge-pill badge-primary ml-2">{{ $items->count() }} Item</span>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="text-muted mr-3"><i class="far fa-calendar-alt mr-1"></i> {{ date('d-m-Y', strtotime($tglPinjam)) }}</span>
                                                        <span class="btn btn-sm btn-outline-light text-dark border"><i class="fas fa-chevron-down"></i> Detail</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr id="details-{{ $groupId }}" class="collapse">
                                            <td colspan="6" class="p-0 border-0">
                                                <div class="bg-light p-4">
                                                    <div class="row mx-0">
                                                        @foreach($items as $item)
                                                            @php
                                                                $start = \Carbon\Carbon::parse($item->tgl_pinjam, 'Asia/Jakarta');
                                                                $jam_durasi = (int) filter_var($item->durasi, FILTER_SANITIZE_NUMBER_INT);
                                                                $end = ($item->durasi == 'Seharian') ? $start->copy()->endOfDay() : $start->copy()->addHours($jam_durasi);
                                                                $is_late = \Carbon\Carbon::now('Asia/Jakarta')->greaterThan($end);
                                                            @endphp
                                                            
                                                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                                                <div class="card shadow-sm mb-0 border-0" style="border-radius: 12px; overflow: hidden;">
                                                                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0 pt-3 pb-0">
                                                                        <h6 class="font-weight-bold mb-0 {{ $is_late ? 'text-danger' : 'text-primary' }}">
                                                                            {{ $item->shoe->merk }}
                                                                        </h6>
                                                                        <span class="badge badge-dark">Size {{ $item->shoe->ukuran }}</span>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="d-flex align-items-center mb-3 text-muted small">
                                                                            <i class="far fa-clock mr-2"></i>
                                                                            <span>{{ $start->format('H:i') }} - {{ $end->format('H:i') }}</span>
                                                                            <span class="mx-2">|</span>
                                                                            <span>{{ $item->durasi }}</span>
                                                                        </div>

                                                                        @if($is_late)
                                                                            <div class="alert alert-danger p-2 mb-3 text-center small border-0">
                                                                                <i class="fas fa-exclamation-triangle mr-1"></i> <strong>Terlambat!</strong>
                                                                            </div>
                                                                        @endif

                                                                        <form action="{{ route('rentals.return', $item->id) }}" method="POST">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <button type="submit" class="btn btn-{{ $is_late ? 'danger' : 'warning' }} btn-block font-weight-bold shadow-sm">
                                                                                <i class="fas fa-check-circle mr-1"></i> Selesaikan Sewa
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center p-5 text-muted">
                                                <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                                Belum ada transaksi peminjaman aktif.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 
    CATATAN: 
    Bagian Modal dicabut dari sini karena sudah dipindahkan ke layouts/admin.blade.php 
    agar tombol "Tambah Sewa" di Sidebar dan di halaman ini memanggil form yang sama.
--}}
@endsection