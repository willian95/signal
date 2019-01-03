<!DOCTYPE HTML>
<html>
    <head>
        <title>
            Reporte    
        </title>
    </head>
    <body style="font-size: 14px;">
        
    <?php 
        ini_set('max_execution_time', 300);

        $deducciones_general = DB::table('conceptos_nominas')
        							->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
        							->where('conceptos_nominas.id_nomina', $id_nomina)
        							->where('conceptos.tipo', 'deduccion')
        							->sum('conceptos_nominas.monto');



    ?>

        <img src="{!!public_path().'/img/zamora.png'!!}" style="width: 100%;">
        <img src="{!!public_path().'/img/zonfipca.png'!!}" style="width: 120px; height: 100px; position: absolute; left: -10px; top: 40px; left: 5px;">
        
       <h5 style="text-align: center; margin-top: 5px;">Nomina @if($tipo == 'obrero'){{'operario'}} @endif n°: {{$n_nomina}}</h5>

       <strong>Del:</strong> {{$fecha_inicio}} &nbsp;&nbsp; <strong>al:</strong> {{$fecha_fin}}

       <br>
       <br>
       
        @foreach($gerencias as $gerencia)

            <?php 
                $asignaciones_g = 0;
                $deducciones_g = 0;
                $patronales_g = 0;

                $datos_empleados = DB::table('nomina')->where('nomina.id', $id_nomina)
                                        ->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
                                        ->distinct()
                                        ->join('datos_empleados', 'conceptos_nominas.id_empleado', '=', 'datos_empleados.id')
                                        ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                                        ->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
                                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                                        ->where('datos_laborales.departamento_id', $gerencia->id)
                                        ->select('datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.tipo_carrera', 'salarios.sueldo', 'datos_laborales.fecha_ingreso', 'datos_empleados.cedula', 'datos_empleados.rif', 'cargos.descripcion', 'nomina.id as nomina_id', 'datos_empleados.id as empleado_id')
                                        ->get();

                $num_empleados = DB::table('nomina')->where('nomina.id', $id_nomina)
                                        ->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
                                        ->distinct()
                                        ->join('datos_empleados', 'conceptos_nominas.id_empleado', '=', 'datos_empleados.id')
                                        ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                                        ->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
                                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                                        ->where('datos_laborales.departamento_id', $gerencia->id)
                                        ->select('datos_empleados.nombre', 'datos_empleados.apellido', 'salarios.sueldo', 'datos_laborales.fecha_ingreso', 'datos_empleados.cedula', 'datos_empleados.rif', 'cargos.descripcion', 'nomina.id as nomina_id', 'datos_empleados.id as empleado_id')
                                        ->count();

            ?>
            @if($num_empleados > 0)
                <p style="text-align: center;"><strong>{{$gerencia->nombre_departamento}}</strong></p>
            @endif


            @foreach($datos_empleados as $dato_empleado)
        
                   <p style="font-size: 10px;"><strong>Nombre y apellidos: </strong>{{$dato_empleado->nombre}} {{$dato_empleado->apellido}} &nbsp; <strong>Sueldo mensual:</strong>{{number_format($dato_empleado->sueldo, 2, ',', '.')}} &nbsp;&nbsp; <strong>C.I:</strong>{{$dato_empleado->cedula}} &nbsp;&nbsp; <strong>Fecha de ingreso:</strong>{{$dato_empleado->fecha_ingreso}}&nbsp;&nbsp; <strong>Grado de instrucción:</strong>{{$dato_empleado->tipo_carrera}}</p>
            
                <?php
                    $asignaciones = 0;
                    $deducciones = 0;
                    $patronales = 0;
                    $conceptos = DB::table('conceptos_nominas')
	                                ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
	                                ->where('id_nomina', $dato_empleado->nomina_id)
	                                ->where('id_empleado', $dato_empleado->empleado_id)
	                                ->orderBy('id_concepto', 'asc')
	                                ->get();

                ?>

                <table style="border: 1px solid; border-collapse: collapse; width: 100%; font-size: 13px;">
                    <thead style="border: 1px solid;">
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; font-size: 8px;">N° concepto</th>
                            <th style="border: 1px solid; font-size: 8px;">Concepto</th>
                            <th style="border: 1px solid; font-size: 8px;">Referencia</th>
                            <th style="border: 1px solid; font-size: 8px;">Asignaciones</th>
                            <th style="border: 1px solid; font-size: 8px;">Deducciones</th>
                            <th style="border: 1px solid; font-size: 8px;">Patronal</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                @foreach($conceptos as $concepto)

                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; font-size: 8px; text-align: center;">{{$concepto->id_concepto}}</td>
                        <td style="border: 1px solid; font-size: 8px; text-align: center;">{{$concepto->descripcion}}</td>
                        <td style="border: 1px solid; font-size: 12px; text-align: center;">{{$concepto->referencia}}</td>
                        <td style="border: 1px solid; text-align: center; font-size: 12px;">
                        @if($concepto->tipo == 'asignacion')
                            <?php 
                                $asignaciones = $asignaciones + $concepto->monto;
                            ?>
                            {{number_format($concepto->monto, 2, ',', '.')}}     
                        @endif           
                        </td>
                        <td style="border: 1px solid; text-align: center; font-size: 12px;">
                        @if($concepto->tipo == 'deduccion')
                            <?php 
                                $deducciones = $deducciones + $concepto->monto;
                            ?>      
                            {{number_format($concepto->monto, 2, ',', '.')}}
                        @endif
                        </td>
                        <td style="border: 1px solid; text-align: center; font-size: 12px;">
                        @if($concepto->tipo == 'patronal')   
                            <?php 
                                $patronales = $patronales + $concepto->monto;
                            ?> 
                            {{number_format($concepto->monto, 2, ',', '.')}}
                        @endif
                        </td>
                    </tr>
                
                @endforeach
                    <?php 
                        $asignaciones_g = $asignaciones_g + $asignaciones;
                        $deducciones_g = $deducciones_g + $deducciones;
                        $patronales_g = $patronales_g + $patronales;
                    ?>
                    <tr style="border: 1px solid; font-size: 12px;">
                           <td colspan="3" style="border: 1px solid; text-align: center;"><strong>TOTAL DE ASIGNACIONES Y DEDUCCIONES</strong></td>
                           <td style="border: 1px solid; text-align: center;">{{number_format($asignaciones, 2, ',', '.')}}</td>
                           <td style="border: 1px solid; text-align: center;">{{number_format($deducciones, 2, ',', '.')}}</td>
                           <td style="border: 1px solid; text-align: center;">{{number_format($patronales, 2, ',', '.')}}</td>
                       </tr>
                       <tr style="border: 1px solid;">
                            <td style="border: 1px solid; text-align: center;"> Neto a cobrar</td>
                           <td colspan="5" style="border: 1px solid; text-align: center;">{{number_format($asignaciones-$deducciones, 2, ',', '.')}}</td>
                       </tr>
                       
                    </tbody>
                
                </table>

                <br>

                @endforeach
                
                @if($num_empleados > 0)

                    <p style="text-align: center;"><strong>TOTAL {{$gerencia->nombre_departamento}}</strong></p>
                <table style="border: 1px solid; border-collapse: collapse; width: 100%; font-size: 12px;">
                    <thead style="border: 1px solid;">
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; text-align: center;">Neto a Cobrar</th>
                            <th style="border: 1px solid; text-align: center;">General Asignaciones</th>
                            <th style="border: 1px solid; text-align: center;">General Deducciones</th>
                            <th style="border: 1px solid; text-align: center;">General Patronal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border: 1px solid;">
                            <td style="border: 1px solid; text-align: center;">{{number_format($asignaciones_g - $deducciones_g, 2, ',', '.')}}</td>
                            <td style="border: 1px solid; text-align: center;">{{number_format($asignaciones_g, 2, ',', '.')}}</td>
                            <td style="border: 1px solid; text-align: center;">{{number_format($deducciones_g, 2, ',', '.')}}</td>
                            <td style="border: 1px solid; text-align: center;">{{number_format($patronales_g, 2, ',', '.')}}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                @endif

        @endforeach

        <table style="border: 1px solid; border-collapse: collapse; width: 100%; font-size: 12px;">
            <thead style="border: 1px solid;">
                <tr style="border: 1px solid;">
                    <th style="border: 1px solid; text-align: center;">Total Neto a Cobrar</th>
                    <th style="border: 1px solid; text-align: center;">Total General Asignaciones</th>
                    <th style="border: 1px solid; text-align: center;">Total General Deducciones</th>
                    <th style="border: 1px solid; text-align: center;">Total General Patronal</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border: 1px solid;">
                    <td style="border: 1px solid; text-align: center;">{{number_format($total_asignaciones - $deducciones_general, 2, ',', '.')}}</td>
                    <td style="border: 1px solid; text-align: center;">{{number_format($total_asignaciones, 2, ',', '.')}}</td>
                    <td style="border: 1px solid; text-align: center;">{{number_format($deducciones_general, 2, ',', '.')}}</td>
                    <td style="border: 1px solid; text-align: center;">{{number_format($total_patronal, 2, ',', '.')}}</td>
                </tr>
            </tbody>
        </table>

    </body>
</html>