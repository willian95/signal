<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CargaFamiliar extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carga_familiar', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nombres');
            $table->string('carga');
            $table->integer('datos_empleados_id')->unsigned();
            
            $table->foreign('datos_empleados_id')->references('id')->on('datos_empleados')->onDelete('cascade')->onUpdate('cascade');
            
			$table->timestamp('created_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
