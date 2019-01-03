<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use Illuminate\Http\Request;

class trimestreController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function create(Request $request){

		$meses = $request->meses;
		$anios = $request->anios;

		$departamentos = DB::table('departamentos')->get();

		$html = view('pdf.trimestre')->with('meses', $meses)->with('anios', $anios)->with('departamentos', $departamentos)->render();
		return PDF::load($html)->show();

	}

}
