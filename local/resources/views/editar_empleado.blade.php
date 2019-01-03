@extends('partials.layout')

@section('content')

  @if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif
    
<table class="bordered center-align">
        <thead>
          <tr>
              <th data-field="id" width="10%">Cedula</th>
              <th data-field="tipo" width="10%">Nombre</th>
              <th data-field="tipo" width="10%">Apellido</th>
              <th data-field="fecha_inicio" width="50%">Cargo</th>
              <th data-field="fecha_fin" width="20%">Acciones</th>
          </tr>
        </thead>

        <tbody>
        @foreach($empleados as $empleado)
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
            	<a class="waves-effect btn tooltipped" data-position="top" data-delay="50" data-tooltip="Datos personales" href="{{url('/editar_empleado_form/'.$empleado->id)}}"><i class="material-icons">edit</i></a>
            	<a class="waves-effect btn tooltipped" data-position="top" data-delay="50" data-tooltip="Datos laborales" style="background-color: #c0ca33;" href="{{url('/editar_laborales_form/'.$empleado->id)}}"><i class="material-icons">edit</i></a>
            	    @if($empleado->status == 'activo')
            		<a class="waves-effect btn tooltipped modal-trigger" href="#modaldesactivar" data-position="bottom" onclick="eliminar({{$empleado->id}})" data-delay="50" data-tooltip="Desactivar" style="background-color: #e53935;"><i class="material-icons">delete</i>
            			<form method="post" style="display:none;" action="{{url('/eliminar_empleado/'.$empleado->id)}}" id="form_eliminar{{$empleado->id}}">
            			    <input type="hidden" name="_token" value="{{ csrf_token() }}">
            			</form>
            		</a>
            		  <a class="waves-effect btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Carga familiar" href="{{url('/carga_familiar/'.$empleado->id)}}"><i class="material-icons">face</i></a>
            		@else
            		<a class="waves-effect btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar" onclick="activar({{$empleado->id}})"><i class="material-icons">assignment_ind</i>
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
                
                {!! $empleados->setPath('') !!}

                <script>

    function eliminar(id){
        swal({
          title: "¿Estás seguro?",
          text: "¡Desactiarás al empleado!",
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