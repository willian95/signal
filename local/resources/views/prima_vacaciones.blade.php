@extends('partials.layout')

@section('content')
	
	<table>
		<thead>
			<tr>
				<th>Nombres y apellidos</th>
				<th>Prima de transporte vacacional</th>
			</tr>
		</thead>
		<tbody>
			@foreach($personas as $persona)

				<tr>
					<td>
						{{$persona->nombre}} {{$persona->apellido}}
					</td>
					<td>
						<?php 
							$prima_vacacional = DB::table('prima_transporte_vacacional')->where('dato_empleado_id', $persona->id)->pluck('prima_vacacional');
						?>
						@if($prima_vacacional == 1)
							<a href="{{url('/prima_vacaciones/desactivar/').'/'.$persona->id}}" class="btn red">
								desactivar
							</a>
						@else
							<a href="{{url('/prima_vacaciones/activar/').'/'.$persona->id}}" class="btn">
								activar
							</a>
						@endif
					</td>
				</tr>

			@endforeach
		</tbody>
	</table>
@endsection