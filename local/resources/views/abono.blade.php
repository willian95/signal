@extends('partials.layout')

@section('content')

	<div class="container">
		
		<form action="" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<table>
				<thead>
					<th>Cedula</th>
					<th>Nombre</th>
					<th>Días</th>
					<th>Total</th>
				</thead>
				<tbody>
					@foreach($empleados as $empleado)
						<tr>
							<td>{{$empleado->cedula}}</td>
							<input name="empleado[]" value="{{$empleado->id}}" type="text" style="display: none;">
							<td>{{$empleado->nombre}} {{$empleado->apellido}}</td>
							<td>
								<div class="input-field col l12">
      								<input name="dias[]" type="text"  onchange="cambiarDias({{$empleado->id}})" class="validate" value="30" id="dias{{$empleado->id}}">
    							</div>
							</td>
							<td>
								<div class="input-field col l12">
      								<input type="text" class="validate" id="total{{$empleado->id}}" value="270000" disabled>
    							</div>
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
		
		function cambiarDias(id){
			var alimentacion = {{$alimentacion}}

			var dias = $('#dias'+id).val();
			var total = dias * alimentacion;

			$('#total'+id).val(total);

		}

	</script>

@endsection