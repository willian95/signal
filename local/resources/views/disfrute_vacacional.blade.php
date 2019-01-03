@extends('partials.layout')

@section('content')
	
	@if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif

     <h3 class="center-align">Disfrute Vacacional</h3>

	<p class="center-align">
		<a class="btn-floating btn-large waves-effect waves-light green modal-trigger" href="#modal-disfrute"><i class="material-icons">add</i></a>
	</p>

	<div class="container">
		<table class="bordered">
			<thead>
	          	<tr>
	            	<th>Cedula</th>
	              	<th>Nombre y apelldio</th>
	              	<th>Fecha de inicio</th>
	              	<th>Fecha de culminacion</th>
	              	<th>Accion</th>
	          	</tr>
	        </thead>
	        <tbody>
	        	@foreach($disfrute_vacacional as $disfrute)
					<tr>
						<td>{{$disfrute->cedula}}</td>
						<td>{{$disfrute->nombre}} {{$disfrute->apellido}}</td>
						<td>{{$disfrute->fecha_inicio}}</td>
						<td>{{$disfrute->fecha_fin}}</td>
						<td>
							<div class="row">
								<p class="center-align">
									<a onclick="eliminar({{$disfrute->id}})" class="waves-effect waves-light btn red"><i class="material-icons">delete</i></a>
									<form id="form_eliminar{{$disfrute->id}}" style="display: none;" action="{{url('/disfrute_vacacional/eliminar/').'/'.$disfrute->id}}" method="post">
				      					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				      				</form>
								</p>
							</div>
						</td>
					</tr>
	        	@endforeach
	        </tbody>
		</table>
	</div>
	
	<div id="modal-disfrute" class="modal">
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
			<form action="{{url('/disfrute_vacacional/registrar')}}" method="post" id="registrar">
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
	      				<h5 class="center-align">Datos del disfrute vacacional</h5>
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
	      			<p class="center-align">
	      				<a class="waves-effect waves-light btn" onclick="registrar()"><i class="material-icons right">send</i>registrar</a>
	      			</p>
	      		</div>
			</form>

    	</div>
  	</div>

  	<script type="text/javascript">
  		function limpiar(){
  			$('#nombre').val('');
  			$('#apellido').val('');
  			$('#cedula-field').val('');
  		}

		function find(){
			var cedula = document.getElementById('cedula').value;

			$.ajax({
	            type: "GET",
	            url: "{{url('/disfrute_vacacional/buscar/')}}/"+cedula,
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

		function registrar(){
  			document.getElementById('registrar').submit();
  		}

		function eliminar(id){
	        swal({
	          title: "¿Estás seguro?",
	          text: "¡Eliminarás el disfrute vacacional!",
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