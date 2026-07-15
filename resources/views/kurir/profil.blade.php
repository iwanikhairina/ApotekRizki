@extends('layouts.kurir')

@section('title', 'Profil Saya')

@section('content')
<style>
    .page-header{margin-bottom:24px;}
    .page-header h1{font-family:'Outfit',sans-serif;font-size:26px;font-weight:700;color:var(--text-dark);}
    .page-header p{color:var(--text-muted);font-size:14px;margin-top:4px;}
    .alert-success{background:var(--mint-50);border:1px solid var(--mint-100);color:var(--mint-700);padding:12px 16px;border-radius:12px;font-size:14px;margin-bottom:20px;font-weight:600;}
    .alert-error{background:#fff1f0;border:1px solid #ffccc7;color:#cf1322;padding:12px 16px;border-radius:12px;font-size:14px;margin-bottom:20px;font-weight:600;}

    .profile-grid{display:grid;grid-template-columns:300px 1fr;gap:22px;align-items:start;}
    .id-card{background:linear-gradient(150deg, var(--mint-500) 0%, var(--mint-700) 100%);border-radius:24px;padding:30px 24px;color:#fff;text-align:center;box-shadow:0 8px 24px rgba(15,47,34,.1);}
    .id-card .avatar-big{width:84px;height:84px;border-radius:50%;background:var(--amber);border:4px solid rgba(255,255,255,.5);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-family:'Outfit',sans-serif;font-weight:700;font-size:30px;color:var(--mint-700);}
    .id-card h2{font-family:'Outfit',sans-serif;font-weight:700;font-size:19px;margin-bottom:4px;}
    .id-card .id-email{font-size:12.5px;opacity:.85;margin-bottom:14px;word-break:break-word;}
    .id-card .id-tag{display:inline-block;font-size:11px;font-weight:700;background:rgba(255,255,255,.18);padding:5px 14px;border-radius:999px;margin-bottom:10px;text-transform:capitalize;}
    .id-card .id-shift{font-size:12px;opacity:.95;margin-bottom:6px;background:rgba(255,255,255,.12);padding:8px 12px;border-radius:10px;}
    .id-card .id-vehicle{font-size:12px;opacity:.95;margin-bottom:6px;background:rgba(255,255,255,.12);padding:8px 12px;border-radius:10px;}
    .id-card .shift-note{font-size:10.5px;opacity:.75;margin-bottom:22px;}
    .id-card .logout-btn{width:100%;display:flex;align-items:center;justify-content:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.35);color:#fff;font-family:'Outfit',sans-serif;font-weight:700;font-size:13px;padding:11px;border-radius:14px;cursor:pointer;transition:.15s ease;}
    .id-card .logout-btn:hover{background:rgba(224,80,59,.85);border-color:transparent;}

    .card{background:#fff;border-radius:20px;padding:24px;box-shadow:0 8px 24px rgba(15,47,34,.06);margin-bottom:20px;}
    .card-heading{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
    .card-heading h3{font-family:'Outfit',sans-serif;font-size:16px;font-weight:700;color:var(--text-dark);display:flex;align-items:center;gap:8px;}
    .btn-edit-toggle{background:var(--mint-50);color:var(--mint-700);border:none;font-size:13px;font-weight:700;padding:8px 16px;border-radius:999px;cursor:pointer;display:flex;align-items:center;gap:6px;transition:.15s ease;}
    .btn-edit-toggle:hover{background:var(--mint-100);}

    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;}
    .form-group{margin-bottom:16px;}
    .form-group label{display:block;font-size:13px;font-weight:600;color:var(--text-dark);margin-bottom:6px;}
    .form-group input,.form-group textarea{width:100%;background:#eef6ff;border:2px solid transparent;border-radius:12px;padding:11px 14px;font-size:14px;font-family:'Inter',sans-serif;color:var(--text-dark);outline:none;transition:.15s ease;}
    .form-group textarea{resize:vertical;min-height:70px;}
    .form-group input:focus,.form-group textarea:focus{border-color:var(--mint-500);background:#fff;}
    .form-group input:disabled,.form-group textarea:disabled{background:#f1f5f4;color:var(--text-muted);cursor:not-allowed;}

    .locked-field{display:flex;align-items:center;justify-content:space-between;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:12px;padding:12px 14px;}
    .locked-field .value{font-size:14px;font-weight:700;color:var(--text-dark);}
    .locked-field .lock-tag{font-size:11px;font-weight:700;color:#94a3b8;display:flex;align-items:center;gap:4px;}

    .btn-save{background:linear-gradient(135deg,var(--mint-500),var(--mint-700));color:#fff;border:none;padding:12px 24px;border-radius:999px;font-size:14px;font-weight:700;font-family:'Outfit',sans-serif;cursor:pointer;transition:.15s ease;}
    .btn-save:hover{filter:brightness(1.05);}
    .btn-save:disabled{opacity:.5;cursor:not-allowed;}

    .info-static-row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f4f2;font-size:14px;}
    .info-static-row:last-child{border-bottom:none;}
    .info-static-row .label{color:var(--text-muted);}
    .info-static-row .value{font-weight:600;color:var(--text-dark);}

    @media (max-width:900px){.profile-grid{grid-template-columns:1fr;} .form-row{grid-template-columns:1fr;}}
</style>

<div class="page-header">
    <h1>Profil Saya</h1>
    <p>Kelola data akun dan keamanan login kamu</p>
</div>

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@if (session('success_password'))
    <div class="alert-success">{{ session('success_password') }}</div>
@endif
@if ($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
@endif

<div class="profile-grid">
    <div class="id-card">
        <div class="avatar-big">{{ strtoupper(substr($user->name ?? 'K', 0, 1)) }}</div>
        <h2>{{ $user->name ?? '-' }}</h2>
        <div class="id-email">{{ $user->email ?? '-' }}</div>
        <span class="id-tag">{{ $user->role ?? 'Kurir' }}</span>
        <div class="id-shift">🕐 Shift: {{ $user->shiftLabel() }}</div>
        <div class="id-vehicle">
            🏍️ {{ $user->jenis_kendaraan ?? 'Kendaraan belum diisi' }}
            @if ($user->plat_nomor)
                &middot; {{ $user->plat_nomor }}
            @endif
        </div>
        <div class="shift-note">Shift diatur oleh pemilik apotek</div>

        <form method="POST" action="{{ route('staff.logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Keluar dari Akun
            </button>
        </form>
    </div>

    <div>
        <div class="card">
            <div class="card-heading">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/></svg>
                    Data Diri
                </h3>
                <button type="button" class="btn-edit-toggle" onclick="toggleEdit()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                    <span id="toggleEditText">Edit</span>
                </button>
            </div>

            <form method="POST" action="{{ route('kurir.profil.update') }}" id="profileForm">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" disabled required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" disabled required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">No. HP</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" disabled placeholder="cth: 08123456789">
                    </div>
                    <div class="form-group">
                        <label>Shift Kerja</label>
                        <div class="locked-field">
                            <span class="value">{{ $user->shiftLabel() }}</span>
                            <span class="lock-tag">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                Terkunci
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jenis_kendaraan">Jenis Kendaraan</label>
                        <input type="text" id="jenis_kendaraan" name="jenis_kendaraan"
                            value="{{ old('jenis_kendaraan', $user->jenis_kendaraan) }}"
                            disabled placeholder="cth: Honda Beat">
                    </div>
                    <div class="form-group">
                        <label for="plat_nomor">Plat Nomor</label>
                        <input type="text" id="plat_nomor" name="plat_nomor"
                            value="{{ old('plat_nomor', $user->plat_nomor) }}"
                            disabled placeholder="cth: BL 1234 XY">
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" disabled placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                </div>

                <div class="info-static-row"><span class="label">Username</span><span class="value">{{ $user->username ?? '-' }}</span></div>
                <div class="info-static-row"><span class="label">Role</span><span class="value" style="text-transform:capitalize;">{{ $user->role ?? '-' }}</span></div>
                <div class="info-static-row"><span class="label">Bergabung Sejak</span><span class="value">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</span></div>

                <div style="margin-top:20px;">
                    <button type="submit" class="btn-save" id="saveBtn" disabled>Simpan Perubahan</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 style="margin-bottom:18px;display:flex;align-items:center;gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Ganti Password
            </h3>

            <form method="POST" action="{{ route('kurir.profil.password') }}">
                @csrf
                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" placeholder="Masukkan password lama">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                    </div>
                </div>
                <button type="submit" class="btn-save">Ganti Password</button>
            </form>
        </div>
    </div>
</div>

<script>
let isEditing = false;
function toggleEdit(){
    isEditing = !isEditing;
    const inputs = document.querySelectorAll('#profileForm input, #profileForm textarea');
    const saveBtn = document.getElementById('saveBtn');
    const toggleText = document.getElementById('toggleEditText');
    inputs.forEach(input => input.disabled = !isEditing);
    saveBtn.disabled = !isEditing;
    toggleText.textContent = isEditing ? 'Batal' : 'Edit';
}
</script>
@endsection