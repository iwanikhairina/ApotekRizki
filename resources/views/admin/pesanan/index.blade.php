@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

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

    <div class="page-header">
        <h1>Manajemen Pesanan</h1>
        <p>Pantau seluruh pesanan dari semua status, dan tugaskan ulang pesanan yang dibatalkan kurir.</p>
    </div>

    {{-- ===== FILTER STATUS + PENCARIAN ===== --}}
    <form method="GET" action="{{ route('admin.pesanan.index') }}" style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
        <select name="status" onchange="this.form.submit()" style="padding:9px 14px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem; background:var(--surface); color:var(--ink-700);">
            <option value="">Semua Status ({{ $jumlahPerStatus->sum() }})</option>
            @foreach($statusLabel as $key => $label)
                <option value="{{ $key }}" {{ $filterStatus === $key ? 'selected' : '' }}>{{ $label }} ({{ $jumlahPerStatus[$key] ?? 0 }})</option>
            @endforeach
        </select>

        <input type="text" name="cari" value="{{ $filterCari }}" placeholder="Cari ID pesanan atau nama pelanggan..."
            style="flex:1; max-width:320px; padding:9px 14px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem;">
        <button type="submit" style="padding:9px 18px; border-radius:10px; background:var(--mint-500); color:#fff; border:none; font-size:0.85rem; font-weight:600; cursor:pointer;">Cari</button>

        @if($filterStatus || $filterCari)
            <a href="{{ route('admin.pesanan.index') }}" style="display:flex; align-items:center; font-size:0.85rem; color:var(--ink-500); text-decoration:underline;">Reset filter</a>
        @endif
    </form>

    {{-- ===== TABEL PESANAN ===== --}}
    <div class="card" style="padding:0; overflow:hidden;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:var(--mint-50); text-align:left;">
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">ID</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Pelanggan</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Kurir</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Total</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Status</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Tanggal</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase; text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $p)
                    <tr style="border-top:1px solid var(--mint-100); {{ $p->status === 'dibatalkan_kurir' ? 'background:#fff7f7;' : '' }}">
                        <td style="padding:14px 16px; font-weight:600; color:var(--ink-900); font-size:0.88rem;">#{{ $p->id }}</td>
                        <td style="padding:14px 16px; font-size:0.85rem; color:var(--ink-700);">{{ $p->user->name ?? '-' }}</td>
                        <td style="padding:14px 16px; font-size:0.85rem; color:var(--ink-700);">{{ $p->kurir->name ?? '-' }}</td>
                        <td style="padding:14px 16px; font-size:0.85rem; color:var(--ink-900); font-weight:600;">Rp {{ number_format($p->totalKeseluruhan(), 0, ',', '.') }}</td>
                        <td style="padding:14px 16px;">
                            <span style="background:{{ $statusColor[$p->status][0] ?? '#f1f5f9' }}; color:{{ $statusColor[$p->status][1] ?? '#334155' }}; padding:3px 10px; border-radius:999px; font-size:0.74rem; font-weight:600;">
                                {{ $statusLabel[$p->status] ?? $p->status }}
                            </span>
                        </td>
                        <td style="padding:14px 16px; font-size:0.8rem; color:var(--ink-500);">{{ $p->created_at->translatedFormat('d M Y, H:i') }}</td>
                        <td style="padding:14px 16px; text-align:right;">
                            <a href="{{ route('admin.pesanan.show', $p->id) }}"
                               style="display:inline-flex; align-items:center; justify-content:center; height:32px; padding:0 14px; border-radius:8px; font-size:0.8rem; font-weight:600; color:var(--mint-700); background:var(--mint-50); border:1px solid var(--mint-100);">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding:32px 16px; text-align:center; color:var(--ink-500); font-size:0.88rem;">
                            Tidak ada pesanan yang cocok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">
        {{ $pesanan->links() }}
    </div>

@endsection