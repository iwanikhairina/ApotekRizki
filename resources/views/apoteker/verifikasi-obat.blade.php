@extends('layouts.apoteker')

@section('title', 'Verifikasi Resep')

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
    thead{background:var(--mint-50);}
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

    .file-chip{
        display:inline-flex;
        align-items:center;
        gap:6px;
        background:var(--blue-soft);
        color:#3b82f6;
        padding:5px 12px;
        border-radius:999px;
        font-size:12.5px;
        font-weight:600;
    }
    .no-file{color:var(--text-muted);font-size:13px;font-style:italic;}

    .badge{
        display:inline-block;
        padding:5px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:700;
    }
    .badge-menunggu{background:var(--amber-bg);color:var(--amber);}
    .badge-disetujui{background:var(--mint-50);color:var(--mint-700);}
    .badge-ditolak{background:#fff1f0;color:#e0433c;}
    .badge-tidak-perlu{background:#f1f5f9;color:#64748b;}

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
        table{min-width:650px;}
    }
</style>

<div class="page-header">
    <h1>Verifikasi Resep</h1>
    <p>Periksa resep dokter dan dokumen pendukung (KTP) untuk obat keras & obat khusus seperti kontrasepsi</p>
</div>

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Resep / Dokumen</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesanan as $item)
                @php
                    $badgeClass = match($item->status_resep) {
                        'menunggu'    => 'badge-menunggu',
                        'disetujui'   => 'badge-disetujui',
                        'ditolak'     => 'badge-ditolak',
                        default       => 'badge-tidak-perlu',
                    };
                    $statusLabel = match($item->status_resep) {
                        'menunggu'    => 'Menunggu',
                        'disetujui'   => 'Disetujui',
                        'ditolak'     => 'Ditolak',
                        default       => 'Tidak Perlu',
                    };
                @endphp
                <tr>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>
                        @if ($item->resep_path)
                            <span class="file-chip">📄 {{ basename($item->resep_path) }}</span>
                        @elseif ($item->ktp_path)
                            <span class="file-chip">🪪 {{ basename($item->ktp_path) }}</span>
                        @else
                            <span class="no-file">Belum ada dokumen</span>
                        @endif
                    </td>
                    <td><span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                    <td>
                        <a href="{{ route('apoteker.verifikasi.detail', $item->id) }}" class="btn-detail">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">Tidak ada pesanan yang membutuhkan verifikasi resep saat ini.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection