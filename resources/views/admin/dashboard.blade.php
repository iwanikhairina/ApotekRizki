@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Ringkasan operasional Apotek Rizki hari ini, {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    {{-- ===== KARTU RINGKASAN UTAMA ===== --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px;">

        <div class="card">
            <div style="font-size:0.8rem; color:var(--ink-500); font-weight:500;">Pesanan Hari Ini</div>
            <div style="font-size:1.9rem; font-weight:700; color:var(--ink-900); margin-top:6px;">{{ $totalPesananHariIni }}</div>
        </div>

        <div class="card">
            <div style="font-size:0.8rem; color:var(--ink-500); font-weight:500;">Pendapatan Hari Ini</div>
            <div style="font-size:1.9rem; font-weight:700; color:var(--spring-deep); margin-top:6px;">
                Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}
            </div>
        </div>

        <div class="card">
            <div style="font-size:0.8rem; color:var(--ink-500); font-weight:500;">Staff Aktif</div>
            <div style="font-size:1.9rem; font-weight:700; color:var(--ink-900); margin-top:6px;">{{ $totalStaffAktif }}</div>
            <div style="font-size:0.78rem; color:var(--ink-500); margin-top:4px;">
                {{ $jumlahApotekerAktif }} Apoteker &middot; {{ $jumlahKurirAktif }} Kurir
            </div>
        </div>

        <div class="card">
            <div style="font-size:0.8rem; color:var(--ink-500); font-weight:500;">Obat Stok Menipis</div>
            <div style="font-size:1.9rem; font-weight:700; color:{{ $obatStokMenipis->count() > 0 ? 'var(--danger)' : 'var(--ink-900)' }}; margin-top:6px;">
                {{ $obatStokMenipis->count() }}
            </div>
        </div>

    </div>

    <div style="display:grid; grid-template-columns: 1.3fr 1fr; gap:16px;">

        {{-- ===== REKAP PESANAN PER STATUS ===== --}}
        <div class="card">
            <h3 style="font-size:1.05rem; margin-bottom:16px;">Pesanan per Status</h3>

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
                    'menunggu_verifikasi' => '#f59e0b',
                    'diproses'            => '#3b82f6',
                    'ditolak'             => '#ef4444',
                    'disiapkan'           => '#8b5cf6',
                    'siap_dikirim'        => '#06b6d4',
                    'dikirim'             => '#0ea5e9',
                    'selesai'             => '#10b981',
                    'dibatalkan_kurir'    => '#f43f5e',
                ];
                $maxStatus = max($rekapStatus) ?: 1;
            @endphp

            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach($rekapStatus as $status => $jumlah)
                    <div>
                        <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:4px;">
                            <span style="color:var(--ink-700); font-weight:500;">{{ $statusLabel[$status] }}</span>
                            <span style="font-weight:700; color:var(--ink-900);">{{ $jumlah }}</span>
                        </div>
                        <div style="background:var(--mint-50); border-radius:6px; height:8px; overflow:hidden;">
                            <div style="width:{{ ($jumlah / $maxStatus) * 100 }}%; background:{{ $statusColor[$status] }}; height:100%; border-radius:6px; transition:width 0.3s ease;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
{{-- ===== OBAT MENDEKATI KADALUARSA (FEFO) ===== --}}
<div class="card">
    <h3 style="font-size:1.05rem; margin-bottom:16px;">⏳ Mendekati Kadaluarsa</h3>

    @if($obatMendekatiKadaluarsa->isEmpty())
        <p style="color:var(--ink-500); font-size:0.88rem;">
            Tidak ada obat yang mendekati kadaluarsa dalam 30 hari ke depan.
        </p>
    @else
        <div style="display:flex; flex-direction:column; gap:10px;">
            @foreach($obatMendekatiKadaluarsa as $obat)
                <div style="padding:10px 12px; background:var(--mint-50); border-radius:10px;">
                    <div style="font-weight:600; font-size:0.88rem; color:var(--ink-900);">
                        {{ $obat->nama }}
                    </div>
                    <div style="font-size:0.76rem; color:var(--ink-500);">
                        Kadaluarsa {{ $obat->tanggal_kadaluarsa->translatedFormat('d F Y') }}
                        &middot;
                        Stok {{ $obat->stok }} pcs
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
        {{-- ===== OBAT STOK MENIPIS ===== --}}
        <div class="card">
            <h3 style="font-size:1.05rem; margin-bottom:16px;">Stok Obat Menipis</h3>

            @if($obatStokMenipis->isEmpty())
                <p style="color:var(--ink-500); font-size:0.88rem;">Semua stok obat masih aman.</p>
            @else
                <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach($obatStokMenipis as $obat)
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 12px; background:var(--mint-50); border-radius:10px;">
                            <div>
                                <div style="font-weight:600; font-size:0.88rem; color:var(--ink-900);">{{ $obat->nama }}</div>
                                <div style="font-size:0.76rem; color:var(--ink-500);">{{ ucfirst($obat->klasifikasi) }}</div>
                            </div>
                            <div style="font-weight:700; font-size:0.95rem; color:var(--danger);">
                                {{ $obat->stok }} pcs
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

@endsection