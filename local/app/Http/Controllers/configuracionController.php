<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class configuracionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function registrar(Request $request){

		$nombre = $request->nombre;
		$sueldo_minimo = $request->sueldo;
		$transporte_corto = $request->transporte_corto;
		$transporte_largo = $request->transporte_largo;
		$transporte_sueldo_minimo = $request->transporte_sueldo_minimo;
		$nombre_coordinador_rh = $request->nombre_coordinador_rh;
		$nombre_gerente_finanzas = $request->nombre_gerente_finanzas;
		$tarjeta_alimentacion = $request->tarjeta_alimentacion;

		DB::table('panel_control')->update(['nombre_gerente_rh' => $nombre, 'sueldo_minimo' => $sueldo_minimo, 'prima_transporte_cerca' => $transporte_corto, 'prima_transporte_lejos' => $transporte_largo, 'nombre_coordinador_rh' => $nombre_coordinador_rh, 'nombre_gerente_finanzas' => $nombre_gerente_finanzas, 'prima_transporte_cerca_minimo' => $transporte_sueldo_minimo, 'alimentacion' => $tarjeta_alimentacion]);

		return redirect()->back()->with('alert', 'Campo editado');

	}

}
