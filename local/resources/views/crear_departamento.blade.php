@extends('partials.layout')

@section('content')

	@if($departamentos == null)
		<ul class="collection">
          	<li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">No hay departamentos en existencia</h5></li>
        </ul>
	@endif

	<p class="center-align"><a class="btn waves-effect modal-trigger" data-target="modal_departamento">Nuevo departamento<i class="material-icons right">add</i></a></p>

	<div class="row">

		@if($departamentos != null)

			@foreach($departamentos as $departamento)

				<div class="col l3">
                	<div class="card horizontal">
                  		<div class="card-stacked">
                    		<div class="card-content">
                    			<form method="post" action="{{url('/editar_departamento/'.$departamento->id)}}" id="editar{{$departamento->id}}">
                    				<input type="hidden" name="_token" value="{{ csrf_token() }}">
	                    			<div class="row">
					                    <div class="input-field col s12">
					                      <input id="nombre" name="nombre" type="text" class="validate" value="{{$departamento->nombre_departamento}}" style="font-size: 18px;">
					                    </div>
					                </div>
				                </form>
                    		</div>
                    		<div class="card-action">
                      			<div class="row">
                      				<a onclick="eliminar({{$departamento->id}})" class="btn red"><i class="material-icons">delete</i></a>
                          			<a onclick="editar({{$departamento->id}})" class="btn"><i class="material-icons">edit</i></a>
                      			</div>
                    		</div>
                      		<form method="post" action="{{url('/borrar_departamento/'.$departamento->id)}}" id="eliminar{{$departamento->id}}" style="display: none;">
                          		<input type="hidden" name="_token" value="{{ csrf_token() }}">
                      		</form>
                  		</div>
                	</div>
              	</div>

			@endforeach

		@endif

	</div>


	<div class="modal modal-fixed-footer" id="modal_departamento" style="height: 310px;">
    	<div class="modal-content">
            <h4 class="center-align">Registrar departamento</h4>
            <form action="{{url('/crear_departamento')}}" method="post" id="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="input-field col s12">
                      <input id="nombre" name="nombre" type="text" class="validate">
                      <label for="nombre">Nombre del departamento</label>
                    </div>
                </div>
                  
                <p class="center-align"><a class="btn" onclick="verificar()">Registrar<i class="material-icons right">send</i></a></p>
                
            </form>
        
        </div>
        <div class="modal-footer">
      		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Cerrar</a>
    	</div>
    </div>

    <script type="text/javascript">
    	
    	function verificar(){
    		var nombre = document.getElementById('nombre').value;
    		if(!nombre){
    			Materialize.toast('Nombre requerido', 4000);
    		}
    		else{
    			document.getElementById('form').submit();
    		}
    	}

    	function eliminar(id){
    		swal({
		          title: "¿Estás seguro?",
		          text: "¡Eliminarás el departamento!",
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

    	function editar(id){
    		var nombre = document.getElementById('nombre').value;
    		if(!nombre){
    			Materialize.toast('Nombre requerido', 4000);
    		}
    		else{
    			swal({
		          title: "¿Estás seguro?",
		          text: "¡Editarás el departamento!",
		          type: "warning",
		          showCancelButton: true,
		          cancelButtonText: "No", 
		          confirmButtonColor: "#DD6B55",
		          confirmButtonText: "Si!",
		          closeOnConfirm: false
		        },
		        function(){
		          document.getElementById('editar'+id).submit();
		        });
    		}

    	}

    </script>

@endsection