<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard - Apotek Rizki</title>
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
        --peach:#FFE4D8;
        --amber:#E8A33D;
        --lilac:#8C7AE6;
        --sky:#4E9BD9;
        --leaf:#6FA83C;
        --coral:#E0715B;
        --teal:#2FA5A0;
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

    /* ===== HERO / SEARCH ===== */
    .hero{
        max-width:1240px;
        margin:0 auto;
        padding:34px 28px 10px;
    }

    .hero-top{
        display:flex;
        align-items:stretch;
        gap:24px;
    }

    .hero-left{
        flex:1 1 380px;
        min-width:0;
        display:flex;
        flex-direction:column;
        justify-content:center;
        position:relative;
        overflow:hidden;
    }

    .hero-left-content{
        position:relative;
        z-index:1;
    }

    .hero-bottle-deco{
        position:absolute;
        z-index:0;
        pointer-events:none;
        object-fit:contain;
    }

    @media (min-width:1080px){
        .hero-bottle-deco{
            display:block;
            left:-76px;
            top:50%;
            width:66px;
            filter:drop-shadow(0 10px 14px rgba(29,43,38,0.16));
            animation:heroBottleFloat 3.6s ease-in-out infinite;
            opacity:1;
        }
    }

    @media (max-width:1079px){
        .hero-bottle-deco{
            display:block;
            right:-24px;
            top:50%;
            transform:translateY(-50%) rotate(-14deg);
            width:190px;
            opacity:.16;
            filter:none;
            animation:heroBottleFloatSlow 6s ease-in-out infinite;
        }
    }

    @keyframes heroBottleFloat{
        0%,100%{transform:translateY(-50%) rotate(-8deg);}
        50%{transform:translateY(-58%) rotate(-3deg);}
    }

    @keyframes heroBottleFloatSlow{
        0%,100%{transform:translateY(-50%) rotate(-14deg);}
        50%{transform:translateY(-54%) rotate(-9deg);}
    }

    .hero-greeting{
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:24px;
        color:var(--ink);
        margin-bottom:4px;
    }

    .hero-greeting .wave{display:inline-block; transform-origin:70% 70%;}

    .hero-sub{
        font-size:13.5px;
        color:var(--muted);
        margin-bottom:20px;
    }

    .search-shell{
        display:flex;
        align-items:center;
        gap:10px;
        background:var(--white);
        border:2px solid var(--mint-deep);
        border-radius:20px;
        padding:6px 8px 6px 20px;
        box-shadow:var(--shadow-sm);
        transition:border-color .15s;
    }
    .search-shell:focus-within{border-color:var(--spring);}

    .search-shell svg.search-ic{
        width:19px; height:19px;
        color:var(--spring-deep);
        flex-shrink:0;
    }

    .search-shell input{
        flex:1;
        border:none;
        outline:none;
        background:transparent;
        font-size:14.5px;
        font-family:inherit;
        padding:11px 0;
        color:var(--ink);
        min-width:0;
    }

    .search-shell input::placeholder{color:var(--muted);}

    .search-shell .search-submit{
        background:var(--spring);
        color:var(--white);
        border:none;
        border-radius:14px;
        padding:11px 20px;
        font-size:13.5px;
        font-weight:700;
        font-family:'Outfit', sans-serif;
        cursor:pointer;
        flex-shrink:0;
        transition:background .15s;
    }
    .search-shell .search-submit:hover{background:var(--spring-deep);}

    .hero-visual-frame{
        flex:1 1 420px;
        background:var(--mint-deep);
        border-radius:26px;
        padding:14px;
        box-shadow:var(--shadow-sm);
    }

    .hero-visual{
        display:flex;
        overflow-x:auto;
        scroll-snap-type:x mandatory;
        -webkit-overflow-scrolling:touch;
        scrollbar-width:none;
        border-radius:18px;
    }
    .hero-visual::-webkit-scrollbar{display:none;}

    .hero-promo-card{
        position:relative;
        flex:0 0 100%;
        scroll-snap-align:start;
        border-radius:18px;
        overflow:hidden;
        min-height:280px;
        background:var(--white);
        cursor:pointer;
        border:none;
        padding:0;
        text-align:left;
        font-family:inherit;
        animation:heroCardIn .55s ease both;
        transition:box-shadow .28s ease;
    }
    .hero-promo-card:nth-child(2){animation-delay:.09s;}

    @keyframes heroCardIn{
        from{opacity:0; transform:translateY(16px) scale(.98);}
        to{opacity:1; transform:translateY(0) scale(1);}
    }

    .hero-promo-card:hover,
    .hero-promo-card:focus-visible{
        box-shadow:0 18px 32px -14px rgba(12,45,33,0.32);
        outline:none;
    }

    .hero-promo-card .promo-img-wrap{
        position:absolute;
        inset:0;
        overflow:hidden;
    }

    .hero-promo-card img{
        position:absolute;
        inset:0;
        width:100%;
        height:100%;
        object-fit:cover;
        transition:transform .55s cubic-bezier(.2,.8,.2,1);
    }

    .hero-promo-card:hover img{transform:scale(1.07);}

    .hero-promo-card::after{
        content:'';
        position:absolute;
        inset:0;
        background:linear-gradient(0deg, rgba(12,45,33,0.65) 0%, rgba(12,45,33,0) 45%);
        transition:background .28s ease;
        pointer-events:none;
    }

    .hero-promo-card.no-caption::after{
        background:linear-gradient(0deg, rgba(12,45,33,0.15) 0%, rgba(12,45,33,0) 30%);
    }

    .promo-badge{
        position:absolute;
        top:12px; left:12px;
        z-index:3;
        background:rgba(255,255,255,.94);
        color:var(--badge-fg, var(--spring-deep));
        font-family:'Outfit', sans-serif;
        font-size:10px;
        font-weight:800;
        padding:5px 11px;
        border-radius:999px;
        letter-spacing:.02em;
        box-shadow:0 4px 10px -4px rgba(29,43,38,0.25);
    }

    .promo-cta{
        position:absolute;
        right:12px; bottom:12px;
        z-index:3;
        width:34px; height:34px;
        border-radius:50%;
        background:var(--white);
        color:var(--ink);
        display:flex;
        align-items:center;
        justify-content:center;
        opacity:0;
        transform:translateY(6px) scale(.85);
        transition:opacity .25s ease, transform .25s ease;
        box-shadow:0 6px 14px -6px rgba(29,43,38,0.35);
    }
    .promo-cta svg{width:14px; height:14px;}

    .hero-promo-card:hover .promo-cta,
    .hero-promo-card:focus-visible .promo-cta{
        opacity:1;
        transform:translateY(0) scale(1);
    }

    .hero-promo-text{
        position:absolute;
        left:16px; right:48px; bottom:16px;
        z-index:2;
        color:var(--white);
    }

    .hero-promo-text span{
        display:block;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:15px;
        line-height:1.25;
    }

    .hero-promo-text small{
        display:block;
        font-size:11.5px;
        font-weight:500;
        opacity:.9;
        margin-top:3px;
    }

    .hero-visual-dots{
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        margin-top:12px;
    }

    .hero-dot{
        position:relative;
        width:22px; height:5px;
        border-radius:999px;
        background:var(--white);
        cursor:pointer;
        overflow:hidden;
        transition:background .2s ease;
    }

    .hero-dot .hero-dot-fill{
        position:absolute;
        inset:0;
        width:0%;
        background:var(--spring-deep);
        border-radius:999px;
    }

    .hero-dot.active .hero-dot-fill{
        animation:heroDotProgress 4.2s linear forwards;
    }

    .hero-dot.visited .hero-dot-fill{width:100%;}

    @keyframes heroDotProgress{
        from{width:0%;}
        to{width:100%;}
    }

    @media (max-width:860px){
        .hero-top{flex-direction:column;}
    }

    @media (max-width:640px){
        .hero-promo-card{min-height:200px;}
        .hero-visual-frame{padding:10px 10px 8px;}
        .hero-promo-text span{font-size:13.5px;}
    }

    .cat-section{
        max-width:1240px;
        margin:0 auto;
        padding:26px 28px 4px;
    }

    .cat-heading{
        display:flex;
        align-items:baseline;
        justify-content:space-between;
        margin-bottom:14px;
    }

    .cat-heading h2{
        font-family:'Outfit', sans-serif;
        font-size:16px;
        font-weight:700;
    }

    .cat-heading span{
        font-size:12px;
        color:var(--muted);
        font-weight:500;
    }

    .blister-strip{
        display:flex;
        gap:0;
        background:var(--white);
        border-radius:22px;
        padding:16px 10px;
        box-shadow:var(--shadow-sm);
        overflow-x:auto;
        scrollbar-width:none;
    }
    .blister-strip::-webkit-scrollbar{display:none;}

    .cat-pill{
        display:flex;
        flex-direction:column;
        align-items:center;
        gap:8px;
        border:none;
        background:transparent;
        cursor:pointer;
        padding:8px 16px;
        position:relative;
        flex-shrink:0;
        min-width:84px;
    }

    .cat-pill:not(:last-child)::after{
        content:'';
        position:absolute;
        right:0;
        top:8px;
        bottom:8px;
        width:1px;
        background:repeating-linear-gradient(to bottom, var(--mint-deep) 0 4px, transparent 4px 8px);
    }

    .cat-pill .bump{
        width:52px; height:52px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        background:var(--cat-bg, var(--mint));
        color:var(--cat-fg, var(--spring-deep));
        transition:transform .15s, box-shadow .15s;
        box-shadow:inset 0 -3px 0 rgba(0,0,0,0.05);
        position:relative;
    }

    .age-badge{
        position:absolute;
        bottom:-4px; right:-4px;
        background:var(--ink);
        color:var(--white);
        font-size:8.5px;
        font-weight:800;
        padding:2px 5px;
        border-radius:999px;
        border:2px solid var(--white);
        letter-spacing:.02em;
    }

    .cat-pill .bump svg{width:24px; height:24px;}

    .cat-pill span.label{
        font-size:11.5px;
        font-weight:600;
        color:var(--muted);
        text-align:center;
        line-height:1.2;
    }

    .cat-pill:hover .bump{transform:translateY(-3px);}

    .cat-pill.active .bump{
        background:var(--cat-fg, var(--spring));
        color:var(--white);
        box-shadow:0 10px 18px -8px var(--cat-fg, var(--spring));
        transform:translateY(-3px) scale(1.04);
    }
    .cat-pill.active span.label{color:var(--ink); font-weight:700;}

    .products-section{
        max-width:1240px;
        margin:0 auto;
        padding:30px 28px 60px;
    }

    .products-heading{
        display:flex;
        align-items:center;
        justify-content:space-between;
        margin-bottom:16px;
    }

    .products-heading h2{
        font-family:'Outfit', sans-serif;
        font-size:17px;
        font-weight:700;
    }

    .products-heading .count-tag{
        font-size:12px;
        color:var(--spring-deep);
        background:var(--mint);
        padding:5px 12px;
        border-radius:999px;
        font-weight:600;
    }

    .product-grid{
        display:grid;
        grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));
        gap:18px;
    }

    .product-card{
        background:var(--white);
        border-radius:20px;
        overflow:hidden;
        box-shadow:var(--shadow-sm);
        transition:transform .18s, box-shadow .18s;
        display:flex;
        flex-direction:column;
        cursor:pointer;
    }

    .product-card:hover{
        transform:translateY(-4px);
        box-shadow:var(--shadow-md);
    }

    .product-thumb{
        aspect-ratio:1/1;
        background:linear-gradient(150deg, var(--mint) 0%, var(--mint-deep) 100%);
        display:flex;
        align-items:center;
        justify-content:center;
        position:relative;
        padding:14px;
    }

    .product-thumb svg{width:46px; height:46px; color:var(--spring); opacity:.55;}

    .stock-flag{
        position:absolute;
        top:10px; left:10px;
        font-size:10px;
        font-weight:700;
        padding:4px 9px;
        border-radius:999px;
        background:var(--white);
        color:var(--spring-deep);
    }
    .stock-flag.low{color:var(--amber);}
    .stock-flag.out{color:var(--error);}

    .product-body{
        padding:14px 15px 16px;
        display:flex;
        flex-direction:column;
        gap:6px;
        flex:1;
    }

    .product-cat-tag{
        font-size:10.5px;
        font-weight:700;
        color:var(--cat-fg, var(--spring-deep));
        text-transform:uppercase;
        letter-spacing:.03em;
    }

    .product-name{
        font-family:'Outfit', sans-serif;
        font-size:14px;
        font-weight:600;
        color:var(--ink);
        line-height:1.3;
        min-height:36px;
    }

    .product-desc{
        font-size:11.5px;
        color:var(--muted);
        line-height:1.4;
    }

    .product-footer{
        margin-top:auto;
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding-top:8px;
    }

    .product-price{
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:14.5px;
        color:var(--ink);
    }
    .product-price small{
        display:block;
        font-family:'Inter', sans-serif;
        font-weight:500;
        font-size:9.5px;
        color:var(--muted);
    }

    .add-btn{
        width:34px; height:34px;
        border-radius:11px;
        border:none;
        background:var(--spring);
        color:var(--white);
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        transition:background .15s, transform .12s;
        flex-shrink:0;
    }
    .add-btn:hover{background:var(--spring-deep);}
    .add-btn:active{transform:scale(0.92);}
    .add-btn svg{width:16px; height:16px;}

    .add-btn.disabled{
        background:var(--mint-deep);
        color:var(--muted);
        cursor:not-allowed;
    }

    .empty-state{
        display:none;
        flex-direction:column;
        align-items:center;
        text-align:center;
        gap:10px;
        padding:60px 20px;
        color:var(--muted);
    }
    .empty-state svg{width:52px; height:52px; color:var(--mint-deep);}
    .empty-state h3{font-family:'Outfit', sans-serif; color:var(--ink); font-size:15px;}
    .empty-state p{font-size:12.5px; max-width:280px;}

    .product-card.locked .product-thumb{
        background:linear-gradient(150deg, #E8E4E6 0%, #D9D2D6 100%);
        filter:blur(0px);
    }

    .lock-overlay{
        display:flex;
        flex-direction:column;
        align-items:center;
        gap:6px;
        color:#5B4B57;
    }
    .lock-overlay svg{width:28px; height:28px; opacity:.7;}
    .lock-overlay span{
        font-size:10px;
        font-weight:700;
        text-align:center;
        padding:0 10px;
    }

    .product-card.locked .product-name,
    .product-card.locked .product-desc{color:var(--muted); font-style:italic;}

    .add-btn.locked-btn{
        background:#5B4B57;
        color:var(--white);
    }
    .add-btn.locked-btn:hover{background:#463a41;}

    .add-btn.resep-btn{
        background:#D64541;
        color:var(--white);
    }
    .add-btn.resep-btn:hover{background:#B93733;}

    .drug-badge{
        position:absolute;
        bottom:10px; left:10px;
        width:26px; height:26px;
        border-radius:50%;
        background:var(--white);
        padding:3px;
        box-shadow:0 2px 6px rgba(0,0,0,0.18);
        object-fit:contain;
    }

    .product-card[data-requires-resep="1"]{cursor:pointer;}

    .cat-pill.restricted .bump{background:#EFE9EC;}

    .age-modal-backdrop{
        display:none;
        position:fixed;
        inset:0;
        background:rgba(29,43,38,0.55);
        z-index:200;
        align-items:center;
        justify-content:center;
        padding:20px;
    }
    .age-modal-backdrop.open{display:flex;}

    .age-modal{
        background:var(--white);
        border-radius:24px;
        padding:32px 28px 26px;
        max-width:360px;
        width:100%;
        text-align:center;
        box-shadow:var(--shadow-md);
        animation:popIn .18s ease;
    }

    @keyframes popIn{
        from{opacity:0; transform:scale(.94);}
        to{opacity:1; transform:scale(1);}
    }

    .age-modal-icon{
        width:56px; height:56px;
        border-radius:50%;
        background:#F3EAEE;
        color:#5B4B57;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:0 auto 16px;
    }
    .age-modal-icon svg{width:26px; height:26px;}

    .age-modal h3{
        font-family:'Outfit', sans-serif;
        font-size:16.5px;
        font-weight:700;
        margin-bottom:8px;
        color:var(--ink);
    }
    .age-modal p{
        font-size:13px;
        color:var(--muted);
        line-height:1.5;
        margin-bottom:22px;
    }

    .age-modal-actions{
        display:flex;
        gap:10px;
    }

    .age-modal-btn{
        flex:1;
        padding:12px;
        border-radius:14px;
        border:none;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13px;
        cursor:pointer;
        transition:background .15s, transform .12s;
    }
    .age-modal-btn:active{transform:scale(.97);}
    .age-modal-btn.secondary{background:var(--mint); color:var(--ink);}
    .age-modal-btn.secondary:hover{background:var(--mint-deep);}
    .age-modal-btn.primary{background:var(--spring); color:var(--white);}
    .age-modal-btn.primary:hover{background:var(--spring-deep);}

    .promo-banner{
        max-width:1240px;
        margin:26px auto 0;
        padding:0 28px;
    }

    .promo-inner{
        background:linear-gradient(120deg, var(--spring) 0%, var(--spring-deep) 100%);
        border-radius:24px;
        padding:24px 28px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:20px;
        color:var(--white);
        flex-wrap:wrap;
    }

    .promo-text h3{
        font-family:'Outfit', sans-serif;
        font-size:17px;
        font-weight:700;
        margin-bottom:4px;
    }
    .promo-text p{
        font-size:12.5px;
        opacity:.9;
        max-width:420px;
    }

    .promo-btn{
        background:var(--white);
        color:var(--spring-deep);
        border:none;
        padding:12px 22px;
        border-radius:999px;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13px;
        cursor:pointer;
        display:flex;
        align-items:center;
        gap:8px;
        flex-shrink:0;
        transition:transform .12s;
    }
    .promo-btn:hover{transform:translateY(-2px);}
    .promo-btn svg{width:16px; height:16px;}

    @media (max-width:560px){
        .hero{padding:26px 18px 6px;}
        .cat-section{padding:22px 18px 4px;}
        .products-section{padding:24px 18px 50px;}
        .promo-banner{padding:0 18px;}
        .hero-greeting{font-size:20px;}
        .product-grid{grid-template-columns:repeat(auto-fill, minmax(150px,1fr)); gap:12px;}
    }
</style>
</head>
<body>

@php
    $userAge = $userAge ?? null;

    $drugClasses = [
        'keras'    => ['label' => 'Obat Keras',          'file' => 'obat_keras.png',           'requires_resep' => true],
        'terbatas' => ['label' => 'Obat Bebas Terbatas',  'file' => 'obat_bebas_terbatas.png',  'requires_resep' => false],
        'bebas'    => ['label' => 'Obat Bebas',           'file' => 'obat_bebas.png',            'requires_resep' => false],
    ];

    $products = $products ?? collect();

    $icons = [
    'pill' => '<path d="M4.93 4.93a6 6 0 0 1 8.49 0l5.66 5.66a6 6 0 1 1-8.49 8.49L4.93 13.4a6 6 0 0 1 0-8.49Z"/><path d="M9.5 9.5l5 5"/>',
    'leaf-drop' => '<path d="M12 2C8 6 6 10 6 13.5A6 6 0 0 0 18 13.5C18 10 16 6 12 2Z"/>',
    'layers' => '<rect x="7" y="7" width="10" height="13" rx="2"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/><path d="M9 12h6M9 15h6"/>',
    'baby' => '<path d="M9 3h6l-1 3H10L9 3Z"/><path d="M8 8h8l-1 11a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2L8 8Z"/><path d="M8 12h8"/>',
    'leaf' => '<path d="M4 20c8 0 16-6 16-16C10 4 4 12 4 20Z"/><path d="M4 20c3-5 6-8 12-11"/>',
    'pulse' => '<path d="M6 3v6a4 4 0 0 0 8 0V3"/><path d="M10 13v3a5 5 0 0 0 10 0v-2"/><circle cx="20" cy="12" r="2"/>',
    'eye' => '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/>',
    'sparkle' => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/><circle cx="12" cy="12" r="2.2"/>',
    'lock' => '<rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
    'droplet' => '<path d="M12 2c4 5 7 8.5 7 12a7 7 0 1 1-14 0c0-3.5 3-7 7-12Z"/>',
    'tooth' => '<path d="M12 3c-2 0-3 1-4 1s-2-1-3-1c-2 0-3 2-3 4.5 0 3 1 6 2 9 .5 1.5 1 2.5 2 2.5s1.5-2 2-4 1-3 2-3 1.5 1 2 3 1 4 2 4 1.5-1 2-2.5c1-3 2-6 2-9 0-2.5-1-4.5-3-4.5-1 0-2 1-3 1s-2-1-4-1Z"/>',
    'medkit' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/><path d="M12 11v6M9 14h6"/>',
    'mask' => '<path d="M3 10c0-2 2-3 4-3h10c2 0 4 1 4 3v3c0 3-4 5-9 5s-9-2-9-5v-3Z"/><path d="M3 11h18M3 13h18"/><path d="M3 10 1 8M21 10l2-2"/>',
    'flu' => '<path d="M12 14.76V4a2 2 0 0 0-4 0v10.76a4 4 0 1 0 4 0Z"/><path d="M12 8h-2"/>',
    'stomach' => '<path d="M12 21a8 8 0 1 1 0-16 6 6 0 1 1 0 12 4 4 0 1 1 0-8 2 2 0 1 1 0 4"/>',
    'hair' => '<path d="M4 21c0-6 2-9 2-13a6 6 0 0 1 12 0c0 4 2 7 2 13"/><path d="M8 21V10M12 21V8M16 21V10"/>',
];
@endphp

<x-customer-navbar />
<header class="hero">
    <div class="hero-top">
        <div class="hero-left">
            <img class="hero-bottle-deco" src="{{ asset('assets/images/hero-bottle.png') }}" alt="">
            <div class="hero-left-content">
                <h1 class="hero-greeting"><span class="wave">👋</span> Halo, Iwani!</h1>
                <p class="hero-sub">Mau cari obat atau kebutuhan kesehatan apa hari ini?</p>

                <form class="search-shell" onsubmit="return false;">
                    <svg class="search-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" id="searchInput" placeholder="Cari obat, vitamin, atau alat kesehatan...">
                    <button type="submit" class="search-submit">Cari</button>
                </form>
            </div>
        </div>

        <div class="hero-visual-frame">
            <div class="hero-visual" id="heroVisual">
                <button type="button" class="hero-promo-card no-caption" data-cat="kecantikan" aria-label="Lihat produk kecantikan dan perawatan kulit">
                    <div class="promo-img-wrap">
                        <img src="{{ asset('assets/images/promo-skin.png') }}" alt="Kulit Lebih Sehat - Serum Anti-Aging">
                    </div>
                    <span class="promo-badge" style="--badge-fg:#8C5A9E;">Skincare</span>
                    <span class="promo-cta">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10"/></svg>
                    </span>
                </button>
                <button type="button" class="hero-promo-card" data-cat="all" aria-label="Rawat kesehatan keluarga, lihat semua produk">
                    <div class="promo-img-wrap">
                        <img src="{{ asset('assets/images/promo-family.png') }}" alt="Kesehatan Keluarga">
                    </div>
                    <span class="promo-badge">Untuk Keluarga</span>
                    <div class="hero-promo-text">
                        <span>Mari Rawat Kesehatan</span>
                        <small>Kita dan Keluarga, Bersama</small>
                    </div>
                    <span class="promo-cta">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10"/></svg>
                    </span>
                </button>
                <button type="button" class="hero-promo-card" data-cat="bayi" aria-label="Lihat produk ibu dan bayi">
                    <div class="promo-img-wrap">
                        <img src="{{ asset('assets/images/promo-ibu-bayi.png') }}" alt="Fokus Perlindungan dan Kesehatan Ibu dan Bayi">
                    </div>
                    <span class="promo-badge" style="--badge-fg:#4E9BD9;">Fokus Perlindungan &amp; Kesehatan</span>
                    <div class="hero-promo-text">
                        <span>Memberikan Perlindungan Terbaik</span>
                        <small>Sejak Hari Pertama</small>
                    </div>
                    <span class="promo-cta">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10"/></svg>
                    </span>
                </button>
            </div>
            <div class="hero-visual-dots" id="heroDots">
                <span class="hero-dot active" data-index="0"><span class="hero-dot-fill"></span></span>
                <span class="hero-dot" data-index="1"><span class="hero-dot-fill"></span></span>
                <span class="hero-dot" data-index="2"><span class="hero-dot-fill"></span></span>
            </div>
        </div>
    </div>
</header>
<section class="cat-section">
    <div class="cat-heading">
        <h2>Kategori</h2>
        <span>Pilih untuk menyaring produk</span>
    </div>

    <div class="blister-strip" id="categoryStrip">
        <button class="cat-pill active" data-cat="all" style="--cat-bg:{{ 'var(--mint)' }}; --cat-fg: var(--spring-deep);">
            <span class="bump">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            </span>
            <span class="label">Semua</span>
        </button>

        @foreach ($categories as $cat)
            <button class="cat-pill {{ !empty($cat['restricted']) ? 'restricted' : '' }}"
                    data-cat="{{ $cat['slug'] }}"
                    data-restricted="{{ !empty($cat['restricted']) ? '1' : '0' }}"
                    data-min-age="{{ $cat['min_age'] ?? 0 }}"
                    style="--cat-fg:{{ $cat['color'] }};">
                <span class="bump">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $icons[$cat['icon']] !!}</svg>
                    @if(!empty($cat['restricted']))
                        <span class="age-badge">{{ $cat['min_age'] }}+</span>
                    @endif
                </span>
                <span class="label">{{ $cat['label'] }}</span>
            </button>
        @endforeach
    </div>
</section>

<section class="promo-banner">
    <div class="promo-inner">
        <div class="promo-text">
            <h3>Punya resep dokter?</h3>
            <p>Unggah foto resepmu, apoteker kami yang akan menyiapkan pesananmu dengan tepat.</p>
        </div>
        <a href="{{ route('resep.upload') }}" class="promo-btn">
            Upload Resep
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v14M6 9l6-6 6 6"/><path d="M4 21h16"/></svg>
        </a>
    </div>
</section>

<section class="products-section">
    <div class="products-heading">
        <h2 id="productsTitle">Semua Produk</h2>
        <span class="count-tag" id="productsCount">{{ count($products) }} produk</span>
    </div>

    <div class="product-grid" id="productGrid">
        @foreach ($products as $p)
            @php
                $catInfo = collect($categories)->firstWhere('slug', $p->category);
                $catColor = $catInfo['color'] ?? '#12A874';
                $catLabel = $catInfo['label'] ?? $p->category;
                $stockState = $p->stock == 0 ? 'out' : ($p->stock <= 5 ? 'low' : 'in');
                $minAge = $catInfo['min_age'] ?? null;
                $isLocked = $minAge !== null && (is_null($userAge) || $userAge < $minAge);
                $drugClassKey = $p->drug_class ?? null;
                $drugClassInfo = $drugClassKey ? ($drugClasses[$drugClassKey] ?? null) : null;
                $requiresResep = $drugClassInfo['requires_resep'] ?? false;
                $shortDesc = $p->description ? \Illuminate\Support\Str::limit(strip_tags($p->description), 70) : 'Lihat detail produk untuk info lengkap';
            @endphp
            <a href="{{ route('product.detail', $p->id) }}"
                 class="product-card {{ $isLocked ? 'locked' : '' }}"
                 data-cat="{{ $p->category }}"
                 data-name="{{ strtolower($p->name) }}"
                 data-locked="{{ $isLocked ? '1' : '0' }}"
                 data-min-age="{{ $minAge ?? 0 }}"
                 data-product-id="{{ $p->id }}"
                 data-requires-resep="{{ ($requiresResep && !$isLocked) ? '1' : '0' }}">
                <div class="product-thumb">
                    @if($isLocked)
                        <span class="stock-flag out">{{ $minAge }}+</span>
                    @elseif($stockState === 'out')
                        <span class="stock-flag out">Habis</span>
                    @elseif($stockState === 'low')
                        <span class="stock-flag low">Sisa {{ $p->stock }}</span>
                    @else
                        <span class="stock-flag">Tersedia</span>
                    @endif

                    @if($isLocked)
                        <div class="lock-overlay">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                            <span>Verifikasi usia {{ $minAge }}+</span>
                        </div>
                    @elseif($p->image)
                        <img src="{{ Storage::url($p->image) }}" alt="{{ $p->name }}" style="width:100%; height:100%; object-fit:contain; background:#fff; border-radius:12px;">
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.93 4.93a6 6 0 0 1 8.49 0l5.66 5.66a6 6 0 1 1-8.49 8.49L4.93 13.4a6 6 0 0 1 0-8.49Z"/><path d="M9.5 9.5l5 5"/></svg>
                    @endif

                    @if($drugClassInfo && !$isLocked)
                        <img class="drug-badge" src="{{ asset('assets/images/'.$drugClassInfo['file']) }}" alt="{{ $drugClassInfo['label'] }}" title="{{ $drugClassInfo['label'] }}">
                    @endif
                </div>
                <div class="product-body" style="--cat-fg:{{ $catColor }};">
                    <span class="product-cat-tag">{{ $catLabel }}</span>
                    <div class="product-name">{{ $isLocked ? 'Produk dibatasi usia' : $p->name }}</div>
                    <div class="product-desc">{{ $isLocked ? 'Verifikasi usia diperlukan untuk melihat produk ini.' : $shortDesc }}</div>
                    <div class="product-footer">
                        <div class="product-price">
                            {{ $isLocked ? '—' : 'Rp'.number_format($p->price, 0, ',', '.') }}
                            @if(!$isLocked)<small>/ item</small>@endif
                        </div>
                        @if($isLocked)
                            <button type="button" class="add-btn locked-btn" data-min-age="{{ $minAge }}" aria-label="Produk dibatasi usia">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                            </button>
                        @elseif($requiresResep)
                            <button type="button" class="add-btn resep-btn" aria-label="Perlu resep dokter" title="Perlu resep dokter">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                            </button>
                        @else
                            <button type="button" class="add-btn {{ $stockState === 'out' ? 'disabled' : '' }}" data-product-id="{{ $p->id }}" {{ $stockState === 'out' ? 'disabled' : '' }} aria-label="Tambah ke keranjang">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="empty-state" id="emptyState">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <h3>Produk tidak ditemukan</h3>
        <p>Coba kata kunci lain atau pilih kategori berbeda.</p>
    </div>
</section>

<div class="age-modal-backdrop" id="ageModal">
    <div class="age-modal">
        <div class="age-modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
        </div>
        <h3 id="ageModalTitle">Khusus usia 21 tahun ke atas</h3>
        <p id="ageModalText">Kategori ini berisi produk yang hanya boleh dibeli oleh pelanggan berusia 21 tahun ke atas. Lengkapi tanggal lahir di profil kamu untuk melanjutkan.</p>
        <div class="age-modal-actions">
            <button class="age-modal-btn secondary" id="ageModalClose">Tutup</button>
            <button class="age-modal-btn primary" id="ageModalProfile">Lengkapi Profil</button>
        </div>
    </div>
</div>

<div class="age-modal-backdrop" id="resepModal">
    <div class="age-modal">
        <div class="age-modal-icon" style="background:#FBEAEA; color:#D64541;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6M9 16h6M9 8h2"/><rect x="5" y="3" width="14" height="18" rx="2"/></svg>
        </div>
        <h3>Perlu Resep Dokter</h3>
        <p>Produk ini termasuk golongan Obat Keras dan hanya bisa dipesan dengan resep dokter yang valid. Unggah resep kamu terlebih dahulu sebelum melanjutkan ke pesanan.</p>
        <div class="age-modal-actions">
            <button class="age-modal-btn secondary" id="resepModalClose">Batal</button>
            <button class="age-modal-btn primary" id="resepModalConfirm" style="background:#D64541;" data-pesanan-url="{{ route('resep.upload') }}">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script>
(function(){
    const userAge = {{ $userAge !== null ? (int) $userAge : 'null' }};

    const catButtons = document.querySelectorAll('.cat-pill');
    const cards = document.querySelectorAll('.product-card');
    const searchInput = document.getElementById('searchInput');
    const productsTitle = document.getElementById('productsTitle');
    const productsCount = document.getElementById('productsCount');
    const emptyState = document.getElementById('emptyState');
    const grid = document.getElementById('productGrid');

    const ageModal = document.getElementById('ageModal');
    const ageModalTitle = document.getElementById('ageModalTitle');
    const ageModalText = document.getElementById('ageModalText');
    const ageModalClose = document.getElementById('ageModalClose');
    const ageModalProfile = document.getElementById('ageModalProfile');

    let activeCat = 'all';

    function isAgeVerified(minAge){
        return userAge !== null && userAge >= minAge;
    }

    function openAgeModal(minAge){
        if(userAge === null){
            ageModalTitle.textContent = 'Verifikasi usia diperlukan';
            ageModalText.textContent = 'Kategori ini khusus pelanggan berusia ' + minAge + ' tahun ke atas. Lengkapi tanggal lahir di profil kamu untuk membuka akses.';
        } else {
            ageModalTitle.textContent = 'Khusus usia ' + minAge + ' tahun ke atas';
            ageModalText.textContent = 'Maaf, produk pada kategori ini hanya untuk pelanggan berusia ' + minAge + ' tahun ke atas.';
        }
        ageModal.classList.add('open');
    }

    function closeAgeModal(){
        ageModal.classList.remove('open');
    }

    ageModalClose.addEventListener('click', closeAgeModal);
    ageModal.addEventListener('click', (e) => { if(e.target === ageModal) closeAgeModal(); });
    ageModalProfile.addEventListener('click', () => {
        closeAgeModal();
        window.location.href = "{{ route('dashboard') }}#profil";
    });

    function applyFilter(){
        const q = searchInput.value.trim().toLowerCase();
        let visible = 0;

        cards.forEach(card => {
            const matchesCat = activeCat === 'all' || card.dataset.cat === activeCat;
            const matchesSearch = !q || card.dataset.name.includes(q);
            const show = matchesCat && matchesSearch;
            card.style.display = show ? '' : 'none';
            if(show) visible++;
        });

        productsCount.textContent = visible + ' produk';
        grid.style.display = visible === 0 ? 'none' : 'grid';
        emptyState.style.display = visible === 0 ? 'flex' : 'none';
    }

    catButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const isRestricted = btn.dataset.restricted === '1';
            const minAge = parseInt(btn.dataset.minAge || '0', 10);

            if(isRestricted && !isAgeVerified(minAge)){
                openAgeModal(minAge);
                return;
            }

            catButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeCat = btn.dataset.cat;
            productsTitle.textContent = activeCat === 'all' ? 'Semua Produk' : btn.querySelector('.label').textContent;
            applyFilter();
        });
    });

    document.querySelectorAll('.product-card.locked').forEach(card => {
        card.addEventListener('click', (e) => {
            e.preventDefault();
            const minAge = parseInt(card.dataset.minAge || '21', 10);
            openAgeModal(minAge);
        });
    });

    document.querySelectorAll('.locked-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const minAge = parseInt(btn.dataset.minAge || '21', 10);
            openAgeModal(minAge);
        });
    });

    const resepModal = document.getElementById('resepModal');
    const resepModalClose = document.getElementById('resepModalClose');
    const resepModalConfirm = document.getElementById('resepModalConfirm');
    let pendingResepProductId = null;

    function openResepModal(){ resepModal.classList.add('open'); }
    function closeResepModal(){ resepModal.classList.remove('open'); }

    resepModalClose.addEventListener('click', closeResepModal);
    resepModal.addEventListener('click', (e) => { if(e.target === resepModal) closeResepModal(); });

    resepModalConfirm.addEventListener('click', () => {
        const productId = pendingResepProductId;
        closeResepModal();

        if (!productId) {
            window.location.href = "{{ route('cart.index') }}";
            return;
        }

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/keranjang/tambah/${productId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
        })
        .then(async (res) => {
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Gagal menambahkan ke keranjang.');
            return data;
        })
        .then(() => {
            window.location.href = "{{ route('cart.index') }}";
        })
        .catch((err) => {
            alert(err.message);
        });
    });

    document.querySelectorAll('[data-requires-resep="1"]').forEach(card => {
        card.addEventListener('click', (e) => {
            e.preventDefault();
            pendingResepProductId = card.dataset.productId || null;
            openResepModal();
        });
    });

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const navCartCount = document.getElementById('navCartCount');
    const checkIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';

    function addToCart(btn){
        if(btn.disabled) return;
        const productId = btn.dataset.productId;
        if(!productId) return;

        btn.disabled = true;
        const originalIcon = btn.innerHTML;

        fetch(`/keranjang/tambah/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        })
        .then(async (res) => {
            const data = await res.json();
            if(!res.ok) throw new Error(data.message || 'Gagal menambahkan ke keranjang.');
            return data;
        })
        .then((data) => {
            if(navCartCount) navCartCount.textContent = data.cart_count;
            btn.innerHTML = checkIcon;
            setTimeout(() => {
                btn.innerHTML = originalIcon;
                btn.disabled = false;
            }, 900);
        })
        .catch((err) => {
            alert(err.message);
            btn.disabled = false;
        });
    }

    document.querySelectorAll('.add-btn:not(.locked-btn):not(.resep-btn)').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            addToCart(btn);
        });
    });

    searchInput.addEventListener('input', applyFilter);

    document.querySelectorAll('.hero-promo-card[data-cat]').forEach(card => {
        card.addEventListener('click', () => {
            const cat = card.dataset.cat;
            if(cat && cat !== 'all'){
                const targetBtn = document.querySelector(`.cat-pill[data-cat="${cat}"]`);
                if(targetBtn) targetBtn.click();
            }
            const productsSection = document.querySelector('.products-section');
            if(productsSection) productsSection.scrollIntoView({behavior:'smooth', block:'start'});
        });
    });

    const heroVisual = document.getElementById('heroVisual');
    const heroDots = document.querySelectorAll('.hero-dot');

    if(heroVisual && heroDots.length){
        const slideCount = heroVisual.children.length;
        let heroIndex = 0;
        let heroAutoTimer = null;
        let heroResumeTimer = null;
        let isHeroDragging = false;

        function setActiveDot(idx){
            heroDots.forEach((d, i) => {
                d.classList.toggle('active', i === idx);
                d.classList.toggle('visited', i < idx);
            });
        }

        function goToHeroSlide(idx, smooth = true){
            const card = heroVisual.children[idx];
            if(!card) return;
            heroVisual.scrollTo({left: card.offsetLeft, behavior: smooth ? 'smooth' : 'auto'});
            heroIndex = idx;
            setActiveDot(idx);
        }

        function startHeroAutoplay(){
            clearInterval(heroAutoTimer);
            heroAutoTimer = setInterval(() => {
                heroIndex = (heroIndex + 1) % slideCount;
                goToHeroSlide(heroIndex);
            }, 4200);
        }

        function pauseHeroAutoplay(){
            clearInterval(heroAutoTimer);
            clearTimeout(heroResumeTimer);
            heroResumeTimer = setTimeout(startHeroAutoplay, 5500);
        }

        heroDots.forEach(dot => {
            dot.addEventListener('click', () => {
                goToHeroSlide(parseInt(dot.dataset.index, 10));
                pauseHeroAutoplay();
            });
        });

        heroVisual.addEventListener('pointerdown', () => { isHeroDragging = true; pauseHeroAutoplay(); });
        heroVisual.addEventListener('pointerup', () => { isHeroDragging = false; });
        heroVisual.addEventListener('touchstart', () => pauseHeroAutoplay(), {passive:true});
        heroVisual.addEventListener('mouseenter', () => clearInterval(heroAutoTimer));
        heroVisual.addEventListener('mouseleave', () => { if(!isHeroDragging) startHeroAutoplay(); });

        let heroScrollTimeout;
        heroVisual.addEventListener('scroll', () => {
            clearTimeout(heroScrollTimeout);
            heroScrollTimeout = setTimeout(() => {
                const firstCard = heroVisual.children[0];
                if(!firstCard) return;
                const cardWidth = firstCard.getBoundingClientRect().width;
                const idx = Math.round(heroVisual.scrollLeft / cardWidth);
                heroIndex = Math.min(Math.max(idx, 0), slideCount - 1);
                setActiveDot(heroIndex);
            }, 100);
        });

        startHeroAutoplay();
    }
})();
</script>

</body>
</html>