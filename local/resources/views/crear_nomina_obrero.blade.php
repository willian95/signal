@extends('partials.layout')

@section('content')

	<div class="container">
		
		<table class="bordered center-align">
        	<thead>
          <tr>
              <th data-field="nombre">Nombre</th>
              <th data-field="apellido">Apellido</th>
              <th data-field="cedula">Cedula</th>
          </tr>
        </thead>

        <tbody>

		@foreach($obreros as $obrero)
          <tr>
            <td>
            	{{$obrero->nombre}}
            </td>
            <td>
            	{{$obrero->apellido}}
            </td>
            <td>
            	{{$obrero->cedula}}
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


    </script>


@endsection