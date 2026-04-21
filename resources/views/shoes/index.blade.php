@extends('layouts.admin')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Stok Sepatu</h1>
        {{-- HANYA ADMIN yang bisa melihat tombol tambah --}}
        @if(auth()->user()->role == 'admin')
        <div class="section-header-button">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Sepatu
            </button>
        </div>
        @endif
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Stok Sepatu Saat Ini</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-md">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Merk</th>
                                <th>Ukuran</th>
                                <th>Jumlah Stok</th>
                                <th>Status</th>
                                {{-- Kolom aksi hanya muncul jika admin --}}
                                @if(auth()->user()->role == 'admin')
                                <th width="150">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shoes as $key => $s)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $s->merk }}</td>
                                <td>{{ $s->ukuran }}</td>
                                <td>{{ $s->stok }}</td>
                                <td>
                                    @if($s->stok > 0)
                                        <span class="badge badge-success">Tersedia</span>
                                    @else
                                        <span class="badge badge-danger">Habis</span>
                                    @endif
                                </td>
                                
                                {{-- Logika Aksi KHUSUS ADMIN --}}
                                @if(auth()->user()->role == 'admin')
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit{{ $s->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('shoes.destroy', $s->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus sepatu ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    <div class="modal fade" id="modalEdit{{ $s->id }}" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Data Sepatu</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('shoes.update', $s->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body text-left">
                                                        <div class="form-group">
                                                            <label>Merk Sepatu</label>
                                                            <input type="text" name="merk" class="form-control" value="{{ $s->merk }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Ukuran</label>
                                                            <input type="number" name="ukuran" class="form-control" value="{{ $s->ukuran }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Jumlah Stok</label>
                                                            <input type="number" name="stok" class="form-control" value="{{ $s->stok }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-whitesmoke">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-warning">Update Data</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MODAL TAMBAH KHUSUS ADMIN --}}
@if(auth()->user()->role == 'admin')
<div class="modal fade" id="modalTambah" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Sepatu Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('shoes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Merk Sepatu</label>
                        <input type="text" name="merk" class="form-control" required placeholder="Contoh: Jackson Ultima">
                    </div>
                    <div class="form-group">
                        <label>Ukuran</label>
                        <input type="number" name="ukuran" class="form-control" required placeholder="Contoh: 42">
                    </div>
                    <div class="form-group">
                        <label>Stok Awal</label>
                        <input type="number" name="stok" class="form-control" required placeholder="0">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

<style>
    .modal-backdrop {
        display: none !important;
    }
    body.modal-open {
        overflow: auto !important;
        padding-right: 0 !important;
    }
    .modal {
        background: rgba(0,0,0,0.5); 
    }
</style>