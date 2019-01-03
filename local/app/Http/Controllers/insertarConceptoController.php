<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class insertarConceptoController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function insertar_concepto(Request $request, $id_nomina, $id_empleado){

        $concepto = $request->concepto;
        $referencia = $request->referencia;
        $fecha_fin = DB::table('nomina')
                        ->where('id', $id_nomina)
                        ->pluck('fecha_fin');

        $verificar = DB::table('conceptos_nominas')
                        ->where('id_nomina', $id_nomina)
                        ->where('id_empleado', $id_empleado)
                        ->where('id_concepto', $request->concepto)
                        ->get();

        $empleados = DB::table('datos_empleados')
                            ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                            ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                            ->where('datos_empleados.id', $id_empleado)
                            ->select('salarios.sueldo', 'datos_empleados.tipo_carrera', 'datos_laborales.fideicomiso', 'datos_empleados.municipio')
                            ->get();

        if($verificar != null){
            return redirect()->to('/editar_personal_obrero_nomina/'.$id_empleado.'/nomina/'.$id_nomina)->with('alert', 'Error, concepto previamente asignado');
        }

        else{

            foreach($empleados as $empleado){

                if($concepto == 1004){

                    $this->jornada_diurno_obrero($referencia, $empleado->sueldo, $id_empleado, $id_nomina);
                    $this->mod_nomina($id_nomina);

                }

                else if($concepto == 1002){

                    $this->dias_descanso_obrero($referencia, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 1003){

                    $this->dias_descanso_oficiales_obrero($referencia, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 1005){

                    $this->jornada_nocturna_obrero($referencia, $id_nomina, $id_empleado, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 1006){

                    $this->jornada_mixta_obrero($referencia, $id_nomina, $id_empleado, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 1015){

                    $this->dias_remunerados_reposo_obrero($id_nomina, $id_empleado, $empleado->sueldo, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5400){
                    $this->horas_extras_obrero($referencia, $empleado->sueldo, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if ($concepto == 5402) {
                    $this->horas_extras_mixtas_obrero($referencia, $empleado->sueldo, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5410){
                    $this->horas_extras_nocturnas_obrero($referencia, $id_nomina, $id_empleado, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5430){
                    $this->dias_feriados_obrero($id_nomina, $id_empleado, $empleado->sueldo, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5440){
                    $this->prima_transporte_obrero($empleado->sueldo, $empleado->municipio, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5441){
                    $this->prima_transporte_reposo_medico_obrero($empleado->sueldo, $empleado->municipio, $id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5442){
                    $this->prima_transporte_vacaciones($empleado->sueldo, $empleado->municipio, $id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5450){
                    $this->prima_profesionalizacion_obrero($empleado->sueldo, $empleado->tipo_carrera, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5454){
                    $this->prima_profesionalizacion_reposo_obrero($empleado->sueldo, $empleado->tipo_carrera, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5460){
                    $this->otras_asignaciones_obrero($referencia, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5461){
                    $this->diferencia_sueldo_obrero($referencia, $id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5471){
                    $this->prima_antiguedad_obrero($id_empleado, $id_nomina, $empleado->sueldo, $fecha_fin);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5472){
                    $this->prima_antiguedad_reposo_obrero($id_empleado, $id_nomina, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 5474){
                    $this->diferencia_prima_transporte($id_empleado, $id_nomina, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20100){
                    $this->fideicomiso_obrero($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20105){

                    $this->descuento_articulo($id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);

                }

                else if($concepto == 20106){
                    $this->prestamo_compra_obrero($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20108){
                    $this->reintegro_obrero($id_nomina, $id_empleado, $referencia);
                }

                else if($concepto == 20111){
                    $this->seguro_social_obrero($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20121){
                    $this->faov_obrero($id_nomina, $id_empleado);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20222){
                    $this->tesoreria_seguridad_social_obreros($id_nomina, $id_empleado, $referencia, $fecha_fin, $empleado->tipo_carrera, $empleado->sueldo, $empleado->municipio);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20131){
                    $this->regimen_prestacional_empleo($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20134){
                    $this->caja_ahorro_obrero($id_nomina, $id_empleado, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 20136){
                    $this->caja_ahorro_patronal_obrero($id_nomina, $id_empleado, $empleado->sueldo);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 30111){
                    $this->seguro_social_patronal_obrero($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 30121){
                    $this->faov_patronal_obrero($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 30131){
                    $this->regimen_prestacional_empleo_patronal($id_nomina, $id_empleado, $referencia);
                    $this->mod_nomina($id_nomina);
                }

                else if($concepto == 30172){
                    //$this->tesoreria_seguridad_social_patronal($id_nomina, $id_empleado, $referencia);
                    $this->tesoreria_seguridad_social_obreros_patronal($id_nomina, $id_empleado, $referencia, $fecha_fin, $empleado->tipo_carrera, $empleado->sueldo, $empleado->municipio);
                    $this->mod_nomina($id_nomina);
                }

                //return redirect()->to('/editar_personal_obrero_nomina/'.$id_empleado.'/nomina/'.$id_nomina)->with('alert', 'Concepto insertado');

            }

        }

    }

    public function jornada_diurno_obrero($referencia, $sueldo, $id_empleado, $id_nomina){

        $sueldo_diario = $sueldo/30;
        $monto = $sueldo_diario*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 1004, 'id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => $referencia, 'monto' => $monto]);

        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);

    }

    public function dias_descanso_obrero($referencia, $id_nomina, $id_empleado){

        $cargo = DB::table('datos_laborales')
                        ->where('dato_empleado_id', $id_empleado)
                        ->pluck('cargo_id');

        $asignaciones = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->whereBetween('id_concepto', [1004, 5430])
                            ->sum('monto');

        $dias_laborados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto',1004)
                            ->pluck('referencia');

        $dias_laborados_mixta = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto',1006)
                                    ->pluck('referencia');

        $dias_laborados_nocturna = DB::table('conceptos_nominas')
                                    ->where('id_nomina', $id_nomina)
                                    ->where('id_empleado', $id_empleado)
                                    ->where('id_concepto',1005)
                                    ->pluck('referencia');

        if($cargo == 79){
            $diferencia_sueldo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5461)
                                        ->pluck('monto');

            $asignaciones = $diferencia_sueldo + $asignaciones;
        }



        $monto = ($asignaciones/($dias_laborados + $dias_laborados_mixta + $dias_laborados_nocturna))*$referencia;
        $monto = round($monto, 2);

        /*echo "asignaciones: ".$asignaciones."<br>";
        echo "dias laborados: ".$dias_laborados."<br>";
        echo "referencia: ".$referencia."<br>";
        echo "monto: ".$monto."<br>";*/

        DB::table('conceptos_nominas')->insert( ['referencia' => $referencia, 'monto' => $monto, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'id_concepto' => 1002]);

        /*$this->verificar_dias_descanso($id_nomina, $id_empleado);
        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        */
    }

    public function verificar_dias_descanso($id_nomina, $id_empleado){

        $cargo = DB::table('datos_laborales')
                        ->where('dato_empleado_id', $id_empleado)
                        ->pluck('cargo_id');

        $asignaciones = DB::table('conceptos_nominas')
                                ->where('id_nomina', $id_nomina)
                                ->where('id_empleado', $id_empleado)
                                ->whereBetween('id_concepto', [1004, 5430])
                                ->sum('monto');

        $referencia = DB::table('conceptos_nominas')
                                ->where('id_nomina', $id_nomina)
                                ->where('id_empleado', $id_empleado)
                                ->where('id_concepto', 1002)
                                ->sum('referencia');

        if($cargo == 79){
            $diferencia_sueldo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5461)
                                        ->pluck('monto');

            $asignaciones = $diferencia_sueldo + $asignaciones;
        }


        $monto = ($asignaciones/5)*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')
            ->where('id_nomina', $id_nomina)
            ->where('id_empleado', $id_empleado)
            ->where('id_concepto', 1002)
            ->update(['monto' => $monto]);

    }

    public function dias_feriados_obrero($id_nomina, $id_empleado, $sueldo, $referencia){

        $sueldo_diario = $sueldo/30;
        $monto = $sueldo_diario * 1.5 * $referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['referencia' => $referencia, 'monto' => $monto, 'id_concepto' => 5430, 'id_empleado' => $id_empleado, 'id_nomina' => $id_nomina]);

        $this->verificar_dias_descanso($id_nomina, $id_empleado);
        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_dias_descanso_oficiales_obrero($id_nomina, $id_empleado);

    }

    public function horas_extras_obrero($referencia, $sueldo, $id_nomina, $id_empleado){

    		$sueldo_diario = $sueldo/30;
    		$sueldo_hora = $sueldo_diario/8;
    		$extras = $sueldo_hora*0.5;

    		$monto = $extras + $sueldo_hora;

    		$monto = $monto * $referencia;

   			$monto = round($monto, 2);

            DB::table('conceptos_nominas')->insert(['id_concepto' => 5400, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_dias_descanso_oficiales_obrero($id_nomina, $id_empleado);

    }

    public function horas_extras_nocturnas_obrero($referencia, $id_nomina, $id_empleado, $sueldo){

        $sueldo_diario = $sueldo/30;
        $monto = (((($sueldo_diario+($sueldo_diario*0.30))/7)*0.50)+(($sueldo_diario+($sueldo_diario*0.30))/7))*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 5410, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

        $this->verificar_dias_descanso($id_nomina, $id_empleado);
        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_dias_descanso_oficiales_obrero($id_nomina, $id_empleado);

    }

    public function prima_transporte_obrero($sueldo, $municipio, $id_nomina, $id_empleado){

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

        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);


    }

    function prima_transporte_reposo_medico_obrero($sueldo, $municipio, $id_nomina, $id_empleado, $referencia){
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

        if($referencia > 0){
            $monto = ($monto/30) * $referencia;
        }

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => $referencia, 'id_concepto' => 5441, 'monto' => $monto]);

        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
    }

    public function diferencia_sueldo_obrero($referencia, $id_nomina, $id_empleado){
        DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => $referencia, 'id_concepto' => 5461, 'monto' => $referencia]);

        $this->verificar_dias_descanso($id_nomina, $id_empleado);
        $this->verificar_dias_descanso_oficiales_obrero($id_nomina, $id_empleado);
        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);

    }

    public function prima_profesionalizacion_obrero($sueldo, $tipo_carrera, $id_nomina, $id_empleado){

        $monto = 0;

        if($tipo_carrera != 'NP'){

            if($tipo_carrera == 'larga'){

                $monto = $sueldo*0.15;

            }

            else if($tipo_carrera == 'corta'){

                $monto = $sueldo*0.12;

            }


            $monto = round($monto, 2);

            DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => 1, 'id_concepto' => 5450, 'monto' => $monto]);

            $this->verificar_faov_obrero($id_nomina, $id_empleado);
            $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
            $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
            $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
            $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
            $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
            $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
            $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);

        }

    }

    public function prima_profesionalizacion_reposo_obrero($sueldo, $tipo_carrera, $id_nomina, $id_empleado){

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

                $this->verificar_faov_obrero($id_nomina, $id_empleado);
                $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
                $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
                $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
                $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
                $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
                $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
                $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);

            }

    }

    public function seguro_social_obrero($id_nomina, $id_empleado, $referencia){

        $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');

        $horas_extras_diurnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5400)
                            ->sum('monto');

        $horas_extras_mixtas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5402)
                            ->sum('monto');

        $horas_extras_nocturnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5410)
                            ->sum('monto');

        $dias_feriados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5430)
                            ->sum('monto');

        $reintegro = DB::table('conceptos_nominas')
                        ->where('id_nomina', $id_nomina)
                        ->where('id_empleado', $id_empleado)
                        ->where('id_concepto', 20108)
                        ->sum('monto');
       


        $resta = $horas_extras_diurnas + $horas_extras_mixtas + $horas_extras_nocturnas + $dias_feriados + $reintegro;

        $asignaciones = $asignaciones - $resta;
         //echo "asignaciones: ".$asignaciones."<br>";

        $monto = ((($asignaciones*$referencia)*12)/52)*0.04;
        $monto = round($monto, 2);

        //echo $asignaciones;

       DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => $referencia, 'id_concepto' => 20111, 'monto' => $monto]);

    }

    public function horas_extras_mixtas_obrero($referencia, $sueldo, $id_nomina, $id_empleado){

        $sueldo_diario = $sueldo/30;
        $monto = ((((($sueldo_diario/8)*4.5)+(((($sueldo_diario + ($sueldo_diario*0.30))/7)*3)))/(7.5))*0.5)+ (((($sueldo_diario/8)*4.5)+(((($sueldo_diario + ($sueldo_diario*0.30))/7)*3)))/(7.5));
        $monto = $monto * $referencia;
        $monto = round($monto, 2);
        DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => $referencia, 'id_concepto' => 5402, 'monto' => $monto]);

        $this->verificar_dias_descanso($id_nomina, $id_empleado);
        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_dias_descanso_oficiales_obrero($id_nomina, $id_empleado);

    }

    public function prima_antiguedad_obrero($id_empleado, $id_nomina, $sueldo, $fecha_fin){

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

        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);

    }

    public function fideicomiso_obrero($id_nomina, $id_empleado, $referencia){

        DB::table('datos_laborales')->where('dato_empleado_id')->update(['fideicomiso' => $referencia]);

        DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => $referencia, 'id_concepto' => 20100, 'monto' => $referencia]);

    }

    public function faov_obrero($id_nomina, $id_empleado){

        $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');

        $reintegro = DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 20108)->pluck('monto');

        $asignaciones = $asignaciones - $reintegro;

        $monto = $asignaciones*0.01;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => 1, 'id_concepto' => 20121, 'monto' => $monto]);

    }

    public function caja_ahorro_obrero($id_nomina, $id_empleado, $sueldo){

    	$monto = ($sueldo*0.05)/4;
    	$monto = round($monto, 2);

        DB::table('conceptos_nominas')
                ->insert(['id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'id_concepto' => 20134, 'referencia' => 1, 'monto' => $monto]);

    }

    public function caja_ahorro_patronal_obrero($id_nomina, $id_empleado, $sueldo){

        $monto = ($sueldo*0.05)/4;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')
                ->insert(['id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'id_concepto' => 20136, 'referencia' => 1, 'monto' => $monto]);

    }

    public function seguro_social_patronal_obrero($id_nomina, $id_empleado, $referencia){

        $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');

        $horas_extras_diurnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5400)
                            ->sum('monto');

        $horas_extras_mixtas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5402)
                            ->sum('monto');

        $horas_extras_nocturnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5410)
                            ->sum('monto');

        $dias_feriados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5430)
                            ->sum('monto');

        $reintegro = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 20108)
                            ->sum('monto');

        $resta = $horas_extras_diurnas + $horas_extras_mixtas + $horas_extras_nocturnas + $dias_feriados + $reintegro;

        $asignaciones = $asignaciones - $resta;

        $monto = (((($asignaciones*$referencia)*12)/52)*9)/100;

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')
                ->insert(['id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'id_concepto' => 30111, 'referencia' => $referencia, 'monto' => $monto]);

    }

    public function faov_patronal_obrero($id_nomina, $id_empleado, $referencia){
        $actual = getdate();

        $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');


        $monto = ($asignaciones)*0.02*$referencia;

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')
                ->insert(['id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'id_concepto' => 30121, 'referencia' => $referencia, 'monto' => $monto]);

    }


    public function verificar_faov_obrero($id_nomina, $id_empleado){

        $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos_nominas.id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');

        $reintegro = DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 20108)->pluck('monto');
        $asignaciones = $asignaciones - $reintegro;

        $monto = $asignaciones*0.01;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 20121)
                                        ->update(['monto' => $monto]);

        DB::table('conceptos_nominas')
            ->where('id_nomina', $id_nomina)
            ->where('id_empleado', $id_empleado)
            ->where('id_concepto', 30121)
            ->update(['monto' => $monto*2]);

    }

    public function jornada_nocturna_obrero($referencia, $id_nomina, $id_empleado, $sueldo){

        	$sueldo_diario = $sueldo/30;
            $monto =(((($sueldo_diario)*30)/100)+($sueldo_diario))* $referencia;

        	$monto = round($monto, 2);

        	$id = DB::table('conceptos_nominas')->insertGetId(['monto' => $monto, 'id_concepto' => 1005, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'id_nomina' => $id_nomina]);

            $this->verificar_dias_descanso($id_nomina, $id_empleado);
            $this->verificar_faov_obrero($id_nomina, $id_empleado);
            $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
            $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
            $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
            $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
            $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
            $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
    }

    public function verificar_seguro_social_obrero($id_nomina, $id_empleado){

        //if($id == 20111)

        $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');

        $horas_extras_diurnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5400)
                            ->sum('monto');

        $horas_extras_mixtas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5402)
                            ->sum('monto');

        $horas_extras_nocturnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5410)
                            ->sum('monto');

        $dias_feriados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5430)
                            ->sum('monto');

        $reintegro = DB::table('conceptos_nominas')
                        ->where('id_nomina', $id_nomina)
                        ->where('id_empleado', $id_empleado)
                        ->where('id_concepto', 20108)
                        ->sum('monto');

        $resta = $horas_extras_diurnas + $horas_extras_mixtas + $horas_extras_nocturnas + $dias_feriados + $reintegro;

        $asignaciones = $asignaciones - $resta;


         $referencia = DB::table('conceptos_nominas')
                        ->where('id_nomina',$id_nomina)
                        ->where('id_empleado', $id_empleado)
                        ->where('id_concepto', 20111)
                        ->pluck('referencia');


        $monto = ((($asignaciones)*12)/52)*0.04*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')
            ->where('id_nomina', $id_nomina)
            ->where('id_empleado', $id_empleado)
            ->where('id_concepto', 20111)
            ->update(['monto' => $monto]);

    }

    public function verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado){

        $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');

        $horas_extras_diurnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5400)
                            ->sum('monto');

        $horas_extras_mixtas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5402)
                            ->sum('monto');

        $horas_extras_nocturnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5410)
                            ->sum('monto');

        $dias_feriados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5430)
                            ->sum('monto');

        $reintegro = DB::table('conceptos_nominas')
                        ->where('id_nomina', $id_nomina)
                        ->where('id_empleado', $id_empleado)
                        ->where('id_concepto', 20108)
                        ->sum('monto');

        $resta = $horas_extras_diurnas + $horas_extras_mixtas + $horas_extras_nocturnas + $dias_feriados + $reintegro;

        $asignaciones = $asignaciones - $resta;


         $referencia = DB::table('conceptos_nominas')
                        ->where('id_nomina',$id_nomina)
                        ->where('id_empleado', $id_empleado)
                        ->where('id_concepto', 20111)
                        ->pluck('referencia');

        $monto = (((($asignaciones*$referencia)*12)/52)*9)/100;
        $monto = round($monto, 2);

       DB::table('conceptos_nominas')
            ->where('id_nomina', $id_nomina)
            ->where('id_empleado', $id_empleado)
            ->where('id_concepto', 30111)
            ->update(['monto' => $monto]);
    }

    public function verificar_regimen_prestacional_empleo($id_nomina, $id_empleado){

        $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');

        $horas_extras_diurnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5400)
                            ->sum('monto');

        $horas_extras_mixtas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5402)
                            ->sum('monto');

        $horas_extras_nocturnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5410)
                            ->sum('monto');

        $dias_feriados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5430)
                            ->sum('monto');

        $reintegro = DB::table('conceptos_nominas')
                        ->where('id_nomina', $id_nomina)
                        ->where('id_empleado', $id_empleado)
                        ->where('id_concepto', 20108)
                        ->sum('monto');

        $resta = $horas_extras_diurnas + $horas_extras_mixtas + $horas_extras_nocturnas + $dias_feriados + $reintegro;

        $asignaciones = $asignaciones - $resta;

         $referencia = DB::table('conceptos_nominas')
                        ->where('id_nomina',$id_nomina)
                        ->where('id_empleado', $id_empleado)
                        ->where('id_concepto', 20131)
                        ->pluck('referencia');

        $id_concepto_nomina = DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 20131)->pluck('id');
        $referencia = DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 20131)->pluck('referencia');

        $monto = (($asignaciones*12)/52)*$referencia*0.005;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id', $id_concepto_nomina)->update(['referencia' => $referencia, 'monto' => $monto]);

    }

    public function jornada_mixta_obrero($referencia, $id_nomina, $id_empleado, $sueldo){

        $sueldo_diario = $sueldo/30;
        $monto = ((((($sueldo_diario/8)*4.5)+(((($sueldo_diario + ($sueldo_diario*0.30))/7)*3)))/(7.5))*7.5)*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 1006, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

        $this->verificar_dias_descanso($id_nomina, $id_empleado);
        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);

    }

    public function dias_remunerados_reposo_obrero($id_nomina, $id_empleado, $sueldo, $referencia){

        $sueldo_diario = $sueldo/30;
        $monto = $sueldo_diario*$referencia*0.3333;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 1015, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => 1, 'monto' => $monto]);

        $this->verificar_dias_descanso($id_nomina, $id_empleado);
        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);

    }

    public function regimen_prestacional_empleo($id_nomina, $id_empleado, $referencia){

         $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');

        $horas_extras_diurnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5400)
                            ->sum('monto');

        $horas_extras_mixtas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5402)
                            ->sum('monto');

        $horas_extras_nocturnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5410)
                            ->sum('monto');

        $dias_feriados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5430)
                            ->sum('monto');

        $reintegro = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 20108)
                            ->sum('monto');

        $resta = $horas_extras_diurnas + $horas_extras_mixtas + $horas_extras_nocturnas + $dias_feriados + $reintegro;

        $asignaciones = $asignaciones - $resta;

        $monto = ($asignaciones*12)/52*0.005*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 20131, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

    }

    public function diferencia_prima_transporte($id_empleado, $id_nomina, $referencia){

        DB::table('conceptos_nominas')->insert(['id_concepto' => 5474, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => 1, 'monto' => $referencia]);

        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);

    }

    public function regimen_prestacional_empleo_patronal($id_nomina, $id_empleado, $referencia){

        $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');

        $horas_extras_diurnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5400)
                            ->sum('monto');

        $horas_extras_mixtas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5402)
                            ->sum('monto');

        $horas_extras_nocturnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5410)
                            ->sum('monto');

        $dias_feriados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5430)
                            ->sum('monto');

        $reintegro = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 20108)
                            ->sum('monto');

        $resta = $horas_extras_diurnas + $horas_extras_mixtas + $horas_extras_nocturnas + $dias_feriados + $reintegro;

        $asignaciones = $asignaciones - $resta;

        $monto = ((($asignaciones*12)/52)*$referencia)*0.02;

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 30131, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

    }

    public function verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado){

        $asignaciones = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->sum('conceptos_nominas.monto');

        $horas_extras_diurnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5400)
                            ->sum('monto');

        $horas_extras_mixtas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5402)
                            ->sum('monto');

        $horas_extras_nocturnas = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5410)
                            ->sum('monto');

        $dias_feriados = DB::table('conceptos_nominas')
                            ->where('id_nomina', $id_nomina)
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5430)
                            ->sum('monto');

        $reintegro = DB::table('conceptos_nominas')
                        ->where('id_nomina', $id_nomina)
                        ->where('id_empleado', $id_empleado)
                        ->where('id_concepto', 20108)
                        ->sum('monto');

        $referencia = DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 20131)->pluck('referencia');

        $resta = $horas_extras_diurnas + $horas_extras_mixtas + $horas_extras_nocturnas + $dias_feriados + $reintegro;

        $asignaciones = $asignaciones - $resta;

        $monto = ((($asignaciones*12)/52)*0.02)*$referencia;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 30131)->update(['referencia' => $referencia, 'monto' => $monto]);

    }

    public function dias_descanso_oficiales_obrero($referencia, $id_nomina, $id_empleado){

        $jornada = 0;
        $monto = 0;

        $dias_jornada_diurna = DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1004)->pluck('referencia');
        $dias_jornada_mixta = DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1006)->pluck('referencia');
        $dias_jornada_nocturna = DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1005)->pluck('referencia');

        $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1004)->pluck('monto');
        $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1005)->pluck('monto');
        $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1006)->pluck('monto');
        $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5430)->pluck('monto');
        $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5400)->pluck('monto');
        $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5402)->pluck('monto');
        $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5410)->pluck('monto');

        $dias = $dias_jornada_diurna + $dias_jornada_mixta + $dias_jornada_nocturna;

        $monto = ($jornada/$dias) * $referencia;

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 1003, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_empleado, $id_nomina);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);

    }

    public function verificar_dias_descanso_oficiales_obrero($id_nomina, $id_empleado){

        $jornada = 0;
        $monto = 0;

        $referencia = DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1003)->pluck('referencia');

        if($referencia == 2){

            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1004)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1005)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1006)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5430)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5400)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5402)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5410)->pluck('monto');
            $jornada = $jornada + $diferencia_sueldo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5461)
                                        ->pluck('monto');

            $monto = ($jornada/5)*$referencia;

        }

        else if($referencia == 3){

            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1004)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1005)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1006)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5430)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5400)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5402)->pluck('monto');
            $jornada = $jornada + DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 5410)->pluck('monto');
            $jornada = $jornada + $diferencia_sueldo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5461)
                                        ->pluck('monto');

            $monto = ($jornada/4)*$referencia;

        }

        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 1003)->update(['monto' => $monto]);


    }

    public function otras_asignaciones_obrero($referencia, $id_nomina, $id_empleado){

        DB::table('conceptos_nominas')->insert(['id_concepto' => 5460, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $referencia]);

        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_empleado, $id_nomina);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);

    }

    public function tesoreria_seguridad_social_obreros_patronal($id_nomina, $id_empleado, $referencia, $fecha_fin, $tipo_carrera, $sueldo, $municipio){

        $prima_profesionalizacion = $this->profesionalizacion_tesoreria($sueldo, $tipo_carrera);
        $prima_antiguedad = $this->antiguedad_tesoreria($id_empleado, $sueldo, $fecha_fin);
        $prima_transporte = $this->transporte_tesoreria($sueldo, $municipio, $id_nomina, $id_empleado);

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte;
        $asignaciones = $sueldo + $primas;
        $monto = ($asignaciones*0.03)/4;
        $monto = round($monto, 2);
        
        DB::table('conceptos_nominas')->insert(['id_concepto' => 20222, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

        DB::table('conceptos_nominas')->insert(['id_concepto' => 30172, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

    }

    public function tesoreria_seguridad_social_obreros($id_nomina, $id_empleado, $referencia, $fecha_fin, $tipo_carrera, $sueldo, $municipio){

        $sueldo = DB::table('datos_laborales')
                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                        ->where('datos_laborales.dato_empleado_id', $id_empleado)
                        ->pluck('salarios.sueldo');

        $prima_profesionalizacion = $this->profesionalizacion_tesoreria($sueldo, $tipo_carrera);
        $prima_antiguedad = $this->antiguedad_tesoreria($id_empleado, $sueldo, $fecha_fin);
        $prima_transporte = $this->transporte_tesoreria($sueldo, $municipio, $id_nomina, $id_empleado);

        $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte;
        $asignaciones = $sueldo + $primas;
        $monto = ($asignaciones*0.03)/4;
        $monto = round($monto, 2);

        /*echo "Prima profesionalizacion: ".$prima_profesionalizacion."<br>";
        echo "Prima antiguedad: ".$prima_antiguedad."<br>";
        echo "Prima transporte: ".$prima_transporte."<br>";
        echo "Monto: ".$monto."<br>";*/

        DB::table('conceptos_nominas')->insert(['id_concepto' => 20222, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);
        DB::table('conceptos_nominas')->insert(['id_concepto' => 30172, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $monto]);

    }

    function prima_transporte_vacaciones($sueldo, $municipio, $id_nomina, $id_empleado, $referencia){
        $municipio = strtolower($municipio);
        $monto = 0;
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

        $prima = ($monto/30)*$referencia;

        $prima = round($prima, 2);

        DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => 1, 'id_concepto' => 5442, 'monto' => $prima]);

        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
    }

    public function verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado){
        
        $fecha_fin = DB::table('nomina')
                        ->where('id', $id_nomina)
                        ->pluck('fecha_fin');

        $sueldo = DB::table('datos_laborales')
                            ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                            ->where('datos_laborales.dato_empleado_id', $id_empleado)
                            ->pluck('salarios.sueldo');

        $municipio = DB::table('datos_empleados')
                            ->where('id', $id_empleado)
                            ->pluck('municipio');

        $tipo_carrera = DB::table('datos_empleados')
                            ->where('id', $id_empleado)
                            ->pluck('tipo_carrera');

    	$referencia_1 = DB::table('conceptos_nominas')
    						->where('id_empleado', $id_empleado)
    						->where('id_nomina', $id_nomina)
    						->where('id_concepto', 30172)
    						->pluck('referencia');

    	$referencia_2 = DB::table('conceptos_nominas')
    						->where('id_empleado', $id_empleado)
    						->where('id_nomina', $id_nomina)
    						->where('id_concepto', 20222)
    						->pluck('referencia');

        $sueldo = DB::table('datos_laborales')
                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                        ->where('datos_laborales.dato_empleado_id', $id_empleado)
                        ->pluck('salarios.sueldo');


        /*$prima_profesionalizacion = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5450)
                                        ->sum('monto');

        $prima_profesionalizacion_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5454)
                                        ->sum('monto');*/
        $prima_antiguedad = $this->antiguedad_tesoreria($id_empleado, $sueldo, $fecha_fin);

        $prima_profesionalizacion = $this->profesionalizacion_tesoreria($sueldo, $tipo_carrera);

        $prima_antiguedad = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5471)
                                        ->sum('monto');

        /*$prima_antiguedad_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5472)
                                        ->sum('monto');

        $prima_transporte_reposo = DB::table('conceptos_nominas')
                                        ->where('id_nomina', $id_nomina)
                                        ->where('id_empleado', $id_empleado)
                                        ->where('id_concepto', 5441)
                                        ->sum('monto');*/

        $prima_transporte = $this->transporte_tesoreria($sueldo, $municipio, $id_nomina, $id_empleado);

        /*$diferencia_prima_transporte = DB::table('conceptos_nominas')
                                            ->where('id_nomina', $id_nomina)
                                            ->where('id_empleado', $id_empleado)
                                            ->where('id_concepto', 5474)
                                            ->sum('monto');*/

        //$primas = $prima_profesionalizacion + $prima_profesionalizacion_reposo + $prima_antiguedad + $prima_antiguedad_reposo + $prima_transporte_reposo + $prima_transporte + $diferencia_prima_transporte;
        $primas = $prima_profesionalizacion + $prima_antiguedad  +  $prima_transporte;
        $asignaciones = $sueldo + $primas;
        $monto = ($asignaciones*0.03)/4;
        $monto = round($monto, 2);

        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 30172)->update(['referencia' => $referencia_1, 'monto' => $monto]);
        DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 20222)->update(['referencia' => $referencia_2, 'monto' => $monto]);


    }

    public function prestamo_compra_obrero($id_nomina, $id_empleado, $referencia){

         DB::table('conceptos_nominas')->insert(['id_concepto' => 20106, 'id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'referencia' => $referencia, 'monto' => $referencia]);

    }

    public function descuento_articulo($id_nomina, $id_empleado){

        $cuota = DB::table('descuentos')->where('id_empleado', $id_empleado)->pluck('cuota');
        $deuda_actual = DB::table('descuentos')->where('id_empleado', $id_empleado)->pluck('deuda_actual');

        if($deuda_actual > $cuota){
            $deuda_actual = $deuda_actual - $cuota;
        }

        else{
            $cuota = $deuda_actual;
            $deuda_actual = $deuda_actual - $cuota;

            $deuda_actual = 0;
        }

        $cuota = round($cuota, 2);

        DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_empleado' => $id_empleado, 'id_concepto' => 20105, 'referencia' => $cuota, 'monto' => $cuota]);
        DB::table('descuentos')->where('id_empleado', $id_empleado)->update(['deuda_actual' => $deuda_actual]);


    }

    public function prima_antiguedad_reposo_obrero($id_empleado, $id_nomina, $sueldo){

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

            if($annio >=10){
                $annio=10;
            }

            $monto = $sueldo*($annio/100);

            $monto = $monto*0.3333;

            $monto = round($monto, 2);

        DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => 1, 'id_concepto' => 5472, 'monto' => $monto]);

        $this->verificar_faov_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_obrero($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo($id_nomina, $id_empleado);
        $this->verificar_regimen_prestacional_empleo_patronal($id_nomina, $id_empleado);
        $this->verificar_tesoreria_seguridad_social_patronal($id_nomina, $id_empleado);
        $this->verificar_seguro_social_patronal_obrero($id_nomina, $id_empleado);

    }

    public function reintegro_obrero($id_nomina, $id_empleado, $referencia){

        DB::table('conceptos_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'referencia' => $referencia, 'id_concepto' => 20108, 'monto' => $referencia]);

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

            $nomina_actual = DB::table('nomina')->where('tipo', 'obrero')->orderBy('id', 'desc')->take(1)->pluck('id');
            $nomina_anterior = DB::table('nomina')->where('tipo', 'obrero')->where('id', '<', $nomina_actual)->orderBy('id', 'desc')->take(1)->pluck('id');

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
