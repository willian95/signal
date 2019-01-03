@extends('partials.layout')


@section('content')
    
       @if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif

    <div class="container">
        
        <p class="center-align"><a class="btn waves-effect modal-trigger" data-target="modal_usuario">Nuevo usuario <i class="material-icons right">send</i></a></p>
        
        @foreach($usuarios as $usuario)
        
            <div class="col s12 m7">
                <div class="card horizontal">
                  <div class="card-image">
                    <img src="{{url('img/user.ico')}}" style="height: 150px; with: 150px;">
                  </div>
                  <div class="card-stacked">
                    <div class="card-content">
                      <p>{{$usuario->nombre}}</p>
                      <p>{{$usuario->descripcion}}</p>
                    </div>
                    <div class="card-action">
                      <div class="row">
                          <a class="btn" style="background-color: #e53935;" onclick="eliminar({{$usuario->user_id}})"><i class="material-icons">delete</i></a>
                          <form id="usuario{{$usuario->user_id}}" action="{{url('/eliminar_usuario/'.$usuario->user_id)}}" method="get"></form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        
        @endforeach
        
    </div>
    
    <div class="modal modal-fixed-footer" id="modal_usuario">
        <div class="modal-content">
            <h4 class="center-align">Registrar usuario</h4>
            <form action="{{url('/registrar_usuario')}}" method="post" id="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="input-field col s12">
                      <input id="nombre" name="nombre" type="text" class="validate">
                      <label for="nombre">Nombre</label>
                    </div>
                  </div>
                  
                   <div class="row">
                    <div class="input-field col s12">
                      <input id="correo" name="correo" type="email" class="validate">
                      <label for="correo">Email</label>
                    </div>
                  </div>
                  
                  <div class="row">
                       <div class="input-field col s12">
                            <select name="role" id="role">
                              <option value="" disabled selected>Elige un rol</option>
                              @foreach($roles as $role)
                              
                                  <option value="{{$role->id}}">{{$role->descripcion}}</option>
                              
                              @endforeach
                              
                            </select>
                            <label>Rol</label>
                          </div>
                  </div>
                  
                  <div class="row">
                    <div class="input-field col s12">
                      <input id="clave" type="password" class="validate" name="clave">
                      <label for="clave">Clave</label>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="input-field col s12">
                      <input id="clave2" type="password" class="validate">
                      <label for="clave2">Repetir clave</label>
                    </div>
                  </div>
                  
                  <p class="center-align"><a class="btn" onclick="verificar()">Registrar<i class="material-icons right">send</i></a></p>
                
            </form>
        
        </div>
                    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Cerrar</a>
    </div>
    </div>
    
    <script>

        function verificar(){
            
            var nombre = document.getElementById('nombre').value;
            var email = document.getElementById('correo').value;
            var role = document.getElementById('role').value;
            var clave = document.getElementById('clave').value;
            var clave2 = document.getElementById('clave2').value;
            
            if(!nombre || !email || !role || !clave || !clave2){
                
                Materialize.toast('Todos los campos son obligatorios', 4000);
                
            }
            
            else{
                
                if(clave == clave2){
                    document.getElementById('form').submit();
                }
                
                else{
                    Materialize.toast('Claves no coinciden', 4000);
                }
                
            }
            
        }
        
        function eliminar(id){
            swal({
          title: "¿Estás seguro?",
          text: "¡Eliminaras al usuario!",
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "No", 
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Si!",
          closeOnConfirm: false
        },
        function(){
          document.getElementById('usuario'+id).submit();
        });
        }
        
    </script>

@endsection