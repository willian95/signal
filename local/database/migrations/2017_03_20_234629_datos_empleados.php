<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatosEmpleados extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('datos_empleados', function(Blueprint $table){
            $table->increments('id');
			$table->string('nombre');
            $table->string('apellido');
            $table->string('cedula');
            $table->string('rif');
            $table->enum('sexo', ['m', 'f']);
            $table->string('profesion');
            $table->string('fecha_nacimiento');
            $table->enum('estado_civil', ['casado', 'soltero', 'viudo', 'divorciado']);
            $table->text('direccion');
            $table->string('parroquia');
            $table->string('municipio');
            $table->string('correo')->unique();
            $table->string('tlf_movil');
            $table->string('tlf_fijo');
            $table->enum('tipo_carrera', ['larga', 'corta']);
            $table->enum('status', ['activo', 'inactivo']);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('datos_empleados');
	}

}
