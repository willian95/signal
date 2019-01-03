<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('conceptos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('descripcion');
			$table->enum('tipo', ['deduccion', 'asignacion', 'patronal']);
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
		Schema::drop('conceptos');
	}

}
