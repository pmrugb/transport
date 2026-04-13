@php
    $sessionExpired = session('session_expired', false);
    $sessionExpiredMessage = session('session_expired_message', 'Your session has expired. Please log in again to continue.');
@endphp

<div
    aria-hidden="true"
    aria-labelledby="sessionExpiredModalLabel"
    class="modal fade"
    data-session-expired="{{ $sessionExpired ? 'true' : 'false' }}"
    id="sessionExpiredModal"
    tabindex="-1"
>
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content session-expired-modal-content">
            <div class="modal-body session-expired-modal-body text-center">
                <div class="session-expired-modal-icon">
                    <i class="fa-solid fa-clock-rotate-left app-icon"></i>
                </div>
                <h5 class="session-expired-modal-title" id="sessionExpiredModalLabel">Session Expired</h5>
                <p class="session-expired-modal-text mb-0">{{ $sessionExpiredMessage }}</p>
            </div>
            <div class="modal-footer session-expired-modal-footer border-0 pt-0 justify-content-center">
                <a class="btn auth-submit-btn session-expired-modal-button" href="{{ route('login') }}">Login Again</a>
            </div>
        </div>
    </div>
</div>
