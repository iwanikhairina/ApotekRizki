@extends('layouts.kurir')

@section('title', 'Dashboard')

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

    .stat-grid{
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:20px;
        margin-bottom:36px;
    }
    .stat-card{
        background:#fff;
        border-radius:20px;
        padding:24px;
        box-shadow:0 8px 24px rgba(15,47,34,.06);
        position:relative;
        overflow:hidden;
    }
    .stat-card::before{
        content:'';
        position:absolute;
        top:-30px;
        right:-30px;
        width:100px;
        height:100px;
        border-radius:50%;
        opacity:.12;
    }
    .stat-card.blue::before{background:#3b82f6;}
    .stat-card.amber::before{background:var(--amber);}
    .stat-card.green::before{background:var(--mint-500);}

    .stat-icon{
        width:46px;height:46px;
        border-radius:14px;
        display:flex;
        align-items:center;
        justify-content:center;
        margin-bottom:16px;
    }
    .stat-card.blue .stat-icon{background:var(--blue-soft);color:#3b82f6;}
    .stat-card.amber .stat-icon{background:var(--amber-bg);color:var(--amber);}
    .stat-card.green .stat-icon{background:var(--mint-50);color:var(--mint-600);}

    .stat-value{
        font-family:'Outfit',sans-serif;
        font-size:32px;
        font-weight:800;
        color:var(--text-dark);
        line-height:1;
        margin-bottom:6px;
        position:relative;
        z-index:1;
    }
    .stat-label{
        font-size:13.5px;
        color:var(--text-muted);
        font-weight:600;
        position:relative;
        z-index:1;
    }

    @media (max-width:900px){
        .stat-grid{grid-template-columns:1fr;}
    }
</style>

<div class="page-header">
    <h1>Selamat datang, {{ auth()->user()->name ?? 'Kurir' }} 👋</h1>
    <p>Berikut ringkasan tugas pengantaran hari ini, {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
</div>

@php
    $onShift = auth()->user()->isOnShiftNow();
@endphp

<div style="
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    flex-wrap:wrap;
    background:{{ $onShift ? 'var(--mint-50)' : '#fff1f0' }};
    border:1px solid {{ $onShift ? 'var(--mint-100)' : '#ffccc7' }};
    border-radius:16px;
    padding:16px 22px;
    margin-bottom:28px;
">
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="
            width:42px;height:42px;
            border-radius:12px;
            background:#fff;
            color:{{ $onShift ? 'var(--mint-700)' : '#e0433c' }};
            display:flex;align-items:center;justify-content:center;
            flex-shrink:0;
        ">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <div>
            <div style="font-size:13px;color:var(--text-muted);font-weight:600;">Shift Kerja Kamu Hari Ini</div>
            <div style="font-family:'Outfit',sans-serif;font-size:16px;font-weight:700;color:var(--text-dark);">
                {{ auth()->user()->shiftLabel() }}
            </div>
        </div>
    </div>
    <span style="
        font-size:12.5px;
        font-weight:700;
        padding:7px 16px;
        border-radius:999px;
        background:{{ $onShift ? 'var(--mint-500)' : '#e0433c' }};
        color:#fff;
        white-space:nowrap;
    ">
        {{ $onShift ? '● Sedang Bertugas' : '● Di Luar Jam Shift' }}
    </span>
</div>

@if (! $onShift)
<div style="background:#fff1f0;border:1px solid #ffccc7;color:#cf1322;padding:12px 16px;border-radius:12px;font-size:13.5px;margin-bottom:24px;font-weight:600;">
    ⚠ Kamu sedang di luar jam shift, sehingga tidak bisa mengambil atau menyelesaikan pesanan sampai jadwal shift kamu dimulai kembali.
</div>
@endif

<div class="stat-grid">
    <div class="stat-card blue">
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </div>
        <div class="stat-value">{{ $siapDiantar }}</div>
        <div class="stat-label">Pesanan Siap Diantar</div>
    </div>

    <div class="stat-card amber">
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="5.5" cy="17.5" r="2.5"/><circle cx="18.5" cy="17.5" r="2.5"/><path d="M15 17.5H9M1 17.5V6a1 1 0 0 1 1-1h11l5 5v7.5h-2"/></svg>
        </div>
        <div class="stat-value">{{ $sedangDiantar }}</div>
        <div class="stat-label">Sedang Diantar</div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
        </div>
        <div class="stat-value">{{ $selesaiHariIni }}</div>
        <div class="stat-label">Selesai Hari Ini</div>
    </div>
</div>
@endsection