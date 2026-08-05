<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
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

    /* Cancel button + modal */
    .btn-cancel-order{
        width:100%;
        background:var(--white);
        color:var(--error);
        border:2px solid #f3c8c2;
        padding:13px;
        border-radius:14px;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13.5px;
        cursor:pointer;
        margin-bottom:18px;
        transition:background .15s;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
    }
    .btn-cancel-order:hover{background:#FCE7E3;}
    .btn-cancel-order svg{width:17px; height:17px;}

    .cancel-reason{
        font-size:12.5px;
        color:#B23A29;
        margin-top:6px;
        font-weight:500;
    }

    .cancel-modal-backdrop{
        display:none;
        position:fixed;
        inset:0;
        background:rgba(29,43,38,0.55);
        z-index:200;
        align-items:center;
        justify-content:center;
        padding:20px;
    }
    .cancel-modal-backdrop.open{display:flex;}

    .cancel-modal{
        background:var(--white);
        border-radius:22px;
        padding:26px;
        max-width:400px;
        width:100%;
        box-shadow:var(--shadow-md);
    }

    .cancel-modal h3{
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:16px;
        margin-bottom:6px;
        color:var(--ink);
    }
    .cancel-modal p{
        font-size:12.5px;
        color:var(--muted);
        line-height:1.5;
        margin-bottom:16px;
    }

    .cancel-modal textarea{
        width:100%;
        border:2px solid var(--mint-deep);
        border-radius:14px;
        padding:12px 14px;
        font-size:13px;
        font-family:inherit;
        color:var(--ink);
        outline:none;
        resize:vertical;
        min-height:90px;
    }
    .cancel-modal textarea:focus{border-color:var(--error);}

    .cancel-modal-actions{
        display:flex;
        gap:10px;
        margin-top:16px;
    }

    .cancel-modal-btn{
        flex:1;
        padding:12px;
        border-radius:14px;
        border:none;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13px;
        cursor:pointer;
    }
    .cancel-modal-btn.secondary{background:var(--mint); color:var(--ink);}
    .cancel-modal-btn.secondary:hover{background:var(--mint-deep);}
    .cancel-modal-btn.primary{background:var(--error); color:var(--white);}
    .cancel-modal-btn.primary:hover{background:#c8452f;}

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

    .resep-card{
        background:var(--white);
        border:1.5px solid var(--mint-deep);
        border-radius:16px;
        padding:18px;
        margin-bottom:18px;
    }
    .resep-card-head{
        display:flex; align-items:center; gap:12px;
        margin-bottom:4px;
    }
    .resep-icon{
        width:42px; height:42px; border-radius:12px;
        background:var(--mint); color:var(--spring-deep);
        display:flex; align-items:center; justify-content:center;
        flex-shrink:0;
    }
    .resep-icon svg{width:20px; height:20px;}
    .resep-title{font-family:'Outfit', sans-serif; font-weight:700; font-size:14.5px;}
    .resep-sub{font-size:12px; color:var(--muted); margin-top:1px;}

    .resep-status{
        display:inline-flex; align-items:center; gap:5px;
        font-size:11px; font-weight:700;
        padding:5px 12px; border-radius:999px;
        margin-top:12px;
    }
    .resep-status svg{width:12px; height:12px;}
    .resep-status.st-menunggu{background:#FDF1DF; color:#A66A17;}
    .resep-status.st-disetujui{background:#E3F5EA; color:var(--spring-deep);}
    .resep-status.st-ditolak{background:#FBE8E6; color:#B23A29;}

    .resep-body{margin-top:14px;}
    .resep-note{font-size:12.5px; color:var(--muted); line-height:1.5; margin-bottom:14px;}

    .resep-preview{
        display:flex; align-items:center; gap:14px; flex-wrap:wrap;
    }
    .resep-preview img{
        width:88px; height:88px; object-fit:cover;
        border-radius:12px; border:1px solid var(--mint-deep);
    }

    .resep-dropzone{
        display:block;
        border:2px dashed var(--mint-deep);
        border-radius:14px;
        background:var(--mint);
        padding:20px 16px;
        text-align:center;
        cursor:pointer;
        transition:border-color .15s, background .15s;
    }
    .resep-dropzone:hover{border-color:var(--spring); background:var(--mint-deep);}
    .resep-dropzone svg{width:24px; height:24px; color:var(--spring-deep); margin-bottom:8px;}
    .resep-dropzone .dz-title{font-size:13px; font-weight:600; color:var(--ink);}
    .resep-dropzone .dz-sub{font-size:11px; color:var(--muted); margin-top:2px;}
    .resep-dropzone input[type="file"]{display:none;}

    .resep-filename{
        display:none;
        align-items:center; gap:8px;
        font-size:12.5px; font-weight:600; color:var(--spring-deep);
        margin-top:10px;
    }
    .resep-filename svg{width:15px; height:15px; flex-shrink:0;}

    .resep-error{
        color:var(--error); font-size:12px; font-weight:600;
        margin-top:8px;
    }

    .btn-resep-submit{
        width:100%;
        background:var(--spring); color:#fff; border:none;
        padding:12px; border-radius:12px;
        font-family:'Outfit', sans-serif; font-weight:700; font-size:13.5px;
        cursor:pointer; margin-top:12px;
        transition:background .15s;
    }
    .btn-resep-submit:hover{background:var(--spring-deep);}

    /* ===== CARD PEMBAYARAN ===== */
    .payment-card{
        background:var(--white);
        border:1.5px solid #BFE0F5;
        border-radius:16px;
        padding:18px;
        margin-bottom:18px;
    }
    .payment-total{
        background:#E5F1FB;
        border-radius:12px;
        padding:14px 16px;
        margin-bottom:14px;
    }
    .payment-total .label{font-size:11px; font-weight:700; color:#2E6FA3; text-transform:uppercase; letter-spacing:.03em;}
    .payment-total .value{font-family:'Outfit', sans-serif; font-weight:800; font-size:20px; color:var(--ink); margin-top:4px;}
    .payment-total .breakdown{font-size:11.5px; color:var(--muted); margin-top:4px;}

    .payment-methods{display:flex; gap:10px; flex-wrap:wrap; margin-bottom:10px;}
    .btn-pay{
        flex:1; min-width:140px;
        border:2px solid var(--mint-deep);
        background:var(--white);
        color:var(--ink);
        padding:13px;
        border-radius:14px;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13.5px;
        cursor:pointer;
        transition:border-color .15s, background .15s;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
    }
    .btn-pay svg{width:17px; height:17px;}
    .btn-pay:hover{border-color:var(--spring); background:var(--mint);}
    .btn-pay.qris:hover{border-color:#2E6FA3; background:#E5F1FB;}

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

    .info-grid{display:grid; grid-template-columns:1fr 1fr; gap:20px;}
    .info-block span.label{display:block; font-size:11px; font-weight:700; color:var(--spring-deep); text-transform:uppercase; letter-spacing:.03em; margin-bottom:4px;}
    .info-block p{font-size:13.5px; color:var(--ink);}

    .payment-chip{
        display:inline-flex; align-items:center; gap:8px;
        background:var(--mint); border-radius:12px; padding:10px 14px;
        font-size:13px; font-weight:600;
    }
    .payment-chip svg{width:18px; height:18px; color:var(--spring-deep); flex-shrink:0;}

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

<x-customer-navbar />

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
                {{-- Kode pesanan (P019, dsb) sengaja TIDAK ditampilkan ke customer,
                     tapi tetap tersimpan di $order['code'] dan tetap dipakai di
                     semua route/form action di bawah (upload resep, batalkan,
                     bayar, terima). Untuk apoteker/kurir/owner kode tetap tampil
                     seperti biasa di panel masing-masing. --}}
                <div class="order-code">Pesanan Anda</div>
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
                <div>
                    Pesanan ini telah dibatalkan.
                    @if($order['alasan_batal'])
                        <div class="cancel-reason">Alasan: {{ $order['alasan_batal'] }}</div>
                    @endif
                </div>
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

            @if ($order['menunggu_pembayaran'])
                <div class="payment-card">
                    <div class="resep-card-head" style="margin-bottom:14px;">
                        <div class="resep-icon" style="background:#E5F1FB; color:#2E6FA3;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                        </div>
                        <div>
                            <div class="resep-title">Resep Disetujui — Pilih Pembayaran</div>
                            <div class="resep-sub">Apoteker sudah menyiapkan obat sesuai resepmu</div>
                        </div>
                    </div>

                    <div class="payment-total">
                        <div class="label">Total Tagihan</div>
                        <div class="value">Rp{{ number_format($order['total_harga'] + $order['shipping_cost'], 0, ',', '.') }}</div>
                        <div class="breakdown">
                            Obat Rp{{ number_format($order['total_harga'], 0, ',', '.') }}
                            + Ongkir Rp{{ number_format($order['shipping_cost'], 0, ',', '.') }}
                        </div>
                    </div>

                    <form method="POST" action="{{ route('pesanan.bayar', $order['code']) }}">
                        @csrf
                        <div class="payment-methods">
                            <button type="submit" name="metode_pembayaran" value="cod" class="btn-pay">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M2 10h20"/><path d="M6 15h4"/></svg>
                                Bayar COD
                            </button>
                            <button type="submit" name="metode_pembayaran" value="qris" class="btn-pay qris">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                                Bayar QRIS
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            @if ($order['can_cancel'])
                <button type="button" class="btn-cancel-order" id="openCancelModal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                    Batalkan Pesanan
                </button>
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

        @if($order['requires_resep'])
            <div class="resep-card">
                <div class="resep-card-head">
                    <div class="resep-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15h6M9 11h2"/></svg>
                    </div>
                    <div>
                        <div class="resep-title">Resep Dokter</div>
                        <div class="resep-sub">Pesanan ini berisi obat yang memerlukan resep</div>
                    </div>
                </div>

                @if(in_array($order['status_resep'], ['menunggu', 'disetujui', 'ditolak']))
                    <span class="resep-status st-{{ $order['status_resep'] }}">
                        @if($order['status_resep'] === 'menunggu')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                            Menunggu Verifikasi Apoteker
                        @elseif($order['status_resep'] === 'disetujui')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Resep Disetujui
                        @elseif($order['status_resep'] === 'ditolak')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                            Resep Ditolak — Unggah Ulang
                        @endif
                    </span>
                @endif

                <div class="resep-body">
                    @if($order['resep_url'] && $order['status_resep'] !== 'ditolak')
                        <div class="resep-preview">
                            <img src="{{ $order['resep_url'] }}" alt="Foto resep yang diunggah">
                            <div>
                                <div class="resep-note" style="margin-bottom:0;">
                                    @if($order['status_resep'] === 'disetujui')
                                        Resep sudah diverifikasi dan disetujui apoteker.
                                    @else
                                        Resep sedang ditinjau apoteker, mohon tunggu konfirmasi.
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="resep-note">
                            @if($order['status_resep'] === 'ditolak')
                                Resep sebelumnya ditolak apoteker. Silakan unggah ulang foto resep yang jelas dan sesuai.
                            @else
                                Unggah foto resep dokter agar apoteker dapat memverifikasi pesananmu.
                            @endif
                        </p>

                        <form action="{{ route('pesanan.upload-resep', $order['code']) }}" method="POST" enctype="multipart/form-data" id="resepForm">
                            @csrf
                            <label class="resep-dropzone" for="resepInput">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M17 8l-5-5-5 5"/><path d="M12 3v12"/></svg>
                                <div class="dz-title">Klik untuk pilih foto resep</div>
                                <div class="dz-sub">JPG atau PNG, maks 5MB</div>
                                <input type="file" name="resep" id="resepInput" accept="image/png, image/jpeg" required>
                            </label>

                            <div class="resep-filename" id="resepFilename">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                <span id="resepFilenameText"></span>
                            </div>

                            @error('resep')
                                <div class="resep-error">{{ $message }}</div>
                            @enderror

                            <button type="submit" class="btn-resep-submit">Unggah Resep</button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        <div class="section-title">Produk Dipesan</div>
        @forelse ($order['items'] as $item)
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
        @empty
            <p style="font-size:12.5px; color:var(--muted); margin-bottom:8px;">
                Belum ada obat pada pesanan ini. Apoteker akan menambahkan obat sesuai resep yang kamu unggah.
            </p>
        @endforelse

        <div class="section-title">Informasi Pengiriman</div>
        <div class="info-grid">
            <div class="info-block">
                <span class="label">Alamat Pengiriman</span>
                <p>{{ $order['shipping_address'] }}</p>
            </div>
            <div class="info-block">
                <span class="label">Metode Pembayaran</span>
                <span class="payment-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $paymentIcons[$order['payment_method']] ?? '<circle cx="12" cy="12" r="9"/><path d="M12 16v-4M12 8h.01"/>' !!}</svg>
                    {{ $paymentLabel[$order['payment_method']] ?? 'Belum dipilih' }}
                </span>
            </div>
            @if ($order['jadwal_pengantaran_label'])
                <div class="info-block">
                    <span class="label">Jadwal Pengantaran</span>
                    <span class="payment-chip">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        {{ $order['jadwal_pengantaran_label'] }}
                    </span>
                </div>
            @endif
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

@if ($order['can_cancel'])
<div class="cancel-modal-backdrop" id="cancelModal">
    <div class="cancel-modal">
        {{-- Kode pesanan sengaja tidak disebut di judul modal ini juga --}}
        <h3>Batalkan Pesanan Ini?</h3>
        <p>Beri tahu kami kenapa kamu ingin membatalkan pesanan ini. Tindakan ini tidak bisa dibatalkan.</p>

        <form method="POST" action="{{ route('pesanan.batalkan', $order['code']) }}">
            @csrf
            <textarea name="alasan_batal" placeholder="Contoh: Salah pesan produk, ingin ganti alamat, dll." required minlength="5" maxlength="500"></textarea>

            <div class="cancel-modal-actions">
                <button type="button" class="cancel-modal-btn secondary" id="closeCancelModal">Batal</button>
                <button type="submit" class="cancel-modal-btn primary">Ya, Batalkan Pesanan</button>
            </div>
        </form>
    </div>
</div>
@endif

@if ($order['jadwal_popup_show'] && $order['jadwal_pengantaran_label'])
<div class="cancel-modal-backdrop" id="jadwalModal">
    <div class="cancel-modal">
        <h3>📦 Info Jadwal Pengantaran</h3>
        <p>
            Pesanan Anda dijadwalkan untuk diantar pada pukul <strong>{{ $order['jadwal_pengantaran_label'] }}</strong>. Kamu bisa melihat kembali jadwal ini kapan saja di bagian Informasi Pengiriman pada halaman ini.
        </p>
        <div class="cancel-modal-actions">
            <button type="button" class="cancel-modal-btn primary" id="closeJadwalModal" style="background:var(--spring);">Mengerti</button>
        </div>
    </div>
</div>
@endif

<script>
(function(){
    const input = document.getElementById('resepInput');
    const filenameBox = document.getElementById('resepFilename');
    const filenameText = document.getElementById('resepFilenameText');

    if (input) {
        input.addEventListener('change', () => {
            if (input.files && input.files.length > 0) {
                filenameText.textContent = input.files[0].name;
                filenameBox.style.display = 'flex';
            } else {
                filenameBox.style.display = 'none';
            }
        });
    }

    const cancelModal = document.getElementById('cancelModal');
    const openCancelModal = document.getElementById('openCancelModal');
    const closeCancelModal = document.getElementById('closeCancelModal');

    if (openCancelModal && cancelModal) {
        openCancelModal.addEventListener('click', () => cancelModal.classList.add('open'));
        closeCancelModal.addEventListener('click', () => cancelModal.classList.remove('open'));
        cancelModal.addEventListener('click', (e) => {
            if (e.target === cancelModal) cancelModal.classList.remove('open');
        });
    }

    // Popup jadwal pengantaran: server hanya mengirim elemen ini ke halaman
    // saat pop-up memang harus tampil (pertama kali dibuka / baru dibuat).
    // Kunjungan berikutnya tidak akan me-render elemen ini lagi.
    const jadwalModal = document.getElementById('jadwalModal');
    const closeJadwalModal = document.getElementById('closeJadwalModal');

    if (jadwalModal) {
        setTimeout(() => jadwalModal.classList.add('open'), 350);

        closeJadwalModal.addEventListener('click', () => jadwalModal.classList.remove('open'));
        jadwalModal.addEventListener('click', (e) => {
            if (e.target === jadwalModal) jadwalModal.classList.remove('open');
        });
    }
})();
</script>

</body>
</html>