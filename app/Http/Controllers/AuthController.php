<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
            'role' => 'required|in:customer,vendor,admin,sales',
            'nama_vendor' => 'required_if:role,vendor|nullable|string|max:255',
        ]);

        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Jika role vendor, buat vendor record
        if ($request->role === 'vendor') {
            $vendor = Vendor::create([
                'nama_vendor' => $request->nama_vendor,
                'user_id' => $user->id,
            ]);
            
            $user->vendor_id = $vendor->idvendor;
            $user->save();
        }

        // Auto login
        Auth::login($user);

        // Redirect berdasarkan role
        if ($user->role === 'vendor') {
            return redirect()->route('vendor.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('dashboard');
        } elseif ($user->role === 'sales') {
            return redirect()->route('toko.kunjungan');
        } else {
            return redirect()->route('landing');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if ($user->role === 'vendor') {
                return redirect()->route('vendor.dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->route('dashboard');
            } elseif($user->role === 'sales') {
                return redirect()->route('toko.kunjungan');
            } else{
                return redirect()->route('landing');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'id_google' => $googleUser->getId(),
                'password' => Hash::make(Str::random(16)),
            ]
        );

        // 🔥 Generate OTP 6 karakter
        $otp = strtoupper(Str::random(6));
        $user->otp = $otp;
        $user->save();

        // 🔥 Kirim OTP ke email
        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . env('RESEND_API_KEY'),
        //     'Content-Type' => 'application/json',
        // ])->post('https://api.resend.com/emails', [
        //     'from' => 'onboarding@resend.dev',
        //     'to' => [$user->email],
        //     'subject' => 'Kode OTP Login',
        //     'html' => "<h1>OTP Anda: $otp</h1>",
        // ]);

        Mail::raw("Kode OTP login anda adalah: $otp", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode OTP Login');
        });

// optional debug dulu
// dd($response->status(), $response->body());

        // Simpan id user sementara
        session(['otp_user_id' => $user->id]);

        return redirect('/verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect('/login');
        }

        if ($request->otp === $user->otp) {

            Auth::login($user);

            // hapus OTP setelah dipakai
            $user->otp = null;
            $user->save();

            session()->forget('otp_user_id');

            return redirect('/dashboard');
        }

        return back()->with('error', 'OTP salah');
    }
}
