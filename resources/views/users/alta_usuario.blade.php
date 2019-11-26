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
        <div class="row">
                        <div class="col-md-12">
                            @if ($errors->any())
								<div class="alert alert-danger alert-dismissible">
									<a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
									<ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
								</div>
                            @endif
                        </div>
                    </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <i class="fa fa-user-plus fa-3x ml-1 mr-1"></i>
                    <h1 class="">Crear Usuario</h1>
                </div>
            </div>
            <div class="card-body">
            <form action="{{ route('registrar') }}" method="post" class="form-horizontal">
                    {{ csrf_field() }}
                    
                    <h2 class="col-md-12">Datos del Usuario</h2>
                    <br>
                <div class="row">
                        <div class="form-group row mb-2">

                    
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label class="fa fa-user fa-2x col-md-4">Nombre del usuario *</label>
                                        <input type="text" name="username" class="form-control col-md-9" v-model="username" placeholder="Clave/No_Control/Usuario"
                                            id="username">
                                    </div>
                                </div>
                                
                                <br><br><br>

                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label class="fa fa-asterisk fa-2x col-md-4">Contraseña*</label>
                                        <input type="password" name="password" class="form-control col-md-9" v-model="password" placeholder="Contraseña">
                                    </div>
                                </div>
    
                                <br><br><br>

                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label class="fa fa-asterisk fa-2x col-md-4">Confirmar contraseña*</label>
                                        <input type="password" name="password_confirm" class="form-control col-md-9" v-model="password" placeholder="Contraseña">
                                    </div>
                                </div>
    
                                <br><br><br>
    
                                <div class="col-md-12">
                                    <div class="input-group">
                                            <label class="fa fa-users fa-2x col-md-4">Rol de Usuario *</label>
                                        <select class="form-control col-md-9" name="role">
                                            @foreach($roles as $item)
                                                <option value="{{ $item->id }}"> {{ $item->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
    
                                <br><br><br>
                                
                            </div>
                </div>
                
                <div class="card-footer">
                        <button class="btn btn-sm btn-success" type="submit">
                        <i class="fa fa-dot-circle-o"></i> Registrar </button>

                        <button class="btn btn-sm btn-danger" type="reset">
                        <i class="fa fa-ban"></i> Cancelar </button>
                </div>
            </form>
            </div>
            
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    </main>
</template>
@endsection