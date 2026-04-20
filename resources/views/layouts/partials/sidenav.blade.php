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
