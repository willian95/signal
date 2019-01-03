@extends('partials.layout')

@section('content')

	@if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif

	 <p class="center-align"><a class="btn-floating btn-large waves-effect waves-light modal-trigger" data-target="modal_sueldos"><i class="material-icons">add</i></a></p>

	 	@if(count($sueldos) <= 0)
		<ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">No hay sueldos disponibles</h5></li>
        </ul>
	@endif


	@if(count($sueldos) > 0)

		<table class="bordered">
        <thead>
          <tr>
              <th>Descripción</th>
              <th>Sueldo</th>
              <th>Acciones</th>
          </tr>
        </thead>

        <tbody>
        @foreach($sueldos as $sueldo)
          <tr>
          	<form action="{{url('/editar_salario/'.$sueldo->id)}}" id="form_editar{{$sueldo->id}}" method="post">
          		<input type="hidden" name="_token" value="{{ csrf_token() }}">
            	<td><input type="text" value="{{$sueldo->descripcion}}" name="descripcion"></td>
            	<td><input type="text" value="{{$sueldo->sueldo}}" name="sueldo"></td>
            </form>
            	<td class="center-align">
            		<a onclick="editar({{$sueldo->id}})" class="btn waves-effect"><i class="material-icons">edit</i></a>
            	</td>
          </tr>
        @endforeach
        </tbody>
      </table>


	@endif

	<!--Modal-->

		<div class="modal modal-fixed-footer" id="modal_sueldos">
    
		    <div class="modal-content">
		        <h4 class="center-align">Salarios</h4>
		        	<div class="row">
		    <form class="col s12" action="{{url('/insertar_salario')}}" id="form" method="post">
		        <input type="hidden" name="_token" value="{{ csrf_token() }}">
		      <div class="row">
		        
		        <div class="input-field col s12">
		          <input id="descripcion" type="text" class="validate" name="descripcion">
		          <label for="nombre">Descripción</label>
		        </div>
		        
		        </div>

		        <div class="row">
		        <div class="input-field col s12">
		          <input type="text" class="validate" id="sueldo" name="sueldo">
		          <label for="edad">Sueldo</label>
		        </div>
		      </div>
		      
		    <p><a class="btn waves-effect" onclick="verificar()">Registrar<i class="material-icons right">send</i></a></p>

		    </form>
		  </div>
		        
		    </div>
		    
		    <div class="modal-footer">
		        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Cerrar</a>
		    </div>
		    
		</div>

	<!---->

	<script type="text/javascript">
		

    function verificar(){
        
        var descripcion = document.getElementById('descripcion').value;
        var sueldo = document.getElementById('sueldo').value;
        
        if(!descripcion || !sueldo){
            Materialize.toast('Todos los campos son obligatorios', 4000);
        }
        
        else{
            document.getElementById('form').submit();
        }
        
    }

     function editar(id){
        swal({
          title: "¿Estás seguro?",
          text: "¡Editaras el salario!",
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