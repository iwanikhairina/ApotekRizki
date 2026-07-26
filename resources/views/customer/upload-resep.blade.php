<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Upload Resep - Apotek Rizki</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root{
        --mint:#EAF7F0;
        --mint-deep:#D3EFE0;
        --spring:#12A874;
        --spring-deep:#0C7E57;
        --ink:#1D2B26;
        --muted:#7C8B84;
        --white:#FFFFFF;
        --error:#E0503B;
        --peach:#FFE4D8;
        --amber:#E8A33D;
        --lilac:#8C7AE6;
        --sky:#4E9BD9;
        --leaf:#6FA83C;
        --coral:#E0715B;
        --teal:#2FA5A0;
        --shadow-sm:0 6px 16px -8px rgba(29,43,38,0.14);
        --shadow-md:0 20px 40px -16px rgba(29,43,38,0.18);
    }

    *{margin:0;padding:0;box-sizing:border-box;}

    body{
        font-family:'Inter', sans-serif;
        background:var(--mint);
        color:var(--ink);
        -webkit-font-smoothing:antialiased;
    }

    a{text-decoration:none; color:inherit;}
    button{font-family:inherit;}

    .page-header{
        max-width:1240px;
        margin:0 auto;
        padding:34px 28px 6px;
    }

    .breadcrumb{
        display:flex;
        align-items:center;
        gap:6px;
        font-size:12.5px;
        color:var(--muted);
        margin-bottom:14px;
    }
    .breadcrumb a{color:var(--muted); font-weight:600;}
    .breadcrumb a:hover{color:var(--spring-deep);}
    .breadcrumb svg{width:13px; height:13px; flex-shrink:0;}
    .breadcrumb .current{color:var(--spring-deep); font-weight:700;}

    .page-header h1{
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:26px;
        color:var(--ink);
        margin-bottom:6px;
    }

    .page-header p{
        font-size:13.5px;
        color:var(--muted);
    }

    .alert-wrap{
        max-width:1240px;
        margin:18px auto 0;
        padding:0 28px;
    }
    .alert{
        display:flex;
        align-items:flex-start;
        gap:10px;
        padding:14px 16px;
        border-radius:16px;
        font-size:13px;
        font-weight:600;
        margin-bottom:10px;
    }
    .alert svg{width:18px; height:18px; flex-shrink:0; margin-top:1px;}
    .alert-success{background:#E1F5EA; color:var(--spring-deep);}
    .alert-error{background:#FCE7E3; color:var(--error);}
    .alert ul{margin-top:4px; padding-left:16px; font-weight:500;}

    .upload-section{
        max-width:1240px;
        margin:0 auto;
        padding:24px 28px 60px;
        display:grid;
        grid-template-columns:1.3fr 1fr;
        gap:22px;
        align-items:start;
    }

    .panel{
        background:var(--white);
        border-radius:22px;
        box-shadow:var(--shadow-sm);
        padding:26px;
    }

    .dropzone{
        border:2px dashed var(--mint-deep);
        border-radius:20px;
        padding:48px 24px;
        display:flex;
        flex-direction:column;
        align-items:center;
        text-align:center;
        gap:6px;
        cursor:pointer;
        transition:border-color .18s, background .18s;
        position:relative;
    }
    .dropzone:hover, .dropzone.dragover{
        border-color:var(--spring);
        background:var(--mint);
    }
    .dropzone.has-file{
        border-style:solid;
        border-color:var(--spring);
        background:var(--mint);
        padding:24px;
    }

    .dropzone-icon{
        width:60px; height:60px;
        border-radius:50%;
        background:var(--mint);
        color:var(--spring-deep);
        display:flex;
        align-items:center;
        justify-content:center;
        margin-bottom:8px;
        transition:background .18s;
    }
    .dropzone-icon svg{width:26px; height:26px;}

    .dropzone-title{
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:15.5px;
        color:var(--ink);
    }

    .dropzone-sub{
        font-size:12.5px;
        color:var(--muted);
    }

    .dropzone-browse-btn{
        margin-top:10px;
        background:var(--ink);
        color:var(--white);
        border:none;
        padding:11px 22px;
        border-radius:999px;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13px;
        cursor:pointer;
        transition:background .15s, transform .12s;
    }
    .dropzone-browse-btn:hover{background:#0f1a16;}
    .dropzone-browse-btn:active{transform:scale(.97);}

    .dropzone-format{
        margin-top:10px;
        font-size:11px;
        color:var(--muted);
    }

    .dropzone input[type="file"]{
        position:absolute;
        inset:0;
        opacity:0;
        cursor:pointer;
    }

    .file-preview{
        display:none;
        align-items:center;
        gap:16px;
        width:100%;
        text-align:left;
    }
    .file-preview.show{display:flex;}
    .dropzone.has-file .dropzone-empty{display:none;}

    .file-preview-thumb{
        width:78px; height:78px;
        border-radius:14px;
        overflow:hidden;
        background:var(--white);
        border:1px solid var(--mint-deep);
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
    }
    .file-preview-thumb img{width:100%; height:100%; object-fit:cover;}

    .file-preview-info{flex:1; min-width:0;}
    .file-preview-name{
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13.5px;
        color:var(--ink);
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }
    .file-preview-size{
        font-size:12px;
        color:var(--muted);
        margin-top:2px;
    }
    .file-preview-status{
        display:inline-flex;
        align-items:center;
        gap:5px;
        margin-top:6px;
        font-size:11px;
        font-weight:700;
        color:var(--spring-deep);
        background:var(--white);
        padding:4px 10px;
        border-radius:999px;
    }
    .file-preview-status svg{width:12px; height:12px;}

    .file-remove-btn{
        width:34px; height:34px;
        border-radius:11px;
        border:none;
        background:var(--white);
        color:var(--error);
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        flex-shrink:0;
        transition:background .15s;
    }
    .file-remove-btn:hover{background:#FCE7E3;}
    .file-remove-btn svg{width:16px; height:16px;}

    .field-error{
        color:var(--error);
        font-size:12px;
        font-weight:600;
        margin-top:8px;
        display:none;
    }
    .field-error.show{display:block;}

    .form-group{margin-top:20px;}
    .form-group label{
        display:block;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:13px;
        color:var(--ink);
        margin-bottom:8px;
    }
    .form-group label .optional{
        font-family:'Inter', sans-serif;
        font-weight:500;
        color:var(--muted);
        font-size:11.5px;
    }

    .form-control{
        width:100%;
        border:2px solid var(--mint-deep);
        border-radius:14px;
        padding:12px 14px;
        font-size:13.5px;
        font-family:inherit;
        color:var(--ink);
        outline:none;
        transition:border-color .15s;
        background:var(--white);
        resize:vertical;
    }
    .form-control:focus{border-color:var(--spring);}
    .form-control::placeholder{color:var(--muted);}

    .submit-btn{
        margin-top:24px;
        width:100%;
        background:var(--spring);
        color:var(--white);
        border:none;
        padding:15px;
        border-radius:16px;
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:14.5px;
        cursor:pointer;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        transition:background .15s, transform .12s;
    }
    .submit-btn:hover{background:var(--spring-deep);}
    .submit-btn:active{transform:scale(.99);}
    .submit-btn:disabled{
        background:var(--mint-deep);
        color:var(--muted);
        cursor:not-allowed;
    }
    .submit-btn svg{width:17px; height:17px;}

    .side-panel{
        display:flex;
        flex-direction:column;
        gap:18px;
    }

    .panel h3{
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:15.5px;
        color:var(--ink);
        margin-bottom:16px;
    }

    .step-list{
        list-style:none;
        display:flex;
        flex-direction:column;
        gap:14px;
    }
    .step-list li{
        display:flex;
        align-items:flex-start;
        gap:12px;
    }
    .step-num{
        width:24px; height:24px;
        border-radius:50%;
        background:var(--mint);
        color:var(--spring-deep);
        font-family:'Outfit', sans-serif;
        font-weight:700;
        font-size:11.5px;
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
    }
    .step-list p{
        font-size:13px;
        color:var(--ink);
        line-height:1.5;
        padding-top:2px;
    }

    .notice-panel{
        background:var(--mint);
        border:1px solid var(--mint-deep);
    }
    .notice-panel h3{
        display:flex;
        align-items:center;
        gap:8px;
        color:var(--spring-deep);
    }
    .notice-panel h3 svg{width:17px; height:17px;}
    .notice-list{
        list-style:none;
        display:flex;
        flex-direction:column;
        gap:9px;
    }
    .notice-list li{
        display:flex;
        align-items:flex-start;
        gap:9px;
        font-size:12.5px;
        color:var(--ink);
        line-height:1.4;
    }
    .notice-list li::before{
        content:'';
        width:5px; height:5px;
        border-radius:50%;
        background:var(--spring);
        margin-top:6px;
        flex-shrink:0;
    }

    @media (max-width:920px){
        .upload-section{grid-template-columns:1fr;}
    }

    @media (max-width:560px){
        .page-header{padding:26px 18px 6px;}
        .alert-wrap{padding:0 18px;}
        .upload-section{padding:20px 18px 50px;}
        .page-header h1{font-size:21px;}
        .panel{padding:20px;}
        .dropzone{padding:34px 16px;}
    }
</style>
</head>
<body>

<x-customer-navbar />

<header class="page-header">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Beranda</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        <span class="current">Upload Resep</span>
    </div>
    <h1>Upload Resep</h1>
    <p>Unggah resep yang valid dari dokter Anda untuk memesan obat dengan resep</p>
</header>

@if(session('success'))
<div class="alert-wrap">
    <div class="alert alert-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m22 4-10 10-3-3"/></svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@if($errors->any())
<div class="alert-wrap">
    <div class="alert alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <div>
            Terjadi kesalahan pada input Anda:
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<section class="upload-section">
    <div class="panel">
        <form action="{{ route('resep.store') }}" method="POST" enctype="multipart/form-data" id="resepForm">
            @csrf

            <div class="dropzone" id="dropzone">
                <div class="dropzone-empty">
                    <div class="dropzone-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v14M6 9l6-6 6 6"/><path d="M4 21h16"/></svg>
                    </div>
                    <div class="dropzone-title">Seret dan lepas resep Anda di sini</div>
                    <div class="dropzone-sub">atau klik untuk memilih file</div>
                    <button type="button" class="dropzone-browse-btn">Pilih File</button>
                    <div class="dropzone-format">Format yang didukung: JPG, PNG (Maks 10MB)</div>
                </div>

                <div class="file-preview" id="filePreview">
                    <div class="file-preview-thumb" id="previewThumb"></div>
                    <div class="file-preview-info">
                        <div class="file-preview-name" id="previewName">nama-file.jpg</div>
                        <div class="file-preview-size" id="previewSize">0 KB</div>
                        <div class="file-preview-status">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m22 4-10 10-3-3"/></svg>
                            Siap diunggah
                        </div>
                    </div>
                    <button type="button" class="file-remove-btn" id="removeFileBtn" aria-label="Hapus file">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <input type="file" name="resep" id="resepInput" accept=".jpg,.jpeg,.png">
            </div>
            <div class="field-error" id="fileError">Silakan pilih file resep terlebih dahulu (JPG atau PNG, maks 10MB).</div>

            <div class="form-group">
                <label for="nama_pasien">Nama Pasien</label>
                <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" placeholder="Sesuai nama pada resep" value="{{ old('nama_pasien') }}">
            </div>

            <div class="form-group">
                <label for="nama_dokter">Nama Dokter <span class="optional">(opsional)</span></label>
                <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" placeholder="Contoh: dr. Andini Putri" value="{{ old('nama_dokter') }}">
            </div>

            <div class="form-group">
                <label for="catatan">Catatan Tambahan <span class="optional">(opsional)</span></label>
                <textarea class="form-control" id="catatan" name="catatan" rows="3" placeholder="Contoh: alamat pengiriman, jumlah obat, dsb.">{{ old('catatan') }}</textarea>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v14M6 9l6-6 6 6"/><path d="M4 21h16"/></svg>
                Unggah Resep
            </button>
        </form>
    </div>

    <div class="side-panel">
        <div class="panel">
            <h3>Petunjuk</h3>
            <ul class="step-list">
                <li>
                    <span class="step-num">1</span>
                    <p>Ambil foto yang jelas dari resep Anda atau pindai sebagai gambar</p>
                </li>
                <li>
                    <span class="step-num">2</span>
                    <p>Pastikan semua detail terlihat jelas dan dapat dibaca</p>
                </li>
                <li>
                    <span class="step-num">3</span>
                    <p>Unggah resep menggunakan formulir</p>
                </li>
                <li>
                    <span class="step-num">4</span>
                    <p>Apoteker kami akan memverifikasi dan menyetujui resep Anda</p>
                </li>
                <li>
                    <span class="step-num">5</span>
                    <p>Anda akan menerima notifikasi setelah obat siap dipesan</p>
                </li>
            </ul>
        </div>

        <div class="panel notice-panel">
            <h3>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                Catatan Penting
            </h3>
            <ul class="notice-list">
                <li>Resep harus valid dan belum kedaluwarsa</li>
                <li>Tanda tangan dan stempel dokter harus terlihat</li>
                <li>Nama pasien harus sesuai dengan detail akun Anda</li>
                <li>File yang diunggah wajib berformat JPG atau PNG</li>
                <li>Ukuran file maksimal 10MB</li>
            </ul>
        </div>
    </div>
</section>

<script>
(function(){
    const dropzone = document.getElementById('dropzone');
    const resepInput = document.getElementById('resepInput');
    const filePreview = document.getElementById('filePreview');
    const previewThumb = document.getElementById('previewThumb');
    const previewName = document.getElementById('previewName');
    const previewSize = document.getElementById('previewSize');
    const removeFileBtn = document.getElementById('removeFileBtn');
    const fileError = document.getElementById('fileError');
    const resepForm = document.getElementById('resepForm');

    const ALLOWED_TYPES = ['image/jpeg', 'image/jpg', 'image/png'];
    const MAX_SIZE = 10 * 1024 * 1024;

    function formatSize(bytes){
        if(bytes < 1024) return bytes + ' B';
        if(bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024*1024)).toFixed(1) + ' MB';
    }

    function showError(msg){
        fileError.textContent = msg;
        fileError.classList.add('show');
    }

    function clearError(){
        fileError.classList.remove('show');
    }

    function renderPreview(file){
        previewName.textContent = file.name;
        previewSize.textContent = formatSize(file.size);

        const reader = new FileReader();
        reader.onload = (e) => {
            previewThumb.innerHTML = '<img src="' + e.target.result + '" alt="Preview resep">';
        };
        reader.readAsDataURL(file);

        dropzone.classList.add('has-file');
        filePreview.classList.add('show');
    }

    function resetDropzone(){
        dropzone.classList.remove('has-file');
        filePreview.classList.remove('show');
        previewThumb.innerHTML = '';
        resepInput.value = '';
        clearError();
    }

    function handleFile(file){
        if(!file) return;

        if(!ALLOWED_TYPES.includes(file.type)){
            showError('Format file tidak didukung. Gunakan JPG atau PNG.');
            resepInput.value = '';
            return;
        }

        if(file.size > MAX_SIZE){
            showError('Ukuran file terlalu besar. Maksimal 10MB.');
            resepInput.value = '';
            return;
        }

        clearError();
        renderPreview(file);
    }

    resepInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        handleFile(file);
    });

    ['dragenter', 'dragover'].forEach(evt => {
        dropzone.addEventListener(evt, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(evt => {
        dropzone.addEventListener(evt, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        const file = e.dataTransfer.files[0];
        if(file){
            const dt = new DataTransfer();
            dt.items.add(file);
            resepInput.files = dt.files;
            handleFile(file);
        }
    });

    removeFileBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        resetDropzone();
    });

    resepForm.addEventListener('submit', (e) => {
        if(!resepInput.files || resepInput.files.length === 0){
            e.preventDefault();
            showError('Silakan pilih file resep terlebih dahulu (JPG atau PNG, maks 10MB).');
            dropzone.scrollIntoView({behavior:'smooth', block:'center'});
        }
    });
})();
</script>

</body>
</html>