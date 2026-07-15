@php
    $isEdit = $staff !== null;
    $tanggalLahirValue = old('tanggal_lahir', $isEdit && $staff->tanggal_lahir ? \Carbon\Carbon::parse($staff->tanggal_lahir)->format('Y-m-d') : '');
@endphp

@if ($errors->any())
    <div class="flash flash-error">
        <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name', $isEdit ? $staff->name : '') }}" required
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Nama Pengguna (Username)</label>
        <input type="text" name="username" value="{{ old('username', $isEdit ? $staff->username : '') }}" required
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Email</label>
        <input type="email" name="email" value="{{ old('email', $isEdit ? $staff->email : '') }}" required
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">No. Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $isEdit ? $staff->phone : '') }}" required
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
</div>

<div style="margin-bottom:14px;">
    <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Alamat</label>
    <textarea name="alamat" rows="2" required
        style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem; font-family:inherit;">{{ old('alamat', $isEdit ? $staff->alamat : '') }}</textarea>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; margin-bottom:14px;">
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="{{ $tanggalLahirValue }}" required
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Role</label>
        <select name="role" required style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem; background:#fff;">
            <option value="">Pilih Role</option>
            <option value="apoteker" {{ old('role', $isEdit ? $staff->role : '') === 'apoteker' ? 'selected' : '' }}>Apoteker</option>
            <option value="kurir" {{ old('role', $isEdit ? $staff->role : '') === 'kurir' ? 'selected' : '' }}>Kurir</option>
        </select>
    </div>
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Shift</label>
        <select name="shift" required style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem; background:#fff;">
            <option value="">Pilih Shift</option>
            <option value="pagi" {{ old('shift', $isEdit ? $staff->shift : '') === 'pagi' ? 'selected' : '' }}>Pagi (08.00 - 17.00)</option>
            <option value="sore" {{ old('shift', $isEdit ? $staff->shift : '') === 'sore' ? 'selected' : '' }}>Sore (17.00 - 22.00)</option>
        </select>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:6px;">
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">
            Password {{ $isEdit ? '(kosongkan jika tidak diubah)' : '' }}
        </label>
        <input type="password" name="password" {{ $isEdit ? '' : 'required' }}
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" {{ $isEdit ? '' : 'required' }}
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
</div>