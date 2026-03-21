<!DOCTYPE html>
<html lang="es" data-skin="flat" data-bs-theme="light" data-menu-color="image" data-topbar-color="gradient" data-layout-position="fixed" data-sidenav-size="default">

<head>
    @include('layouts.partials/title-meta')

    @include('layouts.partials/head-css')
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
