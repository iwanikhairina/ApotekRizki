<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Tambah Alamat - Apotek Rizki</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<style>
    :root{
        --mint:#EAF7F0; --mint-deep:#D3EFE0; --spring:#12A874; --spring-deep:#0C7E57;
        --ink:#1D2B26; --muted:#7C8B84; --white:#FFFFFF; --error:#E0503B;
        --shadow-sm:0 6px 16px -8px rgba(29,43,38,0.14);
    }
    html, body{height:100%;}
    *{margin:0;padding:0;box-sizing:border-box;}
    body{font-family:'Inter', sans-serif; background:var(--white); color:var(--ink); -webkit-font-smoothing:antialiased;}
    a{text-decoration:none; color:inherit;}
    button{font-family:inherit;}
    input{font-family:inherit;}

    .navbar{position:relative; z-index:60; background:var(--white); border-bottom:1px solid var(--mint-deep); display:flex; align-items:center; gap:14px; padding:14px 18px; height:63px;}
    .back-btn{width:34px; height:34px; display:flex; align-items:center; justify-content:center; color:var(--ink); flex-shrink:0;}
    .back-btn svg{width:22px; height:22px;}
    .navbar h1{font-family:'Outfit', sans-serif; font-size:16px; font-weight:700;}

    /* ===== STEP 1: PETA FULL SCREEN (fixed, tidak mengunci scroll body) ===== */
    #step1{position:fixed; top:63px; left:0; right:0; bottom:0; width:100%; overflow:hidden; z-index:10;}
    #map{position:absolute; inset:0; z-index:1;}

    .search-box-wrap{position:absolute; top:14px; left:14px; right:14px; z-index:1000;}
    .search-box{
        width:100%; background:var(--white); border:none; border-radius:14px;
        padding:13px 16px; font-size:14px; box-shadow:var(--shadow-sm); outline:none;
    }
    .search-results{
        background:var(--white); border-radius:14px; margin-top:6px; box-shadow:var(--shadow-sm);
        max-height:240px; overflow-y:auto; display:none;
    }
    .search-results.open{display:block;}
    .search-result-item{
        padding:12px 16px; font-size:13px; cursor:pointer; border-bottom:1px solid var(--mint);
        display:flex; gap:10px; align-items:flex-start;
    }
    .search-result-item:last-child{border-bottom:none;}
    .search-result-item:hover{background:var(--mint);}
    .search-result-item svg{width:15px; height:15px; color:var(--spring); flex-shrink:0; margin-top:2px;}

    .locate-bar{
        position:absolute; left:14px; right:14px; bottom:84px; z-index:900;
        display:flex; align-items:center; gap:10px; padding:13px 16px;
        color:var(--spring-deep); font-weight:600; font-size:13.5px; cursor:pointer;
        background:var(--white); border-radius:14px; box-shadow:var(--shadow-sm);
    }
    .locate-bar svg{width:18px; height:18px; flex-shrink:0;}
    .locate-bar.loading svg{animation:spin 1s linear infinite;}
    @keyframes spin{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}

    .step1-footer{position:absolute; left:0; right:0; bottom:0; z-index:900; padding:14px 18px; border-top:1px solid var(--mint-deep); background:var(--white);}
    .next-btn{
        width:100%; background:var(--spring); color:var(--white); border:none;
        padding:14px; border-radius:14px; font-family:'Outfit', sans-serif; font-weight:700;
        font-size:14.5px; cursor:pointer; transition:background .15s;
    }
    .next-btn:hover{background:var(--spring-deep);}
    .next-btn:disabled{background:var(--mint-deep); color:var(--muted); cursor:not-allowed;}

    /* ===== STEP 2: FORM (scrollable sendiri, terpisah dari body) ===== */
    #step2{display:none; height:calc(100vh - 63px); overflow-y:auto; -webkit-overflow-scrolling:touch;}
    .map-preview{position:relative; height:200px; width:100%;}
    #mapPreview{height:100%; width:100%; z-index:1;}
    .repin-btn{
        position:absolute; bottom:14px; left:50%; transform:translateX(-50%); z-index:500;
        background:var(--white); border:1.5px solid var(--spring); color:var(--spring-deep);
        font-family:'Outfit', sans-serif; font-weight:700; font-size:12.5px;
        padding:9px 18px; border-radius:999px; cursor:pointer; box-shadow:var(--shadow-sm);
    }

    .form-wrap{padding:18px 18px 100px;}
    .field-group{margin-bottom:18px;}
    .field-label{font-size:11.5px; color:var(--muted); margin-bottom:4px; display:block;}
    .field-value-static{font-size:13.5px; line-height:1.5; color:var(--ink);}
    .field-input{
        width:100%; border:none; border-bottom:1.5px solid var(--mint-deep);
        padding:8px 0; font-size:13.5px; color:var(--ink); outline:none; background:transparent;
    }
    .field-input:focus{border-color:var(--spring);}
    .field-input::placeholder{color:#B8C4BE;}

    .divider{height:8px; background:var(--mint); margin:22px -18px;}

    .checkbox-row{display:flex; align-items:center; gap:10px; margin-top:16px;}
    .checkbox-row input[type="checkbox"]{width:18px; height:18px; accent-color:var(--spring);}
    .checkbox-row label{font-size:13px; font-weight:600;}

    .out-of-range-modal-backdrop{
        display:none; position:fixed; inset:0; background:rgba(29,43,38,0.55);
        z-index:2000; align-items:center; justify-content:center; padding:20px;
    }
    .out-of-range-modal-backdrop.open{display:flex;}
    .out-of-range-modal{
        background:var(--white); border-radius:24px; padding:32px 28px 26px;
        max-width:360px; width:100%; text-align:center; box-shadow:0 20px 40px -16px rgba(29,43,38,0.25);
        animation:popIn .18s ease;
    }
    @keyframes popIn{from{opacity:0; transform:scale(.94);} to{opacity:1; transform:scale(1);}}
    .out-of-range-modal-icon{
        width:56px; height:56px; border-radius:50%; background:#FBEAEA; color:#D64541;
        display:flex; align-items:center; justify-content:center; margin:0 auto 16px;
    }
    .out-of-range-modal-icon svg{width:26px; height:26px;}
    .out-of-range-modal h3{font-family:'Outfit', sans-serif; font-size:16.5px; font-weight:700; margin-bottom:8px;}
    .out-of-range-modal p{font-size:13px; color:var(--muted); line-height:1.55; margin-bottom:22px;}
    .out-of-range-modal-btn{
        width:100%; background:var(--spring); color:var(--white); border:none;
        padding:13px; border-radius:14px; font-family:'Outfit', sans-serif; font-weight:700;
        font-size:13.5px; cursor:pointer; transition:background .15s;
    }
    .out-of-range-modal-btn:hover{background:var(--spring-deep);}

    .hint-note{font-size:11px; color:var(--muted); margin-top:4px;}

    .save-footer{
        position:fixed; bottom:0; left:0; right:0; background:var(--white);
        border-top:1px solid var(--mint-deep); padding:14px 18px; z-index:40;
    }
    .save-btn{
        width:100%; background:var(--spring); color:var(--white); border:none;
        padding:14px; border-radius:14px; font-family:'Outfit', sans-serif; font-weight:700;
        font-size:14.5px; cursor:pointer;
    }
    .save-btn:hover{background:var(--spring-deep);}
    .save-btn:disabled{background:var(--mint-deep); color:var(--muted); cursor:not-allowed;}

    .pin-center-note{
        position:absolute; top:50%; left:50%; transform:translate(-50%,-100%);
        pointer-events:none; z-index:800;
    }
</style>
</head>
<body>

<nav class="navbar">
    <a href="{{ route('cart.index') }}" class="back-btn" id="backBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    </a>
    <h1 id="pageTitle">Tambah Alamat</h1>
</nav>

@if ($errors->has('lokasi'))
    <div class="out-of-range-modal-backdrop open" id="outOfRangeModal">
        <div class="out-of-range-modal">
            <div class="out-of-range-modal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/></svg>
            </div>
            <h3>Di luar jangkauan pengiriman</h3>
            <p>Alamat yang dipilih berada di luar area layanan pengiriman. Mohon pilih alamat lain yang masih berada dalam jangkauan.</p>
            <button type="button" class="out-of-range-modal-btn" id="outOfRangeModalBtn">Ubah Alamat</button>
        </div>
    </div>
@endif

{{-- ===================== STEP 1: PILIH LOKASI DI PETA (FULL SCREEN) ===================== --}}
<div id="step1">
    <div id="map"></div>

    <div class="pin-center-note">
        <svg width="36" height="46" viewBox="0 0 24 30" fill="none">
            <path d="M12 0C5.4 0 0 5.4 0 12c0 9 12 18 12 18s12-9 12-18c0-6.6-5.4-12-12-12z" fill="#E0503B"/>
            <circle cx="12" cy="12" r="4.5" fill="#fff"/>
        </svg>
    </div>

    <div class="search-box-wrap">
        <input type="text" id="searchBox" class="search-box" placeholder="Cari alamat, nama jalan, atau tempat..." autocomplete="off">
        <div class="search-results" id="searchResults"></div>
    </div>

    <div class="locate-bar" id="locateBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>
        <span id="locateLabel">Gunakan lokasi saya saat ini</span>
    </div>

    <div class="step1-footer">
        <button class="next-btn" id="nextBtn" disabled>Berikutnya</button>
    </div>
</div>

{{-- ===================== STEP 2: DETAIL ALAMAT ===================== --}}
<div id="step2">
    <div class="map-preview">
        <div id="mapPreview"></div>
        <button type="button" class="repin-btn" id="repinBtn">Ubah Pinpoint</button>
    </div>

    <form class="form-wrap" method="POST" action="{{ route('alamat.store') }}" id="addressForm">
        @csrf
        <input type="hidden" name="latitude" id="inputLat" value="{{ old('latitude', $existingAddress['latitude'] ?? '') }}">
        <input type="hidden" name="longitude" id="inputLng" value="{{ old('longitude', $existingAddress['longitude'] ?? '') }}">
        <input type="hidden" name="alamat_lengkap" id="inputAlamatLengkap" value="{{ old('alamat_lengkap', $existingAddress['alamat_lengkap'] ?? '') }}">

        <div class="field-group">
            <span class="field-label">Alamat (Berdasarkan Pinpoint)</span>
            <div class="field-value-static" id="displayAlamatLengkap">{{ old('alamat_lengkap', $existingAddress['alamat_lengkap'] ?? '—') }}</div>
        </div>

        <div class="field-group">
            <span class="field-label">Detail Alamat</span>
            <input type="text" name="detail_alamat" class="field-input" placeholder="Tulis No. Rumah, Blok, RT/RW, dll" value="{{ old('detail_alamat', $existingAddress['detail_alamat'] ?? '') }}">
        </div>

        <div class="field-group">
            <span class="field-label">Provinsi</span>
            <input type="text" name="provinsi" id="inputProvinsi" class="field-input" placeholder="Isi manual jika kosong" value="{{ old('provinsi', $existingAddress['provinsi'] ?? '') }}" required>
        </div>

        <div class="field-group">
            <span class="field-label">Kota / Kabupaten</span>
            <input type="text" name="kota" id="inputKota" class="field-input" placeholder="Isi manual jika kosong" value="{{ old('kota', $existingAddress['kota'] ?? '') }}" required>
        </div>

        <div class="field-group">
            <span class="field-label">Kecamatan</span>
            <input type="text" name="kecamatan" id="inputKecamatan" class="field-input" placeholder="Isi manual jika kosong" value="{{ old('kecamatan', $existingAddress['kecamatan'] ?? '') }}" required>
        </div>

        <div class="field-group">
            <span class="field-label">Kelurahan</span>
            <input type="text" name="kelurahan" id="inputKelurahan" class="field-input" value="{{ old('kelurahan', $existingAddress['kelurahan'] ?? '') }}">
        </div>

        <div class="field-group">
            <span class="field-label">Kode Pos</span>
            <input type="text" name="kode_pos" id="inputKodePos" class="field-input" value="{{ old('kode_pos', $existingAddress['kode_pos'] ?? '') }}">
            <span class="hint-note">Data otomatis dari OpenStreetMap kadang belum lengkap untuk area ini — silakan koreksi manual kalau perlu.</span>
        </div>

        <div class="divider"></div>

        <div class="field-group">
            <span class="field-label">Nama Penerima</span>
            <input type="text" name="nama_penerima" class="field-input" placeholder="Nama lengkap penerima" value="{{ old('nama_penerima', $existingAddress['nama_penerima'] ?? auth()->user()->name ?? '') }}" required>
        </div>

        <div class="field-group">
            <span class="field-label">Nomor Telepon</span>
            <input type="text" name="no_telepon" class="field-input" placeholder="08xxxxxxxxxx" value="{{ old('no_telepon', $existingAddress['no_telepon'] ?? '') }}" required>
        </div>

        <div class="field-group">
            <span class="field-label">Simpan Sebagai</span>
            <input type="text" name="label_alamat" class="field-input" placeholder="ex: Rumah / Kantor" value="{{ old('label_alamat', $existingAddress['label_alamat'] ?? '') }}">
        </div>

        <div class="checkbox-row">
            <input type="checkbox" checked disabled>
            <label>Alamat Utama</label>
        </div>

        <div class="save-footer">
            <button type="submit" class="save-btn" id="saveBtn">Simpan</button>
        </div>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map, mapPreview, previewMarker;
    let selectedLat = null, selectedLng = null;
    let searchTimeout = null;

    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const nextBtn = document.getElementById('nextBtn');
    const backBtn = document.getElementById('backBtn');
    const pageTitle = document.getElementById('pageTitle');
    const searchResults = document.getElementById('searchResults');

    // Titik tengah default: kalau user sudah punya alamat tersimpan, mulai dari situ (mode edit).
    // Kalau belum, mulai dari titik dekat lokasi Apotek Rizki, Bebesen, Aceh Tengah.
    @if (!empty($existingAddress))
        const defaultCenter = [{{ $existingAddress['latitude'] }}, {{ $existingAddress['longitude'] }}];
        const isEditMode = true;
    @else
        const defaultCenter = [{{ config('apotek.latitude') }}, {{ config('apotek.longitude') }}];
        const isEditMode = false;
    @endif

    if (isEditMode) {
        pageTitle.textContent = 'Ubah Alamat';
    }

    function initMap() {
        map = L.map('map', { zoomControl: true, attributionControl: true }).setView(defaultCenter, 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        map.on('moveend', () => {
            const c = map.getCenter();
            selectedLat = c.lat;
            selectedLng = c.lng;
            nextBtn.disabled = false;
        });

        // trigger sekali di awal supaya nextBtn langsung aktif dengan titik default
        const c = map.getCenter();
        selectedLat = c.lat;
        selectedLng = c.lng;
        nextBtn.disabled = false;

        // Leaflet butuh invalidateSize setelah container benar-benar selesai di-layout browser.
        // Dipanggil berkali-kali di titik waktu berbeda supaya aman di semua kondisi render.
        requestAnimationFrame(() => map.invalidateSize());
        setTimeout(() => map.invalidateSize(), 250);
        setTimeout(() => map.invalidateSize(), 800);
        window.addEventListener('resize', () => map.invalidateSize());

        // Mode edit: langsung tampilkan form yang sudah terisi, user tinggal review/ubah.
        // Peta step 1 tetap tersedia lewat tombol "Ubah Pinpoint" di step 2 kalau mau ganti titik.
        if (isEditMode) {
            goToStep2(defaultCenter[0], defaultCenter[1]);
        }
    }

    // ===== Pencarian alamat via Nominatim =====
    const searchInput = document.getElementById('searchBox');
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        const q = searchInput.value.trim();
        if (q.length < 3) {
            searchResults.classList.remove('open');
            return;
        }
        searchTimeout = setTimeout(() => doSearch(q), 450);
    });

    function doSearch(q) {
        // dibiaskan ke area Aceh Tengah supaya hasil lebih relevan
        const viewbox = '96.75,4.70,96.95,4.55';
        const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(q)}&addressdetails=1&limit=6&countrycodes=id&viewbox=${viewbox}&bounded=0`;

        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                searchResults.innerHTML = '';
                if (!data.length) {
                    searchResults.classList.remove('open');
                    return;
                }
                data.forEach(item => {
                    const el = document.createElement('div');
                    el.className = 'search-result-item';
                    el.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg><span>${item.display_name}</span>`;
                    el.addEventListener('click', () => {
                        map.setView([parseFloat(item.lat), parseFloat(item.lon)], 17);
                        searchResults.classList.remove('open');
                        searchInput.value = item.display_name;
                    });
                    searchResults.appendChild(el);
                });
                searchResults.classList.add('open');
            })
            .catch(() => searchResults.classList.remove('open'));
    }

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.search-box-wrap')) {
            searchResults.classList.remove('open');
        }
    });

    // ===== Gunakan lokasi saya =====
    const locateBtn = document.getElementById('locateBtn');
    const locateLabel = document.getElementById('locateLabel');

    locateBtn.addEventListener('click', () => {
        if (!navigator.geolocation) {
            alert('Browser kamu tidak mendukung deteksi lokasi.');
            return;
        }

        if (!window.isSecureContext) {
            alert('Deteksi lokasi hanya berfungsi di koneksi aman (https, atau localhost/127.0.0.1).');
            return;
        }

        locateBtn.classList.add('loading');
        locateLabel.textContent = 'Mencari lokasi kamu...';

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                locateBtn.classList.remove('loading');
                locateLabel.textContent = 'Gunakan lokasi saya saat ini';
                map.setView([pos.coords.latitude, pos.coords.longitude], 17);
            },
            (err) => {
                locateBtn.classList.remove('loading');
                locateLabel.textContent = 'Gunakan lokasi saya saat ini';

                let msg = 'Gagal mendapatkan lokasi kamu.';
                if (err.code === err.PERMISSION_DENIED) {
                    msg = 'Izin lokasi ditolak. Klik ikon gembok/info di address bar browser, aktifkan izin Location untuk halaman ini, lalu coba lagi.';
                } else if (err.code === err.POSITION_UNAVAILABLE) {
                    msg = 'Lokasi kamu tidak dapat dideteksi saat ini. Pastikan GPS/Location Service perangkat aktif.';
                } else if (err.code === err.TIMEOUT) {
                    msg = 'Deteksi lokasi memakan waktu terlalu lama. Coba lagi.';
                }
                alert(msg);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            }
        );
    });

    // ===== Berikutnya -> reverse geocode =====
    nextBtn.addEventListener('click', () => {
        if (selectedLat === null) return;
        reverseGeocode(selectedLat, selectedLng);
    });

    function reverseGeocode(lat, lng) {
        nextBtn.disabled = true;
        nextBtn.textContent = 'Memuat alamat...';

        const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&addressdetails=1&accept-language=id`;

        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(res => {
                if (!res.ok) throw new Error('Gagal menghubungi layanan alamat.');
                return res.json();
            })
            .then(data => {
                nextBtn.disabled = false;
                nextBtn.textContent = 'Berikutnya';

                if (!data || !data.address) {
                    alert('Gagal membaca alamat di titik ini. Coba geser peta sedikit lalu coba lagi.');
                    return;
                }

                const addr = data.address;
                const alamatLengkap = data.display_name || '';

                const provinsi = addr.state || '';

                // Nominatim TIDAK konsisten menandai level kecamatan/kabupaten
                // di seluruh Indonesia. Di kabupaten (mis. Aceh Tengah), nama
                // kecamatan sering muncul di field "county" (bukan
                // "city_district"), sedangkan nama kabupatennya sendiri
                // muncul di "state_district". Di kota besar, kecamatan
                // biasanya di "city_district"/"suburb". Supaya tidak salah
                // ambil (mis. "Bebesen" kepilih jadi kota, bukan kecamatan),
                // cocokkan dulu semua field kandidat ke daftar kecamatan yang
                // dilayani Apotek Rizki, baru fallback ke urutan prioritas
                // biasa kalau tidak ada yang cocok.
                //
                // PENTING: daftar ini harus selalu sinkron dengan
                // DistanceCalculator::KECAMATAN_DILAYANI_DEFAULT / config
                // apotek.kecamatan_dilayani di backend.
                const kecamatanDilayani = ['Kebayakan', 'Bebesen', 'Pegasing', 'Lut Tawar'];
                const kandidatKecamatan = [
                    addr.city_district,
                    addr.suburb,
                    addr.county,
                    addr.municipality,
                    addr.town,
                ].filter(Boolean);

                let kecamatan = kandidatKecamatan.find((nilai) =>
                    kecamatanDilayani.some((k) => nilai.toLowerCase().includes(k.toLowerCase()))
                );
                if (!kecamatan) {
                    // Tidak ada kandidat yang cocok dengan area layanan —
                    // pakai urutan prioritas standar sebagai fallback.
                    kecamatan = addr.city_district || addr.suburb || addr.municipality || '';
                }

                // Field kota/kabupaten dihitung TERPISAH dari kecamatan (jangan
                // pakai "county" duluan di sini, karena di banyak kabupaten
                // "county" itu isinya nama kecamatan, bukan nama kabupaten).
                const kota = addr.state_district || addr.regency || addr.city
                    || (addr.county && addr.county !== kecamatan ? addr.county : '') || '';

                const kelurahan = addr.village || addr.neighbourhood || addr.hamlet
                    || (addr.suburb && addr.suburb !== kecamatan ? addr.suburb : '') || '';
                const kodePos = addr.postcode || '';

                document.getElementById('inputLat').value = lat;
                document.getElementById('inputLng').value = lng;
                document.getElementById('inputAlamatLengkap').value = alamatLengkap;
                document.getElementById('displayAlamatLengkap').textContent = alamatLengkap;

                document.getElementById('inputProvinsi').value = provinsi;
                document.getElementById('inputKota').value = kota;
                document.getElementById('inputKecamatan').value = kecamatan;
                document.getElementById('inputKelurahan').value = kelurahan;
                document.getElementById('inputKodePos').value = kodePos;

                goToStep2(lat, lng);
            })
            .catch(err => {
                nextBtn.disabled = false;
                nextBtn.textContent = 'Berikutnya';
                alert('Gagal membaca alamat: ' + err.message);
            });
    }

    function goToStep2(lat, lng) {
        step1.style.display = 'none';
        step2.style.display = 'block';
        pageTitle.textContent = 'Tambah Alamat';
        backBtn.onclick = (e) => { e.preventDefault(); goToStep1(); };

        if (mapPreview) {
            mapPreview.remove();
            mapPreview = null;
        }

        mapPreview = L.map('mapPreview', {
            zoomControl: false,
            dragging: false,
            scrollWheelZoom: false,
            touchZoom: false,
            doubleClickZoom: false,
            boxZoom: false,
            keyboard: false,
            tap: false,
        }).setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(mapPreview);
        previewMarker = L.marker([lat, lng]).addTo(mapPreview);

        requestAnimationFrame(() => mapPreview.invalidateSize());
        setTimeout(() => mapPreview.invalidateSize(), 250);

        document.getElementById('repinBtn').onclick = () => goToStep1();
    }

    function goToStep1() {
        step2.style.display = 'none';
        step1.style.display = 'block';
        pageTitle.textContent = 'Tambah Alamat';
        backBtn.href = "{{ route('cart.index') }}";
        backBtn.onclick = null;
        requestAnimationFrame(() => map.invalidateSize());
        setTimeout(() => map.invalidateSize(), 250);
    }

    if (document.readyState === 'complete') {
        initMap();
    } else {
        window.addEventListener('load', initMap);
    }

    // Kalau server menolak karena di luar jangkauan (>18km), buka langsung di step 2
    // supaya form yang tadi diisi user tetap kelihatan di belakang modal.
    @if ($errors->has('lokasi'))
        (function(){
            const lat = parseFloat(document.getElementById('inputLat').value);
            const lng = parseFloat(document.getElementById('inputLng').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                const runAfterMapReady = () => goToStep2(lat, lng);
                if (document.readyState === 'complete') {
                    runAfterMapReady();
                } else {
                    window.addEventListener('load', runAfterMapReady);
                }
            }
        })();
    @endif

    // ===== Modal "di luar jangkauan" -> tombol Ubah Alamat kembali ke peta =====
    const outOfRangeModal = document.getElementById('outOfRangeModal');
    const outOfRangeModalBtn = document.getElementById('outOfRangeModalBtn');
    if (outOfRangeModalBtn) {
        outOfRangeModalBtn.addEventListener('click', () => {
            outOfRangeModal.classList.remove('open');
            goToStep2WasSkipped = true; // penanda: user datang dari error validasi, bukan alur normal
            step2.style.display = 'none';
            step1.style.display = 'block';
            pageTitle.textContent = 'Tambah Alamat';
            backBtn.href = "{{ route('cart.index') }}";
            backBtn.onclick = null;
            requestAnimationFrame(() => map && map.invalidateSize());
            setTimeout(() => map && map.invalidateSize(), 250);
        });
    }
    let goToStep2WasSkipped = false;
</script>

</body>
</html>