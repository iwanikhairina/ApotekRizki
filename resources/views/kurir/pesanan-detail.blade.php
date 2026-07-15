@extends('layouts.kurir')

@section('title', 'Detail Pesanan')

@section('content')
<style>
    .back-link{display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);text-decoration:none;font-size:14px;font-weight:600;margin-bottom:18px;}
    .back-link:hover{color:var(--mint-700);}
    .page-header{margin-bottom:24px;}
    .page-header h1{font-family:'Outfit',sans-serif;font-size:24px;font-weight:700;}
    .alert-error{background:#fff1f0;border:1px solid #ffccc7;color:#cf1322;padding:12px 16px;border-radius:12px;font-size:14px;margin-bottom:20px;font-weight:600;max-width:900px;}

    .wrapper{max-width:900px;}
    .grid{
        display:grid;
        grid-template-columns:1.3fr 1fr;
        gap:20px;
        align-items:start;
    }

    .card{background:#fff;border-radius:20px;padding:24px;box-shadow:0 8px 24px rgba(15,47,34,.06);margin-bottom:20px;}
    .card h3{font-family:'Outfit',sans-serif;font-size:16px;font-weight:700;margin-bottom:16px;color:var(--text-dark);}
    .info-row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f4f2;font-size:14px;}
    .info-row:last-child{border-bottom:none;}
    .info-row .label{color:var(--text-muted);}
    .info-row .value{font-weight:600;color:var(--text-dark);text-align:right;}

    .maps-frame{width:100%;height:260px;border:none;border-radius:14px;margin-top:14px;}
    .maps-link{display:inline-flex;align-items:center;gap:6px;background:var(--blue-soft);color:#3b82f6;text-decoration:none;font-size:12.5px;font-weight:700;padding:8px 16px;border-radius:999px;transition:.15s ease;margin-top:10px;}
    .maps-link:hover{background:#dbeafe;}

    .phone-link{color:var(--mint-700);text-decoration:none;font-weight:700;display:inline-flex;align-items:center;gap:5px;}
    .phone-link:hover{text-decoration:underline;}

    .obat-item{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f4f2;font-size:14px;}
    .obat-item:last-child{border-bottom:none;}

    .ongkir-badge{display:inline-block;font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px;background:var(--mint-50);color:var(--mint-700);}
    .ongkir-badge.warning{background:#fff7e6;color:var(--amber);}

    .total-row{display:flex;justify-content:space-between;padding-top:14px;margin-top:6px;border-top:2px solid var(--mint-100);font-size:15px;font-weight:800;color:var(--text-dark);}

    .cod-box{background:linear-gradient(135deg, var(--mint-500), var(--mint-700));border-radius:16px;padding:20px 22px;color:#fff;margin-top:14px;}
    .cod-box .cod-label{font-size:12.5px;opacity:.9;font-weight:600;margin-bottom:4px;display:flex;align-items:center;gap:6px;}
    .cod-box .cod-amount{font-family:'Outfit',sans-serif;font-size:26px;font-weight:800;}
    .cod-box .cod-note{font-size:11.5px;opacity:.8;margin-top:6px;}
    .transfer-box{background:var(--blue-soft);border:1px solid #bfdbfe;border-radius:16px;padding:16px 20px;margin-top:14px;color:#1d4ed8;font-size:13.5px;font-weight:600;}

    .sticky-col{position:sticky;top:90px;}

    .btn-action{border:none;padding:14px;border-radius:14px;font-size:14px;font-weight:700;font-family:'Outfit',sans-serif;cursor:pointer;width:100%;background:var(--mint-500);color:#fff;}
    .btn-action:hover{background:var(--mint-700);}
    .btn-action:disabled{opacity:.5;cursor:not-allowed;}

    @media (max-width:900px){
        .grid{grid-template-columns:1fr;}
        .sticky-col{position:static;}
    }
</style>

<a href="{{ route('kurir.pesanan') }}" class="back-link">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Kembali ke daftar pesanan
</a>

<div class="wrapper">
    @if (session('shift_error'))
        <div class="alert-error">⚠ {{ session('shift_error') }}</div>
    @endif
</div>

<div class="page-header">
    <h1>Detail Pesanan P{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</h1>
</div>

<div class="wrapper grid">
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
                    @if ($pesanan->user->phone ?? null)
                        <a href="tel:{{ $pesanan->user->phone }}" class="phone-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
                            {{ $pesanan->user->phone }}
                        </a>
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
                <span class="value">{{ $pesanan->metode_pembayaran ?? '-' }}</span>
            </div>
            <div class="info-row">
    <span class="label">Estimasi Waktu Tempuh</span>
    <span class="value" style="color:var(--mint-700);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        ± {{ $pesanan->hitungEstimasiMenit() }} menit
        @if ($pesanan->jarak_km)
            <span style="color:var(--text-muted);font-weight:500;">({{ $pesanan->jarak_km }} km)</span>
        @endif
    </span>
</div>

            @if ($pesanan->googleMapsEmbedUrl())
                <iframe class="maps-frame" src="{{ $pesanan->googleMapsEmbedUrl() }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <a href="{{ $pesanan->googleMapsDirectionUrl() }}" target="_blank" class="maps-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    Buka Rute di Aplikasi Google Maps
                </a>
            @endif
        </div>

        <div class="card">
            <h3>Daftar Obat</h3>
            @forelse ($pesanan->detailPesanan as $detail)
                <div class="obat-item">
                    <span>{{ $detail->obat->nama ?? '-' }}</span>
                    <span>{{ $detail->jumlah }} pcs</span>
                </div>
            @empty
                <p style="font-size:14px;color:var(--text-muted);">Tidak ada data obat.</p>
            @endforelse
        </div>
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
                    @elseif ($pesanan->jarak_km > 15)
                        <span class="ongkir-badge warning">{{ $pesanan->ongkirLabel() }}</span>
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

            @if (str_contains(strtolower($pesanan->metode_pembayaran ?? ''), 'cod'))
                <div class="cod-box">
                    <div class="cod-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                        Uang yang harus diterima
                    </div>
                    <div class="cod-amount">Rp{{ number_format($pesanan->totalKeseluruhan(), 0, ',', '.') }}</div>
                    <div class="cod-note">Pastikan uang sesuai sebelum menyerahkan obat.</div>
                </div>
            @else
                <div class="transfer-box">
                    💳 Sudah dibayar via {{ $pesanan->metode_pembayaran ?? 'transfer' }}.
                </div>
            @endif
        </div>

        <div class="card">
            @if (auth()->user()->isOnShiftNow())
                <form method="POST" action="{{ route('kurir.pesanan.ambil', $pesanan->id) }}">
                    @csrf
                    <button type="submit" class="btn-action">🚚 Ambil Pesanan Ini</button>
                </form>
            @else
                <button type="button" class="btn-action" disabled title="Kamu sedang di luar jam shift">
                    🚚 Ambil Pesanan (Di luar jam shift)
                </button>
            @endif
        </div>
    </div>
</div>
@endsection