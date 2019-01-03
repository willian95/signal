@extends('partials.layout')

@section('content')

		<p class="center" style="margin-top: 50px;"><a class="waves-effect waves-light btn modal-trigger" href="#modal_retirados"><i class="material-icons right">send</i>Retirados</a></p>
		<p class="center"><a class="waves-effect waves-light btn modal-trigger" href="#modal_actuales"><i class="material-icons right">send</i>Actuales</a></p>


		<div id="modal_retirados" class="modal modal-fixed-footer">
			<div class="modal-content">
				<h4>Retirados</h4>
				<form class="col s12" id="formulario1" action="{{url('/generarConstancia/1')}}" method="post">
    				<input type="hidden" name="_token" value="{{ csrf_token() }}">
    				<div class="row">
			        	<div class="input-field col s12">
			          		<input id="cedula" type="text" class="validate" name="cedula">
			          		<label for="cedula">Cedula</label>
			        	</div>
			        	<p class="center-align">
			        		<a class="waves-effect waves-light btn" onclick="verificar()"><i class="material-icons right">send</i>generar constancia</a>
			        	</p>
			    	</div>
    			</form>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
			</div>
		</div>

		<div id="modal_actuales" class="modal modal-fixed-footer">
			<div class="modal-content">
				<h4>Actuales</h4>
				<form class="col s12" id="formulario2" action="{{url('/generarConstancia/2')}}" method="post">
    				<input type="hidden" name="_token" value="{{ csrf_token() }}">
    				<div class="row">
			        	<div class="input-field col s12">
			          		<input id="cedula2" type="text" class="validate" name="cedula">
			          		<label for="cedula2">Cedula</label>
			        	</div>
			        	<p class="center-align">
			        		<a class="waves-effect waves-light btn" onclick="verificar_actuales()"><i class="material-icons right">send</i>generar constancia</a>
			        	</p>
			    	</div>
    			</form>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
			</div>
		</div>


	</div>

	<script type="text/javascript">

		function verificar(){
			var cedula = document.getElementById('cedula').value;

			if(!cedula){
				Materialize.toast('El campo cédula no puede estar vacío', 4000);
			}

			else{
				document.getElementById('formulario1').submit();
			}

		}

		function verificar_actuales(){
			var cedula = document.getElementById('cedula2').value;

			if(!cedula){
				Materialize.toast('El campo cédula no puede estar vacío', 4000);
			}

			else{
				document.getElementById('formulario2').submit();
			}

		}

	</script>

@endsection
