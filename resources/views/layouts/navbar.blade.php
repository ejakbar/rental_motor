<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Demo Switcher -->
    <div class="d-none d-md-flex align-items-center">
        <span class="small text-muted mr-2 font-weight-bold"><i class="fas fa-magic text-warning mr-1"></i>Demo Switcher:</span>
        <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('demo.login', 'admin') }}" class="btn {{ Auth::check() && Auth::user()->isAdmin() ? 'btn-primary' : 'btn-outline-secondary' }}">
                <i class="fas fa-crown fa-xs mr-1"></i> Admin
            </a>
            <a href="{{ route('demo.login', 'pemilik') }}" class="btn {{ Auth::check() && Auth::user()->isPemilik() ? 'btn-primary' : 'btn-outline-secondary' }}">
                <i class="fas fa-motorcycle fa-xs mr-1"></i> Pemilik
            </a>
            <a href="{{ route('demo.login', 'penyewa') }}" class="btn {{ Auth::check() && Auth::user()->isPenyewa() ? 'btn-primary' : 'btn-outline-secondary' }}">
                <i class="fas fa-user-check fa-xs mr-1"></i> Penyewa
            </a>
        </div>
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Public Catalog Link -->
        <li class="nav-item mx-1">
            <a class="nav-link text-primary font-weight-bold" href="{{ route('katalog') }}" title="Lihat Katalog Publik">
                <i class="fas fa-store mr-1"></i> <span class="d-none d-sm-inline">Katalog</span>
            </a>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="text-right mr-3 d-none d-lg-block">
                    <span class="small text-gray-900 font-weight-bold d-block">{{ Auth::user()->nama ?? 'Pengguna' }}</span>
                    <span class="badge badge-pill {{ Auth::user()->isAdmin() ? 'badge-warning text-dark font-weight-bold' : (Auth::user()->isPemilik() ? 'badge-dark font-weight-bold' : 'badge-primary font-weight-bold') }}">
                        {{ strtoupper(Auth::user()->role ?? 'GUEST') }}
                    </span>
                </div>
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center font-weight-bold shadow-sm" style="width: 38px; height: 38px;">
                    {{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}
                </div>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <div class="dropdown-header text-center font-weight-bold">
                    {{ Auth::user()->email ?? '' }}
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('home') }}">
                    <i class="fas fa-home fa-sm fa-fw mr-2 text-gray-400"></i> Halaman Depan
                </a>
                <a class="dropdown-item" href="{{ route('katalog') }}">
                    <i class="fas fa-motorcycle fa-sm fa-fw mr-2 text-gray-400"></i> Katalog Motor
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i> Logout
                </a>
            </div>
        </li>

    </ul>

</nav>