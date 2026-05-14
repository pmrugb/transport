<div class="d-flex flex-wrap gap-2 mt-3">
    @if (auth()->user()?->canViewSecurityLogs())
        <a class="btn {{ request()->routeIs('logs.security.*') ? 'btn-success' : 'btn-outline-secondary' }}" href="{{ route('logs.security.index') }}">
            <i class="fa-solid fa-shield-halved me-2"></i>Security Logs
        </a>
    @endif

    @if (auth()->user()?->canViewAuditLogs())
        <a class="btn {{ request()->routeIs('logs.audit.*') ? 'btn-success' : 'btn-outline-secondary' }}" href="{{ route('logs.audit.index') }}">
            <i class="fa-solid fa-clipboard-list me-2"></i>Audit Logs
        </a>
    @endif

    @if (auth()->user()?->canManageUsers())
        <a class="btn {{ request()->routeIs('logs.recovery.*') || request()->routeIs('logs.deleted-users.*') ? 'btn-success' : 'btn-outline-secondary' }}" href="{{ route('logs.recovery.index') }}">
            <i class="fa-solid fa-rotate-left me-2"></i>Recovery
        </a>
    @endif
</div>
