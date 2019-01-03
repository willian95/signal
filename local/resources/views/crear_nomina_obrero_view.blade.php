@extends('partials.layout')


@section('content')

    <div class="container">
        
        <h4>Crear nomina operarios</h4>
        
        <div class="row">
        <form class="col s12" action="{{url('/crear_nomina_obrero')}}" method="post" id="form_enviar">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="row">
        <div class="input-field col s6">
          <input id="fecha_inicio" type="date" class="datepicker" name="fecha_inicio">
          <label for="fecha_inicio">Fecha Inicio</label>
        </div>
        
        <div class="input-field col s6">
          <input id="fecha_fin" type="date" class="datepicker" name="fecha_fin">
          <label for="fecha_fin">Fecha Fin</label>
        </div>
      </div>

      <p>
      <input type="checkbox" id="reiniciar" name="reiniciar" />
      <label for="reiniciar">Reiniciar Nomina</label>
    </p>
            
            <div class="input-field col s12">
                <select id="semana" name="semana">
                  <option value="" disabled selected>Elija una opción</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
                <label>Semana actual</label>
              </div>
             
             <div class="input-field col s12">
                <select id="semanas" name="semanas">
                  <option value="" disabled selected>Elija una opción</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
                <label>Cantidad de semanas</label>
              </div>
              
              <p class="center-align"><a type="button" onclick="verificar()" class="waves-effect waves-light btn"><i class="material-icons right">send</i>crear</a></p>
              

        </form>
    
  </div>
        
        
    </div>
    
    <script>
        function verificar(){
            
            var semanas = document.getElementById('semanas').value;
            var semana = document.getElementById('semana').value;
            var fecha_inicio = document.getElementById('fecha_inicio').value;
            var fecha_fin = document.getElementById('fecha_fin').value;
            
            if(!fecha_inicio || !fecha_fin || !semanas || !semana)
            {
                Materialize.toast('Todos los campos son obligatorios', 4000);
            }
            
            else{
                document.getElementById('form_enviar').submit();
            }
            
        }

    </script>

@endsection