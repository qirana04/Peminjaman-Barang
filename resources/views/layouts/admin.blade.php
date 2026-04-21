<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Ice Skating Rental</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">

  <style>
    /* Memastikan Navbar tetap di atas dan tidak terganggu konten halaman */
    .main-navbar {
      z-index: 891;
      left: 250px; /* Jarak standar sidebar Stisla */
      right: 5px;
    }

    /* Membuat kotak search rapi dan lebar */
    .main-navbar .search-element {
      position: relative;
      width: 300px; /* Menentukan lebar kotak agar tidak gepeng */
      display: flex;
      align-items: center;
    }

    .main-navbar .search-element .form-control {
      background-color: #f2f2f2;
      border: none;
      height: 35px;
      border-radius: 3px; /* Standar Stisla */
      padding-left: 15px;
      padding-right: 40px;
      width: 100%;
    }

    .main-navbar .search-element .btn {
      position: absolute;
      right: 5px;
      background-color: transparent;
      color: #6777ef;
      border: none;
      height: 100%;
    }

    /* Perbaikan khusus halaman Sepatu agar judul tidak menabrak navbar */
    .main-content {
      padding-top: 80px !important;
    }
    
    /* Style tambahan untuk list sepatu dinamis */
    .item-sepatu {
      background: #f9f9f9;
      padding: 15px;
      border-radius: 5px;
      border-left: 4px solid #6777ef;
    }
  </style>
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      
      @include('layouts.partials.navbar')
      @include('layouts.partials.sidebar')

      <div class="main-content">
        @yield('content')
      </div>
      
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2026 <div class="bullet"></div> Ice Skating Rental
        </div>
      </footer>
    </div>
  </div>

  {{-- MODAL TAMBAH SEWA (VERSI MULTIPLE ITEMS) --}}
  <div class="modal fade" id="modalTambahPinjam" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tambah Sewa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('rentals.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Nama Peminjam</label>
                            <input type="text" name="nama_peminjam" class="form-control" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Jumlah Sepatu</label>
                            <input type="number" id="input_jumlah_sepatu" class="form-control" value="1" min="1" max="10">
                            <small class="text-muted">Max 10 per transaksi</small>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group col-md-4">
                                <label>Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control" value="{{ date('H:i') }}" required>
                        </div>
                    </div>

                    <hr>
                    <div id="container-list-sepatu">
                        {{-- Baris Default --}}
                        <div class="item-sepatu mb-3">
                            <div class="row">
                                <div class="col-md-7">
                                    <label>Pilih Sepatu (1)</label>
                                   <select name="shoe_id[]" class="form-control" required> 
                                      @foreach($global_shoes as $s)
                                          <option value="{{ $s->id }}">{{ $s->merk }} - Size {{ $s->ukuran }}</option>
                                      @endforeach
                                  </select>

                                  <select name="durasi[]" class="form-control" required>
                                      <option value="1 Jam">1 Jam</option>
                                      <option value="2 Jam">2 Jam</option>
                                  </select>
                                </div>
                                <div class="col-md-5">
                                    <label>Durasi</label>
                                    <select name="durasi[]" class="form-control" required>
                                        <option value="1 Jam">1 Jam</option>
                                        <option value="2 Jam">2 Jam</option>
                                        <option value="Seharian">Seharian</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>

  <script src="{{ asset('assets/js/stisla.js') }}"></script>
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>

  {{-- SCRIPT DYNAMIS UNTUK JUMLAH SEPATU --}}
  <script>
    $('#input_jumlah_sepatu').on('input change keyup', function() {
        let jumlah = $(this).val();
        let container = $('#container-list-sepatu');
        
        // Batasi jumlah input agar tidak merusak UI (maksimal 10)
        if(jumlah > 10) { $(this).val(10); jumlah = 10; }
        if(jumlah < 1 && jumlah !== '') { $(this).val(1); jumlah = 1; }

        let htmlContent = '';
        for (let i = 1; i <= jumlah; i++) {
            htmlContent += `
                <div class="item-sepatu mb-3">
                    <div class="row">
                        <div class="col-md-7">
                            <label>Pilih Sepatu (${i})</label>
                            <select name="shoe_id[]" class="form-control" required>
                                <option value="">-- Pilih Sepatu --</option>
                                @foreach($global_shoes as $s)
                                    <option value="{{ $s->id }}">{{ $s->merk }} - Size {{ $s->ukuran }} (Stok: {{ $s->stok }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label>Durasi</label>
                            <select name="durasi[]" class="form-control" required>
                                <option value="1 Jam">1 Jam</option>
                                <option value="2 Jam">2 Jam</option>
                                <option value="Seharian">Seharian</option>
                            </select>
                        </div>
                    </div>
                </div>`;
        }
        
        // Masukkan ke container jika jumlah lebih dari 0
        if(jumlah > 0) {
            container.html(htmlContent);
        }
    });
  </script>
</body>
</html>