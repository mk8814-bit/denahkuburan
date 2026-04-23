<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Denah Kuburan' }} - Management System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    @auth
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <i data-lucide="map-pin" style="color: var(--primary)"></i>
                <h2>DenahMakam</h2>
            </div>
            <nav class="nav-list">
                <li class="nav-item">
                    @php
                        $dashRoute = 'dashboard';
                        if(auth()->user()->role === 'super_admin') $dashRoute = 'super-admin.dashboard';
                        elseif(auth()->user()->role === 'admin') $dashRoute = 'admin.dashboard';
                        elseif(auth()->user()->role === 'karyawan') $dashRoute = 'karyawan.dashboard';
                        elseif(auth()->user()->role === 'customer') $dashRoute = 'customer.dashboard';
                    @endphp
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs($dashRoute) || request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard"></i> Dashboard Utama
                    </a>
                </li>

                @if(auth()->user()->role === 'customer')
                <li class="nav-item">
                    <a href="{{ route('customer.dashboard') }}" class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        <i data-lucide="map"></i> Lokasi Makam
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('customer.graves.create') }}" class="nav-link {{ request()->routeIs('customer.graves.create') ? 'active' : '' }}">
                        <i data-lucide="plus-circle"></i> Tambah / Pesan Makam
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('customer.payments') }}" class="nav-link {{ request()->routeIs('customer.payments') ? 'active' : '' }}">
                        <i data-lucide="credit-card"></i> Pembayaran Makam
                    </a>
                </li>
                @endif

                
                @if(auth()->user()->role === 'super_admin')
                <li class="nav-item">
                    <a href="{{ route('super-admin.users') }}" class="nav-link {{ request()->routeIs('super-admin.users') ? 'active' : '' }}">
                        <i data-lucide="users"></i> Master Pengguna
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.settings') }}" class="nav-link {{ request()->routeIs('super-admin.settings') ? 'active' : '' }}">
                        <i data-lucide="settings"></i> Konfigurasi Global
                    </a>
                </li>
                @endif

                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                <li class="nav-item">
                    <a href="{{ route('admin.graves') }}" class="nav-link {{ request()->routeIs('admin.graves') ? 'active' : '' }}">
                        <i data-lucide="database"></i> Data Makam
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                        <i data-lucide="credit-card"></i> Konfirmasi Bayar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <i data-lucide="bar-chart"></i> Laporan Keuangan
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.heirs') }}" class="nav-link {{ request()->routeIs('admin.heirs') ? 'active' : '' }}">
                        <i data-lucide="book-user"></i> Database Ahli Waris
                    </a>
                </li>
                @endif

                @if(auth()->user()->role === 'karyawan')
                <li class="nav-item">
                    <a href="{{ route('admin.reservations') }}" class="nav-link {{ request()->routeIs('admin.reservations') ? 'active' : '' }}">
                        <i data-lucide="calendar-check"></i> Manajemen Reservasi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.maintenance') }}" class="nav-link {{ request()->routeIs('admin.maintenance') ? 'active' : '' }}">
                        <i data-lucide="wrench"></i> Jadwal Pemeliharaan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.heirs') }}" class="nav-link {{ request()->routeIs('admin.heirs') ? 'active' : '' }}">
                        <i data-lucide="book-user"></i> Database Ahli Waris
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i data-lucide="user"></i> Pengaturan Profil
                    </a>
                </li>

                <li class="nav-item" style="margin-top: auto">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link" style="width: 100%; border: none; cursor: pointer; text-align: left; background: none">
                            <i data-lucide="log-out"></i> Keluar
                        </button>
                    </form>
                </li>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header">
                <div>
                    <h1>{{ $title ?? 'Dashboard' }}</h1>
                    <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong> ({{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }})</p>
                </div>
                <div class="user-profile">
                    <a href="{{ route('profile.edit') }}">
                        <div class="user-img" style="{{ auth()->user()->photo ? "background-image: url('/storage/" . auth()->user()->photo . "'); background-size: cover; background-position: center;" : "background: var(--gray-300);" }} border: 2px solid var(--primary); cursor: pointer; border-radius: 50%; width: 40px; height: 40px;"></div>
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="card" style="background: #effaf3; border-left: 4px solid var(--success); color: #166534; padding: 1rem">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="card" style="background: #fef2f2; border-left: 4px solid var(--danger); color: #991b1b; padding: 1rem">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    @else
        @yield('content')
    @endauth

    @if(auth()->check() && auth()->user()->role === 'customer')
    <!-- Chatbot UI -->
    <div id="chatbot-window" style="display: none; position: fixed; bottom: 85px; right: 25px; width: 350px; height: 450px; background: white; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 9999; flex-direction: column; overflow: hidden; border: 1px solid var(--gray-200); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); transform: translateY(20px); opacity: 0;">
        <div style="background: linear-gradient(135deg, var(--primary), #2d2d2d); color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="bot" style="width: 18px;"></i>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 0.9rem;">Asisten DenahMakam</div>
                    <div style="font-size: 0.7rem; opacity: 0.8;">Online • Siap membantu</div>
                </div>
            </div>
            <button onclick="toggleChat()" style="background: none; border: none; color: white; cursor: pointer; opacity: 0.7; transition: opacity 0.2s;"><i data-lucide="x" style="width: 20px;"></i></button>
        </div>
        
        <div id="chat-messages" style="flex-grow: 1; padding: 15px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 10px;">
            <div style="align-self: flex-start; background: white; padding: 10px 14px; border-radius: 12px 12px 12px 2px; font-size: 0.85rem; max-width: 80%; box-shadow: 0 2px 4px rgba(0,0,0,0.05); color: var(--gray-800);">
                Halo {{ explode(' ', auth()->user()->name)[0] }}! 👋 Ada yang bisa saya bantu terkait reservasi makam atau informasi pelayanan kami?
            </div>
        </div>

        <div style="padding: 15px; background: white; border-top: 1px solid var(--gray-100); display: flex; gap: 8px;">
            <input type="text" id="chat-input" placeholder="Ketik pesan..." style="flex-grow: 1; border: 1px solid var(--gray-300); border-radius: 20px; padding: 8px 15px; font-size: 0.85rem; outline: none; transition: border-color 0.2s;" onkeypress="if(event.key === 'Enter') sendMessage()">
            <button onclick="sendMessage()" style="background: var(--primary); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;"><i data-lucide="send" style="width: 16px;"></i></button>
        </div>
    </div>

    <script>
        function toggleChat() {
            const win = document.getElementById('chatbot-window');
            const isHidden = win.style.display === 'none';
            if(isHidden) {
                win.style.display = 'flex';
                setTimeout(() => {
                    win.style.opacity = '1';
                    win.style.transform = 'translateY(0)';
                }, 10);
            } else {
                win.style.opacity = '0';
                win.style.transform = 'translateY(20px)';
                setTimeout(() => { win.style.display = 'none'; }, 300);
            }
        }

        document.getElementById('chatbot-toggle').onclick = toggleChat;

        async function sendMessage() {
            const input = document.getElementById('chat-input');
            const msg = input.value.trim();
            if(!msg) return;

            addMessage(msg, 'user');
            input.value = '';

            const typingId = 'typing-' + Date.now();
            addMessage("Mengetik...", 'bot', typingId);

            try {
                const response = await fetch('/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message: msg })
                });

                const data = await response.json();
                
                const typingEl = document.getElementById(typingId);
                if(typingEl) typingEl.remove();
                
                if (data.reply) {
                    addMessage(data.reply, 'bot');
                } else {
                    addMessage("Maaf, format respons tidak valid.", 'bot');
                }
            } catch (error) {
                const typingEl = document.getElementById(typingId);
                if(typingEl) typingEl.remove();
                addMessage("Maaf, koneksi ke server chatbot gagal.", 'bot');
            }
        }

        function addMessage(text, side, id = null) {
            const container = document.getElementById('chat-messages');
            const div = document.createElement('div');
            if(id) div.id = id;
            div.style.alignSelf = side === 'user' ? 'flex-end' : 'flex-start';
            div.style.background = side === 'user' ? 'var(--primary)' : 'white';
            div.style.color = side === 'user' ? 'white' : 'var(--gray-800)';
            div.style.padding = '10px 14px';
            div.style.borderRadius = side === 'user' ? '12px 12px 2px 12px' : '12px 12px 12px 2px';
            div.style.fontSize = '0.85rem';
            div.style.maxWidth = '90%';
            div.style.boxShadow = '0 2px 4px rgba(0,0,0,0.05)';
            div.style.whiteSpace = 'pre-wrap';
            div.innerText = text;
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
        }
    </script>
    @endif

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
