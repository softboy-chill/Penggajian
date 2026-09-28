<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Maju Jaya - Payroll System</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Variabel Tema Asli untuk Konten */
            --bg-primary: #f8fafc; 
            --bg-secondary: #ffffff; 
            --bg-glass: rgba(255, 255, 255, 0.85); 
            --border-glass: rgba(226, 232, 240, 0.8); 
            
            --primary: #4f46e5; 
            --primary-hover: #4338ca;
            --primary-glow: rgba(79, 70, 229, 0.15);
            --primary-glow-bg: rgba(79, 70, 229, 0.05);
            --primary-light: #6366f1;
            --primary-accent: #818cf8;
            --primary-soft: rgba(79, 70, 229, 0.05);
            --primary-border: rgba(79, 70, 229, 0.1);
            
            --success: #10b981;
            --success-glow: rgba(16, 185, 129, 0.15);
            --success-glow-bg: rgba(16, 185, 129, 0.05);
            
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --danger-glow: rgba(239, 68, 68, 0.15);
            --danger-light: #f87171;
            --danger-soft: rgba(239, 68, 68, 0.08);
            
            --text-main: #0f172a; 
            --text-muted: #475569; 
            --text-dark: #94a3b8; 
            
            --font-display: 'Outfit', sans-serif;
            --font-sans: 'Inter', sans-serif;
            
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --radius: 12px;

            /* Variabel Kustom Kontrol Sidebar Gelap */
            --sidebar-width: 260px;
            --sidebar-bg: #211472ff; 
            --sidebar-bg-hover: rgba(255, 255, 255, 0.08);
            --sidebar-bg-active: #0766ffff; 
            --sidebar-text: #d4dce7ff;
            --sidebar-text-light: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-main);
            font-family: var(--font-sans);
            min-height: 100vh;
            display: flex;
            flex-direction: row;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 25% 20%, var(--primary-glow-bg) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.03) 0%, transparent 45%);
            background-attachment: fixed;
        }

        /* 🛠️ PERBAIKAN CSS UNTUK HALAMAN LOGIN GUEST (BELUM LOGIN) */
        body.guest-mode {
            justify-content: center;
            align-items: center;
        }

        body.guest-mode .main-wrapper {
            margin-left: 0 !important;
            width: 100%;
            max-width: 550px;
            justify-content: center;
        }

        body.guest-mode .container {
            padding: 0 1.5rem;
            margin: 0;
        }

        /* STRUKTUR SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1.5rem 0;
        }

        .sidebar-brand {
            padding: 0 1.5rem 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-decoration: none;
            display: block;
        }

        .sidebar-brand-title {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--sidebar-text-light);
            letter-spacing: 0.5px;
        }

        .sidebar-brand-sub {
            font-size: 0.75rem;
            color: var(--sidebar-text);
            display: block;
            margin-top: 2px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1.5rem 0.75rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.75rem 1rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 8px;
            transition: var(--transition);
        }

        .sidebar-link:hover {
            background: var(--sidebar-bg-hover);
            color: var(--sidebar-text-light);
        }

        .sidebar-link.active {
            background: var(--sidebar-bg-active);
            color: var(--sidebar-text-light);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .btn-logout {
            width: 100%;
            background: var(--danger);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: var(--transition);
        }

        .btn-logout:hover {
            background: var(--danger-hover);
            transform: translateY(-1px);
        }

        /* STRUKTUR AREA KONTEN UTAMA KANAN */
        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .top-profile-bar {
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-glass);
            padding: 1rem 2.5rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-main);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Styling General Element */
        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius);
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: var(--transition);
            box-shadow: 0 4px 12px var(--primary-glow);
            font-size: 0.9rem;
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
        }

        .btn-secondary {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: var(--text-muted);
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: var(--text-main);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2.5rem;
            flex: 1;
        }

        .card {
            background: var(--bg-secondary);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            margin-bottom: 2rem;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            border: 1px solid transparent;
        }
        .alert-success { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border-color: #fca5a5; }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; }
        .form-control { width: 100%; padding: 0.75rem 1rem; background: #ffffff; border: 1px solid #cbd5e1; border-radius: var(--radius); color: var(--text-main); }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }

        .table-responsive { width: 100%; overflow-x: auto; border-radius: var(--radius); border: 1px solid #e2e8f0; background: #ffffff; }
        .table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        .table th { background: #f8fafc; color: var(--text-muted); font-weight: 600; padding: 1rem; border-bottom: 1px solid #e2e8f0; text-transform: uppercase; font-size: 0.75rem; }
        .table td { padding: 1rem; border-bottom: 1px solid #e2e8f0; color: var(--text-main); }
        .table tr:hover { background: #f8fafc; }

        footer { text-align: center; padding: 2rem; margin-top: auto; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 0.85rem; background: #ffffff; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.4s ease forwards; }

        @media print {
            .sidebar, .top-profile-bar, footer, .no-print { display: none !important; }
            .main-wrapper { margin-left: 0 !important; }
            body { background: white !important; }
        }
    </style>
</head>
<!-- 🛠️ Class otomatis berubah tergantung status login pengguna -->
<body class="{{ Auth::check() ? 'logged-in' : 'guest-mode' }}">

    @auth
    <!-- SIDEBAR NAVIGASI (HANYA AKTIF SAAT LOGGED-IN) -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="sidebar-brand-title">PT MAJU JAYA</span>
            <span class="sidebar-brand-sub">Sistem Penggajian Pegawai</span>
        </div>

        <ul class="sidebar-menu">
            @if(Auth::user()->role === 'admin')
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.penggajian.index') }}" class="sidebar-link {{ request()->routeIs('admin.penggajian.*') ? 'active' : '' }}">
                        <span>Kelola Penggajian</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pegawai.index') }}" class="sidebar-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}">
                        <span>Kelola Pegawai</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.absensi.index') }}" class="sidebar-link {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                        <span>Kelola Absensi</span>
                    </a>
                </li>
            @elseif(Auth::user()->role === 'pegawai')
                <li>
                    <a href="{{ route('pegawai.dashboard') }}" class="sidebar-link {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}">
                        <span>Slip Gaji Saya</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pegawai.absensi') }}" class="sidebar-link {{ request()->routeIs('pegawai.absensi') ? 'active' : '' }}">
                        <span>Absensi Saya</span>
                    </a>
                </li>
            @endif
        </ul>

        <div class="sidebar-footer no-print">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
    @endauth

    <!-- AREA UTAMA KANAN -->
    <div class="main-wrapper">
        @auth
        <header class="top-profile-bar">
            <div class="user-info">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-role">{{ Auth::user()->role }}</span>
            </div>
        </header>
        @endauth

        <main class="container animate-fade-in">
            @if(session('success'))
                <div class="alert alert-success">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <footer>
            &copy; 2026 PT Maju Jaya. Sistem Informasi Penggajian Internal.
        </footer>
    </div>

</body>
</html>