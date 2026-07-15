<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['apoteker', 'kurir']);

        if ($request->filled('role') && in_array($request->role, ['apoteker', 'kurir'])) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $staff = $query->orderBy('role')->orderBy('name')->get();

        return view('admin.staff.index', [
            'staff'        => $staff,
            'filterRole'   => $request->role,
            'filterStatus' => $request->status,
        ]);
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'username'       => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'          => ['required', 'string', 'max:20', 'unique:users,phone'],
            'alamat'         => ['required', 'string'],
            'tanggal_lahir'  => ['required', 'date'],
            'role'           => ['required', Rule::in(['apoteker', 'kurir'])],
            'shift'          => ['required', Rule::in(['pagi', 'sore'])],
            'password'       => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'username.unique' => 'Nama pengguna ini sudah dipakai.',
            'email.unique'    => 'Email ini sudah terdaftar.',
            'phone.unique'    => 'Nomor telepon ini sudah terdaftar.',
        ]);

        User::create([
            'name'          => $validated['name'],
            'username'      => $validated['username'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'],
            'alamat'        => $validated['alamat'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'role'          => $validated['role'],
            'shift'         => $validated['shift'],
            'is_active'     => true,
            'password'      => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Akun staff berhasil dibuat.');
    }

    public function edit(User $staff)
    {
        $this->pastikanStaff($staff);

        return view('admin.staff.edit', ['staff' => $staff]);
    }

    public function update(Request $request, User $staff)
    {
        $this->pastikanStaff($staff);

        // Rule dasar (selalu berlaku)
        $rules = [
            'name'           => ['required', 'string', 'max:255'],
            'username'       => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($staff->id)],
            'email'          => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($staff->id)],
            'phone'          => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($staff->id)],
            'alamat'         => ['required', 'string'],
            'tanggal_lahir'  => ['required', 'date'],
            'role'           => ['required', Rule::in(['apoteker', 'kurir'])],
            'shift'          => ['required', Rule::in(['pagi', 'sore'])],
        ];

        // Password HANYA divalidasi kalau memang diisi.
        // Ini mencegah form gagal submit total gara-gara field password dikosongkan.
        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:6', 'confirmed'];
        }

        $validated = $request->validate($rules, [
            'username.unique' => 'Nama pengguna ini sudah dipakai.',
            'email.unique'    => 'Email ini sudah terdaftar.',
            'phone.unique'    => 'Nomor telepon ini sudah terdaftar.',
        ]);

        $dataUpdate = [
            'name'          => $validated['name'],
            'username'      => $validated['username'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'],
            'alamat'        => $validated['alamat'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'role'          => $validated['role'],
            'shift'         => $validated['shift'],
        ];

        if ($request->filled('password')) {
            $dataUpdate['password'] = Hash::make($validated['password']);
        }

        $staff->update($dataUpdate);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Data staff berhasil diperbarui.');
    }

    public function toggleActive(User $staff)
    {
        $this->pastikanStaff($staff);

        $staff->update(['is_active' => ! $staff->is_active]);

        $pesan = $staff->is_active ? 'diaktifkan kembali' : 'dinonaktifkan';

        return redirect()->route('admin.staff.index')
            ->with('success', "Akun {$staff->name} berhasil {$pesan}.");
    }

    private function pastikanStaff(User $staff): void
    {
        abort_unless(in_array($staff->role, ['apoteker', 'kurir']), 404);
    }
}