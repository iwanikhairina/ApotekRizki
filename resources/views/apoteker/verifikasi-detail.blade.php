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
            <h3>Obat yang Membutuhkan Verifikasi</h3>
            @forelse ($pesanan->detailPesanan as $detail)
                @if ($detail->obat && ($detail->obat->perluResep() || $detail->obat->butuh_ktp))
                    <div class="obat-flag-item">
                        <div>
                            <div class="obat-name">{{ $detail->obat->nama }}</div>
                            <div class="flag-tags">
                                @if ($detail->obat->perluResep())
                                    <span class="tag tag-resep">Wajib Resep Dokter</span>
                                @endif
                                @if ($detail->obat->butuh_ktp)
                                    <span class="tag tag-ktp">Verifikasi KTP / Status Nikah</span>
                                @endif
                            </div>
                        </div>
                        <span style="font-size:13px;color:var(--text-muted);">{{ $detail->jumlah }} pcs</span>
                    </div>
                @endif
            @empty
                <p style="font-size:14px;color:var(--text-muted);">Tidak ada obat yang membutuhkan verifikasi khusus.</p>
            @endforelse
        </div>

        <div class="card">
            <h3>Resep Dokter</h3>
            @if ($pesanan->resep_path)
                <div class="doc-preview">
                    <div class="doc-label">📄 {{ basename($pesanan->resep_path) }}</div>
                    <img src="{{ asset('storage/' . $pesanan->resep_path) }}" alt="Foto Resep">
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
                    <img src="{{ asset('storage/' . $pesanan->ktp_path) }}" alt="Foto KTP">
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
                <button type="submit" class="btn-action btn-setuju">✓ Setujui</button>
            </form>
            <form method="POST" action="{{ route('apoteker.verifikasi.tolak', $pesanan->id) }}" onsubmit="return confirm('Yakin ingin menolak dokumen ini? Pesanan akan otomatis dibatalkan.');">
                @csrf
                <button type="submit" class="btn-action btn-tolak">✕ Tolak</button>
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
                <div class="status-note">Dokumen sudah disetujui. Pesanan dapat dilanjutkan ke proses berikutnya.</div>
            @elseif ($pesanan->status_resep === 'ditolak')
                <div class="status-note">Dokumen ditolak. Pesanan ini telah dibatalkan otomatis.</div>
            @else
                <div class="status-note">Pesanan ini tidak memerlukan verifikasi resep/dokumen khusus.</div>
            @endif
        </div>
    </div>
</div>
@endsection