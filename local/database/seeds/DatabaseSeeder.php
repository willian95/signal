<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		 $this->call('RoleTableSeeder');
		 $this->call('CargoTableSeeder');
         $this->call('ConceptosTableSeeder');
	}

}

class RoleTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->insert(['id' => 1, 'descripcion' => 'usuario']);
        DB::table('roles')->insert(['id' => 2, 'descripcion' => 'administrador']);
    }

}

class CargoTableSeeder extends Seeder {

    public function run()
    {

        DB::table('cargos')->insert(['id'=>1, 'descripcion' => 'ANALISTA CONTABLE']);
        DB::table('cargos')->insert(['id'=>2, 'descripcion' => 'ANALISTA DE ADMINISTRACION']);
        DB::table('cargos')->insert(['id'=>3, 'descripcion' => 'ANALISTA DE COMPRAS']);
        DB::table('cargos')->insert(['id'=>4, 'descripcion' => 'ANALISTA DE INFRAESTRUCTURA']);
        DB::table('cargos')->insert(['id'=>5, 'descripcion' => 'ANALISTA DE PLANIFICACION Y PRESUPUESTO']);
        DB::table('cargos')->insert(['id'=>6, 'descripcion' => 'ANALISTA DE PROGRAMACION DE RADIO']);
        DB::table('cargos')->insert(['id'=>7, 'descripcion' => 'ANALISTA DE RECURSOS HUMANOS']);
        DB::table('cargos')->insert(['id'=>8, 'descripcion' => 'ANALISTA DE SOPORTE TECNICO Y TELECOMUNICACIONES']);
        DB::table('cargos')->insert(['id'=>9, 'descripcion' => 'ANALISTA JURIDICO']);
        DB::table('cargos')->insert(['id'=>10, 'descripcion' => 'ASISTENTE ADMINISTRATIVO']);
        DB::table('cargos')->insert(['id'=>11, 'descripcion' => 'ASISTENTE DE ASUNTOS PUBLICOS Y ATENCION AL CIUDADANO']);
        DB::table('cargos')->insert(['id'=>12, 'descripcion' => 'ASISTENTE DE AUDITORIA INTERNA']);
        DB::table('cargos')->insert(['id'=>13, 'descripcion' => 'ASISTENTE DE BIENES']);
        DB::table('cargos')->insert(['id'=>14, 'descripcion' => 'ASISTENTE DE CONSULTORIA JURIDICA']);
        DB::table('cargos')->insert(['id'=>15, 'descripcion' => 'ASISTENTE DE GERENCIA GENERAL']);
        DB::table('cargos')->insert(['id'=>16, 'descripcion' => 'ASISTENTE DE NOMINA']);
        DB::table('cargos')->insert(['id'=>17, 'descripcion' => 'ASISTENTE DE OPERACIONES']);
        DB::table('cargos')->insert(['id'=>18, 'descripcion' => 'ASISTENTE DE PLANIFICACION Y PRESUPUESTO']);
        DB::table('cargos')->insert(['id'=>19, 'descripcion' => 'ASISTENTE DE PRESIDENCIA']);
        DB::table('cargos')->insert(['id'=>20, 'descripcion' => 'ASISTENTE DE RADIO']);
        DB::table('cargos')->insert(['id'=>21, 'descripcion' => 'ASISTENTE DE RECURSOS HUMANOS']);
        DB::table('cargos')->insert(['id'=>22, 'descripcion' => 'ASISTENTE DE SEGURIDAD, HIGIENE Y AMBIENTE']);
        DB::table('cargos')->insert(['id'=>23, 'descripcion' => 'ASISTENTE DE TIC']);
        DB::table('cargos')->insert(['id'=>24, 'descripcion' => 'ASISTENTE DE TRAMITACION Y CAJA']);
        DB::table('cargos')->insert(['id'=>25, 'descripcion' => 'AUDITOR INTERNO']);
        DB::table('cargos')->insert(['id'=>26, 'descripcion' => 'AYUDANTE DE COCINA']);
        DB::table('cargos')->insert(['id'=>27, 'descripcion' => 'AYUDANTE DE PANADERIA']);
        DB::table('cargos')->insert(['id'=>28, 'descripcion' => 'AYUDANTE DE PANADERO']);
        DB::table('cargos')->insert(['id'=>29, 'descripcion' => 'ESPECIALISTA DE PRESUPUESTO']);
        DB::table('cargos')->insert(['id'=>30, 'descripcion' => 'COCINERO']);
        DB::table('cargos')->insert(['id'=>31, 'descripcion' => 'CONDUCTOR']);
        DB::table('cargos')->insert(['id'=>32, 'descripcion' => 'CONSULTOR JURIDICO']);
        DB::table('cargos')->insert(['id'=>33, 'descripcion' => 'COORDINADOR DE ASUNTOS PUBLICOS Y ATENCION AL CIUDADANO']);
        DB::table('cargos')->insert(['id'=>34, 'descripcion' => 'COORDINADOR DE RADIO']);
        DB::table('cargos')->insert(['id'=>35, 'descripcion' => 'COORDINADOR DE BIENES Y SERVICIOS']);
        DB::table('cargos')->insert(['id'=>36, 'descripcion' => 'COORDINADOR DE COMEDOR INDUSTRIAL']);
        DB::table('cargos')->insert(['id'=>37, 'descripcion' => 'COORDINADOR DE COMPRAS']);
        DB::table('cargos')->insert(['id'=>38, 'descripcion' => 'COORDINADOR DE CONTABILIDAD']);
        DB::table('cargos')->insert(['id'=>39, 'descripcion' => 'COORDINADOR DE INFRAESTRUCTURA']);
        DB::table('cargos')->insert(['id'=>40, 'descripcion' => 'COORDINADOR DE OPERACIONES']);
        DB::table('cargos')->insert(['id'=>41, 'descripcion' => 'COORDINADOR DE PLANIFICACION Y PRESUPUESTO']);
        DB::table('cargos')->insert(['id'=>42, 'descripcion' => 'COORDINADOR DE RECURSOS HUMANOS']);
        DB::table('cargos')->insert(['id'=>43, 'descripcion' => 'COORDINADOR DE SEGURIDAD']);
        DB::table('cargos')->insert(['id'=>44, 'descripcion' => 'COORDINADOR DE SEGURIDAD, HIGIENE Y AMBIENTE']);
        DB::table('cargos')->insert(['id'=>45, 'descripcion' => 'COORDINADOR DE TIC']);
        DB::table('cargos')->insert(['id'=>46, 'descripcion' => 'COORDINADOR DE TRIBUTOS']);
        DB::table('cargos')->insert(['id'=>47, 'descripcion' => 'DIRECTOR DE RADIO']);
        DB::table('cargos')->insert(['id'=>48, 'descripcion' => 'DISEÑADOR GRAFICO']);
        DB::table('cargos')->insert(['id'=>49, 'descripcion' => 'ENCARGADO DE COCINA']);
        DB::table('cargos')->insert(['id'=>50, 'descripcion' => 'ENTRENADOR DEPORTIVO']);
        DB::table('cargos')->insert(['id'=>51, 'descripcion' => 'ESPECIALISTA DESARROLLADOR DE SOFTWARE']);
        DB::table('cargos')->insert(['id'=>52, 'descripcion' => 'ESPECIALISTA EN OPERACIONES ADUANERAS']);
        DB::table('cargos')->insert(['id'=>53, 'descripcion' => 'GERENTE DE ADMINISTRACION Y FINANZAS']);
        DB::table('cargos')->insert(['id'=>54, 'descripcion' => 'GERENTE DE ASUNTOS PUBLICOS Y ATENCION AL CIUDADANO']);
        DB::table('cargos')->insert(['id'=>55, 'descripcion' => 'GERENTE DE INFRAESTRUCTURA']);
        DB::table('cargos')->insert(['id'=>56, 'descripcion' => 'GERENTE DE OPERACIONES']);
        DB::table('cargos')->insert(['id'=>57, 'descripcion' => 'GERENTE DE PLANIFICACION Y PRESUPUESTO']);
        DB::table('cargos')->insert(['id'=>58, 'descripcion' => 'GERENTE DE PROMOCION Y COMERCIALIZACION']);
     	DB::table('cargos')->insert(['id'=>59, 'descripcion' => 'GERENTE DE RECURSOS HUMANOS']);
     	DB::table('cargos')->insert(['id'=>60, 'descripcion' => 'GERENTE DE TECNOLOGIA, INFORMACION Y COMUNICACION']);
     	DB::table('cargos')->insert(['id'=>61, 'descripcion' => 'GERENTE GENERAL']);
     	DB::table('cargos')->insert(['id'=>62, 'descripcion' => 'INSPECTOR DE INFRAESTRUCTURA']);
     	DB::table('cargos')->insert(['id'=>63, 'descripcion' => 'PANADERO']);
     	DB::table('cargos')->insert(['id'=>64, 'descripcion' => 'PROGRAMADOR DE RADIO']);
     	DB::table('cargos')->insert(['id'=>65, 'descripcion' => 'RECEPCIONISTA']);   
     	DB::table('cargos')->insert(['id'=>66, 'descripcion' => 'SOLDADOR']);
     	DB::table('cargos')->insert(['id'=>67, 'descripcion' => 'SUPERVISOR DE INFRAESTRUCTURA']);
     	DB::table('cargos')->insert(['id'=>68, 'descripcion' => 'SUPERVISOR DE MANTENIMIENTO']);
     	DB::table('cargos')->insert(['id'=>69, 'descripcion' => 'TRAMITADOR DE CAJA']);
        DB::table('cargos')->insert(['id'=>70, 'descripcion' => 'ASISTENTE DE PANADERIA']);
        DB::table('cargos')->insert(['id'=>71, 'descripcion' => 'JEFE DE PROYECTO']);
        DB::table('cargos')->insert(['id'=>72, 'descripcion' => 'JEFE DE CONTABILIDAD']);
        DB::table('cargos')->insert(['id'=>73, 'descripcion' => 'INPECTOR DE OBRA']);
        DB::table('cargos')->insert(['id'=>74, 'descripcion' => 'INSPECTOR DE OPERACIONES']);
        DB::table('cargos')->insert(['id'=>75, 'descripcion' => 'PROGRAMADOR DE RADIO']);
        DB::table('cargos')->insert(['id'=>76, 'descripcion' => 'MENSAJERO']);
        DB::table('cargos')->insert(['id'=>77, 'descripcion' => 'OBRERO']);
        DB::table('cargos')->insert(['id'=>78, 'descripcion' => 'MANTENIMIENTO']);
        DB::table('cargos')->insert(['id'=>79, 'descripcion' => 'OFICIAL DE SEGURIDAD']);
        DB::table('cargos')->insert(['id'=>80, 'descripcion' => 'JEFE DE PANADERIA']);
        DB::table('cargos')->insert(['id'=>81, 'descripcion' => 'OPERARIO DE INFRAESTRUCTURA']);
        DB::table('cargos')->insert(['id'=>82, 'descripcion' => 'OPERARIO DE MANTENIMIENTO']);
        DB::table('cargos')->insert(['id'=>83, 'descripcion' => 'MANTENIMIENTO COMEDOR']);
        DB::table('cargos')->insert(['id'=>84, 'descripcion' => 'INSPECTOR DE SEGURIDAD, HIGIENE Y AMBIENTE']);
        DB::table('cargos')->insert(['id'=>85, 'descripcion' => 'NUTRICIONISTA']);
     	
    }

}

class ConceptosTableSeeder extends Seeder {

    public function run()
    {
        DB::table('conceptos')->insert(['id' => 1002, 'descripcion' => 'DIAS DESCANSO', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 1003, 'descripcion' => 'DIAS DESCANSO OFICIALES DE SEGURIDAD', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 1004, 'descripcion' => 'JORNADA DIURNO', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 1005, 'descripcion' => 'JORNADA NOCTURNA', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 1006, 'descripcion' => 'MIXTA', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 1015, 'descripcion' => 'DIAS REMUNERADOS POR REPOSO MEDICO', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 5400, 'descripcion' => 'HORAS EXTRAS DIURNAS', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 5402, 'descripcion' => 'HORAS EXTRAS MIXTAS', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 5410, 'descripcion' => 'HORAS EXTRAS NOCTURNAS', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 5430, 'descripcion' => 'DIAS FERIADOS', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 5440, 'descripcion' => 'PRIMA DE TRANSPORTE', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 5450, 'descripcion' => 'PRIMA DE PROFESIONALIZACION', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 5454, 'descripcion' => 'PRIMA DE PROFESIONALIZACION REPOSO', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 5461, 'descripcion' => 'DIFERENCIA DE SUELDO', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 5471, 'descripcion' => 'PRIMA ANTIGUEDAD', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 5472, 'descripcion' => 'PRIMA ANTIGUEDAD REPOSO MEDICO', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 20100, 'descripcion' => 'PRESTAMO FIDEICOMISO', 'tipo' => 'deduccion']);
        DB::table('conceptos')->insert(['id' => 20106, 'descripcion' => 'PRESTAMO COMPRA', 'tipo' => 'deduccion']);
        DB::table('conceptos')->insert(['id' => 20108, 'descripcion' => 'REINTEGRO', 'tipo' => 'asignacion']);
        DB::table('conceptos')->insert(['id' => 20111, 'descripcion' => 'SEGURO SOCIAL OBLIGATORIO', 'tipo' => 'deduccion']);
        DB::table('conceptos')->insert(['id' => 20121, 'descripcion' => 'FONDO OBLIGATORIO PARA LA VIVIENDA', 'tipo' => 'deduccion']);
        DB::table('conceptos')->insert(['id' => 20131, 'descripcion' => 'REGIMEN PRESTACIONAL DE EMPLEO', 'tipo' => 'deduccion']);
        DB::table('conceptos')->insert(['id' => 20134, 'descripcion' => 'CAJA DE AHORRO', 'tipo' => 'deduccion']);
        DB::table('conceptos')->insert(['id' => 20136, 'descripcion' => 'CAJA DE AHORRO PATRONAL', 'tipo' => 'patronal']);
        DB::table('conceptos')->insert(['id' => 20222, 'descripcion' => 'TESORERIA SEGURIDAD SOCIAL', 'tipo' => 'deduccion']);
        DB::table('conceptos')->insert(['id' => 30111, 'descripcion' => 'SEGURO SOCIAL PATRONAL', 'tipo' => 'patronal']);
        DB::table('conceptos')->insert(['id' => 30121, 'descripcion' => 'FONDO OBLIGATORIO PARA LA VIVIENDA PATRONAL', 'tipo' => 'patronal']);
        DB::table('conceptos')->insert(['id' => 30131, 'descripcion' => 'REGIMEN PRESTACIONAL DE EMPLEO PATRONAL', 'tipo' => 'patronal']);
        DB::table('conceptos')->insert(['id' => 30172, 'descripcion' => 'TESORERIA DE SEGURIDAD SOCIAL PATRONAL', 'tipo' => 'patronal']);

    }

}
