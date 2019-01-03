<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class nominaEmpleadosController extends Controller {

	public function crear_nomina_empleados(Request $request){
	
		    $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $cant_semanas = $request->semanas;
            $quincena = $request->quincena;
            $descripcion = "";
            
            if($quincena == 1){
                $descripcion = "Primera quincena";
            }
            else if($quincena == 2){
                $descripcion = "Segunda quincena";
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

		    $id_nomina = DB::table('nomina')->insertGetId(['tipo' => 'empleado', 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'n_nomina' => $numero_nomina, 'descripcion' => $descripcion]);
        
		    $empleados =  DB::table('datos_empleados')
		                ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
		                ->join('cargos', 'cargos.id', '=', 'datos_laborales.cargo_id')
                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
		                ->where('datos_laborales.tipo_trabajador', '=', 'empleado')
                        ->where('datos_empleados.status', '=', 'activo')
		                ->select('datos_empleados.id','datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.cedula', 'salarios.sueldo', 'cargos.descripcion', 'cargos.id as cargo_id', 'datos_laborales.fecha_comienzo_laboral as fecha_ingreso', 'datos_laborales.fideicomiso', 'datos_empleados.tipo_carrera', 'datos_empleados.municipio')
		                ->get();
        
		    foreach ($empleados as $empleado) {
                
                $this->insertar_sueldo($empleado->id, $id_nomina, $empleado->sueldo);

                $jornada_diurna = $this->jornada_diurna_empleado(1004, $empleado->sueldo);
                 DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 1004, 'id_empleado' => $empleado->id, 'monto' => $jornada_diurna, 'referencia' => 15]);
                
                if($quincena == 2 && $empleado->tipo_carrera != 'NP'){
                    
                    $prima_profesionalizacion = $this->prima_profesionalizacion_empleado(5450, $quincena, $empleado->sueldo, $empleado->tipo_carrera);
                     DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 5450, 'id_empleado' => $empleado->id, 'monto' => $prima_profesionalizacion, 'referencia' => 1]); 
                
                }
                
                if($quincena == 2){

                    $prima_antiguedad = $this->prima_antiguedad_empleado(5471, $empleado->sueldo, $quincena, $empleado->fecha_ingreso, $fecha_fin);
                    if($prima_antiguedad > 0)
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 5471, 'id_empleado' => $empleado->id, 'monto' => $prima_antiguedad, 'referencia' => 1]);    
                }
                
                if($empleado->id == 109){
                    echo $empleado->sueldo;
                }

                $caja_ahorro = $this->caja_ahorro_empleado(20134, $empleado->sueldo);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20134, 'id_empleado' => $empleado->id, 'monto' => $caja_ahorro, 'referencia' => 2]);

                $caja_ahorro_patronal = $this->caja_ahorro_empleado(20134, $empleado->sueldo);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20136, 'id_empleado' => $empleado->id, 'monto' => $caja_ahorro_patronal, 'referencia' => 2]);
                    
                if($empleado->fideicomiso > 0){
                    $fideicomiso = $this->fideicomiso_empleado(20100, $empleado->fideicomiso);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20100, 'id_empleado' => $empleado->id, 'monto' => $fideicomiso, 'referencia' => $fideicomiso]);
                }
            
                $seguro_social = $this->seguro_social_empleado(20111, $cant_semanas, $empleado->sueldo, $quincena, $empleado->municipio, $empleado->fecha_ingreso, $fecha_fin, $empleado->tipo_carrera, $empleado->cargo_id);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20111, 'id_empleado' => $empleado->id, 'monto' => $seguro_social, 'referencia' => $cant_semanas]);

                $descuento = $this->descuentoArticulo($empleado->id);
                if($descuento > 0){
                    //echo $descuento;
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20105, 'id_empleado' => $empleado->id, 'monto' => $descuento, 'referencia' => 1]);
                }
                
                if($quincena == 1){
                    $prima_transporte = $this->prima_transporte_empleado(5440, $empleado->sueldo, $empleado->municipio, $quincena);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 5440, 'id_empleado' => $empleado->id, 'monto' => $prima_transporte, 'referencia' => 1]);

                    $prima_responsabilidad = $this->prima_responsabilidad(5473, $empleado->sueldo, $empleado->cargo_id);
                    DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 5473, 'id_empleado' => $empleado->id, 'monto' => $prima_responsabilidad, 'referencia' => 1]);
                }
                
                $faov = $this->faov_empleado(20121, $quincena, $empleado->sueldo, $empleado->tipo_carrera, $empleado->fecha_ingreso, $fecha_fin, $empleado->municipio, $empleado->cargo_id);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20121, 'id_empleado' => $empleado->id, 'monto' => $faov, 'referencia' => 1]);

                $rpe = $this->regimen_prestacional_empleo_empleado(20131, $quincena, $cant_semanas, $empleado->sueldo, $empleado->tipo_carrera, $empleado->fecha_ingreso, $fecha_fin, $empleado->municipio, $empleado->cargo_id);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20131, 'id_empleado' => $empleado->id, 'monto' => $rpe, 'referencia' => $cant_semanas]);
                
                $ss_patronal = $this->seguro_social_patronal_empleado(30111, $quincena, $cant_semanas, $empleado->sueldo, $empleado->tipo_carrera, $empleado->fecha_ingreso, $fecha_fin, $empleado->municipio, $empleado->cargo_id);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 30111, 'id_empleado' => $empleado->id, 'monto' => $ss_patronal, 'referencia' => $cant_semanas]);
                
                $faov_patronal = $this->faov_patronal_empleado(30121, $quincena, $cant_semanas, $empleado->sueldo, $empleado->tipo_carrera, $empleado->fecha_ingreso, $fecha_fin, $empleado->municipio, $empleado->cargo_id, $empleado->id, $id_nomina);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 30121, 'id_empleado' => $empleado->id, 'monto' => $faov_patronal, 'referencia' => 1]);

                $regimen_patronal = $this->regimen_prestacional_empleo_patronal(30131, $quincena, $cant_semanas, $empleado->sueldo, $empleado->tipo_carrera, $empleado->fecha_ingreso, $fecha_fin, $empleado->municipio, $empleado->cargo_id);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 30131, 'id_empleado' => $empleado->id, 'monto' => $regimen_patronal, 'referencia' => $cant_semanas]);

                $tesoreria_seguridad_social_patronal = $this->tesoreria_seguridad_social_patronal(30172, $quincena, $cant_semanas, $empleado->sueldo, $empleado->tipo_carrera, $empleado->fecha_ingreso, $fecha_fin, $empleado->municipio, $empleado->cargo_id, $id_nomina, $empleado->id);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 30172, 'id_empleado' => $empleado->id, 'monto' => $tesoreria_seguridad_social_patronal, 'referencia' => $cant_semanas]);
                DB::table('conceptos_nominas')->insert(['id_nomina' => $id_nomina, 'id_concepto' => 20222, 'id_empleado' => $empleado->id, 'monto' => $tesoreria_seguridad_social_patronal, 'referencia' => $cant_semanas]);
                
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
        
		   	DB::table('nomina')->where('id', $id_nomina)->update(['asignaciones' => $asignaciones, 'deducciones' => $deducciones, 'patronal' => $patronal]);
        
	       //return redirect()->to('/editar_nomina_empleado_form/'.$id_nomina);

	}

    public function jornada_diurna_empleado($id, $sueldo){
    	if($id == 1004){
            
            $jornada = 15;
            
        	$sueldo_diario = $sueldo/30;
        	$monto = $sueldo_diario*$jornada;

        	$monto = round($monto, 2);

        	return $monto;
        }
    }
    
    public function prima_transporte_empleado($id, $sueldo, $municipio, $quincena){
        
        if($id == 5440){
                
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
        
    }

    public function prima_antiguedad_empleado($id, $sueldo, $quincena, $fecha_ingreso, $fecha_fin){
    	if($id == 5471 && $quincena == 2){

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

    public function prima_profesionalizacion_empleado($id, $quincena, $sueldo, $tipo_carrera){

    	if($id == 5450 && $quincena == 2){
            
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

    public function caja_ahorro_empleado($id, $sueldo){

    	$monto = ($sueldo*0.05)/2;
    	$monto = round($monto, 2);
    	return $monto;
    }

    public function fideicomiso_empleado($id, $fideicomiso){

    	$fideicomiso = round($fideicomiso, 2);

    	return $fideicomiso;
    }
    
    public function seguro_social_empleado($id, $cant_semanas, $sueldo, $quincena, $municipio, $fecha_ingreso, $fecha_fin, $tipo_carrera, $cargo){
        
        if($id == 20111){
            
            $asignacion = 0;
            
            $asignacion = $asignacion + $this->jornada_diurna_empleado(1004, $sueldo);
            
            if($quincena == 1){
            	$asignacion = $asignacion + $this->prima_transporte_empleado(5440, $sueldo, $municipio, $quincena);
            }

            $asignacion = $asignacion + $this->prima_antiguedad_empleado(5471, $sueldo, $quincena, $fecha_ingreso, $fecha_fin);
            $asignacion = $asignacion + $this->prima_profesionalizacion_empleado(5450, $quincena, $sueldo, $tipo_carrera);
            $asignacion = $asignacion + $this->prima_responsabilidad(5473, $sueldo, $cargo, $quincena);            
            
            
            $monto = (($asignacion*12)/52)*0.04*$cant_semanas;
            $monto = round($monto, 2);
            
            return $monto;
        }
        
    }
    
    public function faov_empleado($id, $quincena, $sueldo, $tipo_carrera, $fecha_ingreso, $fecha_fin, $municipio, $cargo){
        
        if($id == 20121){
            
            $asignacion = 0;
            
            if($quincena == 1){
            	$asignacion = $asignacion + $this->prima_transporte_empleado(5440, $sueldo, $municipio, $quincena);
            }

            $asignacion = $asignacion + $this->prima_antiguedad_empleado(5471, $sueldo, $quincena, $fecha_ingreso, $fecha_fin);
            $asignacion = $asignacion + $this->prima_profesionalizacion_empleado(5450, $quincena, $sueldo, $tipo_carrera);
            $asignacion = $asignacion + $this->jornada_diurna_empleado(1004, $sueldo);
            $asignacion = $asignacion + $this->prima_responsabilidad(5473, $sueldo, $cargo, $quincena);

            $monto = $asignacion*0.01;
            
            $monto = round($monto, 2);
            
            return $monto;
            
        }
        
        
    }

    public function regimen_prestacional_empleo_empleado($id, $quincena, $cant_semanas, $sueldo, $tipo_carrera, $fecha_ingreso, $fecha_fin, $municipio, $cargo){

        if($id == 20131){
            
            $asignacion = 0;

            if($quincena == 1){
            	$asignacion = $asignacion + $this->prima_transporte_empleado(5440, $sueldo, $municipio, $quincena);
            }

            $asignacion = $asignacion + $this->prima_antiguedad_empleado(5471, $sueldo, $quincena, $fecha_ingreso, $fecha_fin);
            $asignacion = $asignacion + $this->prima_profesionalizacion_empleado(5450, $quincena, $sueldo, $tipo_carrera);
            $asignacion = $asignacion + $this->jornada_diurna_empleado(1004, $sueldo);
            $asignacion = $asignacion + $this->prima_responsabilidad(5473, $sueldo, $cargo, $quincena);

            $monto = ($asignacion*12)/52*0.005*$cant_semanas;
            $monto = round($monto, 2);

            return $monto;
        }

    }
    
    public function seguro_social_patronal_empleado($id, $quincena, $cant_semanas, $sueldo, $tipo_carrera, $fecha_ingreso, $fecha_fin, $municipio, $cargo){
        
        if($id == 30111){
            
            $asignacion = 0;

            $asignacion = 0;

            if($quincena == 1){
            	$asignacion = $asignacion + $this->prima_transporte_empleado(5440, $sueldo, $municipio, $quincena);
            }
            $asignacion = $asignacion + $this->prima_antiguedad_empleado(5471, $sueldo, $quincena, $fecha_ingreso, $fecha_fin);
            $asignacion = $asignacion + $this->prima_profesionalizacion_empleado(5450, $quincena, $sueldo, $tipo_carrera);
            $asignacion = $asignacion + $this->jornada_diurna_empleado(1004, $sueldo);
            $asignacion = $asignacion + $this->prima_responsabilidad(5473, $sueldo, $cargo, $quincena);
            
            $monto = (((($asignacion*$cant_semanas)*12)/52)*9)/100;
        
            $monto = round($monto, 2);
            return $monto;   
        }
    
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

    public function tesoreria_seguridad_social_patronal($id, $quincena, $cant_semanas, $sueldo, $tipo_carrera, $fecha_ingreso, $fecha_fin, $municipio, $cargo, $id_nomina, $id_empleado){
        if($id == 30172){
            

            $prima_profesionalizacion = $this->profesionalizacion_tesoreria($sueldo, $tipo_carrera);
            $prima_antiguedad = $this->antiguedad_tesoreria($id_empleado, $sueldo, $fecha_fin);
            $prima_transporte = $this->transporte_tesoreria($sueldo, $municipio, $id_nomina, $id_empleado);
            $prima_responsabilidad = $this->responsabilidad_tesoreria($sueldo, $cargo);
            $primas = $prima_profesionalizacion + $prima_antiguedad + $prima_transporte + $prima_responsabilidad;
            $asignacion = ($sueldo + $primas)/2;
            $monto = (($asignacion)*0.03);
            $monto = round($monto, 2);

            /*if($id_empleado == 114){
                echo 'prima profesionalizacion: '.$prima_profesionalizacion."<br>";
                echo 'prima antiguedad: '.$prima_antiguedad."<br>";
                echo 'prima transporte: '.$prima_transporte."<br>";
                echo 'prima responsabilidad: '.$prima_responsabilidad."<br>";
                echo 'monto: '.$monto."<br>";
            }*/

            return $monto;
        }
    }

    public function responsabilidad_tesoreria($sueldo, $cargo){

        $monto = 0;

        if($cargo == 32){
            $monto = $sueldo * 0.20;
        }
        if($cargo >= 36 && $cargo <= 46){
            $monto = $sueldo * 0.10;
        }
        if($cargo >= 53 && $cargo <= 60){
            $monto = $sueldo * 0.20;
        }
        if($cargo == 61){
            $monto = $sueldo * 0.30;
        }
        if($cargo == 39){
            $monto = $sueldo * 0.20;
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


    public function faov_patronal_empleado($id, $quincena, $cant_semanas, $sueldo, $tipo_carrera, $fecha_ingreso, $fecha_fin, $municipio, $cargo, $id_empleado, $id_nomina){
        
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

    public function regimen_prestacional_empleo_patronal($id, $quincena, $cant_semanas, $sueldo, $tipo_carrera, $fecha_ingreso, $fecha_fin, $municipio, $cargo){

        if($id == 30131){
            
            $asignacion = 0;

            if($quincena == 1){
            	$asignacion = $asignacion + $this->prima_transporte_empleado(5440, $sueldo, $municipio, $quincena);
            }
            $asignacion = $asignacion + $this->prima_antiguedad_empleado(5471, $sueldo, $quincena, $fecha_ingreso, $fecha_fin);
            $asignacion = $asignacion + $this->prima_profesionalizacion_empleado(5450, $quincena, $sueldo, $tipo_carrera);
            $asignacion = $asignacion + $this->jornada_diurna_empleado(1004, $sueldo);
            $asignacion = $asignacion + $this->prima_responsabilidad(5473, $sueldo, $cargo, $quincena);

            $monto = ($asignacion*12)/52*0.02*$cant_semanas;
            $monto = round($monto, 2);

            return $monto;
        }

    }

    public function prima_responsabilidad($id, $sueldo, $cargo, $quincena){

        $monto = 0;

        if($id == 5473 && $quincena == 1){
            
            if($cargo == 32){
                $monto = $sueldo * 0.20;
            }
            if($cargo >= 36 && $cargo <= 46){
                $monto = $sueldo * 0.10;
            }
            if($cargo >= 53 && $cargo <= 60){
                $monto = $sueldo * 0.20;
            }
            if($cargo == 61){
                $monto = $sueldo * 0.30;
            }
            if($cargo == 39){
                $monto = $sueldo * 0.20;
            }

            return $monto;

        }

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
    
    public function eliminar_peronal_nomina_empleado($id_empleado, $id_nomina){
        
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
        
        return redirect()->to('/editar_nomina_empleado_view')->with('alert', 'Empleado eliminado de la nomina');
        
    }

    function ingresar_flotante_empleado(Request $request, $id){
        
        $empleado_id = $request->empleado_id;
        $semanas = DB::table('conceptos_nominas')
                        ->where('id_nomina', $id)
                        ->where('id_concepto', '1004')
                        ->pluck('referencia');


        DB::table('conceptos_nominas')->insert(['id_empleado' => $empleado_id, 'id_nomina' => $id]);
        return redirect()->back()->with('alert', 'Empleado insertado');

    }

}
