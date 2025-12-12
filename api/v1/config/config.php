<?php
/**
 * Configuración de la API
 */

// Configuración JWT
define('JWT_SECRET_KEY', getenv('JWT_SECRET_KEY') ?: 'd6c7a776148d0d84854481f2ae4a0a9e');
define('JWT_ALGORITHM', 'HS256');
define('JWT_EXPIRATION', 86400); // 24 horas en segundos

// Configuración de API
define('API_VERSION', '1.0');
define('API_TIMEZONE', 'America/Bogota');

// Configurar zona horaria
date_default_timezone_set(API_TIMEZONE);

// Límites de rate limiting (opcional para futuro)
define('API_RATE_LIMIT', 1000); // Requests por hora
define('API_RATE_WINDOW', 3600); // 1 hora en segundos
