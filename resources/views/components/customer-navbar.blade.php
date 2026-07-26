{{--
    Navbar khusus untuk halaman-halaman customer (dashboard, pesanan, cart, dll).
    JANGAN dipakai di halaman admin/apoteker/kurir — mereka punya navbar/layout sendiri.

    Cara pakai, taruh di mana navbar biasanya berada:
        <x-customer-navbar />

    Cart count dihitung otomatis di sini (tidak perlu lagi kirim $cartCount dari
    controller manapun), supaya angkanya selalu akurat & konsisten di semua halaman.
--}}
@php
    $cartCount = auth()->check()
        ? \App\Models\CartItem::countForUser(auth()->id())
        : 0;
@endphp

<style>
    /* ===== NAVBAR (Customer) ===== */
    .navbar{
        position:sticky;
        top:0;
        z-index:50;
        background:var(--white);
        border-bottom:1px solid var(--mint-deep);
    }

    .navbar-inner{
        max-width:1240px;
        margin:0 auto;
        padding:14px 28px;
        display:flex;
        align-items:center;
        gap:28px;
    }

    .nav-brand{
        display:flex;
        align-items:center;
        gap:10px;
        flex-shrink:0;
    }

    .nav-brand .logo-box{
        width:42px; height:42px;
        border-radius:12px;
        background:var(--mint);
        display:flex;
        align-items:center;
        justify-content:center;
        padding:6px;
        flex-shrink:0;
    }

    .nav-brand .logo-box img{width:100%; height:100%; object-fit:contain;}

    .nav-brand .brand-text{
        font-family:'Outfit', sans-serif;
        font-weight:800;
        font-size:17px;
        line-height:1.15;
        color:var(--ink);
    }

    .nav-brand .brand-text span{
        display:block;
        font-family:'Inter', sans-serif;
        font-weight:500;
        font-size:10.5px;
        color:var(--muted);
        letter-spacing:.02em;
    }

    .nav-links{
        display:flex;
        align-items:center;
        gap:4px;
        flex:1;
    }

    .nav-links a{
        font-size:13.5px;
        font-weight:600;
        color:var(--muted);
        padding:9px 15px;
        border-radius:999px;
        transition:background .15s, color .15s;
        white-space:nowrap;
    }

    .nav-links a:hover{background:var(--mint); color:var(--ink);}
    .nav-links a.active{background:var(--spring); color:var(--white);}

    .nav-actions{
        display:flex;
        align-items:center;
        gap:10px;
        flex-shrink:0;
    }

    .icon-btn{
        width:40px; height:40px;
        border-radius:12px;
        border:none;
        background:var(--mint);
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        color:var(--spring-deep);
        position:relative;
        transition:background .15s;
    }
    .icon-btn:hover{background:var(--mint-deep);}
    .icon-btn svg{width:19px; height:19px;}

    .cart-count{
        position:absolute;
        top:-4px; right:-4px;
        background:var(--error);
        color:var(--white);
        font-size:10px;
        font-weight:700;
        width:17px; height:17px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        border:2px solid var(--white);
    }

    .avatar-btn{
        width:40px; height:40px;
        border-radius:50%;
        background:var(--spring);
        color:var(--white);
        border:none;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:14px;
        cursor:pointer;
        display:flex;
        align-items:center;
        justify-content:center;
        text-decoration:none;
    }

    .nav-toggle{
        display:none;
        width:40px; height:40px;
        border-radius:12px;
        border:none;
        background:var(--mint);
        align-items:center;
        justify-content:center;
        cursor:pointer;
    }
    .nav-toggle svg{width:20px; height:20px; color:var(--ink);}

    @media (max-width:920px){
        .nav-links{
            position:fixed;
            top:70px; left:0; right:0;
            background:var(--white);
            flex-direction:column;
            align-items:stretch;
            padding:10px 16px 16px;
            gap:2px;
            border-bottom:1px solid var(--mint-deep);
            box-shadow:var(--shadow-sm);
            display:none;
        }
        .nav-links.open{display:flex;}
        .nav-links a{padding:12px 14px;}
        .nav-toggle{display:flex;}
    }

    @media (max-width:560px){
        .navbar-inner{padding:12px 18px;}
    }
</style>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('dashboard') }}" class="nav-brand">
            <div class="logo-box">
                <img src="{{ asset('assets/images/logo-apotekrizki.png') }}" alt="Logo Apotek Rizki">
            </div>
            <div class="brand-text">Apotek Rizki<span>Layanan obat terpercaya</span></div>
        </a>

        <button class="nav-toggle" onclick="document.getElementById('navLinks').classList.toggle('open')" aria-label="Buka menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
        </button>

        <div class="nav-links" id="navLinks">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('resep.upload') }}" class="{{ request()->routeIs('resep.*') ? 'active' : '' }}">Upload Resep</a>
            <a href="{{ route('pesanan.index') }}" class="{{ request()->routeIs('pesanan.*') ? 'active' : '' }}">Pesanan Saya</a>
        </div>

        <div class="nav-actions">
            <a href="{{ route('cart.index') }}" class="icon-btn" aria-label="Keranjang">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.6a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                <span class="cart-count" id="navCartCount">{{ $cartCount }}</span>
            </a>
            <a href="{{ route('profile.index') }}" class="avatar-btn" aria-label="Akun saya">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </a>
        </div>
    </div>
</nav>