<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Trimestre</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
    
        <img src="{!!public_path().'/img/zamora.png'!!}" style="width: 100%;">
        <img src="{!!public_path().'/img/zonfipca.png'!!}" style="width: 120px; height: 100px; position: absolute; left: -10px; top: 40px; left: 5px;">
    <?php
        $coordinador_rh = DB::table('panel_control')->pluck('nombre_coordinador_rh');
	    $gerente_rh = DB::table('panel_control')->pluck('nombre_gerente_rh');
	    $gerente_finanzas = DB::table('panel_control')->pluck('nombre_gerente_finanzas');
    ?>
    <br>
    <br>

        <!--            Empleados                   -->

        <p>Depósito en garantía de las Prestaciones Sociales  Art. 143. LOTTT Trimestre @foreach($meses as $mes) {{$mes}} @endforeach 

            <?php if(count($anios) > 1){
                echo "de los años";
            }else{
                echo "del año";
            } ?>
            @foreach($anios as $anio)
                {{$anio}}
            @endforeach
        </p>

        <table  style="width: 100%; border: 1px solid; border-collapse: collapse; font-size: 9px; text-align: center; page-break-after:always;">
            <thead>
                <tr style="border: 1px solid;">
                    <td style="border: 1px solid;">
                        Nombre Trabajador
                    </td>
                    <td style="border: 1px solid;">
                        Cédula
                    </td>
                    <td style="border: 1px solid;">
                        Fecha Ingreso
                    </td>
                    <td style="border: 1px solid;">
                        Salario Básico Mensual
                    </td>
                    <td style="border: 1px solid;">
                        Salario Diario
                    </td>
                    <td style="border: 1px solid;">
                        Prima Transporte
                    </td>
                    <td style="border: 1px solid;">
                        Prima Profesionalización
                    </td>
                    <td style="border: 1px solid;">
                        Prima Antiguedad
                    </td>
                    <td style="border: 1px solid;">
                        Dias Feriados
                    </td>
                    <td style="border: 1px solid;">
                        Salario Devengado por Mes
                    </td>
                    <td style="border: 1px solid;">
                        Salario Prom/Día
                    </td>
                    <td style="border: 1px solid;">
                        Alic. Utilidad/Día
                    </td>
                    <td style="border: 1px solid;">
                        Alic. B. Vac/Día
                    </td>
                    <td style="border: 1px solid;">
                        Salario Integral
                    </td>
                    <td style="border: 1px solid;">
                        Total Días
                    </td>
                    <td style="border: 1px solid;">
                        Incremento Fideicomiso
                    </td>
                </tr>
            </thead>
            <tbody style="font-size: 9px;">

            <?php $total_prestaciones_empleados = 0; ?>

            @foreach($departamentos as $departamento)

                <?php

                    $total_fideicomiso_departamento = 0;
                    $sueldo_minimo = DB::table('panel_control')->pluck('sueldo_minimo');
                    $transporte_cerca = DB::table('panel_control')->pluck('prima_transporte_cerca');
                    $transporte_cerca_minimo = DB::table('panel_control')->pluck('prima_transporte_cerca_minimo');
                    $transporte_lejos = DB::table('panel_control')->pluck('prima_transporte_lejos');

                    $empleados = DB::table('datos_empleados')
                                        ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                                        ->where('datos_laborales.departamento_id', $departamento->id)
                                        ->where('datos_laborales.tipo_trabajador', 'empleado')
                                        ->select('datos_empleados.nombre', 'datos_empleados.cedula', 'datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'datos_empleados.tipo_carrera', 'datos_empleados.municipio', 'salarios.sueldo')
                                        ->get();
                                            
                    $num_empleados = DB::table('datos_empleados')
                                            ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                                            ->where('datos_laborales.departamento_id', $departamento->id)
                                            ->where('datos_laborales.tipo_trabajador', 'empleado')
                                            ->count();
                        
                ?>

				@if($num_empleados > 0)
					<tr style="background-color: #90caf9;">
						<td colspan="16" style="text-align: center; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
							{{$departamento->nombre_departamento}}
						</td>
					</tr>
				@endif

				@foreach($empleados as $empleado)

					<tr style="border: 1px solid; font-size: 9px;">
						<td style="border: 1px solid;">
							{{$empleado->nombre}} {{$empleado->apellido}}
						</td>
						<td style="border: 1px solid;">
							{{$empleado->cedula}}
						</td>
						<td style="border: 1px solid;">
							{{$empleado->fecha_ingreso}}
						</td>
						<td style="border: 1px solid;">
							{{number_format(round($empleado->sueldo, 2),2,',', '.')}}
						</td>
						<td style="border: 1px solid;">
							{{number_format(round($empleado->sueldo/30, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $prima_transporte;
                                if($empleado->sueldo == $sueldo_minimo && strtolower($empleado->municipio) == "carirubana"){
                                    $prima_transporte = $transporte_cerca_minimo;
                                }

                                else if($empleado->sueldo > $sueldo_minimo && strtolower($empleado->municipio) == "carirubana"){
                                    $prima_transporte = $transporte_cerca;
                                }

                                else if(strtolower($empleado->municipio) != "carirubana"){
                                    $prima_transporte = $transporte_lejos;
                                }

                                echo number_format(round($prima_transporte, 2),2, ',', '.');
                            ?>
                        </td>
						<td style="border: 1px solid;">
                            <?php
                                $prima_profesionalizacion; 
                                if($empleado->tipo_carrera != 'NP'){
                                    if($empleado->tipo_carrera == 'larga'){
                                
                                        $monto = $empleado->sueldo*0.15;
                                        $monto = round($monto, 2);
                                        $prima_profesionalizacion = $monto;
                                    
                                    }
                                    
                                    else{
                                        
                                        $monto = $empleado->sueldo*0.12;
                                        $monto = round($monto, 2);
                                        $prima_profesionalizacion = $monto;
                                        
                                    }

                                    echo number_format(round($prima_profesionalizacion, 2), 2, ',', '.');
                                }
                            ?>
						</td>
						<td style="border: 1px solid;">
                            <?php

                                $prima_antiguedad;
                                $anios;
                                $fecha_ingreso = $empleado->fecha_ingreso;
                                $fecha_actual = getdate();
                                $annio_actual =  $fecha_actual['year'];
                                $mes_actual = $fecha_actual['mon'];
                                $dia_actual = $fecha_actual['mday'];
                                
                                $annio_ingreso = substr($fecha_ingreso, 6);
                                $mes_ingreso = substr($fecha_ingreso, 3, 2);
                                $dia_ingreso = substr($fecha_ingreso, 0, 2);
                                
                                $annio = $annio_actual - $annio_ingreso;
                                
                                if($mes_actual < $mes_ingreso && $annio > 0)
                                {
                                    $annio--;
                                }
                                
                                else if($mes_actual == $mes_ingreso){
                                    if($dia_actual <= $dia_ingreso){
                                        $annio--;
                                    }
                                }

                                else if($annio <=0){
                                    $annio = 0;
                                }

                                else if($annio >=10){
                                    $annio=10;
                                }

                                $monto = $empleado->sueldo*($annio/100);
                                
                                $monto = round($monto, 2);
                                $prima_antiguedad = $monto;
                                echo number_format(round($prima_antiguedad, 2), 2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            0
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $devengado = $empleado->sueldo + $prima_transporte + $prima_profesionalizacion + $prima_antiguedad;
                                echo number_format(round($devengado, 2),2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $prom_dia = $devengado/50;
                                echo number_format(round($prom_dia, 2), 2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $alic_u = $prom_dia * 0.25;
                                echo number_format(round($alic_u, 2), 2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $alic_v;
                                $fecha_ingreso = $empleado->fecha_ingreso;
                                $fecha_actual = getdate();
                                $annio_actual =  $fecha_actual['year'];
                                $mes_actual = $fecha_actual['mon'];
                                $dia_actual = $fecha_actual['mday'];
                                
                                $annio_ingreso = substr($fecha_ingreso, 6);
                                $mes_ingreso = substr($fecha_ingreso, 3, 2);
                                $dia_ingreso = substr($fecha_ingreso, 0, 2);
                                
                                $annio = $annio_actual - $annio_ingreso;
                                
                                if($mes_actual < $mes_ingreso && $annio > 0)
                                {
                                    $annio--;
                                }
                                
                                else if($mes_actual == $mes_ingreso){
                                    if($dia_actual <= $dia_ingreso){
                                        $annio--;
                                    }
                                }
                                
                                else if($annio <=0){
                                    $annio = 0;
                                }

                                $annio = 15 + $annio;
                                $alic_v = (($annio + 30)/360)*$prom_dia;
                                echo number_format(round($alic_v, 2), 2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $salario_integral;
                                $salario_integral = $prom_dia + $alic_u + $alic_v;
                                echo number_format(round($salario_integral, 2), 2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            15
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $incremento;
                                $incremento = 15 * $salario_integral;
                                $total_fideicomiso_departamento = $total_fideicomiso_departamento + $incremento;
                                $total_prestaciones_empleados = $total_prestaciones_empleados + $total_fideicomiso_departamento;
                                echo number_format(round($incremento, 2), 2, ',', '.');
                            ?>
                        </td>
					</tr>
				
				@endforeach

                @if($num_empleados > 0)
                    <tr style="background-color: #9fa8da; border: 1px solid;">
                            <td colspan="15" style="text-align: right; padding-right: 10px; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
                                TOTAL {{$departamento->nombre_departamento}}
                            </td>
                            <td style="border: 1px solid;">
                                {{number_format(round($total_fideicomiso_departamento, 2), 2, ',', '.')}}
                            </td>
                    </tr>
                @endif

			@endforeach

            <tr style="background-color: #f4ff81; border: 1px solid;">
                <td colspan="15" style="text-align: right; padding-right: 10px; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
                    TOTAL PRESTACIONES EMPLEADOS
                </td>
                <td style="border: 1px solid;">
                    {{number_format(round($total_prestaciones_empleados, 2), 2, ',', '.')}}
                </td>            
            </tr>

            </tbody>
        </table>

        <!--            Obreros                   -->

        <table  style="width: 100%; border: 1px solid; border-collapse: collapse; font-size: 9px; text-align: center;">
            <thead>
                <tr style="border: 1px solid;">
                    <td style="border: 1px solid;">
                        Nombre Trabajador
                    </td>
                    <td style="border: 1px solid;">
                        Cédula
                    </td>
                    <td style="border: 1px solid;">
                        Fecha Ingreso
                    </td>
                    <td style="border: 1px solid;">
                        Salario Básico Mensual
                    </td>
                    <td style="border: 1px solid;">
                        Salario Diario
                    </td>
                    <td style="border: 1px solid;">
                        Prima Transporte
                    </td>
                    <td style="border: 1px solid;">
                        Prima Profesionalización
                    </td>
                    <td style="border: 1px solid;">
                        Prima Antiguedad
                    </td>
                    <td style="border: 1px solid;">
                        Dias Feriados
                    </td>
                    <td style="border: 1px solid;">
                        Salario Devengado por Mes
                    </td>
                    <td style="border: 1px solid;">
                        Salario Prom/Día
                    </td>
                    <td style="border: 1px solid;">
                        Alic. Utilidad/Día
                    </td>
                    <td style="border: 1px solid;">
                        Alic. B. Vac/Día
                    </td>
                    <td style="border: 1px solid;">
                        Salario Integral
                    </td>
                    <td style="border: 1px solid;">
                        Total Días
                    </td>
                    <td style="border: 1px solid;">
                        Incremento Fideicomiso
                    </td>
                </tr>
            </thead>
            <tbody style="font-size: 9px;">

                <?php $total_prestaciones_obrero = 0 ?>

            @foreach($departamentos as $departamento)

                <?php

                    $total_fideicomiso_departamento = 0;
                    $sueldo_minimo = DB::table('panel_control')->pluck('sueldo_minimo');
                    $transporte_cerca = DB::table('panel_control')->pluck('prima_transporte_cerca');
                    $transporte_cerca_minimo = DB::table('panel_control')->pluck('prima_transporte_cerca_minimo');
                    $transporte_lejos = DB::table('panel_control')->pluck('prima_transporte_lejos');

                    $empleados = DB::table('datos_empleados')
                                        ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                                        ->where('datos_laborales.departamento_id', $departamento->id)
                                        ->where('datos_laborales.tipo_trabajador', 'obrero')
                                        ->select('datos_empleados.nombre', 'datos_empleados.cedula', 'datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'datos_empleados.tipo_carrera', 'datos_empleados.municipio', 'salarios.sueldo')
                                        ->get();
                                            
                    $num_empleados = DB::table('datos_empleados')
                                            ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                                            ->where('datos_laborales.departamento_id', $departamento->id)
                                            ->where('datos_laborales.tipo_trabajador', 'obrero')
                                            ->count();
                        
                ?>

				@if($num_empleados > 0)
					<tr style="background-color: #90caf9;">
						<td colspan="16" style="text-align: center; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
							{{$departamento->nombre_departamento}}
						</td>
					</tr>
				@endif

				@foreach($empleados as $empleado)

					<tr style="border: 1px solid; font-size: 9px;">
						<td style="border: 1px solid;">
							{{$empleado->nombre}} {{$empleado->apellido}}
						</td>
						<td style="border: 1px solid;">
							{{$empleado->cedula}}
						</td>
						<td style="border: 1px solid;">
							{{$empleado->fecha_ingreso}}
						</td>
						<td style="border: 1px solid;">
							{{number_format(round($empleado->sueldo, 2), 2, ',', '.')}}
						</td>
						<td style="border: 1px solid;">
							{{number_format(round($empleado->sueldo/30, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $prima_transporte;
                                if($empleado->sueldo == $sueldo_minimo && strtolower($empleado->municipio) == "carirubana"){
                                    $prima_transporte = $transporte_cerca_minimo;
                                }

                                else if($empleado->sueldo > $sueldo_minimo && strtolower($empleado->municipio) == "carirubana"){
                                    $prima_transporte = $transporte_cerca;
                                }

                                else if(strtolower($empleado->municipio) != "carirubana"){
                                    $prima_transporte = $transporte_lejos;
                                }

                                echo number_format(round($prima_transporte, 2), 2, ',', '.');
                            ?>
                        </td>
						<td style="border: 1px solid;">
                            <?php
                                $prima_profesionalizacion; 
                                if($empleado->tipo_carrera != 'NP'){
                                    if($empleado->tipo_carrera == 'larga'){
                                
                                        $monto = $empleado->sueldo*0.15;
                                        $monto = round($monto, 2);
                                        $prima_profesionalizacion = $monto;
                                    
                                    }
                                    
                                    else{
                                        
                                        $monto = $empleado->sueldo*0.12;
                                        $monto = round($monto, 2);
                                        $prima_profesionalizacion = $monto;
                                        
                                    }

                                    echo number_format(round($prima_profesionalizacion, 2), 2, ',', '.');
                                }
                            ?>
						</td>
						<td style="border: 1px solid;">
                            <?php

                                $prima_antiguedad;
                                $anios;
                                $fecha_ingreso = $empleado->fecha_ingreso;
                                $fecha_actual = getdate();
                                $annio_actual =  $fecha_actual['year'];
                                $mes_actual = $fecha_actual['mon'];
                                $dia_actual = $fecha_actual['mday'];
                                
                                $annio_ingreso = substr($fecha_ingreso, 6);
                                $mes_ingreso = substr($fecha_ingreso, 3, 2);
                                $dia_ingreso = substr($fecha_ingreso, 0, 2);
                                
                                $annio = $annio_actual - $annio_ingreso;
                                
                                if($mes_actual < $mes_ingreso && $annio > 0)
                                {
                                    $annio--;
                                }
                                
                                else if($mes_actual == $mes_ingreso){
                                    if($dia_actual <= $dia_ingreso){
                                        $annio--;
                                    }
                                }

                                else if($annio <=0){
                                    $annio = 0;
                                }

                                else if($annio >=10){
                                    $annio=10;
                                }

                                $monto = $empleado->sueldo*($annio/100);
                                
                                $monto = round($monto, 2);
                                $prima_antiguedad = $monto;
                                echo number_format(round($prima_antiguedad, 2), 2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            0
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $devengado = $empleado->sueldo + $prima_transporte + $prima_profesionalizacion + $prima_antiguedad;
                                echo number_format(round($devengado, 2), 2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $prom_dia = $devengado/30;
                                echo number_format(round($prom_dia, 2), 2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $alic_u = $prom_dia * 0.25;
                                echo number_format(round($alic_u, 2), 2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $alic_v;
                                $fecha_ingreso = $empleado->fecha_ingreso;
                                $fecha_actual = getdate();
                                $annio_actual =  $fecha_actual['year'];
                                $mes_actual = $fecha_actual['mon'];
                                $dia_actual = $fecha_actual['mday'];
                                
                                $annio_ingreso = substr($fecha_ingreso, 6);
                                $mes_ingreso = substr($fecha_ingreso, 3, 2);
                                $dia_ingreso = substr($fecha_ingreso, 0, 2);
                                
                                $annio = $annio_actual - $annio_ingreso;
                                
                                if($mes_actual < $mes_ingreso && $annio > 0)
                                {
                                    $annio--;
                                }
                                
                                else if($mes_actual == $mes_ingreso){
                                    if($dia_actual <= $dia_ingreso){
                                        $annio--;
                                    }
                                }
                                
                                else if($annio <=0){
                                    $annio = 0;
                                }

                                $annio = 15 + $annio;
                                $alic_v = (($annio + 30)/360)*$prom_dia;
                                echo number_format(round($alic_v, 2), 2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $salario_integral;
                                $salario_integral = $prom_dia + $alic_u + $alic_v;
                                echo number_format(round($salario_integral, 2), 2, ',', '.');
                            ?>
                        </td>
                        <td style="border: 1px solid;">
                            15
                        </td>
                        <td style="border: 1px solid;">
                            <?php
                                $incremento;
                                $incremento = 15 * $salario_integral;
                                echo number_format(round($incremento, 2), 2, ',', '.');
                                $total_fideicomiso_departamento = $total_fideicomiso_departamento + $incremento;
                                $total_prestaciones_obrero = $total_prestaciones_obrero + $total_fideicomiso_departamento;
                            ?>
                        </td>
					</tr>
				
				@endforeach

                @if($num_empleados > 0)
                    <tr style="background-color: #9fa8da;">
                            <td colspan="15" style="text-align: right; padding-right: 10px; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
                                TOTAL {{$departamento->nombre_departamento}}
                            </td>
                            <td>
                                {{number_format(round($total_fideicomiso_departamento, 2), 2, ',', '.')}}
                            </td>
                    </tr>
                @endif

			@endforeach

            <tr style="background-color: #f4ff81; border: 1px solid;">
                <td colspan="15" style="text-align: right; padding-right: 10px; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
                    TOTAL PRESTACIONES OBREROS
                </td>
                <td style="border: 1px solid;">
                    {{number_format(round($total_prestaciones_obrero, 2), 2, ',', '.')}}
                </td>            
            </tr>
            <tr style="background-color: #81c784; border: 1px solid;">
                <td colspan="15" style="text-align: right; padding-right: 10px; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
                    TOTAL GENERAL DE PRESTACIONES SOCIALES
                </td>
                <td style="border: 1px solid;">
                    {{number_format(round($total_prestaciones_obrero + $total_prestaciones_empleados, 2), 2, ',', '.')}}
                </td>            
            </tr>

            </tbody>
        </table>

        <div style="position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  padding: 1rem;
  text-align: center;">
			<table style="width: 100%; ">
		<tr>
			<td style="text-align: center">
				Elaborado por:
			</td>
			<td style="text-align: center">
				Revisado por:
			</td>
			<td style="text-align: center">
				Aprobado por:
			</td>
		</tr>
		<tr>
			<td colspan="3" style="padding-top: 10px; padding-bottom: 10px;">
				
			</td>
		</tr>

		<tr>
			<td style="text-align: center; font-size: 12px;">
				<strong>{{$coordinador_rh}}<br>
				COORDiNADOR DE RECURSOS HUMANOS</strong>

			</td>
			<td style="text-align: center; font-size: 12px;">
				<strong>{{$gerente_rh}}<br>
				GERENTE DE RECURSOS HUMANOS (E)</strong>
			</td>
			<td style="text-align: center; font-size: 12px;">
				<strong>{{$gerente_finanzas}}<br>
				GERENTE DE ADMINISTRACIÓN Y FINANZAS</strong>
			</td>
		</tr>
	</table>
	</div>
    
    </body>
</html>