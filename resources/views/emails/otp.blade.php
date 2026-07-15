<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body{font-family:'Segoe UI',Arial,sans-serif;background:#EAF7F0;margin:0;padding:32px 16px;}
    .box{max-width:420px;margin:0 auto;background:#fff;border-radius:20px;padding:32px 28px;box-shadow:0 10px 30px rgba(29,43,38,0.1);}
    .brand{font-size:18px;font-weight:800;color:#1D2B26;text-align:center;margin-bottom:4px;}
    .tag{font-size:12px;color:#7C8B84;text-align:center;margin-bottom:24px;}
    .otp-box{background:#EAF7F0;border-radius:14px;padding:20px;text-align:center;margin:20px 0;}
    .otp-code{font-size:32px;font-weight:800;letter-spacing:8px;color:#0C7E57;}
    p{font-size:13.5px;color:#1D2B26;line-height:1.6;}
    .muted{color:#7C8B84;font-size:12px;text-align:center;margin-top:20px;}
</style>
</head>
<body>
    <div class="box">
        <div class="brand">Apotek Rizki</div>
        <div class="tag">Layanan obat terpercaya untuk keluarga</div>
        <p>Halo {{ $name }},</p>
        <p>Kami menerima permintaan untuk mereset password akun kamu. Gunakan kode OTP di bawah ini:</p>
        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
        </div>
        <p>Kode ini berlaku selama <strong>10 menit</strong>. Jangan bagikan kode ini ke siapa pun, termasuk pihak yang mengaku dari Apotek Rizki.</p>
        <p>Kalau kamu tidak merasa meminta ini, abaikan saja email ini — akun kamu tetap aman.</p>
        <div class="muted">&copy; {{ date('Y') }} Apotek Rizki</div>
    </div>
</body>
</html>