<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class departamentoController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$nombre = $request->nombre;

		DB::table('departamentos')->insert(['nombre_departamento' => $nombre]);

		return redirect()->back()->with('alert', 'Departamento Creado');
	}

	public function delete($id)
	{

		DB::table('departamentos')->where('id', $id)->delete();

		return redirect()->back()->with('alert', 'Departamento Eliminado');
	}

	public function edit(Request $request, $id)
	{

		$nombre = $request->nombre;
		DB::table('departamentos')->where('id', $id)->update(['nombre_departamento' => $nombre]);

		return redirect()->back()->with('alert', 'Departamento Editado');
	}

	

}
