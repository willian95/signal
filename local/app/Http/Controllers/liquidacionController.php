<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use Illuminate\Http\Request;

class liquidacionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{	
		$cedula = $request->cedula;
		$fecha_egreso = $request->fecha_egreso;
		$deposito = $request->deposito;
		$fideicomiso = $request->fideicomiso;

		$prima_transporte = 0;
		$prima_antiguedad = 0;
		$prima_profesionalizacion = 0;
		$salario_basico_mensual = 0;
		$salario_basico_diario = 0;
		$salario_devengado_mensual = 0;
		$salario_devengado_diario = 0;
		$salario_integral = 0;
		$annio = 0;
		$mes = 0;

		DB::table('datos_empleados')
			->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
			->where('datos_empleados.cedula', $cedula)
			->update(['datos_laborales.fecha_egreso' => $fecha_egreso]);

		DB::table('datos_empleados')
			->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
			->where('datos_empleados.cedula', $cedula)
			->update(['datos_empleados.status' => 'inactivo']);

		$datos = DB::table('datos_empleados')
					->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
					->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
					->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
					->where('datos_empleados.cedula', $cedula)
					->select('datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.municipio','datos_laborales.fecha_ingreso', 'cargos.descripcion', 'salarios.sueldo', 'datos_empleados.tipo_carrera', 'datos_empleados.cedula', 'datos_empleados.id')
					->get();
		
		foreach($datos as $dato){

			$prima_profesionalizacion = $this->prima_profesionalizacion($dato->tipo_carrera, $dato->sueldo);
			$prima_antiguedad = $this->prima_antiguedad($fecha_egreso, $dato->fecha_ingreso, $dato->sueldo);
			$prima_transporte = $this->prima_transporte($dato->municipio, $dato->sueldo);
			$salario_basico_mensual = $dato->sueldo;
			$salario_basico_diario = $dato->sueldo/30;
			$salario_devengado_mensual = $dato->sueldo + $prima_antiguedad + $prima_transporte + $prima_profesionalizacion;
			$salario_devengado_diario = $salario_devengado_mensual/30;
			$bono_vacacional = $this->bono_vacacional($dato->fecha_ingreso, $fecha_egreso, $salario_devengado_diario);

			$salario_integral = ((($salario_devengado_diario*120)/12)/30) + $bono_vacacional;

			$mes = $this->mes_servicio($dato->fecha_ingreso, $fecha_egreso);
			$annio = $this->annio_servicio($dato->fecha_ingreso, $fecha_egreso);
			$dias_prestaciones = 0;
			$dias_utilidades = 0;
			$dias_bono_vacacional_fraccionado = ($mes * ((15 + $annio) -1))/12;
			$alicuota_bono_vacacional = ((15 + $annio) + 30)/360 * ($salario_devengado_diario/30);  

			if($mes > 6)
                $dias_prestaciones = ($annio + 1)*30;
            else
                $dias_prestaciones = $annio*30;

            $meses_cancelar = 0;

			 	if(substr($fecha_egreso, 3, 2) == '01'){
					$meses_cancelar = 1;
				}

				else if(substr($fecha_egreso, 3, 2) == '02'){
					$meses_cancelar = 2;
				}

				else if(substr($fecha_egreso, 3, 2) == '03'){
					$meses_cancelar = 3;
				}

				else if(substr($fecha_egreso, 3, 2) == '04'){
					$meses_cancelar = 4;
				}

				else if(substr($fecha_egreso, 3, 2) == '05'){
					$meses_cancelar = 5;
				}

				else if(substr($fecha_egreso, 3, 2) == '06'){
					$meses_cancelar = 6;
				}

				else if(substr($fecha_egreso, 3, 2) == '07'){
					$meses_cancelar = 7;
				}

				else if(substr($fecha_egreso, 3, 2) == '08'){
					$meses_cancelar = 8;
				}

				else if(substr($fecha_egreso, 3, 2) == '09'){
					$meses_cancelar = 9;
				}

				else if(substr($fecha_egreso, 3, 2) == '10'){
					$meses_cancelar = 10;
				}

				else if(substr($fecha_egreso, 3, 2) == '11'){
					$meses_cancelar = 11;
				}

				else if(substr($fecha_egreso, 3, 2) == '12'){
					$meses_cancelar = 12;
				}

			$dias_utilidades = 10 * ($meses_cancelar);
            
			$dias_bono_disfrute_vacacional_fraccionado = ($mes * 30)/12;
			$vacaciones_fraccionado_dias = $dias_bono_disfrute_vacacional_fraccionado;

			$fecha_actual = getdate();
			$annio_actual =  $fecha_actual['year'];
			$mes_actual = $fecha_actual['mon'];
			$dia_actual = $fecha_actual['mday'];

			DB::table('liquidaciones')
				->insert([
					'dato_empleado_id' => $dato->id,
					'fecha_registro' => $dia_actual.'/'.$mes_actual.'/'.$annio_actual,
					'fecha_egreso' => $fecha_egreso,
					'salario_integral' => $salario_integral,
					'salario_devengado_mensual' => $salario_devengado_mensual,
					'mes_servicio' => $mes,
					'annio_servicio' => $annio,
					'dias_prestaciones' => $dias_prestaciones,
					'alicuota_bono_vacacional' => $alicuota_bono_vacacional,
					'utilidades_salario' => $salario_integral - $bono_vacacional,
					'utilidades_dias' => $dias_utilidades,
					'bono_disfrute_vacacional_fraccionado_dias' => $vacaciones_fraccionado_dias,
					'vacaciones_fraccionado_dia' => $vacaciones_fraccionado_dias,
					'bono_vacacional_fraccionado_dia' => $dias_bono_vacacional_fraccionado,
					'reintegro_cuotas_fideicomiso' => $fideicomiso,
					'depositado_banco_mercantil' => $deposito
				]);

		}

		$html = view('pdf.liquidaciones')->with('datos', $datos)->with('salario_basico_mensual', $salario_basico_mensual)->with('salario_basico_diario', $salario_basico_diario)->with('salario_devengado_mensual', $salario_devengado_mensual)->with('salario_devengado_diario', $salario_devengado_diario)->with('salario_integral', $salario_integral)->with('fecha_egreso', $fecha_egreso)->with('mes', $mes)->with('annio', $annio)->with('bono_vacacional', $bono_vacacional)->with('dias_utilidades',$dias_utilidades)->with('dias_bono_disfrute_vacacional_fraccionado', $vacaciones_fraccionado_dias)->with('dias_vacaciones_fraccionado', $vacaciones_fraccionado_dias)->with('dias_bono_vacacional_fraccionado', $dias_bono_vacacional_fraccionado)->with('alicuota_bono_vacacional', $alicuota_bono_vacacional)->with('fideicomiso', $fideicomiso)->with('deposito', $deposito)->render();
    	return PDF::load($html)->show();
		
	}

	public function prima_profesionalizacion($tipo_carrera, $sueldo){
		$monto = 0;
		if($tipo_carrera != 'NP'){

				if($tipo_carrera == 'larga'){
            
                	$monto = $sueldo*0.15;
                	$monto = round($monto, 2);
            
            	}
            
            	else if($tipo_carrera == 'corta'){
                
                	$monto = $sueldo*0.12;
                	$monto = round($monto, 2);
                
            	}

				return $monto;

		}
	}

	public function prima_antiguedad($fecha_egreso, $fecha_ingreso, $sueldo){

    	$annio_egreso = substr($fecha_egreso, 6);
    	$mes_egreso = substr($fecha_egreso, 3, 2);
        $dia_egreso = substr($fecha_egreso, 0, 2);
            
    	$annio_ingreso = substr($fecha_ingreso, 6);
    	$mes_ingreso = substr($fecha_ingreso, 3, 2);
        $dia_ingreso = substr($fecha_ingreso, 0, 2);
            
    	$annio = $annio_egreso - $annio_ingreso;
            
    	if($mes_egreso < $mes_ingreso && $annio > 0)
        {
            $annio--;
        }
            
        else if($mes_egreso == $mes_ingreso){
            if($dia_egreso <= $dia_ingreso){
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
            
    	$monto = round($monto, 2);
    	return $monto;

	}

	public function prima_transporte($municipio, $sueldo){
		$monto = 0;
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

	public function bono_vacacional($fecha_ingreso, $fecha_egreso, $salario_diario_devengado){
		$monto = 0;
    	$annio_egreso =  substr($fecha_egreso, 6);
        $mes_egreso = substr($fecha_egreso, 3, 2);
                                
        $annio_ingreso = substr($fecha_ingreso, 6);
        $mes_ingreso = substr($fecha_ingreso, 3, 2);
                                
    	$annio = $annio_egreso - $annio_ingreso;
                                
        if($mes_egreso < $mes_ingreso && $annio > 0)
        {
            $annio--;
        }           
                                
        else if($annio <=0){
            $annio = 0;
        }

        $annio = 15 + $annio;
    	$monto = (((($annio + 30)-1)/360)*$salario_diario_devengado) + $salario_diario_devengado;
		$monto = round($monto, 2);
		
		return $monto;
		
	}

	public function annio_servicio($fecha_ingreso, $fecha_egreso){
		
		$annio_egreso =  substr($fecha_egreso, 6);
        $mes_egreso = substr($fecha_egreso, 3, 2);
                                
        $annio_ingreso = substr($fecha_ingreso, 6);
        $mes_ingreso = substr($fecha_ingreso, 3, 2);
                                
    	$annio = $annio_egreso - $annio_ingreso;
                                
        if($mes_egreso < $mes_ingreso && $annio > 0)
        {
            $annio--;
        }           
                                
        else if($annio <= 0){
            $annio = 0;
        }

		return $annio;
	
	}

	public function mes_servicio($fecha_ingreso, $fecha_egreso){
        
		$mes_egreso = substr($fecha_egreso, 3, 2);
        $mes_ingreso = substr($fecha_ingreso, 3, 2);
        $mes = 0;

		if($mes_egreso > $mes_ingreso){
			$mes = $mes_egreso - $mes_ingreso;
		}

		else if($mes_egreso < $mes_ingreso){
			$mes = $mes_ingreso - $mes_egreso;
		}

		return $mes;		
	}

	public function salario_devengado_mensual($id_empleado, $fecha_egreso){

		$mes_egreso = substr($fecha_egreso, 3, 2);
		$annio_egreso =  substr($fecha_egreso, 6);

		$salario_devengado_mensual = DB::table('nomina')
										->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
										->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
										->where('conceptos.tipo', 'asignacion')
										->where('nomina.fecha_inicio', 'like', '%'.$mes_egreso.'/'.$annio_egreso.'%')
										->sum('conceptos_nominas.monto');

		return $salario_devengado_mensual;

	}

	public function delete($id){
		$dato_empleado_id = DB::table('liquidaciones')
								->where('id', $id)
								->pluck('dato_empleado_id');

		DB::table('liquidaciones')
				->where('id', $id)
				->delete();

		DB::table('datos_empleados')
			->where('id', $dato_empleado_id)
			->update(['status' => 'activo']);
		
		return redirect()->back()->with('alert', 'Liquidación eliminada');

	}

	public function verificar_liquidacion($id){
		
		$liquidacion = DB::table('liquidaciones')
							->join('datos_empleados', 'liquidaciones.dato_empleado_id', '=', 'datos_empleados.id')
							->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=','datos_empleados.id')
							->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
							->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
							->where('liquidaciones.id', $id)
							->get();
		
		$html = view('pdf.verificar_liquidacion')->with('liquidaciones', $liquidacion)->render();
		return PDF::load($html)->show();

	}

}
