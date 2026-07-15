@extends('layouts.admin')

@section('title', 'Laporan & Riwayat Transaksi')

@section('content')

    <div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:12px;">
        <div>
            <h1>Laporan & Riwayat Transaksi</h1>
            <p>Ringkasan pendapatan dan performa penjualan Apotek Rizki.</p>
        </div>
    </div>

    {{-- ===== FILTER TANGGAL ===== --}}
    <form method="GET" action="{{ route('admin.laporan.index') }}" style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap; align-items:center;">
        <div style="display:flex; align-items:center; gap:8px;">
            <label style="font-size:0.82rem; color:var(--ink-500); font-weight:600;">Dari</label>
            <input type="date" name="dari" value="{{ $tanggalMulai }}" style="padding:9px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem;">
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <label style="font-size:0.82rem; color:var(--ink-500); font-weight:600;">Sampai</label>
            <input type="date" name="sampai" value="{{ $tanggalSelesai }}" style="padding:9px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem;">
        </div>
        <button type="submit" style="padding:9px 18px; border-radius:10px; background:var(--mint-500); color:#fff; border:none; font-size:0.85rem; font-weight:600; cursor:pointer;">Terapkan</button>

        <div style="display:flex; gap:6px; margin-left:auto;">
            <a href="{{ route('admin.laporan.index', ['dari' => now()->startOfMonth()->format('Y-m-d'), 'sampai' => now()->format('Y-m-d')]) }}"
               style="padding:8px 14px; border-radius:999px; font-size:0.78rem; font-weight:600; background:var(--mint-50); color:var(--mint-700);">Bulan Ini</a>
            <a href="{{ route('admin.laporan.index', ['dari' => now()->subDays(6)->format('Y-m-d'), 'sampai' => now()->format('Y-m-d')]) }}"
               style="padding:8px 14px; border-radius:999px; font-size:0.78rem; font-weight:600; background:var(--mint-50); color:var(--mint-700);">7 Hari</a>
            <a href="{{ route('admin.laporan.index', ['dari' => now()->startOfYear()->format('Y-m-d'), 'sampai' => now()->format('Y-m-d')]) }}"
               style="padding:8px 14px; border-radius:999px; font-size:0.78rem; font-weight:600; background:var(--mint-50); color:var(--mint-700);">Tahun Ini</a>
        </div>
    </form>

    {{-- ===== KARTU RINGKASAN ===== --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:20px;">
        <div class="card" style="background:linear-gradient(135deg, var(--mint-500), var(--spring-deep)); border:none;">
            <div style="font-size:0.8rem; color:rgba(255,255,255,0.85); font-weight:500;">Total Pendapatan</div>
            <div style="font-size:1.7rem; font-weight:700; color:#fff; margin-top:6px;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div style="font-size:0.8rem; color:var(--ink-500); font-weight:500;">Transaksi Selesai</div>
            <div style="font-size:1.7rem; font-weight:700; color:var(--ink-900); margin-top:6px;">{{ $totalPesanan }}</div>
        </div>
        <div class="card">
            <div style="font-size:0.8rem; color:var(--ink-500); font-weight:500;">Rata-rata / Transaksi</div>
            <div style="font-size:1.7rem; font-weight:700; color:var(--ink-900); margin-top:6px;">Rp {{ number_format($rataRata, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div style="font-size:0.8rem; color:var(--ink-500); font-weight:500;">Total Ongkir Terkumpul</div>
            <div style="font-size:1.7rem; font-weight:700; color:var(--ink-900); margin-top:6px;">Rp {{ number_format($totalOngkir, 0, ',', '.') }}</div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1.6fr 1fr; gap:16px; margin-bottom:16px;">

        {{-- ===== GRAFIK PENDAPATAN HARIAN ===== --}}
        <div class="card">
            <h3 style="font-size:1rem; margin-bottom:14px;">Tren Pendapatan Harian</h3>
            <canvas id="chartPendapatan" height="110"></canvas>
        </div>

        {{-- ===== PENDAPATAN PER KATEGORI ===== --}}
        <div class="card">
            <h3 style="font-size:1rem; margin-bottom:14px;">Pendapatan per Kategori</h3>
            @if($pendapatanPerKategori->isEmpty())
                <p style="color:var(--ink-500); font-size:0.85rem;">Belum ada data pada periode ini.</p>
            @else
                <canvas id="chartKategori" height="180"></canvas>
            @endif
        </div>
    </div>

    {{-- ===== PRODUK TERLARIS ===== --}}
    <div class="card" style="margin-bottom:16px;">
        <h3 style="font-size:1rem; margin-bottom:14px;">🏆 Produk Terlaris</h3>
        @if($produkTerlaris->isEmpty())
            <p style="color:var(--ink-500); font-size:0.85rem;">Belum ada penjualan pada periode ini.</p>
        @else
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px;">
                @foreach($produkTerlaris as $i => $item)
                    <div style="display:flex; align-items:center; gap:10px; padding:12px; background:var(--mint-50); border-radius:12px;">
                        <div style="width:30px; height:30px; border-radius:50%; background:{{ $i === 0 ? '#fbbf24' : ($i === 1 ? '#cbd5e1' : ($i === 2 ? '#d97706' : 'var(--mint-100)')) }}; color:{{ $i < 3 ? '#fff' : 'var(--mint-700)' }}; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.85rem; flex-shrink:0;">
                            {{ $i + 1 }}
                        </div>
                        <div style="min-width:0;">
                            <div style="font-weight:600; font-size:0.85rem; color:var(--ink-900); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $item->obat->nama ?? '-' }}</div>
                            <div style="font-size:0.76rem; color:var(--ink-500);">{{ $item->total_terjual }} terjual &middot; Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ===== TABEL TRANSAKSI ===== --}}
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding:16px; border-bottom:1px solid var(--mint-100);">
            <h3 style="font-size:1rem;">Rincian Transaksi ({{ $pesananSelesai->count() }} ditampilkan)</h3>
        </div>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:var(--mint-50); text-align:left;">
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">ID</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Pelanggan</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Tanggal Selesai</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase; text-align:right;">Total</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase; text-align:right;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananSelesai as $p)
                    <tr style="border-top:1px solid var(--mint-100);">
                        <td style="padding:12px 16px; font-weight:600; color:var(--ink-900); font-size:0.86rem;">#{{ $p->id }}</td>
                        <td style="padding:12px 16px; font-size:0.85rem; color:var(--ink-700);">{{ $p->user->name ?? '-' }}</td>
                        <td style="padding:12px 16px; font-size:0.82rem; color:var(--ink-500);">{{ $p->updated_at->translatedFormat('d M Y, H:i') }}</td>
                        <td style="padding:12px 16px; font-size:0.86rem; font-weight:600; text-align:right; color:var(--spring-deep);">Rp {{ number_format($p->totalKeseluruhan(), 0, ',', '.') }}</td>
                        <td style="padding:12px 16px; text-align:right;">
                            <a href="{{ route('admin.pesanan.show', $p->id) }}" style="font-size:0.8rem; color:var(--mint-700); font-weight:600;">Lihat &rarr;</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:32px 16px; text-align:center; color:var(--ink-500); font-size:0.88rem;">
                            Tidak ada transaksi selesai pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        const labelsHarian = {!! json_encode(array_map(fn($d) => \Carbon\Carbon::parse($d)->translatedFormat('d M'), array_keys($grafikHarian))) !!};
        const dataHarian = {!! json_encode(array_values($grafikHarian)) !!};

        new Chart(document.getElementById('chartPendapatan'), {
            type: 'line',
            data: {
                labels: labelsHarian,
                datasets: [{
                    label: 'Pendapatan',
                    data: dataHarian,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointBackgroundColor: '#059669',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        ticks: {
                            callback: (val) => 'Rp' + (val / 1000) + 'rb'
                        },
                        grid: { color: '#ecfdf5' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        @if($pendapatanPerKategori->isNotEmpty())
        const labelsKategori = {!! json_encode($pendapatanPerKategori->pluck('kategori')) !!};
        const dataKategori = {!! json_encode($pendapatanPerKategori->pluck('total')) !!};

        new Chart(document.getElementById('chartKategori'), {
            type: 'doughnut',
            data: {
                labels: labelsKategori,
                datasets: [{
                    data: dataKategori,
                    backgroundColor: ['#10b981', '#34d399', '#6ee7b7', '#a7f3d0', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899', '#f43f5e'],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } }
                }
            }
        });
        @endif
    </script>

@endsection