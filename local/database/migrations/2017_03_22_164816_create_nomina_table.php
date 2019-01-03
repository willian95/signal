<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNominaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nomina', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('tipo');
			$table->string('fecha_inicio');
            $table->string('fecha_fin');
			$table->double('asignaciones');
            $table->double('deducciones');
			$table->double('patronal');
            
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('nomina');
	}

}
