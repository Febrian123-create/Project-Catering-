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
            'kontak' => 'required|string|max:20',
            'alamat_default' => 'nullable|string|max:150',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $otp = rand(1000, 9999);

        $user = new User();
        $user->user_id = User::generateUserId();
        $user->nama = $validated['nama'];
        $user->username = $validated['username'];
        $user->kontak = $validated['kontak']; // Langsung ambil dari validated
        $user->alamat_default = $validated['alamat_default'] ?? null;
        $user->role = 'buyer';
        $user->otp_code = $otp;
        $user->is_verified = 0;
        $user->password = Hash::make($validated['password']);

        $user->save();

        // PINDAHKAN INI KE ATAS REDIRECT!
        session(['pending_user_kontak' => $user->kontak]);

        // Aktifkan ini kalau Fonnte sudah hijau (Connected)
         $this->sendWhatsApp($user->kontak, $otp);

        return redirect()->route('otp.view')
            ->with('success', 'Akun berhasil dibuat. Silahkan verifikasi kode OTP Anda.');
    }

    private function sendWhatsApp($target, $otp)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => "Kode OTP Bento kamu adalah: $otp. Jangan beritahu siapapun ya!",
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . env('FONNTE_TOKEN')
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_input' => 'required|numeric',
        ]);

        // Cari user berdasarkan nomor kontak yang ada di session
        $user = User::where('kontak', session('pending_user_kontak'))->first();

        if ($user && $user->otp_code == $request->otp_input) {
            $user->is_verified = 1; // Ubah status jadi sudah verifikasi
            $user->otp_code = null; // Hapus kodenya biar aman
            $user->save();

            Auth::login($user); // Baru sekarang boleh login otomatis
            return redirect()->route('home')->with('success', 'Verifikasi berhasil!');
        }

        return back()->withErrors(['otp_error' => 'Kode OTP salah!']);
    }
}
