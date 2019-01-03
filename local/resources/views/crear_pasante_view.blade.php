@extends('partials.layout')

@section('content')

    <?php 
        $cargos = DB::table('cargos')->orderBy('descripcion', 'asc')->get();
    	$salarios = DB::table('salarios')->get();
		$departamentos = DB::table('departamentos')->get();
    ?>

    @if (count($errors) > 0)
     <ul class="collection">
            @foreach ($errors->all() as $error)
                <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{$error}}</h5></li>

            @endforeach
      </ul>
@endif

   @if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif

<div class="container">
    
    <h4>Ingresar Pasante</h4>

    <form class="col s12" method="post" action="{{url('/crear_pasante')}}" id="form_ingresar">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="row">
        <div class="input-field col s12">
          <input id="nombre" name="nombre" type="text" class="validate">
          <label for="nombre">Nombres</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="apellido" name="apellido" type="text" class="validate">
          <label for="apellido">Apellidos</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="cedula" name="cedula" type="text" class="validate">
          <label for="cedula">Cedula</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="rif" name="rif" type="text" class="validate">
          <label for="rif">R.I.F.</label>
        </div>
      </div>
      <div class="input-field col s12">
    <select name="sexo" id="sexo">
      <option value="" disabled selected>Elija una opción</option>
      <option value="m">Masculino</option>
      <option value="f">Femenino</option>
    </select>
    <label>Sexo</label>
    </div>
    <div class="row">
        <div class="input-field col s12">
          <input type="date" name="fecha_nacimiento" class="datepicker" id="fecha_nacimiento">
          <label for="fecha_nacimiento">Fecha de nacimiento</label>
        </div>
    </div>
    <div class="input-field col s12">
    <select name="estado_civil" id="estado_civil">
      <option value="" disabled selected>Elija una opción</option>
      <option value="casado">Casado</option>
      <option value="soltero">Soltero</option>
      <option value="viudo">Viudo</option>
      <option value="divorciado">Divorciado</option>
    </select>
    <label>Estado civil</label>
    </div>
    <div class="row">
        <div class="input-field col s12">
          <input id="direccion" name="direccion" type="text" class="validate">
          <label for="direccion">Dirección</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="parroquia" name="parroquia" type="text" class="validate">
          <label for="parroquia">Parroquia</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="municipio" name="municipio" type="text" class="validate">
          <label for="municipio">Municipio</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="talla_camisa" name="talla_camisa" type="text" class="validate">
          <label for="talla_camisa">Talla camisa</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="talla_pantalon" name="talla_pantalon" type="text" class="validate">
          <label for="talla_pantalon">Talla pantalon</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="talla_zapatos" name="talla_zapatos" type="text" class="validate">
          <label for="talla_zapatos">Talla zapatos</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="talla_braga" name="talla_braga" type="text" class="validate">
          <label for="talla_braga">Talla braga (Solo infrastructura)</label>
        </div>
      </div>
        <div class="row">
        <div class="input-field col s12">
          <input id="correo" name="correo" type="email" class="validate">
          <label for="correo">Correo Electrónico</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="tlf_movil" name="tlf_movil" type="text" class="validate">
          <label for="tlf_movil">Teléfono móvil</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="tlf_fijo" name="tlf_fijo" type="text" class="validate">
          <label for="tlf_fijo">Teléfono fijo</label>
        </div>
      </div>

      <h3 class="center-align"> Datos Laborales </h3>

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
        <p class="center-align"><a type="button" onclick="verificar()" class="waves-effect waves-light btn"><i class="material-icons right">send</i>siguiente</a></p>
        <br>
        <br>
        <br>
        <br>
      </form>

  </div>

<script type="text/javascript">
  
  function verificar(){
    
    var nombre           = document.getElementById('nombre').value;
    var apellido         = document.getElementById('apellido').value;
    var cedula           = document.getElementById('cedula').value;
    var rif              = document.getElementById('rif').value;
    var sexo             = document.getElementById('sexo').value;
    var fecha_nacimiento = document.getElementById('fecha_nacimiento').value;
    var estado_civil     = document.getElementById('estado_civil').value;
    var direccion        = document.getElementById('direccion').value;
    var parroquia        = document.getElementById('parroquia').value;
    var correo           = document.getElementById('correo').value;
    var tlf_movil        = document.getElementById('tlf_movil').value;
    var municipio        = document.getElementById('municipio').value;
    var departamento     = document.getElementById('departamento').value;
    var fecha_ingreso    = document.getElementById('fecha_ingreso').value;

    if(!nombre || !apellido || !cedula || !rif || !sexo || !fecha_nacimiento || !estado_civil || !direccion || !parroquia || !correo || !tlf_movil || !municipio || !fecha_ingreso || !departamento){
      Materialize.toast('Todos los campos son obligatorios a excepción la profesión', 4000);
    }

    else{
      document.getElementById('form_ingresar').submit();
    }
  } 
</script>
@endsection