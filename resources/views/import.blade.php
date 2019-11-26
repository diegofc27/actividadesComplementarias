<!DOCTYPE html>
<html>
<head>
    <title>Importacion de Estudiantes - Extraescolares - ITH</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>

    @include('layout.head')

<div class="container">
    <div class="text-center">
        <img src="http://ith.mx/Logo%20ITH%20FA.PNG" alt="Logo ITH" height="350" width="350" class="mt-5" />
    </div>
    <div class="card bg-light mt-3">
        <div class="card-header">
            Importacion de Estudiantes - Extraescolares - ITH
        </div>
        <div class="card-body">
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" id="file" class="form-control">
                <br>
                <button type="submit" class="btn btn-success" id="btnImportar">Importar Estudiantes</button>
                <!-- <a class="btn btn-warning" href="{{ route('export') }}">Export User Data</a> -->
            </form>
        </div>
    </div>
</div>
   
</body>
</html>

@include('layout.footer')
<script>
    $( "#btnImportar" ).click(function() {
        if($('#file').get(0).files.length == 0 ){
            toastr.error('Tienes que escoger un archivo de importación de alumnos antes de continuar', 'Error!', {timeOut: 3000});
            event.preventDefault();
            return;
        }else{
            $.blockUI
            ({
                message: '<div class="ft-refresh-cw icon-spin white font-large-1"></div>  <div style="color: white;"><strong>Importando Alumnos...</strong></div>',
                overlayCSS: {
                    opacity: 0.6,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        }
    });
</script>