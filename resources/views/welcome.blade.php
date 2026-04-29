<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Models\Setting::where('key', 'cemetery_name')->value('value') ?? 'DenahMakam' }} - Sistem Manajemen
        Pemakaman Modern</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <style>
        :root {
            --primary: #000000;
            --primary-dark: #1a1a1a;
            --primary-light: #333333;
            --primary-50: #f8f8f8;
            --primary-100: #e0e0e0;
            --secondary: #64748b;
            --success: #10b981;
            --white: #ffffff;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--white);
            color: var(--gray-900);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Subtle Background */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: -1;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(0, 0, 0, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0, 0, 0, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 50% 80%, rgba(0, 0, 0, 0.03) 0%, transparent 40%);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ─── Navigation ─── */
        nav {
            padding: 0.875rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            transition: var(--transition);
        }

        nav.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: var(--shadow-sm);
        }

        .logo {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
        }

        .logo i {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-600);
            transition: var(--transition);
            position: relative;
            padding: 0.25rem 0;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 0%;
            height: 2px;
            background: var(--primary);
            border-radius: 1px;
            transition: var(--transition);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* ─── Buttons ─── */
        .btn {
            padding: 0.7rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
            box-shadow: 0 4px 14px 0 rgba(0, 0, 0, 0.25);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35);
        }

        .btn-outline {
            background: transparent;
            color: var(--gray-700);
            border: 1.5px solid var(--gray-300);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-50);
        }

        .btn-ghost {
            background: transparent;
            color: var(--primary);
            padding: 0.5rem 1rem;
        }

        .btn-ghost:hover {
            background: var(--primary-50);
        }

        /* ─── Hero Section ─── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 7rem 5% 5rem;
            position: relative;
            max-width: 1300px;
            margin: 0 auto;
            gap: 4rem;
        }

        .hero-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeInUp 0.8s ease forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .hero-text h1 {
            font-size: clamp(2.2rem, 4.5vw, 3.5rem);
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1.15;
            margin-bottom: 1.25rem;
            color: var(--gray-900);
        }

        .hero-text h1 span {
            background: linear-gradient(135deg, var(--primary), var(--gray-600));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text p {
            font-size: 1.1rem;
            color: var(--secondary);
            max-width: 520px;
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        .hero-cta {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--gray-200);
            width: 100%;
        }

        .hero-stat h3 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary);
        }

        .hero-stat p {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-bottom: 0;
        }

        /* ─── Modal Background ─── */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            transform: translateY(20px) scale(0.95);
            transition: var(--transition);
            width: 100%;
            max-width: 420px;
            margin: 0 20px;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0) scale(1);
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: transparent;
            border: none;
            color: var(--gray-500);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            border-radius: 50%;
        }

        .modal-close:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }

        /* ─── Login Card ─── */

        .login-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow-xl);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--gray-500), var(--primary));
        }

        .login-card-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-card-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.4rem;
        }

        .login-card-header p {
            color: var(--gray-500);
            font-size: 0.9rem;
        }

        .login-card .form-group {
            margin-bottom: 1.25rem;
        }

        .login-card .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: var(--gray-700);
        }

        .login-card .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid var(--gray-200);
            border-radius: 12px;
            outline: none;
            font-size: 0.9rem;
            font-family: inherit;
            transition: var(--transition);
            background: var(--gray-50);
        }

        .login-card .form-control:focus {
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.1);
        }

        .login-card .form-control::placeholder {
            color: var(--gray-400);
        }

        .login-card .btn-login {
            width: 100%;
            padding: 0.85rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            background: var(--primary);
            color: var(--white);
            border: none;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        }

        .login-card .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35);
        }

        .login-card .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
            color: var(--gray-400);
            font-size: 0.8rem;
        }

        .login-card .divider::before,
        .login-card .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        .login-card .register-link {
            text-align: center;
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .login-card .register-link a {
            color: var(--primary);
            font-weight: 700;
            transition: var(--transition);
        }

        .login-card .register-link a:hover {
            color: var(--primary-dark);
        }

        .login-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* ─── Features Section ─── */
        .features {
            padding: 7rem 5%;
            background: var(--gray-50);
            position: relative;
        }

        .features-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .features-header h2 {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .features-header p {
            color: var(--secondary);
            font-size: 1.05rem;
            max-width: 550px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1100px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            border-color: var(--primary-light);
            box-shadow: var(--shadow-lg);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            background: var(--primary-100);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--primary);
            transition: var(--transition);
        }

        .feature-card:hover .feature-icon {
            background: var(--primary);
            color: var(--white);
            transform: scale(1.05);
        }

        .feature-card h3 {
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .feature-card p {
            color: var(--secondary);
            font-size: 0.95rem;
            line-height: 1.65;
        }

        /* ─── Footer ─── */
        footer {
            background: var(--white);
            padding: 4rem 5% 2rem;
            border-top: 1px solid var(--gray-200);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1100px;
            margin: 0 auto 2.5rem;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .footer-logo {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }

        .footer-sub {
            color: var(--gray-500);
            font-size: 0.85rem;
        }

        .footer-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            color: var(--gray-600);
            font-size: 0.9rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-item i {
            color: var(--gray-900);
        }

        .footer-bottom {
            max-width: 1100px;
            margin: 0 auto;
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-200);
            color: var(--gray-500);
            font-size: 0.85rem;
        }

        /* ─── Animations ─── */
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

        /* ─── Responsive ─── */
        @media (max-width: 900px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding-top: 6rem;
                gap: 2.5rem;
            }

            .hero-text p {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-cta {
                justify-content: center;
            }

            .hero-stats {
                justify-content: center;
            }

            .hero-login {
                flex: none;
                width: 100%;
                max-width: 420px;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero-stats {
                gap: 1.5rem;
            }

            .hero-stat h3 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>
    <div class="bg-pattern"></div>

    <nav id="main-nav">
        <div class="logo">
            <i data-lucide="map-pin"></i>
            {{ \App\Models\Setting::where('key', 'cemetery_name')->value('value') ?? 'DenahMakam' }}
        </div>
        <div class="nav-links">
            <a href="#fitur" class="nav-link">Fitur</a>
            <a href="#tentang" class="nav-link">Tentang</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary" style="padding: 0.5rem 1.25rem;">Dashboard</a>
            @else
                <button onclick="openLoginModal()" class="btn btn-primary" style="padding: 0.5rem 1.25rem;">Masuk</button>
                <a href="{{ route('register') }}" class="btn btn-outline" style="padding: 0.5rem 1.25rem;">Daftar</a>
            @endauth
        </div>
    </nav>

    <section class="hero">
        <div class="hero-text">
            <h1>Kelola Data Makam <br>dengan <span>Mudah & Akurat</span></h1>
            <p>Platform digital untuk manajemen lahan pemakaman. Memudahkan pendataan, pencarian lokasi, hingga
                pemesanan dengan sistem terintegrasi.</p>

            @auth
                <div class="hero-cta">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        Buka Dashboard <i data-lucide="arrow-right" style="width: 18px;"></i>
                    </a>
                </div>
            @else
                <div class="hero-cta">
                    <button onclick="openLoginModal()" class="btn btn-primary">
                        Masuk Sekarang <i data-lucide="log-in" style="width: 18px;"></i>
                    </button>
                    <a href="{{ route('register') }}" class="btn btn-outline">
                        Daftar Akun
                    </a>
                </div>
            @endauth

            <div class="hero-stats">
                <div class="hero-stat">
                    <h3>500+</h3>
                    <p>Lahan Tercatat</p>
                </div>
                <div class="hero-stat">
                    <h3>4</h3>
                    <p>Tipe Pengguna</p>
                </div>
                <div class="hero-stat">
                    <h3>24/7</h3>
                    <p>Akses Online</p>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="features">
        <div class="features-header">
            <h2>Fitur Unggulan</h2>
            <p>Sistem kami rancang untuk memfasilitasi kebutuhan administrasi dan pemetaan lahan secara real-time.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="map"></i>
                </div>
                <h3>Pemetaan Digital</h3>
                <p>Lihat ketersediaan lahan makam melalui visualisasi grid yang interaktif. Status lahan terupdate
                    secara real-time.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="file-text"></i>
                </div>
                <h3>Pemesanan Online</h3>
                <p>Memudahkan masyarakat untuk melakukan pemesanan lahan tanpa antre. Dilengkapi verifikasi dan
                    konfirmasi otomatis.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="users"></i>
                </div>
                <h3>Multi-Akses Role</h3>
                <p>Sistem aman yang terbagi aksesnya untuk Super Admin, Admin, Karyawan, dan Customer dengan kontrol
                    penuh.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div>
                <div class="footer-logo">
                    {{ \App\Models\Setting::where('key', 'cemetery_name')->value('value') ?? 'DenahMakam' }}
                </div>
                <div class="footer-sub">Sistem Manajemen Pemakaman Modern</div>
            </div>
            <div class="footer-info">
                <div class="info-item">
                    <i data-lucide="map-pin" style="width: 18px;"></i>
                    <span>Komp. Ruko Inti, Jl. Laksamana Bintan No.1,Sungai Panas,Kec.Batam Kota,Kota Batam,
                        Kepulauan Riau 29444,Indonesia.</span>
                </div>
                <div class="info-item">
                    <i data-lucide="clock" style="width: 18px;"></i>
                    <span>Buka Setiap Hari: 08:00 - 17:00</span>
                </div>
                <div class="info-item">
                    <i data-lucide="phone" style="width: 18px;"></i>
                    <span>{{ \App\Models\Setting::where('key', 'contact_number')->value('value') ?? '+62 812-7008-7756' }}</span>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }}
            {{ \App\Models\Setting::where('key', 'cemetery_name')->value('value') ?? 'DenahMakam' }}. Hak cipta
            dilindungi undang-undang.
        </div>
    </footer>

    @guest
        <div id="loginModal" class="modal-overlay">
            <div class="modal-content">
                <div class="login-card">
                    <button class="modal-close" onclick="closeLoginModal()">
                        <i data-lucide="x"></i>
                    </button>
                    <div class="login-card-header">
                        <h2>Masuk Akun</h2>
                        <p>Masukkan kredensial untuk melanjutkan</p>
                    </div>

                    @if(session('error'))
                        <div class="login-error">
                            <i data-lucide="alert-circle" style="width: 16px; flex-shrink: 0;"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div
                            style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.85rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i data-lucide="check-circle" style="width: 16px; flex-shrink: 0;"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required
                                autofocus value="{{ old('email') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn-login">
                            Masuk <i data-lucide="log-in" style="width: 16px; margin-left: 4px;"></i>
                        </button>
                    </form>

                    <div class="divider">atau</div>

                    <a href="{{ route('google.login') }}" class="btn btn-outline" style="width: 100%; padding: 0.85rem; margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 12px; font-weight: 600; font-size: 0.95rem; cursor: pointer; text-decoration: none; box-sizing: border-box;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                            <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                            <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                            <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                            <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                        </svg>
                        Login dengan Google
                    </a>

                    <a href="{{ route('google.login', ['type' => 'otp']) }}" class="btn btn-outline" style="width: 100%; padding: 0.85rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 12px; font-weight: 600; font-size: 0.95rem; cursor: pointer; text-decoration: none; box-sizing: border-box;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        Login dengan OTP
                    </a>

                    <div class="register-link">
                        Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    @endguest

    <!-- OTP Step 1: Pilih Email (Google Style) -->
    <div id="otpEmailModal" class="modal-overlay" style="z-index: 1050;">
        <div class="modal-content" style="max-width: 400px; background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="width: 56px; height: 56px; background: #e8f0fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 48 48">
                        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                        <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                    </svg>
                </div>
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #1a1a1a;">Pilih Akun</h3>
                <p style="margin: 5px 0 0; color: #5f6368; font-size: 0.95rem;">ke DenahMakam</p>
            </div>
            
            <div style="max-height: 250px; overflow-y: auto; margin-bottom: 1.5rem; border: 1px solid var(--gray-200); border-radius: 12px;">
                @if(isset($customers) && $customers->count() > 0)
                    @foreach($customers as $customer)
                        <div onclick="selectOtpAccount('{{ $customer->email }}', '{{ $customer->name }}')" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--gray-100); cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='var(--gray-50)'" onmouseout="this.style.background='transparent'">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; flex-shrink: 0;">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 600; font-size: 0.95rem; color: #202124; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $customer->name }}</div>
                                <div style="font-size: 0.85rem; color: #5f6368; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $customer->email }}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
                <div onclick="document.getElementById('otpManualForm').style.display = 'block'; this.style.display = 'none';" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='var(--gray-50)'" onmouseout="this.style.background='transparent'">
                    <div style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--gray-600);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <div style="flex: 1; font-weight: 500; font-size: 0.95rem; color: var(--gray-800);">Gunakan akun lain</div>
                </div>
            </div>

            <div id="otpManualForm" style="display: none; border-top: 1px solid var(--gray-200); padding-top: 1.25rem; margin-top: -0.5rem; margin-bottom: 1rem;">
                <div style="margin-bottom: 1.25rem; text-align: left;">
                    <label for="otpNameInput" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; color: var(--gray-700);">Nama Lengkap (Opsional)</label>
                    <input type="text" id="otpNameInput" class="form-control" placeholder="Nama Anda" style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--gray-200); border-radius: 12px; outline: none; font-size: 0.9rem; transition: all 0.3s; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 1.5rem; text-align: left;">
                    <label for="otpEmailInput" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; color: var(--gray-700);">Alamat Email</label>
                    <input type="email" id="otpEmailInput" class="form-control" placeholder="nama@email.com" style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--gray-200); border-radius: 12px; outline: none; font-size: 0.9rem; transition: all 0.3s; box-sizing: border-box;">
                </div>
                <button onclick="submitOTPEmailForm()" id="otpSubmitBtn" class="btn btn-primary" style="width: 100%; padding: 0.75rem; justify-content: center;">Lanjut</button>
            </div>

            <div id="otpSendError" style="display:none; color:#e53935; font-size:0.85rem; margin-bottom: 1rem; text-align: center;"></div>

            <div style="text-align: center;">
                <button onclick="document.getElementById('otpEmailModal').classList.remove('active')" class="btn btn-ghost" style="font-size: 0.9rem; color: var(--gray-600);">Batal</button>
            </div>
        </div>
    </div>

    <!-- OTP Step 2: Masukkan Kode -->
    <div id="otpInputModal" class="modal-overlay" style="z-index: 1050;">
        <div class="modal-content" style="max-width: 360px; background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2); text-align: center;">
            <div style="width: 56px; height: 56px; background: #e8f0fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
            <h3 style="margin-top: 0; font-size: 1.2rem; font-weight: 700; color: #1a1a1a;">Verifikasi OTP</h3>
            <p style="color: #666; font-size: 0.9rem; margin-bottom: 0.5rem;">Kode OTP dikirim ke:</p>
            <p id="otpTargetDisplay" style="font-weight: 600; color: #1a73e8; font-size: 0.9rem; margin-bottom: 1.25rem;"></p>

            <div style="display: flex; gap: 8px; justify-content: center; margin-bottom: 1.25rem;">
                <input type="text" maxlength="1" class="otp-digit" oninput="otpNext(this,0)" style="width:44px; height:52px; text-align:center; font-size:1.4rem; font-weight:700; border:1.5px solid #ddd; border-radius:8px; outline:none;">
                <input type="text" maxlength="1" class="otp-digit" oninput="otpNext(this,1)" style="width:44px; height:52px; text-align:center; font-size:1.4rem; font-weight:700; border:1.5px solid #ddd; border-radius:8px; outline:none;">
                <input type="text" maxlength="1" class="otp-digit" oninput="otpNext(this,2)" style="width:44px; height:52px; text-align:center; font-size:1.4rem; font-weight:700; border:1.5px solid #ddd; border-radius:8px; outline:none;">
                <input type="text" maxlength="1" class="otp-digit" oninput="otpNext(this,3)" style="width:44px; height:52px; text-align:center; font-size:1.4rem; font-weight:700; border:1.5px solid #ddd; border-radius:8px; outline:none;">
                <input type="text" maxlength="1" class="otp-digit" oninput="otpNext(this,4)" style="width:44px; height:52px; text-align:center; font-size:1.4rem; font-weight:700; border:1.5px solid #ddd; border-radius:8px; outline:none;">
                <input type="text" maxlength="1" class="otp-digit" oninput="otpNext(this,5)" style="width:44px; height:52px; text-align:center; font-size:1.4rem; font-weight:700; border:1.5px solid #ddd; border-radius:8px; outline:none;">
            </div>

            <div id="otpVerifyError" style="display:none; color:#e53935; font-size:0.85rem; margin-bottom: 0.75rem;"></div>
            <p style="font-size:0.8rem; color:#999; margin-bottom:1.25rem;">Belum dapat kode? <a href="#" onclick="resendOTP()" style="color:#1a73e8;">Kirim ulang</a></p>

            <div style="display: flex; gap: 10px;">
                <button onclick="document.getElementById('otpInputModal').classList.remove('active'); document.getElementById('otpEmailModal').classList.add('active')" class="btn btn-outline" style="flex: 1; padding: 0.75rem; justify-content: center;">← Kembali</button>
                <button onclick="verifyOTP()" id="otpVerifyBtn" class="btn btn-primary" style="flex: 1; padding: 0.75rem; justify-content: center;">Verifikasi</button>
            </div>
        </div>
    </div>

    <!-- Gmail Sent Toast -->
    <div id="gmailToast" style="position: fixed; top: 20px; right: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); width: 320px; display: flex; overflow: hidden; transform: translateX(120%); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 1100; border: 1px solid #e0e0e0;">
        <div style="background: #ea4335; width: 4px;"></div>
        <div style="padding: 12px; display: flex; gap: 12px; flex: 1;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48" style="flex-shrink: 0;">
                <path fill="#4caf50" d="M45,16.2l-5,2.75l-5,4.75V40h7c1.657,0,3-1.343,3-3V16.2z"></path>
                <path fill="#1e88e5" d="M3,16.2l3.614,1.71L13,23.7V40H6c-1.657,0-3-1.343-3-3V16.2z"></path>
                <polygon fill="#e53935" points="35,11.2 24,19.45 13,11.2 12,17 13,23.7 24,31.95 35,23.7 36,17"></polygon>
                <path fill="#c62828" d="M3,12.298V16.2l10,7.5V11.2L9.876,8.859C9.132,8.301,8.228,8,7.298,8h0C4.924,8,3,9.924,3,12.298z"></path>
                <path fill="#fbc02d" d="M45,12.298V16.2l-10,7.5V11.2l3.124-2.341C38.868,8.301,39.772,8,40.702,8h0 C43.076,8,45,9.924,45,12.298z"></path>
            </svg>
            <div style="text-align: left;">
                <div style="font-weight: 600; font-size: 0.9rem; color: #202124; margin-bottom: 2px;">Gmail</div>
                <div id="gmailToastEmail" style="font-size: 0.82rem; color: #5f6368;">Kode OTP telah dikirim!</div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        let selectedOtpEmail = '';

        function showOTPLoginModal() {
            closeLoginModal();
            document.getElementById('otpEmailModal').classList.add('active');
            document.getElementById('otpManualForm').style.display = 'none'; // Reset to list view
        }

        function selectOtpAccount(email, name) {
            document.getElementById('otpEmailInput').value = email;
            document.getElementById('otpNameInput').value = name;
            submitOTPEmailForm();
        }

        function submitOTPEmailForm() {
            let email = document.getElementById('otpEmailInput').value;
            let name = document.getElementById('otpNameInput').value;
            
            if (!email || !email.includes('@')) {
                document.getElementById('otpSendError').style.display = 'block';
                document.getElementById('otpSendError').textContent = 'Masukkan alamat email yang valid.';
                return;
            }

            document.getElementById('otpSendError').style.display = 'none';
            document.getElementById('otpSendError').textContent = '';

            let btn = document.getElementById('otpSubmitBtn');
            btn.disabled = true;
            btn.textContent = 'Mengirim...';

            fetch('{{ route("otp.send") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ email: email, name: name })
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Lanjut';
                if (data.success) {
                    selectedOtpEmail = email;
                    document.getElementById('otpEmailModal').classList.remove('active');
                    document.getElementById('otpTargetDisplay').textContent = email;
                    document.getElementById('otpInputModal').classList.add('active');
                    document.querySelectorAll('.otp-digit').forEach(i => i.value = '');
                    setTimeout(() => document.querySelectorAll('.otp-digit')[0].focus(), 300);
                    
                    // Show Gmail toast
                    document.getElementById('gmailToastEmail').textContent = 'Kode OTP dikirim ke ' + email;
                    const toast = document.getElementById('gmailToast');
                    toast.style.transform = 'translateX(0)';
                    setTimeout(() => toast.style.transform = 'translateX(120%)', 5000);
                } else {
                    document.getElementById('otpSendError').style.display = 'block';
                    document.getElementById('otpSendError').textContent = data.message;
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Lanjut';
                document.getElementById('otpSendError').style.display = 'block';
                document.getElementById('otpSendError').textContent = 'Gagal terhubung ke server.';
            });
        }

        function otpNext(input, idx) {
            input.value = input.value.replace(/[^0-9]/g,'');
            const digits = document.querySelectorAll('.otp-digit');
            if (input.value && idx < 5) digits[idx + 1].focus();
        }

        function resendOTP() {
            window.location.href = "{{ route('google.login', ['type' => 'otp']) }}";
        }

        function verifyOTP() {
            const digits = document.querySelectorAll('.otp-digit');
            const code = Array.from(digits).map(d => d.value).join('');
            if (code.length < 6) { document.getElementById('otpVerifyError').style.display='block'; document.getElementById('otpVerifyError').textContent='Masukkan 6 digit kode OTP.'; return; }

            const btn = document.getElementById('otpVerifyBtn');
            btn.disabled = true;
            btn.textContent = 'Memverifikasi...';
            document.getElementById('otpVerifyError').style.display = 'none';

            fetch('{{ route("otp.verify") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ email: selectedOtpEmail, otp: code })
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Verifikasi';
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    document.getElementById('otpVerifyError').style.display = 'block';
                    document.getElementById('otpVerifyError').textContent = data.message;
                    digits.forEach(d => { d.value = ''; d.style.borderColor = '#e53935'; });
                    setTimeout(() => digits.forEach(d => d.style.borderColor = '#ddd'), 1500);
                    digits[0].focus();
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Verifikasi';
                document.getElementById('otpVerifyError').style.display = 'block';
                document.getElementById('otpVerifyError').textContent = 'Gagal terhubung ke server.';
            });
        }

        function openLoginModal() {
            document.getElementById('loginModal').classList.add('active');
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.remove('active');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('loginModal');
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeLoginModal();
                    }
                });
            }

            @if(session('error') || $errors->any())
                openLoginModal();
            @endif

            @if(session('show_otp_verify'))
                selectedOtpEmail = "{{ session('show_otp_verify') }}";
                document.getElementById('otpTargetDisplay').textContent = selectedOtpEmail;
                document.getElementById('otpInputModal').classList.add('active');
                setTimeout(() => document.querySelectorAll('.otp-digit')[0].focus(), 300);
            @endif
        });

        // Navbar effect on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('main-nav');
            if (window.scrollY > 30) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>

</html>