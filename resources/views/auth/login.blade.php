<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Apotek Rizki</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root{
        --mint:#EAF7F0;
        --mint-deep:#D3EFE0;
        --spring:#12A874;
        --spring-deep:#0C7E57;
        --coral:#FF8B6B;
        --coral-deep:#E8703F;
        --ink:#1D2B26;
        --muted:#7C8B84;
        --white:#FFFFFF;
        --error:#E0503B;
        --error-bg:#FDECE8;
    }

    *{margin:0;padding:0;box-sizing:border-box;}

    body{
        font-family:'Inter', sans-serif;
        background:var(--mint);
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        padding:24px;
        color:var(--ink);
        position:relative;
        overflow-x:hidden;
    }

    .blob{
        position:fixed;
        border-radius:50%;
        filter:blur(2px);
        z-index:0;
    }
    .blob1{
        width:420px;height:420px;
        background:var(--mint-deep);
        top:-160px; left:-140px;
    }
    .blob2{
        width:340px;height:340px;
        background:#FFE4D8;
        bottom:-140px; right:-120px;
    }

    .wrap{
        position:relative;
        z-index:1;
        width:100%;
        max-width:420px;
    }

    .top-illustration{
        display:flex;
        justify-content:center;
        gap:10px;
        margin-bottom:22px;
    }

    .capsule-badge{
        width:88px;
        height:88px;
        border-radius:22px;
        background:var(--white);
        display:flex;
        align-items:center;
        justify-content:center;
        box-shadow:0 14px 26px -8px rgba(18,168,116,0.35);
        padding:10px;
    }

    .capsule-badge img{
        width:100%;
        height:100%;
        object-fit:contain;
    }

    .card{
        background:var(--white);
        border-radius:28px;
        padding:38px 32px 30px;
        box-shadow:0 24px 50px -18px rgba(29,43,38,0.22);
        position:relative;
    }

    .blister-row{
        position:absolute;
        top:0; left:0; right:0;
        display:flex;
        justify-content:space-evenly;
        transform:translateY(-9px);
    }
    .blister-row span{
        width:16px; height:16px;
        border-radius:50%;
        background:var(--mint);
        border:2px solid var(--white);
    }

    .brand-row{
        text-align:center;
        margin-bottom:6px;
    }

    .brand-row .name{
        font-family:'Outfit', sans-serif;
        font-weight:800;
        font-size:22px;
        color:var(--ink);
        letter-spacing:-0.01em;
    }

    .brand-row .tag{
        font-size:12.5px;
        color:var(--muted);
        margin-top:3px;
        font-weight:500;
    }

    .heading{
        text-align:center;
        margin:26px 0 24px;
    }

    .heading h1{
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:21px;
        color:var(--ink);
    }

    .heading p{
        font-size:13px;
        color:var(--muted);
        margin-top:5px;
    }

    .alert{
        background:var(--error-bg);
        color:var(--error);
        padding:11px 14px;
        border-radius:14px;
        font-size:13px;
        margin-bottom:16px;
        font-weight:500;
    }

    .field{margin-bottom:16px;}

    .field label{
        display:block;
        font-size:12.5px;
        font-weight:600;
        color:var(--ink);
        margin-bottom:7px;
    }

    .field-input{
        display:flex;
        align-items:center;
        background:var(--mint);
        border:2px solid transparent;
        border-radius:16px;
        transition:border-color .18s, background .18s;
    }

    .field-input:focus-within{
        border-color:var(--spring);
        background:var(--white);
    }

    .field-input svg{
        width:18px; height:18px;
        margin-left:16px;
        color:var(--spring-deep);
        flex-shrink:0;
    }

    .field-input input{
        width:100%;
        border:none;
        outline:none;
        background:transparent;
        padding:13px 14px;
        font-size:14px;
        font-family:inherit;
        color:var(--ink);
    }

    .field-input button{
        background:none;
        border:none;
        cursor:pointer;
        padding:0 14px;
        color:var(--muted);
        display:flex;
    }

    .field-input button svg{margin:0; color:var(--muted);}

    .error-text{
        color:var(--error);
        font-size:11.5px;
        margin-top:5px;
        font-weight:500;
    }

    .options-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        margin:4px 0 22px;
        font-size:12.5px;
    }

    .options-row label{
        display:flex;
        align-items:center;
        gap:6px;
        color:var(--muted);
        font-weight:500;
        cursor:pointer;
    }

    .options-row input[type=checkbox]{
        accent-color:var(--spring);
        width:15px;
        height:15px;
    }

    .options-row a{
        color:var(--spring-deep);
        font-weight:700;
        text-decoration:none;
    }

    .btn-login{
        width:100%;
        padding:15px;
        background:var(--spring);
        color:var(--white);
        border:none;
        border-radius:999px;
        font-size:14.5px;
        font-weight:700;
        font-family:'Outfit', sans-serif;
        cursor:pointer;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        box-shadow:0 14px 24px -10px rgba(18,168,116,0.6);
        transition:background .18s, transform .12s;
    }

    .btn-login:hover{background:var(--spring-deep);}
    .btn-login:active{transform:scale(0.98);}
    .btn-login svg{width:16px;height:16px;}

    .pill-tags{
        display:flex;
        justify-content:center;
        gap:8px;
        margin-top:22px;
    }
    .pill-tags span{
        font-size:10.5px;
        font-weight:600;
        color:var(--spring-deep);
        background:var(--mint);
        padding:6px 12px;
        border-radius:999px;
    }

    .register-link{
        text-align:center;
        font-size:13px;
        color:var(--muted);
        margin-top:20px;
        padding-top:18px;
        border-top:1px solid var(--mint);
        font-weight:500;
    }

    .register-link a{
        color:var(--spring-deep);
        font-weight:700;
        text-decoration:none;
    }

    .register-link a:hover{text-decoration:underline;}

    .footer-text{
        text-align:center;
        font-size:11.5px;
        color:var(--muted);
        margin-top:22px;
    }

    @media (max-width:480px){
        .card{padding:30px 22px 24px; border-radius:24px;}
        .capsule-badge{width:56px; height:56px; border-radius:16px;}
        .brand-row .name{font-size:19px;}
        .heading h1{font-size:19px;}
    }
</style>
</head>
<body>

<div class="blob blob1"></div>
<div class="blob blob2"></div>

<div class="wrap">

    <div class="top-illustration">
        <div class="capsule-badge">
            <img src="{{ asset('assets/images/logo-apotekrizki.png') }}" alt="Logo Apotek Rizki">
        </div>
    </div>

    <div class="card">
        <div class="blister-row">
            <span></span><span></span><span></span><span></span><span></span>
        </div>

        <div class="brand-row">
            <div class="name">Apotek Rizki</div>
            <div class="tag">Layanan obat terpercaya untuk keluarga</div>
        </div>

        <div class="heading">
            <h1>Masuk ke akun kamu</h1>
            <p>Kelola stok dan transaksi apotek dengan mudah</p>
        </div>

        @if ($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        @if (session('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <div class="field-input">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6l9 7 9-7"/><rect x="3" y="4" width="18" height="16" rx="2"/></svg>
                    <input type="email" id="email" name="email" placeholder="contoh@apotekrizki.com" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="field-input">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    <button type="button" onclick="togglePassword()" aria-label="Tampilkan password">
                        <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password')<div class="error-text">{{ $message }}</div>@enderror
            </div>

        

            <button type="submit" class="btn-login">
                Masuk sekarang
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </button>
        </form>

        <div class="pill-tags">
            <span>Cepat</span>
            <span>Aman</span>
            <span>Terpercaya</span>
        </div>

        <p class="register-link">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
    </div>

    <p class="footer-text">&copy; {{ date('Y') }} Apotek Rizki &middot; Semua hak dilindungi</p>
</div>

<script>
function togglePassword(){
    const input = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if(input.type === 'password'){
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.6 21.6 0 0 1 5.06-5.94M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 7 11 7a21.6 21.6 0 0 1-2.16 3.19M14.12 14.12a3 3 0 1 1-4.24-4.24"/><path d="M1 1l22 22"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/>';
    }
}
</script>

</body>
</html>