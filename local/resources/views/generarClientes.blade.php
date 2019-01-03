@extends('partials.layout')

@section('content')
	
	<h3 class="center-align">Tarjetas de alimentacion</h3>
	<h4 class="center-align">Generar clientes</h4>
	
	<center>
		<a class="waves-effect waves-light btn" onclick="seleccionarTodos()">seleccionar todos</a>
	</center>

	<div class="container">
		<form action="{{url('/tarjetaAlimentacion/generarClientes')}}" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<table>
				<thead>
					<th>Cedula</th>
					<th>Nombre</th>
					<th>Acción</th>
				</thead>
				<tbody>
					@foreach($empleados as $empleado)
						<tr>
							<td>{{$empleado->cedula}}</td>
							<td>{{$empleado->nombre}} {{$empleado->apellido}}</td>
							<td>
						    	<input type="checkbox" name="empleado[]" value="{{$empleado->id}}" id="usuario{{$empleado->id}}" />
						    	<label for="usuario{{$empleado->id}}">Incluir</label>
	    					</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<center>
				<input type="submit" class="btn" value="Generar">
			</center>
		</form>
	</div>

	<script type="text/javascript">
		
		function seleccionarTodos(){

			$('input:checkbox').prop("checked", true);

		}

	</script>

@endsection