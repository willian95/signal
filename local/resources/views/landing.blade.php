@extends('partials.layout')


@section('content')



<table class="bordered center-align">
        <thead>
          <tr>
              <th data-field="id">Id</th>
              <th data-field="numero">Numero</th>
              <th data-field="tipo">Tipo</th>
              <th data-field="fecha_inicio">Fecha inicio</th>
              <th data-field="fecha_fin">Fecha fin</th>
              <th data-field="fecha">Asignaciones</th>
              <th data-field="fecha">Deducciones</th>
              <th data-field="fecha">Patronal</th>
          </tr>
        </thead>

        <tbody>
        @foreach($nominas as $nomina)
          <tr>
            <td>
              {{$nomina->id}}
            </td>
            <td>
            	{{$nomina->n_nomina}}
            </td>
            <td>
            	{{$nomina->tipo}}
            </td>
            <td>
            	{{$nomina->fecha_inicio}}
            </td>
            <td>
            	{{$nomina->fecha_fin}}
            </td>
            <td>
            	{{number_format(round($nomina->asignaciones, 2), 2, ',', '.')}}
            </td>
            <td>
            	{{number_format(round($nomina->deducciones, 2), 2, ',', '.')}}
            </td>
            <td>
            	{{number_format(round($nomina->patronal, 2), 2, ',', '.')}}
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
      
      {!! $nominas->setPath('') !!}
      
@if(count($cumples) > 0)
<div class="fixed-action-btn">
    <a class="btn-floating btn-large red modal-trigger" href="#modal_cumple">
      <i class="large material-icons">cake</i>
    </a>
</div>
 @endif
 
   <div id="modal_cumple" class="modal">
    <div class="modal-content" style="background-color: #ffcc80;">
     <ul class="collection">
      @foreach($cumples as $cumple)
           <?php
            $fecha_actual = getdate();
    		$annio_actual =  $fecha_actual['year'];
    		$mes_actual = $fecha_actual['mon'];
            $dia_actual = $fecha_actual['mday'];
            
    		$annio_ingreso = substr($cumple->fecha_nacimiento, 6);
    		$mes_ingreso = substr($cumple->fecha_nacimiento, 3, 2);
            $dia_ingreso = substr($cumple->fecha_nacimiento, 0, 2);
            
    		$annio = $annio_actual - $annio_ingreso;
            
    		if($mes_actual < $mes_ingreso && $annio > 0)
            {
                $annio--;
            }
            
            else if($mes_actual == $mes_ingreso){
                if($dia_actual < $dia_ingreso){
                    $annio--;
                }
                
            }

    		else if($annio <=0){
    			$annio = 0;
    		}  
         
         ?>
        <li class="collection-item"><span class="badge">{{$annio}}</span>{{$cumple->nombre}} {{$cumple->apellido}}</li>      
      @endforeach
        </ul>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat pulse">Cerrar</a>
    </div>
  </div>

@endsection