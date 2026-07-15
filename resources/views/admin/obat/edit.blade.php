@extends('layouts.admin')

@section('title', 'Edit Obat')

@section('content')

    <div class="page-header">
        <h1>Edit Obat — {{ $obat->nama }}</h1>
        <p>Perbarui data obat ini.</p>
    </div>

    <div class="card" style="max-width:640px;">
        <form method="POST" action="{{ route('admin.obat.update', $obat->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.obat._form', ['obat' => $obat])

            <button type="submit" style="width:100%; background:var(--mint-500); color:#fff; border:none; padding:13px; border-radius:10px; font-weight:700; font-size:0.92rem; cursor:pointer; margin-top:8px;">
                Simpan Perubahan
            </button>
        </form>
    </div>

@endsection