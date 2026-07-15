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
    $statusColor = [
        'menunggu_verifikasi' => ['#fef3c7', '#92400e'],
        'diproses'            => ['#dbeafe', '#1e40af'],
        'ditolak'             => ['#fee2e2', '#b91c1c'],
        'disiapkan'           => ['#ede9fe', '#6d28d9'],
        'siap_dikirim'        => ['#cffafe', '#0e7490'],
        'dikirim'             => ['#dbeafe', '#0369a1'],
        'selesai'             => ['#dcfce7', '#166534'],
        'dibatalkan_kurir'    => ['#fecdd3', '#9f1239'],
    ];
@endphp

@section('content')

    <div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
        <div>
            <a href="{{ route('admin.pesanan.index') }}" style="font-size:0.82rem; color:var(--ink-500); text-decoration:none;">&larr; Kembali ke daftar pesanan</a>
            <h1 style="margin-top:6px;">Pesanan #{{ $pesanan->id }}</h1>
            <p>Dibuat {{ $pesanan->created_at->translatedFormat('d M Y, H:i') }}</p>
        </div>
        <span style="background:{{ $statusColor[$pesanan->status][0] ?? '#f1f5f9' }}; color:{{ $statusColor[$pesanan->status][1] ?? '#334155' }}; padding:6px 16px; border-radius:999px; font-size:0.85rem; font-weight:600;">
            {{ $statusLabel[$pesanan->status] ?? $pesanan->status }}
        </span>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display:grid; grid-template-columns:1.6fr 1fr; gap:16px; align-items:start;">

        {{-- ===== KOLOM KIRI: ITEM & INFO ===== --}}
        <div>
            {{-- Item pesanan --}}
            <div class="card" style="padding:0; overflow:hidden; margin-bottom:16px;">
                <div style="padding:16px; border-bottom:1px solid var(--mint-100);">
                    <h3 style="font-size:1rem;">Rincian Item</h3>
                </div>
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:var(--mint-50); text-align:left;">
                            <th style="padding:10px 16px; font-size:0.75rem; color:var(--ink-500); text-transform:uppercase;">Obat</th>
                            <th style="padding:10px 16px; font-size:0.75rem; color:var(--ink-500); text-transform:uppercase; text-align:center;">Jumlah</th>
                            <th style="padding:10px 16px; font-size:0.75rem; color:var(--ink-500); text-transform:uppercase; text-align:right;">Harga Satuan</th>
                            <th style="padding:10px 16px; font-size:0.75rem; color:var(--ink-500); text-transform:uppercase; text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan->detailPesanan as $item)
                            <tr style="border-top:1px solid var(--mint-100);">
                                <td style="padding:12px 16px; font-size:0.85rem; color:var(--ink-900); font-weight:600;">{{ $item->obat->nama ?? 'Obat tidak ditemukan' }}</td>
                                <td style="padding:12px 16px; font-size:0.85rem; text-align:center;">{{ $item->jumlah }}</td>
                                <td style="padding:12px 16px; font-size:0.85rem; text-align:right;">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td style="padding:12px 16px; font-size:0.85rem; text-align:right; font-weight:600;">Rp {{ number_format($item->jumlah * $item->harga_satuan, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding:24px 16px; text-align:center; color:var(--ink-500); font-size:0.85rem;">Tidak ada item.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        @if(isset($pesanan->ongkir))
                        <tr style="border-top:1px solid var(--mint-100);">
                            <td colspan="3" style="padding:10px 16px; text-align:right; font-size:0.82rem; color:var(--ink-500);">Ongkos Kirim</td>
                            <td style="padding:10px 16px; text-align:right; font-size:0.85rem;">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr style="border-top:1px solid var(--mint-100); background:var(--mint-50);">
                            <td colspan="3" style="padding:12px 16px; text-align:right; font-size:0.88rem; font-weight:700; color:var(--ink-900);">Total</td>
                            <td style="padding:12px 16px; text-align:right; font-size:0.95rem; font-weight:700; color:var(--spring-deep);">Rp {{ number_format($pesanan->totalKeseluruhan(), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Info pelanggan & pengiriman --}}
            <div class="card">
                <h3 style="font-size:1rem; margin-bottom:14px;">Info Pelanggan & Pengiriman</h3>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
                    <div>
                        <div style="font-size:0.75rem; color:var(--ink-500); text-transform:uppercase; margin-bottom:4px;">Nama Pelanggan</div>
                        <div style="font-size:0.88rem; font-weight:600; color:var(--ink-900);">{{ $pesanan->user->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:var(--ink-500); text-transform:uppercase; margin-bottom:4px;">Telepon</div>
                        <div style="font-size:0.88rem; color:var(--ink-700);">{{ $pesanan->user->phone ?? $pesanan->user->no_hp ?? '-' }}</div>
                    </div>
                    @if(isset($pesanan->alamat_pengiriman) || isset($pesanan->alamat))
                    <div style="grid-column:1 / -1;">
                        <div style="font-size:0.75rem; color:var(--ink-500); text-transform:uppercase; margin-bottom:4px;">Alamat Pengiriman</div>
                        <div style="font-size:0.88rem; color:var(--ink-700);">{{ $pesanan->alamat_pengiriman ?? $pesanan->alamat ?? '-' }}</div>
                    </div>
                    @endif
                    @if(isset($pesanan->catatan) && $pesanan->catatan)
                    <div style="grid-column:1 / -1;">
                        <div style="font-size:0.75rem; color:var(--ink-500); text-transform:uppercase; margin-bottom:4px;">Catatan</div>
                        <div style="font-size:0.88rem; color:var(--ink-700);">{{ $pesanan->catatan }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== KOLOM KANAN: AKSI OWNER ===== --}}
        <div>
            {{-- Kurir saat ini / assign kurir --}}
            <div class="card" style="margin-bottom:16px;">
                <h3 style="font-size:1rem; margin-bottom:14px;">Kurir</h3>

                @if($pesanan->kurir)
                    <div style="display:flex; align-items:center; gap:10px; padding:10px; background:var(--mint-50); border-radius:10px; margin-bottom:14px;">
                        <div style="width:36px; height:36px; border-radius:50%; background:var(--mint-500); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700;">
                            {{ strtoupper(substr($pesanan->kurir->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:600; font-size:0.85rem; color:var(--ink-900);">{{ $pesanan->kurir->name }}</div>
                            <div style="font-size:0.76rem; color:var(--ink-500);">Kurir bertugas</div>
                        </div>
                    </div>
                @else
                    <p style="font-size:0.83rem; color:var(--ink-500); margin-bottom:14px;">Belum ada kurir ditugaskan.</p>
                @endif

                <form method="POST" action="{{ route('admin.pesanan.assign-kurir', $pesanan->id) }}">
                    @csrf
                    <label style="font-size:0.78rem; color:var(--ink-500); font-weight:600; display:block; margin-bottom:6px;">
                        {{ $pesanan->kurir ? 'Tugaskan ulang ke kurir lain' : 'Tugaskan kurir' }}
                    </label>
                    <select name="kurir_id" required style="width:100%; padding:9px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem; margin-bottom:10px;">
                        <option value="">-- Pilih Kurir --</option>
                        @foreach($daftarKurir as $kurir)
                            <option value="{{ $kurir->id }}" {{ $pesanan->kurir_id == $kurir->id ? 'selected' : '' }}>{{ $kurir->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" style="width:100%; padding:10px; border-radius:10px; background:var(--mint-500); color:#fff; border:none; font-size:0.85rem; font-weight:600; cursor:pointer;">
                        Tugaskan Kurir
                    </button>
                </form>
            </div>

            {{-- Ubah status manual --}}
            <div class="card" style="margin-bottom:16px;">
                <h3 style="font-size:1rem; margin-bottom:14px;">Ubah Status</h3>
                <form method="POST" action="{{ route('admin.pesanan.update-status', $pesanan->id) }}">
                    @csrf
                    <select name="status" required style="width:100%; padding:9px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem; margin-bottom:10px;">
                        @foreach($statusLabel as $key => $label)
                            <option value="{{ $key }}" {{ $pesanan->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" style="width:100%; padding:10px; border-radius:10px; background:var(--ink-900); color:#fff; border:none; font-size:0.85rem; font-weight:600; cursor:pointer;">
                        Simpan Status
                    </button>
                </form>
            </div>

            {{-- Hapus pesanan --}}
            <div class="card" style="border:1px solid #fecaca;">
                <h3 style="font-size:1rem; margin-bottom:8px; color:#b91c1c;">Zona Berbahaya</h3>
                <p style="font-size:0.8rem; color:var(--ink-500); margin-bottom:12px;">Menghapus pesanan bersifat permanen dan tidak bisa dibatalkan.</p>
                <form method="POST" action="{{ route('admin.pesanan.destroy', $pesanan->id) }}"
                      onsubmit="return confirm('Yakin ingin menghapus pesanan #{{ $pesanan->id }} secara permanen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="width:100%; padding:10px; border-radius:10px; background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; font-size:0.85rem; font-weight:600; cursor:pointer;">
                        Hapus Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection