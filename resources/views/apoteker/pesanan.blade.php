@extends('layouts.apoteker')

@section('title', 'Pesanan')

@section('content')
<style>
    .page-header{margin-bottom:24px;}
    .page-header h1{
        font-family:'Outfit',sans-serif;
        font-size:26px;
        font-weight:700;
        color:var(--text-dark);
    }
    .page-header p{color:var(--text-muted);font-size:14px;margin-top:4px;}

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

    .table-card{
        background:#fff;
        border-radius:20px;
        box-shadow:0 8px 24px rgba(15,47,34,.06);
        overflow:hidden;
    }
    table{
        width:100%;
        border-collapse:collapse;
    }
    thead{
        background:var(--mint-50);
    }
    th{
        text-align:left;
        padding:14px 20px;
        font-size:13px;
        font-weight:700;
        color:var(--mint-700);
        text-transform:uppercase;
        letter-spacing:.4px;
    }
    td{
        padding:16px 20px;
        font-size:14px;
        color:var(--text-dark);
        border-top:1px solid #f0f4f2;
    }
    tbody tr:hover{background:#fafffc;}

    .badge{
        display:inline-block;
        padding:5px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:700;
    }
    .badge-menunggu{background:var(--amber-bg);color:var(--amber);}
    .badge-diproses{background:var(--blue-soft);color:#3b82f6;}
    .badge-disiapkan{background:#f3e8ff;color:#9333ea;}
    .badge-siap{background:var(--mint-50);color:var(--mint-700);}
    .badge-ditolak{background:#fff1f0;color:#e0433c;}
    .badge-dikirim{background:#e0f2fe;color:#0284c7;}
    .badge-selesai{background:#f0fdf4;color:#16a34a;}

    .btn-detail{
        display:inline-flex;
        align-items:center;
        gap:6px;
        background:var(--mint-500);
        color:#fff;
        text-decoration:none;
        font-size:13px;
        font-weight:700;
        padding:8px 16px;
        border-radius:999px;
        transition:.15s ease;
    }
    .btn-detail:hover{background:var(--mint-700);}

    .empty-state{
        text-align:center;
        padding:60px 20px;
        color:var(--text-muted);
    }

    @media (max-width:700px){
        .table-card{overflow-x:auto;}
        table{min-width:600px;}
    }
</style>

<div class="page-header">
    <h1>Pesanan</h1>
    <p>Kelola dan pantau semua pesanan pelanggan Apotek Rizki</p>
</div>

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesanan as $item)
                @php
                    $badgeClass = match($item->status) {
                        'menunggu_verifikasi' => 'badge-menunggu',
                        'diproses'            => 'badge-diproses',
                        'disiapkan'           => 'badge-disiapkan',
                        'siap_dikirim'        => 'badge-siap',
                        'ditolak'             => 'badge-ditolak',
                        'dikirim'             => 'badge-dikirim',
                        'selesai'             => 'badge-selesai',
                        default               => 'badge-menunggu',
                    };
                    $statusLabel = match($item->status) {
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'diproses'            => 'Diproses',
                        'disiapkan'           => 'Obat Disiapkan',
                        'siap_dikirim'        => 'Siap Dikirim',
                        'ditolak'             => 'Ditolak',
                        'dikirim'             => 'Dikirim',
                        'selesai'             => 'Selesai',
                        default               => ucfirst($item->status),
                    };
                @endphp
                <tr>
                    <td>P{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td><span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                    <td>
                        <a href="{{ route('apoteker.pesanan.detail', $item->id) }}" class="btn-detail">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">Belum ada pesanan masuk.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection