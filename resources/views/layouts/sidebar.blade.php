<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-motorcycle fa-2x text-warning"></i>
        </div>
        <div class="sidebar-brand-text mx-2 font-weight-bold tracking-tight">Rent<span class="text-warning">Ride</span></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @if(Auth::check() && Auth::user()->isAdmin())
        <!-- ================= ADMIN MENU ================= -->
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard Admin</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Kurasi & Verifikasi</div>

        <!-- Approval Motor -->
        <li class="nav-item {{ request()->routeIs('admin.motor.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.motor.index') }}">
                <i class="fas fa-fw fa-check-double"></i>
                <span>Verifikasi Motor</span>
                @php
                    $pendingMotorCount = \App\Models\Motor::where('status_approval', 'pending')->count();
                @endphp
                @if($pendingMotorCount > 0)
                    <span class="badge badge-warning badge-counter float-right">{{ $pendingMotorCount }}</span>
                @endif
            </a>
        </li>

        <!-- Verifikasi KYC -->
        <li class="nav-item {{ request()->routeIs('admin.kyc.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.kyc.index') }}">
                <i class="fas fa-fw fa-id-card"></i>
                <span>Verifikasi KYC Penyewa</span>
                @php
                    $pendingKycCount = \App\Models\Pengguna::where('role', 'penyewa')->where('status_kyc', 'pending')->count();
                @endphp
                @if($pendingKycCount > 0)
                    <span class="badge badge-warning text-dark font-weight-bold badge-counter float-right">{{ $pendingKycCount }}</span>
                @endif
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Operasional & Resolusi</div>

        <!-- Pusat Resolusi & Transaksi -->
        <li class="nav-item {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.transaksi.index') }}">
                <i class="fas fa-fw fa-exchange-alt"></i>
                <span>Resolusi & Transaksi</span>
            </a>
        </li>

        <!-- Penarikan Dana Pemilik -->
        <li class="nav-item {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.withdrawals.index') }}">
                <i class="fas fa-fw fa-hand-holding-usd"></i>
                <span>Penarikan Dana Pemilik</span>
            </a>
        </li>

        <!-- Data Pengguna -->
        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Data Pengguna</span>
            </a>
        </li>

    @elseif(Auth::check() && Auth::user()->isPemilik())
        <!-- ================= PEMILIK MENU ================= -->
        <li class="nav-item {{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pemilik.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard Pemilik</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Aset & Pesanan</div>

        <!-- Motor Saya -->
        <li class="nav-item {{ request()->routeIs('pemilik.motor.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pemilik.motor.index') }}">
                <i class="fas fa-fw fa-motorcycle"></i>
                <span>Motor Saya</span>
            </a>
        </li>

        <!-- Pesanan Masuk -->
        <li class="nav-item {{ request()->routeIs('pemilik.pesanan.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pemilik.pesanan.index') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Pesanan Masuk</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Keuangan</div>

        <!-- Pendapatan & Saldo -->
        <li class="nav-item {{ request()->routeIs('pemilik.pendapatan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pemilik.pendapatan') }}">
                <i class="fas fa-fw fa-wallet"></i>
                <span>Pendapatan & Tarik Dana</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Marketplace</div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('katalog') }}" target="_blank">
            <i class="fas fa-fw fa-store"></i>
            <span>Buka Katalog Publik <i class="fas fa-external-link-alt fa-xs"></i></span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>