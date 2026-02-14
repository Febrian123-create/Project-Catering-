<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ensure user is loaded
        if (!$user) {
            return redirect()->route('login');
        }

        // Determine the role-based specific data if necessary
        // In the original code, different roles had different table structures.
        // Here we assume a single User model with roles.
        // If specific fields are missing in the User model, we'll use defaults in the view.

        return view('profile.index', compact('user'));
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_img')) {
            // Delete old photo if exists and is not default
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $path = $request->file('profile_img')->store('photos', 'public');
            
            $user->foto = $path;
            $user->save();

            return redirect()->back()->with('success', 'Profile photo updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload photo.');
    }


    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user,username,' . $user->user_id . ',user_id',
            'kontak' => 'required|string|max:20',
            'alamat_default' => 'required|string',
        ]);

        if ($user->kontak !== $request->kontak) {
            $request->validate([
                'kontak' => 'unique:user,kontak',
            ]);

            // Save pending data to session
            $otp = rand(1000, 9999);
            session([
                'profile_pending_data' => $request->only(['nama', 'username', 'kontak', 'alamat_default']),
                'profile_new_kontak' => $request->kontak,
                'profile_otp' => $otp
            ]);

            // Send OTP via WhatsApp
            $this->sendWhatsApp($request->kontak, $otp);

            return redirect()->route('profile.otp.form')->with('success', 'OTP sent to your new number. Please verify.');
        }

        $user->nama = $request->nama;
        $user->username = $request->username;
        // $user->kontak = $request->kontak; // Kontak is updated only if unchanged, or via OTP if changed
        $user->alamat_default = $request->alamat_default;
        
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function verifyOtpForm()
    {
        if (!session('profile_otp')) {
            return redirect()->route('profile.edit');
        }
        return view('profile.verify-otp');
    }

    public function verifyOtpProcess(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        if ($request->otp == session('profile_otp')) {
            $user = Auth::user();
            $data = session('profile_pending_data');

            $user->nama = $data['nama'];
            $user->username = $data['username'];
            $user->kontak = $data['kontak'];
            $user->alamat_default = $data['alamat_default'];
            $user->save();

            session()->forget(['profile_pending_data', 'profile_new_kontak', 'profile_otp']);

            return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
        }

        return back()->withErrors(['otp' => 'Invalid OTP code.']);
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

    public function editPassword()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password updated successfully.');
    }
}
