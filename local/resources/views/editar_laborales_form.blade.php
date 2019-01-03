@extends('partials.layout')

@section('content')

@foreach($laborales as $labor)

<div class="container">

	<h4>Datos laborales</h4>

	<div class="row">
    <form class="col s12" action="{{url('/editar_laborales/'.$labor->id)}}" method="post" id="form_laborales">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    	
    	<div class="input-field col s12">
    		<select name="cargo" id="cargo" onchange="verificar_cargo()">
      			<option value="" disabled selected>Elija una opción</option>
      			@foreach($cargos as $cargo)
      				<option value="{{$cargo->id}}" @if($labor->cargo_id == $cargo->id) {{'selected'}} @endif>{{$cargo->descripcion}}</option>
      			@endforeach
    		</select>
    		<label>Cargo</label>
    	</div>
      
      <div class="input-field col s12" style="display: none;" id="modificar_cargo" >
          <input id="fecha_asignacion" type="text" class="datepicker" name="fecha_asignacion">
          <label for="fecha_asignacion">Fecha de asignacion de cargo</label>
        </div>

       <div class="input-field col s12">
        <select name="departamento" id="cargo">
            <option value="" disabled selected>Elija una opción</option>
            @foreach($departamentos as $departamento)
              <option value="{{$departamento->id}}" @if($labor->departamento_id == $departamento->id) {{'selected'}} @endif>{{$departamento->nombre_departamento}}</option>
            @endforeach
        </select>
        <label>Departamento</label>
      </div>

    	<div class="row">
        	<div class="input-field col s12">
          		<input type="date" name="fecha_ingreso" id="fecha_ingreso">
          		<label for="fecha_nacimiento">Fecha de ingreso</label>
        	</div>
    	</div>

			@if($labor->fecha_egreso)
				<div class="row">
						<div class="input-field col s12">
								<input type="date" name="fecha_egreso" id="fecha_egreso" disabled>
								<label for="fecha_egreso">Fecha de egreso</label>
						</div>
				</div>
			@endif

    	<div class="input-field col s12">
    		<select name="tipo_trabajador" id="tipo_trabajador">
      			<option value="" disabled>Elija una opción</option>
      			<option value="obrero" @if($labor->tipo_trabajador == 'obrero') {{'selected'}} @endif>obrero</option>
      			<option value="empleado" @if($labor->tipo_trabajador == 'empleado') {{'selected'}} @endif>empleado</option>
    		</select>
    		<label>tipo de trabajador</label>
    	</div>

    	<div class="input-field col s12">
    		<select name="tipo_contrato" id="tipo_contrato">
      			<option value="" disabled>Elija una opción</option>
      			<option value="fijo" @if($labor->tipo_contrato == 'fijo') {{'selected'}} @endif >Fijo</option>
      			<option value="contratado" @if($labor->tipo_contrato == 'contratado') {{'selected'}} @endif>Contratado</option>
    		</select>
    		<label>tipo de contrato</label>
    	</div>

			<div class="input-field col l12">
    		<div class="row">
					<div class="col l4">
      			<input type="checkbox" id="pensionado" name="pensionado" @if($labor->pensionado == 1) {{"checked"}} @endif/>
      			<label for="pensionado">Pensionado</label>
					</div>
					<div class="col l4">
      			<input type="checkbox" id="pasante" name="pasante" @if($labor->pasante == 1) {{"checked"}} @endif/>
      			<label for="pasante">Pasante</label>
					</div>
					<div class="col l4">
      			<input type="checkbox" id="jubilado" name="jubilado" @if($labor->jubilado == 1) {{"checked"}} @endif/>
      			<label for="jubilado">Jubilado</label>
					</div>
				</div>
    	</div>

      <div class="input-field col s12">
        <select name="sueldo" id="sueldo">
            <option value="" disabled>Elija una opción</option>
            @foreach($salarios as $salario)

              <option value="{{$salario->id}}" @if($labor->sueldo_id == $salario->id) {{'selected'}} @endif>{{$salario->descripcion}} - {{$salario->sueldo}}</option>

            @endforeach
        </select>
        <label>Sueldo</label>
      </div>

      <div class="input-field col s12">
    		<select name="tipo_cuenta" id="tipo_cuenta">
      			<option value="" disabled>Elija una opción</option>
      			<option value="ahorro" @if($labor->tipo_cuenta == 'ahorro') {{'selected'}} @endif>Ahorro</option>
      			<option value="corriente" @if($labor->tipo_cuenta == 'corriente') {{'selected'}} @endif >Corriente</option>
    		</select>
    		<label>tipo de cuenta</label>
    	</div>

    	<div class="row">
        <div class="input-field col s12">
          <input id="numero_cuenta" type="text" name="numero_cuenta" class="validate" value="{{$labor->numero_cuenta}}">
          <label for="numero_cuenta">Numero de cuenta</label>
        </div>
      </div>

      <div class="row">
        	<div class="input-field col s12">
          		<input type="date" name="culminacion_contrato" id="culminacion_contrato">
          		<label for="culminacion_contrato">Culminacion de contrato</label>
        	</div>
    	</div>
    	
    	<div class="row">
        <div class="input-field col s12">
          <input id="fideicomiso" type="text" name="fideicomiso" class="validate" value="{{$labor->fideicomiso}}">
          <label for="fideicomiso">Monto de fideicomiso</label>
        </div>
      </div>

    	<p class="center-align">
    		<a class="waves-effect waves-light btn" type="button" onclick="verificar()"><i class="material-icons right">send</i>Siguiente</a>
    	</p>

    </form>

    </form>

		@if($labor->pasante == 1)
			<p class="center-align">
    		<a class="waves-effect waves-light btn red" type="button" onclick="eliminar_pasante()"><i class="material-icons right">delete</i>Siguiente</a>
    	</p>
		@endif

  </div>

</div>

<!-- Modal Fecha de cambio-->

<div id="fecha_cargo" class="modal" style="max-height: 1000px;">
    <div class="modal-content" style="height: 1000px;">
      <h4>Fecha de asignacion de cargo</h4>
      <div class="container">
        <div class="input-field col s12">
          <input id="fecha_asignacion" type="text" class="datepicker">
          <label for="fecha_asignacion">Monto de fideicomiso</label>
        </div>
        <p class="center-align">
          <button type="button" class="btn">Registrar fecha</button>
        </p>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
    </div>
  </div>

<!-- Fin modal cambio -->

<script type="text/javascript">
   var cambio = 0;

   var $input = $('#fecha_ingreso').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 200, // Creates a dropdown of 15 years to control year   
        format: 'dd/mm/yyyy' 
    })
    var date = '{{$labor->fecha_ingreso}}'
    var picker = $input.pickadate('picker');
    picker.set('select', date, { format: 'dd/mm/yyyy' });

    var date = '{{$labor->fecha_egreso}}';

    if(date != ''){
      var $input = $('#fecha_egreso').pickadate({
          selectMonths: true, // Creates a dropdown to control month
          selectYears: 200, // Creates a dropdown of 15 years to control year   
          format: 'dd/mm/yyyy' 
      })
        
      var picker = $input.pickadate('picker');
      picker.set('select', date, { format: 'dd/mm/yyyy' });
    }

    var date = '{{$labor->fecha_culminacion}}';
		
    if(date != ''){
      var $input = $('#culminacion_contrato').pickadate({
          selectMonths: true, // Creates a dropdown to control month
          selectYears: 200, // Creates a dropdown of 15 years to control year   
          format: 'dd/mm/yyyy' 
      })
      var picker = $input.pickadate('picker');
      picker.set('select', date, { format: 'dd/mm/yyyy' });
    }
    
    console.log(date);
    


	function verificar(){

		var cargo                =  document.getElementById('cargo').value;
		var fecha_ingreso        =  document.getElementById('fecha_ingreso').value;
		var tipo_trabajador      =  document.getElementById('tipo_trabajador').value;
		var tipo_contrato        =  document.getElementById('tipo_contrato').value;
		var sueldo               =  document.getElementById('sueldo').value;
		var tipo_cuenta          =  document.getElementById('tipo_cuenta').value;
		var numero_cuenta        =  document.getElementById('numero_cuenta').value;
		var culminacion_contrato =  document.getElementById('culminacion_contrato').value;
    var fideicomiso          =  document.getElementById('fideicomiso').value;
    var fecha_cambio         =  document.getElementById('fecha_asignacion').value;

		if(!cargo || !fecha_ingreso || !tipo_trabajador || !tipo_contrato || !sueldo || !tipo_cuenta || !numero_cuenta || !fideicomiso || (!fecha_cambio && cambio == 1)){
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

  function verificar_cargo(){
    cambio = 1;
    $('#modificar_cargo').css('display', 'block');
  }

</script>

@endforeach

@endsection