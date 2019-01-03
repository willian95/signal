<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\insertarConceptoController;
use DB;
use Illuminate\Http\Request;

class insertarConceptoEmpleadoController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function insertar_concepto(Request $request, $id_nomina, $id_empleado){
        
        $concepto = $request->concepto;
        $referencia = $request->referencia;

        $verificar = DB::table('conceptos_nominas')
                        ->where('id_nomina', $id_nomina)
                        ->where('id_empleado', $id_empleado)
                        ->where('id_concepto', $request->concepto)
                        ->get();

        $fecha_fin = DB::table('nomina')->where('id', $id_nomina)->pluck('fecha_fin');

        $empleados = DB::table('datos_empleados')
                            ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                            ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                            ->where('datos_empleados.id', $id_empleado)
                            ->select('salarios.sueldo', 'datos_empleados.tipo_carrera', 'datos_laborales.fideicomiso', 'datos_empleados.municipio')
                            ->get();

        if($verificar != null){
            return redirect()->to('/editar_personal_empleado_nomina/'.$id_empleado.'/nomina/'.$id_nomina)->with('alert', 'Error, concepto previamente asignado');
        }

        else{

            foreach($empleados as $empleado){

                if($concepto == 1004){

                    $this->jornada_diurno_empleado($referencia, $empleado->sueldo, $id_empleado, $id_nomina);
                    $this->mod_nomina($id_nomina);

                }

                else if($concepto == 1002){

                    $this->dias_descanso_empleado($referencia, $id_nomina, $id_empleado, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 1005){

                    $this->jornada_nocturna_empleado($referencia, $id_nomina, $id_empleado, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 1006){

                    $this->jornada_mixta_empleado($referencia, $id_nomina, $id_empleado, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);

                }

                else if($concepto == 1015){
                    $this->dias_remunerados_reposo_empleado($referencia, $id_nomina, $id_empleado, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5400){
                    $this->horas_extras_diurno_empleado($referencia, $empleado->sueldo, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5430){
                    $this->dias_feriados_empleado($id_nomina, $id_empleado, $empleado->sueldo, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5440){
                    $this->prima_transporte_empleado($empleado->sueldo, $empleado->municipio, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5450){
                    $this->prima_profesionalizacion_empleado($empleado->sueldo, $empleado->tipo_carrera, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5454){
                    $this->prima_profesionalizacion_reposo_empleado($empleado->sueldo, $empleado->tipo_carrera, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5460){
                    $this->otras_asignaciones_empleado($referencia, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5461){
                    $this->diferencia_sueldo_empleado($referencia, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5471){
                    $this->prima_antiguedad_empleado($id_empleado, $id_nomina, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5472){
                    $this->prima_antiguedad_reposo_empleado($id_empleado, $id_nomina, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5473){
                    $this->prima_responsabilidad($id_empleado, $id_nomina);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5474){
                    $this->diferencia_prima_transporte($id_empleado, $id_nomina, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20100){

                    (new insertarConceptoController)->fideicomiso_obrero($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20105){

                    (new insertarConceptoController)->descuento_articulo($id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);

                }

                else if($concepto == 20106){
                    (new insertarConceptoController)->prestamo_compra_obrero($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20108){
                    (new insertarConceptoController)->reintegro_obrero($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20111){
                    $this->seguro_social_empleado($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20121){
                    (new insertarConceptoController)->faov_obrero($id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20126){
                    $this->descuento($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20131){
                    $this->regimen_prestacional_empleo_empleado($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20134){
                    $this->caja_ahorro_empleado($id_nomina, $id_empleado, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

				else if($concepto == 20222){
                    $this->tesoreria_seguridad_empleado($id_nomina, $id_empleado, $referencia, $fecha_fin, $empleado->tipo_carrera, $empleado->sueldo, $empleado->municipio);

                    //$this->tesoreria_seguridad_empleado($id_nomina, $id_empleado, $referencia, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20136){
                    $this->caja_ahorro_empleado_patronal($id_nomina, $id_empleado, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 30111){
                    $this->seguro_social_patronal_empleado($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 30121){
                    (new insertarConceptoController)->faov_($id_nomina, $id_empleado, $referencia);
                    //(new insertarConceptoController)->faov_patronal_obrero($id_nomina, $id_empleado, $referencia);
		    $this->mod_nomina($id_nomina);
                }

		else if($concepto == 30131){
                    $this->regimen_prestacional_empleo_empleado_patronal($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 30172){
                    $this->tesoreria_seguridad_empleado_patronal($id_nomina, $id_empleado, $referencia, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5400 || $concepto == 5402 || $concepto == 5410){
                    return redirect()->back();
                    //return redirect()->to('/editar_personal_empleado_nomina/'.$id_empleado.'/nomina/'.$id_nomina)->with('alert', 'Error, este concepto no aplica a esta nomina');
                }

                return redirect()->to('/editar_personal_empleado_nomina/'.$id_empleado.'/nomina/'.$id_nomina)->with('alert', 'Error, concepto previamente asignado')->with('alert', 'Concepto insertado');

            }

        }

    }

    public function descuento($id_nomina, $id_empleado, $referencia){

    	DB::table('conceptos_nominas')->insert(['monto' => $referencia, 'id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'id_concepto' => 20126, 'referencia' => $referencia]);

    }

    public function diferencia_prima_transporte($id_empleado, $id_nomina, $referencia){

        $sueldo = DB::table('datos_laborales')
            ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')->where('dato_empleado_id', $id_empleado)->pluck('salarios.sueldo');

        DB::table('conceptos_nominas')->insert(['id_concepto' => 5474, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => 1, 'monto' => $referencia]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_dias_descanso_empleado($id_nomina, $id_empleado, $sueldo);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);

    }

    public function dias_descanso_empleado($referencia, $id_nomina, $id_empleado, $sueldo){

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

        DB::table('conceptos_nominas')->insert(['id_concepto' => 1002, 'id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => $referencia, 'monto' => $monto]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);

    }

    public function jornada_diurno_empleado($referencia, $sueldo, $id_empleado, $id_nomina){

        $sueldo_diario = $sueldo/30;
        $monto = $sueldo_diario*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 1004, 'id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => $referencia, 'monto' => $monto]);

        $this->verificar_dias_descanso_empleado($id_nomina, $id_empleado, $sueldo);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);

    }

	public function caja_ahorro_empleado($id_nomina, $id_empleado, $referencia){

		$sueldo = DB::table('datos_laborales')
			->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')->where('dato_empleado_id', $id_empleado)->pluck('salarios.sueldo');

		$monto = ($sueldo*0.05)/2;
    		$monto = round($monto, 2);

        	DB::table('conceptos_nominas')
                ->insert(['id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'id_concepto' => 20134, 'referencia' => 2, 'monto' => $monto]);
	}

	public function caja_ahorro_empleado_patronal($id_nomina, $id_empleado, $referencia){

		$sueldo = DB::table('datos_laborales')
			->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')->where('dato_empleado_id', $id_empleado)->pluck('salarios.sueldo');

		$monto = ($sueldo*0.05)/2;
    		$monto = round($monto, 2);

        	DB::table('conceptos_nominas')
                ->insert(['id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'id_concepto' => 20136, 'referencia' => 2, 'monto' => $monto]);
	}

    public function horas_extras_diurno_empleado($referencia, $sueldo, $id_nomina, $id_empleado){

        $sueldo_diario = $sueldo/30;
        $sueldo_hora = $sueldo_diario/8;
        $extras = $sueldo_hora*0.5;

        $monto = $extras + $sueldo_hora;
        $monto = $monto * $referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 5400, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);

    }

    public function jornada_mixta_empleado($referencia, $id_nomina, $id_empleado, $sueldo){

        $sueldo_diario = $sueldo/30;
        $monto = ((((($sueldo_diario/8)*4.5)+(((($sueldo_diario + ($sueldo_diario*0.30))/7)*3)))/7.5)*7.5)*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 1006, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

        $this->verificar_dias_descanso_empleado($id_nomina, $id_empleado, $sueldo);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);

    }

    public function jornada_nocturna_empleado($referencia, $id_nomina, $id_empleado, $sueldo){

        $sueldo_diario = $sueldo/30;
        $monto = ((($sueldo_diario)*0.30)+($sueldo_diario))*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 1005, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

        $this->verificar_dias_descanso_empleado($id_nomina, $id_empleado, $sueldo);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);

    }

    public function dias_remunerados_reposo_empleado($referencia, $id_nomina, $id_empleado, $sueldo){

        $monto = (($sueldo*33.33)/100)/30* $referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 1015, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);

    }

    public function dias_feriados_empleado($id_nomina, $id_empleado, $sueldo, $referencia){
        $sueldo_diario = $sueldo/30;
        $monto = $sueldo_diario * 1.5 * $referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['referencia' => $referencia, 'monto' => $monto, 'id_concepto' => 5430, 'id_empleado' => $id_empleado, 'id_nomina' => $id_nomina]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado, $sueldo);
        $this->verificar_dias_descanso_empleado($id_nomina, $id_empleado, $sueldo);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);

    }

    public function prima_transporte_empleado($sueldo, $municipio, $id_nomina, $id_empleado){
        $municipio = strtolower($municipio);

        $sueldo_minimo = DB::table('panel_control')->pluck('sueldo_minimo');
        $transporte_cerca = DB::table('panel_control')->pluck('prima_transporte_cerca');
        $transporte_cerca_minimo = DB::table('panel_control')->pluck('prima_transporte_cerca_minimo');
        $transporte_lejos = DB::table('panel_control')->pluck('prima_transporte_lejos');

            if($sueldo == $sueldo_minimo && $municipio == "carirubana"){
                $monto = $transporte_cerca_minimo;
            }

            else if($sueldo > $sueldo_minimo && $municipio == "carirubana"){
                $monto = $transporte_cerca;
            }

            else if($municipio != "carirubana"){
                $monto = $transporte_lejos;
            }

            DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => 1, 'id_concepto' => 5440, 'monto' => $monto]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_dias_descanso_empleado($id_nomina, $id_empleado, $sueldo);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);
    }

    public function transporte_tesoreria_verificar($sueldo, $municipio, $id_nomina, $id_empleado){

        $municipio = strtolower($municipio);
        $sueldo_minimo = DB::table('panel_control')->pluck('sueldo_minimo');
        $transporte_cerca = DB::table('panel_control')->pluck('prima_transporte_cerca');
        $transporte_cerca_minimo = DB::table('panel_control')->pluck('prima_transporte_cerca_minimo');
        $transporte_lejos = DB::table('panel_control')->pluck('prima_transporte_lejos');

            if($sueldo == $sueldo_minimo && $municipio == "carirubana"){
                $monto = $transporte_cerca_minimo;

            }

            else if($sueldo > $sueldo_minimo && $municipio == "carirubana"){
                $monto = $transporte_cerca;

            }

            else if($municipio != "carirubana"){
                $monto = $transporte_lejos;
            }
        return $monto;
    }

    public function prima_profesionalizacion_empleado($sueldo, $tipo_carrera, $id_nomina, $id_empleado){
        $monto = 0;

        if($tipo_carrera != 'NP'){

            if($tipo_carrera == 'tsu'){

                $monto = $sueldo*0.12;
                $monto = round($monto, 2);

            }

            else if($tipo_carrera == 'licenciados'){

                $monto = $sueldo*0.15;
                $monto = round($monto, 2);
                

            }

            else if($tipo_carrera == 'especialista'){

                $monto = $sueldo*0.17;
                $monto = round($monto, 2);
                return $monto;

            }

            else if($tipo_carrera == 'magister'){

                $monto = $sueldo*0.18;
                $monto = round($monto, 2);

            }

            else if($tipo_carrera == 'doctor'){

                $monto = $sueldo*0.20;
                $monto = round($monto, 2);

            }


            $monto = round($monto, 2);

            DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => 1, 'id_concepto' => 5450, 'monto' => $monto]);


            (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
	        $this->verificar_dias_descanso_empleado($id_nomina, $id_empleado, $sueldo);
	        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
	        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
	        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
	        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
	        $this->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);

        }
    }

    public function prima_profesionalizacion_reposo_empleado($sueldo, $tipo_carrera, $id_nomina, $id_empleado){
        $monto = 0;

        if($tipo_carrera != 'NP'){

            if($tipo_carrera == 'larga'){

                $monto = $sueldo*0.15;

            }

            else if($tipo_carrera == 'corta'){

                $monto = $sueldo*0.12;

            }

            $monto = $monto*0.3333;
            $monto = round($monto, 2);

            DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => 1, 'id_concepto' => 5454, 'monto' => $monto]);

            (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
            $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
            $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
            $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
            $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
            $this->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);

        }

    }

    public function prima_antiguedad_empleado($id_empleado, $id_nomina, $sueldo){
        $fecha_actual = getdate();
        $annio_actual =  $fecha_actual['year'];
        $mes_actual = $fecha_actual['mon'];
        $dia_actual = $fecha_actual['mday'];

        $fecha_ingreso = DB::table('datos_laborales')
                            ->where('dato_empleado_id', $id_empleado)
                            ->pluck('fecha_comienzo_laboral');

        $annio_ingreso = substr($fecha_ingreso, 6);
        $mes_ingreso = substr($fecha_ingreso, 3, 2);
        $dia_ingreso = substr($fecha_ingreso, 0, 2);
        $annio = $annio_actual - $annio_ingreso;
        
        if($mes_actual < $mes_ingreso && $annio > 0)
            {
                $annio--;
            }

            if($mes_actual == $mes_ingreso){
                if($dia_actual <= $dia_ingreso){
                    $annio--;
                }
            }

            if($annio <=0){
                $annio = 0;
            }

            if($annio > 10 && $annio <= 20){
                $annio=11;
            }

            if($annio > 20){
                $annio = 12;
            }

            $monto = $sueldo*(($annio*2)/100);

            $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => 1, 'id_concepto' => 5471, 'monto' => $monto]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);

    }

    public function prima_antiguedad_reposo_empleado($id_empleado, $id_nomina, $sueldo){

        $fecha_actual = getdate();
        $annio_actual =  $fecha_actual['year'];
        $mes_actual = $fecha_actual['mon'];
        $dia_actual = $fecha_actual['mday'];

        $fecha_ingreso = DB::table('datos_laborales')
                            ->where('dato_empleado_id', $id_empleado)
                            ->pluck('fecha_ingreso');

        $annio_ingreso = substr($fecha_ingreso, 6);
        $mes_ingreso = substr($fecha_ingreso, 3, 2);
        $dia_ingreso = substr($fecha_ingreso, 0, 2);
        $annio = $annio_actual - $annio_ingreso;

        if($mes_actual < $mes_ingreso && $annio > 0){
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

        $monto = $sueldo*($annio/100);
        $monto = $monto*0.3333;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => 1, 'id_concepto' => 5472, 'monto' => $monto]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);

    }

    public function verificar_dias_descanso_empleado($id_nomina, $id_empleado){

        $monto = 0;

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

        $referencia = DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 1002)->pluck('referencia');

        $dias = $dias_diurno + $dias_nocturna + $dias_mixta;
        $jornadas = $jornada_diurna + $jornada_nocturna + $jornada_mixta;

        if($dias > 0)
            $monto = (($jornadas+$dias_feriados)/$dias)*$referencia;

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 1002)->update(['referencia' => $referencia, 'monto' => $monto]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);

    }

    public function seguro_social_empleado($id_nomina, $id_empleado, $referencia){

        //if($id == 20111)

        $jornada_diurna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1004)
                            ->sum('monto');

        $jornada_nocturna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1005)
                            ->sum('monto');

        $jornada_mixta = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1006)
                            ->sum('monto');

        $dias_remunerados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1015)
                            ->sum('monto');

        $dias_descanso = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1002)
                            ->sum('monto');

        $diferencia_prima_transporte = DB::table('conceptos_nominas')
                                            ->where('id_nomina', $id_nomina)
                                            ->where('id_empleado', $id_empleado)
                                            ->where('id_concepto', 5474)
                                            ->sum('monto');

        $prima_transporte = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 5440)
                                    ->sum('monto');

        $prima_profesionalizacion = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5450)
                                        ->sum('monto');

        $prima_profesionalizacion_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5454)
                                        ->sum('monto');

        $prima_antiguedad = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5471)
                                        ->sum('monto');

        $prima_antiguedad_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5472)
                                        ->sum('monto');

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte + $prima_antiguedad_reposo + $prima_profesionalizacion_reposo + $diferencia_prima_transporte;
        $jornadas = $jornada_diurna + $jornada_nocturna + $jornada_mixta;

        $monto = ((($jornadas+$primas+$dias_descanso+$dias_remunerados)*12)/52)*0.04*$referencia;
        $monto = round($monto, 2);

       DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => $referencia, 'id_concepto' => 20111, 'monto' => $monto]);

    }

    public function verificar_seguro_social_empleado($id_nomina, $id_empleado){

        //if($id == 20111)

        $jornada_diurna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1004)
                            ->sum('monto');

        $jornada_nocturna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1005)
                            ->sum('monto');

        $jornada_mixta = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1006)
                            ->sum('monto');

        $dias_remunerados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1015)
                            ->sum('monto');

        $dias_descanso = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1002)
                            ->sum('monto');

        $otras_asignaciones = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5460)
                            ->sum('monto');

        $diferencia_prima_transporte = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 5474)
                                    ->sum('monto');

        $prima_transporte = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 5440)
                                    ->sum('monto');

        $prima_profesionalizacion = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5450)
                                        ->sum('monto');

        $prima_profesionalizacion_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5454)
                                        ->sum('monto');

        $prima_antiguedad = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5471)
                                        ->sum('monto');

        $prima_antiguedad_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5472)
                                        ->sum('monto');

        $referencia = DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 20111)->pluck('referencia');

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte + $prima_antiguedad_reposo + $prima_profesionalizacion_reposo + $diferencia_prima_transporte;
        $jornadas = $jornada_diurna + $jornada_nocturna + $jornada_mixta;

        $monto = ((($jornadas+$primas+$dias_descanso+$dias_remunerados+$otras_asignaciones)*12)/52)*0.04*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 20111)->update(['referencia' => $referencia, 'monto' => $monto]);

    }

    public function diferencia_sueldo_empleado($referencia, $id_nomina, $id_empleado){

        DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => $referencia, 'id_concepto' => 5461, 'monto' => $referencia]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);

    }

    public function seguro_social_patronal_empleado($id_nomina, $id_empleado, $referencia){

        $jornada_diurna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1004)
                            ->sum('monto');

        $jornada_nocturna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1005)
                            ->sum('monto');

        $jornada_mixta = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1006)
                            ->sum('monto');

        $dias_remunerados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1015)
                            ->sum('monto');

        $dias_descanso = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1002)
                            ->sum('monto');

        $diferencia_prima_transporte = DB::table('conceptos_nominas')
                                            ->where('id_nomina', $id_nomina)
                                            ->where('id_empleado', $id_empleado)
                                            ->where('id_concepto', 5474)
                                            ->sum('monto');

        $prima_transporte = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 5440)
                                    ->sum('monto');

        $prima_profesionalizacion = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5450)
                                        ->sum('monto');

        $prima_profesionalizacion_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5454)
                                        ->sum('monto');

        $prima_antiguedad = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5471)
                                        ->sum('monto');

        $prima_antiguedad_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5472)
                                        ->sum('monto');

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte + $prima_antiguedad_reposo + $prima_profesionalizacion_reposo + $diferencia_prima_transporte;
        $jornadas = $jornada_diurna + $jornada_nocturna + $jornada_mixta;
        $monto = ((($jornadas+$primas+$dias_descanso+$dias_remunerados)*12)/52)*0.09*$referencia;

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')
                ->insert(['id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'id_concepto' => 30111, 'referencia' => $referencia, 'monto' => $monto]);

    }

    public function verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado){

        $jornada_diurna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1004)
                            ->sum('monto');

        $jornada_nocturna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1005)
                            ->sum('monto');

        $jornada_mixta = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1006)
                            ->sum('monto');

        $dias_remunerados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1015)
                            ->sum('monto');

        $dias_descanso = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1002)
                            ->sum('monto');

        $otras_asignaciones = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5460)
                            ->sum('monto');

        $diferencia_prima_transporte = DB::table('conceptos_nominas')
                                            ->where('id_nomina', $id_nomina)
                                            ->where('id_empleado', $id_empleado)
                                            ->where('id_concepto', 5474)
                                            ->sum('monto');


        $prima_transporte = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 5440)
                                    ->sum('monto');

        $prima_profesionalizacion = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5450)
                                        ->sum('monto');

        $prima_profesionalizacion_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5454)
                                        ->sum('monto');

        $prima_antiguedad = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5471)
                                        ->sum('monto');

        $prima_antiguedad_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5472)
                                        ->sum('monto');

        $referencia = DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 30111)->pluck('referencia');

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte + $prima_antiguedad_reposo + $prima_profesionalizacion_reposo + $diferencia_prima_transporte;
        $jornadas = $jornada_diurna + $jornada_nocturna + $jornada_mixta;
        $monto = ((($jornadas+$primas+$dias_descanso+$dias_remunerados+$otras_asignaciones)*12)/52)*0.09*$referencia;

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 30111)->update(['referencia' => $referencia, 'monto' => $monto]);

    }

    public function regimen_prestacional_empleo_empleado($id_nomina, $id_empleado, $referencia){

        $jornada_diurna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1004)
                            ->sum('monto');

        $jornada_nocturna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1005)
                            ->sum('monto');

        $jornada_mixta = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1006)
                            ->sum('monto');

        $dias_remunerados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1015)
                            ->sum('monto');

        $dias_descanso = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1002)
                            ->sum('monto');

        $diferencia_prima_transporte = DB::table('conceptos_nominas')
                                            ->where('id_nomina', $id_nomina)
                                            ->where('id_empleado', $id_empleado)
                                            ->where('id_concepto', 5474)
                                            ->sum('monto');

        $prima_transporte = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 5440)
                                    ->sum('monto');

        $prima_profesionalizacion = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5450)
                                        ->sum('monto');

        $prima_profesionalizacion_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5454)
                                        ->sum('monto');

        $prima_antiguedad = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5471)
                                        ->sum('monto');

        $prima_antiguedad_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5472)
                                        ->sum('monto');

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte + $prima_antiguedad_reposo + $prima_profesionalizacion_reposo + $diferencia_prima_transporte;
        $jornadas = $jornada_diurna + $jornada_nocturna + $jornada_mixta;
        $monto = ((($jornadas+$primas+$dias_descanso+$dias_remunerados)*12)/52)*0.005*$referencia;

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 20131, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

    }

public function regimen_prestacional_empleo_empleado_patronal($id_nomina, $id_empleado, $referencia){

        $jornada_diurna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1004)
                            ->sum('monto');

        $jornada_nocturna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1005)
                            ->sum('monto');

        $otras_asignaciones = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5460)
                            ->sum('monto');

        $jornada_mixta = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1006)
                            ->sum('monto');

        $dias_remunerados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1015)
                            ->sum('monto');

        $dias_descanso = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1002)
                            ->sum('monto');

        $diferencia_prima_transporte = DB::table('conceptos_nominas')
                                            ->where('id_nomina', $id_nomina)
                                            ->where('id_empleado', $id_empleado)
                                            ->where('id_concepto', 5474)
                                            ->sum('monto');

        $prima_transporte = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 5440)
                                    ->sum('monto');

        $prima_profesionalizacion = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5450)
                                        ->sum('monto');

        $prima_profesionalizacion_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5454)
                                        ->sum('monto');

        $prima_antiguedad = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5471)
                                        ->sum('monto');

        $prima_antiguedad_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5472)
                                        ->sum('monto');

        //$referencia = DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 30131)->pluck('referencia');

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte + $prima_antiguedad_reposo + $prima_profesionalizacion_reposo + $diferencia_prima_transporte;
        $jornadas = $jornada_diurna + $jornada_nocturna + $jornada_mixta;
        $monto = ((($jornadas+$primas+$dias_descanso+$dias_remunerados+$otras_asignaciones)*12)/52)*0.02*$referencia;

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 30131, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

    }

    public function verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado){

        $jornada_diurna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1004)
                            ->sum('monto');

        $jornada_nocturna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1005)
                            ->sum('monto');

        $jornada_mixta = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1006)
                            ->sum('monto');

        $dias_remunerados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1015)
                            ->sum('monto');

        $dias_descanso = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1002)
                            ->sum('monto');

        $otras_asignaciones = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5460)
                            ->sum('monto');

        $diferencia_prima_transporte = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 5474)
                                    ->sum('monto');

        $prima_transporte = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 5440)
                                    ->sum('monto');

        $prima_profesionalizacion = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5450)
                                        ->sum('monto');

        $prima_profesionalizacion_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5454)
                                        ->sum('monto');

        $prima_antiguedad = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5471)
                                        ->sum('monto');

        $prima_antiguedad_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5472)
                                        ->sum('monto');

        $referencia = DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 20131)->pluck('referencia');

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte + $prima_antiguedad_reposo + $prima_profesionalizacion_reposo + $diferencia_prima_transporte;
        $jornadas = $jornada_diurna + $jornada_nocturna + $jornada_mixta;
        $monto = ((($jornadas+$primas+$dias_descanso+$dias_remunerados+$otras_asignaciones)*12)/52)*0.005*$referencia;

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 20131)->update(['referencia' => $referencia, 'monto' => $monto]);

    }

    public function prima_responsabilidad($id_nomina, $id_empleado){

        $cargo = DB::table('datos_laborales')
                        ->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
                        ->where('datos_laborales.dato_empleado_id', $id_empleado)
                        ->pluck('cargos.descripcion');

        $sueldo = DB::table('datos_laborales')
                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                        ->where('datos_laborales.dato_empleado_id', $id_empleado)
                        ->pluck('salarios.sueldo');

        $asignacion = 0;

        if(strpos($cargo, 'COORDINADOR') > 0){
            $asignacion = $sueldo * 0.10;
        }
        else if(strpos($cargo, 'GERENTE') > 0){
            $asignacion = $sueldo * 0.20;
        }
        if($cargo == 'GERENTE GENERAL'){
            $asignacion = $sueldo * 0.30;
        }

        DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 5473)->update([ 'monto' => $asignacion]);

        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado);

    }

    public function otras_asignaciones_empleado($referencia, $id_nomina, $id_empleado){

        DB::table('conceptos_nominas')->insert(['id_concepto' => 5460, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $referencia]);

        (new insertarConceptoController)->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_empleado($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_empleado($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado);


    }

    public function verificar_regimen_prestacional_empleo_patronal_empleado($id_nomina, $id_empleado){

        $jornada_diurna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1004)
                            ->sum('monto');

        $jornada_nocturna = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1005)
                            ->sum('monto');

        $otras_asignaciones = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5460)
                            ->sum('monto');

        $jornada_mixta = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1006)
                            ->sum('monto');

        $dias_remunerados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1015)
                            ->sum('monto');

        $dias_descanso = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 1002)
                            ->sum('monto');

        $prima_transporte = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto', 5440)
                                    ->sum('monto');

        $prima_profesionalizacion = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5450)
                                        ->sum('monto');

        $prima_profesionalizacion_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5454)
                                        ->sum('monto');

        $prima_antiguedad = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5471)
                                        ->sum('monto');

        $prima_antiguedad_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5472)
                                        ->sum('monto');

        $referencia = DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 30131)->pluck('referencia');

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte + $prima_antiguedad_reposo + $prima_profesionalizacion_reposo;
        $jornadas = $jornada_diurna + $jornada_nocturna + $jornada_mixta;
        $monto = ((($jornadas+$primas+$dias_descanso+$dias_remunerados+$otras_asignaciones)*12)/52)*0.02*$referencia;

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 30131)->update(['referencia' => $referencia, 'monto' => $monto]);

    }

    public function tesoreria_seguridad_empleado($id_nomina, $id_empleado, $referencia, $fecha_fin, $tipo_carrera, $sueldo, $municipio){

        $prima_profesionalizacion = $this->profesionalizacion_tesoreria($sueldo, $tipo_carrera);
        $prima_antiguedad = $this->antiguedad_tesoreria($id_empleado, $sueldo, $fecha_fin);
        $prima_transporte = $this->transporte_tesoreria($sueldo, $municipio, $id_nomina, $id_empleado);
        $prima_responsabilidad = $this->responsabilidad_tesoreria($id_empleado);

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte + $prima_responsabilidad;
        $asignaciones = ($sueldo + $primas)/2;
        $monto = ($asignaciones*0.03);
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 20222, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => 4, 'monto' => $monto]);
        DB::table('conceptos_nominas')->insert(['id_concepto' => 30172, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => 4, 'monto' => $monto]);
        
        /*
        echo "Prima profesionalizacion: ".$prima_profesionalizacion."<br>";
        echo "Prima antiguedad: ".$prima_antiguedad."<br>";
        echo "Prima transporte: ".$prima_transporte."<br>";
        echo "Prima responsabilidad: ".$prima_responsabilidad."<br>";
        echo "Monto: ".$monto."<br>";*/
        

    }

    public function tesoreria_seguridad_empleado_patronal($id_nomina, $id_empleado, $referencia, $fecha_fin, $tipo_carrera, $sueldo, $municipio){

        $prima_profesionalizacion = $this->profesionalizacion_tesoreria($sueldo, $tipo_carrera);
        $prima_antiguedad = $this->antiguedad_tesoreria($id_empleado, $sueldo, $fecha_fin);
        $prima_transporte = $this->transporte_tesoreria($sueldo, $municipio, $id_nomina, $id_empleado);

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte;
        $asignaciones = ($sueldo + $primas)/2;
        $monto = ($asignaciones*0.03);
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 20222, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 30172, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);


    }

    public function transporte_tesoreria($sueldo, $municipio, $id_nomina, $id_empleado){

        $b=0;
        $municipio = strtolower($municipio);
        $monto = 0;
        $fecha_actual = getdate();
        $annio_actual =  $fecha_actual['year'];
        $mes_actual = $fecha_actual['mon'];
        $dia_actual = $fecha_actual['mday'];
        $fecha_ingreso = DB::table('datos_laborales')->where('dato_empleado_id', $id_empleado)->pluck('fecha_ingreso');
        $jubilado = DB::table('datos_laborales')->where('dato_empleado_id', $id_empleado)->where('jubilado', 1)->pluck('jubilado');

        $annio_ingreso = substr($fecha_ingreso, 6);
        $mes_ingreso = substr($fecha_ingreso, 3, 2);
        $dia_ingreso = substr($fecha_ingreso, 0, 2);

        $municipio = DB::table('datos_empleados')
                            ->where('id', $id_empleado)
                            ->pluck('municipio');

        if($mes_actual < 10){

        	$mes_actual="0".$mes_actual;
        	
        }

        $fecha = $dia_actual."/".$mes_actual."/".$annio_actual;

        $fecha_inicio = DB::table('disfrute_vacacional')
        						->where('dato_empleado_id', $id_empleado)
        						->orderBy('id', 'desc')
        						->take(1)
        						->pluck('fecha_inicio');

        $fecha_fin = DB::table('disfrute_vacacional')
        						->where('dato_empleado_id', $id_empleado)
        						->orderBy('id', 'desc')
        						->take(1)
        						->pluck('fecha_fin');

        $annio_fin = substr($fecha_fin, 6);
        $mes_fin = substr($fecha_fin, 3, 2);
        $dia_fin = substr($fecha_fin, 0, 2);


        if($fecha_inicio < $fecha && $fecha_fin > $fecha){

        	$monto = DB::table('conceptos_nominas')
        						->where('id_empleado', $id_empleado)
        						->where('id_concepto', 5442)
        						->orderBy('id', 'desc')
        						->take(1)
        						->pluck('monto');

            $b=1;

        }

        if($mes_actual == $mes_fin && $annio_actual == $annio_fin){

            $nomina_actual = DB::table('nomina')->where('tipo', 'empleado')->orderBy('id', 'desc')->take(1)->pluck('id');
        	$nomina_anterior = DB::table('nomina')->where('tipo', 'empleado')->where('id', '<', $nomina_actual)->orderBy('id', 'desc')->take(1)->pluck('id');

            $monto = DB::table('conceptos_nominas')
                            ->where('id_nomina', $nomina_anterior)
                            ->where('id_concepto', 5442)
                            ->where('id_empleado', $id_empleado)
                            ->pluck('monto');
            $b=1;

        }

        if(!$fecha_fin || !$fecha_inicio){
            $monto = $this->transporte_tesoreria_verificar($sueldo, $municipio, $id_nomina, $id_empleado);
            $b=1;
        }

        if($annio_ingreso == $annio_actual){
            if($mes_ingreso == $mes_actual){
                if($dia_ingreso >= 15){
                    $monto = 0;
                    $b=1;
                }
            }
        }

        $diferencia_prima_transporte = DB::table('conceptos_nominas')
                                            ->where('id_empleado', $id_empleado)
                                            ->where('id_concepto', 5474)
                                            ->where('id_nomina', $id_nomina)
                                            ->pluck('monto');

        if($diferencia_prima_transporte > 0){
            $monto = $diferencia_prima_transporte;
        }

        if($jubilado == 1){
            $monto = 0;
            $b=1;
        }

        if($b==0){
            $monto = $this->transporte_tesoreria_verificar($sueldo, $municipio, $id_nomina, $id_empleado);
        }

        return $monto;

    }

    public function responsabilidad_tesoreria($id_empleado){

    	$monto = 0;
        $jubilado = DB::table('datos_laborales')->where('dato_empleado_id', $id_empleado)->where('jubilado', 1)->pluck('jubilado');

        $cargo = DB::table('datos_laborales')
                        ->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
                        ->where('datos_laborales.dato_empleado_id', $id_empleado)
                        ->pluck('cargos.id');

        $sueldo = DB::table('datos_laborales')
                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                        ->where('datos_laborales.dato_empleado_id', $id_empleado)
                        ->pluck('salarios.sueldo');

        if($cargo == 32){
            $monto = $sueldo * 0.20;
        }
        else if($cargo == 25){
            $monto = $sueldo * 0.20;
        }
        else if($cargo >= 36 && $cargo <= 46){
            $monto = $sueldo * 0.10;
        }
        else if($cargo >= 53 && $cargo <= 60){
            $monto = $sueldo * 0.20;
        }
        else if($cargo == 61){
            $monto = $sueldo * 0.30;
        }
        else if($cargo == 39){
            $monto = $sueldo * 0.20;
        }
        else if($cargo == 90){
            $monto = $sueldo * 0.10;
        }

        if($jubilado == 1){
            $monto= 0;
        }

        return $monto;
    }

    public function antiguedad_tesoreria($id_empleado, $sueldo, $fecha_fin){
        $annio_actual = substr($fecha_fin, 6);
        $mes_actual = substr($fecha_fin, 3, 2);
        $dia_actual = substr($fecha_fin, 0, 2);

        $fecha_ingreso = DB::table('datos_laborales')
                            ->where('dato_empleado_id', $id_empleado)
                            ->pluck('fecha_comienzo_laboral');

        $annio_ingreso = substr($fecha_ingreso, 6);
        $mes_ingreso = substr($fecha_ingreso, 3, 2);
        $dia_ingreso = substr($fecha_ingreso, 0, 2);

        $annio = $annio_actual - $annio_ingreso;

        if($mes_actual < $mes_ingreso && $annio > 0)
        {
            $annio--;
        }

        if($mes_actual == $mes_ingreso){
            if($dia_actual <= $dia_ingreso){
                $annio--;
            }
        }

        if($annio <= 0){
            $annio = 0;
        }

        if($annio > 10 && $annio <= 20){
            $annio=11;
        }

        if($annio > 20){
            $annio = 12;
        }

        $monto = $sueldo*(($annio*2)/100);

        return $monto;
    }

    public function profesionalizacion_tesoreria($sueldo, $tipo_carrera){
        
        $monto = 0;

        if($tipo_carrera == 'tsu'){

            $monto = $sueldo*0.12;
            $monto = round($monto, 2);
            return $monto;

        }

        else if($tipo_carrera == 'licenciados'){

            $monto = $sueldo*0.15;
            $monto = round($monto, 2);
            return $monto;

        }

        else if($tipo_carrera == 'especialista'){

            $monto = $sueldo*0.17;
            $monto = round($monto, 2);
            return $monto;

        }

        else if($tipo_carrera == 'magister'){

            $monto = $sueldo*0.18;
            $monto = round($monto, 2);
            return $monto;

        }

        else if($tipo_carrera == 'doctor'){

            $monto = $sueldo*0.20;
            $monto = round($monto, 2);
            return $monto;

        }

        return $monto;
    }

    public function verificar_tesoreria_seguridad_empleado($id_nomina, $id_empleado){

        $sueldo = DB::table('datos_laborales')
                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                        ->where('datos_laborales.dato_empleado_id', $id_empleado)
                        ->pluck('salarios.sueldo');

        $tipo_carrera = DB::table('datos_empleados')->where('id', $id_empleado)->pluck('tipo_carrera');

        $fecha_fin = DB::table('nomina')->where('id', $id_nomina)->pluck('fecha_fin');

        $municipio = DB::table('datos_empleados')->where('id', $id_empleado)->pluck('municipio');


        $prima_profesionalizacion = $this->profesionalizacion_tesoreria($sueldo, $tipo_carrera);
        $prima_antiguedad = $this->antiguedad_tesoreria($id_empleado, $sueldo, $fecha_fin);
        $prima_transporte = $this->transporte_tesoreria($sueldo, $municipio, $id_nomina, $id_empleado);
        $prima_responsabilidad = $this->responsabilidad_tesoreria($id_empleado);

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte + $prima_responsabilidad;
        $asignaciones = ($sueldo + $primas)/2;
        $monto = ($asignaciones*0.03);
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 30172)->update(['referencia' => 4, 'monto' => $monto]);
        DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->where('id_concepto', 20222)->update(['referencia' => 4, 'monto' => $monto]);

    }

    public function mod_nomina($id_nomina){
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
    }

}
