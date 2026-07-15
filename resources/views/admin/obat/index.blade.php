@extends('layouts.admin')

@section('title', 'Manajemen Obat')

@section('content')

    <div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:12px;">
        <div>
            <h1>Manajemen Obat</h1>
            <p>Kelola daftar obat yang tampil ke pelanggan, termasuk stok dan tanggal kadaluarsa.</p>
        </div>
        <a href="{{ route('admin.obat.create') }}" style="background:var(--mint-500); color:#fff; padding:10px 20px; border-radius:10px; font-weight:600; font-size:0.9rem; display:inline-flex; align-items:center; gap:8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Obat
        </a>
    </div>

    {{-- ===== FILTER ===== --}}
    <form method="GET" action="{{ route('admin.obat.index') }}" style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
        <select name="kategori" onchange="this.form.submit()" style="padding:9px 14px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem; background:var(--surface); color:var(--ink-700);">
            <option value="">Semua Kategori</option>
            @foreach($daftarKategori as $kat)
                <option value="{{ $kat }}" {{ $filterKategori === $kat ? 'selected' : '' }}>{{ $kat }}</option>
            @endforeach
        </select>

        <select name="klasifikasi" onchange="this.form.submit()" style="padding:9px 14px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem; background:var(--surface); color:var(--ink-700);">
            <option value="">Semua Jenis</option>
            <option value="obat_bebas" {{ $filterKlasifikasi === 'obat_bebas' ? 'selected' : '' }}>Obat Bebas</option>
            <option value="obat_bebas_terbatas" {{ $filterKlasifikasi === 'obat_bebas_terbatas' ? 'selected' : '' }}>Obat Bebas Terbatas</option>
            <option value="obat_keras" {{ $filterKlasifikasi === 'obat_keras' ? 'selected' : '' }}>Obat Keras</option>
        </select>

        <select name="status" onchange="this.form.submit()" style="padding:9px 14px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem; background:var(--surface); color:var(--ink-700);">
            <option value="">Semua Status</option>
            <option value="aktif" {{ $filterStatus === 'aktif' ? 'selected' : '' }}>Masih Berlaku</option>
            <option value="kadaluarsa" {{ $filterStatus === 'kadaluarsa' ? 'selected' : '' }}>Sudah Kadaluarsa</option>
        </select>

        @if($filterKategori || $filterKlasifikasi || $filterStatus)
            <a href="{{ route('admin.obat.index') }}" style="display:flex; align-items:center; font-size:0.85rem; color:var(--ink-500); text-decoration:underline;">Reset filter</a>
        @endif
    </form>

    {{-- ===== GRID OBAT ===== --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:16px;">
        @forelse($obat as $o)
            @php
                $kadaluarsa = $o->tanggal_kadaluarsa && \Carbon\Carbon::parse($o->tanggal_kadaluarsa)->isPast();
                $klasifikasiLabel = [
                    'obat_bebas' => 'Obat Bebas',
                    'obat_bebas_terbatas' => 'Bebas Terbatas',
                    'obat_keras' => 'Obat Keras',
                ][$o->klasifikasi] ?? $o->klasifikasi;
                $klasifikasiColor = [
                    'obat_bebas' => ['#dcfce7', '#166534'],
                    'obat_bebas_terbatas' => ['#fef3c7', '#92400e'],
                    'obat_keras' => ['#fee2e2', '#b91c1c'],
                ][$o->klasifikasi] ?? ['#f1f5f9', '#334155'];
            @endphp
            <div class="card" style="padding:0; overflow:hidden; {{ $kadaluarsa ? 'opacity:0.7;' : '' }}">
                <div style="height:140px; background:var(--mint-50); display:flex; align-items:center; justify-content:center; overflow:hidden;">
                    @if($o->gambar)
                        <img src="{{ Storage::url($o->gambar) }}" alt="{{ $o->nama }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mint-500)" stroke-width="1.5"><path d="M10.5 20.5L20.5 10.5a4.95 4.95 0 00-7-7L3.5 13.5a4.95 4.95 0 007 7z"/><path d="M8.5 8.5l7 7"/></svg>
                    @endif
                </div>
                <div style="padding:14px;">
                    @if($kadaluarsa)
                        <span style="background:#fee2e2; color:#b91c1c; padding:2px 8px; border-radius:999px; font-size:0.7rem; font-weight:700; display:inline-block; margin-bottom:6px;">KADALUARSA</span>
                    @endif
                    <div style="font-weight:700; font-size:0.95rem; color:var(--ink-900); margin-bottom:2px;">{{ $o->nama }}</div>
                    @if($o->kategori)
                        <div style="font-size:0.76rem; color:var(--ink-500); margin-bottom:8px;">{{ $o->kategori }}</div>
                    @endif

                    <div style="display:flex; gap:6px; flex-wrap:wrap; margin-bottom:10px;">
                        <span style="background:{{ $klasifikasiColor[0] }}; color:{{ $klasifikasiColor[1] }}; padding:2px 8px; border-radius:999px; font-size:0.7rem; font-weight:600;">{{ $klasifikasiLabel }}</span>
                        @if($o->butuh_resep)
                            <span style="background:#ede9fe; color:#6d28d9; padding:2px 8px; border-radius:999px; font-size:0.7rem; font-weight:600;">Resep</span>
                        @endif
                        @if($o->butuh_ktp)
                            <span style="background:#e0f2fe; color:#0369a1; padding:2px 8px; border-radius:999px; font-size:0.7rem; font-weight:600;">KTP</span>
                        @endif
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                        <div style="font-weight:700; color:var(--spring-deep); font-size:0.95rem;">Rp {{ number_format($o->harga, 0, ',', '.') }}</div>
                        <div style="font-size:0.78rem; color:{{ $o->stok <= 10 ? 'var(--danger)' : 'var(--ink-500)' }}; font-weight:{{ $o->stok <= 10 ? '700' : '400' }};">Stok: {{ $o->stok }}</div>
                    </div>

                    <a href="{{ route('admin.obat.edit', $o->id) }}"
                       style="display:flex; align-items:center; justify-content:center; width:100%; height:32px; border-radius:8px; font-size:0.8rem; font-weight:600; color:var(--mint-700); background:var(--mint-50); border:1px solid var(--mint-100);">
                        Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="card" style="grid-column:1/-1; text-align:center; padding:40px; color:var(--ink-500);">
                Belum ada obat yang cocok dengan filter ini.
            </div>
        @endforelse
    </div>

@endsection