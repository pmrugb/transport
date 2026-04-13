@if ($errors->any())
    <div class="toast-container qas-toast-container position-fixed top-0 end-0 p-3">
        <div class="toast qas-toast qas-toast-error border-0 fade hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="6000" data-app-toast>
            <div class="toast-header">
                <span class="qas-toast-dot qas-toast-dot-error">
                    <i class="fa-solid fa-circle-exclamation app-icon"></i>
                </span>
                <strong class="me-auto">Validation Error</strong>
                <small>Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ $errors->first() }}</div>
        </div>
    </div>
@endif
