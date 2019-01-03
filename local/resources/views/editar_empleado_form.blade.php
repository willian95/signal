@extends('partials.layout')


@section('content')
@foreach($empleados as $empleado)
    <div class="container">
        <h4>Editar Empleado</h4>

    <form class="col s12" method="post" action="{{url('/editar_empleado/'.$empleado->id)}}" id="form_ingresar">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="row">
        <div class="input-field col s12">
          <input id="nombre" name="nombre" type="text" class="validate" value="{{$empleado->nombre}}">
          <label for="nombre">Nombres</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="apellido" name="apellido" type="text" class="validate" value="{{$empleado->apellido}}">
          <label for="apellido">Apellidos</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="rif" name="rif" type="text" class="validate" value="{{$empleado->rif}}">
          <label for="rif">R.I.F.</label>
        </div>
      </div>
      <div class="input-field col s12">
     <select name="sexo" id="sexo">
      <option value="" disabled>Elija una opción</option>
      <option value="m" @if($empleado->sexo == 'm') {{'selected'}} @endif >Masculino</option>
      <option value="f" @if($empleado->sexo == 'f') {{'selected'}} @endif >Femenino</option>
    </select>
    <label>Sexo</label>
    </div>
    <div class="row">
        <div class="input-field col s12">
          <input type="date" name="fecha_nacimiento" id="fecha_nacimiento">
          <label for="fecha_nacimiento">Fecha de nacimiento</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">

        <?php 
          if($empleado->profesion == 'NP'){
            $empleado->profesion = '';
          }
         ?>

          <input id="profesion" name="profesion" type="text" class="validate" value="{{$empleado->profesion}}">
          <label for="profesion">Profesión</label>
        </div>
      </div>
      <div class="input-field col s12">
    <select name="estado_civil" id="estado_civil">
      <option value="" disabled>Elija una opción</option>
      <option value="casado" @if($empleado->estado_civil == 'casado') {{'selected'}} @endif >Casado</option>
      <option value="soltero" @if($empleado->estado_civil == 'soltero') {{'selected'}} @endif>Soltero</option>
      <option value="viudo" @if($empleado->estado_civil == 'viudo') {{'selected'}} @endif>Viudo</option>
      <option value="divorciado" @if($empleado->estado_civil == 'divorciado') {{'selected'}} @endif>Divorciado</option>
    </select>
    <label>Estado civil</label>
    </div>
    <div class="row">
        <div class="input-field col s12">
          <input id="direccion" name="direccion" type="text" class="validate" value="{{$empleado->direccion}}">
          <label for="direccion">Dirección</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="parroquia" name="parroquia" type="text" class="validate" value="{{$empleado->parroquia}}">
          <label for="parroquia">Parroquia</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="municipio" name="municipio" type="text" class="validate" value="{{$empleado->municipio}}">
          <label for="municipio">Municipio</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="tlf_movil" name="tlf_movil" type="text" class="validate" value="{{$empleado->tlf_movil}}">
          <label for="tlf_movil">Teléfono móvil</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="tlf_fijo" name="tlf_fijo" type="text" class="validate" value="{{$empleado->tlf_fijo}}">
          <label for="tlf_fijo">Teléfono fijo</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="talla_camisa" name="talla_camisa" type="text" class="validate" value="{{$empleado->talla_camisa}}">
          <label for="talla_camisa">Talla camisa</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="talla_pantalon" name="talla_pantalon" type="text" class="validate" value="{{$empleado->talla_pantalon}}">
          <label for="talla_pantalon">Talla pantalon</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="talla_zapatos" name="talla_zapatos" type="text" class="validate" value="{{$empleado->talla_zapatos}}">
          <label for="talla_zapatos">Talla zapatos</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="talla_braga" name="talla_braga" type="text" class="validate" value="{{$empleado->talla_braga}}">
          <label for="talla_braga">Talla braga (Solo infrastructura)</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="correo" name="correo" type="text" class="validate" value="{{$empleado->correo}}">
          <label for="correo">Correo Electrónico</label>
        </div>
      </div>
      <div class="input-field col s12">
    <select name="tipo_carrera" id="tipo_carrera">
      <option value="" disabled>Elija una opción</option>
      <option value="tsu" @if($empleado->tipo_carrera == 'tsu') {{'selected'}} @endif>TSU</option>
      <option value="licenciados" @if($empleado->tipo_carrera == 'licenciados') {{'selected'}} @endif>Licenciados</option>
      <option value="especialista" @if($empleado->tipo_carrera == 'especialista') {{'selected'}} @endif>Especialista</option>
      <option value="magister" @if($empleado->tipo_carrera == 'magister') {{'selected'}} @endif>Magister</option>
      <option value="doctor" @if($empleado->tipo_carrera == 'doctor') {{'selected'}} @endif>Doctor</option>
      <option value="NP" @if($empleado->tipo_carrera == 'NP') {{'selected'}} @endif>No posee</option>
    </select>
    <label>Tipo de carrera</label>
    </div>
        <p class="center-align"><a type="button" onclick="verificar()" class="waves-effect waves-light btn"><i class="material-icons right">send</i>siguiente</a></p>
      </form>
    </div>
    
    <script type="text/javascript">

      var $input = $('#fecha_nacimiento').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 200, // Creates a dropdown of 15 years to control year   
        format: 'dd/mm/yyyy' 
    })
      var date = '{{$empleado->fecha_nacimiento}}'
    var picker = $input.pickadate('picker');
    picker.set('select', date, { format: 'dd/mm/yyyy' });

  
  function verificar(){
    
    var nombre           = document.getElementById('nombre').value;
    var apellido         = document.getElementById('apellido').value;
    var rif              = document.getElementById('rif').value;
    var sexo             = document.getElementById('sexo').value;
    var fecha_nacimiento = document.getElementById('fecha_nacimiento').value;
    var estado_civil     = document.getElementById('estado_civil').value;
    var direccion        = document.getElementById('direccion').value;
    var parroquia        = document.getElementById('parroquia').value;
    var tipo_carrera     = document.getElementById('tipo_carrera').value;
    var correo           = document.getElementById('correo').value;
    var talla_camisa     = document.getElementById('talla_camisa').value;
    var talla_pantalon   = document.getElementById('talla_pantalon').value;
    var talla_zapatos    = document.getElementById('talla_zapatos').value;

    if(!nombre || !apellido || !rif || !sexo || !profesion || !fecha_nacimiento || !estado_civil || !direccion || !parroquia || !tipo_carrera || !correo || !talla_camisa || !talla_pantalon || !talla_zapatos){
      Materialize.toast('Todos los campos son obligatorios', 4000);
    }

    else{
      document.getElementById('form_ingresar').submit();
    }

  }


</script>
      @endforeach

@endsection