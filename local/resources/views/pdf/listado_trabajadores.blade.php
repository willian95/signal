<!DOCTYPE html>
<html>
<head>
	<title>Listado de trabajadores</title>
</head>
<body>

<?php 
	$departamentos = DB::table('departamentos')->get();
	$count = 0;
	$contador = 1;
	foreach($arrays as $array){
		if($array == 'on'){
			$count++;
		}
	}
?>

	<table style="width: 100%; border: 1px solid; border-collapse: collapse; font-size: 13px;">
		<thead>
			<tr style="border: 1px solid;">
				<td style="border: 1px solid; text-align: center;">
					<strong>N°</strong>
				</td>
				<td style="border: 1px solid; text-align: center;">
					<strong>N° Cedula</strong>
				</td>
				<td style="border: 1px solid; text-align: center;">
					<strong>Nombre</strong>
				</td>
				<td style="border: 1px solid; text-align: center;">
					<strong>Apellido</strong>
				</td>
				<td style="border: 1px solid; text-align: center;">
					<strong>Fecha Ingreso</strong>
				</td>
				<td style="border: 1px solid; text-align: center;">
					<strong>Cargo</strong>
				</td>
				@if($rif == 'on')
					<td style="border: 1px solid; text-align: center;">
						<strong>RIF</strong>
					</td>
				@endif
				@if($sexo == 'on')
					<td style="border: 1px solid; text-align: center;">
						<strong>Sexo</strong>
					</td>
				@endif
				@if($profesion == 'on')
					<td style="border: 1px solid; text-align: center;">
						<strong>Profesion</strong>
					</td>
				@endif
				@if($fecha_nacimiento == 'on')
					<td style="border: 1px solid; text-align: center;">
						<strong>Fecha de Nacimiento</strong>
					</td>
				@endif
				@if($estado_civil == 'on')
					<td style="border: 1px solid; text-align: center;">
						<strong>Estado Civil</strong>
					</td>
				@endif
				@if($direccion == 'on')
					<td style="border: 1px solid; text-align: center;">
						<strong>Direccion</strong>
					</td>
				@endif
				@if($correo == 'on')
					<td style="border: 1px solid; text-align: center;">
						<strong>Correo</strong>
					</td>
				@endif
				@if($telefono_fijo == 'on')
					<td style="border: 1px solid; text-align: center;">
						<strong>Telefono Fijo</strong>
					</td>
				@endif
				@if($telefono_movil == 'on')
					<td style="border: 1px solid; text-align: center;">
						<strong>Telefono Movil</strong>
					</td>
				@endif
			</tr>
		</thead>
		<tbody>
			@foreach($departamentos as $departamento)
				<tr style="background-color: #90caf9;">
					<td colspan="{{6+$count}}" style="text-align: center; padding-top: 10px; padding-bottom: 10px;">
						{{$departamento->nombre_departamento}}
					</td>
				</tr>

				<?php 

					$trabajadores = DB::table('datos_empleados')
											->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
											->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
											->where('datos_laborales.departamento_id', $departamento->id)
											->where('datos_empleados.status', 'activo')
											->get();
				?>

				@foreach($trabajadores as $trabajador)


						<tr style="border: 1px solid;">
							<td style="border: 1px solid; text-align: center; font-size: 10px;">
								{{$contador++}}
							</td>
							<td style="border: 1px solid; text-align: center; font-size: 10px;">
								{{$trabajador->cedula}}
							</td>
							<td style="border: 1px solid; text-align: center; font-size: 10px;">
								{{$trabajador->nombre}}
							</td>
							<td style="border: 1px solid; text-align: center; font-size: 10px;">
								{{$trabajador->apellido}}
							</td>
							<td style="border: 1px solid; text-align: center; font-size: 10px;">
								{{$trabajador->fecha_ingreso}}
							</td>
							<td style="border: 1px solid; text-align: center; font-size: 10px;">
								{{$trabajador->descripcion}}
							</td>
							@if($rif == 'on')
								<td style="border: 1px solid; text-align: center; font-size: 10px;">
									{{$trabajador->rif}}
								</td>
							@endif
							@if($sexo == 'on')
								<td style="border: 1px solid; text-align: center; font-size: 10px;">
									@if($trabajador->sexo == 'm')
										Masculino
									@else
										Femenino
									@endif
								</td>
							@endif
							@if($profesion == 'on')
								<td style="border: 1px solid; text-align: center; font-size: 10px;">
									{{$trabajador->profesion}}
								</td>
							@endif
							@if($fecha_nacimiento == 'on')
								<td style="border: 1px solid; text-align: center; font-size: 10px;">
									{{$trabajador->fecha_nacimiento}}
								</td>
							@endif
							@if($estado_civil == 'on')
								<td style="border: 1px solid; text-align: center; font-size: 10px;">
									{{$trabajador->estado_civil}}
								</td>
							@endif
							@if($direccion == 'on')
								<td style="border: 1px solid; text-align: center; font-size: 10px;">
									{{$trabajador->direccion}}
								</td>
							@endif
							@if($correo == 'on')
								<td style="border: 1px solid; text-align: center; font-size: 10px;">
									{{$trabajador->correo}}
								</td>
							@endif
							@if($telefono_fijo == 'on')
								<td style="border: 1px solid; text-align: center; font-size: 10px;">
									{{$trabajador->tlf_fijo}}
								</td>
							@endif
							@if($telefono_movil == 'on')
								<td style="border: 1px solid; text-align: center; font-size: 10px;">
									{{$trabajador->tlf_movil}}
								</td>
							@endif

						</tr>
		
				@endforeach

			@endforeach

			@if($pasante == 'on')
				<tr style="background-color: #90caf9;">
					<td colspan="{{6+$count}}" style="text-align: center; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
						Pasantes
					</td>
				</tr>

				@foreach($departamentos as $departamento)
					<?php 
						$pasantes_cantidad = DB::table('datos_empleados')
												->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
												->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
												->where('datos_laborales.departamento_id', $departamento->id)
												->where('datos_laborales.pasante', 1)
												->count();
					?>
				@if($pasantes_cantidad > 0)
					<tr style="background-color: #90caf9;">
						<td colspan="{{6+$count}}" style="text-align: center; padding-top: 10px; padding-bottom: 10px;">
							{{$departamento->nombre_departamento}}
						</td>
					</tr>
				@endif
				<?php 

					$trabajadores = DB::table('datos_empleados')
											->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
											->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
											->where('datos_laborales.departamento_id', $departamento->id)
											->where('datos_laborales.pasante', 1)
											->get();
				?>

				@foreach($trabajadores as $trabajador)


						<tr style="border: 1px solid;">
							<td style="border: 1px solid; text-align: center;">
								{{$contador++}}
							</td>
							<td style="border: 1px solid; text-align: center;">
								{{$trabajador->cedula}}
							</td>
							<td style="border: 1px solid; text-align: center;">
								{{$trabajador->nombre}}
							</td>
							<td style="border: 1px solid; text-align: center;">
								{{$trabajador->apellido}}
							</td>
							<td style="border: 1px solid; text-align: center;">
								{{$trabajador->fecha_ingreso}}
							</td>
							<td style="border: 1px solid; text-align: center;">
								{{$trabajador->descripcion}}
							</td>
							@if($rif == 'on')
								<td style="border: 1px solid; text-align: center;">
									{{$trabajador->rif}}
								</td>
							@endif
							@if($sexo == 'on')
								<td style="border: 1px solid; text-align: center;">
									@if($trabajador->sexo == 'm')
										Masculino
									@else
										Femenino
									@endif
								</td>
							@endif
							@if($profesion == 'on')
								<td style="border: 1px solid; text-align: center;">
									{{$trabajador->profesion}}
								</td>
							@endif
							@if($fecha_nacimiento == 'on')
								<td style="border: 1px solid; text-align: center;">
									{{$trabajador->fecha_nacimiento}}
								</td>
							@endif
							@if($estado_civil == 'on')
								<td style="border: 1px solid; text-align: center;">
									{{$trabajador->estado_civil}}
								</td>
							@endif
							@if($direccion == 'on')
								<td style="border: 1px solid; text-align: center;">
									{{$trabajador->direccion}}
								</td>
							@endif
							@if($correo == 'on')
								<td style="border: 1px solid; text-align: center;">
									{{$trabajador->correo}}
								</td>
							@endif
							@if($telefono_fijo == 'on')
								<td style="border: 1px solid; text-align: center;">
									{{$trabajador->tlf_fijo}}
								</td>
							@endif
							@if($telefono_movil == 'on')
								<td style="border: 1px solid; text-align: center;">
									{{$trabajador->tlf_movil}}
								</td>
							@endif

						</tr>
		
				@endforeach

			@endforeach
			@endif

		</tbody>
	</table>

</body>
</html>
