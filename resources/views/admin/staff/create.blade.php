@extends('layouts.admin')

@section('title', 'Tambah Staff')

@section('content')

    <div class="page-header">
        <h1>Tambah Staff Baru</h1>
        <p>Buat akun untuk Apoteker atau Kurir baru.</p>
    </div>

    <div class="card" style="max-width:640px;">
        <form method="POST" action="{{ route('admin.staff.store') }}">
            @csrf

            @include('admin.staff._form', ['staff' => null])

            <button type="submit" style="width:100%; background:var(--mint-500); color:#fff; border:none; padding:13px; border-radius:10px; font-weight:700; font-size:0.92rem; cursor:pointer; margin-top:8px;">
                Simpan Staff
            </button>
        </form>
    </div>

@endsection
