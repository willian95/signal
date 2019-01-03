<!DOCTYPE html>
<html>
    @include('partials.head')
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
        
        <div class="container" style="background-color: white;">
            
            <table class="bordered">
        <thead>
          <tr>
              <th>Numero</th>
              <th>Fecha inicial</th>
              <th>Fecha final</th>
              <th>Verificar</th>
          </tr>
        </thead>

        <tbody>
            
            @foreach($nominas as $nomina)
                <tr>
                <td>{{$nomina->id}}</td>
                <td>{{$nomina->fecha_inicio}}</td>
                <td>{{$nomina->fecha_fin}}</td>
                <td><a class="btn" target="_blank" href="{{url('/verificar-pdf/'.$nomina->id.'/empleado/'.$id_empleado)}}">
                <i class="material-icons">send</i>
                </a>
                </td>
              </tr>
            @endforeach
        </tbody>
           
            </table>
            
        </div>
        
    </body>
</html>