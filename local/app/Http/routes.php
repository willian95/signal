<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*********************************** INICIO - LOGIN - LOGOUT ************************************/

Route::get('/', function(){
    return view('welcome');
});

Route::post('/handlelogin', ['as' => 'handlelogin', 'uses' => 'authController@handlelogin']);

Route::get('/logout', 'authController@logout');

/************************************************************************************************/

/***************************************** Constancias *********************************************/

Route::get('/constancia', function(){
    return view('constancias');
});

Route::post('/generarConstancia/{id}', 'constanciaController@generarPDF');

/***************************************************************************************************/

/********************************** Verificar Nomina ***********************************************/

Route::post('/verificar_nomina', 'verificarNominaController@verificar');

Route::get('/verificar-pdf/{id_nomina}/empleado/{id_empleado}', 'verificarNominaController@generar');

/***************************************************************************************************/

Route::group(['middleware' => 'admin'], function () {

/************************************** LANDING *************************************************/

Route::get('/landing', function(){

    $actual = getDate();

    $mes_actual = "";

    if($actual['mon'] < 10)
    {
        $mes_actual = '0'.$actual['mon'];
    }
    else{
        $mes_actual = $actual['mon'];
    }

    $dia_actual = "";
    if($actual['mday'] < 10){
        $dia_actual = '0'.$actual['mday'];
    }
    else{
        $dia_actual = $actual['mday'];
    }

    $fecha_actual = $dia_actual."/".$mes_actual;


    $cumples_empleados = DB::table('datos_empleados')->where('status', 'activo')->where('fecha_nacimiento', 'LIKE', "%".$fecha_actual."%")->get();

    //echo $fecha_actual;

    $nominas = DB::table('nomina')->orderBy('id', 'desc')->paginate(30);

    return view('landing', ['nominas' => $nominas, 'cumples' => $cumples_empleados]);
});

/************************************************************************************************/

/************************************** PERSONAL ************************************************/

Route::get('/crear_empleado_view', function(){  //vista crear_empleado
    return view('crear_empleado');
});

Route::post('/insertar_empleado', 'personalController@ingresar'); // controlador para insertar empleados

Route::post('/insertar_laborales/{id}', 'personalController@laborales'); //controlador para insertar laborales

Route::get('/editar_empleado_view', function(){   //vista para editar empleado

    $empleados = DB::table('datos_empleados')
                    ->join('datos_laborales', 'datos_empleados.id', '=', 'datos_laborales.dato_empleado_id')
                    ->join('cargos', 'cargos.id', '=', 'datos_laborales.cargo_id')
                    ->select('datos_empleados.id', 'datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.cedula', 'cargos.descripcion', 'datos_empleados.status')
                    ->paginate(30);

    return view('editar_empleado', ['empleados' => $empleados]);
});

Route::get('/editar_empleado_form/{id}', function($id){

    $empleado = DB::table('datos_empleados')->where('id', $id)->get();

    return view('editar_empleado_form', ['empleados' => $empleado]);
});

Route::get('/crear_pasante_view', function(){
    return view('crear_pasante_view');
});

Route::post('/crear_pasante', 'personalController@crear_pasante');

Route::post('/editar_empleado/{id}', 'personalController@editar_empleado');

Route::get('/editar_laborales_form/{id}', function($id){

    $laborales = DB::table('datos_laborales')
                 ->join('cargos', 'cargos.id', '=', 'datos_laborales.cargo_id')
                 ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
                 ->select('datos_laborales.id',
                    'datos_laborales.fecha_ingreso',
                    'datos_laborales.tipo_trabajador',
                    'datos_laborales.tipo_contrato',
                    'salarios.id as sueldo_id',
                    'datos_laborales.numero_cuenta',
                    'datos_laborales.tipo_cuenta',
                    'datos_laborales.fecha_culminacion',
                    'datos_laborales.fideicomiso',
                    'datos_laborales.departamento_id',
                    'cargos.id as cargo_id',
                    'cargos.descripcion',
                    'datos_laborales.pasante',
                    'datos_laborales.jubilado',
                    'datos_laborales.pensionado',
                    'datos_laborales.fecha_egreso',
                    'datos_laborales.dato_empleado_id')
                 ->where('datos_laborales.dato_empleado_id', $id)->get();

    $cargo = DB::table('cargos')->where('status', 1)->orderBy('descripcion', 'asc')->get();

    $departamentos = DB::table('departamentos')->get();

    $salarios = DB::table('salarios')->get();

    return view('editar_laborales_form', ['laborales' => $laborales, 'cargos' => $cargo, 'salarios' => $salarios, 'departamentos' => $departamentos]);
});

Route::post('/editar_laborales/{id}', 'personalController@editar_laborales');

Route::post('/eliminar_empleado/{id}', 'personalController@eliminar_empleado');

Route::post('/activar_empleado/{id}', function($id){

    DB::table('datos_empleados')->where('id', $id)->update(['status' => 'activo']);

    DB::table('datos_laborales')
        ->join('datos_empleados', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
        ->where('datos_empleados.id', $id)
        ->update(['datos_laborales.fecha_egreso' => '']);

    return redirect()->to('/editar_empleado_view')->with('alert', 'Empleado activado');
});

Route::get('/listado_movimientos_personal', function(){
    return view('listado_movimientos_personal');
});

Route::get('/filtrar_listado/{fecha_inicio}/{fecha_fin}', 'personalController@filtrar_listado');

/************************************************************************************************/

/************************************** CERRAR NOMINA ******************************************/
Route::get('/cierre_nomina', function(){
    return view('cerrar_nomina');
});

Route::post('/cerrar_nomina', 'cierreController@cerrar');

/************************************************************************************************/

/************************************** NOMINA OBREROS ******************************************/

Route::get('/crear_nomina_obrero_view', function(){
    return view('crear_nomina_obrero_view');
});

Route::post('/insertar_obrero', 'insertarEmpleadoController@insertarObrero');

Route::post('/crear_nomina_obrero', 'nominaController@crear_nomina_obreros');

Route::get('/show_nomina/{id_nomina}', function($id_nomina){

		    $obreros =  DB::table('datos_empleados')
		                ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
		                ->join('cargos', 'cargos.id', '=', 'datos_laborales.cargo_id')
                        ->join('salarios', 'datos_laborales.salario_id', '=', 'salarios.id')
		                ->where('datos_laborales.tipo_trabajador', '=', 'obrero')
                        ->where('datos_empleados.status', '=', 'activo')
		                ->select('datos_empleados.id','datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.cedula', 'salarios.sueldo', 'cargos.descripcion')
		                ->get();

    return view('crear_nomina_obrero', ['obreros' => $obreros, 'id_nomina' => $id_nomina]);
});

Route::get('/editar_nomina_obrero_view', function(){

    $nominas = DB::table('nomina')->where('tipo', 'obrero')->orderBy('id', 'desc')->paginate(30);

    return view('editar_nomina_obrero', ['nominas' => $nominas]);
});

Route::post('/eliminar_nomina_obrero/{id}', function($id){

    $resultados = DB::table('conceptos_nominas')->where('id_nomina', $id)->where('id_concepto', 20105)->select('id_empleado', 'monto')->get();

    foreach ($resultados as $resultado) {
        
        $deuda_actual = DB::table('descuentos')->where('id_empleado', $resultado->id_empleado)->pluck('deuda_actual');
        $cuota = $resultado->monto;
        $deuda_actual = $deuda_actual + $cuota;

        DB::table('descuentos')->where('id_empleado', $resultado->id_empleado)->update(['deuda_actual' => $deuda_actual]);
    }

    DB::table('nomina')->where('id', $id)->delete();
    DB::table('sueldo_nominas')->where('id_nomina', $id)->delete();

    return redirect()->to('/editar_nomina_obrero_view')->with('alert', 'Nomina eliminada');

});

Route::get('/editar_nomina_obrero_form/{id}', function($id){

    $nominas = DB::table('conceptos_nominas')
                        ->where('conceptos_nominas.id_nomina', $id)
                        ->join('datos_empleados', 'datos_empleados.id', '=', 'conceptos_nominas.id_empleado')
                        ->select('conceptos_nominas.id_empleado', 'datos_empleados.cedula', 'datos_empleados.nombre', 'datos_empleados.apellido')
                        ->distinct()
                        ->orderBy('conceptos_nominas.id', 'desc')
                        ->get();

    $empleados = DB::table('datos_empleados')
                        ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                        ->where('datos_laborales.tipo_trabajador', 'obrero')
                        ->where('status', 'activo')
                        ->select('datos_empleados.id as empleados_id', 'datos_empleados.cedula', 'datos_empleados.nombre', 'datos_empleados.apellido')
                        ->get();

    return view('editar_nomina_obrero_form', ['nominas' => $nominas, 'id_nomina' => $id, 'empleados' => $empleados]);

});

Route::post('/ingresar_personal_obrero_flotante/{id}', 'nominaController@ingresar_flotante_obrero');

Route::post('/eliminar_personal_obrero_nomina/{id_empleado}/nomina/{id_nomina}', 'nominaController@eliminar_peronal_nomina_obrero');

Route::get('/editar_personal_obrero_nomina/{id_empleado}/nomina/{id_nomina}', function($id_empleado, $id_nomina){

    $conceptos = DB::table('conceptos_nominas')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos_nominas.id_empleado', $id_empleado)
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->select('conceptos.descripcion', 'conceptos.tipo', 'conceptos_nominas.id', 'conceptos_nominas.monto', 'conceptos_nominas.referencia', 'conceptos.id as concepto_id')
                            ->orderBy('conceptos.id', 'asc')
                            ->get();

    $conceptos_nuevos = DB::table('conceptos')->orderBy('id', 'asc')->get();

    $deduccion = DB::table('conceptos_nominas')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos_nominas.id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'deduccion')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->select('conceptos.descripcion', 'conceptos.tipo', 'conceptos_nominas.id', 'conceptos_nominas.monto')
                            ->sum('monto');

    $asignacion = DB::table('conceptos_nominas')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos_nominas.id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->select('conceptos.descripcion', 'conceptos.tipo', 'conceptos_nominas.id', 'conceptos_nominas.monto')
                            ->sum('monto');

    $patronal = DB::table('conceptos_nominas')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos_nominas.id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'patronal')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->select('conceptos.descripcion', 'conceptos.tipo', 'conceptos_nominas.id', 'conceptos_nominas.monto')
                            ->sum('monto');

    $nombre = DB::table('datos_empleados')->where('id', $id_empleado)->pluck('nombre');
    $apellido = DB::table('datos_empleados')->where('id', $id_empleado)->pluck('apellido');

    $nombre = $nombre.' '.$apellido;

    $deduccion = round($deduccion, 2);
    $asignacion = round($asignacion, 2);
    $patronal = round($patronal, 2);

    return view('editar_conceptos_nomina', ['conceptos' => $conceptos, 'id_nomina' => $id_nomina, 'deduccion' => $deduccion, 'asignacion' => $asignacion, 'patronal' => $patronal, 'conceptos_nuevos' => $conceptos_nuevos, 'id_empleado' => $id_empleado, 'nombre' => $nombre]);

});

Route::post('/eliminar_personal_concepto_nomina/{id}/nomina/{id_nomina}', 'editarNominaController@eliminar_concepto');

Route::post('/editar_concepto/{concepto_id}/id/{id}', 'editarNominaController@editar');

/************************************************************************************************/


/*********************************** INSERTAR CONCEPTOS EN NOMINA *******************************/

Route::post('/insertar_concepto_nomina/{id_nomina}/empleado/{id_empleado}', 'insertarConceptoController@insertar_concepto');

Route::post('/insertar_concepto_nomina_empleado/{id_nomina}/empleado/{id_empleado}', 'insertarConceptoEmpleadoController@insertar_concepto');


/************************************************************************************************/

/*************************************** NOMINA EMPLEADOS ***************************************/

Route::get('/crear_nomina_empleado_view', function(){
    return view('crear_nomina_empleado_view');
});

Route::post('/crear_nomina_empleado', 'nominaEmpleadosController@crear_nomina_empleados');

Route::get('/editar_nomina_empleado_view', function(){

    $nominas = DB::table('nomina')->where('tipo', 'empleado')->orderBy('id', 'desc')->paginate(30);

    return view('editar_nomina_empleado', ['nominas' => $nominas]);
});

Route::get('/editar_nomina_empleado_form/{id}', function($id){

    $nominas = DB::table('conceptos_nominas')
                        ->where('conceptos_nominas.id_nomina', $id)
                        ->join('datos_empleados', 'datos_empleados.id', '=', 'conceptos_nominas.id_empleado')
                        ->select('conceptos_nominas.id_empleado', 'datos_empleados.cedula', 'datos_empleados.nombre', 'datos_empleados.apellido')
                        ->distinct()
                        ->orderBy('conceptos_nominas.id', 'desc')
                        ->get();


    $empleados = DB::table('datos_empleados')
                        ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                        ->where('datos_laborales.tipo_trabajador', 'empleado')
                        ->where('status', 'activo')
                        ->select('datos_empleados.id as empleados_id', 'datos_empleados.cedula', 'datos_empleados.nombre', 'datos_empleados.apellido')
                        ->get();

    return view('editar_nomina_empleado_form', ['nominas' => $nominas, 'id_nomina' => $id, 'empleados' => $empleados]);

});

Route::post('/ingresar_personal_empleado_flotante/{id}', 'nominaEmpleadosController@ingresar_flotante_empleado');

Route::get('/editar_personal_empleado_nomina/{id_empleado}/nomina/{id_nomina}', function($id_empleado, $id_nomina){

    $conceptos = DB::table('conceptos_nominas')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos_nominas.id_empleado', $id_empleado)
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->select('conceptos.descripcion', 'conceptos.tipo', 'conceptos_nominas.id', 'conceptos_nominas.monto', 'conceptos_nominas.referencia', 'conceptos.id as concepto_id')
                            ->orderBy('conceptos.id', 'asc')
                            ->get();

    $conceptos_nuevos = DB::table('conceptos')->orderBy('descripcion', 'asc')->get();

    $deduccion = DB::table('conceptos_nominas')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos_nominas.id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'deduccion')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->select('conceptos.descripcion', 'conceptos.tipo', 'conceptos_nominas.id', 'conceptos_nominas.monto')
                            ->sum('monto');

    $asignacion = DB::table('conceptos_nominas')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos_nominas.id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'asignacion')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->select('conceptos.descripcion', 'conceptos.tipo', 'conceptos_nominas.id', 'conceptos_nominas.monto')
                            ->sum('monto');

    $patronal = DB::table('conceptos_nominas')
                            ->where('conceptos_nominas.id_nomina', $id_nomina)
                            ->where('conceptos_nominas.id_empleado', $id_empleado)
                            ->where('conceptos.tipo', 'patronal')
                            ->join('conceptos', 'conceptos.id', '=', 'conceptos_nominas.id_concepto')
                            ->select('conceptos.descripcion', 'conceptos.tipo', 'conceptos_nominas.id', 'conceptos_nominas.monto')
                            ->sum('monto');

    $nombre = DB::table('datos_empleados')->where('id', $id_empleado)->pluck('nombre');
    $apellido = DB::table('datos_empleados')->where('id', $id_empleado)->pluck('apellido');

    $nombre = $nombre.' '.$apellido;

    $deduccion = round($deduccion, 2);
    $asignacion = round($asignacion, 2);
    $patronal = round($patronal, 2);

    return view('editar_conceptos_nomina_empleado', ['conceptos' => $conceptos, 'id_nomina' => $id_nomina, 'deduccion' => $deduccion, 'asignacion' => $asignacion, 'patronal' => $patronal, 'conceptos_nuevos' => $conceptos_nuevos, 'id_empleado' => $id_empleado, 'nombre' => $nombre]);

});

Route::post('/eliminar_personal_concepto_nomina_empleado/{id}/nomina/{id_nomina}', 'editarNominaEmpleadoController@eliminar_concepto');

Route::post('/editar_concepto_empleado/{concepto_id}/id/{id}', 'editarNominaEmpleadoController@editar');

Route::post('/eliminar_nomina_empleado/{id}', function($id){

    $resultados = DB::table('conceptos_nominas')->where('id_nomina', $id)->where('id_concepto', 20105)->select('id_empleado', 'monto')->get();

    foreach ($resultados as $resultado) {
        
        $deuda_actual = DB::table('descuentos')->where('id_empleado', $resultado->id_empleado)->pluck('deuda_actual');
        $cuota = $resultado->monto;
        $deuda_actual = $deuda_actual + $cuota;

        DB::table('descuentos')->where('id_empleado', $resultado->id_empleado)->update(['deuda_actual' => $deuda_actual]);
    }

    DB::table('nomina')->where('id', $id)->delete();
    DB::table('sueldo_nominas')->where('id_nomina', $id)->delete();

    return redirect()->to('/editar_nomina_empleado_view')->with('alert', 'Nomina eliminada');

});

/************************************************************************************************/

/****************************************** REPORTES ********************************************/

Route::get('/reporte/{id_nomina}', 'reporteController@crear');

/************************************************************************************************/

/****************************************** CASOS PARTICULARES ********************************************/

Route::get('/casos_particulares_view', function(){

    $casos_particulares = DB::table('casos_particulares')
                            ->join('datos_empleados', 'casos_particulares.dato_empleado_id', '=', 'datos_empleados.id')
                            ->join('conceptos', 'casos_particulares.concepto_id', '=', 'conceptos.id')
                            ->select('conceptos.descripcion', 'datos_empleados.nombre', 'datos_empleados.apellido', 'casos_particulares.id')
                            ->get();

    $empleados = DB::table('datos_empleados')->orderBy('nombre', 'asc')->get();
    $conceptos = DB::table('conceptos')->orderBy('descripcion', 'asc')->get();

    return view('casos_particulares', ['casos_particulares' => $casos_particulares, 'empleados' => $empleados, 'conceptos' => $conceptos]);
});

Route::post('/casos_particulares', 'casoParticularController@crear');

Route::post('/eliminar_particular/{id}', 'casoParticularController@eliminar');

/************************************************************************************************/

/******************************************* Buscar **********************************************/

Route::get('/buscar', 'buscarController@busqueda_universal');

/**************************************************************************************************/

/*************************************** DescargarTXT **********************************************/

Route::get('/descargar/{id_nomina}', 'descargarController@descargar');


/**************************************************************************************************/

/*************************************** Usuarios *************************************************/

Route::get('/usuarios', function(){

    $usuarios = DB::table('users')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->select('users.nombre', 'roles.descripcion', 'roles.id', 'users.id as user_id')
                    ->get();

    $roles = DB::table('roles')->get();

    return view('usuarios', ['usuarios' => $usuarios, 'roles' => $roles]);
});

Route::post('/registrar_usuario', 'usuariosController@registrar');

Route::get('/eliminar_usuario/{id}', function($id){

    DB::table('users')->where('id', $id)->delete();

    return redirect()->to('/usuarios')->with('alert', 'Usuario eliminado');

});

/**************************************************************************************************/

/*************************************** Cargos ***************************************************/

Route::post('/crear_cargo', 'cargosController@create');

Route::post('/editar_cargo/{id}', 'cargosController@edit');

Route::get('/cargos', function(){

    $cargos = DB::table('cargos')->orderBy('descripcion', 'asc')->get();

   return view('cargos', ['cargos' => $cargos]);
});

Route::post('/borrar_cargos/{id}', function($id){

    DB::table('cargos')->where('id', $id)->update(['status' => 0]);

    return redirect()->back()->with('alert', 'Cargo eliminado');
});

Route::post('/activar_cargos/{id}', function($id){

    DB::table('cargos')->where('id', $id)->update(['status' => 1]);

    return redirect()->back()->with('alert', 'Cargo activado');
});

/***************************************************************************************************/

/********************************** Vacaciones *****************************************************/

Route::get('/crear_vacaciones', function(){
    return view('crear_vacaciones');
});

Route::post('/buscar', 'vacacionesController@buscar');

Route::post('/crear_vacaciones', 'vacacionesController@create');

Route::get('/ver_vacaciones', function(){

    $vacaciones = DB::table('vacaciones')
                    ->join('datos_empleados', 'vacaciones.dato_empleado_id', '=', 'datos_empleados.id')
                    ->select('vacaciones.fecha_inicio', 'vacaciones.fecha_fin', 'vacaciones.id', 'vacaciones.fecha_registro', 'datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.cedula')
                    ->get();

    return view('vacaciones', ['vacaciones' => $vacaciones]);
});

Route::get('/reporte_vacaciones/{id}', function($id){

    $reporte = DB::table('vacaciones')
                ->join('datos_empleados', 'vacaciones.dato_empleado_id', '=', 'datos_empleados.id')
                ->select('vacaciones.fecha_inicio', 'vacaciones.fecha_fin', 'vacaciones.id', 'vacaciones.fecha_registro', 'vacaciones.dias_vacaciones', 'vacaciones.bono_vacacional', 'vacaciones.vacaciones_pagar', 'vacaciones.bono_vacacional_pagar', 'vacaciones.faov', 'datos_empleados.nombre', 'datos_empleados.apellido', 'datos_empleados.cedula',
                    'vacaciones.asignacion')
                ->where('vacaciones.id', $id)
                ->get();

    $html = view('pdf.vacaciones')->with('reportes', $reporte)->render();

    return PDF::load($html)->show();

});

Route::get('/prima_vacaciones', function(){

    $personas = DB::table('datos_empleados')
                    ->where('datos_empleados.status', 'activo')
                    ->get();
    return view('prima_vacaciones', ['personas' => $personas]);
});

Route::get('/prima_vacaciones/activar/{id}', 'vacacionesController@activar');
Route::get('/prima_vacaciones/desactivar/{id}', 'vacacionesController@desactivar');


Route::post('/eliminar_vacacion/{id}', 'vacacionesController@eliminar');

Route::get('/disfrute_vacacional', 'vacacionesController@verDisfruteVacacional');
Route::get('/disfrute_vacacional/buscar/{id}', 'vacacionesController@buscarPersona');
Route::post('/disfrute_vacacional/registrar', 'vacacionesController@registrar');
Route::post('/disfrute_vacacional/eliminar/{id}', 'vacacionesController@eliminar_disfrute');

/***************************************************************************************************/


/******************************************** Salarios *********************************************/

Route::get('/salarios', function(){

    $sueldos = DB::table('salarios')->orderBy('descripcion', 'ASC')->get();

    return view('salarios', ['sueldos' => $sueldos]);
});

Route::post('/insertar_salario', 'salarioController@create');

Route::post('/editar_salario/{id}', 'salarioController@editar');

Route::post('/eliminar_salario/{id}', 'salarioController@eliminar');


/***************************************************************************************************/

/******************************************** Retenciones *********************************************/

Route::get('/retenciones_nomina', function(){

    return view('retenciones_nomina');

});

Route::post('/generarRetencionesPDF', 'retencionesNominaController@generarPDF');
Route::post('/generarTxtTesoreria', 'retencionesNominaController@generarTxtTesoreria');

/***************************************************************************************************/

/*************************************** Configuracion *********************************************/

Route::get('/configuracion', function(){
    $datos = DB::table('panel_control')->get();
    return view('configuracion', ['datos' => $datos]);
});

Route::post('/editar_panel_control', 'configuracionController@registrar');

/***************************************************************************************************/

/********************************* Departamentos ***************************************************/

Route::get('/departamentos', function(){

    $departamentos = DB::table('departamentos')->get();

    return view('crear_departamento', ['departamentos' => $departamentos]);
});

Route::post('/crear_departamento', 'departamentoController@create');

Route::post('/borrar_departamento/{id}', 'departamentoController@delete');

Route::post('/editar_departamento/{id}', 'departamentoController@edit');

/***************************************************************************************************/

/********************************* LISTADO PERSONAL ************************************************/

Route::get('listado_personal', function(){
    return view('listado_personal');
});

Route::post('/generar_listado_trabajadores', 'listado_personalController@generar');

/***************************************************************************************************/

/********************************* DEVENGADO ************************************************/

Route::get('/devengado', function(){
    return view('devengado');
});
Route::post('/generarDevengadoPDF', 'devengadoController@generarPDF');

/***************************************************************************************************/

/********************************* CARGA FAMILIAR ************************************************/

Route::get('/carga_familiar_view', function(){
    return view('carga_familiar_view');
});

Route::get('/carga_familiar/{id}', function($id){


    $carga = DB::table('carga_familiar')->where('datos_empleados_id', $id)->get();

    return view('carga_familiar', ['id_personal' => $id, 'cargas' => $carga]);

});

Route::post('/registrar_familiar/{id}', 'personalController@familia');

Route::post('/editar_familiar/{id}', 'carga_familiarController@editar');

Route::post('/eliminar_familiar/{id}', function($id){

    DB::table('carga_familiar')->where('id', $id)->delete();

    return redirect()->back()->with('alert', 'Familiar eliminado');

});

Route::post('/carga_familiar_pdf', 'carga_familiarController@create');

Route::get('/carga_familiar_todos_pdf', 'carga_familiarController@create_all');

/***************************************************************************************************/

/************************************* TRIMIESTRE **************************************************/

Route::get('/trimestre', function(){
    return view('trimestre');
});

Route::post('/trimestre', 'trimestreController@create');

/***************************************************************************************************/

/************************************* LIQUIDACIONES ***********************************************/

Route::get('/liquidaciones', function(){
    return view('liquidaciones');
});

Route::get('/ver_liquidaciones', function(){

    $liquidaciones = DB::table('liquidaciones')
                        ->join('datos_empleados', 'liquidaciones.dato_empleado_id', '=', 'datos_empleados.id')
                        ->select('datos_empleados.cedula', 'datos_empleados.nombre', 'datos_empleados.apellido', 'liquidaciones.fecha_egreso', 'liquidaciones.fecha_registro', 'liquidaciones.id')
                        ->get();

    return view('ver_liquidaciones', ['liquidaciones' => $liquidaciones]);
});

Route::post('/liquidar', 'liquidacionController@create');

Route::post('/eliminar_liquidacion/{id}', 'liquidacionController@delete');

Route::get('/verificar_liquidacion/{id}', 'liquidacionController@verificar_liquidacion');

/***************************************************************************************************/

/******************************** IMPRIMIR PLANILLA PERSONAL ***************************************/

Route::get('/planilla_personal_view', function(){
    return view('planilla_personal_view');
});

Route::post('/planilla_personal_form', 'personalController@imprimir_planilla');

/***************************************************************************************************/

/******************************** Descuentos ***************************************/

Route::get('/descuentos', function(){

    $descuentos = DB::table('descuentos')
                        ->join('datos_empleados', 'descuentos.id_empleado', '=', 'datos_empleados.id')
                        ->select('datos_empleados.cedula', 'datos_empleados.nombre', 'datos_empleados.apellido', 'descuentos.deuda_inicial', 'descuentos.deuda_actual', 'descuentos.cuota', 'descuentos.id')
                        ->get();

    return view('descuentos', ['descuentos' => $descuentos]);
});

Route::post('/crear_descuento', 'descuentosController@crear');

Route::post('/borrar_descuento/{id}', 'descuentosController@eliminar');


/***************************************************************************************************/

/******************************** Expediente ***************************************/

Route::get('/expedientes', 'expedienteController@show');
Route::get('/encontrarExpedientes/{opcion}/{dato}', 'expedienteController@find');
Route::get('/documentos/{id}', 'expedienteController@mostrarDocs');
Route::post('/documentosVarios/{id}', 'expedienteController@documentosVarios');
Route::get('/documentosVarios/eliminar/{id}', 'expedienteController@eliminarDocumentosVarios');

Route::post('/expediente/subirDoc/{tipo}/{id_empleado}', 'expedienteController@registrar');
Route::get('/expediente/eliminar/{id}/{doc}', 'expedienteController@eliminar');


/***************************************************************************************************/

/******************************** Reposo Médico ***************************************/

Route::get('resposo_medico', 'reposoController@view');
Route::get('/reposo_medico/buscar_personal/{cedula}', 'reposoController@buscar');
Route::post('/reposo_medico/registrar', 'reposoController@registrar');
Route::post('/reposo_medico/eliminar/{id}', 'reposoController@eliminar');

/***************************************************************************************************/

/******************************** Tarjeta de alimentación ***************************************/

Route::get('/tarjetaAlimentacion/generacionClientes', 'tarjetaAlimentacionController@generarClientesView');
Route::post('/tarjetaAlimentacion/generarClientes', 'tarjetaAlimentacionController@generarClientes');

Route::get('/tarjetaAlimentacion/abono', 'tarjetaAlimentacionController@abonoView');
Route::post('/tarjetaAlimentacion/abono', 'tarjetaAlimentacionController@abono');

/***************************************************************************************************/

});
