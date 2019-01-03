<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Dato_empleado extends Model {

	protected $table = 'datos_empleados';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'apellido', 'cedula', 'rif', 'sexo', 'profesion', 'fecha_nacimiento', 'estado_civil', 'direccoin', 'parroquia', 'correo', 'tlf_movil', 'tlf_fijo'];
    
    public function Dato_laboral(){
        return $this->hasMany('App\Dato_laboral');
    }

}
