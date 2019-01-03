<!DOCTYPE HTML>
<html>
    <head>
        <title>
            Reporte    
        </title>
    </head>
    <body>
      <img src="{!!public_path().'/img/zamora.png'!!}" style="width: 100%;">
      <img src="{!!public_path().'/img/zonfipca.png'!!}" style="width: 120px; height: 100px; position: absolute; left: -10px; top: 40px; left: 5px;">
       <h5 style="text-align: center; margin-top: 5px;">RECIBO DE PAGO</h5>
        @foreach($datos_empleados as $dato_empleado)
        
            <p style="position: absolute; top: 120px;"><strong>Apellidos y nombres:</strong>{{$dato_empleado->nombre}} {{$dato_empleado->apellido}}</p>
            
            <p style="position: absolute; top: 128px;"><strong>Sueldo mensual:</strong>{{number_format($sueldo, 2, ',', '.')}}</p>
            <p style="position: absolute; top: 128px; margin-left: 190px;"><strong>Fecha de ingreso:</strong>{{$dato_empleado->fecha_ingreso}}</p>
            <p style="position: absolute; top: 128px; margin-left: 390px;"><strong>Cédula:</strong>{{$dato_empleado->cedula}}</p>
            <p style="position: absolute; top:153px;"><strong>Cargo:</strong>{{$dato_empleado->descripcion}}</p>
            <p style="position: absolute; top:178px;"><strong>Periodo: </strong> {{$periodo}} del mes de {{$mes}}</p>
            <p style="position: absolute; top:203px;"><strong>Fecha: </strong> Del {{$fecha_inicio}} al {{$fecha_fin}}</p>
        
        @endforeach
        
        <table style="position:relative; top: 85px; border: 1px solid; border-collapse: collapse; width: 100%; margin-top: 30px;">
            <thead style="border: 1px solid;">
                <tr style="border: 1px solid;">
                    <th style="border: 1px solid;">Concepto</th>
                    <th style="border: 1px solid;">Asignaciones</th>
                    <th style="border: 1px solid;">Deducciones</th>
                </tr>
            </thead>
            
            <tbody>
        @foreach($conceptos as $concepto)
            <tr style="border: 1px solid;">
                <td style="border: 1px solid;">{{$concepto->descripcion}}</td>
                <td style="border: 1px solid; text-align: center;">
                @if($concepto->tipo == 'asignacion')
                    {{number_format($concepto->monto, 2, ',', '.')}}     
                @endif           
                </td>
                <td style="border: 1px solid; text-align: center;">
                @if($concepto->tipo == 'deduccion')    
                    {{number_format($concepto->monto, 2, ',', '.')}}
                @endif
                </td>
            </tr>
        
        @endforeach
              <tr>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
               <tr>
                   <td>Total de asignaciones y deducciones</td>
                   <td style="text-align: center;">{{number_format($asignaciones, 2, ',', '.')}}</td>
                   <td style="text-align: center;">{{number_format($deducciones, 2, ',', '.')}}</td>
               </tr>
            </tbody>
        
        </table>
        
        <p style="position: relative; margin-top:85px;"><strong>Monto a cobrar:  </strong>{{number_format($total, 2, ',', '.')}}</p>
        
		
		<hr style="margin-top: 90px; text-align: center; width: 280px;">
		<h4 style="text-align: center;">Firma del trabajador</h4>

    </body>
</html>