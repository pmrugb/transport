@extends('layouts.app', ['title' => 'Captcha Settings | Free Public Transport System', 'pageBadge' => 'Settings'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Settings</p>
            <h1 class="page-title">Captcha Settings</h1>
            <p class="page-subtitle">Use this page only to enable or disable login reCAPTCHA.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card captcha-card">
                <div class="card-body p-4">
                    <form method="post" action="{{ route('settings.captcha.update') }}">
                        @csrf
                        @method('put')

                        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                            <div>
                                <h3 class="section-title mb-2">Login reCAPTCHA</h3>
                                <p class="section-copy mb-0">Enable or disable captcha on the login page.</p>
                            </div>

                            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                                <div class="form-check form-switch fs-5 mb-0">
                                    <input class="form-check-input" id="login_recaptcha_enabled" name="login_recaptcha_enabled" type="checkbox" role="switch" value="1" {{ $loginCaptchaEnabled ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2 form-enable" for="login_recaptcha_enabled">Enable / Disable</label>
                                </div>

                                <button class="btn btn-success px-4" type="submit">Save Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
    </section>
@endsection
