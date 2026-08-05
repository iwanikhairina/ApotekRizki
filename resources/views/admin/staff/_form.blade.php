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

@php
    $roleSekarang = old('role', $isEdit ? $staff->role : '');
    $jamMulaiValue = old('jam_antar_mulai', $isEdit && $staff->jam_antar_mulai ? \Carbon\Carbon::parse($staff->jam_antar_mulai)->format('H:i') : '');
    $jamSelesaiValue = old('jam_antar_selesai', $isEdit && $staff->jam_antar_selesai ? \Carbon\Carbon::parse($staff->jam_antar_selesai)->format('H:i') : '');
@endphp

<div id="jamAntarWrapper" style="display:{{ $roleSekarang === 'kurir' ? 'grid' : 'none' }}; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px; background:var(--mint-50); padding:14px; border-radius:12px;">
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Jam Antar Mulai</label>
        <input type="time" name="jam_antar_mulai" value="{{ $jamMulaiValue }}"
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
        <p style="font-size:0.75rem; color:var(--ink-500); margin-top:4px;">Kurir hanya menerima pesanan batch otomatis di jam ini.</p>
    </div>
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Jam Antar Selesai</label>
        <input type="time" name="jam_antar_selesai" value="{{ $jamSelesaiValue }}"
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
</div>

<script>
    (function () {
        var roleSelect = document.querySelector('select[name="role"]');
        var wrapper = document.getElementById('jamAntarWrapper');
        if (!roleSelect || !wrapper) return;
        roleSelect.addEventListener('change', function () {
            wrapper.style.display = this.value === 'kurir' ? 'grid' : 'none';
        });
    })();
</script>

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