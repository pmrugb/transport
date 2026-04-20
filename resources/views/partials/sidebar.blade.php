<aside class="app-sidebar d-none d-lg-block">
    <div class="app-sidebar-shell">
        @php($paymentsOnlySidebar = auth()->user()?->hasPaymentsOnlySidebar() ?? false)
        <a class="app-sidebar-brand" href="{{ route('dashboard') }}">
            <span class="app-brand-mark">
                <i class="fa-solid fa-bus app-icon"></i>
            </span>
            <span class="app-brand-copy">
                <span class="app-sidebar-title">Public Transport</span>
                <span class="app-sidebar-title">Management System</span>
            </span>
        </a>

        <ul class="nav flex-column app-sidebar-nav" id="desktopSidebarAccordion">
            @unless ($paymentsOnlySidebar)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <span class="nav-link-icon"><i class="fa-solid fa-gauge-high app-icon"></i></span>
                        <span class="nav-link-text">{{ $paymentsOnlySidebar ? 'NATCO Dashboard' : 'Dashboard' }}</span>
                    </a>
                </li>
                <li class="nav-item nav-item-group {{ request()->routeIs('trips.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('trips.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopTripsMenu" aria-expanded="{{ request()->routeIs('trips.*') ? 'true' : 'false' }}" aria-controls="desktopTripsMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-road-circle-check app-icon"></i></span>
                        <span class="nav-link-text">Trip Management</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('trips.*') ? 'show' : '' }}" id="desktopTripsMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('trips.index') ? 'active' : '' }}" href="{{ route('trips.index') }}"><i class="fa-solid fa-list app-icon"></i> <span class="nav-link-text">All Trips</span></a>
                            @if (auth()->user()?->canCreateTrips())
                                <a class="app-sidebar-sublink {{ request()->routeIs('trips.create') ? 'active' : '' }}" href="{{ route('trips.create') }}"><i class="fa-solid fa-plus app-icon"></i> <span class="nav-link-text">Add Trip</span></a>
                            @endif
                        </div>
                    </div>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <span class="nav-link-icon"><i class="fa-solid fa-building-user app-icon"></i></span>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
            @endunless
            <li class="nav-item nav-item-group {{ request()->routeIs('payments.*') ? 'open' : '' }}">
                <button class="nav-link nav-link-group-toggle {{ request()->routeIs('payments.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopPaymentsMenu" aria-expanded="{{ request()->routeIs('payments.*') ? 'true' : 'false' }}" aria-controls="desktopPaymentsMenu">
                    <span class="nav-link-icon"><i class="fa-solid fa-wallet app-icon"></i></span>
                    <span class="nav-link-text">Payments</span>
                    <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                </button>
                <div class="collapse {{ request()->routeIs('payments.*') ? 'show' : '' }}" id="desktopPaymentsMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('payments.due') ? 'active' : '' }}" href="{{ route('payments.due') }}"><i class="fa-solid fa-hourglass-half app-icon"></i> <span class="nav-link-text">Due Payments</span></a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('payments.paid') ? 'active' : '' }}" href="{{ route('payments.paid') }}"><i class="fa-solid fa-money-check-dollar app-icon"></i> <span class="nav-link-text">Paid</span></a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('payments.on-hold') ? 'active' : '' }}" href="{{ route('payments.on-hold') }}"><i class="fa-solid fa-pause app-icon"></i> <span class="nav-link-text">On Hold</span></a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('payments.rejected') ? 'active' : '' }}" href="{{ route('payments.rejected') }}"><i class="fa-solid fa-ban app-icon"></i> <span class="nav-link-text">Rejected</span></a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('payments.index') ? 'active' : '' }}" href="{{ route('payments.index') }}"><i class="fa-solid fa-list app-icon"></i> <span class="nav-link-text">All Payments</span></a>
                        </div>
                </div>
            </li>
            
            @if ($paymentsOnlySidebar)
                @if (auth()->user()?->canViewTripsModule())
                    <li class="nav-item nav-item-group {{ request()->routeIs('trips.*') ? 'open' : '' }}">
                        <button class="nav-link nav-link-group-toggle {{ request()->routeIs('trips.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopNatcoTripsMenu" aria-expanded="{{ request()->routeIs('trips.*') ? 'true' : 'false' }}" aria-controls="desktopNatcoTripsMenu">
                            <span class="nav-link-icon"><i class="fa-solid fa-road-circle-check app-icon"></i></span>
                            <span class="nav-link-text">Trip Management</span>
                            <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                        </button>
                        <div class="collapse {{ request()->routeIs('trips.*') ? 'show' : '' }}" id="desktopNatcoTripsMenu" data-bs-parent="#desktopSidebarAccordion">
                            <div class="app-sidebar-submenu">
                                <a class="app-sidebar-sublink {{ request()->routeIs('trips.index') ? 'active' : '' }}" href="{{ route('trips.index') }}"><i class="fa-solid fa-list app-icon"></i> <span class="nav-link-text">All Trips</span></a>
                                @if (auth()->user()?->canCreateTrips())
                                    <a class="app-sidebar-sublink {{ request()->routeIs('trips.create') ? 'active' : '' }}" href="{{ route('trips.create') }}"><i class="fa-solid fa-plus app-icon"></i> <span class="nav-link-text">Add Trip</span></a>
                                @endif
                            </div>
                        </div>
                    </li>
                @endif
                <li class="nav-item nav-item-group {{ request()->routeIs('challans.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('challans.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopChallansMenu" aria-expanded="{{ request()->routeIs('challans.*') ? 'true' : 'false' }}" aria-controls="desktopChallansMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-file-circle-check app-icon"></i></span>
                        <span class="nav-link-text">Challans</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('challans.*') ? 'show' : '' }}" id="desktopChallansMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('challans.index') || request()->routeIs('challans.show') ? 'active' : '' }}" href="{{ route('challans.index') }}"><i class="fa-solid fa-list app-icon"></i> <span class="nav-link-text">All Challans</span></a>
                        </div>
                    </div>
                </li>
                <li class="nav-item nav-item-group {{ request()->routeIs('settings.profile.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('settings.profile.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopNatcoSettingsMenu" aria-expanded="{{ request()->routeIs('settings.profile.*') ? 'true' : 'false' }}" aria-controls="desktopNatcoSettingsMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-gear app-icon"></i></span>
                        <span class="nav-link-text">Settings</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('settings.profile.*') ? 'show' : '' }}" id="desktopNatcoSettingsMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('settings.profile.edit') ? 'active' : '' }}" href="{{ route('settings.profile.edit') }}"><i class="fa-solid fa-user app-icon"></i> Edit Profile</a>
                        </div>
                    </div>
                </li>
            @endif
            @unless ($paymentsOnlySidebar)
                <li class="nav-item nav-item-group {{ request()->routeIs('transporters.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('transporters.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopTransporterMenu" aria-expanded="{{ request()->routeIs('transporters.*') ? 'true' : 'false' }}" aria-controls="desktopTransporterMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-users app-icon"></i></span>
                        <span class="nav-link-text">Transporters</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('transporters.*') ? 'show' : '' }}" id="desktopTransporterMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('transporters.index') ? 'active' : '' }}" href="{{ route('transporters.index') }}"><i class="fa-solid fa-bus app-icon"></i> <span class="nav-link-text">All Transporters</span></a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('transporters.create') ? 'active' : '' }}" href="{{ route('transporters.create') }}"><i class="fa-solid fa-plus app-icon"></i> <span class="nav-link-text">Add Transporters</span></a>
                        </div>
                    </div>
                </li>
                <li class="nav-item nav-item-group {{ request()->routeIs('routes.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('routes.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopRoutesMenu" aria-expanded="{{ request()->routeIs('routes.*') ? 'true' : 'false' }}" aria-controls="desktopRoutesMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-route app-icon"></i></span>
                        <span class="nav-link-text">Routes</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('routes.*') ? 'show' : '' }}" id="desktopRoutesMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('routes.index') ? 'active' : '' }}" href="{{ route('routes.index') }}"><i class="fa-solid fa-road app-icon"></i> <span class="nav-link-text">All Routes</span> </a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('routes.create') ? 'active' : '' }}" href="{{ route('routes.create') }}"><i class="fa-solid fa-plus app-icon"></i> <span class="nav-link-text"> Add New Route</span></a>
                        </div>
                    </div>
                </li>
                <li class="nav-item nav-item-group {{ request()->routeIs('challans.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('challans.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopChallansMenu" aria-expanded="{{ request()->routeIs('challans.*') ? 'true' : 'false' }}" aria-controls="desktopChallansMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-file-circle-check app-icon"></i></span>
                        <span class="nav-link-text">Challans</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('challans.*') ? 'show' : '' }}" id="desktopChallansMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('challans.index') || request()->routeIs('challans.show') || request()->routeIs('challans.edit') ? 'active' : '' }}" href="{{ route('challans.index') }}"><i class="fa-solid fa-list app-icon"></i> <span class="nav-link-text">All Challans</span></a>
                            @if (auth()->user()?->isSuperadmin())
                                <a class="app-sidebar-sublink {{ request()->routeIs('challans.create') ? 'active' : '' }}" href="{{ route('challans.create') }}"><i class="fa-solid fa-plus app-icon"></i> <span class="nav-link-text">Add Challans</span></a>
                            @endif
                        </div>
                    </div>
                </li>
                <li class="nav-item nav-item-group {{ request()->routeIs('vehicles.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('vehicles.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopVehiclesMenu" aria-expanded="{{ request()->routeIs('vehicles.*') ? 'true' : 'false' }}" aria-controls="desktopVehiclesMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-van-shuttle app-icon"></i></span>
                        <span class="nav-link-text">Vehicle</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('vehicles.*') ? 'show' : '' }}" id="desktopVehiclesMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('vehicles.index') ? 'active' : '' }}" href="{{ route('vehicles.index') }}"><i class="fa-solid fa-van-shuttle app-icon"></i> <span class="nav-link-text">All Vehicles</span></a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('vehicles.create') ? 'active' : '' }}" href="{{ route('vehicles.create') }}"><i class="fa-solid fa-plus app-icon"></i> <span class="nav-link-text">Add Vehicle</span></a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('vehicles.types.*') ? 'active' : '' }}" href="{{ route('vehicles.types.index') }}"><i class="fa-solid fa-bus-simple app-icon"></i> <span class="nav-link-text">Vehicle Types</span></a>
                        </div>
                    </div>
                </li>
                <li class="nav-item nav-item-group {{ request()->routeIs('fares.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('fares.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopFaresMenu" aria-expanded="{{ request()->routeIs('fares.*') ? 'true' : 'false' }}" aria-controls="desktopFaresMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-money-bill-wave app-icon"></i></span>
                        <span class="nav-link-text">Fares</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('fares.*') ? 'show' : '' }}" id="desktopFaresMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('fares.index') ? 'active' : '' }}" href="{{ route('fares.index') }}"><i class="fa-solid fa-money-bill-wave app-icon"></i> <span class="nav-link-text">All Fares</span></a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('fares.create') ? 'active' : '' }}" href="{{ route('fares.create') }}"><i class="fa-solid fa-plus app-icon"></i> <span class="nav-link-text">Add Fares</span></a>
                        </div>
                    </div>
                </li>
                <li class="nav-item nav-item-group {{ request()->routeIs('grants.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('grants.*') || request()->routeIs('grant-releases.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopGrantsMenu" aria-expanded="{{ request()->routeIs('grants.*') || request()->routeIs('grant-releases.*') ? 'true' : 'false' }}" aria-controls="desktopGrantsMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-sack-dollar app-icon"></i></span>
                        <span class="nav-link-text">Grants</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('grants.*') || request()->routeIs('grant-releases.*') ? 'show' : '' }}" id="desktopGrantsMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('grants.index') ? 'active' : '' }}" href="{{ route('grants.index') }}"><i class="fa-solid fa-sack-dollar app-icon"></i> <span class="nav-link-text">All Grants</span></a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('grants.create') ? 'active' : '' }}" href="{{ route('grants.create') }}"><i class="fa-solid fa-plus app-icon"></i> <span class="nav-link-text">Add Grant</span></a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('grant-releases.index') ? 'active' : '' }}" href="{{ route('grant-releases.index') }}"><i class="fa-solid fa-hand-holding-dollar app-icon"></i> <span class="nav-link-text">Grant Releases</span></a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('grant-releases.create') ? 'active' : '' }}" href="{{ route('grant-releases.create') }}"><i class="fa-solid fa-plus app-icon"></i> <span class="nav-link-text">Add Grant Release</span></a>
                        </div>
                    </div>
                </li>
                <li class="nav-item nav-item-group {{ request()->routeIs('logs.security.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('logs.security.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopLogsMenu" aria-expanded="{{ request()->routeIs('logs.security.*') ? 'true' : 'false' }}" aria-controls="desktopLogsMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-clipboard-list app-icon"></i></span>
                        <span class="nav-link-text">Logs</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('logs.security.*') ? 'show' : '' }}" id="desktopLogsMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('logs.security.*') ? 'active' : '' }}" href="{{ route('logs.security.index') }}"><i class="fa-solid fa-shield-halved app-icon"></i> <span class="nav-link-text">Security Logs</span></a>
                        </div>
                    </div>
                </li>
                <li class="nav-item nav-item-group {{ request()->routeIs('users.*') || request()->routeIs('settings.profile.*') || request()->routeIs('settings.captcha.*') || request()->routeIs('settings.divisions.*') || request()->routeIs('settings.districts.*') || request()->routeIs('settings.departments.*') || request()->routeIs('settings.roles.*') ? 'open' : '' }}">
                    <button class="nav-link nav-link-group-toggle {{ request()->routeIs('users.*') || request()->routeIs('settings.profile.*') || request()->routeIs('settings.captcha.*') || request()->routeIs('settings.divisions.*') || request()->routeIs('settings.districts.*') || request()->routeIs('settings.departments.*') || request()->routeIs('settings.roles.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#desktopSettingsMenu" aria-expanded="{{ request()->routeIs('users.*') || request()->routeIs('settings.profile.*') || request()->routeIs('settings.captcha.*') || request()->routeIs('settings.divisions.*') || request()->routeIs('settings.districts.*') || request()->routeIs('settings.departments.*') || request()->routeIs('settings.roles.*') ? 'true' : 'false' }}" aria-controls="desktopSettingsMenu">
                        <span class="nav-link-icon"><i class="fa-solid fa-gear app-icon"></i></span>
                        <span class="nav-link-text">Settings</span>
                        <i class="fa-solid fa-chevron-right app-icon nav-link-arrow"></i>
                    </button>
                    <div class="collapse {{ request()->routeIs('users.*') || request()->routeIs('settings.profile.*') || request()->routeIs('settings.captcha.*') || request()->routeIs('settings.divisions.*') || request()->routeIs('settings.districts.*') || request()->routeIs('settings.departments.*') || request()->routeIs('settings.roles.*') ? 'show' : '' }}" id="desktopSettingsMenu" data-bs-parent="#desktopSidebarAccordion">
                        <div class="app-sidebar-submenu">
                            <a class="app-sidebar-sublink {{ request()->routeIs('settings.profile.edit') ? 'active' : '' }}" href="{{ route('settings.profile.edit') }}"><i class="fa-solid fa-user app-icon"></i> Edit Profile</a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('settings.captcha.*') ? 'active' : '' }}" href="{{ route('settings.captcha.edit') }}"><i class="fa-solid fa-shield-halved app-icon"></i> Captcha Settings</a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}"><i class="fa-solid fa-user-plus app-icon"></i> Add New User</a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('users.index') || request()->routeIs('users.all') || request()->routeIs('users.edit') ? 'active' : '' }}" href="{{ route('users.all') }}"><i class="fa-solid fa-users app-icon"></i> All Users</a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('settings.roles.create') ? 'active' : '' }}" href="{{ route('settings.roles.create') }}"><i class="fa-solid fa-plus app-icon"></i> Add Roles</a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('settings.roles.index') || request()->routeIs('settings.roles.edit') ? 'active' : '' }}" href="{{ route('settings.roles.index') }}"><i class="fa-solid fa-user-shield app-icon"></i> All Roles</a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('settings.divisions.*') ? 'active' : '' }}" href="{{ route('settings.divisions.index') }}"><i class="fa-solid fa-sitemap app-icon"></i> Divisions</a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('settings.districts.*') ? 'active' : '' }}" href="{{ route('settings.districts.index') }}"><i class="fa-solid fa-map-location-dot app-icon"></i> Districts</a>
                            <a class="app-sidebar-sublink {{ request()->routeIs('settings.departments.*') ? 'active' : '' }}" href="{{ route('settings.departments.index') }}"><i class="fa-solid fa-building-user app-icon"></i> Departments</a>
                        </div>
                    </div>
                </li>
                @if (auth()->user()?->isSuperadmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <span class="nav-link-icon"><i class="fa-solid fa-chart-column app-icon"></i></span>
                            <span class="nav-link-text">Reports</span>
                        </a>
                    </li>
                @endif
            @endunless
            <li class="nav-item">
                <div class="nav-link ">
                    <form method="post" action="{{ route('logout') }}" class="flex-grow-1">
                        @csrf
                        <button class="app-sidebar-logout border-0 bg-transparent p-0 text-start w-100" type="submit">
                            <span class="nav-link-icon"><i class="fa-solid fa-right-from-bracket app-icon"></i></span>
                            <span class="nav-link-text">Logout</span>
                        </button>
                    </form>
                </div>
            </li>
        </ul>

        <div class="app-sidebar-legal">
            ©2026 All Rights Reserved.<br>Powered by <span>PMRU GB</span>
        </div>
    </div>
</aside>
