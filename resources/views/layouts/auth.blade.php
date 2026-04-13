<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico') }}">
    <title>{{ $title ?? 'Free Public Transport System' }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="auth-page">
    <div class="auth-bg-shape auth-bg-shape-left"></div>
    <div class="auth-bg-shape auth-bg-shape-right"></div>

    <main class="auth-stage d-flex align-items-center justify-content-center">
        @yield('content')
    </main>

    @include('partials.session-expired-modal')

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toasts = document.querySelectorAll('[data-app-toast]');
            const sessionExpiredModalElement = document.getElementById('sessionExpiredModal');
            let hasStoredSessionExpiredFlag = false;

            try {
                hasStoredSessionExpiredFlag = window.sessionStorage.getItem('transport-session-expired') === 'true';
            } catch (error) {
                hasStoredSessionExpiredFlag = false;
            }

            const shouldShowSessionExpiredModal = sessionExpiredModalElement
                && (sessionExpiredModalElement.dataset.sessionExpired === 'true' || hasStoredSessionExpiredFlag);

            if (toasts.length && window.bootstrap && window.bootstrap.Toast) {
                toasts.forEach(function (toastElement) {
                    window.bootstrap.Toast.getOrCreateInstance(toastElement).show();
                });
            }

            if (shouldShowSessionExpiredModal && window.bootstrap && window.bootstrap.Modal) {
                try {
                    window.sessionStorage.removeItem('transport-session-expired');
                } catch (error) {
                    // Ignore storage issues and continue showing the modal.
                }

                window.bootstrap.Modal.getOrCreateInstance(sessionExpiredModalElement).show();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
