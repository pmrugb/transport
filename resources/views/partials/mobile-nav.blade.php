<div>
    <div class="offcanvas offcanvas-start app-mobile-menu" tabindex="-1" id="appMobileMenu" aria-labelledby="appMobileMenuLabel">
        @php($paymentsOnlySidebar = auth()->user()?->hasPaymentsOnlySidebar() ?? false)
        <div class="offcanvas-header">
            <a class="app-sidebar-brand mb-0" href="{{ route('dashboard') }}">
                <span class="app-brand-mark">
                    <i class="fa-solid fa-bus app-icon"></i>
                </span>
                <span class="app-brand-copy">
                    <span class="app-sidebar-title">Free Public</span>
                    <span class="app-sidebar-title">Transport System</span>
                    <span class="app-sidebar-subtitle">PMRU GB Operations</span>
                </span>
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <ul class="nav flex-column app-sidebar-nav" id="mobileSidebarAccordion">
                @unless ($paymentsOnlySidebar)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <span class="nav-link-icon"><i class="fa-solid fa-gauge-high app-icon"></i></span>
                            <span class="nav-link-text">{{ $paymentsOnlySidebar ? 'NATCO Dashboard' : 'Dashboard' }}</span>
                            <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                        </a>
                    </li>
                    <li class="nav-item nav-item-group {{ request()->routeIs('trips.*') ? 'open' : '' }}">
                        <button class="nav-link nav-link-group-toggle {{ request()->routeIs('trips.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobileTripsMenu" aria-expanded="{{ request()->routeIs('trips.*') ? 'true' : 'false' }}" aria-controls="mobileTripsMenu">
                            <span class="nav-link-icon"><i class="fa-solid fa-road-circle-check app-icon"></i></span>
                            <span class="nav-link-text">Trip Management</span>
                            <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                        </button>
                        <div class="collapse {{ request()->routeIs('trips.*') ? 'show' : '' }}" id="mobileTripsMenu" data-bs-parent="#mobileSidebarAccordion">
                            <div class="app-sidebar-submenu">
                                <a class="app-sidebar-sublink {{ request()->routeIs('trips.index') ? 'active' : '' }}" href="{{ route('trips.index') }}">All Trips</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('trips.create') ? 'active' : '' }}" href="{{ route('trips.create') }}">Add Trip</a>
                            </div>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <span class="nav-link-icon"><i class="fa-solid fa-building-user app-icon"></i></span>
                            <span class="nav-link-text">NATCO Department Dashboard</span>
                            <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                        </a>
                    </li>
                @endunless
                <li class="nav-item nav-item-group {{ request()->routeIs('payments.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('payments.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobilePaymentsMenu" aria-expanded="{{ request()->routeIs('payments.*') ? 'true' : 'false' }}" aria-controls="mobilePaymentsMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-wallet app-icon"></i></span>
                        <span class="nav-link-text">Payments</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('payments.*') ? 'show' : '' }}" id="mobilePaymentsMenu" data-bs-parent="#mobileSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('payments.due') ? 'active' : '' }}" href="{{ route('payments.due') }}">Due Payments</a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('payments.paid') ? 'active' : '' }}" href="{{ route('payments.paid') }}">Paid</a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('payments.rejected') ? 'active' : '' }}" href="{{ route('payments.rejected') }}">Rejected</a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('payments.index') ? 'active' : '' }}" href="{{ route('payments.index') }}">All Payments</a>
                        </div>
                    </div>
                </li>
                @if ($paymentsOnlySidebar)
                    <li class="nav-item nav-item-group {{ request()->routeIs('settings.profile.*') ? 'open' : '' }}">
                        <button class="nav-link nav-link-group-toggle {{ request()->routeIs('settings.profile.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNatcoSettingsMenu" aria-expanded="{{ request()->routeIs('settings.profile.*') ? 'true' : 'false' }}" aria-controls="mobileNatcoSettingsMenu">
                            <span class="nav-link-icon"><i class="fa-solid fa-gear app-icon"></i></span>
                            <span class="nav-link-text">Settings</span>
                            <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                        </button>
                        <div class="collapse {{ request()->routeIs('settings.profile.*') ? 'show' : '' }}" id="mobileNatcoSettingsMenu" data-bs-parent="#mobileSidebarAccordion">
                            <div class="app-sidebar-submenu">
                                <a class="app-sidebar-sublink {{ request()->routeIs('settings.profile.edit') ? 'active' : '' }}" href="{{ route('settings.profile.edit') }}">Edit Profile</a>
                            </div>
                        </div>
                    </li>
                @endif
                @unless ($paymentsOnlySidebar)
                    <li class="nav-item nav-item-group {{ request()->routeIs('transporters.*') ? 'open' : '' }}">
                        <button class="nav-link nav-link-group-toggle {{ request()->routeIs('transporters.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobileTransporterMenu" aria-expanded="{{ request()->routeIs('transporters.*') ? 'true' : 'false' }}" aria-controls="mobileTransporterMenu">
                            <span class="nav-link-icon"><i class="fa-solid fa-users app-icon"></i></span>
                            <span class="nav-link-text">Transporters</span>
                            <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                        </button>
                        <div class="collapse {{ request()->routeIs('transporters.*') ? 'show' : '' }}" id="mobileTransporterMenu" data-bs-parent="#mobileSidebarAccordion">
                            <div class="app-sidebar-submenu">
                                <a class="app-sidebar-sublink {{ request()->routeIs('transporters.index') ? 'active' : '' }}" href="{{ route('transporters.index') }}">All Transporters</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('transporters.create') ? 'active' : '' }}" href="{{ route('transporters.create') }}">Add new Transporters</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item nav-item-group {{ request()->routeIs('routes.*') ? 'open' : '' }}">
                        <button class="nav-link nav-link-group-toggle {{ request()->routeIs('routes.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobileRoutesMenu" aria-expanded="{{ request()->routeIs('routes.*') ? 'true' : 'false' }}" aria-controls="mobileRoutesMenu">
                            <span class="nav-link-icon"><i class="fa-solid fa-route app-icon"></i></span>
                            <span class="nav-link-text">Routes</span>
                            <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                        </button>
                        <div class="collapse {{ request()->routeIs('routes.*') ? 'show' : '' }}" id="mobileRoutesMenu" data-bs-parent="#mobileSidebarAccordion">
                            <div class="app-sidebar-submenu">
                                <a class="app-sidebar-sublink {{ request()->routeIs('routes.index') ? 'active' : '' }}" href="{{ route('routes.index') }}">All Routes</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('routes.create') ? 'active' : '' }}" href="{{ route('routes.create') }}">Add New Route</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item nav-item-group {{ request()->routeIs('challans.*') ? 'open' : '' }}">
                        <button class="nav-link nav-link-group-toggle {{ request()->routeIs('challans.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobileChallansMenu" aria-expanded="{{ request()->routeIs('challans.*') ? 'true' : 'false' }}" aria-controls="mobileChallansMenu">
                            <span class="nav-link-icon"><i class="fa-solid fa-file-circle-check app-icon"></i></span>
                            <span class="nav-link-text">Challans</span>
                            <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                        </button>
                        <div class="collapse {{ request()->routeIs('challans.*') ? 'show' : '' }}" id="mobileChallansMenu" data-bs-parent="#mobileSidebarAccordion">
                            <div class="app-sidebar-submenu">
                                <a class="app-sidebar-sublink {{ request()->routeIs('challans.index') || request()->routeIs('challans.show') || request()->routeIs('challans.edit') ? 'active' : '' }}" href="{{ route('challans.index') }}">All Challans</a>
                                @if (auth()->user()?->isSuperadmin())
                                    <a class="app-sidebar-sublink {{ request()->routeIs('challans.create') ? 'active' : '' }}" href="{{ route('challans.create') }}">Add Challans</a>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="nav-item nav-item-group {{ request()->routeIs('vehicles.*') ? 'open' : '' }}">
                        <button class="nav-link nav-link-group-toggle {{ request()->routeIs('vehicles.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobileVehiclesMenu" aria-expanded="{{ request()->routeIs('vehicles.*') ? 'true' : 'false' }}" aria-controls="mobileVehiclesMenu">
                            <span class="nav-link-icon"><i class="fa-solid fa-van-shuttle app-icon"></i></span>
                            <span class="nav-link-text">Vehicle</span>
                            <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                        </button>
                        <div class="collapse {{ request()->routeIs('vehicles.*') ? 'show' : '' }}" id="mobileVehiclesMenu" data-bs-parent="#mobileSidebarAccordion">
                            <div class="app-sidebar-submenu">
                                <a class="app-sidebar-sublink {{ request()->routeIs('vehicles.index') ? 'active' : '' }}" href="{{ route('vehicles.index') }}">All Vehicles</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('vehicles.create') ? 'active' : '' }}" href="{{ route('vehicles.create') }}">Add Vehicle</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('vehicles.types.*') ? 'active' : '' }}" href="{{ route('vehicles.types.index') }}">Vehicle Types</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item nav-item-group {{ request()->routeIs('logs.security.*') ? 'open' : '' }}">
                        <button class="nav-link nav-link-group-toggle {{ request()->routeIs('logs.security.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobileLogsMenu" aria-expanded="{{ request()->routeIs('logs.security.*') ? 'true' : 'false' }}" aria-controls="mobileLogsMenu">
                            <span class="nav-link-icon"><i class="fa-solid fa-clipboard-list app-icon"></i></span>
                            <span class="nav-link-text">Logs</span>
                            <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                        </button>
                        <div class="collapse {{ request()->routeIs('logs.security.*') ? 'show' : '' }}" id="mobileLogsMenu" data-bs-parent="#mobileSidebarAccordion">
                            <div class="app-sidebar-submenu">
                                <a class="app-sidebar-sublink {{ request()->routeIs('logs.security.*') ? 'active' : '' }}" href="{{ route('logs.security.index') }}">Security Logs</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item nav-item-group {{ request()->routeIs('users.*') || request()->routeIs('settings.profile.*') || request()->routeIs('settings.captcha.*') || request()->routeIs('settings.divisions.*') || request()->routeIs('settings.districts.*') || request()->routeIs('settings.departments.*') || request()->routeIs('settings.roles.*') ? 'open' : '' }}">
                        <button class="nav-link nav-link-group-toggle {{ request()->routeIs('users.*') || request()->routeIs('settings.profile.*') || request()->routeIs('settings.captcha.*') || request()->routeIs('settings.divisions.*') || request()->routeIs('settings.districts.*') || request()->routeIs('settings.departments.*') || request()->routeIs('settings.roles.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSettingsMenu" aria-expanded="{{ request()->routeIs('users.*') || request()->routeIs('settings.profile.*') || request()->routeIs('settings.captcha.*') || request()->routeIs('settings.divisions.*') || request()->routeIs('settings.districts.*') || request()->routeIs('settings.departments.*') || request()->routeIs('settings.roles.*') ? 'true' : 'false' }}" aria-controls="mobileSettingsMenu">
                            <span class="nav-link-icon"><i class="fa-solid fa-gear app-icon"></i></span>
                            <span class="nav-link-text">Settings</span>
                            <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                        </button>
                        <div class="collapse {{ request()->routeIs('users.*') || request()->routeIs('settings.profile.*') || request()->routeIs('settings.captcha.*') || request()->routeIs('settings.divisions.*') || request()->routeIs('settings.districts.*') || request()->routeIs('settings.departments.*') || request()->routeIs('settings.roles.*') ? 'show' : '' }}" id="mobileSettingsMenu" data-bs-parent="#mobileSidebarAccordion">
                            <div class="app-sidebar-submenu">
                                <a class="app-sidebar-sublink {{ request()->routeIs('settings.profile.edit') ? 'active' : '' }}" href="{{ route('settings.profile.edit') }}">Edit Profile</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('settings.captcha.*') ? 'active' : '' }}" href="{{ route('settings.captcha.edit') }}">Captcha Settings</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}">Add New User</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('users.index') || request()->routeIs('users.all') || request()->routeIs('users.edit') ? 'active' : '' }}" href="{{ route('users.all') }}">All Users</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('settings.roles.create') ? 'active' : '' }}" href="{{ route('settings.roles.create') }}">Add Roles</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('settings.roles.index') || request()->routeIs('settings.roles.edit') ? 'active' : '' }}" href="{{ route('settings.roles.index') }}">All Roles</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('settings.divisions.*') ? 'active' : '' }}" href="{{ route('settings.divisions.index') }}">Divisions</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('settings.districts.*') ? 'active' : '' }}" href="{{ route('settings.districts.index') }}">Districts</a>
                                <a class="app-sidebar-sublink {{ request()->routeIs('settings.departments.*') ? 'active' : '' }}" href="{{ route('settings.departments.index') }}">Departments</a>
                            </div>
                        </div>
                    </li>
                @endunless
            </ul>

            <div class="app-sidebar-utility">
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="app-sidebar-logout border-0 bg-transparent p-0 text-start w-100" type="submit">
                        <span class="nav-link-icon"><i class="fa-solid fa-right-from-bracket app-icon"></i></span>
                        <span class="nav-link-text">Logout</span>
                    </button>
                </form>
            </div>

            <div class="app-sidebar-legal">
                ©2026 All Rights Reserved.<br>Powered by <span>PMRU GB</span>
            </div>
        </div>
    </div>
</div>
