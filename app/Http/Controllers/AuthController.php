<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan form login ada di resources/views/auth/login.blade.php
    }

    // Memproses login
    public function login(Request $request)
    {
        // Validasi email dan password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah kredensial login valid
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke halaman dashboard atau halaman yang diminta sebelumnya
            return redirect()->intended(route('dashboard'));
        }

        // Jika login gagal, beri pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Logout pengguna
    public function logout(Request $request)
    {
        Auth::logout();

        // Menghapus session dan mengarahkan pengguna ke halaman login setelah logout
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
