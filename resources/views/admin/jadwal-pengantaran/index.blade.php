@extends('layouts.admin')

@section('title', 'Jadwal Pengantaran')

@push('styles')
<style>
    .jp-modal-backdrop{
        display:none;
        position:fixed; inset:0;
        background:rgba(15,23,42,0.5);
        z-index:200;
        align-items:center; justify-content:center;
        padding:20px;
    }
    .jp-modal-backdrop.open{display:flex;}
    .jp-modal{
        background:var(--surface);
        border-radius:16px;
        padding:24px;
        width:100%;
        max-width:400px;
        box-shadow:0 20px 40px -16px rgba(15,23,42,0.25);
    }
    .jp-modal h3{font-size:1.05rem; margin-bottom:6px;}
    .jp-modal p{font-size:0.85rem; color:var(--ink-500); margin-bottom:18px;}
    .jp-field{margin-bottom:14px;}
    .jp-field label{display:block; font-size:0.82rem; font-weight:600; color:var(--ink-700); margin-bottom:6px;}
    .jp-field input[type="time"]{
        width:100%; padding:10px 12px; border-radius:10px;
        border:1px solid var(--mint-100); font-size:0.9rem; color:var(--ink-900);
        font-family:'Inter', sans-serif;
    }
    .jp-field input[type="time"]:focus{outline:none; border-color:var(--mint-500);}
    .jp-checkbox{display:flex; align-items:center; gap:8px; font-size:0.85rem; color:var(--ink-700); margin-bottom:18px;}
    .jp-checkbox input{width:16px; height:16px; accent-color:var(--mint-500);}
    .jp-modal-actions{display:flex; gap:10px; justify-content:flex-end;}
    .jp-btn{
        padding:9px 18px; border-radius:10px; font-size:0.85rem; font-weight:600;
        cursor:pointer; border:none; font-family:'Inter', sans-serif;
    }
    .jp-btn.secondary{background:var(--bg); color:var(--ink-700); border:1px solid var(--ink-300);}
    .jp-btn.primary{background:var(--mint-500); color:#fff;}
    .jp-btn.primary:hover{background:var(--mint-600);}
</style>
@endpush

@section('content')

    <div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:12px;">
        <div>
            <h1>Jadwal Pengantaran</h1>
            <p>Atur rentang waktu pengantaran obat yang bisa dipilih pelanggan saat checkout, misalnya 10.00 - 11.00 WIB.</p>
        </div>
        <button type="button" class="jp-btn primary" style="display:inline-flex; align-items:center; gap:8px;" onclick="openJpModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Jadwal
        </button>
    </div>

    <div class="card" style="padding:0; overflow:hidden;">
        <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
            <colgroup>
                <col style="width:35%;">
                <col style="width:20%;">
                <col style="width:45%;">
            </colgroup>
            <thead>
                <tr style="background:var(--mint-50); text-align:left;">
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Rentang Waktu</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase;">Status</th>
                    <th style="padding:12px 16px; font-size:0.78rem; color:var(--ink-500); font-weight:600; text-transform:uppercase; text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwalList as $jadwal)
                    <tr style="border-top:1px solid var(--mint-100);">
                        <td style="padding:14px 16px; font-weight:600; font-size:0.92rem; color:var(--ink-900);">
                            {{ $jadwal->label }}
                        </td>
                        <td style="padding:14px 16px;">
                            @if($jadwal->aktif)
                                <span style="background:var(--mint-50); color:var(--spring-deep); padding:3px 10px; border-radius:999px; font-size:0.76rem; font-weight:600; white-space:nowrap;">Aktif</span>
                            @else
                                <span style="background:#f1f5f9; color:var(--ink-500); padding:3px 10px; border-radius:999px; font-size:0.76rem; font-weight:600; white-space:nowrap;">Nonaktif</span>
                            @endif
                        </td>
                        <td style="padding:14px 16px; text-align:right;">
                            <div style="display:flex; gap:8px; justify-content:flex-end; align-items:center;">
                                <button type="button"
                                    style="display:inline-flex; align-items:center; justify-content:center; height:32px; padding:0 14px; border-radius:8px; font-size:0.8rem; font-weight:600; color:var(--mint-700); background:var(--mint-50); border:1px solid var(--mint-100); cursor:pointer;"
                                    onclick="openJpModal({{ $jadwal->id }}, '{{ \Illuminate\Support\Str::substr($jadwal->jam_mulai, 0, 5) }}', '{{ \Illuminate\Support\Str::substr($jadwal->jam_selesai, 0, 5) }}', {{ $jadwal->aktif ? 'true' : 'false' }})">
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('admin.jadwal-pengantaran.toggle', $jadwal->id) }}" style="margin:0;">
                                    @csrf
                                    <button type="submit"
                                        style="display:inline-flex; align-items:center; justify-content:center; height:32px; padding:0 14px; border-radius:8px; font-size:0.8rem; font-weight:600; cursor:pointer;
                                        {{ $jadwal->aktif
                                            ? 'color:#b91c1c; background:#fef2f2; border:1px solid #fecaca;'
                                            : 'color:var(--mint-700); background:var(--mint-50); border:1px solid var(--mint-100);' }}">
                                        {{ $jadwal->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.jadwal-pengantaran.destroy', $jadwal->id) }}" style="margin:0;" onsubmit="return confirm('Hapus jadwal {{ $jadwal->label }}? Pesanan lama yang sudah memakai jadwal ini tidak akan terpengaruh.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="display:inline-flex; align-items:center; justify-content:center; height:32px; width:32px; border-radius:8px; color:#b91c1c; background:#fef2f2; border:1px solid #fecaca; cursor:pointer;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding:32px 16px; text-align:center; color:var(--ink-500); font-size:0.88rem;">
                            Belum ada jadwal pengantaran. Tambahkan slot pertama supaya pelanggan bisa memilih jadwal saat checkout.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== MODAL TAMBAH / EDIT ===== --}}
    <div class="jp-modal-backdrop" id="jpModal">
        <div class="jp-modal">
            <h3 id="jpModalTitle">Tambah Jadwal Pengantaran</h3>
            <p>Tentukan rentang waktu pengantaran, misalnya 10.00 sampai 11.00.</p>

            <form method="POST" id="jpForm" action="{{ route('admin.jadwal-pengantaran.store') }}">
                @csrf
                <div id="jpMethodField"></div>

                <div class="jp-field">
                    <label for="jam_mulai">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" required>
                </div>
                <div class="jp-field">
                    <label for="jam_selesai">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" required>
                </div>
                <label class="jp-checkbox">
                    <input type="checkbox" name="aktif" id="aktif" value="1" checked>
                    Aktifkan jadwal ini (langsung tampil ke pelanggan)
                </label>

                <div class="jp-modal-actions">
                    <button type="button" class="jp-btn secondary" onclick="closeJpModal()">Batal</button>
                    <button type="submit" class="jp-btn primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                openJpModal();
                @if(old('jam_mulai'))document.getElementById('jam_mulai').value = @json(old('jam_mulai'));@endif
                @if(old('jam_selesai'))document.getElementById('jam_selesai').value = @json(old('jam_selesai'));@endif
            });
        </script>
    @endif

    <script>
        const jpModal = document.getElementById('jpModal');
        const jpForm = document.getElementById('jpForm');
        const jpMethodField = document.getElementById('jpMethodField');
        const jpModalTitle = document.getElementById('jpModalTitle');

        function openJpModal(id, jamMulai, jamSelesai, aktif) {
            jpMethodField.innerHTML = '';

            if (id) {
                jpModalTitle.textContent = 'Ubah Jadwal Pengantaran';
                jpForm.action = '{{ url('admin/jadwal-pengantaran') }}/' + id;
                jpMethodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                document.getElementById('jam_mulai').value = jamMulai || '';
                document.getElementById('jam_selesai').value = jamSelesai || '';
                document.getElementById('aktif').checked = aktif !== false;
            } else {
                jpModalTitle.textContent = 'Tambah Jadwal Pengantaran';
                jpForm.action = '{{ route('admin.jadwal-pengantaran.store') }}';
                document.getElementById('jam_mulai').value = '';
                document.getElementById('jam_selesai').value = '';
                document.getElementById('aktif').checked = true;
            }

            jpModal.classList.add('open');
        }

        function closeJpModal() {
            jpModal.classList.remove('open');
        }

        jpModal.addEventListener('click', (e) => {
            if (e.target === jpModal) closeJpModal();
        });
    </script>

@endsection
