@extends('partials.layout')

@section('content')

	<h3 class="center-align">Expedientes</h3>

	<div class="container">
		
		<div class="row">
			<div class="col l3">
				<div class="input-field col s12">
    				<select name="opcion" id="opcion">
			      		<option value="0" disabled selected>Elige una opción</option>
			      		<option value="cedula">Cedula</option>
			      		<option value="nombre">Nombre</option>
    				</select>
    				<label>Opción</label>
  				</div>
			</div>
			<div class="col l8">
				<div class="input-field">
		        	<input id="dato" name="dato" type="text" class="validate">
		        </div>
			</div>
			<div class="col l1">
				<button onclick="find()" style="margin-top: 15px;" class="waves-effect waves-light btn"><i class="material-icons">search</i></button>
			</div>
		</div>

		<ul class="collection with-header" id="expedientes">
        	<!--<li class="collection-item"><div>Alvin<a href="#!" class="secondary-content"><i class="material-icons">send</i></a></div></li>-->
      	</ul>
		
	</div>


	<script type="text/javascript">
		
		$(document).ready(function() {
    		$('select').material_select();
  		});

  		function limpiar(){
  			$('#expedientes').empty();
  		}

		function find(){
			var opcion = document.getElementById('opcion').value;
			var dato = document.getElementById('dato').value;

			$.ajax({
	            type: "GET",
	            url: "{{url('/encontrarExpedientes/')}}/"+opcion+"/"+dato,
	            success: function(expedientes) {
	            	
	            	limpiar();
	                for(i = 0; i < expedientes.length; i++){
	                	$('#expedientes').append('<li class="collection-item"><div>'+expedientes[i].nombre+' '+expedientes[i].apellido+'<a href="{{url("/documentos")}}/'+expedientes[i].dato_empleado_id+'" class="secondary-content"><i class="material-icons">send</i></a></div></li>')
	                }
	                
	            }
	        });

		}

	</script>

@endsection