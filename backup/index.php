<?php
// Paso 1: Conexión a la base de datos
$host = "localhost";
$username = "root";
$password = "d57hgtfresh3rdxy78D";
$dbname = "vos3000";

$fecha =  date("Ym");

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SHOW TABLES LIKE 'e_cdr_%'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Paso 3 y 4: Iterar sobre las tablas y generar el reporte mensual
    $report = array();

    foreach ($tables as $table) {
        // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
        $table_date = substr($table, -8);
        
        // Comprobar si la fecha está dentro del rango
        if ($table_date >= '20230712' && $table_date <= '20230830') {
            $sql = "SELECT count(id) FROM $table";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $report[$table] = $data;
        }
    }

    // A partir de aquí, puedes procesar los datos en $report para generar el informe como desees.
    // Por ejemplo, podrías imprimirlos en una tabla HTML.

    // ... (tu código para generar el informe)

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
?>



<!DOCTYPE html>
<html>

<head>
    <title>Informe Mensual</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Informe Mensual</h1>

    <span>fecha: <?php echo $fecha; ?> </span>

    <table>
        <thead>
            <tr>
                <th>Tabla</th>
                <th>ID</th>
                <th>caller</th>
                <th>callee</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report as $table => $data): ?>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?php echo $table; ?></td>
                        <td><?php echo $row['suma']; ?></td>
                        <td><?php echo $row['callere164']; ?></td>
                        <td><?php echo $row['calleee164']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>