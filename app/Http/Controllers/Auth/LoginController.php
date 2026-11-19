<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && in_array($user->role, ['admin', 'super_admin'], true)) {
                return redirect()->intended(route('admin.dashboard'));
            }
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // 1. Brute-Force Rate Limiting (5 attempts per minute)
        $throttleKey = Str::transliterate(Str::lower($request->input('login')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return redirect()->back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => "تم تجاوز عدد محاولات الدخول المسموح بها لحماية الحساب. يرجى الانتظار {$seconds} ثانية قبل المحاولة مجدداً."]);
        }

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 2. Attempt authentication
        if (Auth::attempt([$loginField => $request->login, 'password' => $request->password], $request->filled('remember'))) {
            $user = Auth::user();

            // 3. Strict Admin Role Verification
            if (!$user || !in_array($user->role, ['admin', 'super_admin'], true)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                RateLimiter::hit($throttleKey, 60);

                return redirect()->back()
                    ->withInput($request->only('login'))
                    ->withErrors(['login' => 'هذا الحساب لا يملك صلاحيات الوصول إلى لوحة التحكم.']);
            }

            // Clear throttle on successful login
            RateLimiter::clear($throttleKey);

            // Prevent session fixation
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        // Increment failed attempt counter (locks for 60 seconds after 5 fails)
        RateLimiter::hit($throttleKey, 60);

        return redirect()->back()
            ->withInput($request->only('login'))
            ->withErrors(['login' => 'بيانات الدخول غير صحيحة، يرجى التحقق وإعادة المحاولة.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'تم تسجيل الخروج بنجاح.');
    }
}
