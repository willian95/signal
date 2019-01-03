@extends('partials.layout')

@section('content')

<div class="container">

	<h4>Datos laborales</h4>

	<div class="row">
    <form class="col s12" action="{{url('/insertar_laborales/'.$id)}}" method="post" id="form_laborales">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    	<div class="input-field col s12">
    		<select name="cargo" id="cargo">
      			<option value="" disabled selected>Elija una opción</option>
      			@foreach($cargos as $cargo)
      				<option value="{{$cargo->id}}">{{$cargo->descripcion}}</option>
      			@endforeach
    		</select>
    		<label>Cargo</label>
    	</div>

      <div class="input-field col s12">
        <select name="departamento" id="departamento">
            <option value="" disabled selected>Elija una opción</option>
            @foreach($departamentos as $departamento)
              <option value="{{$departamento->id}}">{{$departamento->nombre_departamento}}</option>
            @endforeach
        </select>
        <label>Departamento</label>
      </div>

    	<div class="row">
        	<div class="input-field col s12">
          		<input type="date" name="fecha_ingreso" class="datepicker" id="fecha_ingreso">
          		<label for="fecha_nacimiento">Fecha de ingreso</label>
        	</div>
    	</div>

    	<div class="input-field col s12">
    		<select name="tipo_trabajador" id="tipo_trabajador">
      			<option value="" disabled selected>Elija una opción</option>
      			<option value="obrero">obrero</option>
      			<option value="empleado">empleado</option>
    		</select>
    		<label>tipo de trabajador</label>
    	</div>

			<div class="input-field col s12">
    		<div class="row">
					<div class="col l4">
      			<input type="checkbox" id="pensionado" name="pensionado"/>
      			<label for="pensionado">Pensionado</label>
					</div>
					<div class="col l4">
      			<input type="checkbox" id="jubilado" name="jubilado"/>
      			<label for="jubilado">Jubilado</label>
					</div>
				</div>
    	</div>

    	<div class="input-field col s12">
    		<select name="tipo_contrato" id="tipo_contrato">
      			<option value="" disabled selected>Elija una opción</option>
      			<option value="fijo">Fijo</option>
      			<option value="contratado">Contratado</option>
    		</select>
    		<label>tipo de contrato</label>
    	</div>

    	<div class="input-field col s12">
        <select name="sueldo" id="sueldo">
            <option value="" disabled selected>Elija una opción</option>
            @foreach($salarios as $salario)

              <option value="{{$salario->id}}">{{$salario->descripcion}} - {{$salario->sueldo}}</option>

            @endforeach
        </select>
        <label>Sueldo</label>
      </div>

      <div class="input-field col s12">
    		<select name="tipo_cuenta" id="tipo_cuenta">
      			<option value="" disabled selected>Elija una opción</option>
      			<option value="ahorro">Ahorro</option>
      			<option value="corriente">Corriente</option>
    		</select>
    		<label>tipo de cuenta</label>
    	</div>

    	<div class="row">
        <div class="input-field col s12">
          <input id="numero_cuenta" type="text" name="numero_cuenta" class="validate">
          <label for="numero_cuenta">Numero de cuenta</label>
        </div>
      </div>

      <div class="row">
        	<div class="input-field col s12">
          		<input type="date" name="culminacion_contrato" class="datepicker" id="culminacion_contrato">
          		<label for="culminacion_contrato">Culminacion de contrato</label>
        	</div>
    	</div>
    	
    	<div class="row">
        <div class="input-field col s12">
          <input id="fideicomiso" type="text" name="fideicomiso" class="validate">
          <label for="fideicomiso">Monto de fideicomiso</label>
        </div>
      </div>

    	<p class="center-align">
    		<a class="waves-effect waves-light btn" type="button" onclick="verificar()"><i class="material-icons right">send</i>Siguiente</a>
    	</p>

    </form>
  </div>


</div>

<script type="text/javascript">
	
	function verificar(){

		var cargo                =  document.getElementById('cargo').value;
		var departamento         =  document.getElementById('departamento').value;
		var fecha_ingreso        =  document.getElementById('fecha_ingreso').value;
		var tipo_trabajador      =  document.getElementById('tipo_trabajador').value;
		var tipo_contrato        =  document.getElementById('tipo_contrato').value;
		var sueldo               =  document.getElementById('sueldo').value;
		var tipo_cuenta          =  document.getElementById('tipo_cuenta').value;
		var numero_cuenta        =  document.getElementById('numero_cuenta').value;
		var culminacion_contrato =  document.getElementById('culminacion_contrato').value;
    var fideicomiso          =  document.getElementById('fideicomiso').value;

		if(!cargo || !fecha_ingreso || !tipo_trabajador || !tipo_contrato || !sueldo || !tipo_cuenta || !numero_cuenta || !fideicomiso || !departamento){
			Materialize.toast('Todos los campos son obligatorios', 4000);
		}

		else{
			if(tipo_contrato == 'contratado' && !culminacion_contrato){
				Materialize.toast('Se requiere la fecha de culminacion', 4000);
			}

			else{
			   document.getElementById('form_laborales').submit();
			}
		}
	}



</script>


@endsection