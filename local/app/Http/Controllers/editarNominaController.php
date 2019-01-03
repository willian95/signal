<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\insertarConceptoController;
use DB;
use Illuminate\Http\Request;

class editarNominaController extends Controller {

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
        
        $referencia = $request->referencia;
        
        foreach($datos_empleado as $dato_empleado){
        
            
            if($concepto_id == 1002){
                $this->dias_descanso_obrero(1002, $id_concepto_nomina, $referencia);
            }

            else if($concepto_id == 1003){
                $this->dias_descanso_oficiales_obrero(1003, $id_concepto_nomina, $referencia);
            }

            else if($concepto_id == 1004){
                $this->jornada_diurna_obrero($concepto_id, $referencia, $dato_empleado->sueldo, $id_concepto_nomina);
            }

            else if($concepto_id == 1005){
                $this->jornada_nocturna_obrero(1005, $dato_empleado->sueldo, $referencia, $id_concepto_nomina);
            }

            else if($concepto_id == 1006){
                $this->jornada_mixta_obrero(1006, $dato_empleado->sueldo, $referencia, $id_concepto_nomina);
            }

            else if($concepto_id == 1015){
                $this->dias_remunerados_reposo_obrero(1015, $dato_empleado->sueldo, $referencia, $id_concepto_nomina);
            }

            else if($concepto_id == 5400){
                $this->horas_extras_diurnas_obrero(5400, $dato_empleado->sueldo, $referencia, $id_concepto_nomina);
            }

            else if ($concepto_id == 5402) {
                $this->horas_extras_mixtas_obrero(5402, $dato_empleado->sueldo, $referencia, $id_concepto_nomina);
            }

            else if($concepto_id == 5410){
                $this->horas_extras_nocturnas_obrero(5410, $id_concepto_nomina, $dato_empleado->sueldo, $referencia);
            }
            
            else if($concepto_id == 5430){
                $this->dias_feriados_obrero(5430, $id_concepto_nomina, $dato_empleado->sueldo, $referencia);
            }
            
            else if($concepto_id == 20100){
                $this->fideicomiso_obrero(20100, $id_concepto_nomina, $referencia);
            }
            
            else if($concepto_id == 20106){
                $this->prestamo_compra_obrero(20106, $id_concepto_nomina, $referencia);
            }

            else if($concepto_id == 20108){
                $this->reintegro_obrero(20108, $id_concepto_nomina, $referencia);
            }
            
        }
        
        $id_nomina = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_nomina');
        $id_empleado = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_empleado');
        
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
        
        return redirect()->to('/editar_personal_obrero_nomina/'.$id_empleado.'/nomina/'.$id_nomina)->with('alert', 'Concepto editado');
    }
    
    public function jornada_diurna_obrero($id, $referencia, $sueldo, $id_concepto_nomina){
    	if($id == 1004){
            
        	$sueldo_diario = $sueldo/30;
        	$monto = $sueldo_diario*$referencia;
            
        	$monto = round($monto, 2);
           
            $id_nomina = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_nomina');
            $id_empleado = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_empleado');
            
        	DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $monto]);
            (new insertarConceptoController)->verificar_dias_descanso($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);       

        }
    }
    
    public function dias_descanso_obrero($id, $id_concepto_nomina, $referencia){

        if($id==1002){
            
            $id_empleado = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_empleado');
            $id_nomina = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_nomina');
            
            $asignaciones = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->whereBetween('id_concepto', [1004, 5430])
                            ->sum('monto');
            
            $monto = ($asignaciones/5)*$referencia;
            $monto = round($monto, 2);

            DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $monto]);

            (new insertarConceptoController)->verificar_dias_descanso($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
            
        }

    }
    
    public function dias_feriados_obrero($id, $id_concepto_nomina, $sueldo, $referencia){
    	if($id == 5430){

        	$sueldo_diario = $sueldo/30;
        	$monto = $sueldo_diario * 1.5 * $referencia;
        	$monto = round($monto, 2);

        	DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $monto]);
            
            $id_nomina = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_nomina');
            $id_empleado = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_empleado');
            
            (new insertarConceptoController)->verificar_dias_descanso($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
   
        }
        
    }

    public function dias_remunerados_reposo_obrero($id, $sueldo, $referencia, $id_concepto_nomina){

        if($id == 1015){

            $sueldo_diario = $sueldo/30;
            $monto = $sueldo_diario * 7 * 0.3333;
            $monto = round($monto, 2);

            DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $monto]);
            $id_nomina = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_nomina');
            $id_empleado = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_empleado');

            (new insertarConceptoController)->verificar_dias_descanso($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
            
        }

    }

    public function horas_extras_diurnas_obrero($id, $sueldo, $referencia, $id_concepto_nomina){
        
        $sueldo_diario = $sueldo/30;
        $sueldo_hora = $sueldo_diario/8;
        $extras = $sueldo_hora*0.5;
        $monto = $extras + $sueldo_hora;
        $monto = $monto * $referencia;
        $monto = round($monto, 2);

        $id_nomina = DB::table('conceptos_nominas')
                            ->where('id', $id_concepto_nomina)
                            ->pluck('id_nomina');
            
        $id_empleado = DB::table('conceptos_nominas')
                            ->where('id', $id_concepto_nomina)
                            ->pluck('id_empleado');
        
        DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $monto]);
        
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_faov_patronal_obrero($id_nomina, $id_empleado);

    }
    

    public function horas_extras_mixtas_obrero($id, $sueldo, $referencia, $id_concepto_nomina){
        if($id == 5402){

            $sueldo_diario = $sueldo/30;
            $monto = ((((($sueldo_diario/8)*4.5)+(((($sueldo_diario + ($sueldo_diario*0.30))/7)*3)))/(7.5))*0.5)+ (((($sueldo_diario/8)*4.5)+(((($sueldo_diario + ($sueldo_diario*0.30))/7)*3)))/(7.5));
            $monto = $monto * $referencia;
            $monto = round($monto, 2);

            $id_nomina = DB::table('conceptos_nominas')
                            ->where('id', $id_concepto_nomina)
                            ->pluck('id_nomina');
            
            $id_empleado = DB::table('conceptos_nominas')
                            ->where('id', $id_concepto_nomina)
                            ->pluck('id_empleado');

            DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $monto]);
            
            (new insertarConceptoController)->verificar_dias_descanso($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        
        }
    }
    
    public function fideicomiso_obrero($id, $id_concepto_nomina, $referencia){
    	if($id == 20100){

        	$id_empleado = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_empleado');
            DB::table('datos_laborales')->where('dato_empleado_id', $id_empleado)->update(['fideicomiso' => $referencia]);
            DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $referencia]);
        }
        
    }
    
    public function jornada_nocturna_obrero($id, $sueldo, $referencia, $id_concepto_nomina){
            
        if($id == 1005){
            
            $id_nomina = DB::table('conceptos_nominas')
                            ->where('id', $id_concepto_nomina)
                            ->pluck('id_nomina');
            
            $id_empleado = DB::table('conceptos_nominas')
                            ->where('id', $id_concepto_nomina)
                            ->pluck('id_empleado');
            
            $sueldo_diario = $sueldo/30;
            $monto =(((($sueldo_diario)*30)/100)+($sueldo_diario))* $referencia;

        	$monto = round($monto, 2);
            
            DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $monto]);
        	
            
            (new insertarConceptoController)->verificar_dias_descanso($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
            
        }
    }

    public function jornada_mixta_obrero($id, $sueldo, $referencia, $id_concepto_nomina){
            
        if($id == 1006){
            
            $id_nomina = DB::table('conceptos_nominas')
                            ->where('id', $id_concepto_nomina)
                            ->pluck('id_nomina');
            
            $id_empleado = DB::table('conceptos_nominas')
                            ->where('id', $id_concepto_nomina)
                            ->pluck('id_empleado');
            
            $sueldo_diario = $sueldo/30;
            
            $monto = ((((($sueldo_diario/8)*4.5)+(((($sueldo_diario + ($sueldo_diario*0.30))/7)*3)))/(7.5))*7.5)*$referencia;
            $monto = round($monto, 2);
            
            DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $monto]);
            
            (new insertarConceptoController)->verificar_dias_descanso($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_seguro_social_obrero($id_nomina, $id_empleado);
            (new insertarConceptoController)->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
            
        }
    }

    public function dias_descanso_oficiales_obrero($id, $id_concepto_nomina, $referencia){

       /* $id_nomina = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_nomina');
        $id_empleado = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_empleado');

        $asignaciones = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->whereBetween('id_concepto', [1004, 1006])
                            ->sum('monto');
        
        $dias_feriados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5430)
                            ->pluck('monto');

        $num_asignaciones = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->whereBetween('id_concepto', [1004, 1006])
                            ->sum('referencia');
        
        $num_dias_feriados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5430)
                            ->pluck('referencia');
        
        $jornadas = $asignaciones + $dias_feriados;
        $num_jornadas = $num_asignaciones + $num_dias_feriados;

        $total = ($jornadas + $num_jornadas)*$referencia;*/

        DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $referencia]);
        
        (new insertarConceptoController)->verificar_dias_descanso($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);

    }

    public function prestamo_compra_obrero($id, $id_concepto_nomina, $referencia){

        DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $referencia]);

    }

    public function reintegro_obrero($id, $id_concepto_nomina, $referencia){
        
        DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $referencia]);
    }
    
    public function eliminar_concepto($id, $id_nomina){
        $id_empleado = DB::table('conceptos_nominas')->where('id', $id)->pluck('id_empleado');
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
        
        (new insertarConceptoController)->verificar_dias_descanso($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);



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
        
          
        return redirect()->to('/editar_personal_obrero_nomina/'.$id_empleado.'/nomina/'.$id_nomina)->with('alert', 'Concepto eliminado de la nomina');
        
        
    }

    public function horas_extras_nocturnas_obrero($id, $id_concepto_nomina, $sueldo, $referencia){

        $sueldo_diario = $sueldo/30;
        $monto = (((($sueldo_diario+($sueldo_diario*0.30))/7)*0.50)+(($sueldo_diario+($sueldo_diario*0.30))/7))*$referencia;
        $monto = round($monto, 2);

        $id_nomina = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_nomina');
        $id_empleado = DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->pluck('id_empleado');

        DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $monto]);

        (new insertarConceptoController)->verificar_dias_descanso($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);

    }

}
