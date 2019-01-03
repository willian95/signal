<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\insertarConceptoEmpleadoController;
use App\Http\Controllers\insertarConceptoController;
use App\Http\Controllers\editarNominaController;
use DB;
use Illuminate\Http\Request;

class editarNominaEmpleadoController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function editar(Request $request, $concepto_id, $id_concepto_nomina){
        
        $datos_empleado = DB::table('datos_empleados')
                            ->join('conceptos_nominas', 'conceptos_nominas.id_empleado', '=', 'datos_empleados.id')
                            ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                            ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                            ->where('conceptos_nominas.id', $id_concepto_nomina)
                            ->select('salarios.sueldo', 'datos_empleados.parroquia', 'datos_empleados.tipo_carrera')
                            ->distinct()
                            ->get();

        $id_nomina = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_nomina');
        $id_empleado = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_empleado');
        
        $referencia = $request->referencia;
        
        foreach($datos_empleado as $dato_empleado){
        
            if($concepto_id == 1004)
            {
                $this->jornada_diurna_empleado($referencia, $dato_empleado->sueldo, $id_empleado, $id_nomina);
            }
            
            else if($concepto_id == 1002){
                $this->dias_descanso_empleado($referencia, $id_nomina, $id_empleado);
            }

            else if($concepto_id == 1005){
                $this->jornada_nocturna_empleado($referencia, $id_nomina, $id_empleado, $dato_empleado->sueldo);
            }

            else if($concepto_id == 1006){
                $this->jornada_mixta_empleado($referencia,  $id_nomina, $id_empleado, $dato_empleado->sueldo);
            }

            else if($concepto_id == 1015){            
                $this->dias_remunerados_reposo_empleado($referencia, $id_nomina, $id_empleado, $dato_empleado->sueldo);
            }
            
            else if($concepto_id == 5430){
                $this->dias_feriados_empleado($id_nomina, $id_empleado, $dato_empleado->sueldo, $referencia);
            }

            else if($concepto_id == 5461){
                $this->diferencia_sueldo_empleado($referencia, $id_nomina, $id_empleado);
            }
            
            else if($concepto_id == 20100){
                (new editarNominaController)->fideicomiso_obrero(20100, $id_concepto_nomina, $referencia);
            }
            
            else if($concepto_id == 20106){
                (new editarNominaController)->prestamo_compra_obrero(20106, $id_concepto_nomina, $referencia);
            }

            else if($concepto_id == 20108){
                (new editarNominaController)->reintegro_obrero(20108, $id_concepto_nomina, $referencia);
            }
            
        }
        
        $id_nomina = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_nomina');
        
         $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');


            $deducciones  = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos.tipo', 'deduccion')
                            ->sum('conceptos_nominas.monto');

            $patronal     = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos.tipo', 'patronal')
                            ->sum('conceptos_nominas.monto');


        DB::table('nomina')->where('id', $id_nomina)->update(['asignaciones' => $asignaciones, 'deducciones' => $deducciones, 'patronal' => $patronal]);
    
        return redirect()->to('/editar_personal_empleado_nomina/'.$id_empleado.'/nomina/'.$id_nomina)->with('alert', 'Concepto editado');
    }
    
    public function jornada_diurna_empleado($referencia, $sueldo, $id_empleado, $id_nomina){
            
        $sueldo_diario = $sueldo/30;
        $monto = $sueldo_diario*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1004)->update(['referencia' => $referencia, 'monto' => $monto]);

        //if($referencia < 15){
            //$this->caja_ahorro_empleado_dias_faltantes($id_nomina, $id_empleado, $sueldo);
            //$this->caja_ahorro_empleado_patronal_dias_faltantes($id_nomina, $id_empleado, $sueldo);
        //}

        (new insertarConceptoEmpleadoController)->verificar_dias_descanso_empleado($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);
        //$this->verificar_caja_ahorro($id_nomina, $id_empleado);

    }

    public function verificar_caja_ahorro($id_nomina, $id_empleado){

        $sueldo = DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1004)->pluck('monto');
        $caja = $sueldo * 0.05;

        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 20134)->update(['monto' => $caja]);
        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 20136)->update(['monto' => $caja]);

    }

    public function diferencia_sueldo_empleado($referencia, $id_nomina, $id_empleado){

        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5461)->update(['referencia' => $referencia, 'monto' => $referencia]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);

    }
    
    public function dias_descanso_empleado($referencia, $id_nomina, $id_empleado){

       $jornada_diurna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1004)
                            ->sum('monto');

        $dias_diurno = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1004)
                            ->pluck('referencia');
        
        $jornada_nocturna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1005)
                            ->sum('monto');

        $dias_nocturna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1005)
                            ->pluck('referencia');
        
        $jornada_mixta = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1006)
                            ->sum('monto');

        $dias_mixta = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1006)
                            ->pluck('referencia');

        $dias_feriados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5430)
                            ->sum('monto');

        $dias = $dias_diurno + $dias_nocturna + $dias_mixta;
        $jornadas = $jornada_diurna + $jornada_nocturna + $jornada_mixta;

        $monto = (($jornadas+$dias_feriados)/$dias)*$referencia;    
        $monto = round($monto, 2);
            
        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1002)->update(['referencia' => $referencia, 'monto' => $monto]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);

    }

    public function jornada_nocturna_empleado($referencia, $id_nomina, $id_empleado, $sueldo){

        $sueldo_diario = $sueldo/30;
        $monto = ((($sueldo_diario)*0.30)+($sueldo_diario))*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1005)->update(['referencia' => $referencia, 'monto' => $monto]);
        
        (new insertarConceptoEmpleadoController)->verificar_dias_descanso_empleado($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);

    }


    public function jornada_mixta_empleado($referencia, $id_nomina, $id_empleado, $sueldo){

        $sueldo_diario = $sueldo/30;
        $monto = ((((($sueldo_diario/8)*4.5)+(((($sueldo_diario + ($sueldo_diario*0.30))/7)*3)))/7.5)*7.5)*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1006)->update(['referencia' => $referencia, 'monto' => $monto]);
        
        (new insertarConceptoEmpleadoController)->verificar_dias_descanso_empleado($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);

    }

    public function dias_remunerados_reposo_empleado($referencia, $id_nomina, $id_empleado, $sueldo){

        $monto = (($sueldo*33.33)/100)/30* $referencia;
        $monto = round($monto, 2);

         DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1015)->update(['referencia' => $referencia, 'monto' => $monto]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);

    }
    
    public function dias_feriados_empleado($id_nomina, $id_empleado, $sueldo, $referencia){
        $sueldo_diario = $sueldo/30;
        $monto = $sueldo_diario * 1.5 * $referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5430)->update(['referencia' => $referencia, 'monto' => $monto]);

        (new insertarConceptoEmpleadoController)->verificar_dias_descanso_empleado($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);

    }

    /*public function caja_ahorro_empleado_dias_faltantes($id_nomina, $id_empleado, $sueldo){

        $jornada_diurna = DB::table('conceptos_nominas')
                                ->where('id_nomina', $id_nomina)
                                ->where('id_empleado', $id_empleado)
                                ->where('id_concepto', 1004)
                                ->pluck('referencia');

    	$monto = ($sueldo*0.05);
    	$monto = $monto/30;
        $monto = $monto*$jornada_diurna;
        $monto = round($monto, 2);
    	
        DB::table('conceptos_nominas')
                ->where('id_nomina', $id_nomina)
                ->where('id_empleado', $id_empleado)
                ->where('id_concepto', 20134)
                ->update(['referencia' => 1, 'monto' => $monto]);
        
    }*/

    /*public function caja_ahorro_empleado_patronal_dias_faltantes($id_nomina, $id_empleado, $sueldo){

        $jornada_diurna = DB::table('conceptos_nominas')
                                ->where('id_nomina', $id_nomina)
                                ->where('id_empleado', $id_empleado)
                                ->where('id_concepto', 1004)
                                ->pluck('referencia');

    	$monto = ($sueldo*0.05);
    	$monto = $monto/30;
        $monto = $monto*$jornada_diurna;
        $monto = round($monto, 2);
    	
        DB::table('conceptos_nominas')
                ->where('id_nomina', $id_nomina)
                ->where('id_empleado', $id_empleado)
                ->where('id_concepto', 20136)
                ->update(['referencia' => 1, 'monto' => $monto]);
        
    }*/
    
    public function eliminar_concepto($id, $id_nomina){
        $id_empleado = DB::table('conceptos_nominas')->where('id', $id)->pluck('id_empleado');
        $sueldo = DB::table('datos_laborales')
        				->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
        				->where('dato_empleado_id', $id_empleado)
        				->pluck('salarios.sueldo');

        $id_concepto = DB::table('conceptos_nominas')->where('id', $id)->pluck('id_concepto');

        if($id_concepto == 20105){

            $cuota = DB::table('conceptos_nominas')->where('id', $id)->pluck('monto');
            $deuda_actual = DB::table('descuentos')->where('id_empleado', $id_empleado)->pluck('deuda_actual');
            $deuda_actual = $deuda_actual + $cuota;

            DB::table('descuentos')->where('id_empleado', $id_empleado)->update(['deuda_actual' => $deuda_actual]);

        }

        
                $referencia_dd = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)                                
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 1002)
                                    ->pluck('referencia');
                
                $id_dd = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)                                
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 1002)
                                    ->pluck('id');
        
        DB::table('conceptos_nominas')->where('id', $id)->delete();
        
        (new insertarConceptoEmpleadoController)->verificar_dias_descanso_empleado($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoEmpleadoController)->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);



            $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');


            $deducciones  = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos.tipo', 'deduccion')
                            ->sum('conceptos_nominas.monto');

            $patronal     = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos.tipo', 'patronal')
                            ->sum('conceptos_nominas.monto');


        DB::table('nomina')->where('id', $id_nomina)->update(['asignaciones' => $asignaciones, 'deducciones' => $deducciones, 'patronal' => $patronal]);
        
        return redirect()->back()->with('alert', 'Concepto eliminado de la nomina');
        
    }

}
