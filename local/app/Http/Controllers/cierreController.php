<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class cierreController extends Controller {



	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function cerrar(Request $request)
	{
		$id = $request->n_nomina;
		DB::table('nomina')->where('id', $id)->update(['status' => 1]);

		return redirect()->back()->with('alert', 'Nomina cerrada');
	}


}
