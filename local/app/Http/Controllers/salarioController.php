<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class salarioController extends Controller {

	public function create(Request $request){

		$descripcion = $request->descripcion;
		$sueldo = $request->sueldo;

		DB::table('salarios')->insert(['descripcion' => $descripcion, 'sueldo' => $sueldo]);

		return redirect()->back()->with('alert', 'Sueldo registrado');

	}

	public function editar(Request $request, $id){

		$descripcion = $request->descripcion;
		$sueldo = $request->sueldo;

		DB::table('salarios')->where('id', $id)->update(['descripcion' => $descripcion, 'sueldo' => $sueldo]);

		return redirect()->back()->with('alert', 'Sueldo editado');
	}

}
