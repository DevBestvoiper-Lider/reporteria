<?php
/**
 * Script de diagnóstico para identificar errores 500
 * Acceder a: /reporteria/api/v1/test.php
 */

// Mostrar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test de Diagnóstico - API Reportería</h1>";
echo "<hr>";

// 1. Test PHP Version
echo "<h2>1. Versión de PHP</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Versión mínima requerida: 7.0<br>";
echo "Estado: " . (version_compare(phpversion(), '7.0.0', '>=') ? '✅ OK' : '❌ Error') . "<br>";

// 2. Test mod_rewrite
echo "<h2>2. Módulo mod_rewrite</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "mod_rewrite: " . (in_array('mod_rewrite', $modules) ? '✅ Activado' : '❌ Desactivado') . "<br>";
    echo "mod_headers: " . (in_array('mod_headers', $modules) ? '✅ Activado' : '❌ Desactivado') . "<br>";
} else {
    echo "⚠️ No se puede verificar (función apache_get_modules no disponible)<br>";
}

// 3. Test de archivos requeridos
echo "<h2>3. Archivos Requeridos</h2>";
$required_files = [
    __DIR__ . '/../../vendor/autoload.php',
    __DIR__ . '/../../models/conexion.php',
    __DIR__ . '/config/config.php',
    __DIR__ . '/middleware/auth.php',
    __DIR__ . '/helpers/response.php',
    __DIR__ . '/endpoints/auth.php',
    __DIR__ . '/endpoints/consumption.php',
    __DIR__ . '/endpoints/reportcdr.php',
];

foreach ($required_files as $file) {
    $exists = file_exists($file);
    $relative = str_replace(__DIR__, '.', $file);
    echo $relative . ": " . ($exists ? '✅ Existe' : '❌ No encontrado') . "<br>";
}

// 4. Test de permisos
echo "<h2>4. Permisos de Archivos</h2>";
echo ".htaccess readable: " . (is_readable(__DIR__ . '/.htaccess') ? '✅ Sí' : '❌ No') . "<br>";
echo "index.php readable: " . (is_readable(__DIR__ . '/index.php') ? '✅ Sí' : '❌ No') . "<br>";
echo "index.php writable: " . (is_writable(__DIR__ . '/index.php') ? '✅ Sí' : '❌ No') . "<br>";

// 5. Test de carga de archivos
echo "<h2>5. Test de Carga de Archivos</h2>";
try {
    require_once __DIR__ . '/../../vendor/autoload.php';
    echo "✅ vendor/autoload.php cargado correctamente<br>";
} catch (Exception $e) {
    echo "❌ Error al cargar vendor/autoload.php: " . $e->getMessage() . "<br>";
}

try {
    require_once __DIR__ . '/../../models/conexion.php';
    echo "✅ models/conexion.php cargado correctamente<br>";
} catch (Exception $e) {
    echo "❌ Error al cargar models/conexion.php: " . $e->getMessage() . "<br>";
}

try {
    require_once __DIR__ . '/config/config.php';
    echo "✅ config/config.php cargado correctamente<br>";
} catch (Exception $e) {
    echo "❌ Error al cargar config/config.php: " . $e->getMessage() . "<br>";
}

try {
    require_once __DIR__ . '/middleware/auth.php';
    echo "✅ middleware/auth.php cargado correctamente<br>";
} catch (Exception $e) {
    echo "❌ Error al cargar middleware/auth.php: " . $e->getMessage() . "<br>";
}

try {
    require_once __DIR__ . '/helpers/response.php';
    echo "✅ helpers/response.php cargado correctamente<br>";
} catch (Exception $e) {
    echo "❌ Error al cargar helpers/response.php: " . $e->getMessage() . "<br>";
}

// 6. Test de constantes
echo "<h2>6. Constantes Definidas</h2>";
echo "JWT_SECRET_KEY: " . (defined('JWT_SECRET_KEY') ? '✅ Definida' : '❌ No definida') . "<br>";
echo "JWT_EXPIRATION: " . (defined('JWT_EXPIRATION') ? '✅ Definida (' . JWT_EXPIRATION . 's)' : '❌ No definida') . "<br>";

// 7. Test de extensiones PHP
echo "<h2>7. Extensiones PHP</h2>";
$required_extensions = ['pdo', 'pdo_mysql', 'json', 'mbstring', 'openssl'];
foreach ($required_extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? '✅ Cargada' : '❌ No cargada') . "<br>";
}

// 8. Test de URL Rewriting
echo "<h2>8. Test de URL Rewriting</h2>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'No definida') . "<br>";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'No definida') . "<br>";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'No definida') . "<br>";

// 9. Información del servidor
echo "<h2>9. Información del Servidor</h2>";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'No definida') . "<br>";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'No definida') . "<br>";
echo "Script Filename: " . (__FILE__) . "<br>";

echo "<hr>";
echo "<p><strong>Si ves este mensaje, PHP está funcionando correctamente.</strong></p>";
echo "<p>Verifica los elementos marcados con ❌ para solucionar el error 500.</p>";
