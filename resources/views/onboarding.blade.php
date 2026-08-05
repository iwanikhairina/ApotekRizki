<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Selamat Datang - Apotek Rizki</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root{
        --spring:#12A874;
        --spring-deep:#0C7E57;
        --spring-dark:#08432D;
        --amber:#E8A33D;
        --ink:#0F231B;
        --white:#FFFFFF;
        --glass-bg:rgba(255,255,255,.14);
        --glass-border:rgba(255,255,255,.32);
    }

    *{margin:0;padding:0;box-sizing:border-box;}

    html,body{
        height:100%;
        overflow:hidden;
        font-family:'Inter', sans-serif;
        background:var(--spring-dark);
    }

    button{font-family:inherit; border:none; background:none; cursor:pointer;}
    a{text-decoration:none; color:inherit;}

    /* ============ APP SHELL ============ */
    .onboard{
        position:relative;
        width:100%;
        height:100dvh;
        overflow:hidden;
    }

    /* ============ SLIDES ============ */
    .slide{
        position:absolute;
        inset:0;
        opacity:0;
        visibility:hidden;
        pointer-events:none;
        transition:opacity .65s cubic-bezier(.4,0,.2,1);
    }
    .slide.active{
        opacity:1;
        visibility:visible;
        pointer-events:auto;
        z-index:2;
    }

    .slide-bg{
        position:absolute;
        inset:0;
        overflow:hidden;
        background:var(--spring-dark);
    }
    /* Lapisan belakang: sama fotonya, di-cover penuh + blur berat, jadi "wash"
       warna latar — bukan buat dilihat detail, cuma biar gak ada area kosong
       polos di kiri-kanan/atas-bawah foto potret di layar lebar. */
    .slide-bg .bg-blur{
        position:absolute;
        inset:-10%;
        background-size:cover;
        background-position:center 30%;
        filter:blur(46px) saturate(1.2) brightness(.78);
        transform:scale(1.1);
        animation-duration:10s;
        animation-timing-function:ease-out;
        animation-fill-mode:forwards;
    }
    .slide.active .slide-bg .bg-blur{ animation-name:kenburnsBlur; }
    @keyframes kenburnsBlur{
        from{ transform:scale(1.1); }
        to{ transform:scale(1.24); }
    }
    /* Lapisan depan: foto ASLI, utuh, tidak dipotong/di-crop paksa —
       object-fit:contain supaya seluruh objek utama tetap terlihat. */
    .slide-bg .bg-fg{
        position:absolute;
        inset:0;
        width:100%;
        height:100%;
        object-fit:contain;
        object-position:center 50%;
        filter:saturate(1.05);
        animation-duration:10s;
        animation-timing-function:ease-out;
        animation-fill-mode:forwards;
    }
    .slide.active .slide-bg .bg-fg{ animation-name:kenburnsFg; }
    @keyframes kenburnsFg{
        from{ transform:scale(1); }
        to{ transform:scale(1.05); }
    }

    /* Gradient wash: hijau + putih, kontras teks tetap terjaga */
    .slide-overlay{
        position:absolute;
        inset:0;
        background:
            linear-gradient(180deg,
                rgba(8,67,45,.72) 0%,
                rgba(18,168,116,.38) 24%,
                rgba(234,247,240,.16) 46%,
                rgba(10,58,39,.30) 68%,
                rgba(6,38,26,.86) 100%);
    }
    /* Vignette lembut hanya menguatkan area di belakang kartu teks (bawah) */
    .slide-vignette{
        position:absolute;
        inset:0;
        background:radial-gradient(ellipse 120% 60% at 50% 100%, rgba(4,28,19,.65) 0%, transparent 62%);
    }

    /* ============ TOP BAR ============ */
    .topbar{
        position:absolute;
        top:0; left:0; right:0;
        z-index:10;
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:22px 20px 0;
    }
    .brand-chip{
        display:flex;
        align-items:center;
        gap:8px;
        background:var(--glass-bg);
        border:1px solid var(--glass-border);
        backdrop-filter:blur(14px);
        -webkit-backdrop-filter:blur(14px);
        padding:8px 14px 8px 8px;
        border-radius:999px;
        opacity:0;
        animation:fadeDown .6s ease forwards;
        animation-delay:.15s;
    }
    .brand-chip .logo-dot{
        width:26px; height:26px;
        border-radius:50%;
        background:var(--white);
        display:flex; align-items:center; justify-content:center;
        color:var(--spring-deep);
        flex-shrink:0;
    }
    .brand-chip .logo-dot svg{width:15px; height:15px;}
    .brand-chip span{
        color:var(--white);
        font-family:'Poppins', sans-serif;
        font-weight:600;
        font-size:12.5px;
        letter-spacing:.01em;
    }

    .skip-btn{
        display:flex; align-items:center; gap:6px;
        background:var(--glass-bg);
        border:1px solid var(--glass-border);
        backdrop-filter:blur(14px);
        -webkit-backdrop-filter:blur(14px);
        color:var(--white);
        font-family:'Poppins', sans-serif;
        font-weight:600;
        font-size:12.5px;
        padding:10px 16px;
        border-radius:999px;
        transition:transform .15s ease, background .15s ease;
        opacity:0;
        animation:fadeDown .6s ease forwards;
        animation-delay:.15s;
    }
    .skip-btn:hover{ background:rgba(255,255,255,.24); }
    .skip-btn:active{ transform:scale(.94); }
    .skip-btn svg{width:13px; height:13px;}
    .skip-btn.is-hidden{ opacity:0 !important; pointer-events:none; }

    /* ============ DECORATIVE FLOATING ICONS ============ */
    .float-ic{
        position:absolute;
        display:flex; align-items:center; justify-content:center;
        border-radius:16px;
        background:var(--glass-bg);
        border:1px solid var(--glass-border);
        backdrop-filter:blur(10px);
        -webkit-backdrop-filter:blur(10px);
        color:var(--white);
        box-shadow:0 8px 24px -8px rgba(0,0,0,.35);
        z-index:3;
        opacity:0;
    }
    .slide.active .float-ic{ animation:popIn .55s cubic-bezier(.34,1.56,.64,1) forwards, floaty 3.6s ease-in-out infinite; }
    .float-ic svg{ width:50%; height:50%; }

    @keyframes popIn{
        0%{ opacity:0; transform:scale(.4) translateY(10px); }
        100%{ opacity:1; transform:scale(1) translateY(0); }
    }
    @keyframes floaty{
        0%,100%{ transform:translateY(0); }
        50%{ transform:translateY(-12px); }
    }

    /* Slide 1 icons */
    #s1 .ic-cross{ width:52px; height:52px; top:16%; left:8%; animation-delay:.25s, .25s; }
    #s1 .ic-capsule{ width:46px; height:46px; top:26%; right:10%; animation-delay:.4s, .8s; }
    #s1 .ic-phone{ width:56px; height:56px; top:44%; left:6%; animation-delay:.55s, .5s; }

    /* Slide 2 icons */
    #s2 .ic-doc{ width:52px; height:52px; top:15%; right:9%; animation-delay:.25s, .3s; }
    #s2 .ic-pill{ width:44px; height:44px; top:47%; left:7%; animation-delay:.45s, .9s; }
    #s2 .ic-check{
        width:46px; height:46px; top:30%; left:10%;
        background:var(--spring); border-color:rgba(255,255,255,.5);
        animation-delay:.6s, .4s;
    }
    #s2 .ic-check svg{ animation:checkPop .5s ease .95s both; }
    @keyframes checkPop{
        0%{ transform:scale(0); opacity:0; }
        60%{ transform:scale(1.25); opacity:1; }
        100%{ transform:scale(1); }
    }
    #s2 .ic-spinner{
        width:34px; height:34px; top:22%; left:26%;
        background:rgba(255,255,255,.9); border:none;
    }
    #s2 .ic-spinner svg{ animation:spin .9s linear infinite; color:var(--spring-deep); }
    @keyframes spin{ to{ transform:rotate(360deg); } }

    /* Slide 3 icons */
    #s3 .ic-map{ width:58px; height:58px; top:16%; right:8%; animation-delay:.25s, .2s; }
    #s3 .ic-bell{ width:44px; height:44px; top:40%; right:6%; animation-delay:.7s, 0s; }
    .slide.active #s3 .ic-bell{ animation:popIn .5s cubic-bezier(.34,1.56,.64,1) forwards, bellBounce 2.1s ease-in-out .9s infinite; }
    @keyframes bellBounce{
        0%,100%{ transform:translateY(0) rotate(0deg); }
        10%{ transform:translateY(-3px) rotate(-8deg); }
        20%{ transform:translateY(0) rotate(8deg); }
        30%{ transform:translateY(-2px) rotate(-5deg); }
        40%,100%{ transform:translateY(0) rotate(0deg); }
    }
    .pulse-ring{
        position:absolute;
        top:33%; left:9%;
        width:16px; height:16px;
        border-radius:50%;
        background:var(--amber);
        z-index:3;
        opacity:0;
    }
    .slide.active .pulse-ring{ animation:pinShow .4s ease .3s forwards; }
    .pulse-ring::before{
        content:'';
        position:absolute; inset:-4px;
        border-radius:50%;
        border:2px solid var(--amber);
        opacity:0;
    }
    .slide.active .pulse-ring::before{ animation:pulseRing 2s ease-out .5s infinite; }
    @keyframes pinShow{ from{opacity:0; transform:scale(0);} to{opacity:1; transform:scale(1);} }
    @keyframes pulseRing{
        0%{ transform:scale(1); opacity:.9; }
        100%{ transform:scale(2.8); opacity:0; }
    }

    /* Courier progress track (slide 3) */
    .courier-track{
        position:absolute;
        left:8%; right:26%;
        top:56%;
        height:2px;
        z-index:3;
        opacity:0;
    }
    .slide.active .courier-track{ animation:popIn .5s ease .5s forwards; }
    .courier-track .track-line{
        position:absolute; inset:0;
        border-top:2px dashed rgba(255,255,255,.55);
    }
    .courier-track .courier-dot{
        position:absolute;
        top:50%; left:0;
        width:30px; height:30px;
        margin-top:-15px; margin-left:-15px;
        border-radius:50%;
        background:var(--white);
        color:var(--spring-deep);
        display:flex; align-items:center; justify-content:center;
        box-shadow:0 6px 14px -4px rgba(0,0,0,.4);
    }
    .courier-dot svg{ width:16px; height:16px; }
    .slide.active .courier-track .courier-dot{ animation:courierMove 3.4s ease-in-out .7s infinite; }
    @keyframes courierMove{
        0%{ left:0%; }
        45%{ left:100%; }
        50%{ left:100%; }
        95%{ left:0%; }
        100%{ left:0%; }
    }

    /* ============ CONTENT CARD ============ */
    .content-wrap{
        position:absolute;
        left:0; right:0; bottom:0;
        z-index:5;
        display:flex;
        justify-content:center;
        padding:0 18px calc(22px + env(safe-area-inset-bottom, 0px));
    }

    .glass-card{
        width:100%;
        max-width:440px;
        background:linear-gradient(155deg, rgba(255,255,255,.20) 0%, rgba(255,255,255,.08) 100%);
        border:1px solid rgba(255,255,255,.35);
        backdrop-filter:blur(22px);
        -webkit-backdrop-filter:blur(22px);
        border-radius:28px;
        padding:26px 24px 22px;
        box-shadow:0 24px 60px -20px rgba(0,0,0,.55);
    }

    .eyebrow{
        display:inline-flex;
        align-items:center;
        gap:6px;
        background:rgba(255,255,255,.9);
        color:var(--spring-deep);
        font-family:'Poppins', sans-serif;
        font-weight:700;
        font-size:10.5px;
        letter-spacing:.05em;
        text-transform:uppercase;
        padding:6px 12px;
        border-radius:999px;
        margin-bottom:14px;
        opacity:0;
    }
    .glass-card.in .eyebrow{ animation:fadeUp .6s ease .1s forwards; }

    .glass-card h1{
        font-family:'Poppins', sans-serif;
        font-weight:700;
        font-size:25px;
        line-height:1.28;
        color:var(--white);
        text-shadow:0 2px 12px rgba(0,0,0,.35);
        margin-bottom:10px;
        opacity:0;
    }
    .glass-card.in h1{ animation:fadeUp .6s ease .2s forwards; }

    .glass-card p{
        font-size:14px;
        line-height:1.6;
        color:rgba(255,255,255,.92);
        margin-bottom:22px;
        opacity:0;
    }
    .glass-card.in p{ animation:fadeUp .6s ease .3s forwards; }

    @keyframes fadeUp{
        from{ opacity:0; transform:translateY(16px); }
        to{ opacity:1; transform:translateY(0); }
    }
    @keyframes fadeDown{
        from{ opacity:0; transform:translateY(-12px); }
        to{ opacity:1; transform:translateY(0); }
    }

    /* dots */
    .dots{
        display:flex;
        align-items:center;
        gap:7px;
        margin-bottom:20px;
        opacity:0;
    }
    .glass-card.in .dots{ animation:fadeUp .6s ease .38s forwards; }
    .dot{
        height:7px;
        width:7px;
        border-radius:999px;
        background:rgba(255,255,255,.4);
        transition:width .35s cubic-bezier(.4,0,.2,1), background .35s ease;
        cursor:pointer;
        padding:0;
    }
    .dot.active{
        width:26px;
        background:var(--white);
    }

    /* action row */
    .action-row{
        display:flex;
        align-items:center;
        gap:12px;
        opacity:0;
    }
    .glass-card.in .action-row{ animation:fadeUp .6s ease .46s forwards; }

    .btn-next{
        flex:1;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        background:var(--white);
        color:var(--spring-deep);
        font-family:'Poppins', sans-serif;
        font-weight:700;
        font-size:14.5px;
        padding:15px 20px;
        border-radius:16px;
        transition:transform .15s ease, box-shadow .3s ease;
    }
    .btn-next:active{ transform:scale(.97); }
    .btn-next svg{ width:17px; height:17px; transition:transform .2s ease; }
    .btn-next:hover svg{ transform:translateX(3px); }

    .btn-next.is-cta{
        background:linear-gradient(135deg, var(--amber) 0%, #C9821F 100%);
        color:var(--white);
        animation:glowPulse 2.4s ease-in-out infinite;
    }
    @keyframes glowPulse{
        0%,100%{ box-shadow:0 8px 22px -6px rgba(232,163,61,.55); }
        50%{ box-shadow:0 8px 32px -4px rgba(232,163,61,.95); }
    }

    .step-label{
        color:rgba(255,255,255,.85);
        font-family:'Poppins', sans-serif;
        font-weight:600;
        font-size:12px;
        flex-shrink:0;
        min-width:34px;
    }

    /* ============ RESPONSIVE ============ */
    @media (min-width:820px){
        .glass-card{ max-width:480px; padding:32px 30px 28px; }
        .glass-card h1{ font-size:30px; }
        .glass-card p{ font-size:15px; }
        .content-wrap{ justify-content:flex-start; padding-left:60px; padding-bottom:64px; }
        .slide-bg img{ object-position:center 30%; }
    }
    @media (max-width:380px){
        .glass-card h1{ font-size:22px; }
        .float-ic{ display:none; }
    }
</style>
</head>
<body>

<div class="onboard">

    {{-- ===================== SLIDE 1 ===================== --}}
    <section class="slide active" id="s1">
        <div class="slide-bg">
            <div class="bg-blur" style="background-image:url('{{ asset('assets/images/onboarding/slide-1-apotek.jpg') }}')"></div>
            <img class="bg-fg" src="{{ asset('assets/images/onboarding/slide-1-apotek.jpg') }}" alt="Apotek Rizki">
        </div>
        <div class="slide-overlay"></div>
        <div class="slide-vignette"></div>

        <span class="float-ic ic-cross">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20"/></svg>
        </span>
        <span class="float-ic ic-capsule">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="8" rx="4" transform="rotate(-45 12 12)"/><path d="m8.5 8.5 7 7" transform="rotate(-45 12 12)"/></svg>
        </span>
        <span class="float-ic ic-phone">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="2" width="12" height="20" rx="2.5"/><path d="M10 18h4"/></svg>
        </span>
    </section>

    {{-- ===================== SLIDE 2 ===================== --}}
    <section class="slide" id="s2">
        <div class="slide-bg">
            <div class="bg-blur" style="background-image:url('{{ asset('assets/images/onboarding/slide-2-apoteker.jpg') }}')"></div>
            <img class="bg-fg" src="{{ asset('assets/images/onboarding/slide-2-apoteker.jpg') }}" alt="Verifikasi Resep">
        </div>
        <div class="slide-overlay"></div>
        <div class="slide-vignette"></div>

        <span class="float-ic ic-doc">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 13h6M9 17h6"/></svg>
        </span>
        <span class="float-ic ic-pill">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.5 20.5 20.5 10.5a4.95 4.95 0 1 0-7-7L3.5 13.5a4.95 4.95 0 1 0 7 7Z"/><path d="m8.5 8.5 7 7"/></svg>
        </span>
        <span class="float-ic ic-check">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </span>
        <span class="float-ic ic-spinner">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M21 12a9 9 0 1 1-9-9"/></svg>
        </span>
    </section>

    {{-- ===================== SLIDE 3 ===================== --}}
    <section class="slide" id="s3">
        <div class="slide-bg">
            <div class="bg-blur" style="background-image:url('{{ asset('assets/images/onboarding/slide-3-kurir.jpg') }}')"></div>
            <img class="bg-fg" src="{{ asset('assets/images/onboarding/slide-3-kurir.jpg') }}" alt="Pengantaran Real-time">
        </div>
        <div class="slide-overlay"></div>
        <div class="slide-vignette"></div>

        <span class="float-ic ic-map">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
        </span>
        <span class="float-ic ic-bell">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
        </span>
        <span class="pulse-ring"></span>
        <div class="courier-track">
            <span class="track-line"></span>
            <span class="courier-dot">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="5.5" cy="17.5" r="3.5"/><circle cx="18.5" cy="17.5" r="3.5"/><path d="M15 6a1 1 0 0 0-1 1v6h5.5l-2-4H16l-1-3Z"/><path d="M2 17.5h1.5M9 17.5h6"/></svg>
            </span>
        </div>
    </section>

    {{-- ===================== TOP BAR ===================== --}}
    <div class="topbar">
        <div class="brand-chip">
            <span class="logo-dot">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" opacity="0"/><rect x="3" y="8" width="18" height="8" rx="4" transform="rotate(-45 12 12)"/><path d="m8.5 8.5 7 7" transform="rotate(-45 12 12)"/></svg>
            </span>
            <span>Apotek Rizki</span>
        </div>
        <button type="button" class="skip-btn" id="skipBtn" onclick="finishOnboarding()">
            Lewati
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
        </button>
    </div>

    {{-- ===================== CONTENT / TEKS PER SLIDE ===================== --}}
    <div class="content-wrap">
        <div class="glass-card in" id="glassCard">

            <div id="cardBody">
                {{-- Isi teks di-render lewat JS berdasarkan slide aktif, supaya animasi fade-up bisa retrigger tiap pindah slide --}}
            </div>

            <div class="dots" id="dots">
                <button type="button" class="dot active" data-i="0" onclick="goTo(0)" aria-label="Slide 1"></button>
                <button type="button" class="dot" data-i="1" onclick="goTo(1)" aria-label="Slide 2"></button>
                <button type="button" class="dot" data-i="2" onclick="goTo(2)" aria-label="Slide 3"></button>
            </div>

            <div class="action-row" id="actionRow">
                <span class="step-label" id="stepLabel">1/3</span>
                <button type="button" class="btn-next" id="nextBtn" onclick="handleNext()">
                    <span id="nextLabel">Selanjutnya</span>
                    <svg id="nextIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
                </button>
            </div>

        </div>
    </div>

</div>

<script>
    const slides = [
        {
            el: document.getElementById('s1'),
            eyebrow: 'Apotek Digital Aceh Tengah',
            title: 'Selamat Datang di Apotek Rizki Aceh Tengah',
            desc: 'Pesan obat dengan mudah, cepat, dan aman langsung dari perangkat Anda.'
        },
        {
            el: document.getElementById('s2'),
            eyebrow: 'Verifikasi Apoteker',
            title: 'Unggah Resep & Pilih Obat',
            desc: 'Unggah resep dokter atau pilih obat yang tersedia. Apoteker akan memverifikasi pesanan sebelum diproses.'
        },
        {
            el: document.getElementById('s3'),
            eyebrow: 'Lacak Real-Time',
            title: 'Pantau Pesanan Secara Real-Time',
            desc: 'Lihat status pesanan mulai dari diproses, dikemas, hingga diantar oleh kurir secara real-time.'
        },
    ];

    let current = 0;
    const dotsEls = document.querySelectorAll('.dot');
    const cardBody = document.getElementById('cardBody');
    const glassCard = document.getElementById('glassCard');
    const skipBtn = document.getElementById('skipBtn');
    const nextLabel = document.getElementById('nextLabel');
    const nextBtn = document.getElementById('nextBtn');
    const stepLabel = document.getElementById('stepLabel');

    const startUrl = "{{ route('login') }}";
    const registerUrl = "{{ route('register') }}";

    function renderCard(i){
        const s = slides[i];
        cardBody.innerHTML = `
            <span class="eyebrow">${s.eyebrow}</span>
            <h1>${s.title}</h1>
            <p>${s.desc}</p>
        `;
    }

    function renderActionRow(i){
        const isLast = i === slides.length - 1;
        stepLabel.textContent = (i + 1) + '/' + slides.length;

        // Selalu ambil elemen ikon yang LIVE di DOM (bukan referensi lama),
        // karena outerHTML di bawah ini mengganti node-nya tiap render.
        const arrowIcon = '<svg id="nextIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>';
        const startIcon = '<svg id="nextIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';

        if (isLast){
            nextBtn.classList.add('is-cta');
            nextLabel.textContent = 'Mulai';
            document.getElementById('nextIcon').outerHTML = startIcon;
            skipBtn.classList.add('is-hidden');
        } else {
            nextBtn.classList.remove('is-cta');
            nextLabel.textContent = 'Selanjutnya';
            document.getElementById('nextIcon').outerHTML = arrowIcon;
            skipBtn.classList.remove('is-hidden');
        }
    }

    // Fade-in ulang isi kartu (judul, deskripsi, dots, tombol) tiap pindah
    // slide, dengan cara lepas-pasang class "in" supaya animasi CSS-nya
    // ke-restart tiap kali (browser butuh reflow paksa di tengah-tengah).
    function replayCardAnimation(){
        glassCard.classList.remove('in');
        void glassCard.offsetWidth; // force reflow
        glassCard.classList.add('in');
    }

    function goTo(i){
        if (i === current) return;
        slides[current].el.classList.remove('active');
        current = i;
        slides[current].el.classList.add('active');

        dotsEls.forEach((d, idx) => d.classList.toggle('active', idx === i));
        renderCard(i);
        renderActionRow(i);
        replayCardAnimation();
    }

    function handleNext(){
        if (current < slides.length - 1){
            goTo(current + 1);
        } else {
            finishOnboarding();
        }
    }

    function finishOnboarding(){
        try { localStorage.setItem('apotek_onboarding_done', '1'); } catch (e) {}
        window.location.href = startUrl;
    }

    // Swipe gesture (mobile)
    let touchStartX = null;
    document.querySelector('.onboard').addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
    }, { passive: true });
    document.querySelector('.onboard').addEventListener('touchend', (e) => {
        if (touchStartX === null) return;
        const dx = e.changedTouches[0].clientX - touchStartX;
        if (Math.abs(dx) > 50){
            if (dx < 0 && current < slides.length - 1) goTo(current + 1);
            if (dx > 0 && current > 0) goTo(current - 1);
        }
        touchStartX = null;
    }, { passive: true });

    // Keyboard nav (desktop)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') handleNext();
        if (e.key === 'ArrowLeft' && current > 0) goTo(current - 1);
    });

    renderCard(0);
    renderActionRow(0);
</script>

</body>
</html>
