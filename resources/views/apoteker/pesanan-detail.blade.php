@extends('layouts.apoteker')

@section('title', 'Detail Pesanan')

@section('content')
<style>
    .back-link{display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);text-decoration:none;font-size:14px;font-weight:600;margin-bottom:18px;}
    .back-link:hover{color:var(--mint-700);}

    .page-header{margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;}
    .page-header h1{font-family:'Outfit',sans-serif;font-size:24px;font-weight:700;}

    .alert-success{background:#eafaf3;border:1px solid var(--mint-100);color:var(--mint-700);padding:12px 16px;border-radius:12px;font-size:14px;margin-bottom:20px;font-weight:600;}
    .alert-error{background:#fff1f0;border:1px solid #ffccc7;color:#cf1322;padding:12px 16px;border-radius:12px;font-size:14px;margin-bottom:20px;font-weight:600;}
    .alert-warn{background:var(--amber-bg);border:1px solid #f5d99a;color:#8a5e15;padding:14px 16px;border-radius:12px;font-size:13.5px;margin-bottom:20px;font-weight:600;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
    .alert-warn a{color:#8a5e15;text-decoration:underline;white-space:nowrap;}

    .badge{display:inline-block;padding:6px 14px;border-radius:999px;font-size:13px;font-weight:700;}
    .badge-menunggu{background:var(--amber-bg);color:var(--amber);}
    .badge-diproses{background:var(--blue-soft);color:#3b82f6;}
    .badge-disiapkan{background:#f3e8ff;color:#9333ea;}
    .badge-siap{background:var(--mint-50);color:var(--mint-700);}
    .badge-ditolak{background:#fff1f0;color:#e0433c;}
    .badge-dikirim{background:#e0f2fe;color:#0284c7;}
    .badge-selesai{background:#f0fdf4;color:#16a34a;}

    .grid{display:grid;grid-template-columns:1.4fr 1fr;gap:20px;}
    .card{background:#fff;border-radius:20px;padding:24px;box-shadow:0 8px 24px rgba(15,47,34,.06);margin-bottom:20px;}
    .card h3{font-family:'Outfit',sans-serif;font-size:16px;font-weight:700;margin-bottom:16px;color:var(--text-dark);}
    .info-row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f4f2;font-size:14px;gap:16px;}
    .info-row:last-child{border-bottom:none;}
    .info-row .label{color:var(--text-muted);flex-shrink:0;}
    .info-row .value{font-weight:600;color:var(--text-dark);text-align:right;}

    .phone-link{color:var(--mint-700);text-decoration:none;font-weight:700;}
    .phone-link:hover{text-decoration:underline;}

    .maps-link{display:inline-flex;align-items:center;gap:6px;background:var(--blue-soft);color:#3b82f6;text-decoration:none;font-size:12.5px;font-weight:700;padding:6px 14px;border-radius:999px;transition:.15s ease;margin-top:10px;}
    .maps-link:hover{background:#dbeafe;}

    .obat-item{display:flex;justify-content:space-between;align-items:flex-start;padding:12px 0;border-bottom:1px solid #f0f4f2;font-size:14px;}
    .obat-item:last-child{border-bottom:none;}
    .obat-name{font-weight:700;color:var(--text-dark);}
    .flag-tags{display:flex;gap:6px;margin-top:4px;}
    .tag{font-size:11px;font-weight:700;padding:3px 9px;border-radius:999px;}
    .tag-resep{background:#fff1f0;color:#e0433c;}
    .tag-ktp{background:#eef2ff;color:#6366f1;}

    .ongkir-badge{display:inline-block;font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px;background:var(--mint-50);color:var(--mint-700);}
    .ongkir-badge.warning{background:#fff7e6;color:var(--amber);}

    .total-row{display:flex;justify-content:space-between;padding-top:14px;margin-top:6px;border-top:2px solid var(--mint-100);font-size:15px;font-weight:800;color:var(--text-dark);}

    .doc-preview{text-align:center;}
    .doc-preview img{width:100%;border-radius:14px;border:1px solid #eee;margin-bottom:10px;}
    .doc-label{font-size:13px;font-weight:700;color:var(--text-dark);margin-bottom:10px;}
    .no-doc{text-align:center;padding:24px 10px;color:var(--text-muted);font-size:13px;background:var(--mint-50);border-radius:14px;}

    .action-buttons{display:flex;flex-direction:column;gap:10px;}
    .btn-action{border:none;padding:13px;border-radius:14px;font-size:14px;font-weight:700;font-family:'Outfit',sans-serif;cursor:pointer;transition:.15s ease;width:100%;}
    .btn-terima{background:var(--mint-500);color:#fff;}
    .btn-terima:hover{background:var(--mint-700);}
    .btn-tolak{background:#fff1f0;color:#e0433c;border:1.5px solid #ffd4d0;}
    .btn-tolak:hover{background:#ffe4e1;}
    .btn-action:disabled{opacity:.5;cursor:not-allowed;}

    .status-note{text-align:center;font-size:13.5px;color:var(--text-muted);background:var(--mint-50);padding:14px;border-radius:14px;}

    .sticky-col{position:sticky;top:90px;}

    @media (max-width:900px){
        .grid{grid-template-columns:1fr;}
        .sticky-col{position:static;}
    }
</style>

@php
    $badgeClass = match($pesanan->status) {
        'menunggu_verifikasi' => 'badge-menunggu',
        'diproses'            => 'badge-diproses',
        'disiapkan'           => 'badge-disiapkan',
        'siap_dikirim'        => 'badge-siap',
        'ditolak'             => 'badge-ditolak',
        'dikirim'             => 'badge-dikirim',
        'selesai'             => 'badge-selesai',
        default               => 'badge-menunggu',
    };
    $statusLabel = match($pesanan->status) {
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'diproses'            => 'Diproses',
        'disiapkan'           => 'Obat Disiapkan',
        'siap_dikirim'        => 'Siap Dikirim',
        'ditolak'             => 'Ditolak',
        'dikirim'             => 'Dikirim',
        'selesai'             => 'Selesai',
        default               => ucfirst($pesanan->status),
    };
    $perluVerifikasiDulu = $pesanan->status_resep === 'menunggu';
    $noTelepon = $pesanan->user->no_telepon ?? $pesanan->user->phone ?? null;
@endphp

<a href="{{ route('apoteker.pesanan') }}" class="back-link">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Kembali ke daftar pesanan
</a>

<div class="page-header">
    <h1>Pesanan P{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</h1>
    <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
</div>

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@if (session('shift_error'))
    <div class="alert-error">⚠ {{ session('shift_error') }}</div>
@endif

@if ($perluVerifikasiDulu)
    <div class="alert-warn">
        <span>⚠ Pesanan ini punya resep/KTP yang belum diverifikasi. Verifikasi dulu sebelum bisa diterima.</span>
        <a href="{{ route('apoteker.verifikasi.detail', $pesanan->id) }}">Buka Verifikasi &rarr;</a>
    </div>
@endif

<div class="grid">
    <div>
        <div class="card">
            <h3>Informasi Pelanggan</h3>
            <div class="info-row">
                <span class="label">Nama</span>
                <span class="value">{{ $pesanan->user->name ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="label">No. HP</span>
                <span class="value">
                    @if ($noTelepon)
                        <a href="tel:{{ $noTelepon }}" class="phone-link">{{ $noTelepon }}</a>
                    @else
                        Tidak tersedia
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="label">Alamat</span>
                <span class="value">{{ $pesanan->alamat ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Metode Pembayaran</span>
                <span class="value">
                    @if ($pesanan->metode_pembayaran === 'cod')
                        COD (Bayar di Tempat)
                    @elseif ($pesanan->metode_pembayaran === 'qris')
                        Transfer QRIS (BSI)
                    @else
                        {{ $pesanan->metode_pembayaran ?? '-' }}
                    @endif
                </span>
            </div>
            @if ($pesanan->alamat)
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($pesanan->alamat) }}" target="_blank" class="maps-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    Lihat di Google Maps
                </a>
            @endif
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
            <h3>Daftar Obat</h3>
            @forelse ($pesanan->detailPesanan as $detail)
                <div class="obat-item">
                    <div>
                        <div class="obat-name">{{ $detail->obat->nama ?? 'Produk dihapus' }}</div>
                        @if ($detail->obat && ($detail->obat->perluResep() || $detail->obat->butuh_ktp))
                            <div class="flag-tags">
                                @if ($detail->obat->perluResep())<span class="tag tag-resep">Resep</span>@endif
                                @if ($detail->obat->butuh_ktp)<span class="tag tag-ktp">KTP</span>@endif
                            </div>
                        @endif
                    </div>
                    <div style="text-align:right;">
                        <div>{{ $detail->jumlah }} pcs</div>
                        <div style="color:var(--text-muted);font-size:12.5px;">Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}/pcs</div>
                    </div>
                </div>
            @empty
                <p style="font-size:14px;color:var(--text-muted);">Tidak ada data obat.</p>
            @endforelse
        </div>

        @if ($pesanan->resep_path || $pesanan->ktp_path)
            <div class="card">
                <h3>Dokumen Terlampir</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="doc-preview">
                        <div class="doc-label">📄 Resep Dokter</div>
                        @if ($pesanan->resep_path)
                            <img src="{{ asset('storage/' . $pesanan->resep_path) }}" alt="Foto Resep">
                        @else
                            <div class="no-doc">Tidak ada</div>
                        @endif
                    </div>
                    <div class="doc-preview">
                        <div class="doc-label">🪪 KTP</div>
                        @if ($pesanan->ktp_path)
                            <img src="{{ asset('storage/' . $pesanan->ktp_path) }}" alt="Foto KTP">
                        @else
                            <div class="no-doc">Tidak ada</div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="sticky-col">
        <div class="card">
            <h3>Rincian Biaya</h3>
            <div class="info-row">
                <span class="label">Total Harga Obat</span>
                <span class="value">Rp{{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Ongkos Kirim</span>
                <span class="value">
                    @if (is_null($pesanan->jarak_km))
                        <span class="ongkir-badge warning">Jarak belum diatur</span>
                    @else
                        Rp{{ number_format($pesanan->ongkir ?? 0, 0, ',', '.') }}
                        <span class="ongkir-badge">{{ $pesanan->ongkirLabel() }}</span>
                    @endif
                </span>
            </div>
            <div class="total-row">
                <span>Total</span>
                <span>Rp{{ number_format($pesanan->totalKeseluruhan(), 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="card">
            <h3>Aksi</h3>

            @if ($pesanan->status === 'menunggu_verifikasi')
                <div class="action-buttons">
                    <form method="POST" action="{{ route('apoteker.pesanan.terima', $pesanan->id) }}">
                        @csrf
                        <button type="submit" class="btn-action btn-terima" {{ $perluVerifikasiDulu ? 'disabled' : '' }}>
                            ✓ Terima Pesanan
                        </button>
                    </form>
                    <form method="POST" action="{{ route('apoteker.pesanan.tolak', $pesanan->id) }}" onsubmit="return confirm('Yakin ingin menolak pesanan ini?');">
                        @csrf
                        <button type="submit" class="btn-action btn-tolak">✕ Tolak Pesanan</button>
                    </form>
                </div>
                @if ($perluVerifikasiDulu)
                    <p style="font-size:12.5px;color:var(--text-muted);margin-top:10px;text-align:center;">Verifikasi resep/KTP dulu sebelum bisa diterima.</p>
                @endif
            @elseif ($pesanan->status === 'diproses')
                <form method="POST" action="{{ route('apoteker.pesanan.proses', $pesanan->id) }}">
                    @csrf
                    <button type="submit" class="btn-action btn-terima">📦 Tandai Obat Sudah Disiapkan</button>
                </form>
            @elseif ($pesanan->status === 'disiapkan')
                <form method="POST" action="{{ route('apoteker.pesanan.siapdikirim', $pesanan->id) }}">
                    @csrf
                    <button type="submit" class="btn-action btn-terima">🚚 Tandai Siap Dikirim</button>
                </form>
            @elseif ($pesanan->status === 'siap_dikirim')
                <div class="status-note">Pesanan siap dikirim, menunggu kurir mengambil.</div>
            @elseif ($pesanan->status === 'dikirim')
                <div class="status-note">Pesanan sedang diantar kurir ke pelanggan.</div>
            @elseif ($pesanan->status === 'selesai')
                <div class="status-note">Pesanan telah selesai diterima pelanggan.</div>
            @elseif ($pesanan->status === 'ditolak')
                <div class="status-note">Pesanan ini telah ditolak.</div>
            @elseif ($pesanan->status === 'dibatalkan_kurir')
                <div class="status-note">Pengiriman dibatalkan kurir. Cek menu Admin &gt; Pesanan untuk menugaskan ulang.</div>
            @endif
        </div>
    </div>
</div>
@endsection