<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RentRide') - Marketplace Rental Motor Modern</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS CDN dengan Tema: Primary Hijau, Secondary Hitam, Tersier Kuning -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        // Primary: Hijau
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a', // Main Primary Green
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        // Secondary: Hitam
                        darkness: {
                            800: '#27272a',
                            850: '#1e1e24',
                            900: '#18181b', // Main Secondary Black
                            950: '#09090b', // Deep Black
                        },
                        // Tersier: Kuning
                        tertiary: {
                            50: '#fefce8',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#facc15',
                            500: '#eab308', // Main Tertiary Yellow
                            600: '#ca8a04',
                            700: '#a16207',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Primary Hijau + Tersier Kuning Highlight */
        .gradient-brand {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 70%, #ca8a04 100%);
        }
        
        .gradient-brand-reverse {
            background: linear-gradient(135deg, #eab308 0%, #16a34a 100%);
        }

        /* Secondary Hitam dengan Aksen Hijau Gelap */
        .gradient-dark {
            background: linear-gradient(135deg, #09090b 0%, #18181b 65%, #052e16 100%);
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .btn-brand-primary {
            background-color: #16a34a;
            color: #ffffff;
            transition: all 0.2s ease;
        }
        .btn-brand-primary:hover {
            background-color: #15803d;
            box-shadow: 0 4px 14px rgba(22, 163, 74, 0.35);
        }

        .btn-brand-tertiary {
            background-color: #eab308;
            color: #18181b;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-brand-tertiary:hover {
            background-color: #ca8a04;
            color: #ffffff;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-column flex-col justify-between antialiased">

    <!-- Top Announcement Bar (Secondary Hitam, Tersier Kuning, Primary Hijau) -->
    <div class="bg-darkness-950 text-slate-200 text-xs py-2 px-4 border-b border-darkness-900">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-brand-500/20 text-brand-300 border border-brand-500/30">
                    <i class="fas fa-circle text-[6px] text-tertiary-400 mr-1.5 animate-pulse"></i>
                    PRD v1.0 Live Demo
                </span>
                <span class="hidden sm:inline text-slate-400">Pilih role untuk menguji alur kerja:</span>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="text-slate-400 text-[11px] mr-1 hidden md:inline">Quick Login:</span>
                <a href="{{ route('demo.login', 'admin') }}" class="px-2.5 py-0.5 rounded-lg bg-darkness-900 hover:bg-brand-600 text-slate-200 hover:text-white transition font-medium text-[11px] flex items-center gap-1.5 border border-darkness-800 hover:border-brand-500">
                    <i class="fas fa-crown text-tertiary-400 text-[10px]"></i> Admin
                </a>
                <a href="{{ route('demo.login', 'pemilik') }}" class="px-2.5 py-0.5 rounded-lg bg-darkness-900 hover:bg-brand-600 text-slate-200 hover:text-white transition font-medium text-[11px] flex items-center gap-1.5 border border-darkness-800 hover:border-brand-500">
                    <i class="fas fa-motorcycle text-brand-400 text-[10px]"></i> Pemilik
                </a>
                <a href="{{ route('demo.login', 'penyewa') }}" class="px-2.5 py-0.5 rounded-lg bg-darkness-900 hover:bg-brand-600 text-slate-200 hover:text-white transition font-medium text-[11px] flex items-center gap-1.5 border border-darkness-800 hover:border-brand-500">
                    <i class="fas fa-id-card-clip text-tertiary-400 text-[10px]"></i> Penyewa (KYC ✓)
                </a>
                <a href="{{ route('demo.login', 'penyewa_baru') }}" class="px-2.5 py-0.5 rounded-lg bg-darkness-900 hover:bg-brand-600 text-slate-200 hover:text-white transition font-medium text-[11px] flex items-center gap-1.5 border border-darkness-800 hover:border-brand-500">
                    <i class="fas fa-user-clock text-slate-400 text-[10px]"></i> Penyewa Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-50 glass border-b border-slate-200/80 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo (Hijau Primary + Kuning Tersier Accent) -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl gradient-brand flex items-center justify-center text-white shadow-md shadow-brand-600/30 group-hover:scale-105 transition-transform">
                        <i class="fas fa-motorcycle text-lg text-tertiary-300"></i>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-xl font-black tracking-tight text-slate-900">Rent<span class="text-brand-600">Ride</span></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary-500"></span>
                        </div>
                        <span class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase -mt-1">Marketplace Motor</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-brand-600' : 'text-slate-600 hover:text-slate-900' }} transition">
                        Beranda
                    </a>
                    <a href="{{ route('katalog') }}" class="text-sm font-semibold {{ request()->routeIs('katalog') ? 'text-brand-600' : 'text-slate-600 hover:text-slate-900' }} transition flex items-center gap-1.5">
                        <i class="fas fa-calendar-alt text-xs text-brand-600"></i>
                        Cari & Sewa Motor
                    </a>
                    @auth
                        @if(Auth::user()->isPenyewa())
                            <a href="{{ route('penyewa.riwayat') }}" class="text-sm font-semibold {{ request()->routeIs('penyewa.riwayat') ? 'text-brand-600' : 'text-slate-600 hover:text-slate-900' }} transition">
                                Riwayat Sewa
                            </a>
                            <a href="{{ route('penyewa.kyc') }}" class="text-sm font-semibold {{ request()->routeIs('penyewa.kyc') ? 'text-brand-600' : 'text-slate-600 hover:text-slate-900' }} transition flex items-center gap-1.5">
                                Verifikasi KYC
                                @if(Auth::user()->isKycVerified())
                                    <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-tertiary-500 animate-pulse"></span>
                                @endif
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Auth Buttons / User Profile -->
                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-brand-600 px-3 py-2 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-sm font-bold text-white gradient-brand shadow-sm hover:opacity-95 transition shadow-brand-600/30">
                            Daftar Sekarang
                        </a>
                    @else
                        <!-- Logged In User Dropdown -->
                        <div class="relative group">
                            <button type="button" class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-100 transition">
                                <div class="w-9 h-9 rounded-full bg-brand-100 border-2 border-brand-600 text-brand-700 font-bold flex items-center justify-center text-sm">
                                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                                </div>
                                <div class="hidden sm:block text-left">
                                    <div class="text-xs font-bold text-slate-900 leading-tight">{{ Str::limit(Auth::user()->nama, 16) }}</div>
                                    <span class="inline-block text-[10px] font-semibold px-1.5 py-0.2 rounded 
                                        {{ Auth::user()->isAdmin() ? 'bg-tertiary-100 text-tertiary-800' : (Auth::user()->isPemilik() ? 'bg-darkness-900 text-white' : 'bg-brand-100 text-brand-700') }}">
                                        {{ ucfirst(Auth::user()->role) }}
                                    </span>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-slate-400 group-hover:text-slate-600 transition"></i>
                            </button>

                            <!-- Dropdown Box -->
                            <div class="absolute right-0 mt-1 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 hidden group-hover:block hover:block z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs text-slate-500">Masuk sebagai</p>
                                    <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition">
                                        <i class="fas fa-gauge-high text-brand-600 w-4"></i> Dashboard Admin
                                    </a>
                                    <a href="{{ route('admin.motor.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition">
                                        <i class="fas fa-check-double text-brand-600 w-4"></i> Verifikasi Motor
                                    </a>
                                    <a href="{{ route('admin.kyc.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition">
                                        <i class="fas fa-id-card text-brand-600 w-4"></i> Verifikasi KYC
                                    </a>
                                @elseif(Auth::user()->isPemilik())
                                    <a href="{{ route('pemilik.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition">
                                        <i class="fas fa-chart-pie text-brand-600 w-4"></i> Dashboard Pemilik
                                    </a>
                                    <a href="{{ route('pemilik.motor.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition">
                                        <i class="fas fa-motorcycle text-brand-600 w-4"></i> Motor Saya
                                    </a>
                                    <a href="{{ route('pemilik.pesanan.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition">
                                        <i class="fas fa-clipboard-list text-brand-600 w-4"></i> Pesanan Masuk
                                    </a>
                                    <a href="{{ route('pemilik.pendapatan') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition">
                                        <i class="fas fa-wallet text-brand-600 w-4"></i> Dompet & Tarik Dana
                                    </a>
                                @else
                                    <a href="{{ route('penyewa.riwayat') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition">
                                        <i class="fas fa-ticket text-brand-600 w-4"></i> Tiket & Riwayat Sewa
                                    </a>
                                    <a href="{{ route('penyewa.kyc') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition">
                                        <i class="fas fa-id-badge text-brand-600 w-4"></i> Dokumen KYC
                                    </a>
                                @endif

                                <div class="border-t border-slate-100 mt-2 pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 transition text-left font-medium">
                                            <i class="fas fa-right-from-bracket w-4"></i> Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-brand-50 border border-brand-200 text-brand-800 flex items-start gap-3 shadow-sm mb-4">
                <i class="fas fa-circle-check text-brand-600 text-lg mt-0.5"></i>
                <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-sm mb-4">
                <i class="fas fa-circle-exclamation text-rose-500 text-lg mt-0.5"></i>
                <div class="flex-1 text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 rounded-xl bg-tertiary-50 border border-tertiary-300 text-tertiary-800 flex items-start gap-3 shadow-sm mb-4">
                <i class="fas fa-triangle-exclamation text-tertiary-600 text-lg mt-0.5"></i>
                <div class="flex-1 text-sm font-medium">{{ session('warning') }}</div>
            </div>
        @endif
    </div>

    <!-- Main Content View -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Modern Footer (Secondary Hitam Pekat dengan Aksen Hijau & Kuning) -->
    <footer class="bg-darkness-950 text-slate-400 text-sm mt-20 border-t border-darkness-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1 -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl gradient-brand flex items-center justify-center text-white">
                            <i class="fas fa-motorcycle text-sm text-tertiary-300"></i>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">Rent<span class="text-brand-500">Ride</span> Marketplace</span>
                    </div>
                    <p class="text-slate-400 text-xs max-w-sm leading-relaxed mb-4">
                        Platform marketplace rental motor terpercaya yang mempertemukan pemilik motor dan penyewa secara aman. Dilengkapi sistem verifikasi identitas (KYC) dan kurasi ketat admin.
                    </p>
                    <div class="flex items-center gap-3 text-slate-400">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-darkness-900 text-brand-400 border border-darkness-800">
                            <i class="fas fa-shield-halved text-brand-500"></i> KYC Protected
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-darkness-900 text-tertiary-400 border border-darkness-800">
                            <i class="fas fa-lock text-tertiary-400"></i> Anti Double-Booking
                        </span>
                    </div>
                </div>

                <!-- Col 2 -->
                <div>
                    <h4 class="text-white font-semibold text-xs tracking-wider uppercase mb-4 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span> Navigasi Cepat
                    </h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-brand-400 transition">Beranda</a></li>
                        <li><a href="{{ route('katalog') }}" class="hover:text-brand-400 transition">Cari Sewa Motor</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-brand-400 transition">Masuk Akun</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-brand-400 transition">Daftar Jadi Pemilik</a></li>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div>
                    <h4 class="text-white font-semibold text-xs tracking-wider uppercase mb-4 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-tertiary-400"></span> Peran Pengguna
                    </h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('demo.login', 'penyewa') }}" class="hover:text-tertiary-400 transition">Portal Penyewa (Renter)</a></li>
                        <li><a href="{{ route('demo.login', 'pemilik') }}" class="hover:text-brand-400 transition">Portal Pemilik (Owner)</a></li>
                        <li><a href="{{ route('demo.login', 'admin') }}" class="hover:text-tertiary-400 transition">Dashboard Admin Platform</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-darkness-900 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} RentRide Platform. Seluruh hak cipta dilindungi.</p>
                <p class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-brand-500"></span>
                    Tema: Hijau (Primary) &bull; Hitam (Secondary) &bull; Kuning (Tersier)
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
