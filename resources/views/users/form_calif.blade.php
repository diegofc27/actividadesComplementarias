@extends('main')
@section('content')
<main class="main">
        <!-- Breadcrumb -->
<ol class="breadcrumb">
    <!-- <li class="breadcrumb-item"><a href="/">Escritorio</a></li> -->
</ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <h6>{{session('success')}}</h6>
            </div>
        @endif
        @if (\Session::has('alert'))
            <div class="alert alert-danger">
                <h6>{{session('alert')}}</h6>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <i class="fa fa-user-plus fa-3x ml-1 mr-1"></i>
                    <h1 class="">Registrar Calificación</h1>
                </div>
            </div>
            <div class="card-body">
            <form action="{{ route('register_calif') }}" method="post" class="form-horizontal">
                    {{ csrf_field() }}
                    
                    <h2 class="col-md-12">Datos del alumno</h2>
                    <br>
                <div class="row">
                        <div class="form-group row mb-2">

                    
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <label class="fa fa-user fa-2x col-md-4">Nombre del alumno:</label>
                                        <h3 class="col-md-4">{{ $alumno->nom_alumno }}</h3>
                                    </div>
                                </div>
                                
                                <br><br><br>

                                <div class="col-md-9">
                                    <div class="input-group">
                                        <label class="fa fa-asterisk fa-2x col-md-4">Apellido Paterno:</label>
                                        <h3 class="col-md-4">{{ $alumno->paternal_surname }}</h3>
                                    </div>
                                </div>
    
                                <br><br><br>

                                <div class="col-md-9">
                                    <div class="input-group">
                                        <label class="fa fa-asterisk fa-2x col-md-4">Apellido Materno:</label>
                                        <h3 class="col-md-4">{{ $alumno->maternal_surname }}</h3>
                                    </div>
                                </div>

                                <br><br><br>

                                <div class="col-md-9">
                                    <div class="input-group">
                                        <label class="fa fa-asterisk fa-2x col-md-4">Grupo:</label>
                                        <h3 class="col-md-4">{{ $alumno->name }}</h3>
                                    </div>
                                </div>
                                
    
                                <br><br><br>

                                <div class="col-md-9">
                                        <div class="input-group">
                                            <label class="fa fa-asterisk fa-2x col-md-4">Grupo:</label>
                                            <h3 class="col-md-4">{{ $alumno->grade }}</h3>
                                        </div>
                                    </div>
    
                                <div class="col-md-9">
                                    <div class="input-group">
                                            <label class="fa fa-users fa-2x col-md-4">Calificacion del Alumno: *</label>
                                        <select class="form-control col-md-4" name="calif">
                                           <option selected="true" disabled="disabled">-- opciones --</option>    
                                           <option value="AC">ACREDITAR</option>
                                           <option value="N-A"> NO ACREDITAR</option>
                                        </select>
                                    </div>
                                </div>
    
                                <br><br><br>
                                
                            </div>
                </div>
                <input type="hidden" name="id" value="{{ $alumno->id }}">
                <div class="card-footer">
                        <button class="btn btn-sm btn-success" type="submit">
                        <i class="fa fa-dot-circle-o"></i> Registrar </button>

                            <a class="btn btn-sm btn-danger" href="{{ route('group_students', ['id' => $alumno->id_group]) }}">Volver</a>
                </div>
            </form>
            </div>
            
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    </main>
</template>
@endsection