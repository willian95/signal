<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatosLaborales extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('datos_laborales', function(Blueprint $table){
            $table->increments('id');
            $table->string('fecha_ingreso');
            $table->enum('tipo_trabajador', ['empleado', 'obrero']);
            $table->enum('tipo_contrato', ['fijo', 'contratado']);
            $table->double('sueldo', 6, 2);
            $table->enum('tipo_cuenta', ['corriente', 'ahorro']);
            $table->string('numero_cuenta');
            $table->string('fecha_culminacion');
            $table->double('fideicomiso');
            
            $table->integer('dato_empleado_id')->unsigned();
            $table->foreign('dato_empleado_id')->references('id')->on('datos_empleados')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('cargo_id')->unsigned();
            $table->foreign('cargo_id')->references('id')->on('cargos')->onDelete('cascade')->onUpdate('cascade');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('datos_laborales');
	}

}
