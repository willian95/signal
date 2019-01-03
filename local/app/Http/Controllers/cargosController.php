<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class cargosController extends Controller {



	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$nombre = $request->nombre;
		DB::table('cargos')->insert(['descripcion' => $nombre, 'status' => 1]);

		return redirect()->back()->with('alert', 'cargo registrado');
	}

	public function edit(Request $request, $id)
	{
		$nombre = $request->descripcion;
		DB::table('cargos')->where('id', $id)->update(['descripcion' => $nombre]);

		return redirect()->back()->with('alert', 'cargo editado');
	}


}
