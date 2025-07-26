-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.7.33 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para votacion
CREATE DATABASE IF NOT EXISTS `votacion` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `votacion`;

-- Volcando estructura para tabla votacion.elementos
CREATE TABLE IF NOT EXISTS `elementos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `lista` enum('A','B') DEFAULT NULL,
  `votos` int(11) DEFAULT '0',
  `video_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla votacion.elementos: ~2 rows (aproximadamente)
REPLACE INTO `elementos` (`id`, `nombre`, `lista`, `votos`, `video_url`) VALUES
	(1, 'Lista A', 'A', 0, 'https://drive.google.com/file/d/1YPWpg3tPAIK1KqpPG2OpiBtu_0kyc8zN/view?usp=sharing'),
	(2, 'Lista B', 'B', 1, 'https://drive.google.com/file/d/1rbvFMV0eUaFbI1GoyiPly04Ex7PlyN7U/view?usp=sharing');

-- Volcando estructura para tabla votacion.votantes
CREATE TABLE IF NOT EXISTS `votantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `correo` varchar(100) DEFAULT NULL,
  `id_elemento` int(11) NOT NULL,
  `fecha_voto` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla votacion.votantes: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
