@extends('partials.layout')


@section('content')
    
    @if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif
<div>
           <form method="post" action="{{url('/insertar_concepto_nomina_empleado/'.$id_nomina.'/empleado/'.$id_empleado)}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="row">
                       <div class="input-field col s6">
                        <select name="concepto">
                          <option value="" disabled selected>Elija un concepto</option>
                          @foreach($conceptos_nuevos as $concepto_nuevo)
                            <option value="{{$concepto_nuevo->id}}">{{$concepto_nuevo->descripcion}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="input-field col s6">
                            <input id="ref" type="text" class="validate" name="referencia">
                            <label for="ref">Referencia</label>
                      </div>
               </div>
               <div class="row">
                   <p class="center-align"><button class="waves-effect btn" type="submit">Asignar</button></p>
               </div>
           </form>

          <h3 class="center-align">{{$nombre}}</h3>
          
          <p class="center-align"><a href="{{url('/editar_nomina_empleado_form/'.$id_nomina)}}" class="waves-effect waves-light btn red"><i class="material-icons left">reply</i>atras</a></p>

    <table class="bordered">
        	<thead>
          <tr>
              <th data-field="concepto">Concepto</th>
              <th data-field="apellido">Referencia</th>
              <th data-field="cedula">Asignacion</th>
              <th data-field="cedula">Deduccion</th>
              <th data-field="acciones">Patronal</th>
              <th data-field="acciones">Acciones</th>
          </tr>
        </thead>

        <tbody>

		@foreach($conceptos as $concepto)
          <tr>
            <td>
            	{{$concepto->descripcion}}
            </td>
            <td>
                <form method="post" action="{{url('/editar_concepto_empleado/'.$concepto->concepto_id.'/id/'.$concepto->id)}}" id="form_editar{{$concepto->id}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
            	    <input type="text" name="referencia" class="validate" value="{{round($concepto->referencia, 2)}}">
                </form>
            </td>
            <td>
                @if($concepto->tipo == 'asignacion')
                    {{number_format($concepto->monto, 2, ',', '.')}}
                @endif
            </td>
            <td>
                @if($concepto->tipo == 'deduccion')
                    {{number_format($concepto->monto, 2, ',', '.')}}
                @endif
            </td>
            <td>
                @if($concepto->tipo == 'patronal')
                    {{number_format($concepto->monto, 2, ',', '.')}}
                @endif
            </td>
            
            <td>
              @if($concepto->concepto_id == 5440 || $concepto->concepto_id == 5450 || $concepto->concepto_id == 5460 || $concepto->concepto_id == 5454 || $concepto->concepto_id == 5471 || $concepto->concepto_id == 5473 || $concepto->concepto_id == 30111 || $concepto->concepto_id == 30121 || $concepto->concepto_id == 20111 || $concepto->concepto_id == 20121 || $concepto->concepto_id == 20134 || $concepto->concepto_id == 20136 || $concepto->concepto_id == 20131 || $concepto->concepto_id == 30131 || $concepto->concepto_id == 30172 || $concepto->concepto_id == 20222)
               <a class="waves-effect btn" style="visibility:hidden;"><i class="material-icons">edit</i></a>
               @else
               <a class="waves-effect btn" onclick="editar({{$concepto->id}})"><i class="material-icons">edit</i>
                  <form method="post" style="display:none;" action="{{url('/editar_concepto_empleado/'.$concepto->id.'/nomina/'.$id_nomina)}}" id="form_editar{{$concepto->id}}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  </form>
                </a>
               
            @endif
               
                <a class="waves-effect btn" style="background-color: #e53935;" onclick="eliminar({{$concepto->id}})"><i class="material-icons">delete</i>
                  <form method="post" style="display:none;" action="{{url('/eliminar_personal_concepto_nomina_empleado/'.$concepto->id.'/nomina/'.$id_nomina)}}" id="form_eliminar{{$concepto->id}}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  </form>
                </a>
            </td>
           
          </tr>
           @endforeach
        
        <tr>
        <td>
            
        </td>
        <td>
            
        </td>
        <td>
            <strong>{{number_format($asignacion, 2, ',', '.')}}</strong>
        </td>
        <td>
            <strong>{{number_format($deduccion, 2, ',', '.')}}</strong>
        </td>
        <td>
            <strong>{{number_format($patronal, 2)}}</strong>
        </td>            
        </tr>

		</tbody>
      </table>
</div>
      
          <script>

    function eliminar(id){
        swal({
          title: "¿Estás seguro?",
          text: "¡Eliminarás al concepto de la nomina!",
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
              
      function editar(id){
        swal({
          title: "¿Estás seguro?",
          text: "¡Editarás el concepto la nomina!",
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "No", 
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Si!",
          closeOnConfirm: false
        },
        function(){
          document.getElementById('form_editar'+id).submit();
        });
      }


    </script>


@endsection