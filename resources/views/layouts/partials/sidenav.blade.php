<!-- Sidenav Menu Start -->
<div class="sidenav-menu">
    <!-- Brand Logo -->
    <a class="logo" href="{{ route('root') }}">
        <span class="logo logo-light">
            <span class="logo-lg"><img alt="logo" src="/images/logo.png" /></span>
            <span class="logo-sm"><img alt="small logo" src="/images/logo-sm.png" /></span>
        </span>
        <span class="logo logo-dark">
            <span class="logo-lg"><img alt="dark logo" src="/images/logo-black.png" /></span>
            <span class="logo-sm"><img alt="small logo" src="/images/logo-sm.png" /></span>
        </span>
    </a>
    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-on-hover">
        <i class="ti ti-menu-4 fs-22 align-middle"></i>
    </button>
    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-offcanvas">
        <i class="ti ti-x align-middle"></i>
    </button>
    <div class="scrollbar" data-simplebar="">
        <!-- User -->
        <div class="sidenav-user">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a class="link-reset" href="#">
                        <img alt="user-image" class="rounded-circle mb-2 avatar-md" src="/images/users/user-3.jpg" />
                        <span class="sidenav-user-name fw-bold">Geneva K.</span>
                        <span class="fs-12 fw-semibold" data-lang="user-role">Art Director</span>
                    </a>
                </div>
                <div>
                    <a aria-expanded="false" aria-haspopup="false"
                        class="dropdown-toggle drop-arrow-none link-reset sidenav-user-set-icon" data-bs-offset="0,12"
                        data-bs-toggle="dropdown" href="#!">
                        <i class="ti ti-settings fs-24 align-middle ms-1"></i>
                    </a>
                    <div class="dropdown-menu">
                        <!-- Header -->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome back!</h6>
                        </div>
                        <!-- My Profile -->
                        <a class="dropdown-item" href="#">
                            <i class="ti ti-user-circle me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Profile</span>
                        </a>
                        <!-- Notifications -->
                        <a class="dropdown-item" href="javascript:void(0);">
                            <i class="ti ti-bell-ringing me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Notifications</span>
                        </a>
                        <!-- Settings -->
                        <a class="dropdown-item" href="javascript:void(0);">
                            <i class="ti ti-settings-2 me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Account Settings</span>
                        </a>
                        <!-- Support -->
                        <a class="dropdown-item" href="javascript:void(0);">
                            <i class="ti ti-headset me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Support Center</span>
                        </a>
                        <!-- Divider -->
                        <div class="dropdown-divider"></div>
                        <!-- Lock -->
                        <a class="dropdown-item" href="#">
                            <i class="ti ti-lock me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Lock Screen</span>
                        </a>
                        <!-- Logout -->
                        <a class="dropdown-item fw-semibold" href="javascript:void(0);">
                            <i class="ti ti-logout-2 me-2 fs-17 align-middle"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <li class="side-nav-title mt-2">Wallet</li>
            <li class="side-nav-item">
                <a class="side-nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}"
                    href="{{ route('accounts.index') }}">
                    <span class="menu-icon"><i class="ti ti-building-bank"></i></span>
                    <span class="menu-text">Accounts</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a class="side-nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}"
                    href="{{ route('services.index') }}">
                    <span class="menu-icon"><i class="ti ti-category"></i></span>
                    <span class="menu-text">Services</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a class="side-nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                    href="{{ route('categories.index') }}">
                    <span class="menu-icon"><i class="ti ti-tag"></i></span>
                    <span class="menu-text">Categories</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a class="side-nav-link {{ request()->routeIs('subcategories.*') ? 'active' : '' }}"
                    href="{{ route('subcategories.index') }}">
                    <span class="menu-icon"><i class="ti ti-tags"></i></span>
                    <span class="menu-text">Subcategories</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a class="side-nav-link {{ request()->routeIs('movements.*') ? 'active' : '' }}"
                    href="{{ route('movements.index') }}">
                    <span class="menu-icon"><i class="ti ti-arrows-exchange"></i></span>
                    <span class="menu-text">Movements</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- Sidenav Menu End -->
