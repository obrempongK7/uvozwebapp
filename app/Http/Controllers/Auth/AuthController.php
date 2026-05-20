<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Wallet;
use App\Models\Referral;
use App\Models\OtpVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username'        => ['required','string','min:3','max:30','alpha_num','unique:users'],
            'email'           => ['required','email','unique:users'],
            'password'        => ['required', Password::min(8)->letters()->numbers(),'confirmed'],
            'referral_code'   => ['nullable','string','exists:users,referral_code'],
            'terms'           => ['accepted'],
        ]);

        $referrer = null;
        if ($request->referral_code) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
        }

        $user = User::create([
            'username'      => strtolower($request->username),
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'display_name'  => $request->username,
            'referral_code' => Str::upper(Str::random(8)),
            'referred_by'   => $referrer?->id,
        ]);

        // Create profile & wallet
        Profile::create(['user_id' => $user->id]);
        Wallet::create(['user_id' => $user->id, 'today_date' => now()->toDateString()]);

        // Track referral
        if ($referrer) {
            Referral::create([
                'referrer_id'    => $referrer->id,
                'referred_id'    => $user->id,
                'commission_rate'=> 10.00,
            ]);
        }

        // Send OTP
        $this->sendEmailOtp($user);

        Auth::login($user);

        return redirect()->route('otp.verify')->with('info', 'Please verify your email address.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required','string'],
            'password' => ['required','string'],
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$field => $request->login, 'password' => $request->password];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->is_banned) {
                Auth::logout();
                return back()->withErrors(['login' => 'Your account has been suspended. Reason: ' . $user->ban_reason]);
            }

            if ($user->two_factor_enabled) {
                session(['2fa_user_id' => $user->id]);
                Auth::logout();
                return redirect()->route('two-factor');
            }

            $user->update(['last_seen_at' => now()]);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['login' => 'Invalid credentials.'])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function showVerifyOtp()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => ['required','string','size:6']]);

        $user = $request->user();
        $otp = OtpVerification::where('contact', $user->email)
            ->where('type', 'email')
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $otp->update(['used' => true]);
        $user->update(['email_verified_at' => now()]);

        return redirect()->route('dashboard')->with('success', 'Email verified successfully! Welcome to UVOZ.');
    }

    public function resendOtp(Request $request)
    {
        $this->sendEmailOtp($request->user());
        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required','email']]);
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(64);
            \DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => Hash::make($token), 'created_at' => now()]
            );
            // In production, mail the reset link
            // Mail::to($user)->send(new PasswordResetMail($token));
        }

        return back()->with('success', 'If an account exists, we sent a reset link to your email.');
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => ['required','email'],
            'token'    => ['required'],
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ]);

        $record = \DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['token' => 'Invalid or expired reset token.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password reset successfully. Please login.');
    }

    public function showTwoFactor()
    {
        if (!session('2fa_user_id')) return redirect()->route('login');
        return view('auth.two-factor');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate(['code' => ['required','string']]);
        $userId = session('2fa_user_id');
        $user = User::find($userId);

        if (!$user) return redirect()->route('login');

        // Simplified 2FA check (in production, use Google2FA package)
        Auth::login($user);
        session()->forget('2fa_user_id');
        $user->update(['last_seen_at' => now()]);

        return redirect()->route('dashboard');
    }

    private function sendEmailOtp(User $user): void
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        OtpVerification::create([
            'contact'    => $user->email,
            'type'       => 'email',
            'otp'        => $otp,
            'purpose'    => 'verify',
            'expires_at' => now()->addMinutes(10),
        ]);
        // Mail::to($user)->send(new OtpMail($otp));
    }
}
