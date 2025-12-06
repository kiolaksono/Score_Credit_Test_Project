<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan Form Login
    public function showLoginForm()
    {
        // Jika user sudah login, jangan tampilkan form login lagi
        if (Auth::check()) {
            return redirect()->route('applications.index');
        }

        return view('login');
    }

    // Memproses Login
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba Login (Mapping kolom custom)
        // Kita harus memetakan input 'email' ke kolom database 'user_email'
        // 'password' tetap dibiarkan 'password' agar Laravel tahu itu field yang harus di-hash check
        if (Auth::attempt(['user_email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Redirect ke halaman home (applications.index). Jika ada intended URL, pakai itu.
            return redirect()->intended(route('applications.index'));
        }

        // 3. Jika gagal, kembali dengan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }


    public function showRegisterForm()
    {
        // Jika sudah login, redirect ke halaman aplikasi
        if (Auth::check()) {
            return redirect()->route('applications.index');
        }

        return view('register');
    }

    public function register(Request $request)
    {
        // Validasi
        $request->validate([
            'username' => 'required|string|max:100',
            'email' => 'required|email|unique:users,user_email', 
            'password' => 'required|min:6|confirmed'
        ]);
        try {
            \App\Models\User::create([
                'username' => $request->username,
                'user_email' => $request->email,
                'user_password' => Hash::make($request->password), 
            ]);

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            // Jika ada error saat menyimpan user, kembali dengan pesan error dan alasan
            return back()->withInput()->with('error', 'Registrasi gagal: ' . $e->getMessage());
        }
    }
}