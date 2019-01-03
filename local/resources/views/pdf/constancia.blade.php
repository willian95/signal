<!DOCTYPE html>
<html>
<head>
	<title>Constancia</title>
</head>
<body>

	<img src="{!! public_path().'/img/cintillo.jpg'!!}" style="width: 100%;">

	<br>
	<br>
	<br>
	<h3 style="text-align: center;"><u>CONSTANCIA</u></h3>
	<br>
	<br>
	<br>

	@foreach($datos as $dato)


		<p style="text-align: justify; margin-left: 15px; font-size: 18px;">Por medio de la presente, se hace constar que el ciudadano: <strong style="text-transform: uppercase;">{{$dato->nombre}} {{$dato->apellido}}</strong>, titular de la cédula de identidad <strong>N° {{$dato->cedula}}</strong>, prestó servicios en esta empresa, desde el {{$dato->fecha_ingreso}} al {{$fecha_salida}}, desempeñando el cargo de {{$dato->cargo_descripcion}}, devengando un sueldo mensual de <strong>BOLÍVARES {{$sueldo_letras}} BS. ({{$sueldo}}), más Prima de transporte de BOLÍVARES {{$primaTransporte_letras}} (Bs. {{$primaTransporte}}), Prima de Antigüedad DE BOLÍVARES {{$primaAntiguedad_letras}} (Bs. {{$primaAntiguedad}}) @if($primaProfesionalizacion == '').   @else y Prima de Profesionalización de BOLÍVARES {{$primaProfesionalizacion_letras}} ({{$primaProfesionalizacion}}).@endif</strong></p>

	@endforeach

	<br>
	<p style="text-align: justify; margin-left: 15px; font-size: 18px;">Constancia que se expide a petición de la parte interesada a los {{$dia_letras}} ({{$dia}}) días del mes de {{$mes}} de {{$año}}</p>

	<br>
	<br>
	<p style="text-align: center;"><strong>{{DB::table('panel_control')->pluck('nombre_gerente_rh')}}</strong> <br> <strong>GERENTE DE RECURSOS HUMANOS (E)</strong></p>
</body>
</html>
