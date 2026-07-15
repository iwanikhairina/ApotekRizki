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

    /* ===== NAVBAR ===== */
    .navbar{
        position:sticky;
        top:0;
        z-index:50;
        background:var(--white);
        border-bottom:1px solid var(--mint-deep);
    }

    .navbar-inner{
        max-width:1240px;
        margin:0 auto;
        padding:14px 28px;
        display:flex;
        align-items:center;
        gap:28px;
    }

    .nav-brand{
        display:flex;
        align-items:center;
        gap:10px;
        flex-shrink:0;
    }

    .nav-brand .logo-box{
        width:42px; height:42px;
        border-radius:12px;
        background:var(--mint);
        display:flex;
        align-items:center;
        justify-content:center;
        padding:6px;
        flex-shrink:0;
    }

    .nav-brand .logo-box img{width:100%; height:100%; object-fit:contain;}

    .nav-brand .brand-text{
        font-family:'Outfit', sans-serif;
        font-weight:800;
        font-size:17px;
        line-height:1.15;
        color:var(--ink);
    }

    .nav-brand .brand-text span{
        display:block;
        font-family:'Inter', sans-serif;
        font-weight:500;
        font-size:10.5px;
        color:var(--muted);
        letter-spacing:.02em;
    }

    .nav-links{
        display:flex;
        align-items:center;
        gap:4px;
        flex:1;
    }

    .nav-links a{
        font-size:13.5px;
        font-weight:600;
        color:var(--muted);
        padding:9px 15px;
        border-radius:999px;
        transition:background .15s, color .15s;
        white-space:nowrap;
    }

    .nav-links a:hover{background:var(--mint); color:var(--ink);}
    .nav-links a.active{background:var(--spring); color:var(--white);}

    .nav-actions{
        display:flex;
        align-items:center;
        gap:10px;
        flex-shrink:0;
    }

    .icon-btn{
        width:40px; height:40px;
        border-radius:12px;
        border:none;
        background:var(--mint);
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        color:var(--spring-deep);
        position:relative;
        transition:background .15s;
    }
    .icon-btn:hover{background:var(--mint-deep);}
    .icon-btn svg{width:19px; height:19px;}

    .cart-count{
        position:absolute;
        top:-4px; right:-4px;
        background:var(--error);
        color:var(--white);
        font-size:10px;
        font-weight:700;
        width:17px; height:17px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        border:2px solid var(--white);
    }

    .avatar-btn{
    width:40px; height:40px;
    border-radius:50%;
    background:var(--spring);
    color:var(--white);
    border:none;
    font-family:'Outfit', sans-serif;
    font-weight:700;
    font-size:14px;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    }

    .nav-toggle{
        display:none;
        width:40px; height:40px;
        border-radius:12px;
        border:none;
        background:var(--mint);
        align-items:center;
        justify-content:center;
        cursor:pointer;
    }
    .nav-toggle svg{width:20px; height:20px; color:var(--ink);}

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
    }

    .hero-bottle-deco{
        position:absolute;
        left:-64px;
        top:50%;
        width:52px;
        height:auto;
        object-fit:contain;
        filter:drop-shadow(0 10px 14px rgba(29,43,38,0.16));
        display:none;
        animation:heroBottleFloat 3.6s ease-in-out infinite;
        transform-origin:center;
    }

    @keyframes heroBottleFloat{
        0%,100%{transform:translateY(-50%) rotate(-8deg);}
        50%{transform:translateY(-58%) rotate(-3deg);}
    }

    @media (min-width:1080px){
        .hero-bottle-deco{display:block;}
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

    /* ===== HERO VISUAL (dua kartu promo, kanan) ===== */
    .hero-visual-frame{
        flex:1 1 420px;
        background:var(--mint-deep);
        border-radius:26px;
        padding:14px;
        box-shadow:var(--shadow-sm);
    }

    .hero-visual{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:14px;
        height:100%;
    }

    .hero-promo-card{
        position:relative;
        border-radius:18px;
        overflow:hidden;
        min-height:240px;
        background:var(--white);
        cursor:pointer;
        border:none;
        padding:0;
        text-align:left;
        display:block;
        width:100%;
        font-family:inherit;
        animation:heroCardIn .55s ease both;
        transition:transform .28s cubic-bezier(.2,.8,.2,1), box-shadow .28s ease;
    }
    .hero-promo-card:nth-child(2){animation-delay:.09s;}

    @keyframes heroCardIn{
        from{opacity:0; transform:translateY(16px) scale(.98);}
        to{opacity:1; transform:translateY(0) scale(1);}
    }

    .hero-promo-card:hover,
    .hero-promo-card:focus-visible{
        transform:translateY(-5px);
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

    .hero-promo-card:hover img{transform:scale(1.09);}

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
        width:32px; height:32px;
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
        left:14px; right:44px; bottom:14px;
        z-index:2;
        color:var(--white);
    }

    .hero-promo-text span{
        display:block;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13.5px;
        line-height:1.25;
    }

    .hero-promo-text small{
        display:block;
        font-size:11px;
        font-weight:500;
        opacity:.9;
        margin-top:2px;
    }

    /* dot indicators, hanya tampil saat mode carousel di layar kecil */
    .hero-visual-dots{
        display:none;
        justify-content:center;
        gap:6px;
        margin-top:10px;
    }

    .hero-dot{
        width:6px; height:6px;
        border-radius:999px;
        background:var(--white);
        opacity:.6;
        cursor:pointer;
        transition:width .25s ease, opacity .25s ease, background .25s ease;
    }
    .hero-dot.active{
        width:18px;
        opacity:1;
        background:var(--spring-deep);
    }

    @media (max-width:860px){
        .hero-top{flex-direction:column;}
    }

    @media (max-width:640px){
        .hero-visual{
            display:flex;
            grid-template-columns:unset;
            overflow-x:auto;
            scroll-snap-type:x mandatory;
            -webkit-overflow-scrolling:touch;
            gap:12px;
            scrollbar-width:none;
        }
        .hero-visual::-webkit-scrollbar{display:none;}
        .hero-promo-card{
            flex:0 0 84%;
            scroll-snap-align:center;
            min-height:190px;
        }
        .hero-visual-frame{padding:10px 10px 6px;}
        .hero-visual-dots{display:flex;}
    }

    /* ===== CATEGORY BLISTER STRIP ===== */
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

    /* ===== PRODUCTS ===== */
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
        aspect-ratio:1/0.85;
        background:linear-gradient(150deg, var(--mint) 0%, var(--mint-deep) 100%);
        display:flex;
        align-items:center;
        justify-content:center;
        position:relative;
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

    /* ===== AGE-RESTRICTED PRODUCT LOCK ===== */
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

    /* ===== AGE VERIFICATION MODAL ===== */
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

    /* ===== PROMO STRIP (upload resep) ===== */
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

    /* ===== RESPONSIVE ===== */
    @media (max-width:920px){
        .nav-links{
            position:fixed;
            top:70px; left:0; right:0;
            background:var(--white);
            flex-direction:column;
            align-items:stretch;
            padding:10px 16px 16px;
            gap:2px;
            border-bottom:1px solid var(--mint-deep);
            box-shadow:var(--shadow-sm);
            display:none;
        }
        .nav-links.open{display:flex;}
        .nav-links a{padding:12px 14px;}
        .nav-toggle{display:flex;}
    }

    @media (max-width:560px){
        .navbar-inner{padding:12px 18px;}
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
    /*
        DATA DUMMY — hapus blok ini setelah Controller mengirim data asli.
        Struktur field mengikuti kolom yang disarankan untuk tabel products:
        id, name, category (slug), description, price, stock, image_url.
        Controller nantinya cukup: return view('customer.dashboard', compact('products', 'categories'));
    */
    $categories = $categories ?? [
        ['slug' => 'obat',       'label' => 'Obat',            'color' => '#12A874', 'icon' => 'pill'],
        ['slug' => 'nutrisi',    'label' => 'Nutrisi',         'color' => '#E8A33D', 'icon' => 'leaf-drop'],
        ['slug' => 'suplemen',   'label' => 'Suplemen',        'color' => '#8C7AE6', 'icon' => 'layers'],
        ['slug' => 'bayi',       'label' => 'Produk Bayi',     'color' => '#4E9BD9', 'icon' => 'baby'],
        ['slug' => 'herbal',     'label' => 'Herbal',          'color' => '#6FA83C', 'icon' => 'leaf'],
        ['slug' => 'alkes',      'label' => 'Alat Kesehatan',  'color' => '#E0715B', 'icon' => 'pulse'],
        ['slug' => 'mata',       'label' => 'Mata',            'color' => '#2FA5A0', 'icon' => 'eye'],
        ['slug' => 'kecantikan', 'label' => 'Kecantikan',      'color' => '#D9679C', 'icon' => 'sparkle'],
        ['slug' => 'dewasa',     'label' => 'Produk Dewasa',   'color' => '#5B4B57', 'icon' => 'lock', 'restricted' => true, 'min_age' => 21],
    ];

    // Umur pelanggan dihitung Controller dari kolom birth_date (lihat CustomerDashboardController).
    // null artinya umur belum bisa dipastikan -> dianggap belum terverifikasi (aman secara default).
    $userAge = $userAge ?? null;

    // Legenda logo golongan obat resmi (merah = Obat Keras, biru = Obat Bebas Terbatas, hijau = Obat Bebas).
    // File logo ditaruh di public/assets/images/. obat_keras.png tetap pakai foto asli/asset resmi kamu.
    // obat_bebas.svg (hijau) dan obat_bebas_terbatas.svg (biru) sudah dibuatkan — tinggal taruh di folder yang sama.
    $drugClasses = [
        'keras'    => ['label' => 'Obat Keras',          'file' => 'obat_keras.png',            'requires_resep' => true],
        'terbatas' => ['label' => 'Obat Bebas Terbatas',  'file' => 'obat_bebas_terbatas.svg',   'requires_resep' => false],
        'bebas'    => ['label' => 'Obat Bebas',           'file' => 'obat_bebas.svg',             'requires_resep' => false],
    ];

    // $products WAJIB dikirim dari Controller (Product::all() dari database).
    // Tidak ada lagi data dummy di sini — kalau $products kosong berarti tabel products
    // di database kamu belum diisi (jalankan: php artisan db:seed --class=ProductSeeder).
    $products = $products ?? collect();

    $icons = [
        'pill' => '<path d="M4.93 4.93a6 6 0 0 1 8.49 0l5.66 5.66a6 6 0 1 1-8.49 8.49L4.93 13.4a6 6 0 0 1 0-8.49Z"/><path d="M9.5 9.5l5 5"/>',
        'leaf-drop' => '<path d="M12 2C8 6 6 10 6 13.5A6 6 0 0 0 18 13.5C18 10 16 6 12 2Z"/>',
        'layers' => '<path d="M12 3 2 8l10 5 10-5-10-5Z"/><path d="M2 13l10 5 10-5"/><path d="M2 18l10 5 10-5"/>',
        'baby' => '<circle cx="12" cy="7" r="4"/><path d="M6 21c0-4.5 2.7-7 6-7s6 2.5 6 7"/><path d="M9 7c.5 1 1.5 1.5 3 1.5S14.5 8 15 7"/>',
        'leaf' => '<path d="M4 20c8 0 16-6 16-16C10 4 4 12 4 20Z"/><path d="M4 20c3-5 6-8 12-11"/>',
        'pulse' => '<path d="M3 12h4l2-7 4 14 2-7h6"/>',
        'eye' => '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/>',
        'sparkle' => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/><circle cx="12" cy="12" r="2.2"/>',
        'lock' => '<rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
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

<header class="hero">
    <div class="hero-top">
        <div class="hero-left">
            <img class="hero-bottle-deco" src="{{ asset('assets/images/hero-bottle.png') }}" alt="">
            <h1 class="hero-greeting"><span class="wave">👋</span> Halo, Iwani!</h1>
            <p class="hero-sub">Mau cari obat atau kebutuhan kesehatan apa hari ini?</p>

            <form class="search-shell" onsubmit="return false;">
                <svg class="search-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" id="searchInput" placeholder="Cari obat, vitamin, atau alat kesehatan...">
                <button type="submit" class="search-submit">Cari</button>
            </form>
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
            </div>
            <div class="hero-visual-dots" id="heroDots">
                <span class="hero-dot active" data-index="0"></span>
                <span class="hero-dot" data-index="1"></span>
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
        <img src="{{ Storage::url($p->image) }}" alt="{{ $p->name }}" style="width:100%; height:100%; object-fit:cover;">
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
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6M9 16h6M9 8h2"/><rect x="5" y="3" width="14" height="18" rx="2"/></svg>
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
    // Umur pelanggan dikirim dari Controller (dihitung dari birth_date). null = belum terverifikasi.
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
        window.location.href = "{{ route('dashboard') }}#profil"; // arahkan ke halaman lengkapi profil kalau sudah ada
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
                return; // batal filter, kategori tetap tidak dibuka
            }

            catButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeCat = btn.dataset.cat;
            productsTitle.textContent = activeCat === 'all' ? 'Semua Produk' : btn.querySelector('.label').textContent;
            applyFilter();
        });
    });

    // Kartu produk yang dibatasi usia: klik dimana pun pada kartu membuka modal verifikasi,
    // dan TIDAK mengarahkan ke halaman detail produk.
    document.querySelectorAll('.product-card.locked').forEach(card => {
        card.addEventListener('click', (e) => {
            e.preventDefault();
            const minAge = parseInt(card.dataset.minAge || '21', 10);
            openAgeModal(minAge);
        });
    });

    // Tombol kunci usia di dalam kartu (safety-net kalau ada event lain)
    document.querySelectorAll('.locked-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const minAge = parseInt(btn.dataset.minAge || '21', 10);
            openAgeModal(minAge);
        });
    });

    // ===== Notifikasi "perlu resep dokter" untuk produk golongan Obat Keras =====
    // Klik kartu Obat Keras membuka modal resep dulu, bukan langsung ke halaman detail.
    const resepModal = document.getElementById('resepModal');
    const resepModalClose = document.getElementById('resepModalClose');
    const resepModalConfirm = document.getElementById('resepModalConfirm');

    function openResepModal(){ resepModal.classList.add('open'); }
    function closeResepModal(){ resepModal.classList.remove('open'); }

    resepModalClose.addEventListener('click', closeResepModal);
    resepModal.addEventListener('click', (e) => { if(e.target === resepModal) closeResepModal(); });
    resepModalConfirm.addEventListener('click', () => {
        window.location.href = resepModalConfirm.dataset.pesananUrl || '#';
    });

    document.querySelectorAll('[data-requires-resep="1"]').forEach(card => {
        card.addEventListener('click', (e) => {
            e.preventDefault();
            openResepModal();
        });
    });

    // Tombol "tambah ke keranjang" tidak boleh ikut membuka halaman detail produk.
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

    // ===== Kartu promo hero: klik untuk filter kategori & scroll ke produk =====
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

    // ===== Carousel promo hero di layar kecil: sinkronisasi dot indikator =====
    const heroVisual = document.getElementById('heroVisual');
    const heroDots = document.querySelectorAll('.hero-dot');
    if(heroVisual && heroDots.length){
        heroDots.forEach(dot => {
            dot.addEventListener('click', () => {
                const idx = parseInt(dot.dataset.index, 10);
                const target = heroVisual.children[idx];
                if(target) target.scrollIntoView({behavior:'smooth', inline:'center', block:'nearest'});
            });
        });

        let heroScrollTimeout;
        heroVisual.addEventListener('scroll', () => {
            clearTimeout(heroScrollTimeout);
            heroScrollTimeout = setTimeout(() => {
                const firstCard = heroVisual.children[0];
                if(!firstCard) return;
                const cardWidth = firstCard.getBoundingClientRect().width + 12;
                const idx = Math.round(heroVisual.scrollLeft / cardWidth);
                heroDots.forEach((d, i) => d.classList.toggle('active', i === idx));
            }, 80);
        });
    }
})();
</script>

</body>
</html>