<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - RentRide Platform</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    
    <!-- Theme Palette Overrides: Primary Hijau (#16a34a), Secondary Hitam (#09090b / #18181b), Tersier Kuning (#eab308 / #facc15) -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
        }

        /* Sidebar: Secondary Hitam dengan Nuansa Gradasi Hijau Hutan */
        .bg-gradient-primary {
            background-color: #09090b !important;
            background-image: linear-gradient(180deg, #09090b 0%, #18181b 60%, #14532d 100%) !important;
            background-size: cover;
        }

        .sidebar-dark .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* Nav Item Aktif: Tersier Kuning Emas */
        .sidebar-dark .nav-item.active .nav-link {
            color: #facc15 !important;
            font-weight: 700 !important;
        }
        .sidebar-dark .nav-item.active .nav-link i {
            color: #facc15 !important;
        }

        .sidebar-dark .nav-item .nav-link:hover {
            color: #4ade80 !important;
        }
        .sidebar-dark .nav-item .nav-link:hover i {
            color: #4ade80 !important;
        }

        .sidebar-dark .sidebar-heading {
            color: #a1a1aa !important;
            font-weight: 700 !important;
            letter-spacing: 0.08em;
        }

        /* Primary Button: Hijau */
        .btn-primary {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
            color: #ffffff !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: #15803d !important;
            border-color: #15803d !important;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.35) !important;
        }

        .btn-outline-primary {
            color: #16a34a !important;
            border-color: #16a34a !important;
        }
        .btn-outline-primary:hover {
            background-color: #16a34a !important;
            color: #ffffff !important;
        }

        /* Tersier Button: Kuning */
        .btn-warning {
            background-color: #eab308 !important;
            border-color: #eab308 !important;
            color: #09090b !important;
            font-weight: 700 !important;
        }
        .btn-warning:hover, .btn-warning:focus {
            background-color: #ca8a04 !important;
            border-color: #ca8a04 !important;
            color: #ffffff !important;
        }

        .btn-outline-warning {
            color: #ca8a04 !important;
            border-color: #eab308 !important;
            font-weight: 600 !important;
        }
        .btn-outline-warning:hover {
            background-color: #eab308 !important;
            color: #09090b !important;
        }

        /* Badges */
        .badge-primary {
            background-color: #16a34a !important;
        }
        .badge-warning {
            background-color: #facc15 !important;
            color: #18181b !important;
        }
        .badge-dark {
            background-color: #18181b !important;
            color: #f4f4f5 !important;
        }
        .badge-info {
            background-color: #18181b !important;
            color: #facc15 !important;
        }

        /* Info classes redirected to Secondary (Hitam) / Tertiary (Kuning) */
        .btn-info {
            background-color: #18181b !important;
            border-color: #18181b !important;
            color: #ffffff !important;
        }
        .btn-info:hover {
            background-color: #09090b !important;
            border-color: #09090b !important;
        }
        .btn-outline-info {
            color: #18181b !important;
            border-color: #18181b !important;
        }
        .btn-outline-info:hover {
            background-color: #18181b !important;
            color: #ffffff !important;
        }

        /* Borders & Highlights */
        .border-left-primary {
            border-left: 0.25rem solid #16a34a !important;
        }
        .border-left-warning {
            border-left: 0.25rem solid #eab308 !important;
        }
        .border-left-dark {
            border-left: 0.25rem solid #18181b !important;
        }
        .border-left-info {
            border-left: 0.25rem solid #18181b !important;
        }

        .text-primary {
            color: #16a34a !important;
        }
        .text-warning {
            color: #ca8a04 !important;
        }

        /* Pagination & Focus */
        .page-item.active .page-link {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
            color: #ffffff !important;
        }
        .page-link {
            color: #16a34a;
        }
        .form-control:focus {
            border-color: #86efac !important;
            box-shadow: 0 0 0 0.2rem rgba(22, 163, 74, 0.25) !important;
        }
    </style>
    @stack('styles')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('layouts.navbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #16a34a;">
                            <i class="fas fa-check-circle mr-2 text-success"></i>{{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #eab308;">
                            <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>{{ session('warning') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('layouts.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top" style="background-color: #16a34a;">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Konfirmasi Keluar</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apakah Anda yakin ingin mengakhiri sesi dan keluar dari akun ini?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger font-weight-bold">Ya, Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
