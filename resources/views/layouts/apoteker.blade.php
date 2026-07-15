<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard') - Apotek Rizki</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root{
        --mint-50:#eafaf3;
        --mint-100:#d7f3e6;
        --mint-500:#10b981;
        --mint-600:#0ea472;
        --mint-700:#0c8a5f;
        --text-dark:#0f2f22;
        --text-muted:#6b7c74;
        --peach:#ffd9c2;
        --blue-soft:#eef6ff;
        --amber:#f59e0b;
        --amber-bg:#fff7e6;
    }
    *{box-sizing:border-box;margin:0;padding:0;}
    body{
        font-family:'Inter',sans-serif;
        background:var(--mint-50);
        min-height:100vh;
        color:var(--text-dark);
    }

    /* NAVBAR */
    .navbar{
        background:#fff;
        position:sticky;
        top:0;
        z-index:50;
        box-shadow:0 2px 12px rgba(15,47,34,.06);
    }
    .navbar-inner{
        max-width:1180px;
        margin:0 auto;
        padding:14px 24px;
        display:flex;
        align-items:center;
        justify-content:space-between;
    }
    .brand{
        display:flex;
        align-items:center;
        gap:10px;
        font-family:'Outfit',sans-serif;
        font-weight:700;
        font-size:18px;
        color:var(--text-dark);
        text-decoration:none;
    }
    .brand img{
        width:38px;height:38px;
        border-radius:10px;
        object-fit:cover;
    }
    .brand small{
        display:block;
        font-family:'Inter',sans-serif;
        font-weight:500;
        font-size:11px;
        color:var(--text-muted);
    }
    .nav-links{
        display:flex;
        align-items:center;
        gap:6px;
        list-style:none;
    }
    .nav-links a{
        display:flex;
        align-items:center;
        gap:6px;
        text-decoration:none;
        color:var(--text-muted);
        font-size:14px;
        font-weight:600;
        padding:9px 16px;
        border-radius:999px;
        transition:.15s ease;
    }
    .nav-links a:hover{
        background:var(--mint-50);
        color:var(--mint-700);
    }
    .nav-links a.active{
        background:var(--mint-500);
        color:#fff;
    }
    .nav-right{
        display:flex;
        align-items:center;
        gap:14px;
    }
    .user-chip{
        display:flex;
        align-items:center;
        gap:8px;
        font-size:13px;
        color:var(--text-dark);
    }
    .user-chip .avatar{
        width:34px;height:34px;
        border-radius:50%;
        background:var(--mint-100);
        color:var(--mint-700);
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
        font-family:'Outfit',sans-serif;
    }
    .logout-btn{
        background:none;
        border:1.5px solid #ffd4d0;
        color:#e0433c;
        font-size:13px;
        font-weight:600;
        padding:7px 14px;
        border-radius:999px;
        cursor:pointer;
        transition:.15s ease;
    }
    .logout-btn:hover{background:#fff1f0;}

    /* MOBILE MENU TOGGLE */
    .menu-toggle{
        display:none;
        background:none;
        border:none;
        cursor:pointer;
        padding:6px;
    }
    .menu-toggle svg{color:var(--text-dark);}

    /* MAIN CONTENT */
    .content{
        max-width:1180px;
        margin:0 auto;
        padding:32px 24px 60px;
    }

    @media (max-width:900px){
        .nav-links{
            position:fixed;
            top:68px;
            left:0;
            right:0;
            background:#fff;
            flex-direction:column;
            align-items:stretch;
            padding:12px;
            box-shadow:0 8px 20px rgba(15,47,34,.08);
            display:none;
            gap:4px;
        }
        .nav-links.open{display:flex;}
        .nav-links a{
            padding:12px 16px;
            border-radius:12px;
        }
        .menu-toggle{display:flex;}
        .user-chip span{display:none;}
    }
</style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('apoteker.dashboard') }}" class="brand">
            <img src="{{ asset('assets/images/logo-apotekrizki.png') }}" alt="Logo Apotek Rizki">
            <span>
                Apotek Rizki
                <small>Panel Apoteker</small>
            </span>
        </a>

        <ul class="nav-links" id="navLinks">
            <li><a href="{{ route('apoteker.dashboard') }}" class="{{ request()->routeIs('apoteker.dashboard') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                Dashboard
            </a></li>
            <li><a href="{{ route('apoteker.pesanan') }}" class="{{ request()->routeIs('apoteker.pesanan') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Pesanan
            </a></li>
            <li><a href="{{ route('apoteker.verifikasi') }}" class="{{ request()->routeIs('apoteker.verifikasi') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                Verifikasi Obat
            </a></li>
            <li><a href="{{ route('apoteker.profil') }}" class="{{ request()->routeIs('apoteker.profil') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
                Profil
            </a></li>
        </ul>

        <div class="nav-right">
            <div class="user-chip">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                <span>{{ auth()->user()->name ?? 'Apoteker' }}</span>
            </div>
            <form method="POST" action="{{ route('staff.logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Keluar</button>
            </form>
            <button class="menu-toggle" onclick="document.getElementById('navLinks').classList.toggle('open')">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
        </div>
    </div>
</nav>

<main class="content">
    @yield('content')
</main>

</body>
</html>