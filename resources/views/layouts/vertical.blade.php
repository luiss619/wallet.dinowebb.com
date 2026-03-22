<!DOCTYPE html>
<html lang="es" data-skin="flat" data-bs-theme="light" data-menu-color="image" data-topbar-color="gradient" data-layout-position="fixed" data-sidenav-size="default">

<head>
    @include('layouts.partials/title-meta')

    @include('layouts.partials/head-css')
    <style>
        .sidenav-menu {
            position: fixed !important;
            top: 0;
            left: 0;
            height: 100vh !important;
            overflow-y: auto;
            z-index: 1050;
        }
    </style>
</head>

<body>
    <div class="wrapper">

        @include('layouts.partials/menu')

        <div class="content-page">

            <div class="container-fluid">

                @yield('content')

            </div>

            @include('layouts.partials/footer')

        </div>

    </div>

@include('layouts.partials/footer-scripts')
</body>

</html>
