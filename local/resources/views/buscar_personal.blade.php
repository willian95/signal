@extends('partials.layout')


@section('content')

    <table class="bordered center-align">
        <thead>
          <tr>
              <th data-field="id">Cedula</th>
              <th data-field="tipo">Nombre</th>
              <th data-field="tipo">Apellido</th>
              <th data-field="fecha_inicio">Cargo</th>
              <th data-field="fecha_fin">Acciones</th>
          </tr>
        </thead>

        <tbody>
        @foreach($busquedas as $empleado)
          <tr>
            <td>
            	{{$empleado->cedula}}
            </td>
            <td>
            	{{$empleado->nombre}}
            </td>
            <td>
            	{{$empleado->apellido}}
            </td>
            <td>
            	{{$empleado->descripcion}}
            </td>
            <td>
            	<a class="waves-effect btn" href="{{url('/editar_empleado_form/'.$empleado->id)}}"><i class="material-icons">edit</i></a>
            	<a class="waves-effect btn" style="background-color: #c0ca33;" href="{{url('/editar_laborales_form/'.$empleado->id)}}"><i class="material-icons">edit</i></a>
            	    @if($empleado->status == 'activo')
            		<a class="waves-effect btn" style="background-color: #e53935;" onclick="eliminar({{$empleado->id}})"><i class="material-icons">delete</i>
            			<form method="post" style="display:none;" action="{{url('/eliminar_empleado/'.$empleado->id)}}" id="form_eliminar{{$empleado->id}}">
            			    <input type="hidden" name="_token" value="{{ csrf_token() }}">
            			</form>
            		</a>
            		  <a class="waves-effect btn" href="{{url('/carga_familiar/'.$empleado->id)}}"><i class="material-icons">group_add</i></a>
            		@else
            		<a class="waves-effect btn" onclick="activar({{$empleado->id}})"><i class="material-icons">assignment_ind</i>
            			<form method="post" style="display:none;" action="{{url('/activar_empleado/'.$empleado->id)}}" id="form_eliminar{{$empleado->id}}">
            			    <input type="hidden" name="_token" value="{{ csrf_token() }}">
            			</form>
            		</a>
            		
            		@endif
            </td>

          </tr>
        @endforeach
        </tbody>
      </table>

                <script>

    function eliminar(id){
        swal({
          title: "¿Estás seguro?",
          text: "¡Eliminarás al empleado!",
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
                    
    function activar(id){
        swal({
          title: "¿Estás seguro?",
          text: "¡Activarás al empleado!",
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


    </script>


@endsection