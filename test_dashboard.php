<?php
// TEST ESPECÍFICO DEL DASHBOARD - Muestra qué consultas fallan

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

echo "<h1>Test Dashboard - BESTBPO</h1>";
echo "<hr>";

// Simular sesión
$_SESSION["iniciarsession"] = "ok";
$_SESSION["customeraccountname"] = "BESTBPO";
$_SESSION["nombre"] = "BESTBPO";

try {
    require_once 'models/conexion.php';
} catch(Exception $e) {
    die("<p style='color: red;'>Error cargando conexion.php: " . $e->getMessage() . "</p>");
}

try {
    require_once 'models/dashboard.models.php';
} catch(Exception $e) {
    die("<p style='color: red;'>Error cargando dashboard.models.php: " . $e->getMessage() . "</p>");
}

try {
    require_once 'controllers/dashboard.controllers.php';
} catch(Exception $e) {
    die("<p style='color: red;'>Error cargando dashboard.controllers.php: " . $e->getMessage() . "</p>");
}

try {
    require_once 'helpers/NumberHelper.php';
} catch(Exception $e) {
    die("<p style='color: red;'>Error cargando NumberHelper.php: " . $e->getMessage() . "</p>");
}

$accountname = "BESTBPO";

echo "<h2>1. Consulta: Datos de Hoy (e_customer)</h2>";
try {
    $item = 'account';
    $valor = $accountname;
    $dashboard = ControllersDashboard::ctrMostrarDatosHoy($item, $valor);
    
    if($dashboard) {
        echo "<pre>";
        print_r($dashboard);
        echo "</pre>";
        
        $consumohoysinformat = (float)(isset($dashboard['todayconsumption']) ? $dashboard['todayconsumption'] : 0);
        $saldoactual = (float)(isset($dashboard['money']) ? $dashboard['money'] : 0);
        echo "<p><strong>Consumo Hoy:</strong> $consumohoysinformat</p>";
        echo "<p><strong>Saldo Actual:</strong> $saldoactual</p>";
    } else {
        echo "<p style='color: red;'>❌ No devolvió datos</p>";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";

echo "<h2>2. Consulta: Datos de Ayer</h2>";
try {
    $ayer = new DateTime();
    $ayer->modify('-1 day');
    $dia = $ayer->format('d');
    $mes = $ayer->format('m');
    $ano = $ayer->format('Y');
    $diaformat = $mes . $dia;
    $tabla = "e_reportcustomerfee_" . $ano . $diaformat;
    
    echo "<p><strong>Tabla buscada:</strong> $tabla</p>";
    
    $item = 'customeraccount';
    $valor = $accountname;
    $dashboard = ControllersDashboard::ctrMostrarDatosAyer($item, $valor);
    
    if($dashboard) {
        echo "<pre>";
        print_r($dashboard);
        echo "</pre>";
        
        $consumoAyer = isset($dashboard['fee']) ? $dashboard['fee'] : 'NO DEFINIDO';
        echo "<p><strong>Consumo Ayer:</strong> $consumoAyer</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ No hay datos de ayer (tabla puede no existir)</p>";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";

echo "<h2>3. Consulta: Datos del Mes Actual</h2>";
try {
    $ano = date('Y');
    $mes = date('m');
    $tabla = "e_reportcustomerfee_". $ano .$mes."%";
    
    echo "<p><strong>Patrón de tabla:</strong> $tabla</p>";
    
    $item = 'customeraccount';
    $valor = $accountname;
    $dashboard = ControllersDashboard::ctrMostrarDatosMes($item, $valor);
    
    if($dashboard) {
        echo "<p><strong>Cantidad de registros:</strong> " . count($dashboard) . "</p>";
        echo "<pre>";
        print_r($dashboard);
        echo "</pre>";
    } else {
        echo "<p style='color: orange;'>⚠️ No hay datos del mes actual</p>";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";

echo "<h2>4. Consulta: Datos del Mes Anterior</h2>";
try {
    $prev = new DateTime('first day of last month');
    $ano = $prev->format('Y');
    $mes = $prev->format('m');
    $tabla = "e_reportcustomerfee_". $ano .$mes."%";
    
    echo "<p><strong>Patrón de tabla:</strong> $tabla</p>";
    
    $item = 'customeraccount';
    $valor = $accountname;
    $dashboard = ControllersDashboard::ctrMostrarDatosMesAnt($item, $valor);
    
    if($dashboard) {
        echo "<p><strong>Cantidad de registros:</strong> " . count($dashboard) . "</p>";
        echo "<pre>";
        print_r($dashboard);
        echo "</pre>";
    } else {
        echo "<p style='color: orange;'>⚠️ No hay datos del mes anterior</p>";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";

echo "<h2>5. Verificar tablas existentes en la BD</h2>";
try {
    $pdo = Conexion::conectar();
    
    // Buscar tablas e_reportcustomerfee_*
    $stmt = $pdo->query("SHOW TABLES LIKE 'e_reportcustomerfee_%'");
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p><strong>Tablas encontradas (últimas 10):</strong></p>";
    echo "<ul>";
    $tablasRecientes = array_slice($tablas, -10);
    foreach($tablasRecientes as $tabla) {
        echo "<li>$tabla</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total de tablas:</strong> " . count($tablas) . "</p>";
    
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>✅ Test Completado</h3>";
?>
