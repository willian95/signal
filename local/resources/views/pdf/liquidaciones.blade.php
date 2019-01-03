<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>

     <?php
        $coordinador_rh = DB::table('panel_control')->pluck('nombre_coordinador_rh');
        $gerente_rh = DB::table('panel_control')->pluck('nombre_gerente_rh');
        $gerente_finanzas = DB::table('panel_control')->pluck('nombre_gerente_finanzas');
    ?>

        <img src="{!!public_path().'/img/zamora.png'!!}" style="width: 100%;">
        <img src="{!!public_path().'/img/zonfipca.png'!!}" style="width: 120px; height: 100px; position: absolute; left: -10px; top: 40px; left: 5px;">
        <br>
        <br>

        <h3 style="text-align: center; font-size: 18px;">RECIBO DE LIQUIDACIÓN POR SERVICIOS LABORALES</h3>
        
        <?php 
            $prestaciones = 0;
            $utilidades = 0;
            $bono_disfrute_vacacional_fraccionado = 0;
            $vacaciones_fraccionado = 0;
            $bono_vacacional_fraccionado = 0;
            $faov = 0;
        ?>

        @foreach($datos as $dato)

            <table width="100%">
                <tbody>
                    <tr>
                        <td width="60%">
                            <strong>Nombre y apellido:</strong> {{$dato->nombre}} {{$dato->apellido}}
                        </td>
                        <td width="40%" style="text-align: right;">
                            <strong>Salario básico mensual:</strong> {{number_format(round($salario_basico_mensual, 2), 2, ',', '.')}}
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <strong>Cédula de identidad:</strong> {{$dato->cedula}}
                        </td>
                        <td width="50%" style="text-align: right;">
                            <strong>Salario básico diario:</strong> {{number_format(round($salario_basico_diario, 2), 2, ',', '.')}}
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <strong>Fecha de ingreso:</strong> {{$dato->fecha_ingreso}}
                        </td>
                        <td width="50%" style="text-align: right;">
                            <strong>Salario devengado mensual:</strong> {{number_format(round($salario_devengado_mensual, 2), 2, ',', '.')}}
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <strong>Fecha de egreso:</strong> {{$fecha_egreso}}
                        </td>
                        <td width="50%" style="text-align: right;">
                            <strong>Salario diario devengado:</strong> {{number_format(round($salario_devengado_diario, 2), 2, ',', '.')}}
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <strong>Tiempo de servicio:</strong> {{$annio}} años
                            @if($mes > 0)
                                y {{$mes}} meses
                            @endif
                        </td>
                        <td width="50%" style="text-align: right;">
                            <strong>Salario integral:</strong> {{number_format(round($salario_integral, 2), 2, ',', '.')}}
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="font-size: 16px;">
                            <strong>Cargo:</strong> {{$dato->descripcion}}
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="border: 1px solid; border-collapse: collapse; width: 100%; text-align: center;">

                <thead>
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid;">

                        </td>
                        <td style="border: 1px solid;">
                            Salario
                        </td>
                        <td style="border: 1px solid;">
                            Días
                        </td>
                        <td style="border: 1px solid;">
                            Asignaciones
                        </td>
                        <td style="border: 1px solid;">
                            Deducciones
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; font-size: 13px;">
                            PRESTACIONES ART. 142 LOTTT LITERAL C
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format(round($salario_integral, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            <?php $dias_prestaciones = 0; ?>
                            @if($mes > 6)
                                {{$dias_prestaciones = ($annio + 1)*30}}
                            @else
                                {{$dias_prestaciones = $annio*30}}
                            @endif
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            <?php 
                                $prestaciones = $salario_integral * $dias_prestaciones;
                            ?>
                            {{number_format(round($salario_integral * $dias_prestaciones, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid;">

                        </td>
                    </tr>

                    <tr style="border: 1px solid;">
                        <td colspan="5" style="text-align: center; border: 1px solid; font-size: 13px;">
                            <strong>VACACIONES Y BONO VACACIONAL</strong>
                        </td>
                    </tr>

                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; font-size: 13px;">
                            UTILIDADES
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format(round($salario_integral - $alicuota_bono_vacacional, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{$dias_utilidades}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            <?php 
                                $utilidades = $salario_integral * $dias_utilidades;
                            ?>
                            {{number_format(round($salario_integral * $dias_utilidades, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid;">

                        </td>
                    </tr>

                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; font-size: 13px;">
                            BONO DE DISFRUTE VACACIONAL  FRACC.
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format(round($salario_devengado_diario, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format(round($dias_vacaciones_fraccionado, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            <?php
                                $bono_disfrute_vacacional_fraccionado = $salario_devengado_diario * $dias_vacaciones_fraccionado;
                            ?>
                            {{number_format(round($salario_devengado_diario * $dias_vacaciones_fraccionado, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid;">

                        </td>
                    </tr>

                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; font-size: 13px;">
                            VACACIONES  FRACCIONADO
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format(round($salario_devengado_diario, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format(round($dias_vacaciones_fraccionado, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            <?php 
                                $vacaciones_fraccionado = $salario_devengado_diario * $dias_vacaciones_fraccionado;
                            ?>
                            {{number_format(round($salario_devengado_diario * $dias_vacaciones_fraccionado, 2),2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; ">

                        </td>
                    </tr>
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; font-size: 13px;">
                            BONO VACACIONAL  FRACCIONADO
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format(round($salario_devengado_diario, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format(round($dias_bono_vacacional_fraccionado, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            <?php 
                                $bono_vacacional_fraccionado = $salario_devengado_diario * $dias_bono_vacacional_fraccionado;
                            ?>
                            {{number_format(round($salario_devengado_diario * $dias_bono_vacacional_fraccionado, 2), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">

                        </td>
                    </tr>

                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; font-size: 13px;">
                            REINTEGRO CUOTAS DE FIDEICOMISO
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">

                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format($fideicomiso, 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">

                        </td>
                    </tr>

                    <tr style="border: 1px solid;">
                        <td colspan="5" style="text-align: center; border: 1px solid; font-size: 13px;">
                            <strong>DEDUCCIONES</strong>
                        </td>
                    </tr>
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; font-size: 13px;">
                            DEPOSITADO BANCO MERCANTIL
                        </td>
                        <td style="border: 1px solid;">
                            
                        </td>
                        <td style="border: 1px solid;">
                            
                        </td>
                        <td style="border: 1px solid;">
                            
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format($deposito, 2, ',', '.')}}
                        </td>
                    </tr>
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; font-size: 13px;">
                            FAOV
                        </td>
                        <td style="border: 1px solid;">
                            
                        </td>
                        <td style="border: 1px solid;">
                            
                        </td>
                        <td style="border: 1px solid;">
                            
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            <?php 
                                $faov = (($prestaciones + $utilidades + $bono_disfrute_vacacional_fraccionado + $vacaciones_fraccionado + $bono_vacacional_fraccionado)*0.01) - $fideicomiso;
                            ?>
                            {{number_format($faov , 2, ',', '.')}}
                        </td>
                    </tr>

                    <tr style="padding: 10px; border: 1px solid;">
                        <td style="border: 1px solid; padding: 10px;">
           
                        </td>
                        <td style="border: 1px solid; padding: 10px;">
           
                        </td>
                        <td style="border: 1px solid; padding: 10px;">
           
                        </td>
                        <td style="border: 1px solid; padding: 10px;">
           
                        </td>
                        <td style="border: 1px solid; padding: 10px;">
           
                        </td>
                    </tr>

                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; font-size: 13px;" colspan="3">
                            TOTAL ASIGNACIONES Y DEDUCCIONES
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format(($prestaciones + $utilidades + $bono_disfrute_vacacional_fraccionado + $vacaciones_fraccionado + $bono_vacacional_fraccionado), 2, ',', '.')}}
                        </td>
                        <td style="border: 1px solid; font-size: 13px;">
                            {{number_format($deposito + $faov, 2, ',', '.')}}
                        </td>
                    </tr>

                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; font-size: 13px;" colspan="4">
                            NETO A PAGAR
                        </td>

                        <td style="border: 1px solid;">
                            {{number_format(($prestaciones + $utilidades + $bono_disfrute_vacacional_fraccionado + $vacaciones_fraccionado + $bono_vacacional_fraccionado) - ($deposito + $faov), 2, ',', '.')}}
                        </td>
                    </tr>

                </tbody>
            
            </table>

        @endforeach

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