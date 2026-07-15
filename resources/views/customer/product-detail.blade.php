<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $produk->nama }} - Apotek Rizki</title>
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
    body{font-family:'Inter', sans-serif; background:var(--mint); color:var(--ink); -webkit-font-smoothing:antialiased;}
    a{text-decoration:none; color:inherit;}

    .topbar{ max-width:900px; margin:0 auto; padding:20px 28px 0; }
    .back-link{ display:inline-flex; align-items:center; gap:6px; font-size:13.5px; font-weight:600; color:var(--spring-deep); }
    .back-link svg{width:16px; height:16px;}

    .detail-wrap{
        max-width:900px; margin:0 auto; padding:20px 28px 60px;
        display:grid; grid-template-columns:1fr 1.1fr; gap:28px;
    }

    .detail-thumb{
        background:linear-gradient(150deg, var(--mint) 0%, var(--mint-deep) 100%);
        border-radius:24px; aspect-ratio:1/1;
        display:flex; align-items:center; justify-content:center;
        box-shadow:var(--shadow-sm); overflow:hidden; position:relative;
    }
    .detail-thumb img{width:100%; height:100%; object-fit:cover;}
    .detail-thumb svg{width:64px; height:64px; color:var(--spring); opacity:.5;}

    .drug-badge{
        position:absolute; bottom:14px; left:14px;
        width:38px; height:38px; border-radius:50%;
        background:var(--white); padding:5px;
        box-shadow:0 2px 8px rgba(0,0,0,0.18);
        object-fit:contain;
    }

    .detail-body{display:flex; flex-direction:column; gap:14px;}
    .detail-cat{ font-size:11.5px; font-weight:700; color:var(--spring-deep); text-transform:uppercase; letter-spacing:.03em; }
    .detail-name{ font-family:'Outfit', sans-serif; font-size:22px; font-weight:700; }
    .detail-desc{font-size:14px; color:var(--muted); line-height:1.6;}

    .detail-meta{ display:flex; gap:10px; flex-wrap:wrap; }
    .meta-badge{
        font-size:12px; font-weight:600; padding:6px 12px; border-radius:999px;
        background:var(--mint); color:var(--spring-deep);
    }
    .meta-badge.warn{background:#FBEAEA; color:#D64541;}
    .meta-badge.low{background:#FDF3E4; color:var(--amber);}
    .meta-badge.out{background:#FBEAEA; color:var(--error);}

    .detail-price{
        font-family:'Outfit', sans-serif; font-weight:800; font-size:28px; color:var(--ink);
        margin-top:6px;
    }
    .detail-price small{
        display:block; font-family:'Inter', sans-serif; font-weight:500; font-size:12px; color:var(--muted);
    }

    .detail-actions{ display:flex; gap:12px; margin-top:8px; }
    .btn{
        flex:1; padding:14px; border-radius:14px; border:none;
        font-family:'Outfit', sans-serif; font-weight:700; font-size:14px;
        cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;
        transition:background .15s, transform .12s;
    }
    .btn:active{transform:scale(.98);}
    .btn.primary{background:var(--spring); color:var(--white);}
    .btn.primary:hover{background:var(--spring-deep);}
    .btn.primary:disabled{background:var(--mint-deep); color:var(--muted); cursor:not-allowed;}
    .btn.resep{background:#D64541; color:var(--white);}
    .btn.resep:hover{background:#B93733;}

    @media (max-width:720px){
        .detail-wrap{grid-template-columns:1fr; padding:16px 18px 50px;}
        .topbar{padding:16px 18px 0;}
    }
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('dashboard') }}" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali ke Beranda
    </a>
</div>

<div class="detail-wrap">
    <div class="detail-thumb">
        @if($produk->gambar)
            <img src="{{ Storage::url($produk->gambar) }}" alt="{{ $produk->nama }}">
        @else
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.93 4.93a6 6 0 0 1 8.49 0l5.66 5.66a6 6 0 1 1-8.49 8.49L4.93 13.4a6 6 0 0 1 0-8.49Z"/><path d="M9.5 9.5l5 5"/></svg>
        @endif

        @php
            $drugClasses = [
                'obat_bebas'          => ['label' => 'Obat Bebas',          'file' => 'obat_bebas.svg'],
                'obat_bebas_terbatas' => ['label' => 'Obat Bebas Terbatas', 'file' => 'obat_bebas_terbatas.svg'],
                'obat_keras'          => ['label' => 'Obat Keras',          'file' => 'obat_keras.png'],
            ];
            $badge = $drugClasses[$produk->klasifikasi] ?? null;
        @endphp
        @if($badge)
            <img class="drug-badge" src="{{ asset('assets/images/'.$badge['file']) }}" alt="{{ $badge['label'] }}" title="{{ $badge['label'] }}">
        @endif
    </div>

    <div class="detail-body">
        <span class="detail-cat">{{ $produk->kategori }}</span>
        <h1 class="detail-name">{{ $produk->nama }}</h1>
        <p class="detail-desc">{{ $produk->deskripsi ?: 'Belum ada deskripsi untuk produk ini.' }}</p>

        <div class="detail-meta">
            @if($badge)
                <span class="meta-badge">{{ $badge['label'] }}</span>
            @endif
            @if($produk->butuh_resep)
                <span class="meta-badge warn">Perlu Resep Dokter</span>
            @endif
            @if($produk->butuh_ktp)
                <span class="meta-badge warn">Perlu KTP</span>
            @endif
            @if($produk->stok == 0)
                <span class="meta-badge out">Stok Habis</span>
            @elseif($produk->stok <= 5)
                <span class="meta-badge low">Sisa {{ $produk->stok }}</span>
            @else
                <span class="meta-badge">Tersedia</span>
            @endif
        </div>

        <div class="detail-price">
            Rp{{ number_format($produk->harga, 0, ',', '.') }}
            <small>/ item</small>
        </div>

        <div class="detail-actions">
            @if($produk->butuh_resep)
                <a href="{{ route('resep.upload') }}" class="btn resep">
                    Upload Resep Dulu
                </a>
            @else
                <button type="button" class="btn primary" {{ $produk->stok == 0 ? 'disabled' : '' }}>
                    {{ $produk->stok == 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                </button>
            @endif
        </div>
    </div>
</div>

</body>
</html>