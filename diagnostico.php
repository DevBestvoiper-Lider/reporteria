<?php
// ARCHIVO DE DIAGNÓSTICO - Sube este archivo al servidor CentOS y ábrelo en el navegador

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnóstico del Sistema</h1>";
echo "<hr>";

// 1. Verificar versión PHP
echo "<h2>1. Versión de PHP</h2>";
echo "Versión: " . phpversion() . "<br>";
echo "Sistema Operativo: " . PHP_OS . "<br><br>";

// 2. Verificar sesiones
echo "<h2>2. Sesiones</h2>";
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Session Save Path: " . session_save_path() . "<br>";
echo "¿Sesión iniciada? " . (isset($_SESSION["iniciarsession"]) ? "SÍ" : "NO") . "<br>";
if(isset($_SESSION["customeraccountname"])) {
    echo "Customer Account Name: " . $_SESSION["customeraccountname"] . "<br>";
}
echo "<br>";

// 3. Verificar archivos críticos
echo "<h2>3. Verificar Archivos Críticos</h2>";

$archivos = [
    'helpers/NumberHelper.php',
    'controllers/dashboard.controllers.php',
    'models/dashboard.models.php',
    'views/modulos/functions/dashboard.functions.php',
    'models/conexion.php'
];

foreach($archivos as $archivo) {
    $existe = file_exists($archivo);
    $color = $existe ? 'green' : 'red';
    $icono = $existe ? '✓' : '✗';
    $path_real = $existe ? realpath($archivo) : 'NO ENCONTRADO';
    
    echo "<span style='color: $color;'>$icono</span> $archivo<br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;→ $path_real<br>";
}
echo "<br>";

// 4. Verificar permisos de escritura
echo "<h2>4. Permisos de Directorios</h2>";
$directorios = ['.', 'views', 'controllers', 'models', 'helpers'];
foreach($directorios as $dir) {
    $readable = is_readable($dir) ? '✓' : '✗';
    $writable = is_writable($dir) ? '✓' : '✗';
    echo "$dir - Lectura: $readable | Escritura: $writable<br>";
}
echo "<br>";

// 5. Probar conexión a base de datos
echo "<h2>5. Conexión Base de Datos</h2>";
if(file_exists('models/conexion.php')) {
    try {
        require_once 'models/conexion.php';
        echo "<span style='color: green;'>✓</span> Archivo conexion.php incluido correctamente<br>";
        
        if(class_exists('Conexion')) {
            echo "<span style='color: green;'>✓</span> Clase Conexion existe<br>";
            try {
                $conn = Conexion::conectar();
                if($conn) {
                    echo "<span style='color: green;'>✓</span> Conexión a BD establecida<br>";
                }
            } catch(Exception $e) {
                echo "<span style='color: red;'>✗</span> Error al conectar: " . $e->getMessage() . "<br>";
            }
        } else {
            echo "<span style='color: red;'>✗</span> Clase Conexion NO existe<br>";
        }
    } catch(Exception $e) {
        echo "<span style='color: red;'>✗</span> Error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "<span style='color: red;'>✗</span> Archivo conexion.php no encontrado<br>";
}
echo "<br>";

// 6. Probar carga de controllers
echo "<h2>6. Cargar Controllers</h2>";
$controllers = [
    'controllers/dashboard.controllers.php' => 'ControllersDashboard',
    'controllers/usuarios.controllers.php' => 'ControllersUsuarios'
];

foreach($controllers as $file => $class) {
    if(file_exists($file)) {
        require_once $file;
        $existe_clase = class_exists($class);
        $color = $existe_clase ? 'green' : 'red';
        $icono = $existe_clase ? '✓' : '✗';
        echo "<span style='color: $color;'>$icono</span> $file → Clase $class " . ($existe_clase ? "cargada" : "NO ENCONTRADA") . "<br>";
    } else {
        echo "<span style='color: red;'>✗</span> $file → NO EXISTE<br>";
    }
}
echo "<br>";

// 7. Verificar extensiones PHP
echo "<h2>7. Extensiones PHP Necesarias</h2>";
$extensiones = ['pdo', 'pdo_mysql', 'session', 'json', 'mbstring'];
foreach($extensiones as $ext) {
    $cargada = extension_loaded($ext);
    $color = $cargada ? 'green' : 'red';
    $icono = $cargada ? '✓' : '✗';
    echo "<span style='color: $color;'>$icono</span> $ext<br>";
}
echo "<br>";

// 8. Probar NumberHelper
echo "<h2>8. Probar NumberHelper</h2>";
if(file_exists('helpers/NumberHelper.php')) {
    require_once 'helpers/NumberHelper.php';
    if(class_exists('NumberHelper')) {
        echo "<span style='color: green;'>✓</span> NumberHelper cargado correctamente<br>";
        try {
            $test = NumberHelper::formatFeeClean(1234.56);
            echo "<span style='color: green;'>✓</span> Método formatFeeClean funciona: $test<br>";
        } catch(Exception $e) {
            echo "<span style='color: red;'>✗</span> Error en formatFeeClean: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "<span style='color: red;'>✗</span> Clase NumberHelper NO existe<br>";
    }
} else {
    echo "<span style='color: red;'>✗</span> helpers/NumberHelper.php NO encontrado<br>";
}
echo "<br>";

// 9. Variables de entorno
echo "<h2>9. Variables de Entorno</h2>";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "<br>";
echo "Current Dir: " . getcwd() . "<br>";
echo "<br>";

// 10. Error log
echo "<h2>10. Log de Errores PHP</h2>";
echo "Error Log: " . ini_get('error_log') . "<br>";
echo "Display Errors: " . ini_get('display_errors') . "<br>";
echo "Log Errors: " . ini_get('log_errors') . "<br>";

echo "<hr>";
echo "<h3>✅ Diagnóstico Completado</h3>";
echo "<p>Si ves errores en rojo, esas son las causas probables del problema en CentOS.</p>";
?>
