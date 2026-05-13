<?php
// Script temporal para mapear la base de datos

try {
    // Conexión sin especificar base de datos primero
    $pdo = new PDO(
        "mysql:host=127.0.0.1;charset=utf8",
        "root",
        "",  // Sin contraseña en Laragon por defecto
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    
    // Listar bases de datos disponibles
    echo "=== BASES DE DATOS DISPONIBLES ===\n\n";
    $databases = $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
    foreach($databases as $db) {
        echo "- $db\n";
    }
    
    // Intentar conectar a vos3000 o buscar una base similar
    $dbToUse = 'vos3000';
    if(!in_array('vos3000', $databases)) {
        foreach($databases as $db) {
            if(stripos($db, 'vos') !== false || stripos($db, 'pura') !== false || stripos($db, 'report') !== false) {
                $dbToUse = $db;
                break;
            }
        }
    }
    
    echo "\nUsando base de datos: $dbToUse\n\n";
    $pdo->exec("USE $dbToUse");
    
    // Obtener todas las tablas
    echo "=== TABLAS EN LA BASE DE DATOS $dbToUse ===\n\n";
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    
    foreach($tables as $table) {
        echo "- $table\n";
    }
    
    echo "\n\n=== ESTRUCTURA DE TABLAS PRINCIPALES ===\n\n";
    
    // Tablas principales a analizar
    $mainTables = array(
        'e_customer',
        'e_gateway',
        'e_clienttype'
    );
    
    // Buscar tablas de reportes (ejemplo del día actual)
    $today = date('Ymd');
    $reportTables = array(
        "e_reportgatewaymappingfee_$today",
        "e_reportgatewayroutingfee_$today",
        "e_reportphonefee_$today",
        "e_reportcustomerclearinglocationfee_$today"
    );
    
    // Combinar tablas a analizar
    $tablesToAnalyze = array_merge($mainTables, $reportTables);
    
    foreach($tablesToAnalyze as $table) {
        if(in_array($table, $tables)) {
            echo "\n--- Tabla: $table ---\n";
            $columns = $pdo->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
            foreach($columns as $col) {
                echo sprintf("  %-30s %-20s %s %s\n", 
                    $col['Field'], 
                    $col['Type'], 
                    $col['Null'] == 'NO' ? 'NOT NULL' : 'NULL',
                    $col['Key'] ? "({$col['Key']})" : ''
                );
            }
            
            // Contar registros
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "  Total registros: $count\n";
        }
    }
    
    // Buscar tablas de reportes de los últimos 3 días
    echo "\n\n=== TABLAS DE REPORTES RECIENTES ===\n\n";
    $recentReportTables = array();
    foreach($tables as $table) {
        if(preg_match('/^e_report.*fee_\d{8}$/', $table)) {
            $recentReportTables[] = $table;
        }
    }
    
    // Ordenar y mostrar las últimas 10
    rsort($recentReportTables);
    $recentReportTables = array_slice($recentReportTables, 0, 10);
    
    foreach($recentReportTables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "$table - $count registros\n";
    }
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
