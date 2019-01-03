<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class descargarController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	*/

    public function agregaCerosIzquierda($valor) 
	{
			switch(strlen($valor)) 
			{
				case 1:	    $numMov	=	'0000000000000000'.$valor; break;
				case 2:	    $numMov	=	'000000000000000'.$valor; break;
				case 3:	    $numMov	=	'00000000000000'.$valor; break;
				case 4:	    $numMov	=	'0000000000000'.$valor; break;
				case 5:	    $numMov	=	'000000000000'.$valor; break;
				case 6:     $numMov	=	'00000000000'.$valor; break;
				case 7:     $numMov	=	'0000000000'.$valor; break;
				case 8:     $numMov	=	'000000000'.$valor; break;
				case 9:     $numMov	=	'00000000'.$valor; break;
				case 10:    $numMov	=	'0000000'.$valor; break;
				case 11:    $numMov	=	'000000'.$valor; break;
				case 12:    $numMov	=	'00000'.$valor; break;
				case 13:    $numMov	=	'0000'.$valor; break;
				case 14:    $numMov	=	'000'.$valor; break;
				case 15:    $numMov	=	'00'.$valor; break;
				case 16:    $numMov	=	'0'.$valor; break;
				case 17:    $numMov	=	$valor; break;
			}
		
		return $numMov;
	}

    function agregaCerosIzquierda_registros($valor) 
	{
			switch(strlen($valor)) 
			{
				case 1:    $numMov	=	'000'.$valor; break;
				case 2:    $numMov	=	'00'.$valor; break;
				case 3:    $numMov	=	'0'.$valor; break;
				case 4:    $numMov	=	$valor; break;
			}
		
		return $numMov;
	}

    function agregaCerosIzquierda_neto_a_pagar($valor) 
	{
			switch(strlen($valor)) 
			{
				case 1:     $numMov	=	'00000000000'.$valor; break;
				case 2:     $numMov	=	'0000000000'.$valor; break;
				case 3:     $numMov	=	'000000000'.$valor; break;
				case 4:     $numMov	=	'00000000'.$valor; break;
				case 5:     $numMov	=	'0000000'.$valor; break;
				case 6:     $numMov	=	'000000'.$valor; break;
				case 7:     $numMov	=	'00000'.$valor; break;
				case 8:     $numMov	=	'0000'.$valor; break;
				case 9:     $numMov	=	'000'.$valor; break;
				case 10:    $numMov	=	'00'.$valor; break;
				case 11:    $numMov	=	'0'.$valor; break;
				case 12:    $numMov	=	$valor; break;
			}
		
		return $numMov;
	}

    function agregaCerosIzquierda_cedula($valor) 
	{
			switch(strlen($valor)) 
			{
				case 1:  $numMov1	=	'000000000'.$valor; break;
				case 2:  $numMov1	=	'00000000'.$valor; break;
				case 3:	 $numMov1	=	'0000000'.$valor; break;
				case 4:	 $numMov1	=	'000000'.$valor; break;
				case 5:	 $numMov1	=	'00000'.$valor; break;
				case 6:	 $numMov1	=	'0000'.$valor; break;
				case 7:	 $numMov1	=	'000'.$valor; break;
				case 8:  $numMov1	=	'00'.$valor; break;
				case 9:  $numMov1	=	'0'.$valor; break;
				case 10: $numMov1	=	$valor; break;
			}
		
		return $numMov1;
	}	

	public function descargar($id_nomina){
        
        $headers = array(
              'Content-Type: application/plain-text',
            );


        $cantidad_personas = 0;

        $cedulas = DB::table('conceptos_nominas')
                    ->where('conceptos_nominas.id_nomina', $id_nomina)
                    ->join('datos_empleados', 'conceptos_nominas.id_empleado', '=', 'datos_empleados.id')
                    ->join('datos_laborales', 'datos_laborales.dato_empleado_id', '=', 'datos_empleados.id')
                    ->select('datos_empleados.cedula', 'datos_empleados.id', 'datos_laborales.numero_cuenta')
                    ->distinct()
                    ->get();

        foreach($cedulas as $cedula){
        	$cantidad_personas++;
        }
        
        $personas = DB::table('conceptos_nominas')
                        ->join('datos_empleados', 'conceptos_nominas.id_empleado', '=', 'datos_empleados.id')
                        ->where('conceptos_nominas.id_nomina', $id_nomina)
                        ->get();
        
        $asignaciones_total = DB::table('conceptos_nominas')
                                    ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                                    ->where('conceptos_nominas.id_nomina', $id_nomina)
                                    ->where('conceptos.tipo', 'asignacion')
                                    ->sum('conceptos_nominas.monto');
        
        $deducciones_total = DB::table('conceptos_nominas')
                                    ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                                    ->where('conceptos_nominas.id_nomina', $id_nomina)
                                    ->where('conceptos.tipo', 'deduccion')
                                    ->sum('conceptos_nominas.monto');
        
        $total_nomina = $asignaciones_total - $deducciones_total;
        $total_nomina = number_format($total_nomina, 2, '', '');
        
        $fecha_actual = getdate();
        $annio_actual =  $fecha_actual['year'];
        $mes_actual = $fecha_actual['mon'];
        $dia_actual = $fecha_actual['mday'];

        if($mes_actual < 10){
        	$mes_actual = '0'.$mes_actual;
        }
	
        $fecha = $annio_actual.$mes_actual.$dia_actual;

        $contenido= "01750113500071145882".$fecha.$this->agregaCerosIzquierda($total_nomina).$this->agregaCerosIzquierda_registros($cantidad_personas);
		\Storage::disk('local')->put('BICENTENARION'.$id_nomina.'.txt', $contenido);

        foreach($cedulas as $cedula){
                
            $asignaciones = DB::table('conceptos_nominas')
                                    ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                                    ->where('conceptos_nominas.id_nomina', $id_nomina)
                                    ->where('conceptos_nominas.id_empleado', $cedula->id)
                                    ->where('conceptos.tipo', 'asignacion')
                                    ->sum('conceptos_nominas.monto');
                
            $deducciones = DB::table('conceptos_nominas')
                                    ->join('conceptos', 'conceptos_nominas.id_concepto', '=', 'conceptos.id')
                                    ->where('conceptos_nominas.id_nomina', $id_nomina)
                                    ->where('conceptos_nominas.id_empleado', $cedula->id)
                                    ->where('conceptos.tipo', 'deduccion')
                                    ->sum('conceptos_nominas.monto');
            
            $total = $asignaciones-$deducciones;
            $total = round($total, 2);

            $total = number_format($total, 2, '', '');

            $contenido= "0746".$this->agregaCerosIzquierda_neto_a_pagar($total).$cedula->numero_cuenta.$this->agregaCerosIzquierda_cedula($cedula->cedula)."00000"."0"."00";
            \Storage::append('BICENTENARION'.$id_nomina.'.txt', $contenido);
        }

        return response()->download(public_path().'/descargas/BICENTENARION'.$id_nomina.'.txt', 'BICENTENARION'.$id_nomina.'.txt', $headers);
        return redirect()->back();
		
    }
}
