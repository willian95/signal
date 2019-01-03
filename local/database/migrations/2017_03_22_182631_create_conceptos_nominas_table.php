<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptosNominasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('conceptos_nominas', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('id_nomina')->unsigned();
			$table->foreign('id_nomina')->references('id')->on('nomina')->onDelete('cascade')->onUpdate('cascade');

			$table->integer('id_concepto')->unsigned();
			$table->foreign('id_concepto')->references('id')->on('conceptos')->onDelete('cascade')->onUpdate('cascade');

			$table->integer('id_empleado')->unsigned();
			$table->foreign('id_empleado')->references('id')->on('datos_empleados')->onDelete('cascade')->onUpdate('cascade');

			$table->integer('referencia');

			$table->double('monto');

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
		Schema::drop('conceptos_nominas');
	}

}
