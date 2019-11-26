<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="nav navbar-nav d-md-down-none">
    </ul>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="{{ route('logout') }}" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();" role="button"
                aria-haspopup="true" aria-expanded="false">
                <span class="d-md-down-none">Cerrar Sesion </span>                
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
                    {{ csrf_field() }}
            </form>

           <!--  <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--right">
                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                    <ul class="m-menu__subnav">

                        <li class="m-menu__item "  aria-haspopup="true">
                            <a  href="{{ route('logout') }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();" class="m-menu__link ">
                                <i class="m-menu__link-icon fa fa-sign-out"></i>
                                <span class="m-menu__link-text">
                                    Salir
                                </span>
                            </a>

                             <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>   

                    </ul>
                </div> -->
            
        </li>
    </ul>
</header>
