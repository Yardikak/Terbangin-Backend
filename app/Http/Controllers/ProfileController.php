<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        // Mengambil data pengguna yang sedang login
        $user = Auth::user();

        // Menampilkan halaman profil
        return view('profile.profile', compact('user'));
    }

    public function edit()
    {
        // Mengambil data pengguna yang sedang login
        $user = Auth::user();

        // Menampilkan halaman edit profil
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        // Mengambil data pengguna yang sedang login
        $user = Auth::user();

        // Memperbarui data pengguna
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Redirect setelah berhasil memperbarui
        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}
