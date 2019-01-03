@extends('partials.layout')

@section('content')

	@if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
    @endif
	
	<div class="row">
   
      	<form action="{{url('/casos_particulares')}}" method="post" id="form">
      		<input type="hidden" name="_token" value="{{ csrf_token() }}">		
			<div class="input-field col s6">
		    	<select name="empleado" id="empleado">
		      		<option value="" disabled selected>Elija un empleado</option>
		      			@foreach($empleados as $empleado)
		      				<option value="{{$empleado->id}}">{{$empleado->nombre}} {{$empleado->apellido}}</option>
		      			@endforeach
		    	</select>
		    	<label>Empleado</label>
		  	</div>

		  	<div class="input-field col s6">
		    	<select name="concepto" id="concepto">
		      		<option value="" disabled selected>Elija un concepto</option>
		      		@foreach($conceptos as $concepto)
		      			<option value="{{$concepto->id}}">{{$concepto->descripcion}}</option>
		      		@endforeach
		    	</select>
		    	<label>Concepto</label>
		  	</div>

		  	<p class="center-align"><a onclick="verificar()" class="btn waves-effect" data-target="modal_caso_particular">Nuevo caso particular<i class="material-icons right">add</i></a></p>

		</form>

	</div>

	<ul class="collection with-header">
		@foreach($casos_particulares as $caso_particular)

			<li class="collection-item"><div>{{$caso_particular->nombre}} {{$caso_particular->apellido}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>{{$caso_particular->descripcion}}</strong><a href="#!" onclick="eliminar({{$caso_particular->id}})" class="secondary-content"><i class="material-icons">delete</i></a></div></li>
			<form method="post" action="{{url('/eliminar_particular/'.$caso_particular->id)}}" id="eliminar{{$caso_particular->id}}" style="display: none;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>

		@endforeach
	</ul>

	<script type="text/javascript">
		
		function verificar(){
			var empleado = document.getElementById('empleado').value;
			var concepto = document.getElementById('concepto').value;

			if(!empleado || !concepto){
				Materialize.toast('Todos los campos son obligatorios', 4000);
			}

			else{
				document.getElementById('form').submit();
			}

		}

		function eliminar(id){

			swal({
	          title: "¿Estás seguro?",
	          text: "¡Editarás el caso particular!",
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