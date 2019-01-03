@extends('partials.layout')

@section('content')

	@if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif

	<div class="container">
		
	<h3 class="center-align">Vacaciones</h3>

		<form action="{{url('/buscar')}}" method="post" id="form_buscar">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="row">
		        <div class="input-field col s12">
		          <input id="cedula" type="text" class="validate" name="cedula">
		          <label for="cedula">Cedula</label>
		        </div>
	      	</div>

	      	<p class="center-align">
	      		<a class="waves-effect waves-light btn" onclick="verificar()"><i class="material-icons right">search</i>buscar</a>
	      	</p>

		</form>

	</div>

	@if(isset($datos))

		<form action="{{url('/crear_vacaciones')}}" method="post" id="form_crear">
			
			@foreach($datos as $dato)
				
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="row">
		        <div class="input-field col s6">
		        	<input disabled id="nombre" type="text" class="validate" value="{{$dato->nombre}}" style="color: black;">
		          <input id="nombre" type="text" class="validate" name="nombre" value="{{$dato->nombre}}" style="color: black; display: none;">
		          <label for="nombre" style="color: black;">Nombre</label>
		        </div>
		        <div class="input-field col s6">
		        	<input disabled id="apellido" type="text" class="validate" value="{{$dato->apellido}}" style="color: black;">
		          <input  id="apellido" type="text" class="validate" name="apellido" value="{{$dato->apellido}}" style="color: black; display: none;">
		          <label for="apellido" style="color: black;">Apellido</label>
		        </div>
	      	</div>

	      	<div class="row">
		        <div class="input-field col s6">
		        	<input disabled id="cargo" type="text" class="validate" value="{{$dato->descripcion}}" style="color: black;">
		          <input id="cargo" type="text" class="validate" name="cargo" value="{{$dato->descripcion}}" style="color: black; display: none;">
		          <label for="cargo" style="color: black;">Cargo</label>
		        </div>
		        <div class="input-field col s6">
		        	<input disabled id="cedula" type="text" class="validate" value="{{$dato->cedula}}" style="color: black;">
		          <input  id="cedula" type="text" class="validate" name="cedula" value="{{$dato->cedula}}" style="color: black; display: none;">
		          <label for="cedula" style="color: black;">Cedula</label>
		        </div>
	      	</div>

	      	<div class="row">
		        <div class="input-field col s6">
		        	<input disabled id="fecha_ingreso" type="text" class="validate" value="{{$dato->fecha_ingreso}}" style="color: black;">
		          <input  id="fecha_ingreso" type="text" class="validate" name="fecha_ingreso" value="{{$dato->fecha_ingreso}}" style="color: black; display: none;">
		          <label for="fecha_ingreso" style="color: black;">Fecha Ingreso</label>
		        </div>
		        <div class="input-field col s6">
		        	<input disabled id="servicio" type="text" class="validate" value="{{$servicio}}" style="color: black;">
		          <input id="servicio" type="text" class="validate" name="servicio" value="{{$servicio}}" style="color: black; display: none;">
		          <label for="servicio" style="color: black;">Años de servicio</label>
		        </div>
	      	</div>
			
			

			<h3 class="center-align">Campos</h3>

	      	<div class="row">
	      		<div class="input-field col s6">
	      			<input type="date" name="fecha_inicio" class="datepicker" id="fecha_inicio">
		          	<label for="dias">Fecha de inicio</label>
		        </div>
		        <div class="input-field col s6">
	      			<input type="date" name="fecha_final" class="datepicker" id="fecha_final">
		          	<label for="dias">Fecha de final</label>
		        </div>
	      	</div>

	      	<div class="row">
	      		<div class="input-field col s6">
	      			<select name="dias" id="dias">
		          		<option value="" disabled selected>Elija una opción</option>
		          		<option value="30">30 días</option>
		          		<option value="15">15 días</option>
		          	</select>
		          	<label for="dias">Días</label>
		        </div>

		        <div class="input-field col s6">
	      			<?php
	      				$bono_vacacional = 0;

	      				if($servicio > 1){
	      					$bono_vacacional = 15;
	      				}

	      				$bono_vacacional = $bono_vacacional + $servicio-1;

	      				if($bono_vacacional > 30){
	      					$bono_vacacional = 30;
	      				}

	      			?>
	      			<input disabled id="bono_vacacional" type="text" class="validate" value="{{$bono_vacacional}}" style="color: black;">	
	      			<input id="bono_vacacional" type="text" class="validate" name="bono_vacacional" value="{{$bono_vacacional}}" style="color: black; display: none;">
		          	<label for="dias" style="color: black;">Bono vacacional</label>
		        </div>
	      	</div>

			<p class="center-align">
	      		<a class="waves-effect waves-light btn" onclick="verificar2()"><i class="material-icons right">send</i>registrar</a>
	      	</p>

	      	@endforeach
		
		</form>

	@endif


	<script type="text/javascript">
		
		function verificar(){
			var cedula = document.getElementById('cedula').value;

			if(!cedula){
				Materialize.toast('Cedula necesaria', 4000);
			}

			else{
				document.getElementById('form_buscar').submit();
			}

		}

		function verificar2(){
			var fecha_inicio = document.getElementById('fecha_inicio').value;
			var fecha_final = document.getElementById('fecha_final').value;
			var dias = document.getElementById('dias').value;
			var bono_vacacional = document.getElementById('bono_vacacional').value;

			if(!fecha_inicio || !fecha_final || !dias || !bono_vacacional ){
				Materialize.toast('Todos los campos son obligatorios', 4000);
			}

			else{
				document.getElementById('form_crear').submit();
			}

		}

	</script>

@endsection