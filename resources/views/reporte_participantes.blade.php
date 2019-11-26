<!doctype html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Registro de participantes</title>

<style>
 th, td {
    border: 1px solid black;
    font-size: 14px;
}
h2,h4,h5{
    text-align: center;
    text-transform: uppercase;
    margin-bottom: 0px;
}
h5{
    margin-top: 0px;
}
table {
    border-collapse: collapse;
    width: 100%;
}
th {
    background-color: #ddd;
}
</style>

        
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <table >
                <tr>
                   
                    <td rowspan="2"> 
                        <img src="img/favicon.png" width="85px" height="85px" class="img-responsive center-block" style="margin-left: 25px">
                    </td>
                    <td>Formato para el Registro de Participantes de Actividades Culturales y/o Deportivas</td>
                    <td>Código: ITH-VI-IT-002-01</td>

                </tr>
                <tr>

                    <td rowspan="1">Referencias a la Norma ISO 9001:2015 8.5.1</td>
                    <td rowspan="1">Revisión: 2</td>

                </tr>
            </table>
                
            
            <h2>INSTITUTO TECNOLÓGICO DE HERMOSILLO</h2>
            <h4><strong>Subdirección de Planeación y Vinculación</strong></h4>
            <h5>DEPARTAMENTO DE ACTIVIDADES EXTRAESCOLARES</h5>
            <h5>OFICINA DE PROMOCIÓN CIVICA. SEMESTRE: AGOSTO-DICIEMBRE 2019-2</h5>

            <p style="text-align: left;"><strong>Actividad:</strong> {{$grupo->name}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="text-align: right; margin-left: 20px;"><strong>Horario:</strong> {{$grupo->schedule}}</span></p>

            <table class="cal">
                <tr>
                    <th>NO.</th>
                    <th>NOMBRE</th>
                    <th>CONTROL</th>
                    <th>ESP.</th>
                    <th>SEM</th>
                    <th>OBSERVACIONES</th>
                </tr>
                @php
                $index = 1;
                @endphp
                @foreach($estudiantes as $estudiante)
                <tr>
                    <td>{{$index}}</td>
                    <td>{{$estudiante->paternal_surname}} {{$estudiante->maternal_surname}} {{$estudiante->name}}</td>
                    <td>{{$estudiante->users->email}}</td>
                    <td>LADM</td>
                    <td>11</td>
                    <td>No Acreditado</td>
                </tr>
                @php
                $index++;
                @endphp
                @endforeach
                
                
            </table>
                       <p><strong>Hermosillo, Sonora a 
                        @php 
                        echo Carbon\Carbon::now()->format('d')." de Noviembre de 2019";
                        @endphp</strong></p>
<br><br>
           <table>
               <tr>
                   <td style="border:none; text-align:center;font-weight:bold">José Francisco López Santamaría T.S.U.</td>
                   <td style="border:none;text-align:center;font-weight:bold">T.S.U. Lizaní Martínez Meza</td>
                   <td style="border:none;text-align:center;font-weight:bold">Ing. Edgar Cheu Burgos</td>
               </tr>
               <tr>
                   <td style="border:none;text-align:center;font-weight:bold">Promotor Cívico</td>
                   <td style="border:none;text-align:center;font-weight:bold">Jefe de Oficina de Promoción Civica</td>
                   <td style="border:none;text-align:center;font-weight:bold">Jefe de Departamento Actividades Extraescolares</td>
               </tr>
           </table>
<br>
            
        </div>
    </body>
</html>
