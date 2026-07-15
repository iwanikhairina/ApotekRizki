<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan Saya - Apotek Rizki</title>
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

    body{
        font-family:'Inter', sans-serif;
        background:var(--mint);
        color:var(--ink);
        -webkit-font-smoothing:antialiased;
    }

    a{text-decoration:none; color:inherit;}
    button{font-family:inherit;}

    /* ===== NAVBAR ===== */
    .navbar{
        position:sticky; top:0; z-index:50;
        background:var(--white);
        border-bottom:1px solid var(--mint-deep);
    }
    .navbar-inner{
        max-width:1100px; margin:0 auto;
        padding:14px 28px;
        display:flex; align-items:center; gap:28px;
    }
    .nav-brand{display:flex; align-items:center; gap:10px; flex-shrink:0;}
    .nav-brand .logo-box{
        width:42px; height:42px; border-radius:12px;
        background:var(--mint); display:flex; align-items:center; justify-content:center; padding:6px;
        flex-shrink:0;
    }
    .nav-brand .logo-box img{width:100%; height:100%; object-fit:contain;}
    .nav-brand .brand-text{font-family:'Outfit', sans-serif; font-weight:800; font-size:17px; line-height:1.15;}
    .nav-brand .brand-text span{display:block; font-family:'Inter'; font-weight:500; font-size:10.5px; color:var(--muted); letter-spacing:.02em;}
    .nav-links{display:flex; align-items:center; gap:4px; flex:1;}
    .nav-links a{
        font-size:13.5px; font-weight:600; color:var(--muted);
        padding:9px 15px; border-radius:999px; transition:background .15s, color .15s;
        white-space:nowrap;
    }
    .nav-links a:hover{background:var(--mint); color:var(--ink);}
    .nav-links a.active{background:var(--spring); color:var(--white);}
    .nav-toggle{
        display:none; width:40px; height:40px; border-radius:12px; border:none;
        background:var(--mint); align-items:center; justify-content:center; cursor:pointer;
    }
    .nav-toggle svg{width:20px; height:20px; color:var(--ink);}
    .nav-actions{display:flex; align-items:center; gap:10px; flex-shrink:0;}
    .icon-btn{
        width:40px; height:40px; border-radius:12px; border:none; background:var(--mint);
        display:flex; align-items:center; justify-content:center; cursor:pointer; position:relative;
        color:var(--spring-deep); transition:background .15s;
    }
    .icon-btn:hover{background:var(--mint-deep);}
    .icon-btn svg{width:19px; height:19px;}
    .cart-count{
        position:absolute; top:-4px; right:-4px; background:var(--error); color:var(--white);
        font-size:10px; font-weight:700; width:17px; height:17px; border-radius:50%;
        display:flex; align-items:center; justify-content:center; border:2px solid var(--white);
    }
    .avatar-btn{
        width:40px; height:40px; border-radius:50%; background:var(--spring); color:var(--white);
        border:none; font-family:'Outfit'; font-weight:700; font-size:14px; cursor:pointer;
    }

    @media (max-width:920px){
        .nav-links{
            position:fixed; top:70px; left:0; right:0; background:var(--white);
            flex-direction:column; align-items:stretch; padding:10px 16px 16px; gap:2px;
            border-bottom:1px solid var(--mint-deep); box-shadow:var(--shadow-sm); display:none;
        }
        .nav-links.open{display:flex;}
        .nav-links a{padding:12px 14px;}
        .nav-toggle{display:flex;}
    }

    /* ===== PAGE ===== */
    .page-wrap{max-width:900px; margin:0 auto; padding:32px 24px 60px;}

    .page-header{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:16px;
        flex-wrap:wrap;
        margin-bottom:22px;
    }

    .page-title{
        font-family:'Outfit', sans-serif;
        font-size:22px; font-weight:700;
    }

    .order-tabs{
        display:flex;
        gap:6px;
        background:var(--white);
        border-radius:999px;
        padding:5px;
        box-shadow:var(--shadow-sm);
    }
    .tab-btn{
        display:flex;
        align-items:center;
        gap:6px;
        border:none;
        background:transparent;
        font-family:'Outfit', sans-serif;
        font-size:12.5px;
        font-weight:700;
        color:var(--muted);
        padding:9px 16px;
        border-radius:999px;
        cursor:pointer;
        transition:background .15s, color .15s;
        white-space:nowrap;
    }
    .tab-btn svg{width:14px; height:14px;}
    .tab-btn:hover{background:var(--mint);}
    .tab-btn.active{background:var(--spring); color:var(--white);}

    .order-card{
        background:var(--white);
        border-radius:20px;
        box-shadow:var(--shadow-sm);
        padding:22px 24px;
        margin-bottom:20px;
    }

    .order-head{
        display:flex; justify-content:space-between; align-items:flex-start;
        gap:14px; flex-wrap:wrap;
        margin-bottom:14px;
    }
    .order-head-left{display:flex; align-items:center; gap:10px; flex-wrap:wrap;}
    .order-code{font-family:'Outfit', sans-serif; font-weight:700; font-size:15px;}
    .order-date{font-size:12px; color:var(--muted); margin-top:2px;}

    .status-badge{
        display:inline-flex; align-items:center; gap:5px;
        font-size:11px; font-weight:700;
        padding:5px 12px; border-radius:999px;
        white-space:nowrap;
    }
    .status-badge svg{width:12px; height:12px;}
    .status-badge.st-terkirim{background:#E3F5EA; color:var(--spring-deep);}
    .status-badge.st-diproses{background:#FDF1DF; color:#A66A17;}
    .status-badge.st-dikirim{background:#E5F1FB; color:#2E6FA3;}
    .status-badge.st-menunggu{background:#F1EEF2; color:#6B5F73;}
    .status-badge.st-dibatalkan{background:#FBE8E6; color:#B23A29;}

    .eta-badge{
        display:inline-flex; align-items:center; gap:5px;
        font-size:11px; font-weight:700;
        padding:5px 12px; border-radius:999px;
        background:#FFF3E0; color:#C9740E;
        white-space:nowrap;
    }
    .eta-badge svg{width:12px; height:12px;}

    .order-head-right{text-align:right;}
    .order-total{font-family:'Outfit', sans-serif; font-weight:700; font-size:15px; color:var(--sky);}
    .order-item-count{font-size:11px; color:var(--muted); margin-top:2px;}

    .item-row{
        display:flex; align-items:center; gap:14px;
        background:var(--mint);
        border-radius:14px;
        padding:12px 14px;
        margin-bottom:8px;
    }
    .item-thumb{
        width:42px; height:42px; border-radius:10px; flex-shrink:0;
        background:linear-gradient(150deg, var(--mint-deep), #BFE6D3);
        display:flex; align-items:center; justify-content:center;
    }
    .item-thumb svg{width:20px; height:20px; color:var(--spring); opacity:.6;}
    .item-info{flex:1; min-width:0;}
    .item-name{font-family:'Outfit', sans-serif; font-weight:600; font-size:13.5px;}
    .item-meta{display:flex; align-items:center; gap:8px; margin-top:3px; flex-wrap:wrap;}
    .item-qty{font-size:11.5px; color:var(--muted);}
    .drug-badge{
        font-size:9.5px; font-weight:700; padding:2px 8px; border-radius:999px;
        text-transform:uppercase; letter-spacing:.02em;
    }
    .drug-badge.bebas{background:#E3F5EA; color:var(--spring-deep);}
    .drug-badge.terbatas{background:#E5F1FB; color:#2E6FA3;}
    .drug-badge.keras{background:#FBE8E6; color:#B23A29;}
    .item-price{font-family:'Outfit', sans-serif; font-weight:700; font-size:13px; flex-shrink:0;}

    .divider{height:1px; background:var(--mint-deep); margin:16px 0;}

    .info-grid{
        display:grid; grid-template-columns:1fr 1fr; gap:20px;
    }
    .info-block span.label{
        display:block; font-size:11px; font-weight:700; color:var(--spring-deep);
        text-transform:uppercase; letter-spacing:.03em; margin-bottom:4px;
    }
    .info-block p{font-size:13px; color:var(--ink);}

    /* ===== PROGRESS TRACKER ===== */
    .tracker{
        display:flex; justify-content:space-between; align-items:flex-start;
        position:relative; padding:0 4px; margin:8px 0 4px;
    }
    .tracker-line{
        position:absolute; top:14px; left:24px; right:24px; height:2px;
        background:var(--mint-deep); z-index:0;
    }
    .tracker-line-fill{
        position:absolute; top:14px; left:24px; height:2px;
        background:var(--spring); z-index:1;
        transition:width .2s;
    }
    .tracker-step{
        position:relative; z-index:2;
        display:flex; flex-direction:column; align-items:center; gap:6px;
        flex:1;
    }
    .tracker-dot{
        width:28px; height:28px; border-radius:50%;
        background:var(--mint-deep); color:var(--muted);
        display:flex; align-items:center; justify-content:center;
        border:2px solid var(--white);
    }
    .tracker-dot svg{width:14px; height:14px;}
    .tracker-step.done .tracker-dot{background:var(--spring); color:var(--white);}
    .tracker-step.current .tracker-dot{background:var(--spring); color:var(--white); box-shadow:0 0 0 4px #D3EFE0;}
    .tracker-label{font-size:10.5px; font-weight:600; color:var(--muted); text-align:center;}
    .tracker-step.done .tracker-label,
    .tracker-step.current .tracker-label{color:var(--ink); font-weight:700;}

    .cancel-note{
        display:flex; align-items:center; gap:10px;
        background:#FBE8E6; color:#B23A29;
        border-radius:14px; padding:12px 16px;
        font-size:12.5px; font-weight:600;
    }
    .cancel-note svg{width:18px; height:18px; flex-shrink:0;}

    /* ===== PAYMENT SUMMARY ===== */
    .summary{
        background:var(--mint);
        border-radius:14px;
        padding:14px 16px;
        margin-top:16px;
    }
    .summary-row{
        display:flex; justify-content:space-between;
        font-size:12.5px; color:var(--muted);
        padding:4px 0;
    }
    .summary-row.total{
        font-family:'Outfit', sans-serif;
        font-weight:700; font-size:14px; color:var(--ink);
        border-top:1px dashed var(--mint-deep);
        margin-top:6px; padding-top:10px;
    }

    /* ===== DETAIL BUTTON ===== */
    .detail-btn{
        display:flex;
        align-items:center;
        justify-content:center;
        gap:6px;
        width:100%;
        margin-top:14px;
        padding:12px;
        border-radius:14px;
        background:var(--white);
        border:2px solid var(--mint-deep);
        color:var(--spring-deep);
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13px;
        cursor:pointer;
        transition:background .15s, border-color .15s;
    }
    .detail-btn:hover{background:var(--mint); border-color:var(--spring);}
    .detail-btn svg{width:16px; height:16px;}

    .empty-orders, .empty-filtered{
        text-align:center; padding:60px 20px; color:var(--muted);
    }
    .empty-orders svg, .empty-filtered svg{width:48px; height:48px; color:var(--mint-deep); margin-bottom:10px;}
    .empty-filtered{display:none;}

    @media (max-width:600px){
        .info-grid{grid-template-columns:1fr;}
        .order-head-right{text-align:left;}
        .navbar-inner{padding:12px 18px;}
        .page-wrap{padding:22px 16px 50px;}
        .page-header{flex-direction:column; align-items:flex-start;}
    }
</style>
</head>
<body>

@php
    // Peta status -> urutan tracker (untuk mengisi progress bar) & label tampilan
    $statusOrder = ['menunggu' => 0, 'diproses' => 1, 'dikirim' => 2, 'terkirim' => 3];
    $statusLabel = [
        'menunggu'    => 'Menunggu Konfirmasi',
        'diproses'    => 'Diproses',
        'dikirim'     => 'Sedang Dikirim',
        'terkirim'    => 'Terkirim',
        'dibatalkan'  => 'Dibatalkan',
    ];
    $drugLabel = ['bebas' => 'Obat Bebas', 'terbatas' => 'Obat Bebas Terbatas', 'keras' => 'Obat Keras'];

    // Hanya 2 metode pembayaran yang tersedia
    $paymentLabel = [
        'cod'  => 'COD (Bayar di Tempat)',
        'qris' => 'Transfer QRIS (BSI)',
    ];

    $statusIcons = [
        'menunggu'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'diproses'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'dikirim'    => '<rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 4v4h-7"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="18.5" cy="18.5" r="2"/>',
        'terkirim'   => '<path d="M20 6 9 17l-5-5"/>',
        'dibatalkan' => '<circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/>',
    ];
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
            <a href="{{ route('cart.index') }}" class="icon-btn" aria-label="Keranjang">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.6a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                <span class="cart-count" id="navCartCount">{{ $cartCount ?? 0 }}</span>
            </a>
            <a href="{{ route('profile.index') }}" class="avatar-btn" aria-label="Akun saya">
    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
</a>
        </div>
    </div>
</nav>

<div class="page-wrap">
    <div class="page-header">
        <h1 class="page-title">Pesanan Saya</h1>
        <div class="order-tabs" id="orderTabs">
            <button class="tab-btn active" data-filter="semua">Semua Pesanan</button>
            <button class="tab-btn" data-filter="riwayat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                Riwayat
            </button>
        </div>
    </div>

    @forelse ($orders as $order)
        @php
            $status = $order['status'];
            $currentStep = $statusOrder[$status] ?? null;
            $subtotal = collect($order['items'])->sum(fn($i) => $i['price']);
            $shipping = $order['shipping_cost'] ?? 0;
            $total = $subtotal + $shipping;
            $itemCount = collect($order['items'])->sum('qty');
        @endphp

        <div class="order-card" data-status="{{ $status }}">
            <div class="order-head">
                <div class="order-head-left">
                    <div>
                        <div class="order-code">{{ $order['code'] }}</div>
                        <div class="order-date">Dipesan pada {{ $order['created_at']->translatedFormat('d F Y') }}</div>
                    </div>
                    <span class="status-badge st-{{ $status }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $statusIcons[$status] !!}</svg>
                        {{ $statusLabel[$status] }}
                    </span>
                    @if(!in_array($status, ['terkirim', 'dibatalkan']) && $order['eta_at'] !== null)
                        <span class="eta-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                            Tiba {{ $order['eta_at']->translatedFormat('H:i, d M') }} ({{ $order['sisa_menit'] }} menit lagi)
                        </span>
                    @endif
                </div>
                <div class="order-head-right">
                    <div class="order-total">Rp{{ number_format($total, 0, ',', '.') }}</div>
                    <div class="order-item-count">{{ $itemCount }} item</div>
                </div>
            </div>

            {{-- Daftar obat / produk yang dipesan --}}
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

            <div class="divider"></div>

            <div class="info-grid">
                <div class="info-block">
                    <span class="label">Alamat Pengiriman</span>
                    <p>{{ $order['shipping_address'] }}</p>
                </div>
                <div class="info-block">
                    <span class="label">Metode Pembayaran</span>
                    <p>{{ $paymentLabel[$order['payment_method']] ?? $order['payment_method'] }}</p>
                </div>
            </div>

            <div class="divider"></div>

            @if($status === 'dibatalkan')
                <div class="cancel-note">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                    Pesanan ini telah dibatalkan.
                </div>
            @else
                {{-- Progress tracker 4 tahap --}}
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

            {{-- Rincian pembayaran: subtotal + ongkir + total --}}
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

            <a href="{{ route('pesanan.detail', $order['code']) }}" class="detail-btn">
                Lihat Detail Pesanan
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
            </a>
        </div>
    @empty
        <div class="empty-orders">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 4v4h-7"/></svg>
            <h3 style="font-family:'Outfit'; color:var(--ink); font-size:15px;">Belum ada pesanan</h3>
            <p style="font-size:12.5px; margin-top:4px;">Pesanan yang kamu buat akan muncul di sini.</p>
        </div>
    @endforelse

    <div class="empty-filtered" id="emptyFiltered">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
        <h3 style="font-family:'Outfit'; color:var(--ink); font-size:15px;">Belum ada riwayat pesanan</h3>
        <p style="font-size:12.5px; margin-top:4px;">Pesanan yang sudah selesai atau dibatalkan akan muncul di sini.</p>
    </div>
</div>

<script>
(function(){
    const tabButtons = document.querySelectorAll('#orderTabs .tab-btn');
    const orderCards = document.querySelectorAll('.order-card');
    const emptyFiltered = document.getElementById('emptyFiltered');
    const emptyFilteredTitle = emptyFiltered.querySelector('h3');
    const emptyFilteredText = emptyFiltered.querySelector('p');

    // Status yang dianggap "riwayat": pesanan yang sudah selesai/berakhir.
    const historyStatuses = ['terkirim', 'dibatalkan'];

    function applyTabFilter(filter){
        let visibleCount = 0;

        orderCards.forEach(card => {
            const status = card.dataset.status;
            const isHistory = historyStatuses.includes(status);
            // Tab "semua" = pesanan aktif saja (bukan riwayat).
            // Tab "riwayat" = hanya yang sudah selesai/dibatalkan.
            const show = filter === 'riwayat' ? isHistory : !isHistory;
            card.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        if (visibleCount === 0) {
            if (filter === 'riwayat') {
                emptyFilteredTitle.textContent = 'Belum ada riwayat pesanan';
                emptyFilteredText.textContent = 'Pesanan yang sudah selesai atau dibatalkan akan muncul di sini.';
            } else {
                emptyFilteredTitle.textContent = 'Tidak ada pesanan aktif';
                emptyFilteredText.textContent = 'Pesanan yang sedang berjalan akan muncul di sini.';
            }
            emptyFiltered.style.display = 'block';
        } else {
            emptyFiltered.style.display = 'none';
        }
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            applyTabFilter(btn.dataset.filter);
        });
    });

    // Jalankan sekali di awal supaya tab "Semua Pesanan" (default aktif)
    // langsung menyembunyikan pesanan yang sudah selesai/dibatalkan.
    applyTabFilter('semua');
})();
</script>