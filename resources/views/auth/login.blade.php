@extends('layouts.auth', ['title' => 'Login | Public Transport Management System'])

@section('content')
    <div class="container auth-container py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card auth-card">
                    <div class="card-body">
                        <h1 class="auth-title">Public Transport <br> Management System</h1>

                        @include('partials.alerts')

                        <form action="{{ route('login.store') }}" class="auth-form" method="post">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="login">Username or Email <span class="text-danger">*</span></label>
                                <input
                                    autocomplete="username"
                                    class="form-control @error('login') is-invalid @enderror"
                                    id="login"
                                    name="login"
                                    placeholder="Username"
                                    required
                                    type="text"
                                    value="{{ old('login') }}"
                                >
                                @error('login')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group auth-password-group">
                                    <input
                                        autocomplete="current-password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        placeholder="Password"
                                        required
                                        type="password"
                                    >
                                    <button aria-label="Show password" class="btn auth-password-toggle" id="togglePassword" type="button">
                                        <i class="fa-solid fa-eye app-icon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember username</label>
                            </div>

                            @if (($loginCaptchaEnabled ?? false) && filled($recaptchaSiteKey ?? null))
                                <div class="mb-3">
                                    <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                                </div>
                            @endif

                            <div class="d-grid">
                                <button class="btn auth-submit-btn" type="submit">Sign in</button>
                            </div>
                        </form>

                        <p class="auth-footer mb-0 text-center">
                            ©2026 All Rights Reserved.<br>Powered by <span>PMRU GB</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if (($loginCaptchaEnabled ?? false) && filled($recaptchaSiteKey ?? null))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const loginInput = document.getElementById('login');
            const rememberCheckbox = document.getElementById('remember');
            const icon = toggleButton ? toggleButton.querySelector('.app-icon') : null;
            const rememberedLoginKey = 'transport-remembered-login';

            if (loginInput && rememberCheckbox) {
                const rememberedLogin = window.localStorage.getItem(rememberedLoginKey);

                if (rememberedLogin && !loginInput.value) {
                    loginInput.value = rememberedLogin;
                    rememberCheckbox.checked = true;
                }

                const syncRememberedLogin = function () {
                    if (rememberCheckbox.checked && loginInput.value.trim() !== '') {
                        window.localStorage.setItem(rememberedLoginKey, loginInput.value.trim());
                        return;
                    }

                    window.localStorage.removeItem(rememberedLoginKey);
                };

                rememberCheckbox.addEventListener('change', syncRememberedLogin);

                loginInput.form?.addEventListener('submit', function () {
                    syncRememberedLogin();
                });
            }

            if (!toggleButton || !passwordInput) {
                return;
            }

            toggleButton.addEventListener('click', function () {
                const showPassword = passwordInput.type === 'password';
                passwordInput.type = showPassword ? 'text' : 'password';
                toggleButton.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');

                if (icon) {
                    icon.classList.toggle('fa-eye', !showPassword);
                    icon.classList.toggle('fa-eye-slash', showPassword);
                }
            });
        });
    </script>
@endpush
