<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\numerosALetrasController;
use DB;
use PDF;
use Illuminate\Http\Request;

class constanciaController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function generarPDF(Request $request, $id){
		$b=0;
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

		$dia = date('d');
		$mes = $meses[date('n')-1];
		$año = date('Y');

		$primaProfesionalizacion_letras = "";
		$primaProfesionalizacion = 0;
		$primaAntiguedad = 0;
		$primaTransporte = 0;
		$fecha_egreso = 0;
        $sueldo = 0;

        $dia_letras = (new numerosALetrasController)->convertir_a_letras($dia);

		$datos = DB::table('datos_empleados')
					->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
					->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
					->join('salarios', 'datos_laborales.salario_id' ,'=','salarios.id')
					->where('datos_empleados.cedula', $request->cedula)
					->select('datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.cedula', 'datos_laborales.fecha_ingreso', 'cargos.descripcion as cargo_descripcion', 'salarios.sueldo', 'datos_empleados.id as dato_empleado_id', 'datos_empleados.municipio', 'datos_laborales.fecha_egreso', 'datos_empleados.tipo_carrera')
					->get();

		foreach ($datos as $dato) {

            if($id == 1){
                $empleado_id = DB::table('datos_empleados')
                                    ->where('datos_empleados.cedula', $request->cedula)
                                    ->pluck('id');

                $ultima_nomina = DB::table('conceptos_nominas')
                                        ->where('id_empleado', $empleado_id)
                                        ->orderBy('id', 'desc')
                                        ->take(1)
                                        ->pluck('conceptos_nominas.id_nomina');

                $sueldo = DB::table('sueldo_nominas')
                                ->where('id_nomina', $ultima_nomina)
                                ->where('id_empleado', $empleado_id)
                                ->pluck('sueldo');



                if(strpos($sueldo, '.'))
                {
                    $sueldo = substr($sueldo, 0, strpos($sueldo, "."));
                    $sueldo_decimales = substr($sueldo, strpos($sueldo, ".")+1, strlen($sueldo));
                }

                $sueldo_letras = (new numerosALetrasController)->convertir_a_letras($sueldo);

                if(strpos($sueldo, '.')){
                    $sueldo_letras = $sueldo_letras." con ".(new numerosALetrasController)->convertir_a_letras($sueldo_decimales)." céntimos";
                }

                $primaAntiguedad = $this->primaAntiguedad($dato->dato_empleado_id, $sueldo);
                $primaTransporte = $this->primaTransporte($sueldo, $dato->municipio, $dato->dato_empleado_id);
                $primaProfesionalizacion = $this->primaProfesionalizacion($sueldo, $dato->tipo_carrera, $dato->dato_empleado_id);
                if($dato->fecha_egreso != null)
                {
                    $b=1;
                    $fecha_egreso = $dato->fecha_egreso;
                }

                

            }
            else{
                if(strpos($dato->sueldo, '.'))
                {
                    $sueldo = substr($dato->sueldo, 0, strpos($dato->sueldo, "."));
                    $sueldo_decimales = substr($dato->sueldo, strpos($dato->sueldo, ".")+1, strlen($dato->sueldo));
                }
                else{
                    $sueldo = $dato->sueldo;
                }
                $sueldo_letras = (new numerosALetrasController)->convertir_a_letras($sueldo);

                if(strpos($dato->sueldo, '.')){
                    $sueldo_letras = $sueldo_letras." con ".(new numerosALetrasController)->convertir_a_letras($sueldo_decimales)." céntimos";
                }

                $primaAntiguedad = $this->primaAntiguedad($dato->dato_empleado_id, $dato->sueldo);
                $primaTransporte = $this->primaTransporte($dato->sueldo, $dato->municipio, $dato->dato_empleado_id);
                $primaProfesionalizacion = $this->primaProfesionalizacion($dato->sueldo, $dato->tipo_carrera, $dato->dato_empleado_id);
                if($dato->fecha_egreso != null)
                {
                    $b=1;
                    $fecha_egreso = $dato->fecha_egreso;
                }

            }

		}

		$primaAntiguedad_decimales = substr($primaAntiguedad, strpos($primaAntiguedad, ".")+1, strlen($primaAntiguedad));
		$primaAntiguedad_sin_decimales = substr($primaAntiguedad, 0, strpos($primaAntiguedad, "."));
		$primaAntiguedad_letras = (new numerosALetrasController)->convertir_a_letras($primaAntiguedad_sin_decimales);
		if($primaAntiguedad_decimales != null){
				$primaAntiguedad_letras = $primaAntiguedad_letras ." con ".(new numerosALetrasController)->convertir_a_letras($primaAntiguedad_decimales)." céntimos";
		}

		else if($primaAntiguedad_decimales == 1){
			$primaAntiguedad_letras = $primaAntiguedad_letras ." con un centimo";
		}

		$primaTransporte_letras = (new numerosALetrasController)->convertir_a_letras($primaTransporte);


			$primaProfesionalizacion_sin_decimales = substr($primaProfesionalizacion, 0, strpos($primaProfesionalizacion, "."));
			$primaProfesionalizacion_decimales = substr($primaProfesionalizacion, strpos($primaProfesionalizacion, ".")+1, strlen($primaProfesionalizacion));
			$primaProfesionalizacion_letras = (new numerosALetrasController)->convertir_a_letras($primaProfesionalizacion_sin_decimales);
			if($primaProfesionalizacion_decimales != null){
					$primaProfesionalizacion_letras = $primaProfesionalizacion_letras ." con ".(new numerosALetrasController)->convertir_a_letras($primaProfesionalizacion_decimales)." céntimos";
			}

			else if($primaProfesionalizacion_decimales == 1){
				$primaProfesionalizacion_letras = $primaProfesionalizacion_letras ." con un centimo";
			}

		if($id == 1 && $b == 1){

			$html = view('pdf.constancia')->with('datos', $datos)->with('fecha_salida', $fecha_egreso)->with('dia', $dia)->with('mes', $mes)->with('año', $año)->with('primaAntiguedad', $primaAntiguedad)->with('primaTransporte', $primaTransporte)->with('primaProfesionalizacion', $primaProfesionalizacion)->with('primaAntiguedad_letras', $primaAntiguedad_letras)->with('primaTransporte_letras', $primaTransporte_letras)->with('primaProfesionalizacion_letras', $primaProfesionalizacion_letras)->with('primaProfesionalizacion', $primaProfesionalizacion)->with('sueldo_letras', $sueldo_letras)->with('dia_letras', $dia_letras)->with('sueldo', $sueldo)->render();
      return PDF::load($html)->show();
		}

		else{
			$html = view('pdf.constanciaActuales')->with('datos', $datos)->with('primaAntiguedad', $primaAntiguedad)->with('dia', $dia)->with('mes', $mes)->with('año', $año)->with('primaTransporte', $primaTransporte)->with('primaProfesionalizacion', $primaProfesionalizacion)->with('primaAntiguedad_letras', $primaAntiguedad_letras)->with('primaTransporte_letras', $primaTransporte_letras)->with('primaProfesionalizacion_letras', $primaProfesionalizacion_letras)->with('sueldo_letras', $sueldo_letras)->with('dia_letras', $dia_letras)->render();
        return PDF::load($html)->show();
		}

	}

	public function primaAntiguedad($id_empleado, $sueldo){
		  $fecha_actual = getdate();

          $fecha_egreso = DB::table('datos_laborales')
                                ->where('dato_empleado_id', $id_empleado)
                                ->pluck('fecha_egreso');

            if($fecha_egreso == ''){
                $annio_actual =  $fecha_actual['year'];
                $mes_actual = $fecha_actual['mon'];
                $dia_actual = $fecha_actual['mday'];
            }
            else{
                $annio_actual = substr($fecha_egreso, 6);
                $mes_actual = substr($fecha_egreso, 3, 2);
                $dia_actual = substr($fecha_egreso, 0, 2);
            }
    		

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

	public function primaTransporte($sueldo, $municipio, $id_empleado){

        $fecha_egreso = DB::table('datos_laborales')
                                ->where('dato_empleado_id', $id_empleado)
                                ->pluck('fecha_egreso');

        if($fecha_egreso == ''){
            $sueldo_minimo = DB::table('panel_control')->pluck('sueldo_minimo');
            $prima_transporte_cerca = DB::table('panel_control')->pluck('prima_transporte_cerca');
            $prima_transporte_lejos = DB::table('panel_control')->pluck('prima_transporte_lejos');
            $prima_transporte_cerca_minimo = DB::table('panel_control')->pluck('prima_transporte_cerca_minimo');
            $municipio = strtolower($municipio);

            $monto=0.0;

            if($sueldo == $sueldo_minimo && $municipio == "carirubana"){
                $monto = $prima_transporte_cerca_minimo;
            }

            else if($sueldo > $sueldo_minimo && $municipio == "carirubana"){
                $monto = $prima_transporte_cerca;
            }

            else if($municipio != "carirubana"){
                $monto = $prima_transporte_lejos;
            }
        }

        else{

            $monto = DB::table('conceptos_nominas')
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5440)
                            ->orderBy('id', 'desc')
                            ->take(1)
                            ->pluck('monto');

        }
		

        return $monto;

    }

    public function primaProfesionalizacion($sueldo, $tipo_carrera, $id_empleado){

            $monto = 0;

            $fecha_egreso = DB::table('datos_laborales')
                                ->where('dato_empleado_id', $id_empleado)
                                ->pluck('fecha_egreso');

            if($fecha_egreso == ''){

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

            else{
                $monto = DB::table('conceptos_nominas')
                            ->where('id_empleado', $id_empleado)
                            ->where('id_concepto', 5450)
                            ->orderBy('id', 'desc')
                            ->take(1)
                            ->pluck('monto');
            }

            return $monto;
    }


}
