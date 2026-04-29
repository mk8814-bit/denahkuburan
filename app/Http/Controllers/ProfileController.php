<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $request->name;

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('profiles', 'public');
            $user->photo = $path;
        }

        $user->save();

        $dashRoute = 'dashboard';
        if ($user->role === 'super_admin') $dashRoute = 'super-admin.dashboard';
        elseif ($user->role === 'admin') $dashRoute = 'admin.dashboard';
        elseif ($user->role === 'karyawan') $dashRoute = 'karyawan.dashboard';
        elseif ($user->role === 'customer') $dashRoute = 'customer.dashboard';

        return redirect()->route($dashRoute)->with('success', 'Profil berhasil diperbarui!');
    }
}
