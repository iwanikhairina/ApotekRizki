<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    body{font-family:'Inter', sans-serif; background:var(--mint); color:var(--ink); -webkit-font-smoothing:antialiased; padding-bottom:110px;}
    a{text-decoration:none; color:inherit;}
    button{font-family:inherit;}

    .navbar{position:sticky; top:0; z-index:50; background:var(--white); border-bottom:1px solid var(--mint-deep);}
    .navbar-inner{max-width:900px; margin:0 auto; padding:14px 20px; display:flex; align-items:center; gap:12px;}
    .back-link{display:flex; align-items:center; gap:8px; font-weight:700; font-size:14.5px; color:var(--ink);}
    .back-link svg{width:20px; height:20px;}
    .nav-title{font-family:'Outfit', sans-serif; font-weight:700; font-size:16px;}

    .page-wrap{max-width:900px; margin:0 auto; padding:0 0 20px;}

    .alert{margin:14px 14px 0; border-radius:14px; padding:13px 16px; font-size:13px; line-height:1.5;}
    .alert-error{background:#FBEAEA; border:1px solid #F3D0CE; color:#9B3A2E;}
    .alert-success{background:#E3F5EA; border:1px solid #BEE6CE; color:var(--spring-deep);}

    .section{
        background:var(--white);
        margin:14px 14px 0;
        border-radius:16px;
        padding:16px;
        box-shadow:var(--shadow-sm);
    }
    .section-title{font-family:'Outfit', sans-serif; font-weight:700; font-size:14.5px; margin-bottom:12px; display:flex; align-items:center; gap:8px;}
    .section-title svg{width:18px; height:18px; color:var(--spring);}

    .addr-block{font-size:13px; line-height:1.6;}
    .addr-block b{display:block; font-size:13.5px; margin-bottom:2px;}
    .addr-block .muted{color:var(--muted); font-size:12px;}

    .item-row{display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid var(--mint); font-size:13px;}
    .item-row:last-child{border-bottom:none;}
    .item-row .item-name{font-weight:600;}
    .item-row .item-qty{color:var(--muted); font-size:12px;}
    .item-row .item-price{font-family:'Outfit', sans-serif; font-weight:700; font-size:13px;}
    .badge-resep{display:inline-block; margin-left:6px; font-size:10px; font-weight:700; background:var(--peach, #FFE4D8); color:#B23A29; padding:2px 7px; border-radius:999px;}

    .summary-row{display:flex; justify-content:space-between; font-size:13px; padding:5px 0; color:var(--muted);}
    .summary-row.total{font-weight:800; font-size:15px; color:var(--ink); border-top:1px solid var(--mint-deep); margin-top:6px; padding-top:10px;}
    .summary-row.total span:last-child{color:var(--spring-deep); font-family:'Outfit', sans-serif;}

    .pay-options{display:flex; flex-direction:column; gap:10px;}
    .pay-option{
        display:flex; align-items:center; gap:12px;
        border:1.5px solid var(--mint-deep);
        border-radius:12px;
        padding:12px 14px;
        cursor:pointer;
        transition:border-color .15s, background .15s;
    }
    .pay-option:has(input:checked){border-color:var(--spring); background:var(--mint);}
    .pay-option input{width:16px; height:16px; accent-color:var(--spring);}
    .pay-option .pay-label{font-size:13px; font-weight:600;}
    .pay-option .pay-desc{font-size:11.5px; color:var(--muted); margin-top:1px;}

    textarea, input[type="file"]{
        width:100%;
        border:1.5px solid var(--mint-deep);
        border-radius:12px;
        padding:11px 13px;
        font-size:13px;
        font-family:inherit;
        color:var(--ink);
        background:var(--white);
    }
    textarea{resize:vertical; min-height:70px;}
    label.field-label{font-size:12.5px; font-weight:700; margin-bottom:6px; display:block;}
    .field-hint{font-size:11.5px; color:var(--muted); margin-top:5px; line-height:1.5;}
    .field-error{font-size:11.5px; color:var(--error); margin-top:5px;}

    .warn-box{
        background:#FCF1DF; border:1px solid #F0DBB0; color:#8A5E15;
        border-radius:12px; padding:12px 14px; font-size:12.5px; line-height:1.5; margin-bottom:14px;
        display:flex; gap:9px; align-items:flex-start;
    }
    .warn-box svg{width:17px; height:17px; flex-shrink:0; margin-top:1px;}

    .checkout-footer{
        position:fixed; bottom:0; left:0; right:0;
        background:var(--white); border-top:1px solid var(--mint-deep);
        padding:14px 20px; display:flex; align-items:center; justify-content:space-between;
        z-index:40; box-shadow:0 -8px 24px -12px rgba(29,43,38,0.15);
    }
    .footer-total .label{font-size:11.5px; color:var(--muted);}
    .footer-total .value{font-family:'Outfit', sans-serif; font-weight:800; font-size:17px; color:var(--spring-deep);}

    .beli-btn{
        background:var(--spring); color:var(--white); border:none;
        padding:13px 34px; border-radius:14px; font-family:'Outfit', sans-serif;
        font-weight:700; font-size:14px; cursor:pointer; transition:background .15s;
    }
    .beli-btn:hover{background:var(--spring-deep);}

    @media (max-width:480px){
        .navbar-inner{padding:12px 16px;}
        .alert, .section{margin-left:10px; margin-right:10px;}
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

    @if ($errors->any())
        <div class="alert alert-error">
            <b style="display:block; margin-bottom:4px;">Periksa kembali data checkout kamu:</b>
            @foreach ($errors->all() as $error)
                {{ $error }}@if(!$loop->last)<br>@endif
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- ===== ALAMAT ===== --}}
        <div class="section">
            <div class="section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                Alamat Pengiriman
            </div>
            <div class="addr-block">
                <b>{{ $user->nama_penerima ?? $user->name }} @if($user->no_telepon) &middot; {{ $user->no_telepon }} @endif</b>
                {{ $user->alamat }}
                <div class="muted" style="margin-top:6px;">
                    Jarak {{ number_format($summary['jarak_km'], 1) }} km dari apotek &middot; Ongkir Rp{{ number_format($summary['ongkir'], 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- ===== ITEM ===== --}}
        <div class="section">
            <div class="section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.6a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                Ringkasan Produk ({{ $summary['item_count'] }})
            </div>
            @foreach ($cartItems as $item)
                <div class="item-row">
                    <div>
                        <div class="item-name">
                            {{ $item->obat->nama }}
                            @if($item->obat->butuh_resep)<span class="badge-resep">Resep</span>@endif
                            @if($item->obat->butuh_ktp)<span class="badge-resep">KTP</span>@endif
                        </div>
                        <div class="item-qty">{{ $item->quantity }} x Rp{{ number_format($item->obat->harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="item-price">Rp{{ number_format($item->quantity * $item->obat->harga, 0, ',', '.') }}</div>
                </div>
            @endforeach

            <div style="margin-top:12px;">
                <div class="summary-row"><span>Subtotal</span><span>Rp{{ number_format($summary['subtotal'], 0, ',', '.') }}</span></div>
                <div class="summary-row"><span>Ongkos Kirim</span><span>Rp{{ number_format($summary['ongkir'], 0, ',', '.') }}</span></div>
                <div class="summary-row total"><span>Total</span><span>Rp{{ number_format($summary['total'], 0, ',', '.') }}</span></div>
            </div>
        </div>

        {{-- ===== VERIFIKASI RESEP / KTP ===== --}}
        @if($butuhResep || $butuhKtp)
            <div class="section">
                <div class="section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                    Verifikasi Dokumen
                </div>
                <div class="warn-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01"/><path d="m10.29 3.86-8.18 14.18A2 2 0 0 0 3.93 21h16.14a2 2 0 0 0 1.82-2.96L13.71 3.86a2 2 0 0 0-3.42 0Z"/></svg>
                    <div>Ada produk di keranjang kamu yang butuh verifikasi sebelum pesanan diproses apoteker. Pesanan tetap tersimpan, tapi statusnya "menunggu verifikasi" sampai dokumen dicek.</div>
                </div>

                @if($butuhResep)
                    <div style="margin-bottom:14px;">
                        <label class="field-label" for="resep">Foto Resep Dokter</label>
                        <input type="file" name="resep" id="resep" accept=".jpg,.jpeg,.png,.pdf" required>
                        <div class="field-hint">Format JPG, PNG, atau PDF, maksimal 10MB.</div>
                    </div>
                @endif

                @if($butuhKtp)
                    <div>
                        <label class="field-label" for="ktp">Foto KTP</label>
                        <input type="file" name="ktp" id="ktp" accept=".jpg,.jpeg,.png,.pdf" required>
                        <div class="field-hint">Dipakai apoteker untuk verifikasi usia/identitas, format JPG, PNG, atau PDF, maksimal 10MB.</div>
                    </div>
                @endif
            </div>
        @endif

        {{-- ===== METODE PEMBAYARAN ===== --}}
        <div class="section">
            <div class="section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                Metode Pembayaran
            </div>
            <div class="pay-options">
                <label class="pay-option">
                    <input type="radio" name="metode_pembayaran" value="cod" checked>
                    <div>
                        <div class="pay-label">COD (Bayar di Tempat)</div>
                        <div class="pay-desc">Bayar tunai langsung ke kurir saat pesanan tiba.</div>
                    </div>
                </label>
                <label class="pay-option">
                    <input type="radio" name="metode_pembayaran" value="qris">
                    <div>
                        <div class="pay-label">Transfer QRIS (BSI)</div>
                        <div class="pay-desc">Transfer sebelum pesanan diproses.</div>
                    </div>
                </label>
            </div>
        </div>

        {{-- ===== CATATAN ===== --}}
        <div class="section">
            <div class="section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05 12.25 20.24a5.5 5.5 0 0 1-7.78-7.78l9.19-9.19a3.67 3.67 0 0 1 5.19 5.19L9.66 17.65a1.83 1.83 0 0 1-2.6-2.6l8.49-8.49"/></svg>
                Catatan Tambahan (opsional)
            </div>
            <textarea name="catatan" placeholder="Contoh: tolong hubungi dulu sebelum diantar.">{{ old('catatan') }}</textarea>
        </div>

        <div class="checkout-footer">
            <div class="footer-total">
                <span class="label">Total Bayar</span>
                <span class="value">Rp{{ number_format($summary['total'], 0, ',', '.') }}</span>
            </div>
            <button type="submit" class="beli-btn">Buat Pesanan</button>
        </div>
    </form>
</div>

</body>
</html>
