<?php
/**
 * Endpoint de Consumo Diario
 * GET /api/v1/consumption/day - Consumo de un día específico (con filtro de fecha)
 * GET /api/v1/consumption/month - Consumo mensual
 */

require_once __DIR__ . '/../../../helpers/NumberHelper.php';

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
    case 'day':
        handleDay($accountname);
        break;

    case 'month':
        handleMonthConsumption($accountname);
        break;

    default:
        Response::error('Acción no válida', 400);
        break;
}

/**
 * Consumo de un día específico
 * Query params: ?date=YYYY-MM-DD (opcional, default: hoy)
 */
function handleDay($accountname) {
    try {
        $input = Response::getInput();
        
        // Filtro de fecha (igual que en las vistas)
        $fecha = isset($input['date']) ? $input['date'] : date('Y-m-d');
        
        // Validar formato de fecha
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            Response::error('Formato de fecha inválido. Use YYYY-MM-DD', 400);
        }

        // Simular el $_POST que usa el controlador
        $_POST['fechabusc'] = $fecha;
        
        $item = 'customeraccount';
        $consumption = ControllersConsumption::ctrMostrarLlamadasHoy($item, $accountname);

        $dataArray = [];
        $consumoTotal = 0;
        $sumHoldtime = 0;
        $feeConsum = [];
        $ipGateway = [];
        $feeTimes = [];

        if ($consumption) {
            foreach ($consumption as $fecha_key => $registros) {
                foreach ($registros as $registro) {
                    $fee = abs($registro['fee']);
                    $feetime = abs($registro['feetime']);
                    
                    $consumoTotal += $fee;
                    $sumHoldtime += $feetime;
                    
                    $feeConsum[] = $fee;
                    $ipGateway[] = $registro['gatewayid'];
                    $feeTimes[] = $feetime;
                    
                    $dataArray[] = [
                        'ip' => $registro['ip'],
                        'gatewayid' => $registro['gatewayid'],
                        'feetime' => $feetime,
                        'feetime_formatted' => gmdate("H:i:s", $feetime),
                        'fee' => NumberHelper::fromScientific($fee),
                        'fee_formatted' => NumberHelper::formatFeeClean($fee)
                    ];
                }
            }
        }

        // Calcular tiempo total formateado
        $horas = floor($sumHoldtime / 3600);
        $sumHoldtime %= 3600;
        $minutos = floor($sumHoldtime / 60);
        $segundos = $sumHoldtime % 60;
        $formatHor = sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos);

        Response::success([
            'date' => $fecha,
            'total_consumption' => NumberHelper::fromScientific($consumoTotal),
            'total_consumption_formatted' => NumberHelper::formatFeeClean($consumoTotal),
            'total_time' => $sumHoldtime + ($horas * 3600) + ($minutos * 60),
            'total_time_formatted' => $formatHor,
            'total_calls' => count($dataArray),
            'calls' => $dataArray,
            'summary' => [
                'fees' => $feeConsum,
                'gateways' => $ipGateway,
                'times' => $feeTimes
            ]
        ]);

    } catch (Exception $e) {
        Response::error('Error al obtener consumo: ' . $e->getMessage(), 500);
    }
}

/**
 * Consumo mensual
 * Query params: ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD
 */
function handleMonthConsumption($accountname) {
    try {
        $input = Response::getInput();
        
        // Filtros de fecha
        $startDate = isset($input['start_date']) ? $input['start_date'] : date('Y-m-01');
        $endDate = isset($input['end_date']) ? $input['end_date'] : date('Y-m-d');
        
        // Validar formatos
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
            Response::error('Formato de fecha inválido. Use YYYY-MM-DD', 400);
        }

        // Simular $_POST para el controlador
        $_POST['FechaInicio'] = $startDate;
        $_POST['FechaFin'] = $endDate;

        $item = 'customeraccount';
        $consumption = ControllersConsumption::ctrMostrarConsumoXMes($item, $accountname);

        $dataArray = [];
        $consumoTotal = 0;
        $sumHoldtime = 0;

        if ($consumption) {
            foreach ($consumption as $fecha_key => $registros) {
                foreach ($registros as $registro) {
                    $fee = abs($registro['fee']);
                    $feetime = abs($registro['feetime']);
                    
                    $consumoTotal += $fee;
                    $sumHoldtime += $feetime;
                    
                    $dataArray[] = [
                        'ip' => $registro['ip'],
                        'gatewayid' => $registro['gatewayid'],
                        'feetime' => $feetime,
                        'feetime_formatted' => gmdate("H:i:s", $feetime),
                        'fee' => NumberHelper::fromScientific($fee),
                        'fee_formatted' => NumberHelper::formatFeeClean($fee)
                    ];
                }
            }
        }

        // Calcular tiempo total formateado
        $horas = floor($sumHoldtime / 3600);
        $sumHoldtime %= 3600;
        $minutos = floor($sumHoldtime / 60);
        $segundos = $sumHoldtime % 60;
        $formatHor = sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos);

        Response::success([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_consumption' => NumberHelper::fromScientific($consumoTotal),
            'total_consumption_formatted' => NumberHelper::formatFeeClean($consumoTotal),
            'total_time' => $sumHoldtime + ($horas * 3600) + ($minutos * 60),
            'total_time_formatted' => $formatHor,
            'total_calls' => count($dataArray),
            'calls' => $dataArray
        ]);

    } catch (Exception $e) {
        Response::error('Error al obtener consumo mensual: ' . $e->getMessage(), 500);
    }
}
