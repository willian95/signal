<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class expedienteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function show(){
		return view('expediente');
	}

	function find($opcion, $dato){
		$expedientes;
		if($opcion == 'cedula'){
			$expedientes = DB::table('datos_empleados')
								->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
								->where('datos_empleados.cedula', $dato)
								->get();
		}
		else{
			$expedientes = DB::table('datos_empleados')
								->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
								->where('datos_empleados.nombre', 'like', '%'.$dato.'%')
								->get();
		}

		return \Response::json($expedientes);
	}

	function mostrarDocs($id){

		$nombre = DB::table('datos_empleados')->where('id', $id)->pluck('nombre');
        $documentosVarios = DB::table('documentos_varios')->where('trabajador_id', $id)->get();
		return view('mostrarDocs', ['nombre' => $nombre,  'id_empleado' => $id, 'documentosVarios' => $documentosVarios]);
	}
	
	function registrar(Request $request, $tipo, $id_empleado){

		$cedula = DB::table('datos_empleados')->where('id', $id_empleado)->pluck('cedula');
		$nombre_empleado = DB::table('datos_empleados')->where('id', $id_empleado)->pluck('nombre');
        $nombre_empleado = str_replace(' ', '_', $nombre_empleado);

		$file = $request->file('archivo');
		$nombre_img =time().uniqid()."-".$file->getClientOriginalName();
        
        $nombre_img = str_replace( " ", "_", $nombre_img); 
        $nombre_img = strtolower($nombre_img);
	    $nombre_img = str_replace( "img", "pic", $nombre_img);
        $nombre_img = str_replace( "#", "-", $nombre_img);

        \Storage::disk('expedientes')->put($nombre_img, \File::get($file));

        if(count(DB::table('documentos')->where('dato_empleado_id', $id_empleado)->get()) <= 0){

        	\Storage::disk('expedientes')->makeDirectory($nombre_empleado.$cedula);
        	\Storage::disk('expedientes')->move($nombre_img, $nombre_empleado.$cedula.'/'.$nombre_img);
        	
        	DB::table('documentos')->insert(['dato_empleado_id' => $id_empleado]);

        }

        else{
        	\Storage::disk('expedientes')->move($nombre_img, $nombre_empleado.$cedula.'/'.$nombre_img);
        }

        if($tipo == 1){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['copia_partida_nacimiento' => $nombre_img]);
        }

        else if($tipo == 2){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['copia_cedula_identidad' => $nombre_img]);
        }

        else if($tipo == 3){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['copia_acta_matrimonio' => $nombre_img]);
        }

       	else if($tipo == 4){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['copia_rif' => $nombre_img]);
        }

        else if($tipo == 5){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['constancia_residencia' => $nombre_img]);
        }

        else if($tipo == 6){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['copia_contrato' => $nombre_img]);
        }

        else if($tipo == 7){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['cuenta_nomina' => $nombre_img]);
        }

        else if($tipo == 8){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['cuenta_fideicomiso' => $nombre_img]);
        }

        else if($tipo == 9){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['cuenta_conducir' => $nombre_img]);
        }

        else if($tipo == 10){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['cuenta_carta_medica' => $nombre_img]);
        }

        else if($tipo == 11){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['poliza_rc' => $nombre_img]);
        }

        else if($tipo == 12){
        	DB::table('documentos')->where('dato_empleado_id', $id_empleado)->update(['carnet' => $nombre_img]);
        }

        return redirect()->back()->with('alert', 'Documento registrado');

	}

	function eliminar($id, $doc){

		$cedula = DB::table('datos_empleados')->where('id', $id)->pluck('cedula');
        $nombre_empleado = DB::table('datos_empleados')->where('id', $id)->pluck('nombre');
        $nombre_empleado = str_replace(' ', '_', $nombre_empleado);

       	$archivo = '';
            
		if($doc == 1){
			$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('copia_partida_nacimiento');
            DB::table('documentos')->where('dato_empleado_id', $id)->update(['copia_partida_nacimiento' => '']);
        }

         else if($doc == 2){
         	$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('copia_cedula_identidad');
        	DB::table('documentos')->where('dato_empleado_id', $id)->update(['copia_cedula_identidad' => '']);
        }

        else if($doc == 3){
        	$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('copia_acta_matrimonio');
        	DB::table('documentos')->where('dato_empleado_id', $id)->update(['copia_acta_matrimonio' => '']);
        }

       	else if($doc == 4){
       		$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('copia_rif');
        	DB::table('documentos')->where('dato_empleado_id', $id)->update(['copia_rif' => '']);
        }

        else if($doc == 5){
        	$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('constancia_residencia');
        	DB::table('documentos')->where('dato_empleado_id', $id)->update(['constancia_residencia' => '']);
        }

        else if($doc == 6){
        	$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('copia_contrato');
        	DB::table('documentos')->where('dato_empleado_id', $id)->update(['copia_contrato' => '']);
        }

        else if($doc == 7){
        	$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('cuenta_nomina');
        	DB::table('documentos')->where('dato_empleado_id', $id)->update(['cuenta_nomina' => '']);
        }

        else if($doc == 8){
        	$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('cuenta_fideicomiso');
        	DB::table('documentos')->where('dato_empleado_id', $id)->update(['cuenta_fideicomiso' => '']);
        }

        else if($doc == 9){
        	$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('copia_conducir');
        	DB::table('documentos')->where('dato_empleado_id', $id)->update(['copia_conducir' => '']);
        }

        else if($doc == 10){
        	$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('copia_carta_medica');
        	DB::table('documentos')->where('dato_empleado_id', $id)->update(['copia_carta_medica' => '']);
        }

        else if($doc == 11){
        	$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('poliza_rc');
        	DB::table('documentos')->where('dato_empleado_id', $id)->update(['poliza_rc' => '']);
        }

        else if($doc == 12){
        	$archivo = DB::table('documentos')->where('dato_empleado_id', $id)->pluck('carnet');
        	DB::table('documentos')->where('dato_empleado_id', $id)->update(['carnet' => '']);
        }

        if(\Storage::disk('expedientes')->exists($nombre_empleado.$cedula.'/'.$archivo))
            \Storage::disk('expedientes')->delete($nombre_empleado.$cedula.'/'.$archivo);


        return redirect()->back()->with('alert', 'Documento eliminado');
		
	}

    function documentosVarios(Request $request, $id_empleado){

        $tipo_documento = $request->documento;
        $parentezco = $request->parentezco;
        $file = $request->file('archivo');
        $cedula = DB::table('datos_empleados')->where('id', $id_empleado)->pluck('cedula');
        $nombre_empleado = DB::table('datos_empleados')->where('id', $id_empleado)->pluck('nombre');
        $nombre_empleado = str_replace(' ', '_', $nombre_empleado);
        $nombre_img =time().uniqid()."-".$file->getClientOriginalName();
        
        $nombre_img = str_replace( " ", "_", $nombre_img); 
        $nombre_img = strtolower($nombre_img);
        $nombre_img = str_replace( "img", "pic", $nombre_img);
        $nombre_img = str_replace( "#", "-", $nombre_img);

        \Storage::disk('expedientes')->put($nombre_img, \File::get($file));

        if(count(DB::table('documentos')->where('dato_empleado_id', $id_empleado)->get()) <= 0){

            \Storage::disk('expedientes')->makeDirectory($nombre_empleado.$cedula);
            \Storage::disk('expedientes')->move($nombre_img, $nombre_empleado.$cedula.'/'.$nombre_img);
            
            DB::table('documentos')->insert(['dato_empleado_id' => $id_empleado]);

        }

        else{
            \Storage::disk('expedientes')->move($nombre_img, $nombre_empleado.$cedula.'/'.$nombre_img);
        }

        DB::table('documentos_varios')->insert(['trabajador_id' => $id_empleado, 'tipo' => $tipo_documento, 'parentezco' => $parentezco, 'ruta' => $nombre_img]);

        return redirect()->back()->with('alert', 'Documento registrado');
    }

	function eliminarDocumentosVarios($id){

		$cedula = DB::table('documentos_varios')
				->join('datos_empleados', 'documentos_varios.trabajador_id', '=', 'datos_empleados.id')
				->where('documentos_varios.id', $id)
				->pluck('datos_empleados.cedula');

		$nombre_empleado = DB::table('documentos_varios')
					->join('datos_empleados', 'documentos_varios.trabajador_id', '=', 'datos_empleados.id')
					->where('documentos_varios.id', $id)
					->pluck('datos_empleados.nombre');

		$nombre_empleado = str_replace(' ', '_', $nombre_empleado);

		$archivo = DB::table('documentos_varios')->where('id', $id)->pluck('ruta');
		DB::table('documentos_varios')->where('id', $id)->delete();
		if(\Storage::disk('expedientes')->exists($nombre_empleado.$cedula.'/'.$archivo)){
			\Storage::disk('expedientes')->delete($nombre_empleado.$cedula.'/'.$archivo);
		}

		return redirect()->back()->with('alert', 'Documento eliminado');


	}

}
