<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use Illuminate\Http\Request;

class personalController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function ingresar(Request $request){

		$nombre           =  $request->nombre;
		$apellido         =  $request->apellido;
		$cedula           =  $request->cedula;
		$rif              =  $request->rif;
		$sexo             =  $request->sexo;
		$profesion        =  $request->profesion;
		$fecha_nacimiento =  $request->fecha_nacimiento;
		$estado_civil     =  $request->estado_civil;
		$direccion        =  $request->direccion;
		$parroquia        =  $request->parroquia;
        $municipio        =  $request->municipio;
		$correo           =  $request->correo;
		$tlf_movil        =  $request->tlf_movil;
		$tlf_fijo         =  $request->tlf_fijo; 
		$tipo_carrera     =  $request->tipo_carrera;
		$talla_camisa     =  $request->talla_camisa;
		$talla_pantalon   =  $request->talla_pantalon;
		$talla_zapatos    =  $request->talla_zapatos;
		$talla_braga      =  $request->talla_braga;

		$messages = [
		    'unique' => 'Cedula o email ingresado ya existe',
		    'correo' => 'Debe ingresar un email válido'
		];     

		$validator = \Validator::make($request->all(),[
    		'correo' => 'email|unique:datos_empleados',
        ], $messages);

    	if($validator->fails()){
    		return redirect()->back()->withErrors($validator)->withInput();
    	}

    	else{

    		if(!$profesion)
    			$profesion = "NP";

    		$id_empleado = DB::table('datos_empleados')->insertGetId(['nombre' => $nombre, 'apellido' => $apellido, 'cedula' => $cedula, 'rif' => $rif, 'sexo' => $sexo, 'profesion' => $profesion, 'fecha_nacimiento' => $fecha_nacimiento, 'estado_civil' => $estado_civil, 'direccion' => $direccion, 'parroquia' => $parroquia, 'municipio' => $municipio, 'correo' => $correo, 'tlf_movil' => $tlf_movil, 'tlf_fijo' => $tlf_fijo, 'tipo_carrera' => $tipo_carrera, 'status' => 'activo', 'talla_camisa' => $talla_camisa, 'talla_pantalon' => $talla_pantalon, 'talla_zapatos' => $talla_zapatos, 'talla_braga' => $talla_braga]);
    		$cargos = DB::table('cargos')->orderBy('descripcion', 'asc')->get();

    		$salarios = DB::table('salarios')->get();

			$departamentos = DB::table('departamentos')->get();

    		return view('datos_laborales', ['cargos' => $cargos, 'id' => $id_empleado, 'salarios' => $salarios, 'departamentos' => $departamentos]);
    	}

	}

	public function eliminar_empleado(Request $request, $id){
		$fecha = $request->fecha_egreso;
	    DB::table('datos_empleados')->where('id', $id)->update(['status' => 'inactivo']);
	    DB::table('datos_laborales')->where('dato_empleado_id', $id)->update(['fecha_egreso' => $fecha]);

	    return redirect()->to('/editar_empleado_view')->with('alert', 'Empleado eliminado');
	}

	public function laborales(Request $request, $id){
		$cargo                =   $request->cargo;
		$fecha_ingreso        =   $request->fecha_ingreso;
		$tipo_trabajador      =   $request->tipo_trabajador;
		$tipo_contrato        =   $request->tipo_contrato;
		$sueldo               =   $request->sueldo;
		$tipo_cuenta          =   $request->tipo_cuenta;
		$numero_cuenta        =   $request->numero_cuenta;
		$culminacion_contrato =   $request->culminacion_contrato;
        $fideicomiso          =   $request->fideicomiso;
        $departamento_id	  =   $request->departamento;
		$jubilado             =   $request->jubilado;

		if($jubilado == 'on'){
			$jubilado = 1;
		}

		else{
			$jubilado = 0;
		}

		DB::table('datos_laborales')->insert(['cargo_id' => $cargo, 'fecha_ingreso' => $fecha_ingreso, 'tipo_trabajador' => $tipo_trabajador, 'tipo_contrato' => $tipo_contrato, 'salario_id' => $sueldo, 'tipo_cuenta' => $tipo_cuenta, 'numero_cuenta' => $numero_cuenta, 'fecha_culminacion' => $culminacion_contrato, 'fideicomiso' => $fideicomiso, 'dato_empleado_id' =>$id, 'departamento_id' => $departamento_id, 'jubilado' => $jubilado]);

		return redirect()->to('/crear_empleado_view')->with('alert', 'Empleado insertado');
	}
    
    public function editar_empleado(Request $request, $id){
        
        $nombre           =  $request->nombre;
		$apellido         =  $request->apellido;
		$rif              =  $request->rif;
		$sexo             =  $request->sexo;
		$profesion        =  $request->profesion;
		$fecha_nacimiento =  $request->fecha_nacimiento;
		$estado_civil     =  $request->estado_civil;
		$direccion        =  $request->direccion;
		$parroquia        =  $request->parroquia;
        $municipio        =  $request->municipio;
		$tlf_movil        =  $request->tlf_movil;
		$tlf_fijo         =  $request->tlf_fijo; 
		$tipo_carrera     =  $request->tipo_carrera;
		$correo           =  $request->correo;
		$talla_camisa     =  $request->talla_camisa;
		$talla_pantalon   =  $request->talla_pantalon;
		$talla_zapatos    =  $request->talla_zapatos;
		$talla_braga    =  $request->talla_braga;

		if(!$profesion)
			$profesion = 'NP';

        DB::table('datos_empleados')->where('id', $id)->update(['nombre' => $nombre, 'apellido' => $apellido, 'rif' => $rif, 'sexo' => $sexo, 'profesion' => $profesion, 'fecha_nacimiento' => $fecha_nacimiento, 'municipio' => $municipio, 'estado_civil' => $estado_civil, 'direccion' => $direccion, 'parroquia' => $parroquia, 'tlf_movil' => $tlf_movil, 'tlf_fijo' => $tlf_fijo, 'tipo_carrera' => $tipo_carrera, 'correo' => $correo, 'talla_camisa' => $talla_camisa, 'talla_pantalon' => $talla_pantalon, 'talla_zapatos' => $talla_zapatos, 'talla_braga' => $talla_braga]);
        
        return redirect()->to('/editar_empleado_view')->with('alert', 'Empleado editado');
        
    }
    
    public function editar_laborales(Request $request, $id){
		$cargo                =   $request->cargo;
		$fecha_ingreso        =   $request->fecha_ingreso;
		$tipo_trabajador      =   $request->tipo_trabajador;
		$tipo_contrato        =   $request->tipo_contrato;
		$sueldo               =   $request->sueldo;
		$tipo_cuenta          =   $request->tipo_cuenta;
		$numero_cuenta        =   $request->numero_cuenta;
		$culminacion_contrato =   $request->culminacion_contrato;
        $caja_ahorro          =   $request->caja_ahorro;
        $fideicomiso          =   $request->fideicomiso;
        $departamento_id	  =   $request->departamento;
		$pasante	          =   $request->pasante;
		$jubilado	          =   $request->jubilado;
		$pensionado           =   $request->pensionado;
		$fecha_asignacion     =   $request->fecha_asignacion;

		if($fecha_asignacion){
			$empleado_id = DB::table('datos_laborales')->where('id', $id)->pluck('dato_empleado_id');
			DB::table('fechas_cargos_empleados')->insert(['fecha' => $fecha_asignacion, 'empleado_id' => $empleado_id, 'cargo_id' => $cargo]);
		}

		if($pensionado == 'on'){
			$pensionado = 1;
		}
		
		else{
			$pensionado = 0;
		}

		if($pasante == 'on'){
			$pasante = 1;
		}

		else{
			$pasante = 0;
		}

		if($jubilado == 'on'){
			$jubilado = 1;
		}

		else{
			$jubilado = 0;
		}

		DB::table('datos_laborales')->where('id', $id)->update(['cargo_id' => $cargo, 'fecha_ingreso' => $fecha_ingreso, 'tipo_trabajador' => $tipo_trabajador, 'tipo_contrato' => $tipo_contrato, 'salario_id' => $sueldo, 'tipo_cuenta' => $tipo_cuenta, 'numero_cuenta' => $numero_cuenta, 'fideicomiso' => $fideicomiso, 'fecha_culminacion' => $culminacion_contrato, 'departamento_id' => $departamento_id, 'pasante' => $pasante, 'jubilado' => $jubilado, 'pensionado' => $pensionado]);

		return redirect()->to('/crear_empleado_view')->with('alert', 'Empleado editado');

	}
    
    public function familia(Request $request, $id){
        
        $nombres = $request->nombre;
        $fecha = $request->fecha;
        $carga = $request->carga;
        $grado = $request->grado;

        DB::table('carga_familiar')->insert(['nombres' => $nombres, 'fecha' => $fecha, 'carga' => $carga, 'datos_empleados_id' => $id, 'grado' => $grado]);
        
        return redirect()->to('/carga_familiar/'.$id)->with('alert', 'Familiar registrado');
        
    }

	public function crear_pasante(Request $request){

		$nombre           =  $request->nombre;
		$apellido         =  $request->apellido;
		$cedula           =  $request->cedula;
		$rif              =  $request->rif;
		$sexo             =  $request->sexo;
		$profesion        =  "NP";
		$fecha_nacimiento =  $request->fecha_nacimiento;
		$estado_civil     =  $request->estado_civil;
		$direccion        =  $request->direccion;
		$parroquia        =  $request->parroquia;
        $municipio        =  $request->municipio;
		$correo           =  $request->correo;
		$tlf_movil        =  $request->tlf_movil;
		$tlf_fijo         =  $request->tlf_fijo; 
		$tipo_carrera     =  "NP";
		$talla_camisa     =  $request->talla_camisa;
		$talla_pantalon   =  $request->talla_pantalon;
		$talla_zapatos    =  $request->talla_zapatos;
		$talla_braga      =  $request->talla_braga;
		$fecha_ingreso    =   $request->fecha_ingreso;
        $departamento_id  =   $request->departamento;

		$messages = [
		    'unique' => 'Cedula o email ingresado ya existe',
		    'correo' => 'Debe ingresar un email válido'
		];     

		$validator = \Validator::make($request->all(),[
    		'correo' => 'email|unique:datos_empleados',
        ], $messages);

    	if($validator->fails()){
    		return redirect()->back()->withErrors($validator)->withInput();
    	}

    	else{

    		$id_empleado = DB::table('datos_empleados')->insertGetId(['nombre' => $nombre, 'apellido' => $apellido, 'cedula' => $cedula, 'rif' => $rif, 'sexo' => $sexo, 'profesion' => $profesion, 'fecha_nacimiento' => $fecha_nacimiento, 'estado_civil' => $estado_civil, 'direccion' => $direccion, 'parroquia' => $parroquia, 'municipio' => $municipio, 'correo' => $correo, 'tlf_movil' => $tlf_movil, 'tlf_fijo' => $tlf_fijo, 'tipo_carrera' => $tipo_carrera, 'status' => 'inactivo', 'talla_camisa' => $talla_camisa, 'talla_pantalon' => $talla_pantalon, 'talla_zapatos' => $talla_zapatos, 'talla_braga' => $talla_braga]);

			DB::table('datos_laborales')->insert(['cargo_id' => 92, 'fecha_ingreso' => $fecha_ingreso, 'departamento_id' => $departamento_id, 'dato_empleado_id' => $id_empleado, 'tipo_cuenta' => 'ahorro', 'salario_id' => 23, 'numero_cuenta' => '0', 'fideicomiso' => 0, 'pasante' => 1, 'tipo_contrato' => 'fijo']);

    		return redirect()->back()->with('alert', 'Pasante registrado');
    	}

	}

	function imprimir_planilla(Request $request){
		
		$cedula = $request->cedula;

		$datos = DB::table('datos_empleados')
						->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
						->join('cargos', 'datos_laborales.cargo_id', '=', 'cargos.id')
						->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
						->where('datos_empleados.cedula', $cedula)
						->select('datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.cedula', 'cargos.descripcion', 'salarios.sueldo', 'datos_empleados.talla_camisa', 'datos_empleados.talla_pantalon')
						->get();

		 $html = view('pdf.planilla')->with('datos', $datos)->render();
        return PDF::load($html)->show();

	}

	function filtrar_listado($fecha_inicio, $fecha_fin){

		$movimientos = DB::table('fechas_cargos_empleados')
							->join('datos_empleados', 'fechas_cargos_empleados.empleado_id', '=', 'datos_empleados.id')
							->join('cargos', 'fechas_cargos_empleados.cargo_id', '=', 'cargos.id')
							->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
							->select('datos_empleados.nombre', 'datos_empleados.apellido', 'cargos.descripcion', 'fechas_cargos_empleados.fecha')
							->get();

		return \Response::json($movimientos);
	}



}
