@extends('partials.layout')


@section('content')

   <div class="container">
       <ul class="collection">
           @foreach($resultados as $resultado)
           <li class="collection-item"><div><span><strong>Numero: </strong></span>{{$resultado->id}} <span style="margin-left: 40px;"><strong>Monto: </strong></span> {{$resultado->monto}}<a href="{{url('/show_nomina/'.$resultado->id)}}" class="secondary-content"><i class="material-icons">send</i></a></div></li>
           @endforeach    
      </ul>
   </div>

@endsection