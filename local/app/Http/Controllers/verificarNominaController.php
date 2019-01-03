<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use Illuminate\Http\Request;

class verificarNominaController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function verificar(Request $request){
        
        $cedula = $request->cedula;
        
        $nominas = DB::table('nomina')
                        ->join('conceptos_nominas', 'conceptos_nominas.id_nomina', '=', 'nomina.id')
                        ->join('datos_empleados', 'conceptos_nominas.id_empleado', '=', 'datos_empleados.id')
                        ->where('datos_empleados.cedula', $cedula)
                        ->select('nomina.id')
                        ->distinct('nomina.id')
                        ->select('nomina.id', 'nomina.fecha_inicio', 'nomina.fecha_fin')
                        ->orderBy('nomina.id', 'desc')
                        ->get();

        $id_empleado = DB::table('datos_empleados')
                            ->where('cedula', $cedula)
                            ->pluck('id');
                        
        return view('verificar', ['nominas' => $nominas, 'id_empleado' => $id_empleado]);
        
    }
    
    public function generar($id_nomina, $id_empleado){
        
        $periodo = DB::table('nomina')->where('id', $id_nomina)->pluck('descripcion');
        $fecha_inicio = DB::table('nomina')->where('id', $id_nomina)->pluck('fecha_inicio');
        $fecha_fin = DB::table('nomina')->where('id', $id_nomina)->pluck('fecha_fin');
        $mes = substr($fecha_fin, 3, 2);

        $mes = intval($mes);

        if($mes == 1)
        {
            $mes = "Enero";
        }
        else if($mes == 2)
        {
            $mes = "Febrero";
        }
        else if($mes == 3)
        {
            $mes = "Marzo";
        }
        else if($mes == 4)
        {
            $mes = "Abril";
        }
        else if($mes == 5)
        {
            $mes = "Mayo";
        }
        else if($mes == 6)
        {
            $mes = "Junio";
        }
        else if($mes == 7)
        {
            $mes = "Julio";
        }
        else if($mes == 8)
        {
            $mes = "Agosto";
        }
        else if($mes == 9)
        {
            $mes = "Septiembre";
        }
        else if($mes == 10)
        {
            $mes = "Octubre";
        }
        else if($mes == 11)
        {
            $mes = "Noviembre";
        }
        else if($mes == 12)
        {
            $mes = "Diciembre";
        }
        
        $datos_empleados = DB::table('datos_empleados')
                                ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                                ->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
                                ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                                ->where('datos_empleados.id', $id_empleado)
                                ->select('datos_empleados.nombre', 'datos_empleados.cedula', 'datos_empleados.apellido', 'datos_empleados.rif', 'datos_laborales.fecha_ingreso', 'salarios.sueldo', 'cargos.descripcion')
                                ->get();
        
        $conceptos = DB::table('conceptos_nominas')
                        ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                        ->where('conceptos_nominas.id_nomina', $id_nomina)
                        ->where('conceptos_nominas.id_empleado', $id_empleado)
                        ->where('conceptos.tipo', '<>', 'patronal')
                        ->select('conceptos.descripcion', 'conceptos.tipo', 'conceptos_nominas.monto')
                        ->get();
        
        $asignaciones = DB::table('conceptos_nominas')
                        ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                        ->where('conceptos_nominas.id_nomina', $id_nomina)
                        ->where('conceptos_nominas.id_empleado', $id_empleado)
                        ->where('conceptos.tipo', 'asignacion')
                        ->sum('conceptos_nominas.monto');
        
        $deducciones = DB::table('conceptos_nominas')
                        ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                        ->where('conceptos_nominas.id_nomina', $id_nomina)
                        ->where('conceptos_nominas.id_empleado', $id_empleado)
                        ->where('conceptos.tipo', 'deduccion')
                        ->sum('conceptos_nominas.monto');

        $sueldo = DB::table('sueldo_nominas')->where('id_nomina', $id_nomina)->where('id_empleado', $id_empleado)->pluck('sueldo');
        
        $total = $asignaciones - $deducciones;
        
        $html = view('pdf.verificar')->with('mes', $mes)->with('periodo', $periodo)->with('fecha_fin', $fecha_fin)->with('fecha_inicio', $fecha_inicio)->with('datos_empleados', $datos_empleados)->with('conceptos', $conceptos)->with('sueldo', $sueldo)->with('asignaciones', $asignaciones)->with('deducciones', $deducciones)->with('total', $total)->render();
        return PDF::load($html)->show();
        
    }
    
}
