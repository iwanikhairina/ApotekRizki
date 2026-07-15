<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.staff-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            // role sekarang boleh kosong -> khusus dipakai untuk login owner
            'role'     => ['nullable', 'in:kurir,apoteker'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Nama pengguna wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $roleDipilih = $credentials['role'] ?? null;

        $user = \App\Models\User::where('username', $credentials['username'])->first();

        // Aturan pemilihan role:
        // - Kalau user login sebagai kurir/apoteker, role WAJIB dipilih dan harus cocok.
        // - Kalau role dikosongkan, hanya boleh login jika akunnya memang role 'owner'.
        $rolesValid = $roleDipilih
            ? ($user && $user->role === $roleDipilih)
            : ($user && $user->role === 'owner');

        if (! $rolesValid || ! Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            return back()->withErrors([
                'username' => 'Nama pengguna, password, atau role tidak sesuai.',
            ])->onlyInput('username', 'role');
        }

        // Cek akun nonaktif (untuk staff yang dinonaktifkan admin)
        if (isset($user->is_active) && ! $user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'username' => 'Akun kamu sedang dinonaktifkan. Hubungi pemilik apotek.',
            ])->onlyInput('username', 'role');
        }

        $request->session()->regenerate();

        return match ($user->role) {
            'kurir'    => redirect()->route('kurir.dashboard'),
            'apoteker' => redirect()->route('apoteker.dashboard'),
            'owner'    => redirect()->route('admin.dashboard'),
            default    => redirect('/'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login');
    }
}