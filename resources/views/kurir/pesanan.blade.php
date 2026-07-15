@extends('layouts.kurir')

@section('title', 'Pesanan')

@section('content')
<style>
    .page-header{margin-bottom:24px;}
    .page-header h1{font-family:'Outfit',sans-serif;font-size:26px;font-weight:700;color:var(--text-dark);}
    .page-header p{color:var(--text-muted);font-size:14px;margin-top:4px;}
    .alert-success{background:var(--mint-50);border:1px solid var(--mint-100);color:var(--mint-700);padding:12px 16px;border-radius:12px;font-size:14px;margin-bottom:20px;font-weight:600;}
    .alert-error{background:#fff1f0;border:1px solid #ffccc7;color:#cf1322;padding:12px 16px;border-radius:12px;font-size:14px;margin-bottom:20px;font-weight:600;}

    .table-card{background:#fff;border-radius:20px;box-shadow:0 8px 24px rgba(15,47,34,.06);overflow:hidden;}
    table{width:100%;border-collapse:collapse;}
    thead{background:var(--mint-50);}
    th{text-align:left;padding:14px 20px;font-size:13px;font-weight:700;color:var(--mint-700);text-transform:uppercase;letter-spacing:.4px;}
    td{padding:16px 20px;font-size:14px;color:var(--text-dark);border-top:1px solid #f0f4f2;}
    tbody tr:hover{background:#fafffc;}

    .btn-detail{display:inline-flex;align-items:center;gap:6px;background:var(--mint-500);color:#fff;text-decoration:none;font-size:13px;font-weight:700;padding:8px 16px;border-radius:999px;transition:.15s ease;}
    .btn-detail:hover{background:var(--mint-700);}
    .empty-state{text-align:center;padding:60px 20px;color:var(--text-muted);}

    @media (max-width:700px){.table-card{overflow-x:auto;} table{min-width:600px;}}
</style>

<div class="page-header">
    <h1>Pesanan Siap Diantar</h1>
    <p>Daftar pesanan yang sudah disiapkan apoteker dan menunggu diambil kurir</p>
</div>

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@if (session('shift_error'))
    <div class="alert-error">⚠ {{ session('shift_error') }}</div>
@endif

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesanan as $item)
                <tr>
                    <td>P{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($item->alamat, 40) }}</td>
                    <td>
                        <a href="{{ route('kurir.pesanan.detail', $item->id) }}" class="btn-detail">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4"><div class="empty-state">Belum ada pesanan yang siap diantar.</div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection