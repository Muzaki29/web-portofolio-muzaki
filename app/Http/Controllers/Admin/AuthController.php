<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function show()
    {
        return Inertia::render('auth/login', ['googleEnabled' => self::googleEnabled()]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => 'Email atau password salah.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public static function googleEnabled(): bool
    {
        return filled(config('services.google.client_id')) && filled(config('services.google.client_secret'));
    }

    public function googleRedirect()
    {
        abort_unless(self::googleEnabled(), 404);

        return Socialite::driver('google')->redirect();
    }

    /** Google callback: log in only an existing admin account with the same verified email. */
    public function googleCallback(Request $request)
    {
        abort_unless(self::googleEnabled(), 404);

        try {
            $google = Socialite::driver('google')->user();
        } catch (\Throwable) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Login Google dibatalkan atau gagal. Coba lagi.']);
        }

        $verified = (bool) ($google->user['email_verified'] ?? $google->user['verified_email'] ?? false);
        $user = $verified ? User::where('email', strtolower((string) $google->getEmail()))->first() : null;

        if (! $user) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Akun Google ini tidak terdaftar sebagai admin.']);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
