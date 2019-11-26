<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/style.css" rel="stylesheet" />
    <title>Alumnos de grupo {{$data[0]->nameQ8}}</title>
   
</head>
<body>
    <h1 align="center">Instituto Tecnológico de Hermosillo</h1>
    <h3 align="center">Alumnos inscritos en el grupo {{$data[0]->nameQ8}} en el semestre <?php echo date('Y').'-'.(date('n') <= 6 ? '1':'2'); ?></h3>
    <table width="100%" style="align:center; border-collapse: collapse; border: 0px;">
        <tr>
            <th style="align:center; border: 1px solid; padding:12px;" width="40%">Nombre</th>
            <th style="align:center; border: 1px solid; padding:12px;" width="20%">Grupo Extraescolar</th>
            <th style="align:center; border: 1px solid; padding:12px;" width="15%">Calificación</th>
        </tr>
        @foreach ($data as $student)
        <tr>
            <td style="align:center; border: 1px solid; padding:12px;">{{$student->student_name}} {{$student->lastName1}} {{$student->lastName2}}</td>
            <td style="align:center; border: 1px solid; padding:12px;">{{$student->group_name}}</td>
            <td style="align:center; border: 1px solid; padding:12px;">{{$student->grade}}</td>
        </tr>
        @endforeach
    </table>
    </body>

</html>
