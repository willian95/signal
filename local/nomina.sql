-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-03-2017 a las 21:33:12
-- Versión del servidor: 10.1.9-MariaDB
-- Versión de PHP: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `nomina`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carga_familiar`
--

CREATE TABLE `carga_familiar` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombres` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `carga` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `datos_empleados_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'ANALISTA CONTABLE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'ANALISTA DE ADMINISTRACION', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'ANALISTA DE COMPRAS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'ANALISTA DE INFRAESTRUCTURA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'ANALISTA DE PLANIFICACION Y PRESUPUESTO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'ANALISTA DE PROGRAMACION DE RADIO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'ANALISTA DE RECURSOS HUMANOS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'ANALISTA DE SOPORTE TECNICO Y TELECOMUNICACIONES', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'ANALISTA JURIDICO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'ASISTENTE ADMINISTRATIVO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'ASISTENTE DE ASUNTOS PUBLICOS Y ATENCION AL CIUDADANO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'ASISTENTE DE AUDITORIA INTERNA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'ASISTENTE DE BIENES', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'ASISTENTE DE CONSULTORIA JURIDICA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'ASISTENTE DE GERENCIA GENERAL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'ASISTENTE DE NOMINA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'ASISTENTE DE OPERACIONES', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'ASISTENTE DE PLANIFICACION Y PRESUPUESTO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'ASISTENTE DE PRESIDENCIA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'ASISTENTE DE RADIO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'ASISTENTE DE RECURSOS HUMANOS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'ASISTENTE DE SEGURIDAD, HIGIENE Y AMBIENTE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'ASISTENTE DE TIC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'ASISTENTE DE TRAMITACION Y CAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'AUDITOR INTERNO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'AYUDANTE DE COCINA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'AYUDANTE DE PANADERIA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'AYUDANTE DE PANADERO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'ESPECIALISTA DE PRESUPUESTO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'COCINERO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'CONDUCTOR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'CONSULTOR JURIDICO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'COORDINADOR DE ASUNTOS PUBLICOS Y ATENCION AL CIUDADANO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'COORDINADOR DE RADIO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'COORDINADOR DE BIENES Y SERVICIOS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'COORDINADOR DE COMEDOR INDUSTRIAL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'COORDINADOR DE COMPRAS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'COORDINADOR DE CONTABILIDAD', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'COORDINADOR DE INFRAESTRUCTURA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'COORDINADOR DE OPERACIONES', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'COORDINADOR DE PLANIFICACION Y PRESUPUESTO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 'COORDINADOR DE RECURSOS HUMANOS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'COORDINADOR DE SEGURIDAD', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'COORDINADOR DE SEGURIDAD, HIGIENE Y AMBIENTE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'COORDINADOR DE TIC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'COORDINADOR DE TRIBUTOS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'DIRECTOR DE RADIO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'DISEÑADOR GRAFICO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'ENCARGADO DE COCINA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'ENTRENADOR DEPORTIVO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'ESPECIALISTA DESARROLLADOR DE SOFTWARE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'ESPECIALISTA EN OPERACIONES ADUANERAS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'GERENTE DE ADMINISTRACION Y FINANZAS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'GERENTE DE ASUNTOS PUBLICOS Y ATENCION AL CIUDADANO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 'GERENTE DE INFRAESTRUCTURA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'GERENTE DE OPERACIONES', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'GERENTE DE PLANIFICACION Y PRESUPUESTO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'GERENTE DE PROMOCION Y COMERCIALIZACION', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'GERENTE DE RECURSOS HUMANOS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 'GERENTE DE TECNOLOGIA, INFORMACION Y COMUNICACION', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 'GERENTE GENERAL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'INSPECTOR DE INFRAESTRUCTURA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'PANADERO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'PROGRAMADOR DE RADIO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'RECEPCIONISTA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'SOLDADOR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'SUPERVISOR DE INFRAESTRUCTURA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'SUPERVISOR DE MANTENIMIENTO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'TRAMITADORA DE CAJA', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos`
--

CREATE TABLE `conceptos` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` enum('deduccion','asignacion','patronal') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `conceptos`
--

INSERT INTO `conceptos` (`id`, `descripcion`, `tipo`, `created_at`, `updated_at`) VALUES
(1002, 'DIAS DESCANSO', 'asignacion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1004, 'JORNADA DIURNO', 'asignacion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1005, 'JORNADA NOCTURNA', 'asignacion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1006, 'MIXTA', 'asignacion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5401, 'HORAS EXTRAS', 'asignacion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5430, 'DIAS FERIADOS', 'asignacion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20100, 'PRESTAMO FIDEICOMISO', 'deduccion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20111, 'SEGURO SOCIAL', 'deduccion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20121, 'FAOV', 'deduccion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20131, 'REGIMEN PRESTACIONAL DE EMPLEO', 'deduccion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20134, 'CAJA DE AHORRO', 'deduccion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20136, 'CAJA DE AHORRO PATRONAL', 'patronal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20222, 'TESORERIA SEGURIDAD SOCIAL', 'deduccion', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30111, 'SEGURO SOCIAL PATRONAL', 'patronal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30121, 'FAOV PATRONAL', 'patronal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30131, 'REGIMEN PRESTACIONAL DE EMPLEO PATRONAL', 'patronal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30172, 'TESORERIA DE SEGURIDAD SOCIAL PATRONAL', 'patronal', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos_nominas`
--

CREATE TABLE `conceptos_nominas` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_nomina` int(10) UNSIGNED NOT NULL,
  `id_concepto` int(10) UNSIGNED NOT NULL,
  `id_empleado` int(10) UNSIGNED NOT NULL,
  `referencia` int(11) NOT NULL,
  `monto` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_empleados`
--

CREATE TABLE `datos_empleados` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cedula` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rif` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sexo` enum('m','f') COLLATE utf8_unicode_ci NOT NULL,
  `profesion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_nacimiento` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `estado_civil` enum('casado','soltero','viudo','divorciado') COLLATE utf8_unicode_ci NOT NULL,
  `direccion` text COLLATE utf8_unicode_ci NOT NULL,
  `parroquia` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tlf_movil` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tlf_fijo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_carrera` enum('larga','corta') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `datos_empleados`
--

INSERT INTO `datos_empleados` (`id`, `nombre`, `apellido`, `cedula`, `rif`, `sexo`, `profesion`, `fecha_nacimiento`, `estado_civil`, `direccion`, `parroquia`, `correo`, `tlf_movil`, `tlf_fijo`, `tipo_carrera`) VALUES
(6, 'Willian', 'Rodriguez', '24595726', '245957269', 'm', 'Ingeniero', '09/11/1995', 'soltero', 'Tacuato', 'Santa Ana', 'rodriguezwillian95@gmail.com', '123434342', '21334344', 'larga');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_laborales`
--

CREATE TABLE `datos_laborales` (
  `id` int(10) UNSIGNED NOT NULL,
  `fecha_ingreso` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_trabajador` enum('empleado','obrero') COLLATE utf8_unicode_ci NOT NULL,
  `tipo_contrato` enum('fijo','contratado') COLLATE utf8_unicode_ci NOT NULL,
  `sueldo` double(10,2) NOT NULL,
  `tipo_cuenta` enum('corriente','ahorro') COLLATE utf8_unicode_ci NOT NULL,
  `numero_cuenta` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fideicomiso` double NOT NULL,
  `fecha_culminacion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dato_empleado_id` int(10) UNSIGNED NOT NULL,
  `cargo_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `datos_laborales`
--

INSERT INTO `datos_laborales` (`id`, `fecha_ingreso`, `tipo_trabajador`, `tipo_contrato`, `sueldo`, `tipo_cuenta`, `numero_cuenta`, `fideicomiso`, `fecha_culminacion`, `dato_empleado_id`, `cargo_id`) VALUES
(5, '05/12/2016', 'obrero', 'fijo', 40638.13, 'ahorro', '2314567543245667543', 400, '', 6, 23);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2017_03_20_234216_roles', 1),
('2017_03_20_234629_datos_empleados', 1),
('2017_03_21_133259_create_cargos_table', 1),
('2017_03_22_164816_create_nomina_table', 1),
('2017_03_22_184245_create_conceptos_table', 1),
('2014_10_12_000000_create_users_table', 2),
('2014_10_12_100000_create_password_resets_table', 2),
('2017_03_20_235640_datos_laborales', 2),
('2017_03_21_000727_carga_familiar', 2),
('2017_03_22_182631_create_conceptos_nominnas_table', 2),
('2017_03_22_182631_create_conceptos_nominas_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina`
--

CREATE TABLE `nomina` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_inicio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_fin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `monto` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `nomina`
--

INSERT INTO `nomina` (`id`, `tipo`, `fecha_inicio`, `fecha_fin`, `monto`, `created_at`, `updated_at`) VALUES
(116, 'obrero', '23/03/2017', '30/03/2017', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'usuario', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'administrador', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `email`, `password`, `role_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Willian Rodriguez', 'rodriguezwillian95@gmail.com', '$2y$10$KYBuLtHxM.ngk.cvDON3du0nZjHZR2ZjM.XIAswPwmbYNg5h3FI6y', 2, NULL, '2017-03-22 22:50:13', '2017-03-22 22:50:13');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carga_familiar`
--
ALTER TABLE `carga_familiar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carga_familiar_datos_empleados_id_foreign` (`datos_empleados_id`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `conceptos`
--
ALTER TABLE `conceptos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `conceptos_nominas`
--
ALTER TABLE `conceptos_nominas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conceptos_nominas_id_nomina_foreign` (`id_nomina`),
  ADD KEY `conceptos_nominas_id_concepto_foreign` (`id_concepto`),
  ADD KEY `conceptos_nominas_id_empleado_foreign` (`id_empleado`);

--
-- Indices de la tabla `datos_empleados`
--
ALTER TABLE `datos_empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `datos_empleados_correo_unique` (`correo`);

--
-- Indices de la tabla `datos_laborales`
--
ALTER TABLE `datos_laborales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `datos_laborales_dato_empleado_id_foreign` (`dato_empleado_id`),
  ADD KEY `datos_laborales_cargo_id_foreign` (`cargo_id`);

--
-- Indices de la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carga_familiar`
--
ALTER TABLE `carga_familiar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT de la tabla `conceptos`
--
ALTER TABLE `conceptos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30173;
--
-- AUTO_INCREMENT de la tabla `conceptos_nominas`
--
ALTER TABLE `conceptos_nominas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=919;
--
-- AUTO_INCREMENT de la tabla `datos_empleados`
--
ALTER TABLE `datos_empleados`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `datos_laborales`
--
ALTER TABLE `datos_laborales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `nomina`
--
ALTER TABLE `nomina`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;
--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carga_familiar`
--
ALTER TABLE `carga_familiar`
  ADD CONSTRAINT `carga_familiar_datos_empleados_id_foreign` FOREIGN KEY (`datos_empleados_id`) REFERENCES `datos_empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `conceptos_nominas`
--
ALTER TABLE `conceptos_nominas`
  ADD CONSTRAINT `conceptos_nominas_id_concepto_foreign` FOREIGN KEY (`id_concepto`) REFERENCES `conceptos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `conceptos_nominas_id_empleado_foreign` FOREIGN KEY (`id_empleado`) REFERENCES `datos_empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `conceptos_nominas_id_nomina_foreign` FOREIGN KEY (`id_nomina`) REFERENCES `nomina` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `datos_laborales`
--
ALTER TABLE `datos_laborales`
  ADD CONSTRAINT `datos_laborales_cargo_id_foreign` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `datos_laborales_dato_empleado_id_foreign` FOREIGN KEY (`dato_empleado_id`) REFERENCES `datos_empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
