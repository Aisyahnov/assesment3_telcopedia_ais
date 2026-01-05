<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function doLogin(Request $req)
    {
        $req->validate([
            'nim'  => 'required',
        ]);

        $credentials = [
            'nim'      => $req->nim,
            'password' => $req->nim
        ];

        if (Auth::attempt($credentials)) {
            $req->session()->regenerate();
            return redirect()->route('landing'); // setelah login
        }

        return back()->with('error', 'NIM tidak ditemukan atau salah.');
    }

    // Logout
    public function logout()
    {
        auth()->logout();        // logout laravel auth
        session()->invalidate(); // hapus semua session
        session()->regenerateToken(); 

        return redirect()->route('login.show')
            ->with('success', 'Berhasil logout.');
    }

    // Tampilkan form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function doRegister(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'nim'  => 'required|unique:users,nim',
        ]);

        User::create([
            'name'     => $req->name,
            'nim'      => $req->nim,
            'password' => bcrypt($req->nim),
        ]);

        return redirect()->route('login.show')
            ->with('success', 'Akun berhasil dibuat. Silakan login.');
    }
}
