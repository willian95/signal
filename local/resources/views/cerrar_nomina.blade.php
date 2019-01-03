@extends('partials.layout')

@section('content')

@if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif

	<div class="container">

		<form id="cierre" action="{{url('/cerrar_nomina')}}" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="row">
		        <div class="input-field col s12">
		          	<input id="n_nomina" type="email" class="validate" name="n_nomina">
		          	<label for="n_nomina">N° nomina</label>
		        </div>
		    </div>

		    <p class="center-align"><a class="waves-effect waves-light btn" onclick="cerrar()"><i class="material-icons right">close</i>Cerrar nomina</a></p>

		</form>

	</div>

	<script type="text/javascript">
		
		function cerrar(){
			swal({
		          title: "¿Estás seguro?",
		          text: "¡Cerraras la nomina!",
		          type: "warning",
		          showCancelButton: true,
		          cancelButtonText: "No", 
		          confirmButtonColor: "#DD6B55",
		          confirmButtonText: "Si!",
		          closeOnConfirm: false
		        },
		        function(){
		          document.getElementById('cierre').submit();
		        });
		}

	</script>

@endsection