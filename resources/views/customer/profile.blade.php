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
        --amber:#F5A623;
        --shadow-sm:0 6px 16px -8px rgba(29,43,38,0.14);
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

    .navbar{
        position:sticky; top:0; z-index:50;
        background:var(--white);
        border-bottom:1px solid var(--mint-deep);
    }
    .navbar-inner{
        max-width:640px; margin:0 auto; padding:14px 28px;
        display:flex; align-items:center; gap:16px;
    }
    .back-link{
        display:flex; align-items:center; gap:8px;
        font-weight:600; font-size:13.5px; color:var(--muted);
        padding:9px 14px; border-radius:999px; transition:background .15s, color .15s;
    }
    .back-link:hover{background:var(--mint); color:var(--ink);}
    .back-link svg{width:17px; height:17px;}
    .navbar-title{
        font-family:'Outfit', sans-serif; font-weight:700; font-size:15px;
    }

    .wrap{
        max-width:640px;
        margin:0 auto;
        padding:32px 24px 60px;
    }

    .alert{
        display:flex;
        gap:10px;
        align-items:flex-start;
        background:#E8F8EF;
        border:1px solid var(--mint-deep);
        color:var(--spring-deep);
        padding:14px 16px;
        border-radius:14px;
        font-size:13px;
        font-weight:600;
        margin-bottom:20px;
    }
    .alert svg{width:18px; height:18px; flex-shrink:0; margin-top:1px;}

    .id-card{
        background:linear-gradient(150deg, var(--spring) 0%, var(--spring-deep) 100%);
        border-radius:24px;
        padding:32px 24px;
        color:#fff;
        text-align:center;
        box-shadow:var(--shadow-sm);
        margin-bottom:20px;
    }
    .id-card .avatar-big{
        width:80px; height:80px; border-radius:50%;
        background:var(--amber);
        border:4px solid rgba(255,255,255,.5);
        margin:0 auto 16px;
        display:flex; align-items:center; justify-content:center;
        font-family:'Outfit', sans-serif; font-weight:700; font-size:28px;
        color:var(--spring-deep);
    }
    .id-card h2{font-family:'Outfit', sans-serif; font-weight:700; font-size:19px; margin-bottom:4px;}
    .id-card .id-email{font-size:12.5px; opacity:.85; margin-bottom:16px; word-break:break-word;}
    .id-card .id-actions{display:flex; gap:10px; justify-content:center; flex-wrap:wrap;}
    .id-card .btn-mini{
        display:inline-flex; align-items:center; gap:6px;
        background:rgba(255,255,255,.16); border:1px solid rgba(255,255,255,.35);
        color:#fff; font-family:'Outfit', sans-serif; font-weight:700; font-size:12.5px;
        padding:9px 16px; border-radius:999px; cursor:pointer; transition:.15s ease;
    }
    .id-card .btn-mini:hover{background:rgba(255,255,255,.28);}
    .id-card .btn-mini.danger:hover{background:rgba(224,80,59,.85); border-color:transparent;}

    .card{
        background:var(--white);
        border-radius:20px;
        padding:22px 24px 8px;
        box-shadow:var(--shadow-sm);
        margin-bottom:20px;
    }
    .card h3{
        font-family:'Outfit', sans-serif; font-weight:700; font-size:15px;
        display:flex; align-items:center; gap:8px; margin-bottom:14px;
    }
    .card h3 svg{width:17px; height:17px; color:var(--spring-deep);}

    .info-row{
        display:flex; justify-content:space-between; gap:12px;
        padding:12px 0; border-bottom:1px solid #F0F4F2;
        font-size:13.5px;
    }
    .info-row:last-child{border-bottom:none;}
    .info-row .label{color:var(--muted);}
    .info-row .value{font-weight:700; color:var(--ink); text-align:right;}

    @media (max-width:560px){
        .navbar-inner{padding:12px 18px;}
        .wrap{padding:24px 16px 50px;}
        .card{padding:18px 18px 4px;}
    }
</style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('dashboard') }}" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
        </a>
        <span class="navbar-title">Profil Saya</span>
    </div>
</nav>

<div class="wrap">

    @if(session('status'))
        <div class="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="id-card">
        <div class="avatar-big">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
        <h2>{{ $user->name ?? '-' }}</h2>
        <div class="id-email">{{ $user->email ?? '-' }}</div>
        <div class="id-actions">
            <a href="{{ route('profile.edit') }}" class="btn-mini">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                Sunting Profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-mini danger">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <h3>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/></svg>
            Data Diri
        </h3>
        <div class="info-row"><span class="label">Nama Lengkap</span><span class="value">{{ $user->name ?? '-' }}</span></div>
        <div class="info-row"><span class="label">Email</span><span class="value">{{ $user->email ?? '-' }}</span></div>
        <div class="info-row"><span class="label">No. HP</span><span class="value">{{ $user->phone ?? '-' }}</span></div>
        <div class="info-row"><span class="label">Tanggal Lahir</span><span class="value">{{ $user->birth_date ? $user->birth_date->translatedFormat('d F Y') : '-' }}</span></div>
        <div class="info-row"><span class="label">Jenis Kelamin</span><span class="value">{{ $user->gender === 'L' ? 'Laki-laki' : ($user->gender === 'P' ? 'Perempuan' : '-') }}</span></div>
        <div class="info-row"><span class="label">Alamat</span><span class="value">{{ $user->alamat ?? '-' }}</span></div>
    </div>

    <div class="card">
        <h3>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Info Akun
        </h3>
        <div class="info-row"><span class="label">Username</span><span class="value">{{ $user->username ?? '-' }}</span></div>
        <div class="info-row"><span class="label">Bergabung Sejak</span><span class="value">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</span></div>
    </div>

</div>

</body>
</html>
