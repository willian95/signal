<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class usuariosController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function registrar(Request $request)
	{
		$nombre = $request->nombre;
        $email = $request->correo;
        $clave= $request->clave;
        $role = $request->role;
        
        $ver = DB::table('users')->where('email', $email)->get();
        
        if($ver != null){
            return redirect()->back()->with('alert','Email ya existente');
        }
        
        else{
            DB::table('users')->insert(['nombre' => $nombre, 'email' => $email, 'password' => bcrypt($clave), 'role_id' => $role]);
            return redirect()->back()->with('alert', 'Usuario registrado');
        }
        
	}

}
