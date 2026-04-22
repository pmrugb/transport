@if (session('success'))
    <div class="toast-container qas-toast-container position-fixed top-0 end-0 p-3">
        <div class="toast qas-toast qas-toast-success border-0 fade hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="6000" data-app-toast>
            <div class="toast-header">
                <span class="qas-toast-dot">
                    <i class="fa-solid fa-check app-icon"></i>
                </span>
                <strong class="me-auto">Success</strong>
                <small>Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ session('success') }}</div>
        </div>
    </div>
@endif

@if (session('warning'))
    <div class="toast-container qas-toast-container position-fixed top-0 end-0 p-3">
        <div class="toast qas-toast qas-toast-warning border-0 fade hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="7000" data-app-toast>
            <div class="toast-header">
                <span class="qas-toast-dot qas-toast-dot-warning">
                    <i class="fa-solid fa-triangle-exclamation app-icon"></i>
                </span>
                <strong class="me-auto">Warning</strong>
                <small>Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ session('warning') }}</div>
        </div>
    </div>
@endif
