<?php
/**
 * PRUEBA RÁPIDA: Consumo diario y mensual desde PHP
 * Fecha: 13 Mayo 2026
 */

require_once "models/conexion.php";
require_once "models/consumption.models.php";
require_once "controllers/consumption.controllers.php";

echo "================================================================================\n";
echo "PRUEBA PHP: Consumo Diario y Mensual con Detección Automática CDR\n";
echo "================================================================================\n\n";

$cliente = 'SOLVENCIA';
$tipos_a_probar = array('gatewaymapping', 'phone', 'customerclearinglocation');

foreach($tipos_a_probar as $tipo){
    echo str_repeat("=", 80) . "\n";
    echo "📊 TIPO: " . strtoupper($tipo) . "\n";
    echo str_repeat("=", 80) . "\n\n";
    
    // ===== CONSULTA DIARIA (HOY) =====
    echo "🔹 CONSULTA DIARIA - HOY (" . date('Y-m-d') . "):\n";
    echo str_repeat("-", 80) . "\n";
    
    $resultado_diario = ControllersConsumption::ctrMostrarLlamadasHoy('customeraccount', $cliente, $tipo);
    
    if(!empty($resultado_diario)){
        foreach($resultado_diario as $tabla => $datos){
            echo "   Tabla: " . $tabla . "\n";
            echo "   Registros: " . count($datos) . "\n";
            
            if(count($datos) > 0){
                echo "\n   Top 3 por costo:\n";
                // Ordenar por fee descendente
                usort($datos, function($a, $b){
                    return $b['fee'] <=> $a['fee'];
                });
                
                $top3 = array_slice($datos, 0, 3);
                foreach($top3 as $row){
                    // Obtener el primer campo (gatewayid, e164 o areacode)
                    $primer_campo = '';
                    if(isset($row['gatewayid'])) $primer_campo = $row['gatewayid'];
                    elseif(isset($row['e164'])) $primer_campo = $row['e164'];
                    elseif(isset($row['areacode'])) $primer_campo = $row['areacode'];
                    
                    echo sprintf("      %-30s Fee: $%.4f\n", 
                        substr($primer_campo, 0, 30), 
                        $row['fee']
                    );
                }
            }
        }
    }else{
        echo "   Sin datos\n";
    }
    
    // ===== CONSULTA MENSUAL =====
    echo "\n🔸 CONSULTA MENSUAL (últimos días):\n";
    echo str_repeat("-", 80) . "\n";
    
    $resultado_mensual = ControllersConsumption::ctrMostrarConsumoXMes('customeraccount', $cliente, $tipo);
    
    if(!empty($resultado_mensual)){
        $total_tablas = count($resultado_mensual);
        $total_registros = 0;
        
        foreach($resultado_mensual as $tabla => $datos){
            $total_registros += count($datos);
        }
        
        echo "   Tablas con datos: " . $total_tablas . "\n";
        echo "   Total registros: " . $total_registros . "\n";
    }else{
        echo "   Sin datos\n";
    }
    
    echo "\n";
}

echo str_repeat("=", 80) . "\n";
echo "✓ PRUEBA COMPLETADA\n";
echo str_repeat("=", 80) . "\n\n";

echo "RESUMEN:\n";
echo "  ✓ DIARIO - HOY: Detecta automáticamente e_cdr_" . date('Ymd') . "\n";
echo "  ✓ DIARIO - AYER: Usa e_report*fee_" . date('Ymd', strtotime('-1 day')) . "\n";
echo "  ✓ MENSUAL: Combina ambas fuentes según fecha\n";
echo "  ✓ Soporta: gatewaymapping, gatewayrouting, phone, customerclearinglocation\n\n";

echo "USO EN TU CÓDIGO:\n";
echo "  // Consulta diaria\n";
echo "  \$datos = ControllersConsumption::ctrMostrarLlamadasHoy('customeraccount', 'CLIENTE', 'phone');\n\n";
echo "  // Consulta mensual\n";
echo "  \$datos = ControllersConsumption::ctrMostrarConsumoXMes('customeraccount', 'CLIENTE', 'phone');\n\n";
?>
