@extends('partials.layout')

@section('content')

    <div class="container">
        
        <h4>Crear nomina empleado</h4>
        
        <div class="row">
        <form class="col s12"  method="post" action="{{url('/crear_nomina_empleado')}}" id="form">
              
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
                <select id="quincena" name="quincena">
                  <option value="" disabled selected>Elija una opción</option>
                  <option value="1">Quincena 1</option>
                  <option value="2">Quincena 2</option>
                </select>
                <label>Quincena</label>
              </div>

      <div class="input-field col s12">
                <select id="semanas" name="semanas">
                  <option value="" disabled selected>Elija una opción</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
                <label>Cantidad de semanas del mes</label>
              </div>
              
              <p class="center-align"><a type="button" onclick="verificar()" class="waves-effect waves-light btn"><i class="material-icons right">send</i>crear</a></p>
              

        </form>
    
  </div>
        
        
    </div>
    
    <script>
        function verificar(){
            var fecha_inicio = document.getElementById('fecha_inicio').value;
            var fecha_fin = document.getElementById('fecha_fin').value;
            var quincena = document.getElementById('quincena').value;
            var semanas = document.getElementById('semanas').value;
            
            if(!fecha_inicio || !fecha_fin || !quincena || !semanas)
            {
                Materialize.toast('Todos los campos son obligatorios', 4000);
            }
            
            else{
                document.getElementById('form').submit();
            }
            
        }

    </script>


@endsection