<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class casoParticularController extends Controller {



	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function crear(Request $request)
	{
		$empleado = $request->empleado;
		$concepto = $request->concepto;

		DB::table('casos_particulares')->insert(['dato_empleado_id' => $empleado, 'concepto_id' => $concepto]);

		return redirect()->to('/casos_particulares_view')->with('alert', 'Caso particular registrado');

	}

	public function eliminar($id){

		DB::table('casos_particulares')->where('id', $id)->delete();

		return redirect()->back()->with('alert', 'Caso particular eliminado');

	}


}
