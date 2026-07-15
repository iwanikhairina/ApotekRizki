<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Pesanan - Apotek Rizki</title>
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
        --sky:#4E9BD9;
        --shadow-sm:0 6px 16px -8px rgba(29,43,38,0.14);
        --shadow-md:0 20px 40px -16px rgba(29,43,38,0.18);
    }
    *{margin:0;padding:0;box-sizing:border-box;}
    body{font-family:'Inter', sans-serif; background:var(--mint); color:var(--ink); -webkit-font-smoothing:antialiased;}
    a{text-decoration:none; color:inherit;}
    button{font-family:inherit;}

    /* ===== NAVBAR ===== */
    .navbar{position:sticky; top:0; z-index:50; background:var(--white); border-bottom:1px solid var(--mint-deep);}
    .navbar-inner{max-width:1100px; margin:0 auto; padding:14px 28px; display:flex; align-items:center; gap:28px;}
    .nav-brand{display:flex; align-items:center; gap:10px; flex-shrink:0;}
    .nav-brand .logo-box{width:42px; height:42px; border-radius:12px; background:var(--mint); display:flex; align-items:center; justify-content:center; padding:6px; flex-shrink:0;}
    .nav-brand .logo-box img{width:100%; height:100%; object-fit:contain;}
    .nav-brand .brand-text{font-family:'Outfit', sans-serif; font-weight:800; font-size:17px; line-height:1.15;}
    .nav-brand .brand-text span{display:block; font-family:'Inter'; font-weight:500; font-size:10.5px; color:var(--muted); letter-spacing:.02em;}
    .nav-links{display:flex; align-items:center; gap:4px; flex:1;}
    .nav-links a{font-size:13.5px; font-weight:600; color:var(--muted); padding:9px 15px; border-radius:999px; transition:background .15s, color .15s; white-space:nowrap;}
    .nav-links a:hover{background:var(--mint); color:var(--ink);}
    .nav-links a.active{background:var(--spring); color:var(--white);}
    .nav-toggle{display:none; width:40px; height:40px; border-radius:12px; border:none; background:var(--mint); align-items:center; justify-content:center; cursor:pointer;}
    .nav-toggle svg{width:20px; height:20px; color:var(--ink);}
    .nav-actions{display:flex; align-items:center; gap:10px; flex-shrink:0;}
    .icon-btn{width:40px; height:40px; border-radius:12px; border:none; background:var(--mint); display:flex; align-items:center; justify-content:center; cursor:pointer; position:relative; color:var(--spring-deep); transition:background .15s;}
    .icon-btn:hover{background:var(--mint-deep);}
    .icon-btn svg{width:19px; height:19px;}
    .cart-count{position:absolute; top:-4px; right:-4px; background:var(--error); color:var(--white); font-size:10px; font-weight:700; width:17px; height:17px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid var(--white);}
    .avatar-btn{width:40px; height:40px; border-radius:50%; background:var(--spring); color:var(--white); border:none; font-family:'Outfit'; font-weight:700; font-size:14px; cursor:pointer;}

    @media (max-width:920px){
        .nav-links{position:fixed; top:70px; left:0; right:0; background:var(--white); flex-direction:column; align-items:stretch; padding:10px 16px 16px; gap:2px; border-bottom:1px solid var(--mint-deep); box-shadow:var(--shadow-sm); display:none;}
        .nav-links.open{display:flex;}
        .nav-links a{padding:12px 14px;}
        .nav-toggle{display:flex;}
    }

    /* ===== PAGE ===== */
    .page-wrap{max-width:760px; margin:0 auto; padding:28px 24px 60px;}

    .back-link{
        display:inline-flex; align-items:center; gap:6px;
        font-size:13px; font-weight:600; color:var(--spring-deep);
        margin-bottom:18px;
    }
    .back-link svg{width:16px; height:16px;}
    .back-link:hover{text-decoration:underline;}

    .flash-success{
        background:#E3F5EA; border:1px solid var(--mint-deep); color:var(--spring-deep);
        padding:12px 16px; border-radius:12px; font-size:13.5px; font-weight:600; margin-bottom:16px;
    }
    .flash-error{
        background:#FBE8E6; border:1px solid #f3c8c2; color:#B23A29;
        padding:12px 16px; border-radius:12px; font-size:13.5px; font-weight:600; margin-bottom:16px;
    }

    .detail-card{
        background:var(--white);
        border-radius:22px;
        box-shadow:var(--shadow-sm);
        padding:26px 28px;
    }

    .detail-head{
        display:flex; justify-content:space-between; align-items:flex-start;
        gap:14px; flex-wrap:wrap;
        margin-bottom:18px;
    }
    .order-code{font-family:'Outfit', sans-serif; font-weight:700; font-size:19px;}
    .order-date{font-size:12.5px; color:var(--muted); margin-top:4px;}

    .status-badge{
        display:inline-flex; align-items:center; gap:5px;
        font-size:11.5px; font-weight:700;
        padding:6px 14px; border-radius:999px;
        white-space:nowrap;
    }
    .status-badge svg{width:13px; height:13px;}
    .status-badge.st-terkirim{background:#E3F5EA; color:var(--spring-deep);}
    .status-badge.st-diproses{background:#FDF1DF; color:#A66A17;}
    .status-badge.st-dikirim{background:#E5F1FB; color:#2E6FA3;}
    .status-badge.st-menunggu{background:#F1EEF2; color:#6B5F73;}
    .status-badge.st-dibatalkan{background:#FBE8E6; color:#B23A29;}

    .section-title{
        font-family:'Outfit', sans-serif; font-size:13.5px; font-weight:700;
        color:var(--spring-deep); text-transform:uppercase; letter-spacing:.03em;
        margin:22px 0 12px;
    }
    .section-title:first-of-type{margin-top:0;}

    /* ETA card */
    .eta-card{
        display:flex; align-items:center; gap:14px;
        background:#FFF3E0;
        border-radius:16px;
        padding:16px 18px;
        margin-bottom:18px;
    }
    .eta-icon{
        width:42px; height:42px; border-radius:12px;
        background:#FFE2B8; color:#C9740E;
        display:flex; align-items:center; justify-content:center;
        flex-shrink:0;
    }
    .eta-icon svg{width:20px; height:20px;}
    .eta-title{
        font-size:11px; font-weight:700; color:#C9740E;
        text-transform:uppercase; letter-spacing:.03em;
    }
    .eta-value{
        font-family:'Outfit', sans-serif; font-weight:700; font-size:16px;
        color:var(--ink); margin-top:2px;
    }
    .eta-sub{
        font-size:12px; color:#A66A17; font-weight:600;
        margin-top:2px;
    }

    /* Courier card */
    .courier-card{
        display:flex; align-items:center; gap:14px;
        background:var(--mint);
        border-radius:16px;
        padding:16px 18px;
        margin-bottom:18px;
    }
    .courier-avatar{
        width:44px; height:44px; border-radius:50%;
        background:var(--spring); color:var(--white);
        display:flex; align-items:center; justify-content:center;
        font-family:'Outfit', sans-serif; font-weight:700; font-size:16px;
        flex-shrink:0;
    }
    .courier-info{flex:1; min-width:0;}
    .courier-name{font-family:'Outfit', sans-serif; font-weight:700; font-size:14.5px;}
    .courier-meta{font-size:12px; color:var(--muted); margin-top:2px; line-height:1.5;}
    .courier-call{
        width:40px; height:40px; border-radius:12px;
        background:var(--white); color:var(--spring-deep);
        display:flex; align-items:center; justify-content:center;
        flex-shrink:0; transition:background .15s;
    }
    .courier-call:hover{background:var(--mint-deep);}
    .courier-call svg{width:18px; height:18px;}

    /* Confirm received button */
    .btn-confirm-received{
        width:100%;
        background:linear-gradient(135deg, var(--spring), var(--spring-deep));
        color:#fff; border:none; padding:14px; border-radius:14px;
        font-family:'Outfit', sans-serif; font-weight:700; font-size:14px;
        cursor:pointer; margin-bottom:18px; transition:filter .15s;
        display:flex; align-items:center; justify-content:center; gap:8px;
    }
    .btn-confirm-received:hover{filter:brightness(1.05);}
    .btn-confirm-received svg{width:18px; height:18px;}

    /* Tracker */
    .tracker{display:flex; justify-content:space-between; align-items:flex-start; position:relative; padding:0 4px; margin:10px 0 6px;}
    .tracker-line{position:absolute; top:14px; left:24px; right:24px; height:2px; background:var(--mint-deep); z-index:0;}
    .tracker-line-fill{position:absolute; top:14px; left:24px; height:2px; background:var(--spring); z-index:1; transition:width .2s;}
    .tracker-step{position:relative; z-index:2; display:flex; flex-direction:column; align-items:center; gap:6px; flex:1;}
    .tracker-dot{width:28px; height:28px; border-radius:50%; background:var(--mint-deep); color:var(--muted); display:flex; align-items:center; justify-content:center; border:2px solid var(--white);}
    .tracker-dot svg{width:14px; height:14px;}
    .tracker-step.done .tracker-dot{background:var(--spring); color:var(--white);}
    .tracker-step.current .tracker-dot{background:var(--spring); color:var(--white); box-shadow:0 0 0 4px #D3EFE0;}
    .tracker-label{font-size:10.5px; font-weight:600; color:var(--muted); text-align:center;}
    .tracker-step.done .tracker-label, .tracker-step.current .tracker-label{color:var(--ink); font-weight:700;}

    .cancel-note{display:flex; align-items:center; gap:10px; background:#FBE8E6; color:#B23A29; border-radius:14px; padding:12px 16px; font-size:12.5px; font-weight:600;}
    .cancel-note svg{width:18px; height:18px; flex-shrink:0;}

    /* Item list */
    .item-row{display:flex; align-items:center; gap:14px; background:var(--mint); border-radius:14px; padding:12px 14px; margin-bottom:8px;}
    .item-thumb{width:44px; height:44px; border-radius:10px; flex-shrink:0; background:linear-gradient(150deg, var(--mint-deep), #BFE6D3); display:flex; align-items:center; justify-content:center;}
    .item-thumb svg{width:20px; height:20px; color:var(--spring); opacity:.6;}
    .item-info{flex:1; min-width:0;}
    .item-name{font-family:'Outfit', sans-serif; font-weight:600; font-size:14px;}
    .item-meta{display:flex; align-items:center; gap:8px; margin-top:3px; flex-wrap:wrap;}
    .item-qty{font-size:11.5px; color:var(--muted);}
    .drug-badge{font-size:9.5px; font-weight:700; padding:2px 8px; border-radius:999px; text-transform:uppercase; letter-spacing:.02em;}
    .drug-badge.bebas{background:#E3F5EA; color:var(--spring-deep);}
    .drug-badge.terbatas{background:#E5F1FB; color:#2E6FA3;}
    .drug-badge.keras{background:#FBE8E6; color:#B23A29;}
    .item-price{font-family:'Outfit', sans-serif; font-weight:700; font-size:13.5px; flex-shrink:0;}

    /* Info blocks */
    .info-grid{display:grid; grid-template-columns:1fr 1fr; gap:20px;}
    .info-block span.label{display:block; font-size:11px; font-weight:700; color:var(--spring-deep); text-transform:uppercase; letter-spacing:.03em; margin-bottom:4px;}
    .info-block p{font-size:13.5px; color:var(--ink);}

    .payment-chip{
        display:inline-flex; align-items:center; gap:8px;
        background:var(--mint); border-radius:12px; padding:10px 14px;
        font-size:13px; font-weight:600;
    }
    .payment-chip svg{width:18px; height:18px; color:var(--spring-deep); flex-shrink:0;}

    /* Summary */
    .summary{background:var(--mint); border-radius:14px; padding:16px 18px;}
    .summary-row{display:flex; justify-content:space-between; font-size:13px; color:var(--muted); padding:5px 0;}
    .summary-row.total{font-family:'Outfit', sans-serif; font-weight:700; font-size:15px; color:var(--ink); border-top:1px dashed var(--mint-deep); margin-top:8px; padding-top:12px;}

    .divider{height:1px; background:var(--mint-deep); margin:0 0 4px;}

    @media (max-width:560px){
        .info-grid{grid-template-columns:1fr;}
        .detail-card{padding:20px;}
        .page-wrap{padding:20px 16px 50px;}
    }
</style>
</head>
<body>

@php
    $statusOrder = ['menunggu' => 0, 'diproses' => 1, 'dikirim' => 2, 'terkirim' => 3];
    $statusLabel = [
        'menunggu'    => 'Menunggu Konfirmasi',
        'diproses'    => 'Diproses',
        'dikirim'     => 'Sedang Dikirim',
        'terkirim'    => 'Terkirim',
        'dibatalkan'  => 'Dibatalkan',
    ];
    $drugLabel = ['bebas' => 'Obat Bebas', 'terbatas' => 'Obat Bebas Terbatas', 'keras' => 'Obat Keras'];
    $paymentLabel = ['cod' => 'COD (Bayar di Tempat)', 'qris' => 'Transfer QRIS (BSI)'];

    $statusIcons = [
        'menunggu'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'diproses'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'dikirim'    => '<rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 4v4h-7"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="18.5" cy="18.5" r="2"/>',
        'terkirim'   => '<path d="M20 6 9 17l-5-5"/>',
        'dibatalkan' => '<circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/>',
    ];

    $paymentIcons = [
        'cod'  => '<rect x="2" y="6" width="20" height="14" rx="2"/><path d="M2 10h20"/><path d="M6 15h4"/>',
        'qris' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM19 14h2M14 19h2M19 19h2"/>',
    ];

    $status = $order['status'];
    $currentStep = $statusOrder[$status] ?? null;
    $subtotal = collect($order['items'])->sum(fn($i) => $i['price']);
    $shipping = $order['shipping_cost'] ?? 0;
    $total = $subtotal + $shipping;
    $itemCount = collect($order['items'])->sum('qty');
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
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('resep.upload') }}" class="{{ request()->routeIs('resep.*') ? 'active' : '' }}">Upload Resep</a>
            <a href="{{ route('pesanan.index') }}" class="{{ request()->routeIs('pesanan.*') ? 'active' : '' }}">Pesanan Saya</a>
        </div>

        <div class="nav-actions">
            <button class="icon-btn" aria-label="Keranjang">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.6a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                <span class="cart-count">3</span>
            </button>
            <button class="avatar-btn" aria-label="Akun saya">A</button>
        </div>
    </div>
</nav>

<div class="page-wrap">
    <a href="{{ route('pesanan.index') }}" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>
        Kembali ke Pesanan Saya
    </a>

    @if (session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="flash-error">{{ session('error') }}</div>
    @endif

    <div class="detail-card">
        <div class="detail-head">
            <div>
                <div class="order-code">{{ $order['code'] }}</div>
                <div class="order-date">Dipesan pada {{ $order['created_at']->translatedFormat('d F Y') }}</div>
            </div>
            <span class="status-badge st-{{ $status }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $statusIcons[$status] !!}</svg>
                {{ $statusLabel[$status] }}
            </span>
        </div>

        @if($status === 'dibatalkan')
            <div class="cancel-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                Pesanan ini telah dibatalkan.
            </div>
        @else
            @if($status === 'dikirim' && $order['eta_at'] !== null)
                <div class="eta-card">
                    <div class="eta-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                    </div>
                    <div>
                        <div class="eta-title">Perkiraan Tiba</div>
                        <div class="eta-value">
                            {{ $order['eta_at']->translatedFormat('H:i') }} WIB, {{ $order['eta_at']->translatedFormat('d F Y') }}
                        </div>
                        <div class="eta-sub">
                            @if($order['sisa_menit'] > 0)
                                Sekitar {{ $order['sisa_menit'] }} menit lagi
                            @else
                                Segera tiba
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($order['kurir'])
                <div class="courier-card">
                    <div class="courier-avatar">{{ strtoupper(substr($order['kurir']['name'], 0, 1)) }}</div>
                    <div class="courier-info">
                        <div class="courier-name">{{ $order['kurir']['name'] }}</div>
                        <div class="courier-meta">
                            {{ $order['kurir']['jenis_kendaraan'] ?? 'Kendaraan belum diisi' }}
                            @if ($order['kurir']['plat_nomor'])
                                &middot; {{ $order['kurir']['plat_nomor'] }}
                            @endif
                            @if ($order['kurir']['phone'])
                                <br>{{ $order['kurir']['phone'] }}
                            @endif
                        </div>
                    </div>
                    @if ($order['kurir']['phone'])
                        <a href="tel:{{ $order['kurir']['phone'] }}" class="courier-call" aria-label="Telepon kurir">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </a>
                    @endif
                </div>
            @endif

            @if ($status === 'dikirim')
                <form method="POST" action="{{ route('pesanan.terima', $order['code']) }}" onsubmit="return confirm('Konfirmasi kamu sudah menerima pesanan ini?');">
                    @csrf
                    <button type="submit" class="btn-confirm-received">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Pesanan Sudah Diterima
                    </button>
                </form>
            @endif

            <div class="tracker">
                <div class="tracker-line"></div>
                <div class="tracker-line-fill" style="width: {{ $currentStep * 33.33 }}%"></div>

                @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'terkirim' => 'Terkirim'] as $key => $label)
                    @php
                        $stepIndex = $statusOrder[$key];
                        $stateClass = $stepIndex < $currentStep ? 'done' : ($stepIndex === $currentStep ? 'current' : '');
                    @endphp
                    <div class="tracker-step {{ $stateClass }}">
                        <div class="tracker-dot">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">{!! $statusIcons[$key] !!}</svg>
                        </div>
                        <span class="tracker-label">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="section-title">Produk Dipesan</div>
        @foreach ($order['items'] as $item)
            <div class="item-row">
                <div class="item-thumb">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4.93 4.93a6 6 0 0 1 8.49 0l5.66 5.66a6 6 0 1 1-8.49 8.49L4.93 13.4a6 6 0 0 1 0-8.49Z"/></svg>
                </div>
                <div class="item-info">
                    <div class="item-name">{{ $item['name'] }}</div>
                    <div class="item-meta">
                        <span class="item-qty">Jumlah: {{ $item['qty'] }}</span>
                        @if(!empty($item['drug_class']))
                            <span class="drug-badge {{ $item['drug_class'] }}">{{ $drugLabel[$item['drug_class']] }}</span>
                        @endif
                    </div>
                </div>
                <div class="item-price">Rp{{ number_format($item['price'], 0, ',', '.') }}</div>
            </div>
        @endforeach

        <div class="section-title">Informasi Pengiriman</div>
        <div class="info-grid">
            <div class="info-block">
                <span class="label">Alamat Pengiriman</span>
                <p>{{ $order['shipping_address'] }}</p>
            </div>
            <div class="info-block">
                <span class="label">Metode Pembayaran</span>
                <span class="payment-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $paymentIcons[$order['payment_method']] !!}</svg>
                    {{ $paymentLabel[$order['payment_method']] ?? $order['payment_method'] }}
                </span>
            </div>
        </div>

        <div class="section-title">Rincian Pembayaran</div>
        <div class="summary">
            <div class="summary-row">
                <span>Subtotal ({{ $itemCount }} item)</span>
                <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Ongkos Kirim</span>
                <span>Rp{{ number_format($shipping, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row total">
                <span>Total Pembayaran</span>
                <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>