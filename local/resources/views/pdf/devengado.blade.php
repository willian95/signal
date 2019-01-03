<!DOCTYPE html>
<html>
<head>
  <title>Total Devengado</title>
</head>
<body>
  <?php 
    $departamentos = DB::table('departamentos')->get();
    $subtotal_completo = 0;

    $coordinador_rh = DB::table('panel_control')->pluck('nombre_coordinador_rh');
    $gerente_rh = DB::table('panel_control')->pluck('nombre_gerente_rh');
    $gerente_finanzas = DB::table('panel_control')->pluck('nombre_gerente_finanzas');
  ?>

  <div style="text-align: center; margin-bottom: -12px;">
    <strong>RELACIÓN DE EMPLEADOS (NÓMINA QUINCENAL) Y OPERARIOS (NÓMINA SEMANAL)</strong>
  </div>
  <div style="text-align: center;">
    <strong>Total Devengado</strong>
  </div>

  <p>Mes y Año: <strong>{{$mes}}/{{$annio}}</strong></p>

  <div style="border: 1px solid; font-size: 12px; margin-bottom: -3px; margin-left: 0.4px; margin-right: 0.4px;"><strong>NOMBRE DE EMPRESA:ZONA FRANCA INDUSTRIAL COMERCIAL Y DE SERVICIOS DE PARAGUANA </strong></div>
  <div style="border: 1px solid; font-size: 12px; margin-bottom: -1.8px; margin-left: 0.4px; margin-right: 0.4px;"><strong>TELÉFONOS: 2482553-2481198</strong></div>

  <table style="width: 100%; border: 1px solid; border-collapse: collapse; page-break-after:always;">
    <thead>
      <tr style="border: 1px solid; font-size: 13px;">
        <td style="border: 1px solid;">
          <strong>APELLIDOS Y NOMBRES</strong>
        </td>
        <td style="border: 1px solid;">
          <strong>CÉDULA DE IDENTIDAD</strong>
        </td>
        <td style="border: 1px solid;">
          <strong>FECHA DE INGRESO</strong>
        </td>
        <td style="border: 1px solid;">
          <strong>SALARIO MENSUAL</strong>
        </td>
        <td style="border: 1px solid;">
          <strong>SALARIO DEVENGADO MENSUAL</strong>
        </td>
      </tr>
    </thead>
    <tbody>
    <?php
      $subtotal_empleados = 0;
    ?>
      @foreach($departamentos as $departamento)

        <?php 
          $obreros = DB::table('datos_empleados')
                    ->join('conceptos_nominas', 'conceptos_nominas.id_empleado', '=', 'datos_empleados.id')
                    ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                    ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                    ->where('datos_laborales.departamento_id', $departamento->id)
                    ->where('datos_laborales.tipo_trabajador', 'empleado')
                    ->where('datos_empleados.status', 'activo')
                    ->select('datos_empleados.cedula', 'datos_empleados.nombre', 'salarios.sueldo','datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'datos_empleados.id')
                    ->distinct()
                    ->get();
          
          $num_obreros = DB::table('datos_empleados')
                    ->join('conceptos_nominas', 'conceptos_nominas.id_empleado', '=', 'datos_empleados.id')
                    ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                    ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                    ->where('datos_laborales.departamento_id', $departamento->id)
                    ->where('datos_laborales.tipo_trabajador', 'empleado')
                    ->where('datos_empleados.status', 'activo')
                    ->select('datos_empleados.cedula', 'datos_empleados.nombre', 'salarios.sueldo','datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'datos_empleados.id')
                    ->count();
        ?>

        <?php
          $subtotal_aporte = 0;
          $subtotal = 0;
        ?>

        @if($num_obreros > 0)
          <tr style="background-color: #90caf9;">
            <td colspan="5" style="text-align: center; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
              {{$departamento->nombre_departamento}}
            </td>
          </tr>
        @endif

        @foreach($obreros as $obrero)
        
          <?php 

            $aporte = DB::table('nomina')
                    ->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
                    ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                    ->where('nomina.fecha_inicio', 'like', '%'.$mes.'/'.$annio.'%')
                    ->where('conceptos_nominas.id_empleado', $obrero->id)
                    ->where('conceptos.tipo', 'asignacion')
                    ->sum('conceptos_nominas.monto');

            $aporte = round($aporte, 2);
            $subtotal_aporte += $aporte;

            $subtotal_aporte = round($subtotal_aporte, 2);
          ?>
          @if($aporte > 0)
            <tr style="border: 1px solid; font-size: 12px;">
              <td style="border: 1px solid;">
                {{$obrero->nombre}} {{$obrero->apellido}}
              </td>
              <td style="border: 1px solid;">
                {{$obrero->cedula}}
              </td>
              <td style="border: 1px solid;">
                {{$obrero->fecha_ingreso}}
              </td>
              <td style="border: 1px solid;">
                {{number_format($obrero->sueldo, 2, ',', '.')}}
              </td>
              <td style="border: 1px solid;">
                {{number_format($aporte, 2, ',', '.')}}
              </td>
            </tr>
          @endif
        
        @endforeach

          <?php  
            $subtotal = $subtotal_aporte * 2;
            $subtotal_completo += $subtotal; 

            $subtotal = round($subtotal, 2);
            $subtotal_completo = round($subtotal_completo, 2);
            $subtotal_empleados += $subtotal_aporte;
          ?>
          @if($subtotal > 0)
          <tr style="border: 1px solid; font-size: 13px;">
            <td colspan="4" style="text-align: center; border: 1px solid;">
              Subtotal
            </td>
            <td style="border: 1px solid;">
              {{number_format($subtotal_aporte, 2, ',', '.')}}
            </td>
          </tr>
          @endif
      @endforeach

      <tr style="border: 1px solid; font-size: 13px;">
            <td colspan="4" style="text-align: center; border: 1px solid;">
              Total Empleados
            </td>
            <td style="border: 1px solid;">
              {{number_format($subtotal_empleados, 2, ',', '.')}}
            </td>
          </tr>

    </tbody>
  </table>

  <br>
  <br>

  <div style="page-break: before;">
  <!--<table style="width: 100%; border: 1px solid; border-collapse: collapse; page-break-after:always;"-->
  <table style="width: 100%; border: 1px solid; border-collapse: collapse; margin-bottom: 60px;">
    <thead>
      <tr style="border: 1px solid; font-size: 13px;">
        <td style="border: 1px solid;">
          <strong>APELLIDOS Y NOMBRES</strong>
        </td>
        <td style="border: 1px solid;">
          <strong>CÉDULA DE IDENTIDAD</strong>
        </td>
        <td style="border: 1px solid;">
          <strong>FECHA DE INGRESO</strong>
        </td>
        <td style="border: 1px solid;">
          <strong>SALARIO MENSUAL</strong>
        </td>
        <td style="border: 1px solid;">
          <strong>SALARIO DEVENGADO MENSUAL</strong>
        </td>

      </tr>
    </thead>
    <tbody>
      <?php
        $subtotal_obreros = 0;
      ?>
      @foreach($departamentos as $departamento)

        <?php 
          $obreros = DB::table('datos_empleados')
                    ->join('conceptos_nominas', 'conceptos_nominas.id_empleado', '=', 'datos_empleados.id')
                    ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                    ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                    ->where('datos_laborales.departamento_id', $departamento->id)
                    ->where('datos_laborales.tipo_trabajador', 'obrero')
                    ->where('datos_empleados.status', 'activo')
                    ->select('datos_empleados.cedula', 'datos_empleados.nombre', 'salarios.sueldo','datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'datos_empleados.id')
                    ->distinct()
                    ->get();
          
          $num_obreros = DB::table('datos_empleados')
                    ->join('conceptos_nominas', 'conceptos_nominas.id_empleado', '=', 'datos_empleados.id')
                    ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                    ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                    ->where('datos_laborales.departamento_id', $departamento->id)
                    ->where('datos_laborales.tipo_trabajador', 'obrero')
                    ->where('datos_empleados.status', 'activo')
                    ->select('datos_empleados.cedula', 'datos_empleados.nombre', 'salarios.sueldo','datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'datos_empleados.id')
                    ->count();
        ?>

        <?php
          $subtotal_aporte = 0;
          $subtotal = 0;
        ?>

        @if($num_obreros > 0)
          <tr style="background-color: #90caf9;">
            <td colspan="5" style="text-align: center; padding-top: 10px; padding-bottom: 10px; border: 1px solid;">
              {{$departamento->nombre_departamento}}
            </td>
          </tr>
        @endif

        @foreach($obreros as $obrero)
        
          <?php 

            $aporte = DB::table('nomina')
                    ->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
                    ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                    ->where('nomina.fecha_inicio', 'like', '%/'.$mes.'/'.$annio.'%')
                    ->where('conceptos_nominas.id_empleado', $obrero->id)
                    ->where('conceptos.tipo', 'asignacion')
                    ->sum('conceptos_nominas.monto');

            $aporte = round($aporte, 2);
            $subtotal_aporte += $aporte;

            $subtotal_aporte = round($subtotal_aporte, 2);
          ?>

          <tr style="border: 1px solid; font-size: 12px;">
            <td style="border: 1px solid;">
              {{$obrero->nombre}} {{$obrero->apellido}}
            </td>
            <td style="border: 1px solid;">
              {{$obrero->cedula}}
            </td>
            <td style="border: 1px solid;">
              {{$obrero->fecha_ingreso}}
            </td>
            <td style="border: 1px solid;">
              {{number_format($obrero->sueldo, 2, ',', '.')}}
            </td>
            <td style="border: 1px solid;">
              {{number_format($aporte, 2, ',', '.')}}
            </td>
          </tr>
        
        @endforeach

          <?php  
            $subtotal = $subtotal_aporte * 2;
            $subtotal_completo += $subtotal; 

            $subtotal = round($subtotal, 2);
            $subtotal_completo = round($subtotal_completo, 2);
            $subtotal_obreros += $subtotal_aporte;
          ?>
          @if($subtotal > 0)
          <tr style="border: 1px solid; font-size: 13px;">
            <td colspan="4" style="text-align: center; border: 1px solid;">
              Subtotal
            </td>
            <td style="border: 1px solid;">
              {{number_format($subtotal_aporte, 2, ',', '.')}}
            </td>
          </tr>
          @endif


      @endforeach

        <tr style="border: 1px solid; font-size: 13px;">
            <td colspan="4" style="text-align: center; border: 1px solid;">
              Total Obreros
            </td>
            <td style="border: 1px solid;">
              {{number_format($subtotal_obreros, 2, ',', '.')}}
            </td>
          </tr>

          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>
          <tr> <td colspan="5"></td></tr>

          <tr style="border: 1px solid; font-size: 13px;">
            <td colspan="4" style="text-align: center; border: 1px solid; font-size: 14px;">
              Total Empleados y Obreros
            </td>
            <td style="border: 1px solid; font-size: 14px;">
              {{number_format($subtotal_obreros + $subtotal_empleados, 2, ',', '.')}}
            </td>
          </tr>

      

    </tbody>
  </table>

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


  <!--<div style="position: absolute;
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
        GERENTE DE RECURSOS HUMANOS</strong>
      </td>
      <td style="text-align: center; font-size: 12px;">
        <strong>{{$gerente_finanzas}}<br>
        GERENTE DE ADMINISTRACIÓN Y FINANZAS</strong>
      </td>
    </tr>
  </table>
  </div>-->

</body>
</html>