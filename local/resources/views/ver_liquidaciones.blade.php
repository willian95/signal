@extends('partials.layout')

@section('content')

    @if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif

    <table class="bordered">
        <thead>
          <tr>
              <th>Cedula</th>
              <th>Nombre</th>
              <th>Fecha Egreso</th>
              <th>Fecha Registro</th>
              <th>Acciones</th>
          </tr>
        </thead>

        <tbody>
        @foreach($liquidaciones as $liquidacion)
          <tr>
            <td>{{$liquidacion->cedula}}</td>
            <td>{{$liquidacion->nombre}} {{$liquidacion->apellido}}</td>
            <td>{{$liquidacion->fecha_egreso}}</td>
            <td>{{$liquidacion->fecha_registro}}</td>
            <td>
            	<a class="btn waves-effect" href="{{url('/verificar_liquidacion/'.$liquidacion->id)}}" target="_blank"><i class="material-icons">search</i></a>
            	<a class="btn waves-effect red" onclick="eliminar({{$liquidacion->id}})"><i class="material-icons">delete</i></a>
              <form method="post" action="{{url('/eliminar_liquidacion/'.$liquidacion->id)}}" id="eliminar{{$liquidacion->id}}">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>

    <script type="text/javascript">
        function eliminar(id){
        swal({
          title: "¿Estás seguro?",
          text: "¡Eliminarás la liquidación!",
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "No", 
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Si!",
          closeOnConfirm: false
        },
        function(){
          document.getElementById('eliminar'+id).submit();
        });
      }
      </script>

@endsection