<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman form register.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Memproses pendaftaran akun baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'      => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password'   => ['required', 'confirmed', Rules\Password::min(8)->letters()->numbers()],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender'     => ['nullable', 'in:L,P'],
            'terms'      => ['required', 'accepted'],
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email ini sudah terdaftar. Silakan masuk.',
            'phone.required'      => 'Nomor HP wajib diisi.',
            'phone.unique'        => 'Nomor HP ini sudah terdaftar. Silakan masuk.',
            'password.required'   => 'Password wajib diisi.',
            'password.confirmed'  => 'Konfirmasi password tidak sama.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.letters'    => 'Password harus mengandung huruf.',
            'password.numbers'    => 'Password harus mengandung angka.',
            'birth_date.date'     => 'Format tanggal lahir tidak valid.',
            'birth_date.before'   => 'Tanggal lahir tidak valid.',
            'gender.in'           => 'Jenis kelamin tidak valid.',
            'terms.required'      => 'Kamu harus menyetujui Syarat & Ketentuan dan Kebijakan Privasi.',
            'terms.accepted'      => 'Kamu harus menyetujui Syarat & Ketentuan dan Kebijakan Privasi.',
        ]);

        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'],
            'password'   => Hash::make($validated['password']),
            'birth_date' => $validated['birth_date'] ?? null,
            'gender'     => $validated['gender'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil dibuat. Selamat datang di Apotek Rizki!');
    }
}