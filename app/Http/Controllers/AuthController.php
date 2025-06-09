<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    // Menampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('auth.register'); // Pastikan form registrasi ada di resources/views/auth/register.blade.php
    }

    // Memproses registrasi pengguna baru
    public function register(Request $request)
    {
        // Validasi data registrasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Membuat pengguna baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
        ]);

        // Setelah registrasi berhasil, arahkan pengguna ke halaman login
        return redirect()->route('login')->with('status', 'Registration successful! Please login.');
    }
}
