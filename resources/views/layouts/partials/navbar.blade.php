<nav class="navbar navbar-expand-lg main-navbar">
  <form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
      <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
    </ul>
    
    <div class="search-element">
        <input class="form-control" type="search" name="search" placeholder="Cari data peminjaman..." aria-label="Search" value="{{ request('search') }}">
        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
    </div>
  </form>

  <ul class="navbar-nav navbar-right">
    <li class="dropdown">
      <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
        <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
        {{-- Nama dinamis berdasarkan user yang login --}}
        <div class="d-sm-none d-lg-inline-block">
            Halo, {{ Auth::check() ? Auth::user()->name : 'Guest' }}
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        @if(Auth::check())
            {{-- Bagian yang diperbaiki: menampilkan role secara dinamis --}}
            <div class="dropdown-title">
                Logged in as: {{ strtoupper(Auth::user()->role) }}
            </div>
            
            <a href="#" class="dropdown-item has-icon">
              <i class="far fa-user"></i> Profile
            </a>
            <div class="dropdown-divider"></div>
            
            {{-- Tombol Logout --}}
            <a href="{{ route('logout') }}" 
               class="dropdown-item has-icon text-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>

            {{-- Form Logout (Wajib POST untuk keamanan) --}}
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
        @else
            <a href="{{ route('login') }}" class="dropdown-item has-icon">
              <i class="fas fa-sign-in-alt"></i> Login
            </a>
        @endif
      </div>
    </li>
  </ul>
</nav>