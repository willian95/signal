@extends('partials.layout')

@section('content')
	
	 @if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif

	<p class="center-align">
		<a class="btn-floating btn-large waves-effect waves-light green modal-trigger" href="#modal-reposo"><i class="material-icons">add</i></a>
	</p>


	<div class="container">
		<table class="bordered">
			<thead>
          		<tr>
              		<th>Cedula</th>
              		<th>Nombre y apellido</th>
              		<th>Fecha inicio</th>
              		<th>Fecha fin</th>
              		<th>Acciones</th>
          		</tr>
        	</thead>
        	<tbody>
        		@foreach($reposos as $reposo)
					<tr>
						<td>
							{{$reposo->cedula}}
						</td>
						<td>
							{{$reposo->nombre}} {{$reposo->apellido}}
						</td>
						<td>
							{{$reposo->fecha_inicio}}
						</td>
						<td>
							{{$reposo->fecha_fin}}
						</td>
						<td>
							<div class="row">
								<div class="col">
									<a class="waves-effect waves-light btn modal-trigger" href="#modal{{$reposo->id}}"><i class="material-icons">assignment</i></a>
								</div>
								<div class="col">
									<a onclick="eliminar({{$reposo->id}})" class="waves-effect waves-light btn red"><i class="material-icons">delete</i></a>
									<form id="form_eliminar{{$disfrute->id}}" style="display: none;" action="{{url('/disfrute_vacacional/eliminar/').'/'.$reposo->id}}" method="post">
				      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				      				</form>
								</div>
							</div>
						</td>
					</tr>

					<div id="modal{{$reposo->id}}" class="modal">
				    	<div class="modal-content">
				      		<h4 class="center-align">Motivo</h4>
				      		<p>{{$reposo->motivo}}</p>
				    	</div>
				    	<div class="modal-footer">
				      		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
				    	</div>
				  	</div>

        		@endforeach
        	</tbody>
		</table>
	</div>


	<div id="modal-reposo" class="modal">
    	<div class="modal-content">
      		<h4 class="center-align">Datos</h4>
      		
      		<div class="row">
      			<div class="input-field col s10">
	          		<input id="cedula" type="text">
	         	 	<label for="cedula">Cedula</label>
	        	</div>
	        	<div class="col s2">
	        		<a class="waves-effect waves-light btn" onclick="find()"><i class="material-icons">search</i></a>
	        	</div>
      		</div>
			<form action="{{url('/reposo_medico/registrar')}}" method="post" id="registrar">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
	      		<div class="row">
	      			<div class="input-field col s6">
		          		<input id="nombre" type="text" disabled>
		        	</div>
		        	<div class="input-field col s6">
		          		<input id="apellido" type="text" disabled>
		        	</div>
	      		</div>

	      		<div class="row">
	      			<div class="col s12">
	      				<h5 class="center-align">Datos del reposo</h5>
	      			</div>
	      		</div>

	      		<input type="text" id="cedula-field" name="cedula" style="display: none;">

	      		<div class="row">
	      			<div class="input-field col s6">
		          		<input id="fecha-inicio" name="fecha_inicio" type="text" class="datepicker">
		         	 	<label for="fecha-inicio">Fecha inicio</label>
		        	</div>
		        	<div class="input-field col s6">
		          		<input id="fecha-fin" name="fecha_fin" type="text" class="datepicker">
		         	 	<label for="fecha-fin">Fecha de culminacion</label>
		        	</div>
	      		</div>
	      		<div class="row">
	      			<div class="input-field col s12">
		      			<textarea id="motivo" name="motivo" class="materialize-textarea"></textarea>
		          		<label for="motivo">Motivo</label>
		          	</div>
	      		</div>
	      		<div class="row">
	      			<p class="center-align">
	      				<a class="waves-effect waves-light btn" onclick="registrar()"><i class="material-icons right">send</i>registrar</a>
	      			</p>
	      		</div>
			</form>

    	</div>
    	<div class="modal-footer">
      		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
    	</div>
  	</div>

  	<script type="text/javascript">
  		
  		function limpiar(){
  			$('#nombre').val('');
  			$('#apellido').val('');
  			$('#cedula-field').val('');
  		}

  		function registrar(){
  			document.getElementById('registrar').submit();
  		}

		function find(){
			var cedula = document.getElementById('cedula').value;

			$.ajax({
	            type: "GET",
	            url: "{{url('/reposo_medico/buscar_personal/')}}/"+cedula,
	            success: function(empleado) {
	            	
	            	if(empleado.length > 0){
	            		limpiar();
		            	$('#nombre').val(empleado[0].nombre);
		            	$('#apellido').val(empleado[0].apellido);
		            	$('#cedula-field').val(empleado[0].cedula);
	            	}
	            	else{
	            		swal({
					          title: "Error",
					          text: "¡Empleado no encontrado!",
					          type: "warning"
					        });
	            	}
	                
	            }
	        });

		}

		function eliminar(id){
	        swal({
	          title: "¿Estás seguro?",
	          text: "¡Eliminarás el reposo médico!",
	          type: "warning",
	          showCancelButton: true,
	          cancelButtonText: "No", 
	          confirmButtonColor: "#DD6B55",
	          confirmButtonText: "Si!",
	          closeOnConfirm: false
	        },
	        function(){
	          document.getElementById('form_eliminar'+id).submit();
	        });
      }

  	</script>

@endsection