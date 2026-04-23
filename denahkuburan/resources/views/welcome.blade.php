<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DenahMakam - Sistem Manajemen Pemakaman Modern</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
            --primary-50: #eff6ff;
            --primary-100: #dbeafe;
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
                radial-gradient(circle at 20% 50%, rgba(37, 99, 235, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 50% 80%, rgba(37, 99, 235, 0.03) 0%, transparent 40%);
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

        .logo i { color: var(--primary); }

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

        .nav-link:hover { color: var(--primary); }

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

        .nav-link:hover::after { width: 100%; }

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
            box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.25);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
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
            padding: 7rem 5% 5rem;
            position: relative;
            max-width: 1300px;
            margin: 0 auto;
            gap: 4rem;
        }

        .hero-text {
            flex: 1;
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
            background: linear-gradient(135deg, var(--primary), #6366f1);
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
        }

        .hero-stats {
            display: flex;
            gap: 2.5rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--gray-200);
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

        /* ─── Login Card (Embedded in Hero) ─── */
        .hero-login {
            flex: 0 0 400px;
            animation: fadeInUp 0.8s ease 0.3s forwards;
            opacity: 0;
            transform: translateY(30px);
        }

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
            background: linear-gradient(90deg, var(--primary), #6366f1, var(--primary));
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
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
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
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
        }

        .login-card .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
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

        .footer-socials {
            display: flex;
            gap: 0.75rem;
        }

        .footer-socials a {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-500);
            transition: var(--transition);
        }

        .footer-socials a:hover {
            background: var(--primary);
            color: var(--white);
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

            .hero-text p { margin-left: auto; margin-right: auto; }
            .hero-cta { justify-content: center; }
            .hero-stats { justify-content: center; }

            .hero-login {
                flex: none;
                width: 100%;
                max-width: 420px;
            }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hero-stats { gap: 1.5rem; }
            .hero-stat h3 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>

    <nav id="main-nav">
        <div class="logo">
            <i data-lucide="map-pin"></i>
            DenahMakam
        </div>
        <div class="nav-links">
            <a href="#fitur" class="nav-link">Fitur</a>
            <a href="#tentang" class="nav-link">Tentang</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary" style="padding: 0.5rem 1.25rem;">Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-outline" style="padding: 0.5rem 1.25rem;">Daftar</a>
            @endauth
        </div>
    </nav>

    <section class="hero">
        <div class="hero-text">
            <h1>Kelola Data Makam <br>dengan <span>Mudah & Akurat</span></h1>
            <p>Platform digital untuk manajemen lahan pemakaman. Memudahkan pendataan, pencarian lokasi, hingga pemesanan dengan sistem terintegrasi.</p>

            @auth
                <div class="hero-cta">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        Buka Dashboard <i data-lucide="arrow-right" style="width: 18px;"></i>
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

        @guest
        <div class="hero-login">
            <div class="login-card">
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
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.85rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="check-circle" style="width: 16px; flex-shrink: 0;"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="nama@email.com" required autofocus value="{{ old('email') }}">
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

                <div class="register-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                </div>
            </div>
        </div>
        @endguest
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
                <p>Lihat ketersediaan lahan makam melalui visualisasi grid yang interaktif. Status lahan terupdate secara real-time.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="file-text"></i>
                </div>
                <h3>Pemesanan Online</h3>
                <p>Memudahkan masyarakat untuk melakukan pemesanan lahan tanpa antre. Dilengkapi verifikasi dan konfirmasi otomatis.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="users"></i>
                </div>
                <h3>Multi-Akses Role</h3>
                <p>Sistem aman yang terbagi aksesnya untuk Super Admin, Admin, Karyawan, dan Customer dengan kontrol penuh.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div>
                <div class="footer-logo">DenahMakam</div>
                <div class="footer-sub">Sistem Manajemen Pemakaman Modern</div>
            </div>
            <div class="footer-socials">
                <a href="#"><i data-lucide="github" style="width: 18px;"></i></a>
                <a href="#"><i data-lucide="twitter" style="width: 18px;"></i></a>
                <a href="#"><i data-lucide="mail" style="width: 18px;"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} DenahMakam. Hak cipta dilindungi undang-undang.
        </div>
    </footer>

    <script>
        lucide.createIcons();

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
