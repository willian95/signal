<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use Illuminate\Http\Request;

class retencionesNominaController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function generarPDF(Request $request){

		$mes = $request->mes;
		$annio = $request->annio;
		$concepto = $request->concepto;
		$mayor_obrero = 0;
		$mayor_empleado = 0;

		if($concepto == 20100){
			$nominas_obrero = DB::table('nomina')
									->where('fecha_inicio', 'like', '%/'.$mes.'/'.$annio.'%')
									->where('tipo', 'obrero')
									->get();

			$nominas_empleado = DB::table('nomina')
									->where('fecha_inicio', 'like', '%/'.$mes.'/'.$annio.'%')
									->where('tipo', 'empleado')
									->get();

			foreach ($nominas_obrero as $obrero) {
				if($obrero->id > $mayor_obrero){
					$mayor_obrero = $obrero->id;
				}
			}

			foreach ($nominas_empleado as $empleado) {
				if($empleado->id > $mayor_empleado){
					$mayor_empleado = $empleado->id;
				}
			}

				$html = view('pdf.retenciones.retenciones_fideicomiso')->with('mayor_obrero', $mayor_obrero)->with('mayor_empleado', $mayor_empleado)->with('mes', $mes)->with('annio', $annio)->render();
        		return PDF::load($html)->show();
        	
		}

		else if($concepto == 20222){
			$nominas_obrero = DB::table('nomina')
									->where('fecha_inicio', 'like', '%/'.$mes.'/'.$annio.'%')
									->where('tipo', 'obrero')
									->get();

			$nominas_empleado = DB::table('nomina')
									->where('fecha_inicio', 'like', '%/'.$mes.'/'.$annio.'%')
									->where('tipo', 'empleado')
									->get();

			foreach ($nominas_obrero as $obrero) {
				if($obrero->id > $mayor_obrero){
					$mayor_obrero = $obrero->id;
				}
			}

			foreach ($nominas_empleado as $empleado) {
				if($empleado->id > $mayor_empleado){
					$mayor_empleado = $empleado->id;
				}
			}

			$html = view('pdf.retenciones.retenciones_tesoreria_seguridad')->with('mayor_obrero', $mayor_obrero)->with('mayor_empleado', $mayor_empleado)->with('mes', $mes)->with('annio', $annio)->render();
    		return PDF::load($html)->show();
        	
		}

		else if($concepto == 20134){

				$html = view('pdf.retenciones.retenciones_caja_ahorro')->with('annio', $annio)->with('mes', $mes)->render();
        		return PDF::load($html)->show();
		}

		else if($concepto == 30121){

			$nominas_obrero = DB::table('nomina')
									->where('fecha_inicio', 'like', '%/'.$mes.'/'.$annio.'%')
									->where('tipo', 'obrero')
									->get();

			$nominas_empleado = DB::table('nomina')
									->where('fecha_inicio', 'like', '%/'.$mes.'/'.$annio.'%')
									->where('tipo', 'empleado')
									->get();
			foreach ($nominas_obrero as $obrero) {
				if($obrero->id > $mayor_obrero){
					$mayor_obrero = $obrero->id;
				}
			}

			foreach ($nominas_empleado as $empleado) {
				if($empleado->id > $mayor_empleado){
					$mayor_empleado = $empleado->id;
				}
			}

			if($nominas_obrero != null && $nominas_empleado != null){
				$html = view('pdf.retenciones.retenciones_faov')->with('mayor_empleado', $mayor_empleado)->with('mayor_obrero', $mayor_obrero)->with('mes', $mes)->with('annio', $annio)->render();
        		return PDF::load($html)->show();
			}
			else{
				return redirect()->back()->with('alert', 'No existen retenciones para esta fecha');
			}
		}
		
	}

	public function generarTxtTesoreria(Request $request){

		$mes = $request->mes;
		$annio = $request->annio;

		$empleados = DB::table('datos_empleados')
							->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
							->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
							->where('datos_empleados.status', 'activo')
							->select('datos_empleados.cedula', 'datos_empleados.nombre', 'salarios.sueldo','datos_empleados.apellido', 'datos_laborales.fecha_ingreso', 'datos_empleados.id')
							->distinct()
							->get();

		$nominas = DB::table('nomina')->where('fecha_inicio', 'like', '%'.$mes.'/'.$annio)->get();
		$contenido = '';

		if($mes == 1 || $mes == 3 || $mes == 5 || $mes == 7 || $mes == 8 || $mes == 10 || $mes == 12){
			$dia = 31;
		}
		else if($mes == 4 || $mes == 6 || $mes == 9 || $mes == 11){
			$dia = 30;
		}
		else if($mes == 2){
			$dia = 28;
		}

		\Storage::disk('local')->put('/tesoreria/'.$annio.$mes.'_010425511937_O.txt', $contenido);

		foreach($empleados as $empleado){

			$dias_descanso =  DB::table('nomina')
									->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
									->where('fecha_inicio', 'like',  '%'.$mes.'/'.$annio.'%')
									->where('conceptos_nominas.id_empleado', $empleado->id)
									->where('conceptos_nominas.id_concepto', 1002)
									->sum('conceptos_nominas.monto');

			$dias_descanso_oficiales =  DB::table('nomina')
											->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
											->where('fecha_inicio', 'like',  '%'.$mes.'/'.$annio.'%')
											->where('conceptos_nominas.id_empleado', $empleado->id)
											->where('conceptos_nominas.id_concepto', 1003)
											->sum('conceptos_nominas.monto');

			$jornada_diurna =  DB::table('nomina')
									->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
									->where('fecha_inicio', 'like',  '%'.$mes.'/'.$annio.'%')
									->where('conceptos_nominas.id_empleado', $empleado->id)
									->where('conceptos_nominas.id_concepto', 1004)
									->sum('conceptos_nominas.monto');

			$jornada_nocturna =  DB::table('nomina')
									->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
									->where('fecha_inicio', 'like',  '%'.$mes.'/'.$annio.'%')
									->where('conceptos_nominas.id_empleado', $empleado->id)
									->where('conceptos_nominas.id_concepto', 1005)
									->sum('conceptos_nominas.monto');

			$jornada_mixta =  DB::table('nomina')
									->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
									->where('fecha_inicio', 'like',  '%'.$mes.'/'.$annio.'%')
									->where('conceptos_nominas.id_empleado', $empleado->id)
									->where('conceptos_nominas.id_concepto', 1006)
									->sum('conceptos_nominas.monto');

			$dias_remunerados =  DB::table('nomina')
									->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
									->where('fecha_inicio', 'like',  '%'.$mes.'/'.$annio.'%')
									->where('conceptos_nominas.id_empleado', $empleado->id)
									->where('conceptos_nominas.id_concepto', 1015)
									->sum('conceptos_nominas.monto');

			$prima_antiguedad = DB::table('nomina')
									->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
									->where('fecha_inicio', 'like',  '%'.$mes.'/'.$annio.'%')
									->where('conceptos_nominas.id_empleado', $empleado->id)
									->where('conceptos_nominas.id_concepto', 5471)
									->sum('conceptos_nominas.monto');

			$prima_transporte = DB::table('nomina')
									->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
									->where('fecha_inicio', 'like',  '%'.$mes.'/'.$annio.'%')
									->where('conceptos_nominas.id_empleado', $empleado->id)
									->where('conceptos_nominas.id_concepto', 5440)
									->sum('conceptos_nominas.monto');

			$prima_profesionalizacion = DB::table('nomina')
											->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
											->where('fecha_inicio', 'like',  '%'.$mes.'/'.$annio.'%')
											->where('conceptos_nominas.id_empleado', $empleado->id)
											->where('conceptos_nominas.id_concepto', 5450)
											->sum('conceptos_nominas.monto');

			$columna_1 = $empleado->cedula;
			$columna_2 = $dias_descanso + $dias_descanso_oficiales + $jornada_diurna + $jornada_nocturna + $jornada_mixta + $dias_remunerados;
			$columna_3 = $prima_antiguedad;
			$columna_4 = '0';
			$columna_5 = $prima_transporte + $prima_profesionalizacion;
			$columna_6 = $dia.'/'.$mes.'/'.$annio;

						
			$columna_2 = number_format($columna_2, 2, ',', '');
			$columna_3 = number_format($columna_3, 2, ',', '');
			$columna_5 = number_format($columna_5, 2, ',', '');
			
			$contenido = $columna_1.'|'.$columna_2.'|'.$columna_3.'|'.$columna_4.'|'.$columna_5.'|'.$columna_6;

			\Storage::disk('local')->append('/tesoreria/'.$annio.$mes.'_010425511937_O.txt', $contenido);

		}

		$headers = array(
             'Content-Type: application/plain-text',
        );

		return response()->download(public_path().'/descargas/tesoreria/'.$annio.$mes.'_010425511937_O.txt', $annio.$mes.'_010425511937_O.txt', $headers);
		return redirect()->back();

	}

}
