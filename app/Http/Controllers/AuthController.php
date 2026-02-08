<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->isSeller()) {
                return redirect()->intended(route('seller.dashboard'));
            }
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:80',
            'username' => 'required|string|max:20|unique:user',
            'kontak' => 'nullable|numeric',
            'alamat_default' => 'nullable|string|max:150',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User();
        $user->user_id = User::generateUserId();
        $user->nama = $validated['nama'];
        $user->username = $validated['username'];
        $user->kontak = $validated['kontak'] ?? null;
        $user->alamat_default = $validated['alamat_default'] ?? null;
        $user->role = 'buyer'; // Default choice
        
        $user->password = Hash::make($validated['password']);
        
        $user->save();

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
