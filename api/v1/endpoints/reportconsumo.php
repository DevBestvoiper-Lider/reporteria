<?php
/**
 * Endpoint de Reporte de Consumo por Línea
 * GET /api/v1/reportconsumo/month - Reporte de consumo agrupado por línea
 */

$method = $_SERVER['REQUEST_METHOD'];
$uriParts = $GLOBALS['uriParts'];
$action = isset($uriParts[1]) ? $uriParts[1] : null;

// Obtener usuario autenticado
$authUser = AuthMiddleware::getAuthenticatedUser();
$accountname = $authUser['customeraccountname'];

if ($method !== 'GET') {
    Response::error('Método no permitido. Use GET', 405);
}

switch ($action) {
    case 'month':
        handleConsumoMonth($accountname);
        break;

    default:
        Response::error('Acción no válida', 400);
        break;
}

/**
 * Reporte de consumo por línea
 */
function handleConsumoMonth($accountname) {
    try {
        $item = 'customeraccount';
        $reportcdr = ControllersReportConsumo::ctrMostrarDatosCdrMes($item, $accountname);

        // Obtener balance actual
        $item2 = 'account';
        $dashboard = ControllersDashboard::ctrMostrarDatosHoy($item2, $accountname);
        $saldoActual = @$dashboard['money'];

        $tuArray = $reportcdr;
        $totalFee = 0;
        $totalTime = 0;
        $solicData = [];

        if (isset($tuArray)) {
            foreach ($tuArray as $fecha => $registros) {
                foreach ($registros as $registro) {
                    if (isset($registro['fee'])) {
                        $totalFee += abs(floatval($registro['fee']));
                    }
                    if (isset($registro['feetime'])) {
                        $totalTime += intval($registro['feetime']);
                    }
                    
                    // Agrupar por número (calleraccesse164)
                    $numsolic = isset($registro['calleraccesse164']) ? $registro['calleraccesse164'] : null;
                    if ($numsolic !== null) {
                        if (!isset($solicData[$numsolic])) {
                            $solicData[$numsolic] = ['fee' => 0, 'feetime' => 0, 'calls' => 0];
                        }
                        if (isset($registro['fee'])) {
                            $solicData[$numsolic]['fee'] += abs(floatval($registro['fee']));
                        }
                        if (isset($registro['feetime'])) {
                            $solicData[$numsolic]['feetime'] += intval($registro['feetime']);
                        }
                        $solicData[$numsolic]['calls']++;
                    }
                }
            }
        }

        // Formatear datos agrupados
        $linesData = [];
        foreach ($solicData as $number => $data) {
            $horas = floor($data['feetime'] / 3600);
            $minutos = floor(($data['feetime'] % 3600) / 60);
            $segundos = $data['feetime'] % 60;
            
            $linesData[] = [
                'number' => $number,
                'total_fee' => $data['fee'],
                'total_fee_formatted' => number_format($data['fee'], 0, ',', '.'),
                'total_time' => $data['feetime'],
                'total_time_formatted' => sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos),
                'total_calls' => $data['calls']
            ];
        }

        // Ordenar por consumo descendente
        usort($linesData, function($a, $b) {
            if ($a['total_fee'] == $b['total_fee']) return 0;
            return ($a['total_fee'] < $b['total_fee']) ? 1 : -1;
        });

        // Calcular tiempo total formateado
        $horasTotal = floor($totalTime / 3600);
        $minutosTotal = floor(($totalTime % 3600) / 60);
        $segundosTotal = $totalTime % 60;

        Response::success([
            'balance' => $saldoActual,
            'balance_formatted' => number_format($saldoActual, 0, ',', '.'),
            'total_consumption' => $totalFee,
            'total_consumption_formatted' => number_format($totalFee, 0, ',', '.'),
            'total_time' => $totalTime,
            'total_time_formatted' => sprintf("%02d:%02d:%02d", $horasTotal, $minutosTotal, $segundosTotal),
            'total_lines' => count($linesData),
            'month' => date('Y-m'),
            'lines' => $linesData
        ]);

    } catch (Exception $e) {
        Response::error('Error al obtener reporte de consumo: ' . $e->getMessage(), 500);
    }
}
