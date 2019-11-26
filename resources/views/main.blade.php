@include('layout.head')
    <body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
        <div id="app">
            @include('layout.header')
            <div class="app-body">
                @include('layout.sidebar')
                <!-- Contenido Principal -->
                @yield('content')
                <!-- /Fin del contenido principal -->
            </div>
        </div>
        @include('layout.footer')
    </body>
</html>