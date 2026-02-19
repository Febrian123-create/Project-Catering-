<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }
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

            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
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
            'kontak' => 'required|string|max:20|unique:user,kontak',
            'alamat_default' => 'nullable|string|max:150',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'kontak.unique' => 'Nomor WhatsApp ini sudah terdaftar.',
        ]);

        $otp = rand(1000, 9999);

        $user = new User();

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
        $this->whatsAppService->sendOTP($user->kontak, $otp);

        return redirect()->route('otp.view')
            ->with('success', 'Akun berhasil dibuat. Silahkan verifikasi kode OTP Anda.');
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

        // Cari user berdasarkan nomor kontak yang ada di session DAN yang belum diverifikasi
        $user = User::where('kontak', session('pending_user_kontak'))
            ->where('is_verified', 0)
            ->latest('user_id')
            ->first();

        if ($user && $user->otp_code == $request->otp_input) {
            $user->is_verified = 1; // Ubah status jadi sudah verifikasi
            $user->otp_code = null; // Hapus kodenya biar aman
            $user->save();

            Auth::login($user); // Baru sekarang boleh login otomatis
            $request->session()->regenerate(); // Regenerate session ID to prevent fixation

            return redirect()->route('menus.index')->with('success', 'Verifikasi berhasil!');
        }

        return back()->withErrors(['otp_error' => 'Kode OTP salah!']);
    }

    // ==========================================
    // FORGOT PASSWORD FLOW
    // ==========================================

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'kontak' => 'required|string',
        ]);

        // Cari user yang username DAN kontak-nya cocok
        $user = User::where('username', $request->username)
            ->where('kontak', $request->kontak)
            ->where('is_verified', 1)
            ->first();

        if (!$user) {
            Log::warning("Forgot Password: User not found or not verified for username: {$request->username}, kontak: {$request->kontak}");
            return back()
                ->withInput()
                ->with('error', 'Username atau nomor WhatsApp tidak cocok, atau akun belum diverifikasi.');
        }

        // Generate OTP dan simpan ke database
        $otp = rand(1000, 9999);
        $user->otp_code = $otp;
        $user->save();

        // Simpan data ke session untuk step selanjutnya
        session([
            'reset_user_id' => $user->user_id,
            'reset_kontak' => $user->kontak,
        ]);

        Log::info("Forgot Password: OTP generated for user_id: {$user->user_id}, sending to: {$user->kontak}");

        // Kirim OTP via WhatsApp
        $this->whatsAppService->sendOTP($user->kontak, $otp);

        return redirect()->route('password.otp')
            ->with('success', 'Kode OTP telah dikirim ke WhatsApp kamu.');
    }

    public function showResetOtpForm()
    {
        if (!session('reset_user_id')) {
            return redirect()->route('password.forgot')
                ->with('error', 'Silahkan verifikasi username dan nomor WhatsApp terlebih dahulu.');
        }

        return view('auth.forgot-password-otp');
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'otp_input' => 'required|numeric',
        ]);

        $user = User::find(session('reset_user_id'));

        if (!$user || $user->otp_code != $request->otp_input) {
            return back()->withErrors(['otp_error' => 'Kode OTP salah!']);
        }

        // OTP cocok, hapus kode OTP dan set flag reset_verified
        $user->otp_code = null;
        $user->save();

        session(['reset_verified' => true]);

        return redirect()->route('password.reset');
    }

    public function showResetPassword()
    {
        if (!session('reset_verified') || !session('reset_user_id')) {
            return redirect()->route('password.forgot')
                ->with('error', 'Silahkan verifikasi OTP terlebih dahulu.');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (!session('reset_verified') || !session('reset_user_id')) {
            return redirect()->route('password.forgot');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::find(session('reset_user_id'));

        if (!$user) {
            return redirect()->route('password.forgot')
                ->with('error', 'User tidak ditemukan.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Bersihkan semua session reset
        session()->forget(['reset_user_id', 'reset_kontak', 'reset_verified']);

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah! Silahkan login dengan password baru.');
    }
}
