<!DOCTYPE html>
<html>
<head>
	<title>Constancia</title>
</head>
<body>

	<img src="{!! public_path().'/img/cintillo.jpg'!!}" style="width: 100%;">
  <!--<img src="{!! public_path().'/img/zonfipca.png'!!}" style="width: 120px; height: 100px; position: absolute; left: -10px; top: 55px; left: 5px;">-->

	<br>
	<br>
	<br>
	<h3 style="text-align: center;"><u>CONSTANCIA</u></h3>
	<br>
	<br>
	<br>

	@foreach($datos as $dato)


		<p style="text-align: justify; margin-left: 15px; font-size: 18px;">Por medio de la presente, se hace constar que el ciudadano(a): <strong style="text-transform: uppercase;">{{$dato->nombre}} {{$dato->apellido}}</strong>, titular de la cédula de identidad <strong>N° {{$dato->cedula}}</strong>, presta sus servicios en esta empresa, desde el {{$dato->fecha_ingreso}}, desempeñando el cargo de {{$dato->cargo_descripcion}}, devengando un sueldo mensual de <strong style="text-transform: uppercase;">BOLÍVARES {{$sueldo_letras}} BS. ({{$dato->sueldo}}), más Prima de transporte de BOLÍVARES {{$primaTransporte_letras}} (Bs. {{$primaTransporte}})@if($primaAntiguedad > 0), Prima de Antigüedad de BOLÍVARES {{$primaAntiguedad_letras}} (Bs. {{$primaAntiguedad}})@endif @if($primaProfesionalizacion > 0) y Prima de Profesionalización de BOLÍVARES {{$primaProfesionalizacion_letras}} (Bs. {{$primaProfesionalizacion}}) @endif</strong></p>

	@endforeach

	<br>
	<p style="text-align: justify; margin-left: 15px; font-size: 18px;">Constancia que se expide a petición de la parte interesada a los {{$dia_letras}} ({{$dia}}) días del mes de {{$mes}} de {{$año}}</p>

	<br>
	<br>
	<p style="text-align: center; text-transform: uppercase;"><strong>{{DB::table('panel_control')->pluck('nombre_gerente_rh')}}</strong> <br> <strong>GERENTE DE RECURSOS HUMANOS (E)</strong></p>

	<img src="{!! public_path().'/img/zamora.png'!!}" style="width: 100%; position: absolute; bottom: 40px;">

</body>
</html>
