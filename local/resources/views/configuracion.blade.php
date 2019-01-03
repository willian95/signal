@extends('partials.layout')

@section('content')

	@if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
    @endif

	<div class="container">
		
		<form method="post" action="{{url('/editar_panel_control')}}" id="form">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<h3 class="center-align"><strong>Configuración</strong></h3>

			@foreach($datos as $dato)
				<div class="row">
	        		<div class="input-field col s12">
	          			<input id="nombre" type="text" class="validate" name="nombre" value="{{$dato->nombre_gerente_rh}}">
	          			<label for="nombre">Nombre del Gerente de RRHH</label>
	        		</div>
	        	</div>

	        	<div class="row">
	        		<div class="input-field col s12">
	          			<input id="nombre_coordinador" type="text" class="validate" name="nombre_coordinador_rh" value="{{$dato->nombre_coordinador_rh}}">
	          			<label for="nombre">Nombre del Coordinador de RRHH</label>
	        		</div>
	        	</div>

	        	<div class="row">
	        		<div class="input-field col s12">
	          			<input id="gerente_finanzas" type="text" class="validate" name="nombre_gerente_finanzas" value="{{$dato->nombre_gerente_finanzas}}">
	          			<label for="nombre">Nombre del Gerente de Finanzas</label>
	        		</div>
	        	</div>

	        	<div class="row">
	        		<div class="input-field col s12">
	          			<input id="sueldo" type="text" class="validate" name="sueldo" value="{{$dato->sueldo_minimo}}">
	          			<label for="sueldo">Sueldo mínimo</label>
	        		</div>
	      		</div>
	      
	       		<div class="row"> 
	        		<div class="input-field col s12">
	          			<input id="transporte_largo" type="text" class="validate" name="transporte_largo" value="{{$dato->prima_transporte_lejos}}">
	          			<label for="transporte_largo">Prima de transporte fuera del municipio</label>
	        		</div>
	        	</div>

	        	<div class="row"> 
	        		<div class="input-field col s12">
	          			<input id="transporte_corto" type="text" class="validate" name="transporte_corto" value="{{$dato->prima_transporte_cerca}}">
	          			<label for="transporte_corto">Prima de transporte dentro del municipio</label>
	        		</div>
	        	</div>

	        	<div class="row"> 
	        		<div class="input-field col s12">
	          			<input id="transporte_sueldo_minimo" type="text" class="validate" name="transporte_sueldo_minimo" value="{{$dato->prima_transporte_cerca_minimo}}">
	          			<label for="transporte_corto">Prima de transporte dentro del municipio con sueldo minimo</label>
	        		</div>
	        	</div>

	        	<div class="row"> 
	        		<div class="input-field col s12">
	          			<input id="tarjeta_alimentacion" type="text" class="validate" name="tarjeta_alimentacion" value="{{$dato->alimentacion}}">
	          			<label for="tarjeta_alimentacion">Monto Tarjeta Alimentación</label>
	        		</div>
	        	</div>

	        @endforeach

	        <p class="center-align"><a onclick="verificar()" class="waves-effect waves-light btn"><i class="material-icons right">send</i>editar</a></p>

		</form>

	</div>

	    <script>
        function verificar(){
            
            var nombre = document.getElementById('nombre').value;
            var nombre_coordinador = document.getElementById('nombre_coordinador').value;
            var gerente_coordinador = document.getElementById('gerente_finanzas').value;
            var sueldo = document.getElementById('sueldo').value;
            var transporte_largo = document.getElementById('transporte_largo').value;
            var transporte_corto = document.getElementById('transporte_corto').value;
            var transporte_sueldo_minimo = document.getElementById('transporte_sueldo_minimo').value;
            
            if(!nombre || !sueldo || !transporte_largo || !transporte_corto || !nombre_coordinador || !gerente_coordinador || !transporte_sueldo_minimo)
            {
                Materialize.toast('Todos los campos son obligatorios', 4000);
            }
            
            else{
                document.getElementById('form').submit();
            }
            
        }

    </script>


@endsection