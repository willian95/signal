<!DOCTYPE html>
<html>
<head>
	<title>Retenciones</title>
</head>
<body>
<?php 
	$departamentos = DB::table('departamentos')->get();
	$subtotal_completo_empleados = 0;
	$subtotal_completo_operarios = 0;

	$coordinador_rh = DB::table('panel_control')->pluck('nombre_coordinador_rh');
	$gerente_rh = DB::table('panel_control')->pluck('nombre_gerente_rh');
	$gerente_finanzas = DB::table('panel_control')->pluck('nombre_gerente_finanzas');
?>
	
	<div style="text-align: center; margin-bottom: -12px;"><strong>RELACIÓN DE EMPLEADOS (NÓMINA QUINCENAL) Y OPERARIOS (NÓMINA SEMANAL)</strong></div>
	<div style="text-align: center;"><strong>TESORERIA DE SEGURIDAD SOCIAL</strong></div>

	<p>Mes y Año: <strong>{{$mes}}/{{$annio}}</strong></p>

	<h3 style="text-align: center;">EMPLEADOS</h3>
	<div style="border: 1px solid; font-size: 12px; margin-bottom: -3px; margin-left: 0.4px; margin-right: 0.4px;"><strong>NOMBRE DE EMPRESA:ZONA FRANCA INDUSTRIAL COMERCIAL Y DE SERVICIOS DE PARAGUANA </strong></div>
	<div style="border: 1px solid; font-size: 12px; margin-bottom: -1.8px; margin-left: 0.4px; margin-right: 0.4px;"><strong>TELÉFONOS: 2482553-2481198</strong></div>
	
	<table style="width: 100%; border: 1px solid; border-collapse: collapse;">
		<thead>
			<tr style="border: 1px solid; font-size: 13px;">
				<td style="border: 1px solid;">
					<strong>APELLIDOS Y NOMBRES</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>CÉDULA DE IDENTIDAD</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>FECHA DE INGRESO</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>SALARIO MENSUAL</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>APORTE TRABAJADOR 3%</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>APORTE EMPRESA 3%</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>TOTAL</strong>
				</td>
			</tr>
		</thead>
		<tbody>

			@foreach($departamentos as $departamento)
				<?php
					$subtotal_aporte = 0;
					$subtotal = 0;

					$empleados = DB::table('datos_empleados')
										->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
										->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
										->where('datos_laborales.departamento_id', $departamento->id)
										->where('datos_laborales.tipo_trabajador', 'empleado')
										->where('datos_empleados.status', 'activo')
										->select('datos_empleados.cedula', 'datos_empleados.nombre', 'salarios.sueldo','datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'datos_empleados.id')
										->distinct()
										->get();
					
					$num_empleados = DB::table('datos_empleados')
										->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
										->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
										->where('datos_laborales.departamento_id', $departamento->id)
										->where('datos_laborales.tipo_trabajador', 'empleado')
										->where('datos_empleados.status', 'activo')
										->select('datos_empleados.cedula', 'datos_empleados.nombre', 'salarios.sueldo','datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'datos_empleados.id')
										->distinct()
										->count();

				?>
					@if($num_empleados > 0)
						<tr style="background-color: #90caf9;">
							<td colspan="7" style="text-align: center; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
								{{$departamento->nombre_departamento}}
							</td>
						</tr>
					@endif

				@foreach($empleados as $empleado)
				
					<?php 

						$aporte =  DB::table('nomina')
										->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
										->where('fecha_inicio', 'like',  '%'.$mes.'/'.$annio.'%')
										->where('conceptos_nominas.id_empleado', $empleado->id)
										->where('conceptos_nominas.id_concepto', 20222)
										->sum('conceptos_nominas.monto');

						
						$aporte = round($aporte, 2);
						$subtotal_aporte += $aporte;

						$subtotal_aporte = round($subtotal_aporte, 2);
					?>
					@if($aporte > 0)
						<tr style="border: 1px solid; font-size: 12px;">
							<td style="border: 1px solid;">
								{{$empleado->nombre}} {{$empleado->apellido}}
							</td>
							<td style="border: 1px solid;">
								{{$empleado->cedula}}
							</td>
							<td style="border: 1px solid;">
								{{$empleado->fecha_ingreso}}
							</td>
							<td style="border: 1px solid;">
								{{$empleado->sueldo}}
							</td>
							<td style="border: 1px solid;">
								{{$aporte}}
							</td>
							<td style="border: 1px solid;">
								{{$aporte}}
							</td>
							<td style="border: 1px solid;">
								{{$aporte * 2}}
							</td>
						</tr>
					@endif
				
				@endforeach
					<?php  
						$subtotal = $subtotal_aporte * 2;
						$subtotal_completo_empleados+= $subtotal; 

						$subtotal = round($subtotal, 2);
						$subtotal_completo_empleados = round($subtotal_completo_empleados, 2);

					?>
					@if($subtotal > 0)
						<tr style="border: 1px solid; font-size: 13px;">
							<td colspan="4" style="text-align: center; border: 1px solid;">
								Subtotal
							</td>
							<td style="border: 1px solid;">
								{{$subtotal_aporte}}
							</td>
							<td style="border: 1px solid;">
								{{$subtotal_aporte}}
							</td>
							<td style="border: 1px solid;">
								{{$subtotal}}
							</td>
						</tr>
					@endif

			@endforeach

			<tr style="border: 1px solid;">
				<td colspan="6">
					<strong>SUB- TOTAL  APORTE EMPLEADOS Y EMPRESA</strong>
				</td>
				<td>
					{{$subtotal_completo_empleados}}
				</td>
			</tr>
			<tr style="border: 1px solid;">
				<td colspan="7" style="border: 1px solid; padding-top: 15px;">
					
				</td>
			</tr>

		</tbody>
	</table>

	<div style="page-break-after: always;"></div>

	<h3 style="text-align: center;">OPERARIOS</h3>
	<table style="width: 100%; border: 1px solid; border-collapse: collapse;">
		<thead>
			<tr style="border: 1px solid; font-size: 13px;">
				<td style="border: 1px solid;">
					<strong>APELLIDOS Y NOMBRES</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>CÉDULA DE IDENTIDAD</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>FECHA DE INGRESO</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>SALARIO MENSUAL</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>APORTE TRABAJADOR 3%</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>APORTE EMPRESA 3%</strong>
				</td>
				<td style="border: 1px solid;">
					<strong>TOTAL</strong>
				</td>
			</tr>
		</thead>
		<tbody>

			@foreach($departamentos as $departamento)
				<?php
					$subtotal_aporte = 0;
					$subtotal = 0;

					$obreros= DB::table('datos_empleados')
										->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
										->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
										->where('datos_laborales.departamento_id', $departamento->id)
										->where('datos_laborales.tipo_trabajador', 'obrero')
										->where('datos_empleados.status', 'activo')
										->select('datos_empleados.cedula', 'datos_empleados.nombre', 'salarios.sueldo','datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'datos_empleados.id')
										->distinct()
										->get();
					
					$num_obreros = DB::table('datos_empleados')
										->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
										->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
										->where('datos_laborales.departamento_id', $departamento->id)
										->where('datos_laborales.tipo_trabajador', 'obrero')
										->where('datos_empleados.status', 'activo')
										->select('datos_empleados.cedula', 'datos_empleados.nombre', 'salarios.sueldo','datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'datos_empleados.id')
										->distinct()
										->count();

				?>
					@if($num_obreros > 0)
						<tr style="background-color: #90caf9;">
							<td colspan="7" style="text-align: center; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
								{{$departamento->nombre_departamento}}
							</td>
						</tr>
					@endif

				@foreach($obreros as $obrero)
				
					<?php 

						$aporte =  DB::table('nomina')
										->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
										->where('fecha_inicio', 'like',  '%'.$mes.'/'.$annio.'%')
										->where('conceptos_nominas.id_empleado', $obrero->id)
										->where('conceptos_nominas.id_concepto', 20222)
										->sum('conceptos_nominas.monto');

						
						$aporte = round($aporte, 2);
						$subtotal_aporte += $aporte;

						$subtotal_aporte = round($subtotal_aporte, 2);
					?>
					@if($aporte > 0)
						<tr style="border: 1px solid; font-size: 12px;">
							<td style="border: 1px solid;">
								{{$obrero->nombre}} {{$obrero->apellido}}
							</td>
							<td style="border: 1px solid;">
								{{$obrero->cedula}}
							</td>
							<td style="border: 1px solid;">
								{{$obrero->fecha_ingreso}}
							</td>
							<td style="border: 1px solid;">
								{{$obrero->sueldo}}
							</td>
							<td style="border: 1px solid;">
								{{$aporte}}
							</td>
							<td style="border: 1px solid;">
								{{$aporte}}
							</td>
							<td style="border: 1px solid;">
								{{$aporte * 2}}
							</td>
						</tr>
					@endif
				
				@endforeach
					<?php  
						$subtotal = $subtotal_aporte * 2;
						$subtotal_completo_operarios += $subtotal; 

						$subtotal = round($subtotal, 2);
						$subtotal_completo_operarios = round($subtotal_completo_operarios, 2);

					?>
					@if($subtotal > 0)
						<tr style="border: 1px solid; font-size: 13px;">
							<td colspan="4" style="text-align: center; border: 1px solid;">
								Subtotal
							</td>
							<td style="border: 1px solid;">
								{{$subtotal_aporte}}
							</td>
							<td style="border: 1px solid;">
								{{$subtotal_aporte}}
							</td>
							<td style="border: 1px solid;">
								{{$subtotal}}
							</td>
						</tr>
					@endif

			@endforeach

			<tr style="border: 1px solid;">
				<td colspan="6">
					<strong>SUB- TOTAL  APORTE OPERARIOS Y EMPRESA</strong>
				</td>
				<td>
					{{$subtotal_completo_operarios}}
				</td>
			</tr>
			<tr style="border: 1px solid;">
				<td colspan="7" style="border: 1px solid; padding-top: 15px;">
					
				</td>
			</tr>

			<tr style="border: 1px solid;">
				<td colspan="6">
					<strong>TOTAL APORTE PATRONAL Y OPERARIOS</strong>
				</td>
				<td>
					{{$subtotal_completo_operarios + $subtotal_completo_empleados}}
				</td>
			</tr>

		</tbody>
	</table>


	<div style="position: relative;
  right: 0;
  bottom: 0;
  left: 0;
  padding: 1rem;
  text-align: center;">
			<table style="width: 100%; ">
		<tr>
			<td style="text-align: center">
				Elaborado por:
			</td>
			<td style="text-align: center">
				Revisado por:
			</td>
			<td style="text-align: center">
				Aprobado por:
			</td>
		</tr>
		<tr>
			<td colspan="3" style="padding-top: 10px; padding-bottom: 10px;">
				
			</td>
		</tr>

		<tr>
			<td style="text-align: center; font-size: 12px;">
				<strong>{{$coordinador_rh}}<br>
				COORDiNADOR DE RECURSOS HUMANOS</strong>

			</td>
			<td style="text-align: center; font-size: 12px;">
				<strong>{{$gerente_rh}}<br>
				GERENTE DE RECURSOS HUMANOS (E)</strong>
			</td>
			<td style="text-align: center; font-size: 12px;">
				<strong>{{$gerente_finanzas}}<br>
				GERENTE DE ADMINISTRACIÓN Y FINANZAS</strong>
			</td>
		</tr>
	</table>
	</div>


</body>
</html>