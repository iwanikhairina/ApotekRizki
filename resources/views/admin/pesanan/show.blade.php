@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $pesanan->id)

@php
    $statusLabel = [
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'diproses'            => 'Diproses',
        'ditolak'             => 'Ditolak',
        'disiapkan'           => 'Disiapkan',
        'siap_dikirim'        => 'Siap Dikirim',
        'dikirim'             => 'Dikirim',
        'selesai'             => 'Selesai',
        'dibatalkan_kurir'    => 'Dibatalkan Kurir',
    ];
    $statusFinal = ['selesai', 'ditolak', 'dibatalkan_kurir'];
@endphp

@section('content')

    <div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
        <div>
            <h1>Pesanan #{{ $pesanan->id }}</h1>
            <p>Dibuat {{ $pesanan->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>
        <a href="{{ route('admin.pesanan.index') }}" style="font-size:0.85rem; color:var(--ink-500);">&larr; Kembali ke daftar</a>
    </div>

    @if($pesanan->status === 'dibatalkan_kurir')
        <div class="flash" style="background:#fecdd3; color:#9f1239; border:1px solid #fda4af;">
            Pesanan ini dibatalkan oleh kurir{{ $pesanan->alasan_batal ? ': "' . $pesanan->alasan_batal . '"' : '.' }} Tugaskan ke kurir lain di bawah, atau proses manual.
        </div>
    @endif

    <div style="display:grid; grid-template-columns:1.4fr 1fr; gap:16px;">

        {{-- ===== KOLOM KIRI: ITEM & INFO PESANAN ===== --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            <div class="card">
                <h3 style="font-size:1rem; margin-bottom:14px;">Item Pesanan</h3>
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="text-align:left; border-bottom:1px solid var(--mint-100);">
                            <th style="padding:6px 0; font-size:0.76rem; color:var(--ink-500); text-transform:uppercase;">Obat</th>
                            <th style="padding:6px 0; font-size:0.76rem; color:var(--ink-500); text-transform:uppercase; text-align:center;">Jumlah</th>
                            <th style="padding:6px 0; font-size:0.76rem; color:var(--ink-500); text-transform:uppercase; text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan->detailPesanan as $item)
                            <tr style="border-bottom:1px solid var(--mint-50);">
                                <td style="padding:10px 0; font-size:0.88rem; color:var(--ink-900);">{{ $item->obat->nama ?? '(obat dihapus)' }}</td>
                                <td style="padding:10px 0; font-size:0.88rem; text-align:center;">{{ $item->jumlah }}</td>
                                <td style="padding:10px 0; font-size:0.88rem; text-align:right; font-weight:600;">Rp {{ number_format($item->jumlah * $item->harga_satuan, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top:14px; padding-top:14px; border-top:1px solid var(--mint-100); display:flex; flex-direction:column; gap:6px;">
                    <div style="display:flex; justify-content:space-between; font-size:0.86rem; color:var(--ink-700);">
                        <span>Subtotal Obat</span>
                        <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:0.86rem; color:var(--ink-700);">
                        <span>Ongkir ({{ $pesanan->jarak_km ?? '-' }} km)</span>
                        <span>Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:0.98rem; font-weight:700; color:var(--spring-deep); margin-top:4px;">
                        <span>Total</span>
                        <span>Rp {{ number_format($pesanan->totalKeseluruhan(), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="font-size:1rem; margin-bottom:14px;">Informasi Pengiriman</h3>
                <div style="display:flex; flex-direction:column; gap:8px; font-size:0.86rem;">
                    <div><span style="color:var(--ink-500);">Alamat:</span> {{ $pesanan->alamat }}</div>
                    <div><span style="color:var(--ink-500);">Metode Pembayaran:</span> {{ strtoupper($pesanan->metode_pembayaran ?? '-') }}</div>
                    @if($pesanan->jadwalPengantaranLabel())
                        <div><span style="color:var(--ink-500);">Jadwal Pengantaran:</span> <strong style="color:var(--spring-deep);">{{ $pesanan->jadwalPengantaranLabel() }}</strong></div>
                    @endif
                    @if($pesanan->catatan)
                        <div><span style="color:var(--ink-500);">Catatan:</span> {{ $pesanan->catatan }}</div>
                    @endif
                    @if($pesanan->waktu_diambil)
                        <div><span style="color:var(--ink-500);">Diambil Kurir:</span> {{ $pesanan->waktu_diambil->translatedFormat('d M Y, H:i') }}</div>
                    @endif
                    @if($pesanan->resep_path)
                        <div><a href="{{ Storage::url($pesanan->resep_path) }}" target="_blank" style="color:var(--mint-700); font-weight:600;">Lihat foto resep &rarr;</a></div>
                    @endif
                    @if($pesanan->ktp_path)
                        <div><a href="{{ Storage::url($pesanan->ktp_path) }}" target="_blank" style="color:var(--mint-700); font-weight:600;">Lihat foto KTP &rarr;</a></div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ===== KOLOM KANAN: STATUS & AKSI ===== --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            <div class="card">
                <h3 style="font-size:1rem; margin-bottom:10px;">Status Saat Ini</h3>
                <span style="display:inline-block; padding:6px 14px; border-radius:999px; font-size:0.85rem; font-weight:700; background:var(--mint-50); color:var(--spring-deep);">
                    {{ $statusLabel[$pesanan->status] ?? $pesanan->status }}
                </span>

                <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--mint-100);">
                    <div style="font-size:0.82rem; color:var(--ink-500); margin-bottom:4px;">Pelanggan</div>
                    <div style="font-weight:600; font-size:0.9rem;">{{ $pesanan->user->name ?? '-' }}</div>
                    <div style="font-size:0.8rem; color:var(--ink-500);">{{ $pesanan->user->phone ?? '-' }}</div>
                </div>

                <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--mint-100);">
                    <div style="font-size:0.82rem; color:var(--ink-500); margin-bottom:4px;">Kurir</div>
                    <div style="font-weight:600; font-size:0.9rem;">{{ $pesanan->kurir->name ?? 'Belum ditugaskan' }}</div>
                </div>
            </div>

            {{-- Tugaskan ulang kurir --}}
            @if(! in_array($pesanan->status, $statusFinal, true) && $pesanan->status !== 'menunggu_verifikasi')
                <div class="card">
                    <h3 style="font-size:1rem; margin-bottom:10px;">Tugaskan / Ganti Kurir</h3>
                    <form method="POST" action="{{ route('admin.pesanan.assign-kurir', $pesanan->id) }}">
                        @csrf
                        <select name="kurir_id" required style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.88rem; margin-bottom:10px; background:#fff;">
                            <option value="">Pilih Kurir</option>
                            @foreach($daftarKurir as $k)
                                <option value="{{ $k->id }}" {{ $pesanan->kurir_id === $k->id ? 'selected' : '' }}>{{ $k->name }} ({{ ucfirst($k->shift) }})</option>
                            @endforeach
                        </select>
                        <button type="submit" style="width:100%; background:var(--mint-500); color:#fff; border:none; padding:10px; border-radius:10px; font-weight:600; font-size:0.86rem; cursor:pointer;">
                            Tugaskan Kurir
                        </button>
                    </form>
                </div>
            @endif

            {{-- Ubah status manual --}}
            <div class="card">
                <h3 style="font-size:1rem; margin-bottom:10px;">Ubah Status Manual</h3>
                <form method="POST" action="{{ route('admin.pesanan.update-status', $pesanan->id) }}">
                    @csrf
                    <select name="status" required style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.88rem; margin-bottom:10px; background:#fff;">
                        @foreach($statusLabel as $key => $label)
                            <option value="{{ $key }}" {{ $pesanan->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" style="width:100%; background:var(--surface); color:var(--ink-700); border:1px solid var(--mint-100); padding:10px; border-radius:10px; font-weight:600; font-size:0.86rem; cursor:pointer;">
                        Simpan Status
                    </button>
                </form>
            </div>

            {{-- Hapus dari histori --}}
            @if(in_array($pesanan->status, $statusFinal, true))
                <div class="card" style="border-color:#fecaca;">
                    <h3 style="font-size:1rem; margin-bottom:8px; color:#b91c1c;">Hapus dari Histori</h3>
                    <p style="font-size:0.82rem; color:var(--ink-500); margin-bottom:12px;">Tindakan ini permanen dan tidak bisa dibatalkan.</p>
                    <form method="POST" action="{{ route('admin.pesanan.destroy', $pesanan->id) }}" onsubmit="return confirm('Hapus pesanan #{{ $pesanan->id }} secara permanen dari histori?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width:100%; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:10px; border-radius:10px; font-weight:600; font-size:0.86rem; cursor:pointer;">
                            Hapus Pesanan Ini
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>

@endsection