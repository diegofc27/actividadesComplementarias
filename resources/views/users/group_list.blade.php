@extends('main')
@section('content')
<main class="main">
        <!-- Breadcrumb -->
<ol class="breadcrumb">
    <!-- <li class="breadcrumb-item"><a href="/">Escritorio</a></li> -->
</ol>
@if (\Session::has('error'))
<div class="alert alert-danger">
    <h6>{{session('error')}}</h6>
</div>
@endif
<div class="container-fluid">
    <!-- Ejemplo de tabla Listado -->
    <div class="card">
        <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h1>Lista de Grupos Asignados</h1>

            </div>
            <table class="table table-bordered table-striped" id="table_grupos">
                <thead>
                    <tr>
                        <th>Nombre del grupo</th>
                        <th>Numero actual de alumnos</th>
                        <th>Numero maximo de alumnos</th>
                        <th>Horario</th>
                        <th>Lista de asistencia</th>
                    </tr>
                </thead>
                <tbody><!-- Aqui empiezan la lista de grupos ================================================================================= -->
                    @foreach($grupos as $item)
                    <tr>                        
                        <td>{{$item->name}}</td>
                        <td>{{$item->num_students}}</td>
                        <td>{{$item->max_students}}</td>
                        <td>{{$item->schedule}}</td>
                        <td>
                            <a data-id="{{$item->id}}" href="{{ route('group_students', ['id' => $item->id]) }}" type="button"  class="btn btn-warning">
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