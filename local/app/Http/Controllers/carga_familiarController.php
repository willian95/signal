<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use Illuminate\Http\Request;

class carga_familiarController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{

		$carga_familiar = DB::table('datos_empleados')
								->join('carga_familiar', 'carga_familiar.datos_empleados_id', '=', 'datos_empleados.id')
								->where('datos_empleados.cedula', $request->cedula)
								->select('carga_familiar.nombres', 'carga_familiar.fecha', 'carga_familiar.carga')
								->get();
		
		$datos_empleados = DB::table('datos_empleados')
								->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
								->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
								->where('cedula', $request->cedula)
								->get();

		if($carga_familiar == null){
			return redirect()->back()->with('alert', 'No existe carga familiar');
		}

		else{

			$html = view('pdf.carga_familiar')->with('datos_empleados', $datos_empleados)->with('cargas_familiares', $carga_familiar)->render();
    		return PDF::load($html)->show();

		}

	}

	public function create_all(){
		
		$empleados = DB::table('datos_empleados')
							->where('status', 'activo')
							->get();
		
		$html = view('pdf.carga_familiar_todos')->with('datos_empleados', $empleados)->render();
    	return PDF::load($html)->show();

	}

	public function editar(Request $request, $id){

		$grado = $request->grado;
		DB::table("carga_familiar")
			->where('id', $id)
			->update(['grado' => $grado]);

		return redirect()->back()->with('alert', 'Carga familiar editada');
		
	}

}
