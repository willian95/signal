<!DOCTYPE html>
<html>
    <head>
       @include('partials.head')
    </head>
    
    <body>
        
        <img class="responsive-img" src="{{url('img/welcome.jpg')}}" 
style="
	width:100%;
	height:100%; 
    top:0;
    left:0;
    position:fixed;
    z-index: -1;" 
>
       
          @if(session('alert'))
        <ul class="collection">
          <li class="collection-item teal darken-3"><h5 class="center-align" style="color: white;">{{session('alert')}}</h5></li>
        </ul>
      @endif
           
           <div class="row">
               
               <div class="col l6 s12">
                   <div class="row">
                   		<div class="col s12">
			        <div class="card-panel z-depth-5 v-align animated slideInUp">

			        	<h4 class="center-align">Consulta tu recibo de pago</h4>
			          <form method="post" action="{{url('/verificar_nomina')}}">
			          <input type="hidden" name="_token" value="{{ csrf_token() }}">
			          	<div class="row">
			        		<div class="input-field col s12">
			          			<input id="email" type="text" class="validate" name="cedula">
			          			<label for="email">Cedula</label>
			        		</div>
			      		</div>

			          <p class="center-align"><button class="waves-effect waves-light btn" type="submit"><i class="material-icons right">send</i>Iniciar</button></p>

			          </form>
			        </div>
			      </div>
                   </div>

                   <div class="row">
                   		<div class="col s12">
			        <div class="card-panel z-depth-5 v-align animated slideInUp">

			        	<h4 class="center-align">Genera tu constancia de trabajo</h4>
			          <form method="post" action="{{url('/generarConstancia/2')}}">
			          <input type="hidden" name="_token" value="{{ csrf_token() }}">
			          	<div class="row">
			        		<div class="input-field col s12">
			          			<input id="cedula2" type="text" class="validate" name="cedula">
			          			<label for="cedula2">Cedula</label>
			        		</div>
			      		</div>

			          <p class="center-align"><button class="waves-effect waves-light btn" type="submit"><i class="material-icons right">send</i>Iniciar</button></p>

			          </form>
			        </div>
			      </div>
                   </div>
               </div>
               
               <div class="col l6 s12">
                   
                   <div class="col s12">
        <div class="card-panel z-depth-5 v-align animated slideInUp">

        	<h4 class="center-align">Administrador</h4>
          <form method="post" action="{{url('/handlelogin')}}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          	<div class="row">
        		<div class="input-field col s12">
          			<input id="email" type="email" class="validate" name="email">
          			<label for="email">Correo</label>
        		</div>
      		</div>
          	<div class="row">
        		<div class="input-field col s12">
          			<input id="password" type="password" class="validate" name="password">
          			<label for="password">Clave</label>
        		</div>
      		</div>

          <p class="center-align"><button class="waves-effect waves-light btn" type="submit"><i class="material-icons right">send</i>Iniciar</button></p>

          </form>
        </div>
      </div>
                   
                   
               </div>
               
           </div>
           
           
        
        
    </body>
    
</html>