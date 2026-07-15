<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Profil Saya - Apotek Rizki</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root{
        --mint:#EAF7F0;
        --mint-deep:#D3EFE0;
        --spring:#12A874;
        --spring-deep:#0C7E57;
        --ink:#1D2B26;
        --muted:#7C8B84;
        --white:#FFFFFF;
        --error:#E0503B;
        --amber:#E8A33D;
        --shadow-sm:0 6px 16px -8px rgba(29,43,38,0.14);
        --shadow-md:0 20px 40px -16px rgba(29,43,38,0.18);
    }

    *{margin:0;padding:0;box-sizing:border-box;}

    body{
        font-family:'Inter', sans-serif;
        background:var(--mint);
        color:var(--ink);
        -webkit-font-smoothing:antialiased;
    }

    a{text-decoration:none; color:inherit;}
    button{font-family:inherit;}

    /* ===== NAVBAR (sama seperti dashboard) ===== */
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
    .avatar-btn.on-profile{background:var(--spring-deep);}

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

    /* ===== HERO ===== */
    .hero{
        max-width:1240px;
        margin:0 auto;
        padding:34px 28px 10px;
    }

    .hero-greeting{
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:24px;
        color:var(--ink);
        margin-bottom:4px;
    }

    .hero-sub{
        font-size:13.5px;
        color:var(--muted);
    }

    /* ===== LENGKAPI PROFIL BANNER ===== */
    .promo-banner{
        max-width:1240px;
        margin:22px auto 0;
        padding:0 28px;
    }

    .promo-inner{
        background:linear-gradient(120deg, var(--amber) 0%, #C9821F 100%);
        border-radius:24px;
        padding:22px 28px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:20px;
        color:var(--white);
        flex-wrap:wrap;
    }

    .promo-text{display:flex; align-items:center; gap:14px;}

    .promo-text .promo-ic{
        width:42px; height:42px;
        border-radius:12px;
        background:rgba(255,255,255,0.22);
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
    }
    .promo-text .promo-ic svg{width:22px; height:22px;}

    .promo-text h3{
        font-family:'Outfit', sans-serif;
        font-size:16px;
        font-weight:700;
        margin-bottom:2px;
    }
    .promo-text p{
        font-size:12.5px;
        opacity:.95;
        max-width:420px;
    }

    .promo-btn{
        background:var(--white);
        color:#B97F22;
        border:none;
        padding:12px 22px;
        border-radius:999px;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13px;
        cursor:pointer;
        display:flex;
        align-items:center;
        gap:8px;
        flex-shrink:0;
        transition:transform .12s;
    }
    .promo-btn:hover{transform:translateY(-2px);}
    .promo-btn svg{width:16px; height:16px;}

    /* ===== PROFILE SECTION ===== */
    .profile-section{
        max-width:1240px;
        margin:0 auto;
        padding:28px 28px 60px;
        display:grid;
        grid-template-columns: 300px 1fr;
        gap:22px;
        align-items:start;
    }

    /* kartu identitas kiri */
    .id-card{
        background:linear-gradient(150deg, var(--spring) 0%, var(--spring-deep) 100%);
        border-radius:24px;
        padding:30px 24px;
        color:var(--white);
        text-align:center;
        box-shadow:var(--shadow-sm);
    }

    .id-card .avatar-big{
        width:84px; height:84px;
        border-radius:50%;
        background:var(--amber);
        border:4px solid rgba(255,255,255,0.5);
        margin:0 auto 16px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:30px;
        color:var(--spring-deep);
    }

    .id-card h2{
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:19px;
        margin-bottom:4px;
    }

    .id-card .id-email{
        font-size:12.5px;
        opacity:.85;
        margin-bottom:18px;
        word-break:break-word;
    }

    .id-card .id-tag{
        display:inline-block;
        font-size:11px;
        font-weight:600;
        background:rgba(255,255,255,0.18);
        padding:5px 14px;
        border-radius:999px;
        margin-bottom:22px;
    }

    .id-card .edit-link{
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        background:var(--white);
        color:var(--spring-deep);
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13px;
        padding:12px;
        border-radius:14px;
        transition:transform .12s;
    }
    .id-card .edit-link:hover{transform:translateY(-2px);}
    .id-card .edit-link svg{width:16px; height:16px;}

    .id-card .logout-form{margin-top:10px;}

    .id-card .logout-btn{
        width:100%;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        background:rgba(255,255,255,0.12);
        border:1px solid rgba(255,255,255,0.35);
        color:var(--white);
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13px;
        padding:11px;
        border-radius:14px;
        cursor:pointer;
        transition:background .15s, transform .12s;
    }
    .id-card .logout-btn:hover{background:rgba(224,80,59,0.85); border-color:transparent;}
    .id-card .logout-btn:active{transform:scale(.98);}
    .id-card .logout-btn svg{width:16px; height:16px;}

    /* kartu data kanan */
    .detail-card{
        background:var(--white);
        border-radius:24px;
        padding:8px 0;
        box-shadow:var(--shadow-sm);
    }

    .detail-heading{
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding:20px 26px 14px;
    }

    .detail-heading h3{
        font-family:'Outfit', sans-serif;
        font-size:16px;
        font-weight:700;
    }

    .detail-heading a{
        font-size:12.5px;
        font-weight:700;
        color:var(--spring-deep);
        background:var(--mint);
        padding:8px 14px;
        border-radius:999px;
        display:flex;
        align-items:center;
        gap:6px;
        transition:background .15s;
    }
    .detail-heading a:hover{background:var(--mint-deep);}
    .detail-heading a svg{width:14px; height:14px;}

    .detail-list{
        display:flex;
        flex-direction:column;
    }

    .detail-row{
        display:flex;
        align-items:flex-start;
        gap:16px;
        padding:16px 26px;
        border-top:1px solid var(--mint);
    }

    .detail-row .row-ic{
        width:38px; height:38px;
        border-radius:11px;
        background:var(--mint);
        color:var(--spring-deep);
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
    }
    .detail-row .row-ic svg{width:18px; height:18px;}

    .detail-row .row-body{flex:1; min-width:0;}

    .detail-row .row-label{
        font-size:11px;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.04em;
        color:var(--muted);
        margin-bottom:3px;
    }

    .detail-row .row-value{
        font-size:14.5px;
        font-weight:600;
        color:var(--ink);
        word-break:break-word;
    }

    .detail-row .row-value.empty{
        color:var(--muted);
        font-weight:500;
        font-style:italic;
    }

    /* ===== RESPONSIVE ===== */
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
        .profile-section{grid-template-columns:1fr;}
    }

    @media (max-width:560px){
        .navbar-inner{padding:12px 18px;}
        .hero{padding:26px 18px 6px;}
        .promo-banner{padding:0 18px;}
        .profile-section{padding:22px 18px 50px;}
        .hero-greeting{font-size:20px;}
        .promo-inner{flex-direction:column; align-items:flex-start;}
        .promo-btn{width:100%; justify-content:center;}
    }
</style>
</head>
<body>

@php
    // Field yang dianggap penting untuk kelengkapan profil (misalnya dibutuhkan verifikasi usia produk).
    $isIncomplete = empty($user->phone) || empty($user->birth_date) || empty($user->alamat);
@endphp

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
            <a href="{{ route('dashboard') }}">Beranda</a>
            <a href="{{ route('resep.upload') }}">Upload Resep</a>
            <a href="{{ route('pesanan.index') }}">Pesanan Saya</a>
        </div>

        <div class="nav-actions">
            <a href="{{ route('cart.index') }}" class="icon-btn" aria-label="Keranjang">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.6a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                <span class="cart-count">3</span>
            </a>
            <a href="{{ route('profile.index') }}" class="avatar-btn on-profile" aria-label="Akun saya">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </a>
        </div>
    </div>
</nav>

<header class="hero">
    <h1 class="hero-greeting">Profil Saya</h1>
    <p class="hero-sub">Kelola data diri kamu supaya pesanan lebih cepat dan akurat.</p>
</header>

@if($isIncomplete)
<section class="promo-banner">
    <div class="promo-inner">
        <div class="promo-text">
            <span class="promo-ic">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v5"/><path d="M12 16h.01"/></svg>
            </span>
            <div>
                <h3>Lengkapi profil kamu</h3>
                <p>Nomor HP, tanggal lahir, dan alamat masih ada yang kosong. Data ini dibutuhkan untuk pengiriman dan verifikasi beberapa produk.</p>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="promo-btn">
            Lengkapi Profil
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        </a>
    </div>
</section>
@endif

<section class="profile-section">
    <div class="id-card">
        <div class="avatar-big">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
        <h2>{{ $user->name ?? '-' }}</h2>
        <div class="id-email">{{ $user->email ?? '-' }}</div>
        <span class="id-tag">Pelanggan Apotek Rizki</span>
        <a href="{{ route('profile.edit') }}" class="edit-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            Sunting Profil
        </a>

        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Keluar dari Akun
            </button>
        </form>
    </div>

    <div class="detail-card">
        <div class="detail-heading">
            <h3>Data Diri</h3>
            <a href="{{ route('profile.edit') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                Sunting
            </a>
        </div>

        <div class="detail-list">
            <div class="detail-row">
                <span class="row-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/></svg></span>
                <div class="row-body">
                    <div class="row-label">Nama</div>
                    <div class="row-value">{{ $user->name ?? '-' }}</div>
                </div>
            </div>

            <div class="detail-row">
                <span class="row-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span>
                <div class="row-body">
                    <div class="row-label">Tanggal Lahir</div>
                    @if($user->birth_date)
                        <div class="row-value">{{ \Carbon\Carbon::parse($user->birth_date)->translatedFormat('d F Y') }}</div>
                    @else
                        <div class="row-value empty">Belum diisi</div>
                    @endif
                </div>
            </div>

            <div class="detail-row">
                <span class="row-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 13a4 4 0 0 0 8 0"/><path d="M9 9h.01M15 9h.01"/></svg></span>
                <div class="row-body">
                    <div class="row-label">Jenis Kelamin</div>
                    <div class="row-value {{ !$user->gender ? 'empty' : '' }}">
                        @if($user->gender === 'L') Laki-laki
                        @elseif($user->gender === 'P') Perempuan
                        @else Belum diisi
                        @endif
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <span class="row-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z" opacity="0"/><path d="M22 6 12 13 2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg></span>
                <div class="row-body">
                    <div class="row-label">Email</div>
                    <div class="row-value">{{ $user->email ?? '-' }}</div>
                </div>
            </div>

            <div class="detail-row">
                <span class="row-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg></span>
                <div class="row-body">
                    <div class="row-label">No. HP</div>
                    <div class="row-value {{ !$user->phone ? 'empty' : '' }}">{{ $user->phone ?? 'Belum diisi' }}</div>
                </div>
            </div>

            <div class="detail-row">
                <span class="row-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></span>
                <div class="row-body">
                    <div class="row-label">Alamat</div>
                    <div class="row-value {{ !$user->alamat ? 'empty' : '' }}">{{ $user->alamat ?? 'Belum diisi' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var toggle = document.querySelector('.nav-toggle');
    var links = document.getElementById('navLinks');
    if(toggle && links){
        toggle.addEventListener('click', function(){ links.classList.toggle('open'); });
    }
});
</script>

</body>
</html>