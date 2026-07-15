@php
    $isEdit = $obat !== null;
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

<div style="display:flex; gap:20px; margin-bottom:16px; align-items:flex-start;">
    <div style="width:120px; height:120px; border-radius:14px; background:var(--mint-50); border:2px dashed var(--mint-100); display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0;" id="previewBox">
        @if($isEdit && $obat->gambar)
            <img src="{{ Storage::url($obat->gambar) }}" alt="{{ $obat->nama }}" id="previewImg" style="width:100%; height:100%; object-fit:cover;">
        @else
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--mint-500)" stroke-width="1.5" id="previewIcon"><path d="M10.5 20.5L20.5 10.5a4.95 4.95 0 00-7-7L3.5 13.5a4.95 4.95 0 007 7z"/><path d="M8.5 8.5l7 7"/></svg>
            <img src="" alt="" id="previewImg" style="display:none; width:100%; height:100%; object-fit:cover;">
        @endif
    </div>
    <div style="flex:1;">
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Foto Obat</label>
        <input type="file" name="gambar" accept="image/*" onchange="previewGambar(this)"
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.85rem; background:#fff;">
        <p style="font-size:0.76rem; color:var(--ink-500); margin-top:6px;">Format JPG/PNG, maks 2MB. {{ $isEdit ? 'Kosongkan jika tidak ingin mengganti foto.' : '' }}</p>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Nama Obat</label>
        <input type="text" name="nama" value="{{ old('nama', $isEdit ? $obat->nama : '') }}" required
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Kategori</label>
        <select name="kategori" required style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem; background:#fff;">
            <option value="">Pilih Kategori</option>
            @foreach($daftarKategori as $kat)
                <option value="{{ $kat }}" {{ old('kategori', $isEdit ? $obat->kategori : '') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
            @endforeach
        </select>
    </div>
</div>

<div style="margin-bottom:14px;">
    <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Deskripsi</label>
    <textarea name="deskripsi" rows="3"
        style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem; font-family:inherit;">{{ old('deskripsi', $isEdit ? $obat->deskripsi : '') }}</textarea>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Jenis Obat</label>
        <select name="klasifikasi" required style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem; background:#fff;">
            @php $klas = old('klasifikasi', $isEdit ? $obat->klasifikasi : 'obat_bebas'); @endphp
            <option value="obat_bebas" {{ $klas === 'obat_bebas' ? 'selected' : '' }}>Obat Bebas</option>
            <option value="obat_bebas_terbatas" {{ $klas === 'obat_bebas_terbatas' ? 'selected' : '' }}>Obat Bebas Terbatas</option>
            <option value="obat_keras" {{ $klas === 'obat_keras' ? 'selected' : '' }}>Obat Keras</option>
        </select>
    </div>
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Tanggal Kadaluarsa</label>
        <input type="date" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa', $isEdit ? $obat->tanggal_kadaluarsa : '') }}"
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Harga (Rp)</label>
        <input type="number" name="harga" value="{{ old('harga', $isEdit ? $obat->harga : '') }}" min="0" step="1" required
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
    <div>
        <label style="display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;">Stok</label>
        <input type="number" name="stok" value="{{ old('stok', $isEdit ? $obat->stok : '') }}" min="0" required
            style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid var(--mint-100); font-size:0.9rem;">
    </div>
</div>

<div style="display:flex; gap:24px; margin-bottom:6px;">
    <label style="display:flex; align-items:center; gap:8px; font-size:0.88rem; color:var(--ink-700); cursor:pointer;">
        <input type="checkbox" name="butuh_resep" value="1" {{ old('butuh_resep', $isEdit ? $obat->butuh_resep : false) ? 'checked' : '' }}>
        Perlu Resep Dokter
    </label>
    <label style="display:flex; align-items:center; gap:8px; font-size:0.88rem; color:var(--ink-700); cursor:pointer;">
        <input type="checkbox" name="butuh_ktp" value="1" {{ old('butuh_ktp', $isEdit ? $obat->butuh_ktp : false) ? 'checked' : '' }}>
        Perlu KTP
    </label>
</div>

<script>
function previewGambar(input) {
    const box = document.getElementById('previewBox');
    const img = document.getElementById('previewImg');
    const icon = document.getElementById('previewIcon');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            img.src = e.target.result;
            img.style.display = 'block';
            if (icon) icon.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>