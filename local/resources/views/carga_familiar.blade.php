@extends('partials.layout')

@section('content')
    
    @if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif

	<div class="container">

         <p class="center-align"><a class="btn-floating btn-large waves-effect waves-light modal-trigger" data-target="modal_familiar"><i class="material-icons">add</i></a></p>
     
		 <table class="bordered center-align">
        <thead>
          <tr>
              <th data-field="nombre">Nombres</th>
              <th data-field="fecha_nacimiento">Fecha de nacimiento</th>
              <th data-field="edad">Edad</th>
              <th data-field="carga">Carga familiar</th>
              <th data-field="grado">Grado</th>
              <th data-field="acciones">Acciones</th>
          </tr>
        </thead>

        <tbody>
         @foreach($cargas as $carga)
          <tr>
            <td>{{$carga->nombres}}</td>
            
            <td>{{$carga->fecha}}</td>

            <td>
                <?php
                    $fecha_actual = getdate();
                    $annio_actual =  $fecha_actual['year'];
                    $mes_actual = $fecha_actual['mon'];
                    $dia_actual = $fecha_actual['mday'];
                    
                    $annio_cumple = substr($carga->fecha, 6);
                    $mes_cumple = substr($carga->fecha, 3, 2);
                    $dia_cumple = substr($carga->fecha, 0, 2);
                    
                    $annio = $annio_actual - $annio_cumple;
                    
                    if($mes_actual < $mes_cumple && $annio > 0)
                    {
                        $annio--;
                    }
                    
                    else if($mes_actual == $mes_cumple){
                        if($dia_actual < $dia_cumple){
                            $annio--;
                        }
                        
                    }

                    else if($annio <=0){
                        $annio = $mes_actual - $mes_cumple;
                        if($annio <= 0){
                            $annio = $annio * -1;
                            $annio = $annio." meses ";
                        }
                    }

                    echo $annio;
                ?>
            </td>
            
            <td>{{$carga->carga}}</td>
                
            <td>
                <form id="editar{{$carga->id}}" method="post" action="{{url('/editar_familiar/'.$carga->id)}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="text" value="{{$carga->grado}}" name="grado" id="grado"></td>
                </form>
            <td>
                <div class="row">
                    <a class="btn red" onclick="eliminar({{$carga->id}})"><i class="material-icons">delete</i></a>
                    <a class="btn green" onclick="editar({{$carga->id}})"><i class="material-icons">edit</i></a>
                </div>
            </td>
                <form id="eliminar{{$carga->id}}" method="post" action="{{url('/eliminar_familiar/'.$carga->id)}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            
          </tr>
          @endforeach
        </tbody>
      </table>
		
	</div>

<div class="modal modal-fixed-footer" id="modal_familiar">
    
    <div class="modal-content">
        <h4 class="center-align">Familiar</h4>
        	<div class="row">
    <form class="col s12" action="{{url('/registrar_familiar/'.$id_personal)}}" id="form" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="row">
        
        <div class="input-field col s12">
          <input id="nombre" type="text" class="validate" name="nombre">
          <label for="nombre">Nombres</label>
        </div>
        
        </div>

        <div class="row">
        <div class="input-field col s12">
          <input type="date" class="datepicker" id="fecha" name="fecha">
          <label for="edad">Fecha de nacimiento</label>
        </div>
      </div>

      <div class="input-field col s12">
          <input id="grado" type="text" class="validate" name="grado">
          <label for="grado">Grado de estudio</label>
        </div>
      
       <div class="row"> 
        <div class="input-field col s12">
          <input id="carga" type="text" class="validate" name="carga">
          <label for="carga">Carga familiar</label>
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

<script>
    
    function verificar(){
        
        var nombre = document.getElementById('nombre').value;
        var edad = document.getElementById('fecha').value;
        var carga = document.getElementById('carga').value;
        
        if(!nombre || !edad || !carga){
            Materialize.toast('Todos los campos son obligatorios', 4000);
        }
        
        else{
            document.getElementById('form').submit();
        }
        
    }

    function editar(id){
        
        var grado = document.getElementById('grado').value;
        
        if(!grado){
            Materialize.toast('Todos los campos son obligatorios', 4000);
        }
        
        else{
            document.getElementById('editar'+id).submit();
        }
        
    }
    
    function eliminar(id){
        swal({
          title: "¿Estás seguro?",
          text: "¡Eliminarás al familiar!",
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