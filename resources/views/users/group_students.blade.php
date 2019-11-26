@extends('main')
@section('content')
<main class="main">
        <!-- Breadcrumb -->
<ol class="breadcrumb">
    <!-- <li class="breadcrumb-item"><a href="/">Escritorio</a></li> -->
</ol>

<div class="container-fluid">
    <!-- Ejemplo de tabla Listado -->
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-6">                    
                    <div class="input-group">
                        
                            <h3 class="">Grupo: {{ $grupo->name }}</h3>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        <a class="btn btn-info" style="float: right;" href="{{ action('CalificationController@export_pdf', $id_grupo->id) }}">Exportar PDF</a>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped" id="table_grupos">
                <thead>
                    <tr>
                        <th>Nombre estudiante</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Calificacion actual</th>
                        <th>Agregar Calificacion</th>
                    </tr>
                </thead>
                <tbody><!-- Aqui empiezan la lista de grupos ================================================================================= -->
                    @foreach($estudiantes as $item)
                    <tr>                        
                        <td>{{$item->nombre_estudiante}}</td>
                        <td>{{$item->paternal_surname}}</td>
                        <td>{{$item->maternal_surname}}</td>
                        <td>{{$item->grade}}</td>
                        <td>
                            <a type="button"  class="btn btn-warning " data-id="{{$item->id}}" href="{{ route('form_calif', ['id' => $item->id]) }}">
                                   <i class="icon-pencil"></i>
                            </a>
                        </td>                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- <nav>
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#">Ant</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#"></a>
                    </li>
                    <li class="page-item" >
                        <a class="page-link" href="#" >Sig</a>
                    </li>
                </ul>
            </nav> -->
        </div>
    </div>
    <!-- Fin ejemplo de tabla Listado -->
</div>

    </main>
</template>
@endsection