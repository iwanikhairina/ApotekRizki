<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Checkout - Apotek Rizki</title>
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
    body{font-family:'Inter', sans-serif; background:var(--mint); color:var(--ink); -webkit-font-smoothing:antialiased; padding-bottom:100px;}
    a{text-decoration:none; color:inherit;}
    button{font-family:inherit;}

    .navbar{position:sticky; top:0; z-index:50; background:var(--white); border-bottom:1px solid var(--mint-deep);}
    .navbar-inner{max-width:760px; margin:0 auto; padding:14px 20px; display:flex; align-items:center; gap:12px;}
    .back-link{display:flex; align-items:center; gap:8px; font-weight:700; font-size:14.5px; color:var(--ink);}
    .back-link svg{width:20px; height:20px;}
    .nav-title{font-family:'Outfit', sans-serif; font-weight:700; font-size:16px; margin-right:auto;}

    .page-wrap{max-width:760px; margin:0 auto; padding:20px 20px 40px;}

    .flash-success{background:#E3F5EA; border:1px solid var(--mint-deep); color:var(--spring-deep); padding:12px 16px; border-radius:12px; font-size:13.5px; font-weight:600; margin-bottom:16px;}
    .flash-error{background:#FBE8E6; border:1px solid #f3c8c2; color:#B23A29; padding:12px 16px; border-radius:12px; font-size:13.5px; font-weight:600; margin-bottom:16px;}

    .card{background:var(--white); border-radius:20px; box-shadow:var(--shadow-sm); padding:20px 22px; margin-bottom:16px;}

    .card-title{
        display:flex; align-items:center; gap:8px;
        font-family:'Outfit', sans-serif; font-size:14px; font-weight:700;
        color:var(--spring-deep); text-transform:uppercase; letter-spacing:.03em;
        margin-bottom:14px;
    }
    .card-title svg{width:16px; height:16px;}

    /* alamat */
    .addr-row{display:flex; align-items:flex-start; gap:12px;}
    .addr-row svg{width:20px; height:20px; color:var(--spring); flex-shrink:0; margin-top:2px;}
    .addr-row p{font-size:13.5px; line-height:1.5;}
    .addr-row p b{display:block; font-size:14px; margin-bottom:2px;}
    .addr-change{display:inline-block; margin-top:8px; font-size:12.5px; font-weight:700; color:var(--spring-deep);}

    /* daftar item */
    .co-item{display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid var(--mint); font-size:13.5px;}
    .co-item:last-child{border-bottom:none;}
    .co-item .name{font-weight:600;}
    .co-item .qty{color:var(--muted); font-size:12px; margin-top:2px;}
    .co-item .price{font-family:'Outfit', sans-serif; font-weight:700; flex-shrink:0; margin-left:12px;}

    /* metode pembayaran */
    .pay-option{
        display:flex; align-items:center; gap:12px;
        border:2px solid var(--mint-deep); border-radius:14px;
        padding:14px 16px; margin-bottom:10px; cursor:pointer;
        transition:border-color .15s, background .15s;
    }
    .pay-option:has(input:checked){border-color:var(--spring); background:var(--mint);}
    .pay-option input{width:18px; height:18px; accent-color:var(--spring); flex-shrink:0;}
    .pay-option .pay-icon{width:36px; height:36px; border-radius:10px; background:var(--mint); color:var(--spring-deep); display:flex; align-items:center; justify-content:center; flex-shrink:0;}
    .pay-option .pay-icon svg{width:18px; height:18px;}
    .pay-option .pay-info span{display:block; font-size:13.5px; font-weight:700;}
    .pay-option .pay-info small{font-size:11.5px; color:var(--muted);}

    /* upload resep */
    .resep-alert{
        display:flex; align-items:flex-start; gap:10px;
        background:#FCF1DF; border:1px solid #F0DBB0; color:#8A5E15;
        border-radius:14px; padding:13px 16px; margin-bottom:14px;
    }
    .resep-alert svg{width:19px; height:19px; flex-shrink:0; margin-top:1px;}
    .resep-alert .body{font-size:12.5px; line-height:1.5;}
    .resep-alert .body b{display:block; font-size:13px; margin-bottom:2px;}

    .upload-box{
        border:2px dashed var(--mint-deep);
        border-radius:16px;
        padding:24px 16px;
        text-align:center;
        cursor:pointer;
        transition:border-color .15s, background .15s;
        position:relative;
    }
    .upload-box:hover{border-color:var(--spring); background:var(--mint);}
    .upload-box.has-file{border-color:var(--spring); border-style:solid; background:var(--mint);}
    .upload-box svg{width:30px; height:30px; color:var(--spring); margin-bottom:8px;}
    .upload-box .upload-text{font-size:13px; font-weight:600;}
    .upload-box .upload-hint{font-size:11.5px; color:var(--muted); margin-top:4px;}
    .upload-box input[type="file"]{position:absolute; inset:0; opacity:0; cursor:pointer;}
    .upload-filename{font-size:12.5px; font-weight:700; color:var(--spring-deep); margin-top:10px; word-break:break-all;}

    /* catatan */
    textarea{
        width:100%; border:2px solid var(--mint-deep); border-radius:14px;
        padding:12px 14px; font-family:inherit; font-size:13px; resize:vertical; min-height:70px;
        outline:none; transition:border-color .15s;
    }
    textarea:focus{border-color:var(--spring);}

    /* ringkasan biaya */
    .summary-row{display:flex; justify-content:space-between; font-size:13px; color:var(--muted); padding:5px 0;}
    .summary-row.total{font-family:'Outfit', sans-serif; font-weight:700; font-size:15px; color:var(--ink); border-top:1px dashed var(--mint-deep); margin-top:8px; padding-top:12px;}

    /* footer sticky */
    .co-footer{
        position:fixed; bottom:0; left:0; right:0;
        background:var(--white); border-top:1px solid var(--mint-deep);
        padding:14px 20px; display:flex; align-items:center; justify-content:space-between;
        z-index:40; box-shadow:0 -8px 24px -12px rgba(29,43,38,0.15);
    }
    .footer-total .label{font-size:11.5px; color:var(--muted);}
    .footer-total .value{font-family:'Outfit', sans-serif; font-weight:800; font-size:17px; color:var(--spring-deep);}

    .submit-btn{
        background:var(--spring); color:var(--white); border:none;
        padding:13px 30px; border-radius:14px; font-family:'Outfit', sans-serif;
        font-weight:700; font-size:14px; cursor:pointer; transition:background .15s;
    }
    .submit-btn:hover{background:var(--spring-deep);}
    .submit-btn:disabled{background:var(--mint-deep); color:var(--muted); cursor:not-allowed;}

    @media (max-width:480px){
        .card{padding:16px 18px;}
        .page-wrap{padding:16px 16px 40px;}
    }
</style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('cart.index') }}" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div class="nav-title">Checkout</div>
    </div>
</nav>

<div class="page-wrap">

    @if (session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="flash-error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" enctype="multipart/form-data" id="checkoutForm">
        @csrf

        {{-- ===== ALAMAT PENGIRIMAN ===== --}}
        <div class="card">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                Alamat Pengiriman
            </div>
            <div class="addr-row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                <p>
                    <b>{{ $user->name }}</b>
                    {{ $user->alamat }}
                </p>
            </div>
            <a href="{{ route('alamat.create') }}" class="addr-change">Ubah Alamat</a>
        </div>

        {{-- ===== PRODUK DIPESAN ===== --}}
        <div class="card">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.6a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                Produk Dipesan ({{ $cartItems->count() }})
            </div>
            @foreach ($cartItems as $item)
                <div class="co-item">
                    <div>
                        <div class="name">{{ $item->obat->nama }}</div>
                        <div class="qty">{{ $item->quantity }} x Rp{{ number_format($item->obat->harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="price">Rp{{ number_format($item->obat->harga * $item->quantity, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>

        {{-- ===== METODE PEMBAYARAN ===== --}}
        <div class="card">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                Metode Pembayaran
            </div>

            <label class="pay-option">
                <input type="radio" name="metode_pembayaran" value="cod" checked>
                <div class="pay-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M2 10h20"/><path d="M6 15h4"/></svg>
                </div>
                <div class="pay-info">
                    <span>COD (Bayar di Tempat)</span>
                    <small>Bayar tunai saat pesanan tiba</small>
                </div>
            </label>

            <label class="pay-option">
                <input type="radio" name="metode_pembayaran" value="qris">
                <div class="pay-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                </div>
                <div class="pay-info">
                    <span>Transfer QRIS (BSI)</span>
                    <small>Bayar via scan QRIS</small>
                </div>
            </label>

            @error('metode_pembayaran')<div class="flash-error" style="margin-top:8px;">{{ $message }}</div>@enderror
        </div>

        {{-- ===== UPLOAD RESEP (kondisional) ===== --}}
        @if($requiresResep)
            <div class="card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6M9 16h6M9 8h2"/><rect x="5" y="3" width="14" height="18" rx="2"/></svg>
                    Resep Dokter
                </div>

                <div class="resep-alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01"/><path d="m10.29 3.86-8.18 14.18A2 2 0 0 0 3.93 21h16.14a2 2 0 0 0 1.82-2.96L13.71 3.86a2 2 0 0 0-3.42 0Z"/></svg>
                    <div class="body">
                        <b>Wajib diunggah</b>
                        Pesananmu mengandung: {{ $kerasItems->pluck('obat.nama')->implode(', ') }}. Apoteker kami akan memverifikasi resep ini sebelum pesanan diproses.
                    </div>
                </div>

                <label class="upload-box" id="uploadBox">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v14M6 9l6-6 6 6"/><path d="M4 21h16"/></svg>
                    <div class="upload-text">Klik untuk pilih foto resep</div>
                    <div class="upload-hint">JPG, PNG, atau PDF — maks 10MB</div>
                    <input type="file" name="resep" id="resepInput" accept=".jpg,.jpeg,.png,.pdf" required>
                </label>
                <div class="upload-filename" id="uploadFilename"></div>

                @error('resep')<div class="flash-error" style="margin-top:10px;">{{ $message }}</div>@enderror
            </div>
        @endif

        {{-- ===== CATATAN ===== --}}
        <div class="card">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05 12.25 20.24a5.5 5.5 0 0 1-7.78-7.78l9.19-9.19a3.67 3.67 0 0 1 5.19 5.19L9.66 17.65a1.83 1.83 0 0 1-2.6-2.6l8.49-8.49"/></svg>
                Catatan (opsional)
            </div>
            <textarea name="catatan" placeholder="Contoh: tolong titip di satpam, atau info tambahan lainnya" maxlength="1000"></textarea>
        </div>

        {{-- ===== RINCIAN BIAYA ===== --}}
        <div class="card">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Rincian Biaya
            </div>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp{{ number_format($summary['subtotal'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Ongkos Kirim {{ $summary['jarak_km'] ? '('.number_format($summary['jarak_km'], 1).' km)' : '' }}</span>
                <span>Rp{{ number_format($summary['ongkir'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-row total">
                <span>Total Pembayaran</span>
                <span>Rp{{ number_format($summary['total'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="co-footer">
            <div class="footer-total">
                <span class="label">Total Pembayaran</span>
                <span class="value">Rp{{ number_format($summary['total'], 0, ',', '.') }}</span>
            </div>
            <button type="submit" class="submit-btn" id="submitBtn">Buat Pesanan</button>
        </div>

    </form>
</div>

<script>
(function(){
    const resepInput = document.getElementById('resepInput');
    const uploadBox = document.getElementById('uploadBox');
    const uploadFilename = document.getElementById('uploadFilename');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('checkoutForm');

    if(resepInput){
        resepInput.addEventListener('change', () => {
            if(resepInput.files.length > 0){
                uploadBox.classList.add('has-file');
                uploadFilename.textContent = '📎 ' + resepInput.files[0].name;
            } else {
                uploadBox.classList.remove('has-file');
                uploadFilename.textContent = '';
            }
        });
    }

    form.addEventListener('submit', () => {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Memproses...';
    });
})();
</script>

</body>
</html>