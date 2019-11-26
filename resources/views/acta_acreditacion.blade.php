<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Acta de acreditacion</title>
    <style>
    	body{
    		background-image: url("../img/fondo_Wea.jpeg");
    		background-repeat: no-repeat; /* Do not repeat the image */
    		margin: 50px;
    	}
    </style>
</head>
<body>
	<img src="img/sep.png" width="220" height="80">
	<img src="img/tecnologico.png" width="240" height="70" style="margin-left: 165px">
	<p style="text-align: right; margin-top: 0px; margin-bottom: 50px;">Instituto Tecnologico de Hermosillo</p>
<p style="text-align: center;margin-top: 15px"><strong>“2019, Año del Caudillo del Sur, Emiliano Zapata”</strong></p>
<p style="text-align: center;"><strong>Constancia de Acreditación de Actividad Complementaria</strong></p>

<p style="text-align: right; margin-bottom: 0px">Hermosillo, Sonora 
@php 
                        echo Carbon\Carbon::now()->format('d')."/Noviembre/2019";
                        @endphp
</p>
<p style="text-align: right; margin-top: 0px">OFICIO No.: 2515/2019</p>

<p style="text-align: left;margin-bottom: 0px">M.S.I. BETTINA ELISA SANTA CRUZ WELSH</p>
<p style="text-align: left;margin-bottom: 0px; margin-top: 0px">JEFE DEL DEPARTAMENTO DE SERVICIOS ESCOLARES</p>
<p style="text-align: left;margin-bottom: 0px; margin-top: 0px">PRESENTE.</p>

<p>La que suscribe Jefa del Depto. de Actividades Extraescolares, por este medio se
permite hacer de su conocimiento que el (la) estudiante {{$estudiante->paternal_surname}} {{$estudiante->maternal_surname}} {{$estudiante->name}} con número de control {{$estudiante->users->email}} de la carrera de {{$estudiante->career}} ha ACREDITADO la actividad complementaria de EXTRAESCOLARES I
durante el período escolar AGO-DIC/2019 con un valor curricular de 0.5 crédito.</p>

<p>Se extiende la presente en la Ciudad de Hermosillo, Sonora., a los 20 días del mes de Diciembre de
2019</p>

<p style="text-align: left;margin-bottom: 0px; margin-top: 40px">A T E N T A M E N T E</p>
<p  style="text-align: left;margin-bottom: 0px;margin-top: 0px"><strong>Excelencia en Educación Tecnológica</strong></p>
<p  style="text-align: left;margin-bottom: 0px;margin-top: 0px"><strong>
“En el Esfuerzo Común, la Grandeza de Todos”</strong></p>


<table style="margin-top: 60px;">
	<tr>
		<td>L.E.F. FCO. JAVIER PEREZ ARCE</td>
		<td>Vo.Bo.ING. LORENIA ACOSTA BELTRÁN</td>
	</tr>
	<tr>
		<td>JEFE DE LA OFICINA DE PROMOCIÓN</td>
		<td>JEFE DE DEPARTAMENTO DE</td>
	</tr>
	<tr>
		<td>DEPORTIVA</td>
		<td>EXTRAESCOLARES</td>
	</tr>
</table>
<hr style="margin-top: 50px; height: .3px;">
<p style="text-align: center;margin-bottom: 0px; font-size: 14px ">Av. Tecnológico S/N Col. El Sahuaro C.P. 83170 Hermosillo, Sonora</p>
<p style="text-align: center;margin-bottom: 0px;margin-top: 0px;font-size: 14px">Tel. (662) 2-606500 Ext. 126 e-mail: ext_hermosillo@tecnm.mx</p>
<p style="text-align: center;margin-bottom: 0px;margin-top: 0px;font-size: 14px">www.tecnm.mx | www.ith.mx</p>
</body>
</html>