<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Selalu balas sukses walau email tidak ditemukan, supaya tidak
        // membocorkan apakah suatu email terdaftar (praktik keamanan standar).
        if (! $user) {
            return response()->json([
                'message' => 'Jika email terdaftar, kode OTP telah dikirim.',
            ]);
        }

        $otp = (string) random_int(100000, 999999);

        DB::table('password_reset_otps')->where('email', $user->email)->delete();
        DB::table('password_reset_otps')->insert([
            'email'      => $user->email,
            'otp'        => Hash::make($otp),
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Mail::to($user->email)->send(new OtpMail($otp, $user->name));

        return response()->json([
            'message' => 'Kode OTP telah dikirim ke email kamu.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp'   => ['required', 'string', 'size:6'],
        ]);

        $record = DB::table('password_reset_otps')->where('email', $request->email)->first();

        if (! $record || now()->greaterThan($record->expires_at)) {
            return response()->json(['message' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.'], 422);
        }

        if (! Hash::check($request->otp, $record->otp)) {
            return response()->json(['message' => 'Kode OTP yang kamu masukkan salah.'], 422);
        }

        return response()->json(['message' => 'Kode OTP valid.']);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'otp'      => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $record = DB::table('password_reset_otps')->where('email', $request->email)->first();

        if (! $record || now()->greaterThan($record->expires_at) || ! Hash::check($request->otp, $record->otp)) {
            return response()->json(['message' => 'Kode OTP tidak valid atau sudah kedaluwarsa.'], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['message' => 'Akun tidak ditemukan.'], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_otps')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password berhasil direset. Silakan login dengan password baru.']);
    }
}