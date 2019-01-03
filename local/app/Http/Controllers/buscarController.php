<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class buscarController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	
	/*
		Filtrar pasantes en el istado de personal
		colocar meses en los trimestres
		

	*/

	public function busqueda_universal(Request $request){

		 	$objeto = $request->buscar;
            
			$busqueda_nomina = DB::table('nomina')
									->where('id', 'like', '%'.$objeto)
									->get();
			
			if(strpos($objeto, ' ')){
				$objeto = substr($objeto, 0, strpos($objeto, ' '));
			}

			$busqueda_empleado= DB::table('datos_empleados')
									->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
									->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
                    				->where('cedula', $objeto)
									->orWhere('nombre', 'like', '%'.$objeto.'%')
									->orWhere('apellido', 'like', '%'.$objeto.'%')
									->select('datos_empleados.nombre', 'datos_empleados.cedula', 'datos_empleados.apellido', 'cargos.descripcion', 'datos_empleados.status', 'datos_empleados.id')
                    				->get();
         
            return view('buscar', ['busquedas_nominas' => $busqueda_nomina, 'busquedas_empleados' => $busqueda_empleado]);
            
	 }
    

}
