@extends('partials.layout')

@section('content')
    
    
     @if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif
    
    @if($busquedas_nominas)
      <h3>Nominas</h3>
      <table class="bordered center-align">
        <thead>
          <tr>
              <th data-field="id">Numero</th>
              <th data-field="tipo">Tipo</th>
              <th data-field="fecha_inicio">Fecha inicio</th>
              <th data-field="fecha_fin">Fecha fin</th>
              <th data-field="fecha">Asignaciones</th>
              <th data-field="fecha">Deducciones</th>
              <th data-field="fecha">Patronal</th>
          </tr>
        </thead>

        <tbody>
        @foreach($busquedas_nominas as $nomina)
          <tr>
            <td>
            	{{$nomina->id}}
            </td>
            <td>
            	{{$nomina->tipo}}
            </td>
            <td>
            	{{$nomina->fecha_inicio}}
            </td>
            <td>
            	{{$nomina->fecha_fin}}
            </td>
            <td>
            	{{round($nomina->asignaciones, 2)}}
            </td>
            <td>
            	{{round($nomina->deducciones, 2)}}
            </td>
            <td>
            	{{round($nomina->patronal, 2)}}
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>

    @endif


    @if($busquedas_empleados)
        <h3>Empleados</h3>
		<table class="bordered center-align">
        	<thead>
          <tr>
              <th data-field="numero">Cedula</th>
              <th data-field="tipo">Nombres</th>
              <th data-field="fecha_inicio">Apellidos</th>
              <th data-field="Cargo">Cargo</th>
              <th class="center-align" data-field="acciones">Acciones</th>
          </tr>
        </thead>

        <tbody>

		@foreach($busquedas_empleados as $busqueda_empleado)
          <tr>
            <td>
            	{{$busqueda_empleado->cedula}}
            </td>
            <td>
            	{{$busqueda_empleado->nombre}}
            </td>
            <td>
            	{{$busqueda_empleado->apellido}}
            </td>
            <td>
            	{{$busqueda_empleado->descripcion}}
            </td>
      
            <td class="center-align">
            	<a class="waves-effect btn tooltipped" data-position="top" data-delay="50" data-tooltip="Datos personales" href="{{url('/editar_empleado_form/'.$busqueda_empleado->id)}}"><i class="material-icons">edit</i></a>
            	<a class="waves-effect btn tooltipped" data-position="top" data-delay="50" data-tooltip="Datos laborales" style="background-color: #c0ca33;" href="{{url('/editar_laborales_form/'.$busqueda_empleado->id)}}"><i class="material-icons">edit</i></a>
            	    @if($busqueda_empleado->status == 'activo')
            		<a class="waves-effect btn tooltipped modal-trigger" href="#modal_eliminar{{$busqueda_empleado->id}}" data-position="bottom" data-delay="50" data-tooltip="Desactivar" style="background-color: #e53935;"><i class="material-icons">delete</i>
            		</a>
            		  <a class="waves-effect btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Carga familiar" href="{{url('/carga_familiar/'.$busqueda_empleado->id)}}"><i class="material-icons">face</i></a>
            		@else
            		<a class="waves-effect btn tooltipped" data-position="bottom" data-delay="50" data-tooltip="Activar" onclick="activar({{$busqueda_empleado->id}})"><i class="material-icons">assignment_ind</i>
            			<form method="post" style="display:none;" action="{{url('/activar_empleado/'.$busqueda_empleado->id)}}" >
            			    <input type="hidden" name="_token" value="{{ csrf_token() }}">
            			</form>
            		</a>
            		
            		@endif
            </td>
          </tr>

          <!-- Modal desactivar -->
                    <div id="modal_eliminar{{$busqueda_empleado->id}}" class="modal" style="max-height: 800px;">
                      <div class="modal-content" style="height: 800px;">
                        <div class="container">
                          <h4>Fecha de desactivacion</h4>
                        <form action="{{url('/eliminar_empleado/'.$busqueda_empleado->id)}}" method="post" id="form_eliminar{{$busqueda_empleado->id}}">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          <div class="input-field col s12">
                            <input id="fecha_desactivacion" type="text" class="datepicker" name="fecha_egreso">
                            <label for="fecha_desactivacion">Fecha de desactivacion</label>
                          </div>
                          <p class="center-align">
                            <button class="btn" onclick="eliminar({{$busqueda_empleado->id}})">
                              Desactivar
                            </button>
                          </p>
                        </form>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
                      </div>
                    </div>
                  <!---->

		@endforeach

		</tbody>
      </table>
    @endif
	<script>
      function eliminar(id){
        document.getElementById('form_eliminar'+id).submit();
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