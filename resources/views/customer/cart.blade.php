<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Keranjang Belanja - Apotek Rizki</title>
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
    body{font-family:'Inter', sans-serif; background:var(--mint); color:var(--ink); -webkit-font-smoothing:antialiased; padding-bottom:90px;}
    a{text-decoration:none; color:inherit;}
    button{font-family:inherit;}

    .navbar{position:sticky; top:0; z-index:50; background:var(--white); border-bottom:1px solid var(--mint-deep);}
    .navbar-inner{max-width:900px; margin:0 auto; padding:14px 20px; display:flex; align-items:center; gap:12px;}
    .back-link{display:flex; align-items:center; gap:8px; font-weight:700; font-size:14.5px; color:var(--ink);}
    .back-link svg{width:20px; height:20px;}
    .nav-title{font-family:'Outfit', sans-serif; font-weight:700; font-size:16px; margin-right:auto;}
    .home-link{width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:var(--spring-deep);}
    .home-link svg{width:20px; height:20px;}

    .page-wrap{max-width:900px; margin:0 auto; padding:0 0 20px;}

    /* ===== FLASH MESSAGES ===== */
    .flash-success{
        background:#E3F5EA; border:1px solid var(--mint-deep); color:var(--spring-deep);
        padding:13px 16px; border-radius:14px; font-size:13.5px; font-weight:600;
        margin:14px 14px 0;
    }
    .flash-error{
        background:#FBE8E6; border:1px solid #f3c8c2; color:#B23A29;
        padding:13px 16px; border-radius:14px; font-size:13.5px; font-weight:600;
        margin:14px 14px 0;
    }

    /* ===== ADDRESS BAR ===== */
    .address-bar{
        background:var(--white);
        margin:14px 14px 0;
        border-radius:16px;
        padding:14px 16px;
        display:flex;
        align-items:center;
        gap:10px;
        box-shadow:var(--shadow-sm);
    }
    .address-bar svg.pin{width:18px; height:18px; color:var(--spring); flex-shrink:0;}
    .address-bar .addr-text{flex:1; font-size:13px; min-width:0;}
    .address-bar .addr-text b{font-weight:700;}
    .address-bar .addr-text .addr-line{color:var(--muted); font-size:12px; display:block; margin-top:2px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;}
    .address-bar .change-btn{
        font-size:12.5px;
        font-weight:700;
        color:var(--spring-deep);
        flex-shrink:0;
        padding:6px 10px;
        border-radius:999px;
        transition:background .15s;
    }
    .address-bar .change-btn:hover{background:var(--mint);}

    /* ===== WARNING BANNER (jarak > radius / belum ada alamat) ===== */
    .warn-banner{
        margin:12px 14px 0;
        background:#FBEAEA;
        border:1px solid #F3D0CE;
        border-radius:14px;
        padding:13px 16px;
        display:flex;
        align-items:flex-start;
        gap:10px;
        color:#9B3A2E;
    }
    .warn-banner.amber{background:#FCF1DF; border-color:#F0DBB0; color:#8A5E15;}
    .warn-banner svg{width:19px; height:19px; flex-shrink:0; margin-top:1px;}
    .warn-banner .warn-body{font-size:12.5px; line-height:1.5;}
    .warn-banner .warn-body b{display:block; font-size:13px; margin-bottom:2px;}
    .warn-banner a{font-weight:700; text-decoration:underline;}

    /* ===== SELECT ALL ===== */
    .select-all-bar{
        margin:12px 14px 0;
        background:var(--white);
        border-radius:14px;
        padding:13px 16px;
        display:flex;
        align-items:center;
        gap:10px;
        font-size:13px;
        font-weight:700;
        box-shadow:var(--shadow-sm);
    }

    .chk{
        width:20px; height:20px;
        border-radius:6px;
        border:2px solid var(--mint-deep);
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        flex-shrink:0;
        transition:background .15s, border-color .15s;
        background:var(--white);
    }
    .chk svg{width:13px; height:13px; color:var(--white); opacity:0; transition:opacity .1s;}
    .chk.checked{background:var(--spring); border-color:var(--spring);}
    .chk.checked svg{opacity:1;}

    /* ===== CART GROUP / ITEM ===== */
    .cart-group{
        margin:12px 14px 0;
        background:var(--white);
        border-radius:16px;
        box-shadow:var(--shadow-sm);
        overflow:hidden;
    }
    .group-header{
        display:flex;
        align-items:center;
        gap:10px;
        padding:14px 16px;
        border-bottom:1px solid var(--mint);
    }
    .group-header .store-name{font-size:13.5px; font-weight:700;}
    .group-header .store-loc{font-size:11.5px; color:var(--muted); font-weight:500;}

    .cart-item{
        display:flex;
        gap:12px;
        padding:14px 16px;
        border-bottom:1px solid var(--mint);
    }
    .cart-item:last-child{border-bottom:none;}

    .item-thumb{
        width:64px; height:64px;
        border-radius:12px;
        background:linear-gradient(150deg, var(--mint) 0%, var(--mint-deep) 100%);
        flex-shrink:0;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
    }
    .item-thumb img{width:100%; height:100%; object-fit:cover;}
    .item-thumb svg{width:26px; height:26px; color:var(--spring); opacity:.5;}

    .item-body{flex:1; min-width:0; display:flex; flex-direction:column; gap:4px;}
    .item-name{font-size:13.5px; font-weight:600; line-height:1.35;}
    .item-unit{font-size:11.5px; color:var(--muted);}
    .item-price{font-family:'Outfit', sans-serif; font-weight:700; font-size:14.5px; margin-top:2px;}

    .item-footer{
        display:flex;
        align-items:center;
        justify-content:space-between;
        margin-top:6px;
    }

    .note-link{
        display:flex;
        align-items:center;
        gap:5px;
        font-size:11.5px;
        color:var(--spring-deep);
        font-weight:600;
    }
    .note-link svg{width:13px; height:13px;}

    .qty-controls{display:flex; align-items:center; gap:10px;}
    .qty-btn{
        width:26px; height:26px;
        border-radius:8px;
        border:1.5px solid var(--mint-deep);
        background:var(--white);
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        color:var(--ink);
        transition:background .15s;
    }
    .qty-btn:hover{background:var(--mint);}
    .qty-btn svg{width:13px; height:13px;}
    .qty-btn.remove-btn{border-color:transparent; color:var(--muted);}
    .qty-val{font-size:13px; font-weight:700; min-width:16px; text-align:center;}

    /* ===== EMPTY STATE ===== */
    .empty-cart{
        background:var(--white);
        border-radius:20px;
        box-shadow:var(--shadow-sm);
        padding:70px 30px;
        text-align:center;
        color:var(--muted);
        margin:14px;
    }
    .empty-cart svg{width:56px; height:56px; color:var(--mint-deep); margin-bottom:16px;}
    .empty-cart h3{font-family:'Outfit', sans-serif; color:var(--ink); font-size:16px; margin-bottom:6px;}
    .empty-cart p{font-size:13px; margin-bottom:20px;}
    .empty-cart a{
        display:inline-block;
        background:var(--spring);
        color:var(--white);
        padding:12px 24px;
        border-radius:999px;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13px;
    }
    .empty-cart a:hover{background:var(--spring-deep);}

    /* ===== STICKY FOOTER ===== */
    .cart-footer{
        position:fixed;
        bottom:0; left:0; right:0;
        background:var(--white);
        border-top:1px solid var(--mint-deep);
        padding:14px 20px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        z-index:40;
        box-shadow:0 -8px 24px -12px rgba(29,43,38,0.15);
    }
    .footer-total{display:flex; flex-direction:column;}
    .footer-total .label{font-size:11.5px; color:var(--muted);}
    .footer-total .value{font-family:'Outfit', sans-serif; font-weight:800; font-size:17px; color:var(--spring-deep);}
    .footer-total .ongkir-note{font-size:10.5px; color:var(--muted); margin-top:1px;}

    .beli-btn{
        background:var(--spring);
        color:var(--white);
        border:none;
        padding:13px 34px;
        border-radius:14px;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:14px;
        cursor:pointer;
        transition:background .15s;
    }
    .beli-btn:hover{background:var(--spring-deep);}
    .beli-btn:disabled{background:var(--mint-deep); color:var(--muted); cursor:not-allowed;}

    @media (max-width:480px){
        .navbar-inner{padding:12px 16px;}
        .address-bar, .warn-banner, .select-all-bar, .cart-group, .flash-success, .flash-error{margin-left:10px; margin-right:10px;}
    }
</style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('dashboard') }}" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div class="nav-title">Keranjang</div>
        <a href="{{ route('dashboard') }}" class="home-link" aria-label="Beranda">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12l9-9 9 9"/><path d="M5 10v10h14V10"/></svg>
        </a>
    </div>
</nav>

<div class="page-wrap">

    {{-- ===== FLASH MESSAGES =====
         SEBELUMNYA TIDAK ADA di halaman ini. Ini penyebab paling mungkin kenapa
         tombol "Beli" terasa "tidak berfungsi": CheckoutController@show akan
         redirect balik ke sini (route('cart.index')) dengan session('error')
         kalau alamat belum lengkap atau ongkir tidak bisa dihitung (misal
         $user->jarak_km null / di luar radius). Tanpa blok ini, redirect itu
         terjadi diam-diam — halaman keranjang cuma "reload" tanpa pesan apa pun,
         jadi kelihatan seperti tombolnya tidak melakukan apa-apa. --}}
    @if (session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="flash-error">{{ session('error') }}</div>
    @endif

    {{-- ===== ADDRESS BAR ===== --}}
    <div class="address-bar">
        <svg class="pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
        <div class="addr-text">
            @if($alamatLengkap)
                <b>Dikirim ke {{ $user->nama_penerima ?? $user->name }}</b>
                <span class="addr-line">{{ $user->alamat }}</span>
            @else
                <b>Alamat belum diatur</b>
                <span class="addr-line">Lengkapi alamat untuk menghitung ongkos kirim</span>
            @endif
        </div>
        <a href="{{ route('alamat.create') }}" class="change-btn">{{ $alamatLengkap ? 'Ubah Alamat' : 'Tambah Alamat' }}</a>
    </div>

    {{-- ===== WARNING: alamat belum diisi ===== --}}
    @if(!$alamatLengkap && $cartItems->count() > 0)
        <div class="warn-banner amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01"/><path d="m10.29 3.86-8.18 14.18A2 2 0 0 0 3.93 21h16.14a2 2 0 0 0 1.82-2.96L13.71 3.86a2 2 0 0 0-3.42 0Z"/></svg>
            <div class="warn-body">
                <b>Ongkos kirim belum bisa dihitung</b>
                Lengkapi alamat di <a href="{{ route('alamat.create') }}">profil kamu</a> supaya kami bisa hitung jarak dan ongkir secara otomatis.
            </div>
        </div>
    @endif

    {{-- ===== WARNING: di luar area kecamatan yang dilayani ===== --}}
    @if($alamatLengkap && !$summary['bisa_diantar'])
        <div class="warn-banner">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/></svg>
            <div class="warn-body">
                <b>Maaf, alamat kamu di luar jangkauan pengiriman</b>
                Saat ini pengantaran hanya melayani Kecamatan {{ implode(', ', \App\Support\DistanceCalculator::kecamatanDilayani()) }}. Silakan <a href="{{ route('alamat.create') }}">ganti ke alamat yang berada di area tersebut</a>.
            </div>
        </div>
    @endif

    @if($cartItems->count() === 0)
        <div class="empty-cart">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.6a2 2 0 0 0 2-1.6L23 6H6"/></svg>
            <h3>Keranjang kamu masih kosong</h3>
            <p>Yuk cari obat atau kebutuhan kesehatan yang kamu perlukan.</p>
            <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
        </div>
    @else
        {{-- ===== SELECT ALL ===== --}}
        <div class="select-all-bar">
            <div class="chk checked" id="selectAllChk">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
            Pilih Semua ({{ $cartItems->count() }} produk)
        </div>

        {{-- ===== CART GROUP (satu apotek) ===== --}}
        <div class="cart-group">
            <div class="group-header">
                <div class="chk checked item-chk">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
                <div>
                    <div class="store-name">{{ config('apotek.nama') }}</div>
                    <div class="store-loc">Kec. Bebesen, Aceh Tengah</div>
                </div>
            </div>

            @foreach($cartItems as $item)
                <div class="cart-item" data-cart-item-id="{{ $item->id }}" data-item-price="{{ $item->obat->harga ?? 0 }}">
                    <div class="item-thumb">
                        @if($item->obat->image ?? false)
                            <img src="{{ Storage::url($item->obat->image) }}" alt="{{ $item->obat->nama }}">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.93 4.93a6 6 0 0 1 8.49 0l5.66 5.66a6 6 0 1 1-8.49 8.49L4.93 13.4a6 6 0 0 1 0-8.49Z"/><path d="M9.5 9.5l5 5"/></svg>
                        @endif
                    </div>
                    <div class="item-body">
                        <div class="item-name">{{ $item->obat->nama ?? 'Produk tidak ditemukan' }}</div>
                        <div class="item-unit">Per {{ $item->obat->satuan ?? 'item' }}</div>
                        <div class="item-price">Rp{{ number_format($item->obat->harga ?? 0, 0, ',', '.') }}</div>
                        <div class="item-footer">
                            <a href="#" class="note-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05 12.25 20.24a5.5 5.5 0 0 1-7.78-7.78l9.19-9.19a3.67 3.67 0 0 1 5.19 5.19L9.66 17.65a1.83 1.83 0 0 1-2.6-2.6l8.49-8.49"/></svg>
                                Tulis catatan
                            </a>
                            <div class="qty-controls">
                                <button class="qty-btn remove-btn" data-action="remove" aria-label="Hapus">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
                                </button>
                                <button class="qty-btn" data-action="decrease" aria-label="Kurangi">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14"/></svg>
                                </button>
                                <span class="qty-val">{{ $item->quantity }}</span>
                                <button class="qty-btn" data-action="increase" aria-label="Tambah">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@if($cartItems->count() > 0)
<div class="cart-footer">
    <div class="footer-total">
        <span class="label">Total Pembelian</span>
        <span class="value" id="footerTotal">Rp{{ number_format($summary['total'], 0, ',', '.') }}</span>
        @if($alamatLengkap && $summary['bisa_diantar'])
            <span class="ongkir-note">Termasuk ongkir Rp{{ number_format($summary['ongkir'], 0, ',', '.') }} ({{ number_format($summary['jarak_km'], 1) }} km)</span>
        @elseif($alamatLengkap && !$summary['bisa_diantar'])
            <span class="ongkir-note" style="color:var(--error);">Ongkir belum bisa dihitung</span>
        @else
            <span class="ongkir-note">Ongkir dihitung setelah alamat diisi</span>
        @endif
    </div>
    <button type="button" class="beli-btn" id="beliBtn" {{ (!$alamatLengkap || !$summary['bisa_diantar']) ? 'disabled' : '' }}>
        Beli
    </button>
</div>
@endif

<script>
(function(){
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ===== TOMBOL "BELI" =====
    // Dipasang paling awal supaya tidak terpengaruh error di bagian script
    // lain. type="button" ditambahkan di HTML supaya tombol ini TIDAK
    // pernah dianggap submit button kalau suatu saat markup di atasnya
    // berubah jadi ada <form>-nya.
    const beliBtn = document.getElementById('beliBtn');
    if (beliBtn) {
        beliBtn.addEventListener('click', function () {
            if (beliBtn.disabled) return;
            beliBtn.disabled = true;
            beliBtn.textContent = 'Memproses...';
            window.location.href = '{{ route('checkout.show') }}';
        });
    }

    const subtotalPerItem = {};

    document.querySelectorAll('.cart-item').forEach(el => {
        const qtyEl = el.querySelector('.qty-val');
        const price = parseFloat(el.dataset.itemPrice);
        const qty = qtyEl ? parseInt(qtyEl.textContent, 10) : NaN;

        // Item dengan data harga/qty tidak valid (mis. produk sudah
        // dihapus dari database) dilewati saja, tidak menghentikan
        // seluruh script seperti sebelumnya.
        if (!isNaN(price) && !isNaN(qty)) {
            subtotalPerItem[el.dataset.cartItemId] = { price, qty };
        }
    });

    const ongkir = {{ $summary['ongkir'] ?? 0 }};

    function recalcTotal(){
        let subtotal = 0;
        Object.values(subtotalPerItem).forEach(i => subtotal += i.price * i.qty);
        const footerTotal = document.getElementById('footerTotal');
        if(footerTotal) footerTotal.textContent = 'Rp' + (subtotal + ongkir).toLocaleString('id-ID');
    }

    document.querySelectorAll('.cart-item').forEach(itemEl => {
        const cartItemId = itemEl.dataset.cartItemId;
        const qtyValEl = itemEl.querySelector('.qty-val');

        itemEl.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.dataset.action;
                const url = `/keranjang/${cartItemId}`;

                if(action === 'remove'){
                    if(!confirm('Hapus produk ini dari keranjang?')) return;

                    fetch(url, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    })
                    .then(res => res.json())
                    .then(() => {
                        itemEl.remove();
                        delete subtotalPerItem[cartItemId];
                        recalcTotal();
                        if(Object.keys(subtotalPerItem).length === 0) window.location.reload();
                    });
                    return;
                }

                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ action }),
                })
                .then(async res => {
                    const data = await res.json();
                    if(!res.ok) throw new Error(data.message || 'Gagal memperbarui keranjang.');
                    return data;
                })
                .then(data => {
                    if(data.deleted){
                        itemEl.remove();
                        delete subtotalPerItem[cartItemId];
                        if(Object.keys(subtotalPerItem).length === 0) window.location.reload();
                    } else {
                        qtyValEl.textContent = data.quantity;
                        subtotalPerItem[cartItemId].qty = data.quantity;
                    }
                    recalcTotal();
                })
                .catch(err => alert(err.message));
            });
        });
    });
})();
</script>

</body>
</html>