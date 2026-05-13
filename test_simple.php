<?php
// TEST SIMPLE - Ver qué archivos faltan

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Test Básico</h1>";
echo "<hr>";

echo "<h2>1. Cargar archivos uno por uno</h2>";

// Test 1: Conexión
echo "<p>Cargando models/conexion.php...</p>";
if(file_exists('models/conexion.php')) {
    require_once 'models/conexion.php';
    echo "<span style='color: green;'>✓ Cargado</span><br>";
} else {
    echo "<span style='color: red;'>✗ NO EXISTE</span><br>";
    die();
}

// Test 2: Dashboard Models
echo "<p>Cargando models/dashboard.models.php...</p>";
if(file_exists('models/dashboard.models.php')) {
    require_once 'models/dashboard.models.php';
    echo "<span style='color: green;'>✓ Cargado</span><br>";
} else {
    echo "<span style='color: red;'>✗ NO EXISTE</span><br>";
    die();
}

// Test 3: Dashboard Controllers
echo "<p>Cargando controllers/dashboard.controllers.php...</p>";
if(file_exists('controllers/dashboard.controllers.php')) {
    require_once 'controllers/dashboard.controllers.php';
    echo "<span style='color: green;'>✓ Cargado</span><br>";
} else {
    echo "<span style='color: red;'>✗ NO EXISTE</span><br>";
    die();
}

// Test 4: Helper
echo "<p>Cargando helpers/NumberHelper.php...</p>";
if(file_exists('helpers/NumberHelper.php')) {
    require_once 'helpers/NumberHelper.php';
    echo "<span style='color: green;'>✓ Cargado</span><br>";
} else {
    echo "<span style='color: red;'>✗ NO EXISTE</span><br>";
    die();
}

echo "<hr>";

// Test 5: Consulta simple
echo "<h2>2. Test Consulta Simple</h2>";

try {
    $pdo = Conexion::conectar();
    echo "<p><span style='color: green;'>✓</span> Conectado a BD</p>";
    
    // Consulta básica a e_customer
    $stmt = $pdo->prepare("SELECT * FROM e_customer WHERE account = :account LIMIT 1");
    $stmt->bindParam(':account', $account, PDO::PARAM_STR);
    $account = 'BESTBPO';
    $stmt->execute();
    $result = $stmt->fetch();
    
    if($result) {
        echo "<p><span style='color: green;'>✓</span> Usuario encontrado en e_customer</p>";
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    } else {
        echo "<p><span style='color: red;'>✗</span> Usuario NO encontrado</p>";
    }
    
} catch(Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}

echo "<hr>";
echo "<h3>✅ Test Completado</h3>";
?>
