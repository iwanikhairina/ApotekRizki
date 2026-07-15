@extends('layouts.kurir')

@section('title', 'Riwayat Pengiriman')

@section('content')
<style>
    .page-header{margin-bottom:24px;}
    .page-header h1{font-family:'Outfit',sans-serif;font-size:26px;font-weight:700;color:var(--text-dark);}
    .page-header p{color:var(--text-muted);font-size:14px;margin-top:4px;}

    .table-card{background:#fff;border-radius:20px;box-shadow:0 8px 24px rgba(15,47,34,.06);overflow:hidden;max-width:900px;}
    table{width:100%;border-collapse:collapse;}
    thead{background:var(--mint-50);}
    th{text-align:left;padding:14px 20px;font-size:13px;font-weight:700;color:var(--mint-700);text-transform:uppercase;letter-spacing:.4px;}
    td{padding:16px 20px;font-size:14px;color:var(--text-dark);border-top:1px solid #f0f4f2;vertical-align:top;}
    tbody tr:hover{background:#fafffc;}

    .badge{display:inline-block;padding:5px 12px;border-radius:999px;font-size:12px;font-weight:700;}
    .badge-selesai{background:var(--mint-50);color:var(--mint-700);}
    .badge-batal{background:#fff1f0;color:#e0433c;}

    .alasan-text{font-size:12.5px;color:var(--text-muted);margin-top:4px;max-width:220px;}

    .empty-state{text-align:center;padding:60px 20px;color:var(--text-muted);}

    @media (max-width:700px){.table-card{overflow-x:auto;} table{min-width:650px;}}
</style>

<div class="page-header">
    <h1>Riwayat Pengiriman</h1>
    <p>Semua pesanan yang sudah kamu antar atau batalkan</p>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesanan as $item)
                <tr>
                    <td>P{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($item->alamat, 40) }}</td>
                    <td>
                        @if ($item->status === 'selesai')
                            <span class="badge badge-selesai">✓ Selesai</span>
                        @else
                            <span class="badge badge-batal">✕ Dibatalkan</span>
                            @if ($item->alasan_batal)
                                <div class="alasan-text">{{ $item->alasan_batal }}</div>
                            @endif
                        @endif
                    </td>
                    <td>{{ $item->updated_at->translatedFormat('d F Y, H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty-state">Belum ada riwayat pengiriman.</div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection