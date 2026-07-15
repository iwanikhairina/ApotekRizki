<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') - Apotek Rizki</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --mint-50:  #ecfdf5;
            --mint-100: #d1fae5;
            --mint-500: #10b981;
            --mint-600: #059669;
            --mint-700: #047857;
            --spring:       #34d399;
            --spring-deep:  #065f46;

            --ink-900: #0f172a;
            --ink-700: #334155;
            --ink-500: #64748b;
            --ink-300: #cbd5e1;
            --surface: #ffffff;
            --bg: #f4faf7;

            --danger: #ef4444;
            --warning: #f59e0b;

            --radius: 14px;
            --shadow-soft: 0 4px 16px rgba(16, 185, 129, 0.08);
            --shadow-card: 0 2px 10px rgba(15, 23, 42, 0.06);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--ink-900);
            min-height: 100vh;
        }

        h1, h2, h3, h4, .brand {
            font-family: 'Outfit', sans-serif;
        }

        a { text-decoration: none; color: inherit; }

        /* ===== NAVBAR ===== */
        .admin-navbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: var(--surface);
            border-bottom: 1px solid var(--mint-100);
            box-shadow: var(--shadow-soft);
        }

        .navbar-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 68px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--spring-deep);
        }

        .brand-logo{
    width:50px;
    height:50px;
    border-radius:12px;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    box-shadow:0 2px 8px rgba(16,185,129,.12);
}

.brand-logo img{
    width:100%;
    height:100%;
    object-fit:contain;
}

        .brand-sub {
            font-size: 0.68rem;
            font-weight: 500;
            color: var(--ink-500);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--ink-700);
            transition: all 0.15s ease;
        }

        .nav-link:hover {
            background: var(--mint-50);
            color: var(--mint-700);
        }

        .nav-link.active {
            background: var(--mint-100);
            color: var(--spring-deep);
            font-weight: 600;
        }

        .nav-link svg {
            width: 17px;
            height: 17px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .admin-chip {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 6px 12px 6px 6px;
            background: var(--mint-50);
            border-radius: 999px;
            border: 1px solid var(--mint-100);
        }

        .admin-avatar{
        width:34px;
        height:34px;
        font-size:.85rem;
            border-radius: 50%;
            background: var(--spring-deep);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .admin-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--ink-900);
        }

        .logout-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid var(--ink-300);
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--ink-500);
            transition: all 0.15s ease;
        }

        .logout-btn:hover {
            background: #fef2f2;
            border-color: #fecaca;
            color: var(--danger);
        }

        /* ===== MOBILE NAV TOGGLE ===== */
        .nav-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--ink-700);
        }

        @media (max-width: 900px) {
            .nav-links {
                position: fixed;
                top: 68px;
                left: 0;
                right: 0;
                background: var(--surface);
                flex-direction: column;
                align-items: stretch;
                padding: 10px 16px 16px;
                box-shadow: var(--shadow-card);
                display: none;
                gap: 2px;
            }
            .nav-links.open { display: flex; }
            .nav-toggle { display: flex; }
            .admin-name { display: none; }
        }

        /* ===== MAIN CONTENT ===== */
        .admin-main {
            max-width: 1280px;
            margin: 0 auto;
            padding: 28px 24px 60px;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ink-900);
        }

        .page-header p {
            color: var(--ink-500);
            font-size: 0.9rem;
            margin-top: 4px;
        }

        /* ===== SHARED CARD STYLE (dipakai di semua halaman admin) ===== */
        .card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            border: 1px solid var(--mint-100);
            padding: 20px;
        }

        /* ===== FLASH MESSAGE ===== */
        .flash {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.88rem;
            margin-bottom: 20px;
        }
        .flash-success {
            background: var(--mint-50);
            color: var(--spring-deep);
            border: 1px solid var(--mint-100);
        }
        .flash-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
    </style>

    @stack('styles')
</head>
<body>

    <nav class="admin-navbar">
        <div class="navbar-inner">
            <a href="{{ route('admin.dashboard') }}" class="brand">
                <div class="brand-logo">
    <img src="{{ asset('assets/images/logo-apotekrizki.png') }}" alt="Logo Apotek Rizki">
</div>
                <div>
                    <div>Apotek Rizki</div>
                    <div class="brand-sub">Panel Owner</div>
                </div>
            </a>

            <button class="nav-toggle" onclick="document.getElementById('navLinks').classList.toggle('open')">
                <svg viewBox="0 0 24 24" fill="none" width="24" height="24"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>

            <div class="nav-links" id="navLinks">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M3 12l9-9 9 9M5 10v10h14V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Dashboard
                </a>
                <a href="{{ Route::has('admin.staff.index') ? route('admin.staff.index') : '#' }}" class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Staff
                </a>
                <a href="{{ Route::has('admin.obat.index') ? route('admin.obat.index') : '#' }}" class="nav-link {{ request()->routeIs('admin.obat.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M10.5 20.5L20.5 10.5a4.95 4.95 0 00-7-7L3.5 13.5a4.95 4.95 0 007 7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 8.5l7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Obat
                </a>
                <a href="{{ Route::has('admin.pesanan.index') ? route('admin.pesanan.index') : '#' }}" class="nav-link {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M9 2h6a1 1 0 011 1v2H8V3a1 1 0 011-1zM4 7h16l-1 13a2 2 0 01-2 2H7a2 2 0 01-2-2L4 7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Pesanan
                </a>
                <a href="{{ Route::has('admin.laporan.index') ? route('admin.laporan.index') : '#' }}" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M3 3v18h18M7 15l4-6 3 4 5-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Laporan
                </a>
            </div>

            <div class="navbar-right">
                <div class="admin-chip">
                    <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    <span class="admin-name">Owner</span>
                </div>
                <form method="POST" action="{{ route('staff.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" title="Keluar">
                        <svg viewBox="0 0 24 24" fill="none" width="16" height="16"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="admin-main">
        @if(session('success'))
            <div class="flash flash-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

</body>
</html>