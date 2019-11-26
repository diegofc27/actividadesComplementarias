<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
        @if(Auth::check() && Auth::user()->id_role == "1")
            <li class="nav-title">
                Administración <br>
                Usuario :  {{ Auth::user()->email }}
            </li>
        @endif
        @if(Auth::check() && Auth::user()->id_role == "2")
            <li class="nav-title">
                Maestro: <br> Matricula: {{ Auth::user()->email }}
            </li>
        @endif
        @if(Auth::check() && Auth::user()->id_role == "3")
            <li class="nav-title">
                Alumno <br> Matricula: {{ Auth::user()->email }}
            </li>
            <li class="nav-item">
                <a @click="menu=19" class="nav-link" href="#"><i class="fa fa-users"></i>Inscripcion</a>
            </li>
        @endif

            @if(Auth::check() && Auth::user()->id_role == "1")
            <li class="nav-item nav-dropdown" >
                <a @click="menu=0" class="nav-link" href="#"><i class=" fa fa-soccer-ball-o"></i> Organización
                    de grupos por extraescolar</a>
            </li>

            <li class="nav-item" >
                <a @click="menu=3" class="nav-link" href="#"><i class="fa fa-folder-open-o"></i> Organización de
                    grupos por Q8</a>
            </li>
            <li class="nav-item">
                <a @click="menu=5" class="nav-link" href="#"><i class="fa fa-align-justify"></i> Gestión de
                    Maestros</a>
                <!-- <ul class="nav-dropdown-items">
                    <li @click="menu=6" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Agregar Maestro</a>
                    </li>
                </ul> -->
            </li>
            <li class="nav-item">
                <a @click="menu=6" class="nav-link" href="#"><i class="icon-people"></i> Gestión de
                    Alumnos</a>
            </li>
            
            <li class="nav-item">
                <a @click="menu=12" class="nav-link" href="#"><i class="fa fa-users"></i>Usuarios</a>
            </li>







            {{-- <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-file-pdf-o"></i> Reportes <span
                        class="badge badge-danger mr-3">PDF</span></a>
            </li> --}}
            {{-- <li @click="menu=18" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-info"></i> Acerca de<span
                        class="badge badge-info mr-3">IT</span></a>
            </li> --}}
            
            @endif

            @if(Auth::check() && Auth::user()->id_role == "2")

            <li class="nav-item" >
                <a @click="menu=22" class="nav-link" href="#"><i class=" fa fa-soccer-ball-o"></i> Gestión de Grupos
                de Maestro</a>
            </li>

                <div id="invisible" value="" style="display: none;">{{ Auth::user()->email }}</div>

            @endif
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
