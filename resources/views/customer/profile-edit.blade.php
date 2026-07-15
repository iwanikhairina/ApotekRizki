<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Sunting Profil - Apotek Rizki</title>
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
        max-width:900px; margin:0 auto; padding:14px 28px;
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

    .page-heading h1{
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:22px;
        margin-bottom:4px;
    }
    .page-heading p{
        font-size:13.5px;
        color:var(--muted);
        margin-bottom:22px;
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

    .form-card{
        background:var(--white);
        border-radius:22px;
        padding:26px 26px 8px;
        box-shadow:var(--shadow-sm);
    }

    .field{
        margin-bottom:18px;
    }
    .field label{
        display:block;
        font-size:12.5px;
        font-weight:700;
        color:var(--ink);
        margin-bottom:7px;
    }
    .field .hint{
        font-size:11.5px;
        color:var(--muted);
        font-weight:500;
        margin-top:5px;
    }

    .field input[type="text"],
    .field input[type="email"],
    .field input[type="date"],
    .field input[type="tel"],
    .field select,
    .field textarea{
        width:100%;
        font-family:'Inter', sans-serif;
        font-size:14px;
        color:var(--ink);
        background:var(--mint);
        border:2px solid transparent;
        border-radius:12px;
        padding:12px 14px;
        outline:none;
        transition:border-color .15s, background .15s;
    }
    .field input:focus,
    .field select:focus,
    .field textarea:focus{
        border-color:var(--spring);
        background:var(--white);
    }
    .field textarea{resize:vertical; min-height:80px;}

    .field.has-error input,
    .field.has-error select,
    .field.has-error textarea{
        border-color:var(--error);
        background:#FDECEA;
    }
    .field-error{
        font-size:11.5px;
        font-weight:600;
        color:var(--error);
        margin-top:6px;
    }

    .row-2{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:16px;
    }

    .form-actions{
        display:flex;
        gap:12px;
        padding:18px 0 26px;
        position:sticky;
        bottom:0;
        background:linear-gradient(to top, var(--white) 60%, rgba(255,255,255,0));
        margin-top:4px;
    }

    .btn{
        flex:1;
        border:none;
        cursor:pointer;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:14px;
        padding:14px 18px;
        border-radius:14px;
        transition:background .15s, transform .12s;
    }
    .btn:active{transform:scale(.98);}
    .btn-primary{background:var(--spring); color:var(--white);}
    .btn-primary:hover{background:var(--spring-deep);}
    .btn-secondary{background:var(--mint); color:var(--ink); flex:0 0 auto;}
    .btn-secondary:hover{background:var(--mint-deep);}

    @media (max-width:560px){
        .navbar-inner{padding:12px 18px;}
        .wrap{padding:24px 16px 50px;}
        .row-2{grid-template-columns:1fr;}
        .form-card{padding:20px 18px 4px;}
    }
</style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('profile.index') }}" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
        </a>
        <span class="navbar-title">Sunting Profil</span>
    </div>
</nav>

<div class="wrap">
    <div class="page-heading">
        <h1>Sunting Profil</h1>
        <p>Perbarui data diri kamu supaya proses pemesanan dan pengiriman lebih lancar.</p>
    </div>

    @if(session('status'))
        <div class="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="form-card">
        @csrf
        @method('PUT')

        <div class="field {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Nama sesuai identitas">
            @error('name')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="nama@email.com">
            @error('email')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="row-2">
            <div class="field {{ $errors->has('birth_date') ? 'has-error' : '' }}">
                <label for="birth_date">Tanggal Lahir</label>
                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d') ?? $user->birth_date) }}">
                @error('birth_date')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field {{ $errors->has('gender') ? 'has-error' : '' }}">
                <label for="gender">Jenis Kelamin</label>
                <select id="gender" name="gender">
                    <option value="" {{ old('gender', $user->gender) == '' ? 'selected' : '' }}>Pilih</option>
                    <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="field {{ $errors->has('phone') ? 'has-error' : '' }}">
            <label for="phone">No. HP</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
            @error('phone')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field {{ $errors->has('alamat') ? 'has-error' : '' }}">
            <label for="alamat">Alamat</label>
            <textarea id="alamat" name="alamat" placeholder="Alamat lengkap untuk pengiriman pesanan">{{ old('alamat', $user->alamat) }}</textarea>
            @error('alamat')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('profile.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

</body>
</html>