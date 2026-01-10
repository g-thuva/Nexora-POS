
<header class="navbar-expand-md" style="background-color: #fff;">
    <div class="collapse navbar-collapse" id="navbar-menu" style="position: static;">
        <div class="navbar" style="position: static;">
            <div class="container-xxl" style="position: static;">
                <ul class="navbar-nav">
                    <li class="nav-item {{ request()->is('/') || request()->is('dashboard*') ? 'active' : null }}">
                        <a class="nav-link" href="{{ route('dashboard') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Dashboard') }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('pos') || request()->routeIs('orders.create') ? 'active' : null }}">
                        <a class="nav-link" href="{{ route('orders.create') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-nexora icon-nexora-device-pos" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 7l-2 0l8 -2l8 2l-2 0" /><path d="M7 9l0 10a1 1 0 0 0 1 1l8 0a1 1 0 0 0 1 -1l0 -10" /><path d="M13 17l0 .01" /><path d="M10 14l0 .01" /><path d="M10 11l0 .01" /><path d="M13 11l0 .01" /><path d="M16 11l0 .01" /><path d="M16 14l0 .01" /></svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('POS') }}
                            </span>
                        </a>
                    </li>

                    @if(Auth::user()->hasInventoryAccess())
                    <li class="nav-item dropdown {{ request()->is('products*') ? 'active' : null }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-products" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-nexora icon-nexora-packages" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" /><path d="M2 13.5v5.5l5 3" /><path d="M7 16.545l5 -3.03" /><path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" /><path d="M12 19l5 3" /><path d="M17 16.5l5 -3" /><path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5" /><path d="M7 5.03v5.455" /><path d="M12 8l5 -3" /></svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Products') }}
                            </span>
                        </a>
                        <div class="dropdown-menu" id="navbar-products">
                            <a href="{{ route('products.index') }}" class="dropdown-item {{ request()->routeIs('products.index') ? 'active' : '' }}">
                                View Products
                            </a>
                            <a href="{{ route('products.create') }}" class="dropdown-item {{ request()->routeIs('products.create') ? 'active' : '' }}">
                                New Product
                            </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown {{ request()->is('orders*') && !request()->routeIs('orders.create') ? 'active' : null }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-sales" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-nexora icon-nexora-package-export" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21l-8 -4.5v-9l8 -4.5l8 4.5v4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12v9" /><path d="M12 12l-8 -4.5" /><path d="M15 18h7" /><path d="M19 15l3 3l-3 3" /></svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Sales') }}
                            </span>
                        </a>
                        <div class="dropdown-menu" id="navbar-sales">
                            <a href="{{ route('orders.index') }}" class="dropdown-item {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                                Cash Sales
                            </a>
                            <a href="{{ route('credit-sales.index') }}" class="dropdown-item {{ request()->routeIs('credit-sales.index') ? 'active' : '' }}">
                                Credit Sales
                            </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown {{ request()->is('jobs*') ? 'active' : null }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-jobs" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" /></svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Jobs') }}
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('jobs.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                                {{ __('Create New Job') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('jobs.list') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12l.01 0" /><path d="M13 12l2 0" /><path d="M9 16l.01 0" /><path d="M13 16l2 0" /></svg>
                                {{ __('View All Jobs') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('job-types.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
                                {{ __('Job Types') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('job-letterhead.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/></svg>
                                {{ __('Job Letterhead') }}
                            </a>
                        </div>
                    </li>
                    @endif

                    @if(Auth::user()->canAccessReports())
                    {{-- navFinanceKpis is provided by AppServiceProvider view composer; it contains returnKpi and expenseKpi objects --}}
                    <li class="nav-item dropdown {{ request()->is('reports*') ? 'active' : null }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-reports" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/>
                                    <path d="M12 7v5l3 3"/>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Reports') }}
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <div class="dropdown-header">Sales Reports</div>
                                    <a href="{{ route('reports.sales.index') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <rect x="4" y="4" width="16" height="16" rx="2"/>
                                            <path d="M9 9h6v6h-6z"/>
                                        </svg>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('reports.sales.daily') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                                            <line x1="16" y1="3" x2="16" y2="7"/>
                                            <line x1="8" y1="3" x2="8" y2="7"/>
                                            <line x1="4" y1="11" x2="20" y2="11"/>
                                            <line x1="11" y1="15" x2="12" y2="15"/>
                                            <line x1="12" y1="15" x2="12" y2="18"/>
                                        </svg>
                                        Daily Sales
                                    </a>
                                    <a href="{{ route('reports.sales.weekly') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                                            <line x1="16" y1="3" x2="16" y2="7"/>
                                            <line x1="8" y1="3" x2="8" y2="7"/>
                                            <line x1="4" y1="11" x2="20" y2="11"/>
                                            <rect x="8" y="15" width="2" height="2"/>
                                        </svg>
                                        Weekly Sales
                                    </a>
                                    <a href="{{ route('reports.sales.monthly') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                                            <line x1="16" y1="3" x2="16" y2="7"/>
                                            <line x1="8" y1="3" x2="8" y2="7"/>
                                            <line x1="4" y1="11" x2="20" y2="11"/>
                                            <path d="M11 15h1v4h-1z"/>
                                        </svg>
                                        Monthly Sales
                                    </a>
                                    <a href="{{ route('reports.sales.yearly') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <rect x="4" y="5" width="16" height="16" rx="2"/>
                                            <line x1="16" y1="3" x2="16" y2="7"/>
                                            <line x1="8" y1="3" x2="8" y2="7"/>
                                            <line x1="4" y1="11" x2="20" y2="11"/>
                                            <path d="M8 15h2v4H8z"/>
                                            <path d="M14 15h2v4h-2z"/>
                                        </svg>
                                        Yearly Sales
                                    </a>
                                </div>
                                <div class="dropdown-menu-column">
                                    <div class="dropdown-header">Finance Reports</div>
                                    <a href="{{ route('reports.sales.finance.returns') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 11v-2a9 9 0 0 1 9 -9h0"/><path d="M21 13v2a9 9 0 0 1 -9 9h0"/><path d="M21 7l-6 6"/><path d="M15 7l6 6"/></svg>
                                        Returns
                                        <span class="badge bg-secondary ms-2">{{ $navFinanceKpis['returnKpi']->items_returned ?? $navFinanceKpis['returnKpi']->items_returned ?? 0 }}</span>
                                    </a>
                                    <a href="{{ route('reports.sales.finance.expenses') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7h18"/><path d="M7 7v-3"/><path d="M17 7v-3"/><path d="M5 7v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-14"/><path d="M9 12h6"/></svg>
                                        Expenses
                                        <span class="badge bg-secondary ms-2">{{ number_format(($navFinanceKpis['expenseKpi']->total_expenses ?? 0) / 100, 2) }}</span>
                                    </a>
                                    <a href="{{ route('reports.sales.finance.credit-sales') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z"/><path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/></svg>
                                        Credit Sales
                                    </a>
                                    <a href="{{ route('reports.sales.finance.customers') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z"/><path d="M5 21v-2a4 4 0 0 1 4 -4h6a4 4 0 0 1 4 4v2"/></svg>
                                        Customers
                                    </a>
                                    <a href="{{ route('reports.sales.finance.products') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M7 20v-2a4 4 0 0 1 4 -4h2a4 4 0 0 1 4 4v2"/></svg>
                                        Products
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endif

                    <li class="nav-item dropdown {{ request()->is('categories*', 'units*') || (Auth::user()->canManageUsers() && request()->is('users*')) ? 'active' : null }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="icon icon-nexora icon-nexora-settings"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     stroke-width="2"
                                     stroke="currentColor"
                                     fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                            </span>
                                <span class="nav-link-title">
                                {{ __('Settings') }}
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            @if(Auth::user()->hasInventoryAccess())
                            <a class="dropdown-item" href="{{ route('categories.index') }}">
                                {{ __('Categories') }}
                            </a>
                            @endif
                            @if(Auth::user()->isShopOwner() || (method_exists(Auth::user(), 'isManagerRole') && Auth::user()->isManagerRole()) || (method_exists(Auth::user(), 'isManager') && Auth::user()->isManager()))
                            <a class="dropdown-item" href="{{ route('letterhead.index') }}">
                                {{ __('Letterhead') }}
                            </a>
                            @endif
                            <a class="dropdown-item" href="{{ route('user.profile') }}">
                                {{ __('Profile') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                {{ __('Account Settings') }}
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</header>
