<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use Illuminate\Http\Request;

class devengadoController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function generarPDF(Request $request){

		$mes = $request->mes;
		$annio = $request->annio;
		$mayor_obrero = 0;
		$mayor_empleado = 0;

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

		$html = view('pdf.devengado')->with('mayor_obrero', $mayor_obrero)->with('mayor_empleado', $mayor_empleado)->with('mes', $mes)->with('annio', $annio)->render();
		return PDF::load($html)->show();
	}
}
