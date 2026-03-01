<!DOCTYPE html>
<html lang="en"
    data-skin="material"
    data-bs-theme="light"
    data-topbar-color="gradient"
    data-layout-position="scrollable">

<head>
    @include('layouts.partials/title-meta')
    @include('layouts.partials/head-css')
</head>

<body>
    <div class="wrapper">

        @include('layouts.partials/topbar')

        <!-- Wallet Nav -->
        <div class="app-wallet-nav border-bottom">
            <div class="container-fluid">
                <ul class="nav nav-underline gap-1 py-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}"
                            href="{{ route('accounts.index') }}">
                            <i class="ti ti-building-bank me-1 fs-16"></i> Accounts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}"
                            href="{{ route('services.index') }}">
                            <i class="ti ti-category me-1 fs-16"></i> Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                            href="{{ route('categories.index') }}">
                            <i class="ti ti-tag me-1 fs-16"></i> Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('subcategories.*') ? 'active' : '' }}"
                            href="{{ route('subcategories.index') }}">
                            <i class="ti ti-tags me-1 fs-16"></i> Subcategories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('movements.*') ? 'active' : '' }}"
                            href="{{ route('movements.index') }}">
                            <i class="ti ti-arrows-exchange me-1 fs-16"></i> Movements
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="content-page" style="margin-left: 0 !important;">
            <div class="container-fluid">
                @yield('content')
            </div>
            @include('layouts.partials/footer')
        </div>

    </div>

    @include('layouts.partials/customizer')
    @include('layouts.partials/footer-scripts')
</body>

</html>
