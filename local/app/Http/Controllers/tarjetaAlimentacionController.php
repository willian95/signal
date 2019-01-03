<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class tarjetaAlimentacionController extends Controller {

	function generarClientesView(){
		
		$empleados = DB::table('datos_empleados')->where('status', 'activo')->get();
		return view('generarClientes', ['empleados' => $empleados]);
	
	}

	function generarClientes(Request $request){

		$contenido="";
		$headers = array(
              'Content-Type: application/plain-text',
            );
		\Storage::disk('local')->put('tarjeta.txt', $contenido);

		$empleadosCount = count($request->empleado);

		for($i = 0; $i < $empleadosCount; $i++){
			$nacionalidad = DB::table('datos_empleados')->where('id', $request->empleado[$i])->pluck('nacionalidad');

			$cedula = DB::table('datos_empleados')->where('id', $request->empleado[$i])->pluck('cedula');
			$apellido = DB::table('datos_empleados')->where('id', $request->empleado[$i])->pluck('apellido');
			$nombre = DB::table('datos_empleados')->where('id', $request->empleado[$i])->pluck('nombre');
			$sexo = DB::table('datos_empleados')->where('id', $request->empleado[$i])->pluck('sexo');
			$estado_civil_numero = DB::table('datos_empleados')->where('id', $request->empleado[$i])->pluck('estado_civil');
			$direccion = DB::table('datos_empleados')->where('id', $request->empleado[$i])->pluck('direccion');
			$estado = "FALCON";

			if(strpos($apellido, ' ') > 0 && $cedula != '10965081'){
				$primer_apellido = substr($apellido, 0, strrpos($apellido, ' '));
				$segundo_apellido = substr($apellido, strrpos($apellido, ' '));
			}

			else if($cedula == '10965081'){
				$primer_apellido = $apellido;
			}

			$primer_nombre = substr($nombre, 0, strpos($nombre, ' '));
			$segundo_nombre = substr($nombre, strrpos($nombre, ' '));
			
			if($estado_civil_numero == 'soltero')
				$estado_civil = 1;

			else if($estado_civil_numero == 'casado')
						$estado_civil = 2;

			else if($estado_civil_numero == 'divorciado')
						$estado_civil = 3;

			else if($estado_civil_numero == 'viudo')
						$estado_civil = 4;

			$contenido= $nacionalidad.$this->contar($cedula, 15).$this->contar($primer_apellido, 29).$this->contar($segundo_apellido, 29).$this->contar($primer_nombre, 29).$this->contar($segundo_nombre, 29).$this->contar("", 29).$sexo.$estado_civil.$this->contar($direccion, 169).$estado.chr(13).chr(10);

			\Storage::append('tarjeta.txt', $contenido); 
			
		}

		return response()->download(public_path().'/descargas/tarjeta.txt', 'tarjeta.txt', $headers);

	}

	function contar($cadena, $cantidad){

		$numCaracteres = strlen($cadena);
		$numEspacios = $cantidad - $numCaracteres;

		for($i = 0; $i<$numEspacios; $i++){
			$cadena = $cadena." ";
		}

		return $cadena;

	}

	function abonoView(){
		
		$alimentacion = DB::table('panel_control')->where('id', 1)->pluck('alimentacion');
		$empleados = DB::table('datos_empleados')->where('status', 'activo')->get();
		return view("abono", ['empleados' => $empleados, 'alimentacion' => $alimentacion]);
		
	}

	function abono(Request $request){

		$alimentacion = DB::table('panel_control')->where('id', 1)->pluck('alimentacion');
		$alimentacion = $alimentacion/30;


		$contenido="";
		$headers = array(
              'Content-Type: application/plain-text',
            );
		\Storage::disk('local')->put('abono.txt', $contenido);

		$diasContar = count($request->dias);

		for($i = 0; $i < $diasContar; $i++){

			$nacionalidad = DB::table('datos_empleados')->where('id', $request->empleado[$i])->pluck('nacionalidad');
			$cedula = DB::table('datos_empleados')->where('id', $request->empleado[$i])->pluck('cedula');
			
			$monto = $request->dias[$i] * $alimentacion;
			$monto = round($monto, 2);

			$decimales = "00";

			if(strpos($monto, '.')){
				$decimales = substr($monto, strpos($monto, '.')+1, 2);
			}

			$contenido = "90002".$nacionalidad.$this->cerosIzquierda($cedula, 10).$this->cerosIzquierda($monto.$decimales, 15).chr(13).chr(10);;


			\Storage::append('abono.txt', $contenido); 
		}

		return response()->download(public_path().'/descargas/abono.txt', 'abono.txt', $headers);

	}

	function cerosIzquierda($cadena, $cantidad){
		
		$numCaracteres = strlen($cadena);
		$numCeros = $cantidad - $numCaracteres;
		$ceros = "";

		for($i = 0; $i<$numCeros; $i++){
			$ceros = $ceros."0";
		}

		$cadena = $ceros.$cadena;

		return $cadena;

	}

}