<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class reposoController extends Controller {

	function view(){
        $reposos = DB::table('reposos_medicos')
                        ->join('datos_empleados', 'reposos_medicos.dato_empleado_id', '=', 'datos_empleados.id')
                        ->select('datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.cedula', 'reposos_medicos.id', 'reposos_medicos.motivo', 'reposos_medicos.fecha_inicio', 'reposos_medicos.fecha_fin')
                        ->get();
        return view('resposo_medico',['reposos' => $reposos]);
    }

    function buscar($cedula){

        $empleado = DB::table('datos_empleados')->where('cedula', $cedula)->get();
        return \Response::json($empleado);

    }

    function registrar(Request $request){
        
        $cedula = $request->cedula;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $motivo = $request->motivo;

        if($fecha_inicio && $cedula && $motivo){

            $dato_empleado_id = DB::table('datos_empleados')->where('cedula', $cedula)->pluck('id');

            DB::table('reposos_medicos')->insert(['fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'motivo' => $motivo, 'dato_empleado_id' => $dato_empleado_id]);
            return redirect()->back()->with('alert', 'Reposo medico creado');
        }
        else{
            return redirect()->back()->with('alert', 'Todos los campos son obligatorios a excepcion de la fecha de culminacion');
        }
    }

    function eliminar($id){
        DB::table('reposos_medicos')->where('id', $id)->delete();
        return redirect()->back()->with('alert', 'Reposo médico eliminado');
    }

}
