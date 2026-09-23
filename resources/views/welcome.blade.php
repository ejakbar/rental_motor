<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentRide - Platform Rental Motor Modern</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --accent: #ec4899;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --gray-900: #1e293b;
            --gray-800: #334155;
            --gray-700: #475569;
            --gray-600: #64748b;
            --gray-500: #94a3b8;
            --gray-400: #cbd5e1;
            --gray-300: #e2e8f0;
            --gray-200: #f1f5f9;
            --gray-100: #f8fafc;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Glassmorphism Effect */
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .glass-dark {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
            background: rebeccapurple;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 800;
            background-color: white;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .navbar.scrolled .logo {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-menu {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }

        .nav-menu a {
            text-decoration: none;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            position: relative;
            transition: all 0.3s;
            letter-spacing: 0.3px;
        }

        .navbar.scrolled .nav-menu a {
            color: var(--gray-700);
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s;
        }

        .nav-menu a:hover::after {
            width: 100%;
        }

        .nav-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.75rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid white;
            color: white;
        }

        .navbar.scrolled .btn-outline {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-3px);
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--accent) 0%, var(--primary) 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(236, 72, 153, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(236, 72, 153, 0.4);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        /* Hero Section */
        .hero {
            padding: 10rem 2rem 6rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }

        .hero-content {
            max-width: 1400px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 4.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            letter-spacing: -2px;
            animation: fadeInUp 0.8s ease;
        }

        .hero .gradient-text {
            background: linear-gradient(135deg, #fff 0%, #fbbf24 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease 0.2s both;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stats Section */
        .stats-section {
            background: white;
            padding: 4rem 2rem;
            margin-top: -3rem;
            position: relative;
            z-index: 2;
        }

        .stats-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
        }

        .stat-item {
            text-align: center;
            animation: fadeInUp 0.8s ease;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--gray-600);
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 3rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .section-subtitle {
            font-size: 1.25rem;
            color: var(--gray-900);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Motor Cards */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .motor-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            cursor: pointer;
        }

        .motor-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .motor-card-image {
            width: 100%;
            height: 240px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            position: relative;
            overflow: hidden;
        }

        .motor-card-image::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .motor-card-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
            backdrop-filter: blur(10px);
            z-index: 1;
        }

        .badge-available {
            background: rgba(16, 185, 129, 0.9);
            color: white;
        }

        .badge-rented {
            background: rgba(245, 158, 11, 0.9);
            color: white;
        }

        .badge-maintenance {
            background: rgba(239, 68, 68, 0.9);
            color: white;
        }

        .motor-card-body {
            padding: 2rem;
        }

        .motor-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .motor-card-specs {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .spec-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-600);
            font-size: 0.9rem;
        }

        .spec-icon {
            width: 20px;
            height: 20px;
            background: var(--gray-200);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        .motor-card-price {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .price-amount {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
        }

        .price-period {
            color: var(--gray-500);
            font-size: 0.95rem;
        }

        /* Filter Section */
        .filter-bar {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--gray-300);
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(8px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .modal-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
        }

        .close-btn {
            width: 40px;
            height: 40px;
            background: var(--gray-200);
            border: none;
            border-radius: 12px;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .close-btn:hover {
            background: var(--gray-300);
            transform: rotate(90deg);
        }

        /* Dashboard */
        .dashboard-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
            margin-top: 6rem;
        }

        .sidebar {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            height: fit-content;
            position: sticky;
            top: 6rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            padding: 1rem 1.25rem;
            margin-bottom: 0.5rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            color: var(--gray-600);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-menu li:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .sidebar-menu li.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1.25rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .navbar.scrolled .user-profile {
            background: var(--gray-100);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .user-info span {
            display: block;
            color: white;
            font-weight: 600;
        }

        .navbar.scrolled .user-info span {
            color: var(--dark);
        }

        .user-info small {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        .navbar.scrolled .user-info small {
            color: var(--gray-600);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary) 0%, var(--accent) 100%);
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: var(--gray-600);
            font-weight: 600;
        }

        /* Table */
        .table-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--gray-100);
        }

        th {
            padding: 1.25rem;
            text-align: left;
            font-weight: 700;
            color: var(--dark);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            color: var(--gray-700);
        }

        tbody tr {
            transition: all 0.3s;
        }

        tbody tr:hover {
            background: var(--gray-50);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .dashboard-layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .nav-menu {
                display: none;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 2rem;
            }
        }

        .hidden {
            display: none !important;
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Feature Cards */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .feature-card {
            background: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.4s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--gray-600);
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <div class="logo" onclick="showPage('home')">
                🏍️ RentRide
            </div>
            <ul class="nav-menu">
                <li><a href="#" onclick="showPage('home')">Beranda</a></li>
                <li><a href="#" onclick="showPage('motors')">Motor</a></li>
                <li><a href="#" onclick="showPage('about')">Tentang</a></li>
                <li><a href="#" onclick="showPage('contact')">Kontak</a></li>
            </ul>
            <div class="nav-actions">
                <div id="authButtons">
                    <button class="btn btn-outline" onclick="showModal('loginModal')">Masuk</button>
                    <button class="btn btn-primary" onclick="showModal('registerModal')">Daftar</button>
                </div>
                <div id="userProfile" class="user-profile hidden" onclick="toggleUserMenu()">
                    <div class="user-avatar" id="userAvatar">U</div>
                    <div class="user-info">
                        <span id="userName">User</span>
                        <small id="userRole">Penyewa</small>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="mainContent">
        <!-- Home Page -->
        <div id="homePage">
            <!-- Hero Section -->
            <section class="hero">
                <div class="hero-content">
                    <h1>
                        Sewa Motor <span class="gradient-text">Impianmu</span><br>
                        Dengan Mudah
                    </h1>
                    <p>Platform rental motor terpercaya dengan koleksi terlengkap dan harga terbaik di Indonesia</p>
                    <div class="hero-buttons">
                        <button class="btn btn-gradient" onclick="showPage('motors')" style="font-size: 1.1rem; padding: 1.25rem 2.5rem;">
                            🔍 Jelajahi Motor
                        </button>
                        <button class="btn btn-outline" onclick="showModal('registerModal')" style="font-size: 1.1rem; padding: 1.25rem 2.5rem;">
                            📝 Daftar Sekarang
                        </button>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="stats-section">
                <div class="stats-container">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Motor Tersedia</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Pengguna Aktif</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Mitra Pemilik</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">4.9</div>
                        <div class="stat-label">Rating Pengguna</div>
                    </div>
                </div>
            </section>

            <!-- Popular Motors -->
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Motor Populer</h2>
                    <p class="section-subtitle">Pilihan favorit dari ribuan penyewa kami</p>
                </div>
                <div class="cards-grid" id="popularMotors"></div>
            </div>

            <!-- Features Section -->
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Kenapa Pilih RentRide?</h2>
                    <p class="section-subtitle">Kemudahan dan keamanan adalah prioritas kami</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h3>Booking Cepat</h3>
                        <p>Proses booking hanya dalam hitungan menit dengan sistem otomatis yang efisien</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🛡️</div>
                        <h3>Aman & Terpercaya</h3>
                        <p>Semua motor sudah terverifikasi dan diasuransikan untuk keamanan Anda</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">💰</div>
                        <h3>Harga Terjangkau</h3>
                        <p>Dapatkan harga terbaik dengan berbagai pilihan paket sewa yang fleksibel</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Motors Page -->
        <div id="motorsPage" class="hidden">
            <div class="container" style="margin-top: 8rem;">
                <div class="section-header">
                    <h2 class="section-title">Jelajahi Motor</h2>
                    <p class="section-subtitle">Temukan motor yang sempurna untuk perjalanan Anda</p>
                </div>

                <div class="filter-bar">
                    <div class="filter-grid">
                        <div class="form-group">
                            <label>🏍️ Merk Motor</label>
                            <select class="form-control" id="filterMerk" onchange="filterMotors()">
                                <option value="">Semua Merk</option>
                                <option value="Honda">Honda</option>
                                <option value="Yamaha">Yamaha</option>
                                <option value="Suzuki">Suzuki</option>
                                <option value="Kawasaki">Kawasaki</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>⚙️ Tipe CC</label>
                            <select class="form-control" id="filterCC" onchange="filterMotors()">
                                <option value="">Semua Tipe</option>
                                <option value="110">100-125 cc</option>
                                <option value="155">150-160 cc</option>
                                <option value="250">250+ cc</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>📊 Status</label>
                            <select class="form-control" id="filterStatus" onchange="filterMotors()">
                                <option value="">Semua Status</option>
                                <option value="tersedia">Tersedia</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>💵 Harga Maksimal</label>
                            <select class="form-control" id="filterPrice" onchange="filterMotors()">
                                <option value="">Semua Harga</option>
                                <option value="100000">< Rp 100.000</option>
                                <option value="150000">< Rp 150.000</option>
                                <option value="200000">< Rp 200.000</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="cards-grid" id="motorsList"></div>
            </div>
        </div>

        <!-- Dashboard Page -->
        <div id="dashboardPage" class="hidden">
            <div class="container">
                <div class="dashboard-layout">
                    <aside class="sidebar">
                        <ul class="sidebar-menu" id="dashboardMenu"></ul>
                        <button class="btn btn-danger" style="width: 100%; margin-top: 2rem;" onclick="logout()">
                            🚪 Keluar
                        </button>
                    </aside>
                    <main>
                        <div id="dashboardContent"></div>
                    </main>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Masuk ke Akun</h2>
                <button class="close-btn" onclick="closeModal('loginModal')">×</button>
            </div>
            <form onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label>📧 Email</label>
                    <input type="email" class="form-control" placeholder="nama@email.com" required>
                </div>
                <div class="form-group">
                    <label>🔒 Password</label>
                    <input type="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                    Masuk
                </button>
            </form>
            <p style="text-align: center; margin-top: 1.5rem; color: var(--gray-600);">
                Belum punya akun? 
                <a href="#" onclick="closeModal('loginModal'); showModal('registerModal')" style="color: var(--primary); font-weight: 600;">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Buat Akun Baru</h2>
                <button class="close-btn" onclick="closeModal('registerModal')">×</button>
            </div>
            <form onsubmit="handleRegister(event)">
                <div class="form-group">
                    <label>👤 Nama Lengkap</label>
                    <input type="text" class="form-control" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label>📧 Email</label>
                    <input type="email" class="form-control" placeholder="nama@email.com" required>
                </div>
                <div class="form-group">
                    <label>📱 No. HP</label>
                    <input type="tel" class="form-control" placeholder="08123456789" required>
                </div>
                <div class="form-group">
                    <label>🎭 Daftar Sebagai</label>
                    <select class="form-control" id="registerRole" required>
                        <option value="">Pilih Role</option>
                        <option value="penyewa">🛵 Penyewa Motor</option>
                        <option value="pemilik">👔 Pemilik Motor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>🔒 Password</label>
                    <input type="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-gradient" style="width: 100%; margin-top: 1rem;">
                    Daftar Sekarang
                </button>
            </form>
            <p style="text-align: center; margin-top: 1.5rem; color: var(--gray-600);">
                Sudah punya akun? 
                <a href="#" onclick="closeModal('registerModal'); showModal('loginModal')" style="color: var(--primary); font-weight: 600;">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Booking Motor</h2>
                <button class="close-btn" onclick="closeModal('bookingModal')">×</button>
            </div>
            <div id="selectedMotorInfo" style="margin-bottom: 2rem;"></div>
            <form onsubmit="handleBooking(event)">
                <div class="form-group">
                    <label>📅 Tanggal Mulai</label>
                    <input type="date" class="form-control" id="bookingStartDate" required>
                </div>
                <div class="form-group">
                    <label>📅 Tanggal Selesai</label>
                    <input type="date" class="form-control" id="bookingEndDate" required>
                </div>
                <div class="form-group">
                    <label>⏱️ Durasi Sewa</label>
                    <select class="form-control" id="bookingDuration" onchange="calculatePrice()" required>
                        <option value="harian">Per Hari</option>
                        <option value="mingguan">Per Minggu</option>
                        <option value="bulanan">Per Bulan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>💰 Total Harga</label>
                    <input type="text" class="form-control" id="bookingPrice" readonly style="font-weight: 700; color: var(--primary); font-size: 1.25rem;">
                </div>
                <button type="submit" class="btn btn-gradient" style="width: 100%; margin-top: 1rem; font-size: 1.1rem; padding: 1rem;">
                    🎯 Konfirmasi Booking
                </button>
            </form>
        </div>
    </div>

    <script>
        // Sample Data
        const sampleMotors = [
            {
                id: 1,
                merk: 'Honda',
                model: 'Beat Street',
                tipe_cc: '110',
                status: 'tersedia',
                no_plat: 'B 1234 XYZ',
                tarif_harian: 75000,
                tarif_mingguan: 450000,
                tarif_bulanan: 1500000
            },
            {
                id: 2,
                merk: 'Yamaha',
                model: 'NMAX',
                tipe_cc: '155',
                status: 'tersedia',
                no_plat: 'B 5678 ABC',
                tarif_harian: 125000,
                tarif_mingguan: 750000,
                tarif_bulanan: 2500000
            },
            {
                id: 3,
                merk: 'Honda',
                model: 'PCX 160',
                tipe_cc: '160',
                status: 'disewa',
                no_plat: 'B 9012 DEF',
                tarif_harian: 150000,
                tarif_mingguan: 900000,
                tarif_bulanan: 3000000
            },
            {
                id: 4,
                merk: 'Suzuki',
                model: 'Nex II',
                tipe_cc: '115',
                status: 'tersedia',
                no_plat: 'B 3456 GHI',
                tarif_harian: 70000,
                tarif_mingguan: 420000,
                tarif_bulanan: 1400000
            },
            {
                id: 5,
                merk: 'Yamaha',
                model: 'Aerox 155',
                tipe_cc: '155',
                status: 'tersedia',
                no_plat: 'B 7890 JKL',
                tarif_harian: 135000,
                tarif_mingguan: 810000,
                tarif_bulanan: 2700000
            },
            {
                id: 6,
                merk: 'Honda',
                model: 'Vario 160',
                tipe_cc: '160',
                status: 'tersedia',
                no_plat: 'B 2468 MNO',
                tarif_harian: 140000,
                tarif_mingguan: 840000,
                tarif_bulanan: 2800000
            }
        ];

        let currentUser = null;
        let currentMotorId = null;
        let selectedMotor = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadPopularMotors();
            loadMotorsList();
            handleScroll();
            
            window.addEventListener('scroll', handleScroll);
        });

        // Navbar Scroll Effect
        function handleScroll() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }

        // Page Navigation
        function showPage(pageName) {
            document.getElementById('homePage').classList.add('hidden');
            document.getElementById('motorsPage').classList.add('hidden');
            document.getElementById('dashboardPage').classList.add('hidden');

            switch(pageName) {
                case 'home':
                    document.getElementById('homePage').classList.remove('hidden');
                    window.scrollTo(0, 0);
                    break;
                case 'motors':
                    document.getElementById('motorsPage').classList.remove('hidden');
                    loadMotorsList();
                    window.scrollTo(0, 0);
                    break;
                case 'dashboard':
                    if (!currentUser) {
                        showModal('loginModal');
                        return;
                    }
                    document.getElementById('dashboardPage').classList.remove('hidden');
                    loadDashboard();
                    window.scrollTo(0, 0);
                    break;
            }
        }

        // Modal Functions
        function showModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Create Motor Card
        function createMotorCard(motor) {
            const statusInfo = {
                'tersedia': { badge: 'badge-available', text: '✓ Tersedia' },
                'disewa': { badge: 'badge-rented', text: '⏱ Disewa' },
                'perawatan': { badge: 'badge-maintenance', text: '🔧 Perawatan' }
            };

            const status = statusInfo[motor.status];

            return `
                <div class="motor-card" onclick="${motor.status === 'tersedia' ? `openBookingModal(${motor.id})` : ''}">
                    <div class="motor-card-image">
                        🏍️
                        <div class="motor-card-badge ${status.badge}">${status.text}</div>
                    </div>
                    <div class="motor-card-body">
                        <h3 class="motor-card-title">${motor.merk} ${motor.model}</h3>
                        <div class="motor-card-specs">
                            <div class="spec-item">
                                <div class="spec-icon">⚙️</div>
                                <span>${motor.tipe_cc} cc</span>
                            </div>
                            <div class="spec-item">
                                <div class="spec-icon">🔢</div>
                                <span>${motor.no_plat}</span>
                            </div>
                        </div>
                        <div class="motor-card-price">
                            <span class="price-amount">Rp ${motor.tarif_harian.toLocaleString('id-ID')}</span>
                            <span class="price-period">/hari</span>
                        </div>
                        ${motor.status === 'tersedia' 
                            ? `<button class="btn btn-gradient" style="width: 100%;" onclick="event.stopPropagation(); openBookingModal(${motor.id})">
                                🎯 Booking Sekarang
                               </button>`
                            : `<button class="btn btn-outline" style="width: 100%; opacity: 0.5;" disabled>
                                Tidak Tersedia
                               </button>`
                        }
                    </div>
                </div>
            `;
        }

        // Load Motors
        function loadPopularMotors() {
            const container = document.getElementById('popularMotors');
            const availableMotors = sampleMotors.filter(m => m.status === 'tersedia').slice(0, 3);
            container.innerHTML = availableMotors.map(motor => createMotorCard(motor)).join('');
        }

        function loadMotorsList() {
            const container = document.getElementById('motorsList');
            container.innerHTML = sampleMotors.map(motor => createMotorCard(motor)).join('');
        }

        function filterMotors() {
            const merk = document.getElementById('filterMerk').value;
            const cc = document.getElementById('filterCC').value;
            const status = document.getElementById('filterStatus').value;
            const price = document.getElementById('filterPrice').value;

            let filtered = sampleMotors;

            if (merk) filtered = filtered.filter(m => m.merk === merk);
            if (cc) filtered = filtered.filter(m => m.tipe_cc === cc);
            if (status) filtered = filtered.filter(m => m.status === status);
            if (price) filtered = filtered.filter(m => m.tarif_harian <= parseInt(price));

            const container = document.getElementById('motorsList');
            container.innerHTML = filtered.length > 0 
                ? filtered.map(motor => createMotorCard(motor)).join('')
                : '<div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: var(--gray-600);"><h3>Tidak ada motor yang sesuai filter</h3></div>';
        }

        // Booking
        function openBookingModal(motorId) {
            if (!currentUser) {
                showModal('loginModal');
                return;
            }

            currentMotorId = motorId;
            selectedMotor = sampleMotors.find(m => m.id === motorId);
            
            const infoHtml = `
                <div style="background: var(--gray-100); padding: 1.5rem; border-radius: 16px;">
                    <h3 style="margin-bottom: 0.5rem;">${selectedMotor.merk} ${selectedMotor.model}</h3>
                    <p style="color: var(--gray-600); font-size: 0.9rem;">${selectedMotor.tipe_cc} cc • ${selectedMotor.no_plat}</p>
                </div>
            `;
            
            document.getElementById('selectedMotorInfo').innerHTML = infoHtml;
            calculatePrice();
            showModal('bookingModal');
        }

        function calculatePrice() {
            if (!selectedMotor) return;
            
            const duration = document.getElementById('bookingDuration').value;
            let price = 0;
            
            switch(duration) {
                case 'harian':
                    price = selectedMotor.tarif_harian;
                    break;
                case 'mingguan':
                    price = selectedMotor.tarif_mingguan;
                    break;
                case 'bulanan':
                    price = selectedMotor.tarif_bulanan;
                    break;
            }
            
            document.getElementById('bookingPrice').value = `Rp ${price.toLocaleString('id-ID')}`;
        }

        function handleBooking(e) {
            e.preventDefault();
            alert('🎉 Booking berhasil! Silakan lakukan pembayaran dalam 24 jam.');
            closeModal('bookingModal');
            
            if (currentUser.role === 'penyewa') {
                showPage('dashboard');
                loadDashboardContent('bookings');
            }
        }

        // Auth Functions
        function handleLogin(e) {
            e.preventDefault();
            const form = e.target;
            const role = 'penyewa'; // Demo: default role
            
            currentUser = {
                name: 'John Doe',
                role: role,
                email: form.elements[0].value
            };
            
            updateUserUI();
            closeModal('loginModal');
            
            setTimeout(() => {
                alert('✅ Login berhasil! Selamat datang di RentRide.');
            }, 300);
        }

        function handleRegister(e) {
            e.preventDefault();
            const form = e.target;
            const role = document.getElementById('registerRole').value;
            
            currentUser = {
                name: form.elements[0].value,
                role: role,
                email: form.elements[1].value
            };
            
            updateUserUI();
            closeModal('registerModal');
            
            setTimeout(() => {
                alert('🎉 Registrasi berhasil! Selamat bergabung dengan RentRide.');
            }, 300);
        }

        function updateUserUI() {
            document.getElementById('authButtons').classList.add('hidden');
            document.getElementById('userProfile').classList.remove('hidden');
            document.getElementById('userName').textContent = currentUser.name;
            document.getElementById('userRole').textContent = currentUser.role.charAt(0).toUpperCase() + currentUser.role.slice(1);
            document.getElementById('userAvatar').textContent = currentUser.name.charAt(0);
            
            const navMenu = document.querySelector('.nav-menu');
            if (!document.querySelector('.nav-menu .dashboard-link')) {
                navMenu.innerHTML += '<li class="dashboard-link"><a href="#" onclick="showPage(\'dashboard\')">Dashboard</a></li>';
            }
        }

        function toggleUserMenu() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                logout();
            }
        }

        function logout() {
            currentUser = null;
            document.getElementById('authButtons').classList.remove('hidden');
            document.getElementById('userProfile').classList.add('hidden');
            
            const dashboardLink = document.querySelector('.dashboard-link');
            if (dashboardLink) dashboardLink.remove();
            
            showPage('home');
            alert('👋 Anda telah keluar. Sampai jumpa lagi!');
        }

        // Dashboard
        function loadDashboard() {
            if (!currentUser) {
                showPage('home');
                return;
            }

            const menu = document.getElementById('dashboardMenu');

            if (currentUser.role === 'penyewa') {
                menu.innerHTML = `
                    <li class="active" onclick="loadDashboardContent('bookings')">
                        📋 Riwayat Booking
                    </li>
                    <li onclick="loadDashboardContent('profile')">
                        👤 Profil Saya
                    </li>
                `;
                loadDashboardContent('bookings');
            } else if (currentUser.role === 'pemilik') {
                menu.innerHTML = `
                    <li class="active" onclick="loadDashboardContent('my-motors')">
                        🏍️ Motor Saya
                    </li>
                    <li onclick="loadDashboardContent('add-motor')">
                        ➕ Tambah Motor
                    </li>
                    <li onclick="loadDashboardContent('revenue')">
                        💰 Pendapatan
                    </li>
                    <li onclick="loadDashboardContent('profile')">
                        👤 Profil Saya
                    </li>
                `;
                loadDashboardContent('my-motors');
            }
        }

        function loadDashboardContent(section) {
            const content = document.getElementById('dashboardContent');
            const menuItems = document.querySelectorAll('.sidebar-menu li');
            menuItems.forEach(item => item.classList.remove('active'));
            event.target.classList.add('active');

            switch(section) {
                case 'bookings':
                    content.innerHTML = `
                        <div class="section-header" style="text-align: left;">
                            <h2 class="section-title">Riwayat Booking</h2>
                            <p class="section-subtitle" style="margin: 0;">Lihat semua booking motor Anda</p>
                        </div>
                        
                        <div class="stats-grid">
                            <div class="stat-card">
                                <h3>5</h3>
                                <p>Total Booking</p>
                            </div>
                            <div class="stat-card">
                                <h3>2</h3>
                                <p>Sedang Berlangsung</p>
                            </div>
                            <div class="stat-card">
                                <h3>3</h3>
                                <p>Selesai</p>
                            </div>
                        </div>

                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Motor</th>
                                        <th>Tanggal</th>
                                        <th>Durasi</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Honda Beat Street</strong></td>
                                        <td>01 Des 2025</td>
                                        <td>3 Hari</td>
                                        <td><strong>Rp 225.000</strong></td>
                                        <td><span class="badge badge-available">Aktif</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Yamaha NMAX</strong></td>
                                        <td>25 Nov 2025</td>
                                        <td>1 Minggu</td>
                                        <td><strong>Rp 750.000</strong></td>
                                        <td><span class="badge badge-available">Selesai</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `;
                    break;

                case 'my-motors':
                    content.innerHTML = `
                        <div class="section-header" style="text-align: left;">
                            <h2 class="section-title">Motor Saya</h2>
                            <p class="section-subtitle" style="margin: 0;">Kelola semua motor yang Anda sewakan</p>
                        </div>
                        
                        <div class="stats-grid">
                            <div class="stat-card">
                                <h3>3</h3>
                                <p>Total Motor</p>
                            </div>
                            <div class="stat-card">
                                <h3>2</h3>
                                <p>Tersedia</p>
                            </div>
                            <div class="stat-card">
                                <h3>1</h3>
                                <p>Disewa</p>
                            </div>
                        </div>

                        <div class="cards-grid">
                            ${sampleMotors.slice(0, 3).map(motor => createMotorCard(motor)).join('')}
                        </div>
                    `;
                    break;

                case 'add-motor':
                    content.innerHTML = `
                        <div class="section-header" style="text-align: left;">
                            <h2 class="section-title">Tambah Motor Baru</h2>
                            <p class="section-subtitle" style="margin: 0;">Lengkapi informasi motor yang akan disewakan</p>
                        </div>

                        <div class="glass" style="padding: 3rem; border-radius: 24px;">
                            <form onsubmit="handleAddMotor(event)">
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem;">
                                    <div class="form-group">
                                        <label>Merk Motor</label>
                                        <input type="text" class="form-control" placeholder="Honda, Yamaha, dll" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Model</label>
                                        <input type="text" class="form-control" placeholder="Beat, NMAX, dll" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tipe CC</label>
                                        <input type="number" class="form-control" placeholder="110" required>
                                    </div>
                                    <div class="form-group">
                                        <label>No. Plat</label>
                                        <input type="text" class="form-control" placeholder="B 1234 XYZ" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tarif Harian (Rp)</label>
                                        <input type="number" class="form-control" placeholder="75000" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tarif Mingguan (Rp)</label>
                                        <input type="number" class="form-control" placeholder="450000" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Tarif Bulanan (Rp)</label>
                                    <input type="number" class="form-control" placeholder="1500000" required>
                                </div>
                                <button type="submit" class="btn btn-gradient" style="margin-top: 2rem; padding: 1rem 2rem;">
                                    ➕ Tambah Motor
                                </button>
                            </form>
                        </div>
                    `;
                    break;

                case 'revenue':
                    content.innerHTML = `
                        <div class="section-header" style="text-align: left;">
                            <h2 class="section-title">Pendapatan</h2>