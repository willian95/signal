<!DOCTYPE html>
<html>
<head>
	<title>Vacaciones</title>
</head>
<body>

	<img src="{!!public_path().'/img/zamora.png'!!}" style="width: 100%;">
        <img src="{!!public_path().'/img/zonfipca.png'!!}" style="width: 120px; height: 100px; position: absolute; left: -10px; top: 40px; left: 5px;">


	@foreach($reportes as $reporte)

		<p style="text-align: right; margin-top: 10px;">{{$reporte->fecha_registro}}</p>

		<h3 style="text-align: center;">Vacaciones</h3>

		<p>Nombre y apellido: <strong>{{$reporte->nombre}} {{$reporte->apellido}}</strong></p> 
		<p style="text-align: right; position: fixed; top: 160px;">Cedula de identidad: <strong>{{$reporte->cedula}}</strong></p>

		<p>Fecha inicial: <strong>{{$reporte->fecha_inicio}}</strong></p> 
		<p style="text-align: right; position: fixed; top: 212px;">Fecha final: <strong>{{$reporte->fecha_fin}}</strong></p>

		<table style="width: 100%; border: 1px solid; border-collapse: collapse;">
	        <thead >
	          <tr style="border: 1px solid;">
	              <th style="border: 1px solid;">Concepto</th>
	              <th style="border: 1px solid;">Referencia</th>
	              <th style="border: 1px solid;">Asignación</th>
	              <th style="border: 1px solid;">Deducción</th>
	          </tr>
	        </thead>

	        <tbody>
	          <tr style="border: 1px solid;">
	            <td style="border: 1px solid;">Dias de vacaciones</td>
	            <td style="border: 1px solid;">{{$reporte->dias_vacaciones}}</td>
	            <td style="border: 1px solid;">{{round($reporte->vacaciones_pagar, 2)}}</td>
	            <td style="border: 1px solid;"></td>
	          </tr>
	          <tr style="border: 1px solid;">
	            <td style="border: 1px solid;">Dias bono vacacional</td>
	            <td style="border: 1px solid;">{{$reporte->bono_vacacional}}</td>
	            <td style="border: 1px solid;">{{round($reporte->bono_vacacional_pagar, 2)}}</td>
	            <td style="border: 1px solid;"></td>
	          </tr>
	          <tr style="border: 1px solid;">
	            <td style="border: 1px solid;">FAOV</td>
	            <td style="border: 1px solid;">1</td>
	            <td style="border: 1px solid;"></td>
	            <td style="border: 1px solid;">{{round($reporte->faov, 2)}}</td>
	          </tr>
	          <tr style="border: 1px solid;">
	            <td>Subtotal</td>
	            <td></td>
	            <td style="border: 1px solid;"><?php

	            	echo round($reporte->asignacion, 2);

	            ?></td>
	            <td></td>
	          </tr>
	          <tr style="border: 1px solid;">
	            <td style="border-top: 1px solid;">total</td>
	            <td style="border-top: 1px solid;"></td>
	            <td style="border-top: 1px solid;"></td>
	            <td style="border-top: 1px solid;"><?php

	            $deduccion = $reporte->asignacion - $reporte->faov;

	            	echo round($deduccion, 2);

	            ?></td>
	          </tr>


	        </tbody>
		</table>
		

	@endforeach

</body>
</html>