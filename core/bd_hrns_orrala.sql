-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-10-2021 a las 09:06:59
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_hrns_orrala`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `persona_id`, `estado`, `created_at`, `updated_at`) VALUES
(1, 12, 'A', '2021-10-19 19:38:54', '2021-10-19 22:04:14'),
(2, 13, 'A', '2021-10-20 05:17:53', '2021-10-20 05:17:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_vehiculos`
--

CREATE TABLE `clientes_vehiculos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `vehiculo_id` int(11) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientes_vehiculos`
--

INSERT INTO `clientes_vehiculos` (`id`, `cliente_id`, `vehiculo_id`, `estado`) VALUES
(1, 1, 6, 'A'),
(6, 2, 5, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobantes`
--

CREATE TABLE `comprobantes` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) DEFAULT NULL,
  `total` float DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `iva` float DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_orden`
--

CREATE TABLE `estado_orden` (
  `id` int(11) NOT NULL,
  `detalle` text DEFAULT NULL,
  `estado` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `estado`) VALUES
(1, 'Mazda', 'A'),
(2, 'Ford', 'A'),
(3, 'Chevrolet', 'A'),
(4, 'Suzuki', 'A'),
(5, 'Kia', 'A'),
(6, 'Toyota', 'A'),
(7, 'Honda', 'A'),
(8, 'Nissan', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mecanicos`
--

CREATE TABLE `mecanicos` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mecanicos`
--

INSERT INTO `mecanicos` (`id`, `persona_id`, `estado`) VALUES
(1, 10, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `id_seccion` int(11) DEFAULT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `icono` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `posicion` int(2) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`id`, `id_seccion`, `menu`, `icono`, `url`, `posicion`, `estado`) VALUES
(1, 0, 'Inicio', 'fa fa-tachometer', 'inicio', 0, 'A'),
(2, 0, 'Gestión de Usuarios', 'fa fa-users', 'gestion-usuarios', 1, 'A'),
(3, 2, 'Nuevos Usuarios', '#', 'gestion/nuevo', 0, 'A'),
(4, 2, 'Listar Usuarios', '#', 'gestion/listar', 1, 'A'),
(5, 1, 'Dashboard Administrador', '#', 'inicio/administrador', 0, 'A'),
(6, 2, 'Listar Clientes', '#', 'gestion/clienteslistar', 2, 'A'),
(7, 0, 'Gestión de Vehículos', 'fa fa-car', 'gestion-vehiculo', 2, 'A'),
(8, 7, 'Marcas', '#', 'marcas/nueva', 0, 'A'),
(9, 7, 'Nuevo Vehículo', '#', 'vehiculo/nuevo', 1, 'A'),
(10, 7, 'Asignar Vehículo', '#', 'vehiculo/asignar', 2, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden`
--

CREATE TABLE `orden` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `vehiculo_id` int(11) DEFAULT NULL,
  `mecanico_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `total` float DEFAULT NULL,
  `estado_orden_id` int(11) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `pagado` char(1) DEFAULT 'N',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_servicio`
--

CREATE TABLE `orden_servicio` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) DEFAULT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `acceso` char(1) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `rol_id`, `menu_id`, `acceso`, `estado`) VALUES
(1, 1, 1, 'S', 'A'),
(2, 1, 2, 'S', 'A'),
(3, 1, 3, 'S', 'A'),
(4, 1, 4, 'S', 'A'),
(5, 1, 5, 'S', 'A'),
(6, 1, 6, 'S', 'A'),
(7, 1, 7, 'S', 'A'),
(8, 1, 8, 'S', 'A'),
(9, 1, 9, 'S', 'A'),
(10, 1, 10, 'S', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `cedula` varchar(10) DEFAULT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `cedula`, `nombres`, `apellidos`, `telefono`, `correo`, `direccion`, `estado`, `created_at`, `updated_at`) VALUES
(1, '2400556677', 'Cesar', 'Orrala', '0999667788', 'cesar@hotmail.com', 'La Libertad', 'A', '2021-10-07 02:25:01', '2021-10-16 04:09:30'),
(2, '2400454720', 'Jose', 'Sanchez', '7565454455', 'juan@gmail.com', 'La Libertad', 'A', '2021-10-08 08:16:45', '2021-10-19 18:55:55'),
(5, '0930287768', 'Playa ', 'Cautivo', '5678865786', 'bm@gmail.com', 'Muey', 'A', '2021-10-08 17:40:31', '2021-10-10 09:42:57'),
(10, '2450044405', 'Manuel', 'Villon', '0985959998', 'manuel@hotmail.es', 'Salinas', 'A', '2021-10-19 19:33:08', '2021-10-19 19:33:08'),
(12, '0928020874', 'Carlos', 'Gomez', '0984554265', 'Carlos@hotmail.com', 'Muey', 'A', '2021-10-19 19:38:54', '2021-10-19 20:18:23'),
(13, '2450042805', 'Alex', 'Balon', '0986565656', 'alex@hotmail.com', 'Guayaquil', 'A', '2021-10-20 05:17:52', '2021-10-20 05:17:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progresos`
--

CREATE TABLE `progresos` (
  `id` int(11) NOT NULL,
  `orden_id` int(11) DEFAULT NULL,
  `detalle` text DEFAULT NULL,
  `progreso` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `faltante` int(11) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `cargo`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'A', '2021-10-07 02:27:16', '2021-10-07 02:27:16'),
(2, 'Mecánico', 'A', '2021-10-09 23:14:03', '2021-10-09 23:14:03'),
(3, 'Clientes', 'A', '2021-10-17 22:26:37', '2021-10-17 22:26:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `detalle` text DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `estado` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `conf_clave` varchar(255) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `persona_id`, `rol_id`, `usuario`, `img`, `clave`, `conf_clave`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Cesar', 'cesar.jpg', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'A', '2021-10-07 02:27:57', '2021-10-16 04:09:30'),
(2, 2, 1, 'Jose', 'default.jpg', 'ed08c290d7e22f7bb324b15cbadce35b0b348564fd2d5f95752388d86d71bcca', 'ed08c290d7e22f7bb324b15cbadce35b0b348564fd2d5f95752388d86d71bcca', 'A', '2021-10-08 08:16:46', '2021-10-19 19:00:10'),
(5, 5, 1, 'Bumon', 'hola.jpg', '2963881b72032a09437589135e5676a3ef08d409f12f4d245197ff1d9f991a1c', '2963881b72032a09437589135e5676a3ef08d409f12f4d245197ff1d9f991a1c', 'A', '2021-10-08 17:40:32', '2021-10-16 06:04:35'),
(10, 10, 2, 'manuel', 'user-default.jpg', 'cca457407f24b80c72d89dd061837112cb99a0aa050c155514b320b7aaffe95c', 'cca457407f24b80c72d89dd061837112cb99a0aa050c155514b320b7aaffe95c', 'A', '2021-10-19 19:33:08', '2021-10-19 19:33:08'),
(12, 12, 3, 'carlos', 'user-default.jpg', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'A', '2021-10-19 19:38:54', '2021-10-19 19:38:54'),
(13, 13, 3, 'alex', 'user-default.jpg', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'A', '2021-10-20 05:17:52', '2021-10-20 05:17:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL,
  `marca_id` int(11) DEFAULT NULL,
  `placa` varchar(8) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `kilometraje` varchar(20) DEFAULT NULL,
  `disponible` char(1) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `marca_id`, `placa`, `modelo`, `kilometraje`, `disponible`, `estado`, `created_at`, `updated_at`) VALUES
(2, 3, 'ABC-202', 'Spard', '10000km', 'S', 'A', '2021-10-20 01:44:55', '2021-10-20 01:44:55'),
(3, 1, 'XYZ-145', '2021', '50000km', 'S', 'A', '2021-10-20 01:49:06', '2021-10-20 01:49:06'),
(4, 4, 'GHJ-254', 'Forza 2', '10000km', 'S', 'A', '2021-10-20 01:50:47', '2021-10-20 06:27:07'),
(5, 2, 'AYR-202', '4X4', '100000km', 'N', 'A', '2021-10-20 01:54:06', '2021-10-20 06:27:23'),
(6, 4, 'IDA-567', 'Forza 1', '12000km', 'N', 'A', '2021-10-20 01:54:52', '2021-10-20 05:02:33');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cliente_persona` (`persona_id`);

--
-- Indices de la tabla `clientes_vehiculos`
--
ALTER TABLE `clientes_vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cv_cliente` (`cliente_id`),
  ADD KEY `fk_cv_vehiculo` (`vehiculo_id`);

--
-- Indices de la tabla `comprobantes`
--
ALTER TABLE `comprobantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_compro_orden` (`orden_id`);

--
-- Indices de la tabla `estado_orden`
--
ALTER TABLE `estado_orden`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mecanicos`
--
ALTER TABLE `mecanicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mecanico_persona` (`persona_id`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orden`
--
ALTER TABLE `orden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_o_usuario` (`usuario_id`),
  ADD KEY `fk_o_cliente` (`cliente_id`),
  ADD KEY `fk_o_mecanico` (`mecanico_id`),
  ADD KEY `fk_o_estado_o` (`estado_orden_id`);

--
-- Indices de la tabla `orden_servicio`
--
ALTER TABLE `orden_servicio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ser_orden` (`orden_id`),
  ADD KEY `fk_ser_serv` (`servicio_id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_permiso_rol` (`rol_id`),
  ADD KEY `fk_permiso_menu` (`menu_id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `progresos`
--
ALTER TABLE `progresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pro_orden` (`orden_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario_persona` (`persona_id`),
  ADD KEY `fk_usuario_rol` (`rol_id`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vehiculo_marca` (`marca_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes_vehiculos`
--
ALTER TABLE `clientes_vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `comprobantes`
--
ALTER TABLE `comprobantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_orden`
--
ALTER TABLE `estado_orden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `mecanicos`
--
ALTER TABLE `mecanicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `orden`
--
ALTER TABLE `orden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_servicio`
--
ALTER TABLE `orden_servicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `progresos`
--
ALTER TABLE `progresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_cliente_persona` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `clientes_vehiculos`
--
ALTER TABLE `clientes_vehiculos`
  ADD CONSTRAINT `fk_cv_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `fk_cv_vehiculo` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`);

--
-- Filtros para la tabla `comprobantes`
--
ALTER TABLE `comprobantes`
  ADD CONSTRAINT `fk_compro_orden` FOREIGN KEY (`orden_id`) REFERENCES `orden` (`id`);

--
-- Filtros para la tabla `mecanicos`
--
ALTER TABLE `mecanicos`
  ADD CONSTRAINT `fk_mecanico_persona` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `orden`
--
ALTER TABLE `orden`
  ADD CONSTRAINT `fk_o_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `fk_o_estado_o` FOREIGN KEY (`estado_orden_id`) REFERENCES `estado_orden` (`id`),
  ADD CONSTRAINT `fk_o_mecanico` FOREIGN KEY (`mecanico_id`) REFERENCES `mecanicos` (`id`),
  ADD CONSTRAINT `fk_o_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `orden_servicio`
--
ALTER TABLE `orden_servicio`
  ADD CONSTRAINT `fk_ser_orden` FOREIGN KEY (`orden_id`) REFERENCES `orden` (`id`),
  ADD CONSTRAINT `fk_ser_serv` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `fk_permiso_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`),
  ADD CONSTRAINT `fk_permiso_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `progresos`
--
ALTER TABLE `progresos`
  ADD CONSTRAINT `fk_pro_orden` FOREIGN KEY (`orden_id`) REFERENCES `orden` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_persona` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `fk_vehiculo_marca` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
