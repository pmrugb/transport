<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\SecurityLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.login', [
            'loginCaptchaEnabled' => Setting::loginCaptchaEnabled(),
            'recaptchaSiteKey' => config('services.recaptcha.site_key'),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->credentials();
        $login = $credentials['login'];

        if ($response = $this->validateCaptcha($request)) {
            return $response;
        }

        $user = User::query()
            ->where('email', $login)
            ->orWhere('name', $login)
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            SecurityLog::recordEvent('failed_login', $request, null, $login);

            return back()
                ->withErrors(['login' => 'The provided credentials do not match our records.'])
                ->onlyInput('login');
        }

        Auth::login($user, false);
        $request->session()->regenerate();
        SecurityLog::recordEvent('login_success', $request, $user, $user->email);

        return redirect()->intended(route('dashboard'));
    }

    private function validateCaptcha(LoginRequest $request): ?RedirectResponse
    {
        if (! Setting::loginCaptchaEnabled()) {
            return null;
        }

        $secretKey = config('services.recaptcha.secret_key');

        if (blank($secretKey)) {
            SecurityLog::recordEvent('captcha_failed', $request, null, (string) $request->input('login'), [
                'reason' => 'secret_key_missing',
            ]);

            return back()
                ->withErrors(['login' => 'Login captcha is enabled but the secret key is not configured.'])
                ->onlyInput('login');
        }

        $captchaToken = (string) $request->input('g-recaptcha-response');

        if (blank($captchaToken)) {
            SecurityLog::recordEvent('captcha_failed', $request, null, (string) $request->input('login'), [
                'reason' => 'captcha_missing',
            ]);

            return back()
                ->withErrors(['login' => 'Please complete the captcha challenge.'])
                ->onlyInput('login');
        }

        try {
            $verification = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $captchaToken,
                'remoteip' => $request->ip(),
            ]);

            if (! $verification->successful() || ! data_get($verification->json(), 'success')) {
                SecurityLog::recordEvent('captcha_failed', $request, null, (string) $request->input('login'), [
                    'reason' => 'verification_failed',
                ]);

                return back()
                    ->withErrors(['login' => 'Captcha verification failed. Please try again.'])
                    ->onlyInput('login');
            }
        } catch (\Throwable) {
            SecurityLog::recordEvent('captcha_failed', $request, null, (string) $request->input('login'), [
                'reason' => 'service_unavailable',
            ]);

            return back()
                ->withErrors(['login' => 'Captcha verification is currently unavailable. Please try again.'])
                ->onlyInput('login');
        }

        return null;
    }

    public function destroy(): RedirectResponse
    {
        if ($user = Auth::user()) {
            SecurityLog::recordEvent('logout', request(), $user, $user->email);
        }

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been signed out successfully.');
    }
}
