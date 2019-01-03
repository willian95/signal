<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model {

	protected $table = 'cargos';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['descripcion'];
    
    public function Dato_laboral(){
        return $this->hasMany('App\Dato_laboral');
    }

}
