<!DOCTYPE html>
<html lang="es">

<head>

    <!-- Main styles for this application -->
    <link href="/css/layout.css" rel="stylesheet">
    <style type="text/css" media="all">
        #header{
            margin-left: 18%;
        }
        #header h1{
            margin-left: 10%;
        }
        #head_ith{
            margin-left: 37%;
            margin-top: -10%;

            font-family: "segoe UI light";
            font-size: 24px;
            font-weight: 700;

            position: absolute;
        }
        .container-fluid{
            margin-top: 10%;
        }
        #cuadro{
            width: 10px!important;
            height: 1px;
            border: solid 1px black;
            color: white;
        }
        #texto{
            font-size: 8px;
            color: white;
        }
        #white-space{
            margin-right: 5%;
        }
    </style>
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
        <div id="app">
                    <div class="app-body">
                <!-- Contenido Principal -->     
<main class="main">
        <!-- Breadcrumb -->
<ol class="breadcrumb">
    <!-- <li class="breadcrumb-item"><a href="/">Escritorio</a></li> -->
</ol>
<div id="header" class="row">
<img src="{{ asset('img/favicon.png') }}" alt="">
</div>
<div id="head_ith">
    Instituto Tecnológico de Hermosillo <br>
    Dpto. Actividades Extraescolares 
</div>

<div class="container-fluid">
    <!-- Ejemplo de tabla Listado -->
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-6">                    
                    <div class="input-group">
                            <?php $var = 0 ?>
                            <h3 id="white-space">
                            @foreach ($grupo as $item)
                                
                                @if($var == 0)
                                Grupo: {{ $grupo->name }} 
                                @endif
                                
                                <?php $var++ ?>

                                 @endforeach
                            </h3>     

                            <?php $var = 0 ?>
                            <h3>@foreach ($schedule as $item)
                                
                                @if($var == 0)
                                Horario: {{ $schedule->schedule }} 
                                @endif
                                
                                <?php $var++ ?>

                                 @endforeach
                            </h3>

                            <?php $var = 0 ?>
                            <h3>
                            @foreach ($schedule as $item)
                                
                                @if($var == 0)
                                Maestro: {{ $schedule->teacher_name }} 
                                @endif
                                
                                <?php $var++ ?>

                                 @endforeach
                            </h3>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped" id="table_grupos">
                <thead>
                    <tr>
                        <th>No. de control</th>
                        <th>Carrera</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody><!-- Aqui empiezan la lista de grupos ================================================================================= -->
                    @foreach($estudiantes as $item)
                    <tr>                        
                        <td>{{$item->no_control}}</td>
                        <td>{{$item->career}}</td>
                        <td>{{$item->paternal_surname}} {{ $item->maternal_surname }} {{ $item->nombre_estudiante }}</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        <td id="cuadro">w</td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Fin ejemplo de tabla Listado -->
</div>
</template>

<!-- /Fin del contenido principal -->
</div>
</div>

<!-- GenesisUI main scripts -->
<script src="/js/app.js"></script>
<script src="/js/layout.js"></script>

</body>



</html>