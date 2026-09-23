        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15"></div>
                <div class="sidebar-brand-text mx-3">My RENT</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()-> is('dasboard') ? 'active' : '' }}">
                <a class="nav-link" href="/dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manajemen Data
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ request()-> is('pengguna*') ? 'active' : '' }}">
                <a class="nav-link" href="pengguna">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Pengguna</span>
                </a>
            </li>

            <li class="nav-item {{ request()-> is('motor') ? 'active' : '' }}">
                <a class="nav-link" href="/motor">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Motor</span>
                </a>
            </li>

            <li class="nav-item {{ request()-> is('penyewaan') ? 'active' : '' }}">
                <a class="nav-link" href="/penyewaan">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Penyewaan</span>
                </a>
            </li>

            <li class="nav-item {{ request()-> is('pembayaran') ? 'active' : '' }}">
                <a class="nav-link" href="/pembayaran">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Pembayaran</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>