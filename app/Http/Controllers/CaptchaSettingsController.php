<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaptchaSettingsController extends Controller
{
    public function edit(): View
    {
        $this->ensureSuperadmin();

        return view('settings.captcha', [
            'loginCaptchaEnabled' => Setting::loginCaptchaEnabled(),
            'siteKeyConfigured' => filled(config('services.recaptcha.site_key')),
            'secretKeyConfigured' => filled(config('services.recaptcha.secret_key')),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->ensureSuperadmin();

        Setting::set('login_recaptcha_enabled', $request->boolean('login_recaptcha_enabled'));

        return redirect()->route('settings.captcha.edit')
            ->with('success', 'Captcha settings updated successfully.');
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }
}
