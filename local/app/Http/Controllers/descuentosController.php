<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class descuentosController extends Controller {

	function crear(Request $request){

		$cedula = $request->cedula;
		$deuda_inicial = $request->deuda_inicial;
		$cuota = $request->cuota;

		$id_empleado = DB::table('datos_empleados')->where('cedula', $cedula)->pluck('id');

		DB::table('descuentos')->insert(['id_empleado' => $id_empleado, 'deuda_inicial' => $deuda_inicial, 'deuda_actual' => $deuda_inicial, 'cuota' => $cuota]);

		return redirect()->back()->with('alert', 'Descuento creado');

	}

	function eliminar($id){
		DB::table('descuentos')->where('id', $id)->delete();
		return redirect()->back()->with('alert', 'Descuendo eliminado');
	}

}
