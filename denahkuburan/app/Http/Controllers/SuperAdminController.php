<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index()
    {
        return view('dashboards.super-admin.index', [
            'total_users' => User::count(),
            'total_graves' => \App\Models\Grave::count(),
        ]);
    }

    public function users()
    {
        return view('dashboards.super-admin.users', [
            'users' => User::latest()->get(),
        ]);
    }

    public function settings()
    {
        return view('dashboards.super-admin.settings', [
            'settings' => Setting::all(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return back()->with('success', 'Pengaturan global berhasil diperbarui.');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:super_admin,admin,karyawan,customer',
            'password' => 'required|min:4',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,admin,karyawan,customer',
            'password' => 'nullable|min:4',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->update($data);

        return back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
