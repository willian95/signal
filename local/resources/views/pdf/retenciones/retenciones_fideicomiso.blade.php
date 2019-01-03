<!DOCTYPE html>
<html>
<head>
	<title>Retenciones</title>
</head>
<body>
<?php 
	$departamentos = DB::table('departamentos')->get();
	$subtotal_aporte_completo = 0;
    $subtotal_aporte_patronal_completo = 0;

	$coordinador_rh = DB::table('panel_control')->pluck('nombre_coordinador_rh');
	$gerente_rh = DB::table('panel_control')->pluck('nombre_gerente_rh');
	$gerente_finanzas = DB::table('panel_control')->pluck('nombre_gerente_finanzas');
?>
	
	<div style="text-align: center; margin-bottom: -12px;"><strong>RELACIÓN DE EMPLEADOS (NÓMINA QUINCENAL) Y OPERARIOS (NÓMINA SEMANAL)</strong></div>
	<div style="text-align: center;"><strong>FIDEICOMISO</strong></div>

	<p>Fecha: <strong>{{$mes}}/{{$annio}}</strong></p>

	<div style="border: 1px solid; font-size: 12px; margin-bottom: -3px; margin-left: 0.4px; margin-right: 0.4px;"><strong>NOMBRE DE EMPRESA:ZONA FRANCA INDUSTRIAL COMERCIAL Y DE SERVICIOS DE PARAGUANA </strong></div>
	<div style="border: 1px solid; font-size: 12px; margin-bottom: -1.8px; margin-left: 0.4px; margin-right: 0.4px;"><strong>TELÉFONOS: 2482553-2481198</strong></div>
	
	                                                    <!--  Empleados  -->
	
	
	<table style="width: 100%; border: 1px solid; border-collapse: collapse; page-break-after:always;">
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
					<strong>SALARIO DEVENGADO</strong>
				</td>
				<td style="border: 1px solid; text-align: center;">
					<strong>FIDEICOMISO</strong>
				</td>
			</tr>
		</thead>
		<tbody>
		<?php $subtotal_empleados = 0;?>
			@foreach($departamentos as $departamento)

			<?php  

					$empleados = DB::table('sueldo_nominas')
										->join('datos_empleados', 'sueldo_nominas.id_empleado', '=', 'datos_empleados.id')
										->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'sueldo_nominas.id_empleado')
										->where('sueldo_nominas.id_nomina', $mayor_empleado)
										->where('datos_laborales.departamento_id', $departamento->id)
										->where('datos_empleados.status', 'activo')
										->select('datos_empleados.cedula', 'datos_empleados.nombre', 'datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'sueldo_nominas.sueldo', 'datos_empleados.id as empleado_id')
										->get();
										
					$num_empleados = DB::table('sueldo_nominas')
										->join('datos_empleados', 'sueldo_nominas.id_empleado', '=', 'datos_empleados.id')
										->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'sueldo_nominas.id_empleado')
										->where('sueldo_nominas.id_nomina', $mayor_empleado)
										->where('datos_laborales.departamento_id', $departamento->id)
										->where('datos_empleados.status', 'activo')
										->select('datos_empleados.cedula', 'datos_empleados.nombre', 'datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'sueldo_nominas.sueldo')
										->count();
					
				?>


				<?php
					$subtotal_departamento = 0;
				?>

				@if($num_empleados > 0)
					<tr style="background-color: #90caf9;">
						<td colspan="5" style="text-align: center; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
							{{$departamento->nombre_departamento}}
						</td>
					</tr>
				@endif

				@foreach($empleados as $empleado)
					
					@if($empleado->empleado_id == 57 || $empleado->empleado_id == 58)
						
					@else


					<?php 

                        $monto = DB::table('conceptos_nominas')
                                        ->join('nomina', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
                                        ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                                        ->where('id_empleado', $empleado->empleado_id)
                                        ->where('nomina.fecha_inicio', 'like', '%/'.$mes.'/'.$annio.'%')
                                        ->where('conceptos.tipo', 'asignacion')
                                        ->sum('conceptos_nominas.monto');

	                    $fideicomiso = DB::table('conceptos_nominas')
	                                    ->join('nomina', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
	                                    ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
	                                    ->where('id_empleado', $empleado->empleado_id)
	                                    ->where('nomina.fecha_inicio', 'like', '%/'.$mes.'/'.$annio.'%')
	                                    ->where('conceptos.id', 20100)
	                                    ->sum('conceptos_nominas.monto');
					?>

					
						
					

					@if($fideicomiso > 0)
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
							{{round($monto, 2)}}
						</td>
						<td style="border: 1px solid;">
							{{round($fideicomiso, 2)}}
						</td>
					</tr>
					@endif

					<?php  
						$subtotal_departamento += $fideicomiso; 
					?>
					
					@endif

				
				@endforeach
					<?php  
						$subtotal_empleados += $subtotal_departamento; 
					?>
					@if($subtotal_departamento> 0)
					<tr style="border: 1px solid; font-size: 13px;">
						<td colspan="4" style="text-align: center; border: 1px solid;">
							Subtotal
						</td>
						<td style="border: 1px solid;">
							{{round($subtotal_departamento, 2)}}
						</td>
					</tr>
					@endif
			@endforeach

			<tr style="border: 1px solid;">
				<td colspan="4">
					<strong>SUB- TOTAL FIDEICOMISO EMPLEADOS</strong>
				</td>
				<td>
					{{round($subtotal_empleados, 2)}}
				</td>
			</tr>
			<tr style="border: 1px solid;">
				<td colspan="5" style="border: 1px solid; padding-top: 15px;">
					
				</td>
			</tr>

		</tbody>
	</table>

	<div style="page-break: before;">

                                                      <!--  Obreros  -->

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
					<strong>SALARIO DEVENGADO</strong>
				</td>
				<td style="border: 1px solid; text-align: center;">
					<strong>FIDEICOMISO</strong>
				</td>
			</tr>
		</thead>
		<tbody>
		<?php
			$subtotal_obreros = 0;
		?>

	@foreach($departamentos as $departamento)

			<?php  
						
					$obreros = DB::table('sueldo_nominas')
										->join('datos_empleados', 'sueldo_nominas.id_empleado', '=', 'datos_empleados.id')
										->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'sueldo_nominas.id_empleado')
										->where('sueldo_nominas.id_nomina', $mayor_obrero)
										->where('datos_laborales.departamento_id', $departamento->id)
										->where('datos_empleados.status', 'activo')
										->select('datos_empleados.cedula', 'datos_empleados.nombre', 'datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'sueldo_nominas.sueldo', 'datos_empleados.id as obrero_id')
										->get();
					
					$num_obreros = DB::table('sueldo_nominas')
										->join('datos_empleados', 'sueldo_nominas.id_empleado', '=', 'datos_empleados.id')
										->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'sueldo_nominas.id_empleado')
										->where('sueldo_nominas.id_nomina', $mayor_obrero)
										->where('datos_laborales.departamento_id', $departamento->id)
										->where('datos_empleados.status', 'activo')
										->select('datos_empleados.cedula', 'datos_empleados.nombre', 'datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'sueldo_nominas.sueldo')
										->count();
				

				?>
					

				<?php
					$subtotal_departamento = 0;
				?>

				@if($num_obreros > 0)
					<tr style="background-color: #90caf9;">
						<td colspan="5" style="text-align: center; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
							{{$departamento->nombre_departamento}}
						</td>
					</tr>
				@endif

				@foreach($obreros as $obrero)
					
					@if($obrero->obrero_id == 57 || $obrero->obrero_id == 58)
					
					@else
					<?php 
						$monto = DB::table('conceptos_nominas')
                                        ->join('nomina', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
                                        ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                                        ->where('id_empleado', $obrero->obrero_id)
                                        ->where('nomina.fecha_inicio', 'like', '%/'.$mes.'/'.$annio.'%')
                                        ->where('conceptos.tipo', 'asignacion')
                                        ->sum('conceptos_nominas.monto');

	                    $fideicomiso = DB::table('conceptos_nominas')
	                                    ->join('nomina', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
	                                    ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
	                                    ->where('id_empleado', $obrero->obrero_id)
	                                    ->where('nomina.fecha_inicio', 'like', '%/'.$mes.'/'.$annio.'%')
	                                    ->where('conceptos.id', 20100)
	                                    ->sum('conceptos_nominas.monto');

	                    $nominas = DB::table('conceptos_nominas')
	                                    ->join('nomina', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
	                                    ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
	                                    ->where('id_empleado', $obrero->obrero_id)
	                                    ->where('nomina.id', 135)
	                                    ->where('conceptos.id', 20100)
	                                    ->select('nomina.id')
	                                    ->get();



					?>

					@foreach($nominas as $nomina)
						<tr><td colspan="5">{{$nomina->id}}</td></tr>
					@endforeach
				

					@if($fideicomiso > 0)
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
							{{round($monto, 2)}}
						</td>
						<td style="border: 1px solid;">
							{{round($fideicomiso, 2)}}
						</td>
					</tr>
					@endif
					<?php  
						$subtotal_departamento += $fideicomiso; 
					?>
					@endif
				@endforeach
					<?php  
						$subtotal_obreros += $subtotal_departamento; 
					?>
					@if($subtotal_departamento > 0)
						<tr style="border: 1px solid; font-size: 13px;">
							<td colspan="4" style="text-align: center; border: 1px solid;">
								Subtotal
							</td>
							<td style="border: 1px solid;">
								{{round($subtotal_departamento, 2)}}
							</td>
						</tr>
					@endif

			@endforeach


			<tr style="border: 1px solid;">
				<td colspan="4">
					<strong>SUB- TOTAL FIDEICOMISO OBREROS</strong>
				</td>
				<td>
					{{round($subtotal_obreros, 2)}}
				</td>
			</tr>
			<tr style="border: 1px solid;">
				<td colspan="5" style="border: 1px solid; padding-top: 15px;">
					
				</td>
			</tr>

			<tr style="border: 1px solid;">
				<td colspan="4">
					<strong>TOTAL APORTE PATRONAL Y EMPLEADOS</strong>
				</td>
				<td>
					{{round($subtotal_empleados + $subtotal_obreros, 2)}}
				</td>
			</tr>

		</tbody>
	</table>

<div style="position: absolute;
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
	
	<?php 
		
	?>

</body>
</html>