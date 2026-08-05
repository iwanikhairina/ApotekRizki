@extends('layouts.kurir')

@section('title', 'Pengiriman')

@section('content')
<style>
    .page-header{margin-bottom:24px;}
    .page-header h1{font-family:'Outfit',sans-serif;font-size:26px;font-weight:700;color:var(--text-dark);}
    .page-header p{color:var(--text-muted);font-size:14px;margin-top:4px;}
    .alert-success{background:var(--mint-50);border:1px solid var(--mint-100);color:var(--mint-700);padding:12px 16px;border-radius:12px;font-size:14px;margin-bottom:20px;font-weight:600;max-width:900px;}
    .alert-error{background:#fff1f0;border:1px solid #ffccc7;color:#cf1322;padding:12px 16px;border-radius:12px;font-size:14px;margin-bottom:20px;font-weight:600;max-width:900px;}

    .wrapper{max-width:900px;}

    .batch-banner{
        background:linear-gradient(135deg, var(--mint-500), var(--mint-700));
        border-radius:16px;
        padding:18px 22px;
        color:#fff;
        margin-bottom:20px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:10px;
    }
    .batch-banner .batch-title{font-family:'Outfit',sans-serif;font-weight:800;font-size:18px;}
    .batch-banner .batch-sub{font-size:12.5px;opacity:.9;margin-top:2px;}
    .batch-progress{font-family:'Outfit',sans-serif;font-size:22px;font-weight:800;text-align:right;}

    .timer-box{
        background:linear-gradient(135deg, var(--mint-500), var(--mint-700));
        border-radius:16px;
        padding:20px 22px;
        color:#fff;
        text-align:center;
        margin-bottom:20px;
    }
    .timer-box .timer-label{font-size:12.5px;opacity:.9;font-weight:600;margin-bottom:6px;display:flex;align-items:center;justify-content:center;gap:6px;}
    .timer-box .timer-value{font-family:'Outfit',sans-serif;font-size:34px;font-weight:800;letter-spacing:1px;}
    .timer-box .timer-sub{font-size:11.5px;opacity:.8;margin-top:6px;}
    .timer-box.overdue{background:linear-gradient(135deg, #f59e0b, #d97706);}

    .grid{
        display:grid;
        grid-template-columns:1.3fr 1fr;
        gap:20px;
        align-items:start;
    }

    .card{
        background:#fff;
        border-radius:20px;
        padding:24px;
        box-shadow:0 8px 24px rgba(15,47,34,.06);
        margin-bottom:20px;
    }
    .card h3{font-family:'Outfit',sans-serif;font-size:16px;font-weight:700;margin-bottom:16px;color:var(--text-dark);}
    .info-row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f4f2;font-size:14px;}
    .info-row:last-child{border-bottom:none;}
    .info-row .label{color:var(--text-muted);}
    .info-row .value{font-weight:600;color:var(--text-dark);text-align:right;}

    .maps-frame{width:100%;height:260px;border:none;border-radius:14px;margin-top:14px;}
    .maps-link{display:inline-flex;align-items:center;gap:6px;background:var(--blue-soft);color:#3b82f6;text-decoration:none;font-size:12.5px;font-weight:700;padding:8px 16px;border-radius:999px;transition:.15s ease;margin-top:10px;}
    .maps-link:hover{background:#dbeafe;}

    .phone-link{color:var(--mint-700);text-decoration:none;font-weight:700;display:inline-flex;align-items:center;gap:5px;}
    .phone-link:hover{text-decoration:underline;}

    .obat-item{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f4f2;font-size:14px;}
    .obat-item:last-child{border-bottom:none;}

    .cod-box{background:linear-gradient(135deg, var(--mint-500), var(--mint-700));border-radius:16px;padding:20px 22px;color:#fff;margin-top:14px;}
    .cod-box .cod-label{font-size:12.5px;opacity:.9;font-weight:600;margin-bottom:4px;display:flex;align-items:center;gap:6px;}
    .cod-box .cod-amount{font-family:'Outfit',sans-serif;font-size:26px;font-weight:800;}
    .cod-box .cod-note{font-size:11.5px;opacity:.8;margin-top:6px;}
    .transfer-box{background:var(--blue-soft);border:1px solid #bfdbfe;border-radius:16px;padding:16px 20px;margin-top:14px;color:#1d4ed8;font-size:13.5px;font-weight:600;}
    .total-row{display:flex;justify-content:space-between;padding-top:14px;margin-top:6px;border-top:2px solid var(--mint-100);font-size:15px;font-weight:800;color:var(--text-dark);}

    .sticky-col{position:sticky;top:90px;}

    .action-buttons{display:flex;flex-direction:column;gap:10px;}
    .btn-selesai{border:none;padding:14px;border-radius:14px;font-size:14px;font-weight:700;font-family:'Outfit',sans-serif;cursor:pointer;background:var(--mint-500);color:#fff;width:100%;}
    .btn-selesai:hover{background:var(--mint-700);}
    .btn-batal{border:1.5px solid #ffd4d0;padding:14px;border-radius:14px;font-size:14px;font-weight:700;font-family:'Outfit',sans-serif;cursor:pointer;background:#fff1f0;color:#e0433c;width:100%;}
    .btn-batal:hover{background:#ffe4e1;}

    .empty-state{text-align:center;padding:60px 20px;color:var(--text-muted);background:#fff;border-radius:20px;box-shadow:0 8px 24px rgba(15,47,34,.06);max-width:900px;}

    .stop-list{list-style:none;padding:0;margin:0;}
    .stop-list li{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #f0f4f2;font-size:13.5px;}
    .stop-list li:last-child{border-bottom:none;}
    .stop-num{width:26px;height:26px;border-radius:50%;background:var(--mint-50);color:var(--mint-700);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;flex-shrink:0;}
    .stop-num.done{background:var(--mint-500);color:#fff;}
    .stop-info{flex:1;}
    .stop-info .nama{font-weight:700;color:var(--text-dark);}
    .stop-info .alamat{color:var(--text-muted);font-size:12.5px;}
    .stop-jarak{color:var(--mint-700);font-weight:700;font-size:12.5px;white-space:nowrap;}
    .stop-status{font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;white-space:nowrap;}
    .stop-status.selesai{background:var(--mint-50);color:var(--mint-700);}
    .stop-status.batal{background:#fff1f0;color:#e0433c;}

    .modal-overlay{display:none;position:fixed;inset:0;background:rgba(15,47,34,.4);z-index:100;align-items:center;justify-content:center;padding:20px;}
    .modal-overlay.open{display:flex;}
    .modal-box{background:#fff;border-radius:20px;padding:26px;max-width:420px;width:100%;}
    .modal-box h3{font-family:'Outfit',sans-serif;font-size:17px;font-weight:700;margin-bottom:14px;color:var(--text-dark);}
    .modal-box label{display:block;font-size:13px;font-weight:600;color:var(--text-dark);margin-bottom:8px;}
    .modal-box select,
    .modal-box textarea{width:100%;background:#eef6ff;border:2px solid transparent;border-radius:12px;padding:11px 14px;font-size:14px;font-family:'Inter',sans-serif;color:var(--text-dark);outline:none;margin-bottom:14px;}
    .modal-box select:focus,
    .modal-box textarea:focus{border-color:var(--mint-500);background:#fff;}
    .modal-buttons{display:flex;gap:10px;}
    .modal-buttons button{flex:1;padding:12px;border-radius:12px;font-size:13.5px;font-weight:700;font-family:'Outfit',sans-serif;cursor:pointer;border:none;}
    .btn-modal-cancel{background:#f1f5f4;color:var(--text-muted);}
    .btn-modal-confirm{background:#e0433c;color:#fff;}

    @media (max-width:900px){
        .grid{grid-template-columns:1fr;}
        .sticky-col{position:static;}
    }
</style>

<div class="page-header">
    <h1>Pengiriman Berlangsung</h1>
    <p>Batch pengantaran yang sedang kamu jalani saat ini — rute sudah diurutkan dari rumah terdekat</p>
</div>

<div class="wrapper">
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if (session('shift_error'))
        <div class="alert-error">⚠ {{ session('shift_error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif
</div>

@if ($batch && $stopSaatIni)
    @php
        $pesanan = $stopSaatIni; // stop yang sedang diantar sekarang
        $estimasiSelesai = $pesanan->estimasiSelesaiAt();
        $selesaiCount = $batch->jumlahSelesai();
    @endphp

    <div class="wrapper">
        <div class="batch-banner">
            <div>
                <div class="batch-title">Batch #{{ $batch->id }} — {{ $batch->jumlah_pesanan }} Rumah</div>
                <div class="batch-sub">Total jarak rute ± {{ $batch->total_jarak_km ?? '-' }} km</div>
            </div>
            <div class="batch-progress">{{ $selesaiCount }} / {{ $batch->jumlah_pesanan }}</div>
        </div>

        <div class="timer-box" id="timerBox">
            <div class="timer-label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Mengantar Rumah ke-{{ $pesanan->urutan_pengiriman }}
            </div>
            <div class="timer-value" id="timerValue">--:--</div>
            <div class="timer-sub">Jarak {{ $pesanan->jarak_leg_km ?? '-' }} km dari titik sebelumnya</div>
        </div>
    </div>

    <div class="wrapper grid">
        <div>
            <div class="card">
                <h3>Informasi Pelanggan (Rumah ke-{{ $pesanan->urutan_pengiriman }})</h3>
                <div class="info-row">
                    <span class="label">Nama</span>
                    <span class="value">{{ $pesanan->user->name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">No. HP</span>
                    <span class="value">
                        @if ($pesanan->user->phone ?? null)
                            <a href="tel:{{ $pesanan->user->phone }}" class="phone-link">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
                                {{ $pesanan->user->phone }}
                            </a>
                        @else
                            Tidak tersedia
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Alamat</span>
                    <span class="value">{{ $pesanan->alamat ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Metode Pembayaran</span>
                    <span class="value">{{ $pesanan->metode_pembayaran ?? '-' }}</span>
                </div>

                @if ($pesanan->googleMapsEmbedUrl())
                    <iframe class="maps-frame" src="{{ $pesanan->googleMapsEmbedUrl() }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <a href="{{ $pesanan->googleMapsDirectionUrl() }}" target="_blank" class="maps-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        Buka Rute di Aplikasi Google Maps
                    </a>
                @endif
            </div>

            <div class="card">
                <h3>Daftar Obat</h3>
                @forelse ($pesanan->detailPesanan as $detail)
                    <div class="obat-item">
                        <span>{{ $detail->obat->nama ?? '-' }}</span>
                        <span>{{ $detail->jumlah }} pcs</span>
                    </div>
                @empty
                    <p style="font-size:14px;color:var(--text-muted);">Tidak ada data obat.</p>
                @endforelse
            </div>

            <div class="card">
                <h3>Urutan Rute Batch Ini</h3>
                <ul class="stop-list">
                    @foreach ($batch->pesanan as $stop)
                        <li>
                            <span class="stop-num {{ in_array($stop->status, ['selesai','dibatalkan_kurir']) ? 'done' : '' }}">
                                {{ $stop->urutan_pengiriman }}
                            </span>
                            <span class="stop-info">
                                <span class="nama">{{ $stop->user->name ?? '-' }}</span><br>
                                <span class="alamat">{{ \Illuminate\Support\Str::limit($stop->alamat, 45) }}</span>
                            </span>
                            @if ($stop->status === 'selesai')
                                <span class="stop-status selesai">✓ Selesai</span>
                            @elseif ($stop->status === 'dibatalkan_kurir')
                                <span class="stop-status batal">✕ Dibatalkan</span>
                            @else
                                <span class="stop-jarak">{{ $stop->jarak_leg_km ?? '-' }} km</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="sticky-col">
            <div class="card">
                <h3>Rincian Biaya</h3>
                <div class="info-row">
                    <span class="label">Total Harga Obat</span>
                    <span class="value">Rp{{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Ongkos Kirim</span>
                    <span class="value">Rp{{ number_format($pesanan->ongkir ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>Total</span>
                    <span>Rp{{ number_format($pesanan->totalKeseluruhan(), 0, ',', '.') }}</span>
                </div>

                @if (str_contains(strtolower($pesanan->metode_pembayaran ?? ''), 'cod'))
                    <div class="cod-box">
                        <div class="cod-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                            Uang yang harus diterima
                        </div>
                        <div class="cod-amount">Rp{{ number_format($pesanan->totalKeseluruhan(), 0, ',', '.') }}</div>
                        <div class="cod-note">Pastikan jumlah uang sudah sesuai sebelum menekan "Selesai".</div>
                    </div>
                @else
                    <div class="transfer-box">
                        💳 Sudah dibayar via {{ $pesanan->metode_pembayaran ?? 'transfer' }}.
                    </div>
                @endif
            </div>

            <div class="card">
                <div class="action-buttons">
                    <form method="POST" action="{{ route('kurir.pengiriman.selesai', $pesanan->id) }}" onsubmit="return confirm('Konfirmasi: apakah pembayaran sudah diterima dan obat sudah diserahkan ke pelanggan?');">
                        @csrf
                        <button type="submit" class="btn-selesai">✓ Selesai Rumah Ini</button>
                    </form>
                    <button type="button" class="btn-batal" onclick="document.getElementById('batalModal').classList.add('open')">✕ Batalkan Rumah Ini</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="batalModal">
        <div class="modal-box">
            <h3>Batalkan Pengiriman ke Rumah Ini</h3>
            <form method="POST" action="{{ route('kurir.pengiriman.batal', $pesanan->id) }}">
                @csrf
                <label for="alasan">Alasan Pembatalan</label>
                <select name="alasan" id="alasan" required>
                    <option value="">Pilih alasan...</option>
                    <option value="pelanggan_tidak_ada">Pelanggan tidak ada di tempat</option>
                    <option value="alamat_tidak_ditemukan">Alamat tidak ditemukan</option>
                    <option value="pelanggan_batal">Pelanggan membatalkan pesanan</option>
                    <option value="kendala_kendaraan">Kendala kendaraan/teknis kurir</option>
                    <option value="lainnya">Lainnya</option>
                </select>

                <label for="catatan_tambahan">Catatan Tambahan (opsional)</label>
                <textarea name="catatan_tambahan" id="catatan_tambahan" rows="3" placeholder="Jelaskan lebih detail jika perlu..."></textarea>

                <div class="modal-buttons">
                    <button type="button" class="btn-modal-cancel" onclick="document.getElementById('batalModal').classList.remove('open')">Batal</button>
                    <button type="submit" class="btn-modal-confirm">Kirim & Lanjut Rumah Berikutnya</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const estimasiSelesai = @json($estimasiSelesai ? $estimasiSelesai->toIso8601String() : null);

        function updateTimer() {
            const timerValue = document.getElementById('timerValue');
            const timerBox = document.getElementById('timerBox');
            if (!estimasiSelesai) {
                timerValue.textContent = 'Belum dimulai';
                return;
            }

            const target = new Date(estimasiSelesai).getTime();
            const now = new Date().getTime();
            const diff = target - now;

            if (diff <= 0) {
                timerValue.textContent = 'Seharusnya sudah tiba';
                timerBox.classList.add('overdue');
                return;
            }

            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            timerValue.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    </script>
@else
    @if ($antrian->isNotEmpty())
        <div class="wrapper">
            <div class="batch-banner">
                <div>
                    <div class="batch-title">{{ $antrian->count() }} Pesanan Siap Diantar</div>
                    <div class="batch-sub">
                        @if ($jadwalSudahDiatur)
                            Pengantaran otomatis dimulai jam {{ \Carbon\Carbon::parse($kurir->jam_antar_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($kurir->jam_antar_selesai)->format('H:i') }}
                        @else
                            Jam antar kamu belum diatur pemilik apotek — kamu berangkat sendiri kapan siap
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Daftar Pesanan yang Sudah Kamu Ambil</h3>
                <ul class="stop-list">
                    @foreach ($antrian as $item)
                        <li>
                            <span class="stop-info">
                                <span class="nama">P{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }} — {{ $item->user->name ?? '-' }}</span><br>
                                <span class="alamat">{{ \Illuminate\Support\Str::limit($item->alamat, 45) }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>

                @if ($jadwalSudahDiatur)
                    <div class="alert-success" style="margin-top:16px;margin-bottom:0;">
                        ⏰ Pengantaran dimulai pukul {{ \Carbon\Carbon::parse($kurir->jam_antar_mulai)->format('H:i') }} — {{ \Carbon\Carbon::parse($kurir->jam_antar_selesai)->format('H:i') }}. Sistem akan otomatis menggabungkan semua pesanan ini jadi satu rute begitu jamnya tiba, kamu tidak perlu menekan apa-apa.
                    </div>
                @else
                    <form method="POST" action="{{ route('kurir.pengiriman.mulai') }}" style="margin-top:16px;" onsubmit="return confirm('Mulai antar {{ $antrian->count() }} pesanan sekarang? Kamu masih bisa ambil pesanan lain sebelum menekan tombol ini.');">
                        @csrf
                        <button type="submit" class="btn-selesai">▶ Mulai Pengantaran ({{ $antrian->count() }} Pesanan)</button>
                    </form>
                    <p style="font-size:12.5px;color:var(--text-muted);margin-top:10px;">Masih bisa ambil pesanan lain dulu dari menu <strong>Pesanan</strong> sebelum menekan tombol Mulai — semuanya akan digabung jadi satu rute.</p>
                @endif
            </div>
        </div>
    @else
        <div class="empty-state">
            Tidak ada pengiriman yang sedang berlangsung.<br>
            @if ($jadwalSudahDiatur)
                Pesanan akan otomatis dibagikan ke kamu jam {{ \Carbon\Carbon::parse($kurir->jam_antar_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($kurir->jam_antar_selesai)->format('H:i') }}.
            @else
                Ambil pesanan dari menu <strong>Pesanan</strong>, lalu tekan tombol Mulai di sini kalau sudah siap berangkat.
            @endif
        </div>
    @endif
@endif
@endsection
