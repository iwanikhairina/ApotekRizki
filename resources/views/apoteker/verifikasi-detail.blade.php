@extends('layouts.apoteker')

@section('title', 'Detail Verifikasi Resep')

@section('content')
<style>
    .back-link{
        display:inline-flex;
        align-items:center;
        gap:6px;
        color:var(--text-muted);
        text-decoration:none;
        font-size:14px;
        font-weight:600;
        margin-bottom:18px;
    }
    .back-link:hover{color:var(--mint-700);}

    .page-header{margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;}
    .page-header h1{
        font-family:'Outfit',sans-serif;
        font-size:24px;
        font-weight:700;
    }

    .alert-success{
        background:#eafaf3;
        border:1px solid var(--mint-100);
        color:var(--mint-700);
        padding:12px 16px;
        border-radius:12px;
        font-size:14px;
        margin-bottom:20px;
        font-weight:600;
    }

    .badge{
        display:inline-block;
        padding:6px 14px;
        border-radius:999px;
        font-size:13px;
        font-weight:700;
    }
    .badge-menunggu{background:var(--amber-bg);color:var(--amber);}
    .badge-disetujui{background:var(--mint-50);color:var(--mint-700);}
    .badge-ditolak{background:#fff1f0;color:#e0433c;}
    .badge-tidak-perlu{background:#f1f5f9;color:#64748b;}

    .grid{
        display:grid;
        grid-template-columns:1.4fr 1fr;
        gap:20px;
    }
    .card{
        background:#fff;
        border-radius:20px;
        padding:24px;
        box-shadow:0 8px 24px rgba(15,47,34,.06);
        margin-bottom:20px;
    }
    .card h3{
        font-family:'Outfit',sans-serif;
        font-size:16px;
        font-weight:700;
        margin-bottom:16px;
        color:var(--text-dark);
    }
    .info-row{
        display:flex;
        justify-content:space-between;
        padding:10px 0;
        border-bottom:1px solid #f0f4f2;
        font-size:14px;
    }
    .info-row:last-child{border-bottom:none;}
    .info-row .label{color:var(--text-muted);}
    .info-row .value{font-weight:600;color:var(--text-dark);text-align:right;}

    .obat-flag-item{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:12px 0;
        border-bottom:1px solid #f0f4f2;
    }
    .obat-flag-item:last-child{border-bottom:none;}
    .obat-name{font-weight:700;font-size:14px;color:var(--text-dark);}
    .flag-tags{display:flex;gap:6px;margin-top:4px;}
    .tag{
        font-size:11.5px;
        font-weight:700;
        padding:3px 10px;
        border-radius:999px;
    }
    .tag-resep{background:#fff1f0;color:#e0433c;}
    .tag-ktp{background:#eef2ff;color:#6366f1;}

    .doc-preview{
        text-align:center;
    }
    .doc-preview img{
        width:100%;
        border-radius:14px;
        border:1px solid #eee;
        margin-bottom:10px;
    }
    .doc-preview img.doc-zoomable{
        cursor:zoom-in;
        transition:opacity .15s;
    }
    .doc-preview img.doc-zoomable:hover{opacity:.9;}

    /* ===== Modal preview gambar (zoom in/out) ===== */
    .img-modal-backdrop{
        display:none;
        position:fixed;
        inset:0;
        background:rgba(15,23,20,.92);
        z-index:500;
        flex-direction:column;
    }
    .img-modal-backdrop.open{display:flex;}

    .img-modal-toolbar{
        display:flex;
        align-items:center;
        justify-content:center;
        gap:10px;
        padding:14px;
        flex-shrink:0;
    }
    .img-modal-toolbar button{
        width:38px;height:38px;
        border-radius:10px;
        border:none;
        background:rgba(255,255,255,.12);
        color:#fff;
        font-size:18px;
        font-weight:700;
        cursor:pointer;
        display:flex;align-items:center;justify-content:center;
        transition:background .15s;
    }
    .img-modal-toolbar button:hover{background:rgba(255,255,255,.22);}
    .img-modal-toolbar #imgModalClose{
        background:rgba(224,67,60,.85);
        margin-left:14px;
    }
    .img-modal-toolbar #imgModalClose:hover{background:#e0433c;}
    .img-modal-zoom-level{
        color:#fff;
        font-size:13px;
        font-weight:700;
        font-family:'Outfit',sans-serif;
        min-width:52px;
        text-align:center;
    }

    .img-modal-viewport{
        flex:1;
        overflow:hidden;
        display:flex;
        align-items:center;
        justify-content:center;
        position:relative;
        cursor:grab;
        touch-action:none;
    }
    .img-modal-viewport.dragging{cursor:grabbing;}
    .img-modal-viewport img{
        max-width:90%;
        max-height:85vh;
        user-select:none;
        pointer-events:none;
        transition:transform .05s linear;
        border-radius:8px;
    }
    .doc-label{
        font-size:13px;
        font-weight:700;
        color:var(--text-dark);
        margin-bottom:10px;
    }
    .no-doc{
        text-align:center;
        padding:30px 10px;
        color:var(--text-muted);
        font-size:13.5px;
        background:var(--mint-50);
        border-radius:14px;
    }

    .action-buttons{
        display:flex;
        flex-direction:column;
        gap:10px;
    }
    .btn-action{
        border:none;
        padding:13px;
        border-radius:14px;
        font-size:14px;
        font-weight:700;
        font-family:'Outfit',sans-serif;
        cursor:pointer;
        transition:.15s ease;
        width:100%;
    }
    .btn-setuju{background:var(--mint-500);color:#fff;}
    .btn-setuju:hover{background:var(--mint-700);}
    .btn-tolak{background:#fff1f0;color:#e0433c;border:1.5px solid #ffd4d0;}
    .btn-tolak:hover{background:#ffe4e1;}

    .status-note{
        text-align:center;
        font-size:13.5px;
        color:var(--text-muted);
        background:var(--mint-50);
        padding:14px;
        border-radius:14px;
    }

    .add-obat-form{margin-bottom:16px;}
    .obat-row{display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;margin-bottom:10px;}
    .add-obat-form .field{flex:1;min-width:160px;}
    .add-obat-form label{display:block;font-size:12px;font-weight:600;color:var(--text-muted);margin-bottom:6px;}
    .add-obat-form select,
    .add-obat-form input{
        width:100%;
        padding:10px 12px;
        border:1.5px solid #e2e8f0;
        border-radius:12px;
        font-size:13.5px;
        font-family:inherit;
    }
    .add-obat-form select:focus,
    .add-obat-form input:focus{outline:none;border-color:var(--mint-500);}
    .btn-tambah-obat{
        background:var(--mint-500);
        color:#fff;
        border:none;
        padding:11px 20px;
        border-radius:12px;
        font-size:13.5px;
        font-weight:700;
        font-family:'Outfit',sans-serif;
        cursor:pointer;
        white-space:nowrap;
    }
    .btn-tambah-obat:hover{background:var(--mint-700);}
    .btn-tambah-obat.outline{background:#fff;color:var(--mint-500);border:1.5px solid var(--mint-500);}
    .btn-tambah-obat.outline:hover{background:var(--mint-50);}

    .obat-list-item{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:12px 0;
        border-bottom:1px solid #f0f4f2;
        gap:10px;
    }
    .obat-list-item:last-child{border-bottom:none;}
    .obat-list-item .qty-price{font-size:12.5px;color:var(--text-muted);margin-top:2px;}
    .btn-hapus-obat{
        background:#fff1f0;
        color:#e0433c;
        border:1px solid #ffd4d0;
        width:30px;height:30px;
        border-radius:9px;
        cursor:pointer;
        flex-shrink:0;
        display:flex;align-items:center;justify-content:center;
    }
    .btn-hapus-obat:hover{background:#ffe4e1;}
    .empty-list{color:var(--text-muted);font-size:13.5px;padding:6px 0;}

    .catatan-apoteker-field{margin-bottom:14px;}
    .catatan-apoteker-field label{display:block;font-size:12.5px;font-weight:700;color:var(--text-dark);margin-bottom:6px;}
    .catatan-apoteker-field textarea{
        width:100%;
        min-height:80px;
        padding:12px;
        border:1.5px solid #e2e8f0;
        border-radius:12px;
        font-size:13.5px;
        font-family:inherit;
        resize:vertical;
    }
    .catatan-apoteker-field textarea:focus{outline:none;border-color:var(--mint-500);}

    @media (max-width:900px){
        .grid{grid-template-columns:1fr;}
    }
</style>

<a href="{{ route('apoteker.verifikasi') }}" class="back-link">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Kembali ke daftar verifikasi
</a>

@php
    $badgeClass = match($pesanan->status_resep) {
        'menunggu'    => 'badge-menunggu',
        'disetujui'   => 'badge-disetujui',
        'ditolak'     => 'badge-ditolak',
        default       => 'badge-tidak-perlu',
    };
    $statusLabel = match($pesanan->status_resep) {
        'menunggu'    => 'Menunggu Verifikasi',
        'disetujui'   => 'Disetujui',
        'ditolak'     => 'Ditolak',
        default       => 'Tidak Perlu',
    };
@endphp

<div class="page-header">
    <h1>Verifikasi Resep — {{ $pesanan->user->name ?? '-' }}</h1>
    <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
</div>

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@if (session('shift_error'))
    <div style="background:#fff1f0;border:1px solid #ffccc7;color:#cf1322;padding:10px 14px;border-radius:12px;font-size:13px;margin-bottom:14px;font-weight:600;">
        ⚠ {{ session('shift_error') }}
    </div>
@endif

<div class="grid">
    <div>
    <div class="card">
    <h3>Informasi Pelanggan</h3>
    <div class="info-row">
        <span class="label">Nama Pelanggan</span>
        <span class="value">{{ $pesanan->user->name ?? '-' }}</span>
    </div>
    <div class="info-row">
        <span class="label">Tanggal Lahir</span>
        <span class="value">
            @if ($pesanan->user->tanggal_lahir)
                {{ \Carbon\Carbon::parse($pesanan->user->tanggal_lahir)->translatedFormat('d F Y') }}
                ({{ \Carbon\Carbon::parse($pesanan->user->tanggal_lahir)->age }} tahun)
            @else
                Belum diisi
            @endif
        </span>
    </div>
    <div class="info-row">
        <span class="label">Email</span>
        <span class="value">{{ $pesanan->user->email ?? '-' }}</span>
    </div>
</div>

@if ($pesanan->catatan)
<div class="card">
    <h3>Catatan dari Pelanggan</h3>
    <p style="font-size:14px;color:var(--text-dark);line-height:1.6;background:var(--mint-50);padding:14px 16px;border-radius:14px;">
        {{ $pesanan->catatan }}
    </p>
</div>
@endif

        <div class="card">
            <h3>Obat pada Pesanan Ini</h3>

            @if ($pesanan->status_resep === 'menunggu')
                <form method="POST" action="{{ route('apoteker.verifikasi.tambah-obat', $pesanan->id) }}" class="add-obat-form" id="tambahObatForm">
                    @csrf
                    <div id="obatRows">
                        <div class="obat-row">
                            <div class="field">
                                <label>Pilih Obat (sesuai resep)</label>
                                <select name="obat_id[]" required>
                                    <option value="">— Pilih obat —</option>
                                    @foreach ($daftarObat as $obatItem)
                                        <option value="{{ $obatItem->id }}">
                                            {{ $obatItem->nama }} — Rp{{ number_format($obatItem->harga, 0, ',', '.') }} (stok {{ $obatItem->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field" style="max-width:100px;">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah[]" min="1" value="1" required>
                            </div>
                            <button type="button" class="btn-hapus-obat btn-remove-row" title="Hapus baris ini">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    <div style="display:flex; gap:10px; margin-top:4px;">
                        <button type="button" id="btnAddRow" class="btn-tambah-obat outline">+ Baris Obat Lain</button>
                        <button type="submit" class="btn-tambah-obat">Tambahkan Semua ke Pesanan</button>
                    </div>
                </form>

                <script>
                (function(){
                    const rowsContainer = document.getElementById('obatRows');
                    const btnAddRow = document.getElementById('btnAddRow');
                    if (!rowsContainer || !btnAddRow) return;

                    function bindRemove(row){
                        const btn = row.querySelector('.btn-remove-row');
                        btn.addEventListener('click', () => {
                            if (rowsContainer.children.length > 1) row.remove();
                        });
                    }

                    btnAddRow.addEventListener('click', () => {
                        const firstRow = rowsContainer.querySelector('.obat-row');
                        const newRow = firstRow.cloneNode(true);
                        newRow.querySelectorAll('select, input').forEach(el => {
                            if (el.tagName === 'SELECT') el.selectedIndex = 0;
                            if (el.tagName === 'INPUT') el.value = 1;
                        });
                        rowsContainer.appendChild(newRow);
                        bindRemove(newRow);
                    });

                    bindRemove(rowsContainer.querySelector('.obat-row'));
                })();
                </script>
            @endif

            @forelse ($pesanan->detailPesanan as $detail)
                <div class="obat-list-item">
                    <div>
                        <div class="obat-name">{{ $detail->obat->nama ?? 'Obat dihapus' }}</div>
                        <div class="qty-price">
                            {{ $detail->jumlah }} pcs &times; Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}
                        </div>
                        <div class="flag-tags">
                            @if ($detail->obat && $detail->obat->perluResep())
                                <span class="tag tag-resep">Wajib Resep Dokter</span>
                            @endif
                            @if ($detail->obat && $detail->obat->butuh_ktp)
                                <span class="tag tag-ktp">Verifikasi KTP / Status Nikah</span>
                            @endif
                        </div>
                        <span style="font-size:13px;color:var(--text-muted);">{{ $detail->jumlah }} pcs</span>
                    </div>
                    @if ($pesanan->status_resep === 'menunggu')
                        <form method="POST" action="{{ route('apoteker.verifikasi.hapus-obat', [$pesanan->id, $detail->id]) }}" onsubmit="return confirm('Hapus obat ini dari pesanan?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-hapus-obat" title="Hapus obat ini">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14Z"/></svg>
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="empty-list">Belum ada obat pada pesanan ini. Tambahkan sesuai isi resep di atas.</p>
            @endforelse
        </div>

        <div class="card">
            <h3>Resep Dokter</h3>
            @if ($pesanan->resep_path)
                <div class="doc-preview">
                    <div class="doc-label">📄 {{ basename($pesanan->resep_path) }}</div>
                    <img src="{{ asset('storage/' . $pesanan->resep_path) }}" alt="Foto Resep" class="doc-zoomable" onclick="openImgModal(this.src, this.alt)">
                </div>
            @else
                <div class="no-doc">Pelanggan belum mengunggah resep.</div>
            @endif
        </div>

        <div class="card">
            <h3>Foto KTP / Bukti Status Nikah</h3>
            @if ($pesanan->ktp_path)
                <div class="doc-preview">
                    <div class="doc-label">🪪 {{ basename($pesanan->ktp_path) }}</div>
                    <img src="{{ asset('storage/' . $pesanan->ktp_path) }}" alt="Foto KTP" class="doc-zoomable" onclick="openImgModal(this.src, this.alt)">
                </div>
            @else
                <div class="no-doc">Pelanggan belum mengunggah KTP.</div>
            @endif
        </div>
    </div>

    <div>
        <div class="card">
            <h3>Aksi Verifikasi</h3>

            @if ($pesanan->status_resep === 'menunggu')
                <div class="action-buttons">
                    @if (auth()->user()->isOnShiftNow())
                        <form method="POST" action="{{ route('apoteker.verifikasi.setujui', $pesanan->id) }}">
                            @csrf
                            <div class="catatan-apoteker-field">
                                <label>Catatan Apoteker (obat yang dibaca dari resep, dosis, dsb — opsional)</label>
                                <textarea name="catatan_apoteker" placeholder="Contoh: Sesuai resep dr. Andi, Amoxicillin 500mg 3x1 selama 5 hari.">{{ old('catatan_apoteker', $pesanan->catatan_apoteker) }}</textarea>
                            </div>
                            <button type="submit" class="btn-action btn-setuju">✓ Setujui</button>
                            <button type="submit" formaction="{{ route('apoteker.verifikasi.tolak', $pesanan->id) }}" onclick="return confirm('Yakin ingin menolak dokumen ini? Pesanan akan otomatis dibatalkan.');" class="btn-action btn-tolak" style="margin-top:10px;">✕ Tolak</button>
                        </form>
                    @else
                        <button type="button" class="btn-action btn-setuju" disabled style="opacity:.5;cursor:not-allowed;" title="Kamu sedang di luar jam shift">
                            ✓ Setujui (Di luar jam shift)
                        </button>
                        <button type="button" class="btn-action btn-tolak" disabled style="opacity:.5;cursor:not-allowed;" title="Kamu sedang di luar jam shift">
                            ✕ Tolak (Di luar jam shift)
                        </button>
                    @endif
                </div>
            @elseif ($pesanan->status_resep === 'disetujui')
                <div class="status-note">
                    Dokumen sudah disetujui. Pesanan diteruskan ke kurir untuk pengiriman.
                    @if ($pesanan->catatan_apoteker)
                        <br><br><strong>Catatan:</strong> {{ $pesanan->catatan_apoteker }}
                    @endif
                </div>
            @elseif ($pesanan->status_resep === 'ditolak')
                <div class="status-note">
                    Dokumen ditolak. Pesanan ini telah dibatalkan otomatis.
                    @if ($pesanan->catatan_apoteker)
                        <br><br><strong>Alasan:</strong> {{ $pesanan->catatan_apoteker }}
                    @endif
                </div>
            @else
                <div class="status-note">Pesanan ini tidak memerlukan verifikasi resep/dokumen khusus.</div>
            @endif
        </div>
    </div>
</div>

{{-- Modal preview gambar resep/KTP: zoom in/out + geser (pan) --}}
<div class="img-modal-backdrop" id="imgModal">
    <div class="img-modal-toolbar">
        <button type="button" id="imgZoomOut" title="Perkecil">−</button>
        <span class="img-modal-zoom-level" id="imgZoomLevel">100%</span>
        <button type="button" id="imgZoomIn" title="Perbesar">+</button>
        <button type="button" id="imgZoomReset" title="Reset ukuran">⟳</button>
        <button type="button" id="imgModalClose" title="Tutup">✕</button>
    </div>
    <div class="img-modal-viewport" id="imgViewport">
        <img id="imgModalImg" src="" alt="">
    </div>
</div>

<script>
(function(){
    const modal      = document.getElementById('imgModal');
    const viewport   = document.getElementById('imgViewport');
    const img        = document.getElementById('imgModalImg');
    const zoomLevel  = document.getElementById('imgZoomLevel');
    const btnIn      = document.getElementById('imgZoomIn');
    const btnOut     = document.getElementById('imgZoomOut');
    const btnReset   = document.getElementById('imgZoomReset');
    const btnClose   = document.getElementById('imgModalClose');

    let scale = 1, posX = 0, posY = 0;
    let dragging = false, startX = 0, startY = 0;

    const MIN_SCALE = 0.5, MAX_SCALE = 5, STEP = 0.25;

    window.openImgModal = function(src, alt){
        img.src = src;
        img.alt = alt || '';
        scale = 1; posX = 0; posY = 0;
        applyTransform();
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
    };

    function closeModal(){
        modal.classList.remove('open');
        document.body.style.overflow = '';
        img.src = '';
    }

    function applyTransform(){
        img.style.transform = `translate(${posX}px, ${posY}px) scale(${scale})`;
        zoomLevel.textContent = Math.round(scale * 100) + '%';
    }

    function setScale(newScale){
        scale = Math.min(MAX_SCALE, Math.max(MIN_SCALE, newScale));
        if (scale === 1) { posX = 0; posY = 0; }
        applyTransform();
    }

    btnIn.addEventListener('click', () => setScale(scale + STEP));
    btnOut.addEventListener('click', () => setScale(scale - STEP));
    btnReset.addEventListener('click', () => setScale(1));
    btnClose.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (!modal.classList.contains('open')) return;
        if (e.key === 'Escape') closeModal();
        if (e.key === '+' || e.key === '=') setScale(scale + STEP);
        if (e.key === '-') setScale(scale - STEP);
    });

    // Zoom pakai scroll mouse
    viewport.addEventListener('wheel', (e) => {
        if (!modal.classList.contains('open')) return;
        e.preventDefault();
        setScale(scale + (e.deltaY < 0 ? STEP : -STEP));
    }, { passive: false });

    // Geser gambar (pan) saat sudah di-zoom, dengan klik-tahan-tarik
    viewport.addEventListener('mousedown', (e) => {
        if (scale <= 1) return;
        dragging = true;
        viewport.classList.add('dragging');
        startX = e.clientX - posX;
        startY = e.clientY - posY;
    });
    window.addEventListener('mousemove', (e) => {
        if (!dragging) return;
        posX = e.clientX - startX;
        posY = e.clientY - startY;
        applyTransform();
    });
    window.addEventListener('mouseup', () => {
        dragging = false;
        viewport.classList.remove('dragging');
    });

    // Double-click untuk cepat zoom in/reset
    img.addEventListener('dblclick', () => {
        setScale(scale > 1 ? 1 : 2);
    });
})();
</script>
@endsection