<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil user yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        return view('customer.profile', compact('user'));
    }

    /**
     * Menampilkan form sunting profil.
     */
    public function edit()
    {
        $user = Auth::user();

        return view('customer.profile-edit', compact('user'));
    }

    /**
     * Menyimpan perubahan data profil.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'birth_date' => ['nullable', 'date'],
            'gender'     => ['nullable', 'in:L,P'],
            'phone'      => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($user->id)],
            'alamat'     => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($validated);

        return redirect()
            ->route('profile.index')
            ->with('status', 'Profil berhasil diperbarui.');
    }

    /**
     * Proses keluar dari akun (logout), lalu arahkan ke halaman login.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}