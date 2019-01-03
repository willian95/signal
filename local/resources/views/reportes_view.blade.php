@extends('partials.layout')

@section('content')

    <div class="container">
        
        <h4>Reportes</h4>
        
        <div class="row">
        <form class="col s12" method="post" action="{{url('/reporte')}}" id="form_enviar">
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
              
              
              <div class="input-field col s12">
                <select id="tipo" name="tipo">
                  <option value="" disabled selected>Elija una opción</option>
                  <option value="empleado">Empleado</option>
                  <option value="obrero">Obrero</option>
                </select>
                <label>Tipo de nomina</label>
              </div>
              
              <p class="center-align"><a type="button" onclick="verificar()" class="waves-effect waves-light btn"><i class="material-icons right">search</i>buscar</a></p>
              

        </form>
    
  </div>
        
        
    </div>
    
    <script>
        function verificar(){
            var fecha_inicio = document.getElementById('fecha_inicio').value;
            var fecha_fin = document.getElementById('fecha_fin').value;
            var tipo = document.getElementById('tipo').value;
            
            if(!fecha_inicio || !fecha_fin || !tipo)
            {
                Materialize.toast('Todos los campos son obligatorios', 4000);
            }
            
            else{
                document.getElementById('form_enviar').submit();
            }
            
        }

    </script>


@endsection