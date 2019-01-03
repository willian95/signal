<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use Illuminate\Http\Request;

class reporteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function crear($id_nomina){

        $tipo = DB::table('nomina')
                    ->where('id', $id_nomina)
                    ->pluck('tipo');

        $fecha_inicio = DB::table('nomina')
                    ->where('id', $id_nomina)
                    ->pluck('fecha_inicio');

        $fecha_fin = DB::table('nomina')
                    ->where('id', $id_nomina)
                    ->pluck('fecha_fin');

        $n_nomina = DB::table('nomina')
                    ->where('id', $id_nomina)
                    ->pluck('n_nomina');

        $asignaciones = DB::table('nomina')
                            ->where('id', $id_nomina)
                            ->pluck('asignaciones');

        $deducciones = DB::table('nomina')
                            ->where('id', $id_nomina)
                            ->pluck('deducciones');

        $patronal = DB::table('nomina')
                            ->where('id', $id_nomina)
                            ->pluck('patronal');

        $gerencias = DB::table('departamentos')->orderBy('nombre_departamento')->get();

        $html = view('pdf.reporte')->with('tipo', $tipo)->with('fecha_inicio', $fecha_inicio)->with('fecha_fin', $fecha_fin)->with('n_nomina', $n_nomina)->with('id_nomina', $id_nomina)->with('total_asignaciones', $asignaciones)->with('total_deducciones', $deducciones)->with('total_patronal', $patronal)->with('gerencias', $gerencias)->render();
        return PDF::load($html)->show();
    }
}
