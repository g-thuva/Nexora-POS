<header class="navbar-expand-md admin-navbar">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <!-- Admin Dashboard -->
                    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : null }}">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Dashboard') }}
                                <span class="admin-badge">ADMIN</span>
                            </span>
                        </a>
                    </li>

                    <!-- User Management -->
                    <li class="nav-item dropdown {{ request()->routeIs('admin.users.*') ? 'active' : null }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-users" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('User Management') }}
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="7" r="4" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                        {{ __('All Users') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.users.create') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="7" r="4" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                            <path d="M15 8h6m-3 -3v6" />
                                        </svg>
                                        {{ __('Create User') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.users.index', ['role' => 'admin']) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 6l0 6l6 0l0 -6z" />
                                            <path d="M21 14l0 7l-7 0l0 -7" />
                                            <path d="M14 3l0 7l-7 0l0 -7" />
                                            <path d="M10 17l0 4l-4 0l0 -4" />
                                        </svg>
                                        {{ __('Administrators') }}
                                    </a>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.users.index', ['role' => 'shop_owner']) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 21l18 0" />
                                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                                            <path d="M9 9l0 4" />
                                            <path d="M12 7l0 6" />
                                            <path d="M15 11l0 2" />
                                        </svg>
                                        {{ __('Shop Owners') }}
                                    </a>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.users.index', ['role' => 'manager']) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="7" r="4" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                        {{ __('Managers') }}
                                    </a>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.users.index', ['role' => 'employee']) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="7" r="4" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                        {{ __('Employees') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Shop Management -->
                    <li class="nav-item dropdown {{ request()->routeIs('admin.shops.*') ? 'active' : null }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-shops" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 21l18 0" />
                                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                                    <path d="M9 9l0 4" />
                                    <path d="M12 7l0 6" />
                                    <path d="M15 11l0 2" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Shop Management') }}
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="{{ route('admin.shops.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 21l18 0" />
                                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                                            <path d="M9 9l0 4" />
                                            <path d="M12 7l0 6" />
                                            <path d="M15 11l0 2" />
                                        </svg>
                                        {{ __('All Shops') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.shops.create') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 21l18 0" />
                                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                                            <path d="M12 11l0 -4" />
                                            <path d="M10 9l4 0" />
                                        </svg>
                                        {{ __('Create Shop') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.shops.index', ['status' => 'active']) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon dropdown-item-icon text-green" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M9 12l2 2l4 -4" />
                                        </svg>
                                        {{ __('Active Shops') }}
                                    </a>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.shops.index', ['status' => 'suspended']) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon dropdown-item-icon text-red" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M15 9l-6 6" />
                                            <path d="M9 9l6 6" />
                                        </svg>
                                        {{ __('Suspended Shops') }}
                                    </a>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.shops.index', ['status' => 'overdue']) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon dropdown-item-icon text-orange" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M12 8v4" />
                                            <path d="M12 16h.01" />
                                        </svg>
                                        {{ __('Overdue Shops') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- System Settings -->
                    <li class="nav-item dropdown {{ request()->routeIs('letterhead.*') ? 'active' : null }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-settings" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('System Settings') }}
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    @if (auth()->user() && (auth()->user()->isShopOwner() || auth()->user()->isManagerRole() || auth()->user()->isManager()))
                                        <a class="dropdown-item" href="{{ route('letterhead.index') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                <path
                                                    d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                <path d="M9 9l1 0" />
                                                <path d="M9 13l6 0" />
                                                <path d="M9 17l6 0" />
                                            </svg>
                                            {{ __('Letterhead') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Reports -->
                    <li class="nav-item dropdown {{ request()->routeIs('reports.*') ? 'active' : null }}">
                        <a id="navbar-reports-toggle" class="nav-link dropdown-toggle" href="#"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button"
                            aria-expanded="false" aria-haspopup="true">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                    <path d="M12 7v5l3 3" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Reports') }}
                            </span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbar-reports-toggle">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="7" r="4" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                        {{ __('User Reports') }}
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 21l18 0" />
                                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                                            <path d="M9 9l0 4" />
                                            <path d="M12 7l0 6" />
                                            <path d="M15 11l0 2" />
                                        </svg>
                                        {{ __('Shop Reports') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <!-- Right side - Admin Info -->
                <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item d-none d-md-flex me-3">
                        <div class="btn-list">
                            <span class="badge bg-green-lt">{{ Auth::user()->getRoleDisplayName() }}</span>
                            <span class="text-white-50">{{ Auth::user()->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
