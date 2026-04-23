<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DenahMakam - Sistem Manajemen Pemakaman Modern</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #18181b;
            --primary-light: #2d2d2d;
            --secondary: #71717a;
            --light: #f4f4f5;
            --dark: #09090b;
            --white: #ffffff;
            --gray-100: #f4f4f5;
            --gray-200: #e4e4e7;
            --gray-300: #d4d4d8;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--dark);
            color: var(--white);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Abstract Background Pattern */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: -1;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(255, 255, 255, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(255, 255, 255, 0.03) 0%, transparent 50%);
        }

        .bg-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: -2;
            background-size: 50px 50px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Navigation */
        nav {
            padding: 1.5rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
            background: rgba(9, 9, 11, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: var(--transition);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo i {
            color: var(--light);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--gray-300);
            transition: var(--transition);
            position: relative;
        }

        .nav-link:hover {
            color: var(--white);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 0%;
            height: 2px;
            background: var(--white);
            transition: var(--transition);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn {
            padding: 0.75rem 1.75rem;
            border-radius: 99px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--white);
            color: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: var(--gray-200);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(255, 255, 255, 0.15);
        }

        .btn-outline {
            background: transparent;
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-outline:hover {
            border-color: var(--white);
            background: rgba(255, 255, 255, 0.05);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 8rem 5% 5rem;
            position: relative;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 99px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--gray-300);
            margin-bottom: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            animation: fadeInDown 1s ease forwards;
            opacity: 0;
            transform: translateY(-20px);
        }

        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            max-width: 900px;
            background: linear-gradient(to right, #fff, #a1a1aa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: fadeInUp 1s ease 0.2s forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .hero p {
            font-size: clamp(1rem, 2vw, 1.25rem);
            color: var(--gray-300);
            max-width: 600px;
            margin-bottom: 3rem;
            animation: fadeInUp 1s ease 0.4s forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            animation: fadeInUp 1s ease 0.6s forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        /* Features Section */
        .features {
            padding: 6rem 5%;
            background: #0a0a0c;
            position: relative;
        }

        .features-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .features-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .features-header p {
            color: var(--gray-400);
            max-width: 500px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 2.5rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 0%, rgba(255, 255, 255, 0.05) 0%, transparent 60%);
            opacity: 0;
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.04);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--white);
            transition: var(--transition);
        }

        .feature-card:hover .feature-icon {
            background: var(--white);
            color: var(--primary);
        }

        .feature-card h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .feature-card p {
            color: var(--gray-400);
            font-size: 0.95rem;
            line-height: 1.7;
        }

        /* Footer */
        footer {
            background: #050505;
            padding: 4rem 5% 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto 3rem;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .footer-logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .footer-sub {
            color: var(--gray-500);
            font-size: 0.9rem;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--gray-500);
            font-size: 0.85rem;
        }

        /* Animations */
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

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none; /* simple mobile nav */
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero-cta {
                flex-direction: column;
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    <div class="bg-grid"></div>

    <nav>
        <div class="logo">
            <i data-lucide="map-pin"></i>
            DenahMakam
        </div>
        <div class="nav-links">
            <a href="#fitur" class="nav-link">Fitur</a>
            <a href="#tentang" class="nav-link">Tentang</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">Daftar</a>
            @endauth
        </div>
    </nav>

    <section class="hero">
        <div class="hero-badge">
            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981; box-shadow: 0 0 10px #10b981;"></span>
            Sistem Manajemen Terpadu v2.0
        </div>
        <h1>Kelola Data Makam dengan Mudah & Akurat</h1>
        <p>Platform digital untuk manajemen lahan pemakaman. Memudahkan pendataan, pencarian lokasi, hingga pemesanan dengan sistem terintegrasi.</p>
        <div class="hero-cta">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    Buka Dashboard <i data-lucide="arrow-right" style="width: 18px;"></i>
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary">
                    Mulai Sekarang <i data-lucide="arrow-right" style="width: 18px;"></i>
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline">
                    Masuk Akun
                </a>
            @endauth
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
                <p>Lihat ketersediaan lahan makam melalui visualisasi grid yang interaktif. Status lahan (tersedia, dipesan, terisi) terupdate secara real-time.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="file-text"></i>
                </div>
                <h3>Pemesanan Online</h3>
                <p>Memudahkan masyarakat untuk melakukan pemesanan lahan tanpa antre. Dilengkapi verifikasi dokumen dan konfirmasi pembayaran.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i data-lucide="users"></i>
                </div>
                <h3>Multi-Akses Role</h3>
                <p>Sistem aman yang terbagi aksesnya untuk Super Admin, Admin, Karyawan, dan Customer. Memastikan privasi dan kontrol data.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div>
                <div class="footer-logo">DenahMakam</div>
                <div class="footer-sub">Sistem Manajemen Pemakaman Modern</div>
            </div>
            <div style="display: flex; gap: 1rem; color: var(--gray-400);">
                <a href="#" style="transition: color 0.2s;"><i data-lucide="github"></i></a>
                <a href="#" style="transition: color 0.2s;"><i data-lucide="twitter"></i></a>
                <a href="#" style="transition: color 0.2s;"><i data-lucide="mail"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} DenahMakam. Hak cipta dilindungi undang-undang.
        </div>
    </footer>

    <script>
        lucide.createIcons();

        // Navbar blur on scroll
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.style.background = 'rgba(9, 9, 11, 0.9)';
                nav.style.borderBottom = '1px solid rgba(255, 255, 255, 0.1)';
            } else {
                nav.style.background = 'rgba(9, 9, 11, 0.7)';
                nav.style.borderBottom = '1px solid rgba(255, 255, 255, 0.05)';
            }
        });
    </script>
</body>
</html>
