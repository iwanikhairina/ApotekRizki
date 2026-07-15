@extends('layouts.admin')

@section('title', 'Manajemen Staff')

@section('content')

    <div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:12px;">
        <div>
            <h1>Manajemen Staff</h1>
            <p>Kelola akun Apoteker & Kurir — tambah, edit, dan atur shift mereka di sini.</p>
        </div>
        <a href="{{ route('admin.staff.create') }}" style="background:var(--mint-500); color:#fff; padding:10px 20px; border-radius:10px; font-weight:600; font-size:0.9rem; display:inline-flex; align-items:center; gap:8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Staff
        </a>
    </div>

    {{-- ===== FILTER ===== --}}
    <form method="GET" action="{{ route('admin.staff.index') }}" style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
        <select name="role" onchange="this.form.submit()" style="padding:9px 14px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem; background:var(--surface); color:var(--ink-700);">
            <option value="">Semua Role</option>
            <option value="apoteker" {{ $filterRole === 'apoteker' ? 'selected' : '' }}>Apoteker</option>
            <option value="kurir" {{ $filterRole === 'kurir' ? 'selected' : '' }}>Kurir</option>
        </select>

        <select name="status" onchange="this.form.submit()" style="padding:9px 14px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem; background:var(--surface); color:var(--ink-700);">
            <option value="">Semua Status</option>
            <option value="aktif" {{ $filterStatus === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ $filterStatus === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        @if($filterRole || $filterStatus)
            <a href="{{ route('admin.staff.index') }}" style="display:flex; align-items:center; font-size:0.85rem; color:var(--ink-500); text-decoration:underline;">Reset filter</a>
        @endif
    </form>

    {{-- ===== TABEL STAFF ===== --}}
    <div class="card" style="padding:0; overflow:hidden;">
        <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
            <colgroup>
                <col style="width:22%;">
                <col style="width:12%;">
                <col style="width:10%;">
                <col style="width:26%;">
                <col style="width:12%;">
                <col style="width:18%;">
            </colgroup>
            <thead>
                <tr style="background:var(--mint-50); text-align:left;">
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Nama</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Role</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Shift</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Kontak</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Status</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase; text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $s)
                    <tr style="border-top:1px solid var(--mint-100);">
                        <td style="padding:14px 16px; overflow:hidden; text-overflow:ellipsis;">
                            <div style="font-weight:600; font-size:0.9rem; color:var(--ink-900); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $s->name }}</div>
                            <div style="font-size:0.78rem; color:var(--ink-500);">{{ '@' . $s->username }}</div>
                        </td>
                        <td style="padding:14px 16px;">
                            <span style="background:{{ $s->role === 'apoteker' ? '#ede9fe' : '#fee2e2' }}; color:{{ $s->role === 'apoteker' ? '#6d28d9' : '#b91c1c' }}; padding:3px 10px; border-radius:999px; font-size:0.76rem; font-weight:600; white-space:nowrap;">
                                {{ ucfirst($s->role) }}
                            </span>
                        </td>
                        <td style="padding:14px 16px; font-size:0.85rem; color:var(--ink-700);">
                            {{ $s->shift ? ucfirst($s->shift) : '-' }}
                        </td>
                        <td style="padding:14px 16px; font-size:0.82rem; color:var(--ink-700); overflow:hidden;">
                            <div style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $s->email }}</div>
                            <div style="color:var(--ink-500);">{{ $s->phone }}</div>
                        </td>
                        <td style="padding:14px 16px;">
                            @if($s->is_active)
                                <span style="background:var(--mint-50); color:var(--spring-deep); padding:3px 10px; border-radius:999px; font-size:0.76rem; font-weight:600; white-space:nowrap;">Aktif</span>
                            @else
                                <span style="background:#f1f5f9; color:var(--ink-500); padding:3px 10px; border-radius:999px; font-size:0.76rem; font-weight:600; white-space:nowrap;">Nonaktif</span>
                            @endif
                        </td>
                        <td style="padding:14px 16px; text-align:right;">
                            <div style="display:flex; gap:8px; justify-content:flex-end; align-items:center;">
                                <a href="{{ route('admin.staff.edit', $s->id) }}"
                                   style="display:inline-flex; align-items:center; justify-content:center; height:32px; padding:0 14px; border-radius:8px; font-size:0.8rem; font-weight:600; color:var(--mint-700); background:var(--mint-50); border:1px solid var(--mint-100); line-height:1; white-space:nowrap;">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.staff.toggle', $s->id) }}" onsubmit="return confirm('{{ $s->is_active ? 'Nonaktifkan' : 'Aktifkan kembali' }} akun {{ $s->name }}?');" style="margin:0;">
                                    @csrf
                                    <button type="submit"
                                        style="display:inline-flex; align-items:center; justify-content:center; height:32px; padding:0 14px; border-radius:8px; font-size:0.8rem; font-weight:600; cursor:pointer; line-height:1; white-space:nowrap;
                                        {{ $s->is_active
                                            ? 'color:#b91c1c; background:#fef2f2; border:1px solid #fecaca;'
                                            : 'color:var(--mint-700); background:var(--mint-50); border:1px solid var(--mint-100);' }}">
                                        {{ $s->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:32px 16px; text-align:center; color:var(--ink-500); font-size:0.88rem;">
                            Belum ada staff yang cocok dengan filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection