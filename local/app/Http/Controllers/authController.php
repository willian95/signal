<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;

use Illuminate\Http\Request;

class authController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

    public function handlelogin(Request $request)
	{	
		$data = $request->only('email', 'password');

		if(\Auth::attempt($data)){
			return redirect()->to('/landing');
		}

		else{
			$alert = "Usuario no encontrado";
			return redirect()->to('/')->with('alert', $alert);
		}
	}

	public function logout(){
		\Auth::logout();
		return  redirect()->to('/');
	}

}
