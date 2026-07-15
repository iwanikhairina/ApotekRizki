@extends('layouts.admin')

@section('title', 'Edit Staff')

@section('content')

    <div class="page-header">
        <h1>Edit Staff — {{ $staff->name }}</h1>
        <p>Perbarui biodata, role, atau shift staff ini.</p>
    </div>

    <div class="card" style="max-width:640px;">
        <form method="POST" action="{{ route('admin.staff.update', $staff->id) }}">
            @csrf
            @method('PUT')

            @include('admin.staff._form', ['staff' => $staff])

            <button type="submit" style="width:100%; background:var(--mint-500); color:#fff; border:none; padding:13px; border-radius:10px; font-weight:700; font-size:0.92rem; cursor:pointer; margin-top:8px;">
                Simpan Perubahan
            </button>
        </form>
    </div>

@endsection