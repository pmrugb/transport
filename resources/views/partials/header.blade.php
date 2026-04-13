<header class="app-topbar">
    <div class="container-fluid app-topbar-inner">
        <button class="btn app-topbar-toggle" type="button" data-app-sidebar-toggle aria-label="Toggle navigation">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-text-indent-left text-muted" viewBox="0 0 16 16">
                <path d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm.646 2.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L4.293 8 2.646 6.354a.5.5 0 0 1 0-.708zM7 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"></path>
            </svg>
        </button>

        <ul class="navbar-nav navbar-right-wrap ms-lg-auto d-flex nav-top-wrap align-items-center ms-4 ms-lg-0">
            <li class="dropdown ms-2">
                <a class="rounded-circle topbar-user-trigger" href="#!" role="button" id="dropdownUser" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md avatar-indicators avatar-online">
                        <img alt="avatar" src="{{ asset('images/avatar.png') }}" class="rounded-circle" decoding="async" width="72" height="70">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end topbar-dropdown-menu" aria-labelledby="dropdownUser">
                    <div class="px-4 pb-0 pt-2">
                        <div class="lh-1">
                            <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                            <div class="text-muted fs-6">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="dropdown-divider mt-3 mb-2"></div>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('settings.profile.edit') }}">
                                <i class="pe-2 fa-solid fa-user-pen"></i>
                                Edit Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="nav-icon me-2 fa-solid fa-right-from-bracket"></i>Sign Out
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <form id="logout-form" method="post" action="{{ route('logout') }}" class="d-none">
            @csrf
        </form>
    </div>
</header>
