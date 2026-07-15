<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Staff - Apotek Rizki</title>
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
    }
    *{box-sizing:border-box;margin:0;padding:0;}
    body{
        font-family:'Inter',sans-serif;
        background:var(--mint-50);
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        position:relative;
        overflow-x:hidden;
        padding:24px 16px;
    }
    .blob{
        position:absolute;
        border-radius:50%;
        z-index:0;
    }
    .blob-top{
        width:300px;height:300px;
        background:var(--mint-100);
        top:-120px;left:-120px;
    }
    .blob-bottom{
        width:260px;height:260px;
        background:var(--peach);
        bottom:-100px;right:-100px;
        opacity:.7;
    }
    .card{
        position:relative;
        z-index:1;
        background:#fff;
        width:100%;
        max-width:440px;
        border-radius:28px;
        box-shadow:0 20px 50px rgba(15,47,34,.08);
        padding:40px 36px 32px;
    }
    .logo-wrap{
        display:flex;
        justify-content:center;
        margin-bottom:8px;
    }
    .logo-wrap img{
        width:78px;height:78px;
        border-radius:20px;
        object-fit:cover;
        box-shadow:0 8px 20px rgba(16,185,129,.25);
    }
    h1{
        font-family:'Outfit',sans-serif;
        font-size:26px;
        font-weight:700;
        color:var(--text-dark);
        text-align:center;
        margin-top:14px;
    }
    .subtitle{
        text-align:center;
        color:var(--text-muted);
        font-size:14px;
        margin-top:4px;
        margin-bottom:22px;
    }
    .staff-badge{
        display:block;
        text-align:center;
        font-size:12px;
        font-weight:600;
        color:var(--mint-700);
        background:var(--mint-100);
        padding:4px 12px;
        border-radius:999px;
        width:fit-content;
        margin:0 auto 20px;
        letter-spacing:.3px;
    }
    label{
        display:block;
        font-size:13px;
        font-weight:600;
        color:var(--text-dark);
        margin-bottom:6px;
    }
    .field{
        margin-bottom:18px;
    }
    .role-group{
        display:flex;
        gap:10px;
        margin-bottom:20px;
    }
    .role-option{
        flex:1;
    }
    .role-option input{
        display:none;
    }
    .role-option label{
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        gap:4px;
        padding:14px 8px;
        border:2px solid var(--mint-100);
        border-radius:16px;
        cursor:pointer;
        transition:.15s ease;
        text-align:center;
        font-size:13px;
        color:var(--text-muted);
        background:#fafffc;
    }
    .role-option label .emoji{font-size:20px;}
    .role-option input:checked + label{
        border-color:var(--mint-500);
        background:var(--mint-50);
        color:var(--mint-700);
        font-weight:700;
    }
    .input-wrap{
        display:flex;
        align-items:center;
        gap:10px;
        background:#eef6ff;
        border:2px solid transparent;
        border-radius:14px;
        padding:12px 14px;
        transition:.15s ease;
    }
    .input-wrap:focus-within{
        border-color:var(--mint-500);
        background:#fff;
    }
    .input-wrap svg{flex-shrink:0;color:var(--mint-600);}
    .input-wrap input{
        border:none;
        outline:none;
        background:transparent;
        width:100%;
        font-size:15px;
        font-family:'Inter',sans-serif;
        color:var(--text-dark);
    }
    .toggle-pass{
        cursor:pointer;
        color:#9aa8a1;
        flex-shrink:0;
    }
    .row-between{
        display:flex;
        align-items:center;
        justify-content:space-between;
        font-size:13px;
        margin-bottom:22px;
    }
    .remember{
        display:flex;
        align-items:center;
        gap:6px;
        color:var(--text-muted);
    }
    .btn-submit{
        width:100%;
        background:linear-gradient(135deg,var(--mint-500),var(--mint-700));
        color:#fff;
        border:none;
        padding:14px;
        border-radius:999px;
        font-size:15px;
        font-weight:700;
        font-family:'Outfit',sans-serif;
        cursor:pointer;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        transition:.15s ease;
    }
    .btn-submit:hover{filter:brightness(1.05);transform:translateY(-1px);}
    .error-box{
        background:#fff1f0;
        border:1px solid #ffccc7;
        color:#cf1322;
        font-size:13px;
        border-radius:12px;
        padding:10px 14px;
        margin-bottom:18px;
    }
    .footer-note{
        text-align:center;
        font-size:12px;
        color:var(--text-muted);
        margin-top:26px;
    }
    @media (max-width:480px){
        .card{padding:30px 22px 26px;border-radius:22px;}
        h1{font-size:22px;}
        .role-option label{font-size:12px;padding:12px 6px;}
    }
</style>
</head>
<body>
    <div class="blob blob-top"></div>
    <div class="blob blob-bottom"></div>

    <div class="card">
        <div class="logo-wrap">
            <img src="{{ asset('assets/images/logo-apotekrizki.png') }}" alt="Logo Apotek Rizki">
        </div>
        <span class="staff-badge">AREA STAFF</span>
        <h1>Apotek Rizki</h1>
        <p class="subtitle">Masuk sesuai role kamu untuk melanjutkan</p>

        @if ($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('staff.login.submit') }}">
            @csrf

            <label>Pilih Role</label>
            <div class="role-group">
                <div class="role-option">
                    <input type="radio" name="role" id="role-kurir" value="kurir" {{ old('role') === 'kurir' ? 'checked' : '' }}>
                    <label for="role-kurir">
                        <span class="emoji">🛵</span>
                        Kurir
                    </label>
                </div>
                <div class="role-option">
                    <input type="radio" name="role" id="role-apoteker" value="apoteker" {{ old('role') === 'apoteker' ? 'checked' : '' }}>
                    <label for="role-apoteker">
                        <span class="emoji">💊</span>
                        Apoteker
                    </label>
                </div>
            </div>

            <div class="field">
                <label for="username">Nama Pengguna</label>
                <div class="input-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
                    <input type="text" name="username" id="username" placeholder="cth: kurir1" value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                    <span class="toggle-pass" onclick="togglePassword()">
                        <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                </div>
            </div>

            <div class="row-between">
                <label class="remember">
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
            </div>

            <button type="submit" class="btn-submit">
                Masuk sekarang
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </button>
        </form>

        <p class="footer-note">© {{ date('Y') }} Apotek Rizki · Khusus akses internal staff</p>
    </div>

<script>
function togglePassword(){
    const input = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if(input.type === 'password'){
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.6 18.6 0 0 1 5.06-5.94M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a18.6 18.6 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
}
</script>
</body>
</html>