<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class nominaController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function crear_nomina_obreros(Request $request){
		    $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $cant_semanas = $request->semanas;
            $semana = $request->semana;
            $descripcion = "";

            if($semana == 1){
                $descripcion = "Primera semana";
            }
            else if($semana == 2){
                $descripcion = "Segunda semana";
            }
            else if($semana == 3){
                $descripcion = "Tercera semana";
            }
            else if($semana == 4){
                $descripcion = "Cuarta semana";
            }
            else if($semana == 5){
                $descripcion = "Quinta semana";
            }

            $dia = substr($fecha_inicio, 0, 2);
            $mes = substr($fecha_inicio, 3, 2);
            $annio = substr($fecha_inicio, 6);

            //$cant_semanas = $this->cantSemanas($mes + 0, $annio + 0);
            $numero_nomina = 0;

            if($request->reiniciar == 'on'){
                $numero_nomina = 1;
            }
            else{
                $n_nomina = DB::table('nomina')
                            ->where('tipo', 'obrero')
                            ->orderBy('id', 'desc')
                            ->pluck('n_nomina');
                $numero_nomina = $n_nomina + 1;
            }


		    $id_nomina = DB::table('nomina')->insertGetId(['tipo' => 'obrero', 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'n_nomina' =>$numero_nomina, 'descripcion' => $descripcion]);

		    $obreros =  DB::table('datos_empleados')
		                ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
		                ->join('cargos', 'cargos.id', '=', 'datos_laborales.cargo_id')
                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
		                ->where('datos_laborales.tipo_trabajador', '=', 'obrero')
                        ->where('datos_empleados.status', '=', 'activo')
		                ->select('datos_empleados.id','datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.cedula', 'salarios.sueldo', 'cargos.descripcion', 'cargos.id as cargo_id', 'datos_laborales.fecha_comienzo_laboral as fecha_ingreso', 'datos_laborales.fideicomiso', 'datos_empleados.tipo_carrera', 'datos_empleados.municipio')
		                ->get();

		    foreach ($obreros as $obrero) {


                /*if($obrero->id == 106){
                    echo "aqui"."<br>";
                    echo "aqui"."<br>";
                }*/

                $this->insertar_sueldo($obrero->id, $id_nomina, $obrero->sueldo);

                $dias_descanso = $this->dias_descanso_obrero(1002, $obrero->sueldo, $obrero->cargo_id);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 1002, 'id_empleado' => $obrero->id, 'monto' => $dias_descanso, 'referencia' => 2]);

                $jornada_diurna = $this->jornada_diurna_obrero(1004, $obrero->sueldo);
                 DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 1004, 'id_empleado' => $obrero->id, 'monto' => $jornada_diurna, 'referencia' => 5]);

                 /*if($obrero->id == 91){
                 	echo "<br>";
                 	echo "<br>";
                 	echo "<br>";
                 	echo "Aqui";
                 }*/

                if($semana == 1){

                    $prima_transporte = $this->prima_transporte_obrero(5440, $obrero->sueldo, $obrero->municipio, $semana);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 5440, 'id_empleado' => $obrero->id, 'monto' => $prima_transporte, 'referencia' => 1]);


                }

                if($semana == 4 && $obrero->tipo_carrera != 'NP'){

                    $prima_profesionalizacion = $this->prima_profesionalizacion_obrero(5450, $semana, $obrero->sueldo, $obrero->tipo_carrera);
                     DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 5450, 'id_empleado' => $obrero->id, 'monto' => $prima_profesionalizacion, 'referencia' => 1]);

                }

                if($semana == 3){
                    $prima_antiguedad = $this->prima_antiguedad_obrero(5471, $obrero->sueldo, $semana, $obrero->fecha_ingreso, $fecha_fin);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 5471, 'id_empleado' => $obrero->id, 'monto' => $prima_antiguedad, 'referencia' => 1]);
                }

                if($obrero->fideicomiso > 0){
                    $fideicomiso = $this->fideicomiso_obrero(20100, $obrero->fideicomiso);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20100, 'id_empleado' => $obrero->id, 'monto' => $fideicomiso, 'referencia' => $fideicomiso]);
                }

                $a = $this->sinDescuento(20111, $obrero->id);
                if($a == 0){
                    $seguro_social = $this->seguro_social_obrero(20111, $cant_semanas, $obrero->sueldo, $semana, $obrero->municipio, $obrero->fecha_ingreso, $fecha_fin, $obrero->tipo_carrera, $obrero->cargo_id);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20111, 'id_empleado' => $obrero->id, 'monto' => $seguro_social, 'referencia' => $cant_semanas]);
                }

				$descuento = $this->descuentoArticulo($obrero->id);
				if($descuento > 0 && $semana < 5){
					//echo $descuento;
					DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20105, 'id_empleado' => $obrero->id, 'monto' => $descuento, 'referencia' => 1]);
				}

                $b = $this->sinDescuento(20121, $obrero->id);
                if($b == 0){
                    $faov = $this->faov_obrero(20121, $semana, $obrero->sueldo, $obrero->tipo_carrera, $obrero->fecha_ingreso, $fecha_fin, $obrero->municipio, $obrero->cargo_id);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20121, 'id_empleado' => $obrero->id, 'monto' => $faov, 'referencia' => 1]);
                }

                $c = $this->sinDescuento(20131, $obrero->id);
                if($c == 0){
                    $rpe = $this->regimen_prestacional_empleo_obrero(20131, $semana, $cant_semanas, $obrero->sueldo, $obrero->tipo_carrera, $obrero->fecha_ingreso, $fecha_fin, $obrero->municipio, $obrero->cargo_id);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20131, 'id_empleado' => $obrero->id, 'monto' => $rpe, 'referencia' => $cant_semanas]);
                }

                $caja_ahorro = $this->caja_ahorro_obrero(20134, $obrero->sueldo);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20134, 'id_empleado' => $obrero->id, 'monto' => $caja_ahorro, 'referencia' => 4]);

                $caja_ahorro_patronal = $this->caja_ahorro_obrero(20134, $obrero->sueldo);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20136, 'id_empleado' => $obrero->id, 'monto' => $caja_ahorro_patronal, 'referencia' => 4]);

                $d = $this->sinDescuento(30111, $obrero->id);
                if($d == 0){
                    $ss_patronal = $this->seguro_social_patronal_obrero(30111, $cant_semanas, $obrero->sueldo, $semana, $obrero->municipio, $obrero->fecha_ingreso, $fecha_fin, $obrero->tipo_carrera, $obrero->cargo_id);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 30111, 'id_empleado' => $obrero->id, 'monto' => $ss_patronal, 'referencia' => 4]);
                }

                $e = $this->sinDescuento(30121, $obrero->id);
                if($e == 0){
                    $faov_patronal = $this->faov_patronal_obrero(30121, $id_nomina, $obrero->id);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 30121, 'id_empleado' => $obrero->id, 'monto' => $faov_patronal, 'referencia' => 1]);
                }

                $f = $this->sinDescuento(30131, $obrero->id);
                if($f == 0){
                    $regimen_prestacional_empleo_patronal = $this->regimen_prestacional_empleo_patronal(30131, $semana, $cant_semanas, $obrero->sueldo, $obrero->tipo_carrera, $obrero->fecha_ingreso, $fecha_fin, $obrero->municipio, $obrero->cargo_id);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 30131, 'id_empleado' => $obrero->id, 'monto' => $regimen_prestacional_empleo_patronal, 'referencia' => 4]);
                }

                if($semana < 5){
                    $tesoreria_seguridad_patronal = $this->tesoreria_seguridad_social_patronal(30172, $semana, $cant_semanas, $obrero->sueldo, $obrero->tipo_carrera, $obrero->fecha_ingreso, $fecha_fin, $obrero->municipio, $obrero->cargo_id, $obrero->id, $id_nomina);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 30172, 'id_empleado' => $obrero->id, 'monto' => $tesoreria_seguridad_patronal, 'referencia' => $cant_semanas]);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20222, 'id_empleado' => $obrero->id, 'monto' => $tesoreria_seguridad_patronal, 'referencia' => $cant_semanas]);
                }

                /*if($obrero->id == 106){
                    echo "<br>";
                    echo "<br>";
                }*/
                
		    }

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

            $asignaciones = round($asignaciones, 2);
            $deducciones = round($deducciones, 2);
            $patronal = round($patronal, 2);

		   	DB::table('nomina')->where('id', $id_nomina)->update(['asignaciones' => $asignaciones, 'deducciones' => $deducciones, 'patronal' => $patronal]);

		  return redirect()->to('/editar_nomina_obrero_form/'.$id_nomina);

	}

    function numberOfWeek ($dia, $mes, $ano) {

        //generamos la fecha para el día 1 del mes y año especificado
        $fecha = mktime (0, 0, 0, $mes, 1, $ano);

        $numberOfWeek = ceil (($dia + (date ("w", $fecha)-1)) / 7);

        return $numberOfWeek;
    }

    public function jornada_diurna_obrero($id, $sueldo){
    	if($id == 1004){

            $jornada = 5;

        	$sueldo_diario = $sueldo/30;
        	$monto = $sueldo_diario*$jornada;

        	$monto = round($monto, 2);

        	return $monto;
        }
    }

    public function prima_transporte_obrero($id, $sueldo, $municipio, $semana){

        if($id == 5440){

            if($semana == 1){

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
                 /*echo "sueldo: ".$sueldo."<br>";
                echo "sueldo minimo: ".$sueldo_minimo."<br>";
                echo "municipio: ".$municipio."<br>";
                echo $monto."<br>";*/
               	return $monto;
            }


        }

    }

    public function prima_antiguedad_obrero($id, $sueldo, $semana, $fecha_ingreso, $fecha_fin){

        if($id == 5471 && $semana == 3){

    		$annio_actual = substr($fecha_fin, 6);
            $mes_actual = substr($fecha_fin, 3, 2);
            $dia_actual = substr($fecha_fin, 0, 2);

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
    		return $monto;
    	}
    }

    public function prima_profesionalizacion_obrero($id, $semana, $sueldo, $tipo_carrera){

    	if($id == 5450 && $semana == 4){

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

    	}

    }

    public function caja_ahorro_obrero($id, $sueldo){

    	$monto = ($sueldo*0.05)/4;
    	$monto = round($monto, 2);
    	return $monto;
    }

    public function fideicomiso_obrero($id, $fideicomiso){

    	$fideicomiso = round($fideicomiso, 2);

    	return $fideicomiso;
    }

    public function dias_descanso_obrero($id, $sueldo){

        if($id==1002){

            $sueldo_diario = $sueldo/30;
            $dias_laborados = 5;

                $monto = $sueldo_diario*2;

            $monto = round($monto, 2);

            return $monto;
        }

    }

    public function sinDescuento($id_concepto, $id_empleado){
        $b=0;
        $casos_particulares = DB::table('casos_particulares')->get();
        foreach ($casos_particulares as $caso_particular) {
            if($caso_particular->concepto_id == $id_concepto && $caso_particular->dato_empleado_id == $id_empleado){
                $b = 1;
                break;
            }
        }

        return $b;
    }

    public function seguro_social_obrero($id, $cant_semanas, $sueldo, $semana, $municipio, $fecha_ingreso, $fecha_fin, $tipo_carrera, $cargo){

        if($id == 20111){

            $dias_laborados = 5;

            $asignacion = 0;
            $asignacion = $asignacion + $this->prima_transporte_obrero(5440, $sueldo, $municipio, $semana);
            $asignacion = $asignacion + $this->prima_antiguedad_obrero(5471, $sueldo, $semana, $fecha_ingreso, $fecha_fin);
            $asignacion = $asignacion + $this->prima_profesionalizacion_obrero(5450, $semana, $sueldo, $tipo_carrera);
            $asignacion = $asignacion + $this->dias_descanso_obrero(1002, $sueldo);
            $asignacion = $asignacion + $this->jornada_diurna_obrero(1004, $sueldo);

            $monto = (($asignacion*12)/52)*0.04*$cant_semanas;
            $monto = round($monto, 2);

            return $monto;
        }

    }

    public function faov_obrero($id, $semana, $sueldo, $tipo_carrera, $fecha_ingreso, $fecha_fin, $municipio, $cargo){

        if($id == 20121){

            $asignacion = 0;
            $asignacion = $asignacion + $this->prima_transporte_obrero(5440, $sueldo, $municipio, $semana);
            $asignacion = $asignacion + $this->prima_antiguedad_obrero(5471, $sueldo, $semana, $fecha_ingreso, $fecha_fin);
            $asignacion = $asignacion + $this->prima_profesionalizacion_obrero(5450, $semana, $sueldo, $tipo_carrera);
            $asignacion = $asignacion + $this->dias_descanso_obrero(1002, $sueldo);
            $asignacion = $asignacion + $this->jornada_diurna_obrero(1004, $sueldo);

            $monto = $asignacion*0.01;

            $monto = round($monto, 2);

            return $monto;

        }


    }

    public function regimen_prestacional_empleo_obrero($id, $semana, $cant_semanas, $sueldo, $tipo_carrera, $fecha_ingreso, $fecha_fin, $municipio, $cargo){

        if($id == 20131){

            $asignacion = 0;

            $asignacion = $asignacion + $this->prima_transporte_obrero(5440, $sueldo, $municipio, $semana);
            $asignacion = $asignacion + $this->prima_antiguedad_obrero(5471, $sueldo, $semana, $fecha_ingreso, $fecha_fin);
            $asignacion = $asignacion + $this->prima_profesionalizacion_obrero(5450, $semana, $sueldo, $tipo_carrera);
            $asignacion = $asignacion + $this->dias_descanso_obrero(1002, $sueldo, 88);
            $asignacion = $asignacion + $this->jornada_diurna_obrero(1004, $sueldo);

            $monto = ((($asignacion*12)/52)*$cant_semanas)*0.005;
            $monto = round($monto, 2);

            return $monto;
        }

    }

    public function seguro_social_patronal_obrero($id, $cant_semanas, $sueldo, $semana, $municipio, $fecha_ingreso, $fecha_fin, $tipo_carrera, $cargo){

        if($id == 30111){
            $asignacion = 0;

            $asignacion = $asignacion + $this->prima_transporte_obrero(5440, $sueldo, $municipio, $semana);
            $asignacion = $asignacion + $this->prima_antiguedad_obrero(5471, $sueldo, $semana, $fecha_ingreso, $fecha_fin);
            $asignacion = $asignacion + $this->prima_profesionalizacion_obrero(5450, $semana, $sueldo, $tipo_carrera);
            $asignacion = $asignacion + $this->dias_descanso_obrero(1002, $sueldo, 88);
            $asignacion = $asignacion + $this->jornada_diurna_obrero(1004, $sueldo);

            $monto = (((($asignacion*$cant_semanas)*12)/52)*9)/100;

            $monto = round($monto, 2);
            return $monto;
        }

    }

    public function tesoreria_seguridad_social_patronal($id, $semana, $cant_semanas, $sueldo, $tipo_carrera, $fecha_ingreso, $fecha_fin, $municipio, $cargo, $empleado_id, $id_nomina){
        if($id == 30172){

            $asignacion = 0;

            $prima_transporte = $asignacion + $this->transporte_tesoreria($sueldo, $municipio, $id_nomina, $empleado_id);
            $prima_antiguedad = $asignacion + $this->antiguedad_tesoreria($empleado_id, $sueldo, $fecha_fin);
            $prima_profesionalizacion = $asignacion + $this->profesionalizacion_tesoreria($sueldo, $tipo_carrera);

            $primas = $prima_transporte + $prima_antiguedad + $prima_profesionalizacion;
            $asignacion = $sueldo + $primas;

            echo "prima transporte: ".$prima_transporte."<br>";
            echo "prima antiguedad: ".$prima_antiguedad."<br>";
            echo "prima profesionalizacion: ".$prima_profesionalizacion."<br>";
            echo "sueldo: ".$sueldo."<br>";

            //echo "asignacion: ".$asignacion."<br>";

            //$monto = (($asignacion)*0.03)/4;
            $monto = (($asignacion)*0.03)/4;
            $monto = round($monto, 2);

            return $monto;
        }
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

    public function faov_patronal_obrero($id, $id_nomina, $id_empleado){

        if($id == 30121){

            $faov = DB::table('conceptos_nominas')
                            ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                            ->where('conceptos_nominas.id_empleado', $id_empleado)
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos_nominas.id_concepto', 20121)
                            ->pluck('conceptos_nominas.monto');


            $monto = ($faov)*2;

            $monto = round($monto, 2);
            return $monto;
        }

    }

    public function regimen_prestacional_empleo_patronal($id, $semana, $cant_semanas, $sueldo, $tipo_carrera, $fecha_ingreso, $fecha_fin, $municipio, $cargo){

        if($id == 30131){

            $asignacion = 0;

            $asignacion = $asignacion + $this->prima_transporte_obrero(5440, $sueldo, $municipio, $semana);
            $asignacion = $asignacion + $this->prima_antiguedad_obrero(5471, $sueldo, $semana, $fecha_ingreso, $fecha_fin);
            $asignacion = $asignacion + $this->prima_profesionalizacion_obrero(5450, $semana, $sueldo, $tipo_carrera);
            $asignacion = $asignacion + $this->dias_descanso_obrero(1002, $sueldo, 88);
            $asignacion = $asignacion + $this->jornada_diurna_obrero(1004, $sueldo);

            $monto = ($asignacion*12)/52*0.02*$cant_semanas;
            $monto = round($monto, 2);

            return $monto;
        }

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

		public function descuentoArticulo($id_empleado){

			$monto = DB::table('descuentos')->where('id_empleado', $id_empleado)->pluck('cuota');
			$deuda_actual = DB::table('descuentos')->where('id_empleado', $id_empleado)->pluck('deuda_actual');

			if($deuda_actual > 0){
				if($monto < $deuda_actual)
					$deuda_actual = $deuda_actual - $monto;

				else
					{
						$monto = $deuda_actual;
						$deuda_actual = 0;
					}
			}

			DB::table('descuentos')->where('id_empleado', $id_empleado)->update(['deuda_actual' => $deuda_actual]);
			$monto = round($monto, 2);

			return $monto;

		}

    public function insertar_sueldo($id_empleado, $id_nomina, $sueldo){

        DB::table('sueldo_nominas')->insert(['id_empleado' => $id_empleado, 'id_nomina' => $id_nomina, 'sueldo' => $sueldo]);

    }

    public function eliminar_peronal_nomina_obrero($id_empleado, $id_nomina){
        $deuda_actual = 0;
        $deuda_actual = DB::table('descuentos')->where('id_empleado', $id_empleado)->pluck('deuda_actual');

        if($deuda_actual > 0){

            $cuota = DB::table('conceptos_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->where('id_concepto', 20105)->pluck('monto');
            $deuda_actual = DB::table('descuentos')->where('id_empleado', $id_empleado)->pluck('deuda_actual');
            $deuda_actual = $deuda_actual + $cuota;

            DB::table('descuentos')->where('id_empleado', $id_empleado)->update(['deuda_actual' => $deuda_actual]);

        }

        DB::table('conceptos_nominas')->where('id_empleado', $id_empleado)->where('id_nomina', $id_nomina)->delete();

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

        return redirect()->to('/editar_nomina_obrero_view')->with('alert', 'Empleado eliminado de la nomina');

    }

        function ingresar_flotante_obrero(Request $request, $id){
        $empleado_id = $request->empleado_id;
        $dias = DB::table('conceptos_nominas')
                        ->where('id_nomina', $id)
                        ->where('id_concepto', '1004')
                        ->pluck('referencia');

        $sueldo = DB::table('datos_laborales')
                                ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                                ->where('datos_laborales.dato_empleado_id', $empleado_id)
                                ->pluck('sueldo');

            $sueldo_diario = $sueldo/30;
            $monto = $sueldo_diario*$dias;


        DB::table('conceptos_nominas')->insert(['id_empleado' => $empleado_id, 'id_nomina' => $id, 'id_concepto' => 1004, 'referencia' => $dias]);
        return redirect()->back()->with('alert', 'Obrero insertado');

    }

}
