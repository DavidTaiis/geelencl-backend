-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-07-2023 a las 06:52:20
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `geleencl`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `value` varchar(256) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `users_id` int(10) UNSIGNED NOT NULL,
  `comercial_name` varchar(255) DEFAULT NULL,
  `legal_name` varchar(255) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `direction` varchar(255) DEFAULT NULL,
  `phone_number` varchar(12) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ruc` varchar(13) DEFAULT NULL,
  `direction2` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL COMMENT 'Identificador de imagen',
  `file_name` varchar(256) NOT NULL COMMENT 'Nombre de la imagen',
  `weight` int(11) DEFAULT NULL COMMENT 'Tamaño de la imagen',
  `image_parameter_id` int(11) NOT NULL COMMENT 'Identificador del parámetro de imagen al que pertenece',
  `entity_id` int(11) DEFAULT NULL COMMENT 'Identificador de la entidad a la que pertenece la imagen',
  `entity_type` varchar(45) DEFAULT NULL COMMENT 'Tipo de entidad a la que pertenece la imagen',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha en la que se crea una imagen',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha en la que se actualiza una imagen',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha en la que se elimina una imagen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla para almacenar la información de la administración de imágenes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `image_parameter`
--

CREATE TABLE `image_parameter` (
  `id` int(11) NOT NULL COMMENT 'Identifcador del parámetro de imagen',
  `name` varchar(45) NOT NULL COMMENT 'Nombre del parámetro de imagen',
  `label` varchar(64) DEFAULT NULL COMMENT 'Etiqueta del parámetro de imagen',
  `width` int(11) NOT NULL COMMENT 'Ancho de la imagen que va a ser permitido',
  `height` int(11) NOT NULL COMMENT 'Alto de la imagen que va a ser permitido',
  `entity` enum('PRODUCT','USER') NOT NULL COMMENT 'Entidad a la que pertenece la imagen',
  `extension` varchar(45) NOT NULL COMMENT 'Tipos de extensiones permitidas',
  `max_size` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha en la que se crea un parámetro de imagen',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha en la que se actualiza un parámetro de imagen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla en la que se almacena la información de la administración de parámetros de imagen';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `manual`
--

CREATE TABLE `manual` (
  `id` int(11) NOT NULL,
  `name` varchar(112) DEFAULT NULL,
  `directory` varchar(524) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `link` varchar(45) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `icon` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `name`, `link`, `parent_id`, `weight`, `icon`) VALUES
(1, 'Home', '', NULL, 1, NULL),
(2, 'Empresas', '#', NULL, 2, '<i class=\"fa fa-cog menu-icon\"></i>'),
(3, 'Configuraciones', '#', NULL, 5, NULL),
(4, 'Proveedores', '#', NULL, 3, NULL),
(5, 'Cuestionarios', '#', NULL, 4, NULL),
(7, 'Tipo de proveedor', 'typeProvider', 4, 1, NULL),
(8, 'Proveedores', 'provider', 4, 2, NULL),
(9, 'Respuestas', 'answers', 5, 1, NULL),
(10, 'Secciones', 'section', 5, 2, NULL),
(11, 'Imágenes', 'multimedia/image-parameter', 3, 1, NULL),
(14, 'Manuales', 'documents', 3, 2, NULL),
(15, 'Administración', '#', NULL, 1, NULL),
(16, 'Roles', '/rbac/role/index', 15, 1, NULL),
(17, 'Ingreso Formulario', 'providersCompany', 4, NULL, NULL),
(18, 'Lista de proveedores', 'companyProviders', 2, 2, NULL),
(19, 'Perfil', 'profileCompany', 2, 1, NULL),
(20, 'Lista de empresas', 'company', 2, 3, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 2),
(1, 'App\\Models\\User', 3),
(1, 'App\\Models\\User', 4),
(1, 'App\\Models\\User', 8),
(1, 'App\\Models\\User', 11),
(1, 'App\\Models\\User', 12),
(1, 'App\\Models\\User', 13),
(2, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 7),
(2, 'App\\Models\\User', 9),
(2, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 14),
(2, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) NOT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'GET index', 'web', '2023-03-31 01:59:44', '2023-03-31 01:59:44'),
(2, 'GET company', 'web', '2023-04-04 05:19:00', '2023-04-04 05:19:00'),
(3, 'GET company/list', 'web', '2023-04-04 05:19:03', '2023-04-04 05:19:03'),
(4, 'GET company/form/{id?}', 'web', '2023-04-04 05:19:07', '2023-04-04 05:19:07'),
(5, 'GET rbac/role/index', 'web', '2023-04-04 05:38:19', '2023-04-04 05:38:19'),
(6, 'GET rbac/role/list', 'web', '2023-04-04 05:38:22', '2023-04-04 05:38:22'),
(7, 'GET rbac/role/form', 'web', '2023-04-04 05:38:23', '2023-04-04 05:38:23'),
(8, 'POST rbac/role/unique-name', 'web', '2023-04-04 05:38:30', '2023-04-04 05:38:30'),
(9, 'POST rbac/role/save', 'web', '2023-04-04 05:38:33', '2023-04-04 05:38:33'),
(10, 'POST company/save', 'web', '2023-04-04 05:39:17', '2023-04-04 05:39:17'),
(11, 'GET provider', 'web', '2023-04-04 05:59:54', '2023-04-04 05:59:54'),
(12, 'GET provider/list', 'web', '2023-04-04 06:00:22', '2023-04-04 06:00:22'),
(13, 'GET provider/form/{id?}', 'web', '2023-04-04 06:00:24', '2023-04-04 06:00:24'),
(14, 'POST provider/save', 'web', '2023-04-04 06:00:38', '2023-04-04 06:00:38'),
(15, 'GET typeProvider', 'web', '2023-04-04 06:10:09', '2023-04-04 06:10:09'),
(16, 'GET typeProvider/list', 'web', '2023-04-04 06:10:12', '2023-04-04 06:10:12'),
(17, 'GET typeProvider/form/{id?}', 'web', '2023-04-04 06:10:13', '2023-04-04 06:10:13'),
(18, 'POST typeProvider/save', 'web', '2023-04-04 06:10:25', '2023-04-04 06:10:25'),
(19, 'GET section', 'web', '2023-04-04 14:56:02', '2023-04-04 14:56:02'),
(20, 'GET section/list', 'web', '2023-04-04 14:56:03', '2023-04-04 14:56:03'),
(21, 'GET section/form/{id?}', 'web', '2023-04-04 14:57:23', '2023-04-04 14:57:23'),
(22, 'POST section/save', 'web', '2023-04-04 14:59:19', '2023-04-04 14:59:19'),
(23, 'GET multimedia/image-parameter', 'web', '2023-04-17 20:04:12', '2023-04-17 20:04:12'),
(24, 'GET multimedia/image-parameter/list', 'web', '2023-04-17 20:04:12', '2023-04-17 20:04:12'),
(25, 'GET rbac/role/form/{id?}', 'web', '2023-04-17 20:04:29', '2023-04-17 20:04:29'),
(26, 'GET /', 'web', '2023-04-17 20:08:12', '2023-04-17 20:08:12'),
(27, 'GET rbac/user/index', 'web', '2023-04-17 20:15:48', '2023-04-17 20:15:48'),
(28, 'GET rbac/user/list', 'web', '2023-04-17 20:15:49', '2023-04-17 20:15:49'),
(29, 'GET answers', 'web', '2023-04-17 20:17:22', '2023-04-17 20:17:22'),
(30, 'GET answers/list', 'web', '2023-04-17 20:17:23', '2023-04-17 20:17:23'),
(31, 'GET answers/form/{id?}', 'web', '2023-04-17 20:20:30', '2023-04-17 20:20:30'),
(32, 'POST answers/save', 'web', '2023-04-17 21:38:58', '2023-04-17 21:38:58'),
(33, 'GET multimedia/image-parameter/form', 'web', '2023-04-17 21:39:23', '2023-04-17 21:39:23'),
(34, 'GET question', 'web', '2023-05-05 20:43:10', '2023-05-05 20:43:10'),
(35, 'GET question/list/{id?}', 'web', '2023-05-05 20:43:11', '2023-05-05 20:43:11'),
(36, 'GET question/list', 'web', '2023-05-05 20:43:36', '2023-05-05 20:43:36'),
(37, 'GET question/{id?}', 'web', '2023-05-05 20:47:37', '2023-05-05 20:47:37'),
(38, 'GET question/list/1', 'web', '2023-05-05 21:53:10', '2023-05-05 21:53:10'),
(39, 'GET question/form/{id?}', 'web', '2023-05-05 22:02:15', '2023-05-05 22:02:15'),
(40, 'GET question/form/{sectionId?}/{id?}', 'web', '2023-05-05 22:04:34', '2023-05-05 22:04:34'),
(41, 'POST question/save', 'web', '2023-05-05 22:20:58', '2023-05-05 22:20:58'),
(42, 'GET files', 'web', '2023-05-08 16:13:18', '2023-05-08 16:13:18'),
(43, 'GET files/list', 'web', '2023-05-08 16:13:19', '2023-05-08 16:13:19'),
(44, 'GET files/form/{id?}', 'web', '2023-05-08 16:13:21', '2023-05-08 16:13:21'),
(45, 'POST files/save', 'web', '2023-05-08 16:38:20', '2023-05-08 16:38:20'),
(46, 'POST files/save/uploads', 'web', '2023-05-08 16:45:15', '2023-05-08 16:45:15'),
(47, 'GET documents', 'web', '2023-05-08 17:16:52', '2023-05-08 17:16:52'),
(48, 'GET documents/list', 'web', '2023-05-08 17:16:52', '2023-05-08 17:16:52'),
(49, 'GET documents/form/{id?}', 'web', '2023-05-08 17:16:58', '2023-05-08 17:16:58'),
(50, 'POST documents/save/uploads', 'web', '2023-05-08 17:17:14', '2023-05-08 17:17:14'),
(51, 'POST documents/save', 'web', '2023-05-08 17:42:49', '2023-05-08 17:42:49'),
(52, 'GET providersCompany', 'web', '2023-05-09 01:31:18', '2023-05-09 01:31:18'),
(53, 'POST providersCompany/save', 'web', '2023-05-09 22:23:51', '2023-05-09 22:23:51'),
(54, 'GET companyProviders', 'web', '2023-05-10 18:26:44', '2023-05-10 18:26:44'),
(55, 'GET companyProviders/list', 'web', '2023-05-10 19:03:48', '2023-05-10 19:03:48'),
(56, 'GET companyProviders/{id?}', 'web', '2023-05-10 22:51:57', '2023-05-10 22:51:57'),
(57, 'GET profileCompany', 'web', '2023-05-11 04:49:27', '2023-05-11 04:49:27'),
(58, 'POST profileCompany/save', 'web', '2023-05-11 05:11:02', '2023-05-11 05:11:02'),
(59, 'GET rbac/user/form', 'web', '2023-05-17 16:48:22', '2023-05-17 16:48:22'),
(60, 'POST rbac/user/unique-email', 'web', '2023-05-17 16:48:23', '2023-05-17 16:48:23'),
(61, 'POST rbac/user/save', 'web', '2023-05-17 17:19:29', '2023-05-17 17:19:29'),
(62, 'GET rbac/user/form/{id?}', 'web', '2023-05-23 22:34:39', '2023-05-23 22:34:39'),
(63, 'POST companyProviders/qualificationProvider', 'web', '2023-07-04 00:54:05', '2023-07-04 00:54:05'),
(64, 'POST companyProviders/save', 'web', '2023-07-04 17:01:02', '2023-07-04 17:01:02'),
(65, 'POST companyProviders/saveQualification', 'web', '2023-07-04 17:13:34', '2023-07-04 17:13:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `secciones_id` int(11) NOT NULL,
  `question` varchar(255) DEFAULT NULL,
  `type_question` varchar(45) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `document` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_proveedor`
--

CREATE TABLE `preguntas_proveedor` (
  `id` int(11) NOT NULL,
  `preguntas_id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `empresas_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `respuestas_id` int(11) DEFAULT NULL,
  `directory` text DEFAULT NULL,
  `value` varchar(2048) DEFAULT NULL,
  `qualification` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_respuestas`
--

CREATE TABLE `preguntas_respuestas` (
  `id` int(11) NOT NULL,
  `respuestas_id` int(11) NOT NULL,
  `preguntas_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_tipo_proveedor`
--

CREATE TABLE `preguntas_tipo_proveedor` (
  `id` int(11) NOT NULL,
  `preguntas_id` int(11) NOT NULL,
  `tipo_proveedor_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id` int(11) NOT NULL,
  `users_id` int(10) UNSIGNED NOT NULL,
  `tipo_proveedor_id` int(11) NOT NULL,
  `comercial_name` varchar(255) DEFAULT NULL,
  `legal_name` varchar(255) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `direction` varchar(255) DEFAULT NULL,
  `phone_number` varchar(12) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `qualification` decimal(8,2) DEFAULT NULL,
  `ruc` varchar(13) DEFAULT NULL,
  `direction2` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(12) DEFAULT NULL,
  `empresas_id` int(11) NOT NULL,
  `statusInformation` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id` int(11) NOT NULL,
  `answer` varchar(255) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `respuestas`
--

INSERT INTO `respuestas` (`id`, `answer`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Si', 'ACTIVE', '2023-04-17 21:38:58', '2023-05-08 17:09:43'),
(2, 'No', 'ACTIVE', '2023-04-17 21:39:06', '2023-05-08 17:09:49'),
(3, 'Abierta', 'ACTIVE', '2023-05-09 23:10:36', '2023-05-09 23:10:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Empresa', 'web', '2023-04-04 05:38:34', '2023-04-04 05:38:34'),
(2, 'Proveedor', 'web', '2023-04-04 06:20:52', '2023-04-04 06:20:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(47, 1),
(47, 2),
(48, 1),
(48, 2),
(52, 2),
(53, 2),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `total_points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones_tipo_proveedor`
--

CREATE TABLE `secciones_tipo_proveedor` (
  `id` int(11) NOT NULL,
  `secciones_id` int(11) NOT NULL,
  `tipo_proveedor_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_proveedor`
--

CREATE TABLE `tipo_proveedor` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL,
  `phone_number` varchar(13) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code_user` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `remember_token`, `status`, `phone_number`, `created_at`, `updated_at`, `code_user`) VALUES
(1, 'Admin', 'admin@admin.com', NULL, '$2y$10$3JXYOWWtx0NXs4e4GwyHbeHI4D1GqccT5TGfpoIb8sQHVJE9pnUuK', 'DgGx42XFgEKuDXuPGhRrx0Mz37dJMUeBj3io3FqgpQb7mJ8rBk2Rbp60N1t4', 'ACTIVE', NULL, '2020-10-29 21:28:42', '2023-05-23 23:53:10', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empresas_users1_idx` (`users_id`);

--
-- Indices de la tabla `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_image_image_parameter1_idx` (`image_parameter_id`);

--
-- Indices de la tabla `image_parameter`
--
ALTER TABLE `image_parameter`
  ADD PRIMARY KEY (`id`,`name`);

--
-- Indices de la tabla `manual`
--
ALTER TABLE `manual`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_menu1_idx` (`parent_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indices de la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indices de la tabla `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indices de la tabla `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indices de la tabla `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Indices de la tabla `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_preguntas_secciones1_idx` (`secciones_id`);

--
-- Indices de la tabla `preguntas_proveedor`
--
ALTER TABLE `preguntas_proveedor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_preguntas_proveedor_preguntas1_idx` (`preguntas_id`),
  ADD KEY `fk_preguntas_proveedor_proveedor1_idx` (`proveedor_id`),
  ADD KEY `fk_preguntas_proveedor_empresas1_idx` (`empresas_id`);

--
-- Indices de la tabla `preguntas_respuestas`
--
ALTER TABLE `preguntas_respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_preguntas_respuestas_respuestas1_idx` (`respuestas_id`),
  ADD KEY `fk_preguntas_respuestas_preguntas1_idx` (`preguntas_id`);

--
-- Indices de la tabla `preguntas_tipo_proveedor`
--
ALTER TABLE `preguntas_tipo_proveedor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_preguntas_tipo_proveedor_preguntas1_idx` (`preguntas_id`),
  ADD KEY `fk_preguntas_tipo_proveedor_tipo_proveedor1_idx` (`tipo_proveedor_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empresas_users1_idx` (`users_id`),
  ADD KEY `fk_proveedor_tipo_proveedor1_idx` (`tipo_proveedor_id`),
  ADD KEY `fk_proveedor_empresas1_idx` (`empresas_id`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `secciones_tipo_proveedor`
--
ALTER TABLE `secciones_tipo_proveedor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_secciones_tipo_proveedor_tipo_proveedor1_idx` (`tipo_proveedor_id`),
  ADD KEY `fk_secciones_tipo_proveedor_secciones1_idx` (`secciones_id`);

--
-- Indices de la tabla `tipo_proveedor`
--
ALTER TABLE `tipo_proveedor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de imagen';

--
-- AUTO_INCREMENT de la tabla `image_parameter`
--
ALTER TABLE `image_parameter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifcador del parámetro de imagen';

--
-- AUTO_INCREMENT de la tabla `manual`
--
ALTER TABLE `manual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `preguntas_proveedor`
--
ALTER TABLE `preguntas_proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT de la tabla `preguntas_respuestas`
--
ALTER TABLE `preguntas_respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `preguntas_tipo_proveedor`
--
ALTER TABLE `preguntas_tipo_proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `secciones_tipo_proveedor`
--
ALTER TABLE `secciones_tipo_proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `tipo_proveedor`
--
ALTER TABLE `tipo_proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD CONSTRAINT `fk_empresas_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `fk_image_image_parameter1` FOREIGN KEY (`image_parameter_id`) REFERENCES `image_parameter` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_menu1` FOREIGN KEY (`parent_id`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `fk_preguntas_secciones1` FOREIGN KEY (`secciones_id`) REFERENCES `secciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `preguntas_proveedor`
--
ALTER TABLE `preguntas_proveedor`
  ADD CONSTRAINT `fk_preguntas_proveedor_empresas1` FOREIGN KEY (`empresas_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_preguntas_proveedor_preguntas1` FOREIGN KEY (`preguntas_id`) REFERENCES `preguntas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_preguntas_proveedor_proveedor1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `preguntas_respuestas`
--
ALTER TABLE `preguntas_respuestas`
  ADD CONSTRAINT `fk_preguntas_respuestas_preguntas1` FOREIGN KEY (`preguntas_id`) REFERENCES `preguntas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_preguntas_respuestas_respuestas1` FOREIGN KEY (`respuestas_id`) REFERENCES `respuestas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `preguntas_tipo_proveedor`
--
ALTER TABLE `preguntas_tipo_proveedor`
  ADD CONSTRAINT `fk_preguntas_tipo_proveedor_preguntas1` FOREIGN KEY (`preguntas_id`) REFERENCES `preguntas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_preguntas_tipo_proveedor_tipo_proveedor1` FOREIGN KEY (`tipo_proveedor_id`) REFERENCES `tipo_proveedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
