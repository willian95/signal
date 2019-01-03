<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use DB;
use Illuminate\Http\Request;

class vacacionesController extends Controller {

	public function buscar(Request $request){

		$fecha_actual = getdate();
    	$annio_actual =  $fecha_actual['year'];
    	$mes_actual = $fecha_actual['mon'];
        $dia_actual = $fecha_actual['mday'];

		$datos = DB::table('datos_empleados')
					->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
					->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
					->where('datos_empleados.cedula', $request->cedula)
					->select('datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.cedula', 'datos_laborales.fecha_ingreso', 'cargos.descripcion')
					->get();

        if($datos != null){
            foreach($datos as $dato){
                $annio_ingreso = substr($dato->fecha_ingreso, 6);
                $mes_ingreso = substr($dato->fecha_ingreso, 3, 2);
                $dia_ingreso = substr($dato->fecha_ingreso, 0, 2);

                $servicio = $annio_actual - $annio_ingreso;
                
                if($mes_actual < $mes_ingreso && $annio_actual > 0)
                {
                    $servicio--;
                }
                
                else if($mes_actual == $mes_ingreso){
                    if($dia_actual <= $dia_ingreso){
                        $servicio--;
                    }
                }

                else if($servicio <=0){
                    $servicio = 0;
                }

                if($servicio > 15){
                    $servicio = 30;
                }

            }

            return view('crear_vacaciones', ['datos' => $datos, 'servicio' => $servicio]);
        }

        else{
            return redirect()->back()->with('alert', 'No existe ese empleado');
        }

	}

    public function create(Request $request){

        $cedula = $request->cedula;
        $dias_vacaciones = $request->dias;
        $bono_vacacional = $request->bono_vacacional;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_final;
        $servicio = $request->servicio;

        $fecha_actual = getdate();
        $annio_actual =  $fecha_actual['year'];
        $mes_actual = $fecha_actual['mon'];
        $dia_actual = $fecha_actual['mday'];

        $fecha_registro = $dia_actual.'/'.$mes_actual.'/'.$annio_actual;

        $datos_empleados = DB::table('datos_empleados')->where('cedula', $cedula)
	                            ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
	                            ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
	                            ->select('datos_empleados.id', 'datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.profesion', 'datos_empleados.municipio', 'datos_empleados.tipo_carrera', 'salarios.sueldo', 'datos_laborales.fecha_ingreso')
	                            ->get();

	    $cargo = DB::table('datos_empleados')->where('cedula', $cedula)
	                            ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
	                            ->pluck('cargo_id');

	    $sueldo = DB::table('datos_empleados')->where('cedula', $cedula)
	                            ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
	                            ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
	                            ->pluck('salarios.sueldo');

        $prima_transporte = 0;

        $prima_antiguedad = 0;

        $prima_profesionalizacion = 0;

        foreach ($datos_empleados as $dato_empleado) {

            $salario_minimo = DB::table('panel_control')->pluck('sueldo_minimo');

            if($dato_empleado->sueldo == $salario_minimo && strtolower($dato_empleado->municipio) == "carirubana"){
                    $prima_transporte = DB::table('panel_control')->pluck('prima_transporte_cerca_minimo');
                }

                else if($dato_empleado->sueldo > $salario_minimo && strtolower($dato_empleado->municipio) == "carirubana"){
                    $prima_transporte = DB::table('panel_control')->pluck('prima_transporte_cerca');
                }

                else if(strtolower($dato_empleado->municipio) != "carirubana"){
                    $prima_transporte = DB::table('panel_control')->pluck('prima_transporte_lejos');
                }

                $prima_antiguedad = $this->prima_antiguedad_empleado($dato_empleado->sueldo, $dato_empleado->fecha_ingreso);

                $prima_profesionalizacion = 0;

                if($dato_empleado->tipo_carrera == 'tsu'){

	                $monto = $dato_empleado->sueldo*0.12;
	                $prima_profesionalizacion = round($monto, 2);

	            }

	            else if($dato_empleado->tipo_carrera == 'licenciados'){

	                $monto = $dato_empleado->sueldo*0.15;
	                $prima_profesionalizacion = round($monto, 2);

	            }

	            else if($dato_empleado->tipo_carrera == 'especialista'){

	                $monto = $dato_empleado->sueldo*0.17;
	                $prima_profesionalizacion = round($monto, 2);

	            }

	            else if($dato_empleado->tipo_carrera == 'magister'){

	                $monto = $dato_empleado->sueldo*0.18;
	                $prima_profesionalizacion = round($monto, 2);

	            }

	            else if($dato_empleado->tipo_carrera == 'doctor'){

	                $monto = $dato_empleado->sueldo*0.20;
	                $prima_profesionalizacion = round($monto, 2);
	                
	            }
        

	        $prima_responsabilidad = $this->prima_responsabilidad($cargo, $sueldo);
            $diario = (($dato_empleado->sueldo + $prima_antiguedad + $prima_transporte + $prima_profesionalizacion + $prima_responsabilidad) /30);

            $pagar_dias_vacaciones = $diario * $dias_vacaciones;
            $pagar_bono_vacacional = $diario * $bono_vacacional;

            $total = $pagar_dias_vacaciones + $pagar_bono_vacacional;


            $empleado_id = DB::table('datos_empleados')->where('cedula', $cedula)->pluck('id');
            $isCaso = DB::table('casos_particulares')->where('dato_empleado_id', $empleado_id)->where('concepto_id', 20121)->get();

            if(count($isCaso) <= 0)
            {
                $faov = $total*0.01;
                $faov = round($faov, 2);
            }
            else{
                $faov = 0;
            }

            DB::table('vacaciones')->insert(['dato_empleado_id' => $dato_empleado->id, 'dias_vacaciones' => $dias_vacaciones, 'bono_vacacional' => $bono_vacacional, 'faov' => $faov, 'asignacion' => $total, 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'fecha_registro' => $fecha_registro, 'vacaciones_pagar' => $pagar_dias_vacaciones, 'bono_vacacional_pagar' => $pagar_bono_vacacional]);

        }

        return redirect()->to('/crear_vacaciones')->with('alert', 'Vacación creada');

    }

    public function eliminar($id){

        DB::table('vacaciones')->where('id', $id)->delete();
        return redirect()->back()->with('alert', 'Vacación eliminada');

    }

    public function prima_antiguedad_empleado($sueldo, $fecha_ingreso){

        $date = getdate();

        $annio_actual = $date['year'];
        $mes_actual = $date['mon'];
        $dia_actual = $date['mday'];

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

    function verDisfruteVacacional(){
        $disfrute_vacacional = DB::table('disfrute_vacacional')
                                    ->join('datos_empleados', 'disfrute_vacacional.dato_empleado_id', '=', 'datos_empleados.id')
                                    ->select('datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.cedula', 'disfrute_vacacional.fecha_inicio', 'disfrute_vacacional.fecha_fin', 'disfrute_vacacional.id')
                                    ->get();
        return view('disfrute_vacacional', ['disfrute_vacacional' => $disfrute_vacacional]);
    }

    function buscarPersona($cedula){
        $empleado = DB::table('datos_empleados')->where('cedula', $cedula)->get();
        return \Response::json($empleado);
    }

    function registrar(Request $request){
        $cedula = $request->cedula;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        if($cedula && $fecha_inicio && $fecha_fin){
            $empleado_id = DB::table('datos_empleados')->where('cedula', $cedula)->pluck('id');
            DB::table('disfrute_vacacional')->insert(['dato_empleado_id' => $empleado_id, 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin]);
            return redirect()->back()->with('alert', 'Disfrute vacacional creado');
        }
        else{
            return redirect()->back()->with('alert', 'Todos los campos son obligatorios');
        }

    }

    function eliminar_disfrute($id){
        DB::table('disfrute_vacacional')->where('id', $id)->delete();
        return redirect()->back()->with('alert', 'Disfrute vacacional eliminado');
    }

    function activar($id){
        DB::table('prima_transporte_vacacional')->insert(['dato_empleado_id' => $id, 'prima_vacacional' => 1]);
        return redirect()->back()->with('alert', 'Prima de transporte vacacional activada');
    }

    function desactivar($id){
        DB::table('prima_transporte_vacacional')->where('dato_empleado_id', $id)->delete();
        return redirect()->back()->with('alert', 'Prima de transporte vacacional desactivada');
    }

    function prima_responsabilidad($cargo, $sueldo){
    	$monto = 0;
            
        if($cargo >= 36 || $cargo <= 46){
            $monto = $sueldo * 0.10;
        }
        if($cargo >= 53 || $cargo <= 60){
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
