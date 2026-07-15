@extends('layouts.admin')

@section('title', 'Tambah Obat')

@section('content')

    <div class="page-header">
        <h1>Tambah Obat Baru</h1>
        <p>Isi data obat yang akan ditampilkan ke pelanggan.</p>
    </div>

    <div class="card" style="max-width:640px;">
        <form method="POST" action="{{ route('admin.obat.store') }}" enctype="multipart/form-data">
            @csrf

            @include('admin.obat._form', ['obat' => null])

            <button type="submit" style="width:100%; background:var(--mint-500); color:#fff; border:none; padding:13px; border-radius:10px; font-weight:700; font-size:0.92rem; cursor:pointer; margin-top:8px;">
                Simpan Obat
            </button>
        </form>
    </div>

@endsection