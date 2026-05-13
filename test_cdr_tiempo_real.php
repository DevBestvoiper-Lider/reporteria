<?php
/**
 * Script de prueba para verificar que el sistema usa e_cdr_YYYYMMDD
 * para datos del día actual en tiempo real
 */

require_once "controllers/consumption.controllers.php";
require_once "models/conexion.php";
require_once "models/consumption.models.php";

echo "<h1>Prueba de Consumo en Tiempo Real - Día Actual</h1>";
echo "<hr>";

// Simular POST con fecha de hoy
$_POST['fechabusc'] = date('Y-m-d');

echo "<h2>Fecha consultada: " . $_POST['fechabusc'] . " (HOY)</h2>";

// Probar con un cliente conocido (basado en los resultados anteriores)
$clientes = ['SOLVENCIA', 'PUNTUALMENTE', 'CORFIN'];

foreach ($clientes as $cliente) {
    echo "<h3>Cliente: $cliente</h3>";
    
    // Probar cada tipo de tabla
    $tipos = ControllersConsumption::ctrObtenerTiposTabla();
    
    foreach ($tipos as $nombreTipo => $prefijo) {
        echo "<h4>Tipo: $nombreTipo ($prefijo)</h4>";
        
        $resultado = ControllersConsumption::ctrMostrarLlamadasHoy(
            'customeraccount',
            $cliente,
            $nombreTipo
        );
        
        if (!empty($resultado)) {
            foreach ($resultado as $tabla => $datos) {
                echo "<strong>Tabla: $tabla</strong><br>";
                echo "Registros: " . count($datos) . "<br>";
                
                if (count($datos) > 0) {
                    echo "<table border='1' cellpadding='5'>";
                    echo "<tr>";
                    foreach (array_keys($datos[0]) as $campo) {
                        echo "<th>$campo</th>";
                    }
                    echo "</tr>";
                    
                    // Mostrar solo primeros 5 registros
                    $limite = min(5, count($datos));
                    for ($i = 0; $i < $limite; $i++) {
                        echo "<tr>";
                        foreach ($datos[$i] as $valor) {
                            echo "<td>$valor</td>";
                        }
                        echo "</tr>";
                    }
                    
                    echo "</table>";
                    
                    // Calcular totales
                    $totalFeetime = array_sum(array_column($datos, 'feetime'));
                    $totalFee = array_sum(array_column($datos, 'fee'));
                    
                    echo "<p><strong>Total Tiempo:</strong> " . number_format($totalFeetime, 2) . " segundos</p>";
                    echo "<p><strong>Total Costo:</strong> $" . number_format($totalFee, 4) . "</p>";
                } else {
                    echo "<p><em>Sin datos</em></p>";
                }
                echo "<br>";
            }
        } else {
            echo "<p><em>Sin resultados</em></p>";
        }
        
        echo "<hr>";
    }
    
    echo "<hr style='border: 2px solid #000;'>";
}

echo "<h2>Prueba completada</h2>";
echo "<p>Verificar que los datos provienen de e_cdr_" . date('Ymd') . "</p>";
?>
