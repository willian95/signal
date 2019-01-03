<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Dato_laboral extends Model {

	protected $table = 'datos_laborales';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['fecha_ingreso', 'tipo_trabajador', 'tipo_contrato', 'sueldo', 'tipo_cuenta', 'numero_cuenta', 'fecha_culminacion', 'dato_empleado_id'];
    
    public function Dato_empleado(){
        return $this->belongsTo('App\Dato_empleado', 'dato_empleado_id');
    }
    
    public function Cargo(){
        return $this->belongsTo('App\Cargo', 'cargo_id');
    }
    
}
