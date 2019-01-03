<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use Illuminate\Http\Request;

class listado_personalController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function generar(Request $request){

		$rif = $request->rif;
		$sexo =$request->sexo;
		$profesion = $request->profesion;
		$fecha_nacimiento = $request->fecha_nacimiento;
		$estado_civil = $request->estado_civil;
		$direccion = $request->direccion;
		$correo = $request->correo;
		$telefono_fijo = $request->telefono_fijo;
		$telefono_movil = $request->telefono_movil;
		$pasante = $request->pasante;

		$arrays = [
			'rif' => $request->rif,
			'sexo' => $request->sexo,
			'profesion' => $request->profesion,
			'fecha_nacimiento' => $request->fecha_nacimiento,
			'estado_civil' => $request->estado_civil,
			'direccion' => $request->direccion,
			'correo' => $request->correo,
			'telefono_fijo' => $request->telefono_fijo,
			'telefono_movil' => $request->telefono_movil		
		];

		$html = view('pdf.listado_trabajadores')->with('arrays', $arrays)->with('rif', $rif)->with('sexo', $sexo)->with('profesion', $profesion)->with('fecha_nacimiento', $fecha_nacimiento)->with('estado_civil', $estado_civil)->with('direccion', $direccion)->with('correo', $correo)->with('telefono_fijo', $telefono_fijo)->with('telefono_movil', $telefono_movil)->with('pasante', $pasante)->render();
        return PDF::load($html)->show();

	}

}
