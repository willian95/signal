@extends('partials.layout')

  <?php 
      $numero = 0;
    ?>

@section('content')

    <p class="center-align">
      <a class="btn-floating btn-large waves-effect waves-light green modal-trigger" data-target="modal_nuevo_empleado"><i class="material-icons">add</i></a>
    </p>

    <!-- Modal -->
    <div id="modal_nuevo_empleado" class="modal modal-fixed-footer" style="height: 800px;">
      <div class="modal-content">
        <h4>Ingresar empleado</h4>
        <form method="post" action="{{url('/ingresar_personal_empleado_flotante/'.$id_nomina)}}" id="flotante_form">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="input-field col s12">
            <select id="empleado_id" name="empleado_id">
              <option value="" disabled selected>Elija un empleado</option>
              @foreach($empleados as $empleado)
                <option value="{{$empleado->empleados_id}}">{{$empleado->nombre}} {{$empleado->apellido}}</option>
              @endforeach
            </select>
            <label>Empleados</label>
          </div>
        </form>
        <p class="center-align">
          <a class="waves-effect waves-light btn" onclick="verificar_flotante()"><i class="material-icons right">send</i>registrar</a>
        </p>
      </div>
      <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      </div>
    </div>

    <!---->
    
    <div class="container">
       
       <table class="bordered center-align">
        	<thead>
          <tr>
              <th data-field="nombre">Numero</th>
              <th data-field="nombre">Nombre</th>
              <th data-field="apellido">Apellido</th>
              <th data-field="cedula">Cedula</th>
              <th data-field="acciones">Acciones</th>
          </tr>
        </thead>

        <tbody>

		@foreach($nominas as $nomina)
      <?php 
      $numero++;
    ?>

          <tr>
            <td>
              {{$numero}}
            </td>
            <td>
            	{{$nomina->nombre}}
            </td>
            <td>
            	{{$nomina->apellido}}
            </td>
            <td>
            	{{$nomina->cedula}}
            </td>
            <td>
            	<a class="waves-effect btn" href="{{url('/editar_personal_empleado_nomina/'.$nomina->id_empleado.'/nomina/'.$id_nomina)}}"><i class="material-icons">edit</i></a>
                <a class="waves-effect btn red" onclick="eliminar({{$nomina->id_empleado}})"><i class="material-icons">delete</i>
                  <form method="post" style="display:none;" action="{{url('/eliminar_personal_obrero_nomina/'.$nomina->id_empleado.'/nomina/'.$id_nomina)}}" id="form_eliminar{{$nomina->id_empleado}}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  </form>
                </a>
                
            </td>
            <td>
            	
            </td>
          </tr>

		@endforeach

		</tbody>
      </table>
        
    </div>
    
    <script>

    function eliminar(id){
        swal({
          title: "¿Estás seguro?",
          text: "¡Eliminarás al empleado de la nomina!",
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "No", 
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Si!",
          closeOnConfirm: false
        },
        function(){
          document.getElementById('form_eliminar'+id).submit();
        });
      }

      function verificar_flotante(){
        var id = document.getElementById('empleado_id').value
        if(!id){
          Materialize.toast('Debe seleccionar un trabajador', 4000)
        }
        else{
          document.getElementById('flotante_form').submit();
        }
      }


    </script>

@endsection