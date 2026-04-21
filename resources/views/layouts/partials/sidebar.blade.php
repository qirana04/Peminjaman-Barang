<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="/">ICE SKATING</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="/">IS</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="/dashboard"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
      </li>

      <li class="menu-header">Manajemen Sepatu</li>
      <li class="{{ Request::is('shoes*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('shoes.index') }}"><i class="fas fa-shoe-prints"></i> <span>Daftar Sepatu</span></a>
      </li>

      <li class="menu-header">Transaksi</li>
      <li class="{{ Request::is('rentals') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('rentals.index') }}">
              <i class="fas fa-exchange-alt"></i> <span>Peminjaman</span>
          </a>
      </li>
      <li class="{{ Request::is('rentals/returnHistory') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('rentals.returnHistory') }}">
              <i class="fas fa-history"></i> <span>Pengembalian</span>
          </a>
      </li>

      <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
        <button class="btn btn-primary btn-lg btn-block btn-icon-split" 
                data-toggle="modal" 
                data-target="#modalTambahPinjam"> <i class="fas fa-plus"></i> Tambah Sewa
        </button>
      </div>
  </aside>
</div>