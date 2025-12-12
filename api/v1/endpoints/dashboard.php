<?php
/**
 * Endpoint de Dashboard
 * GET /api/v1/dashboard/today - Datos de hoy
 * GET /api/v1/dashboard/yesterday - Datos de ayer
 * GET /api/v1/dashboard/month - Datos del mes
 * GET /api/v1/dashboard/year - Datos del año
 */

$method = $_SERVER['REQUEST_METHOD'];
$uriParts = $GLOBALS['uriParts'];
$action = $uriParts[1] ?? null;

// Obtener usuario autenticado
$authUser = AuthMiddleware::getAuthenticatedUser();
$accountname = $authUser['customeraccountname'];

if ($method !== 'GET') {
    Response::error('Método no permitido. Use GET', 405);
}

switch ($action) {
    case 'today':
        handleToday($accountname);
        break;

    case 'yesterday':
        handleYesterday($accountname);
        break;

    case 'month':
        handleMonth($accountname);
        break;

    case 'year':
        handleYear($accountname);
        break;

    case 'summary':
        handleSummary($accountname);
        break;

    default:
        Response::error('Acción no válida', 400);
        break;
}

/**
 * Datos de hoy
 */
function handleToday($accountname) {
    try {
        $item = 'account';
        $dashboard = ControllersDashboard::ctrMostrarDatosHoy($item, $accountname);

        $consumohoysinformat = @$dashboard['todayconsumption'];
        $saldoactual = @$dashboard['money'];

        Response::success([
            'consumption' => abs($consumohoysinformat),
            'consumption_formatted' => number_format(abs($consumohoysinformat), 0, ',', '.'),
            'balance' => $saldoactual,
            'balance_formatted' => number_format($saldoactual, 0, ',', '.'),
            'date' => date('Y-m-d')
        ]);

    } catch (Exception $e) {
        Response::error('Error al obtener datos: ' . $e->getMessage(), 500);
    }
}

/**
 * Datos de ayer
 */
function handleYesterday($accountname) {
    try {
        $item = 'customeraccount';
        $dashboard = ControllersDashboard::ctrMostrarDatosAyer($item, $accountname);

        $consumoAyer = @$dashboard['fee'];

        Response::success([
            'consumption' => abs($consumoAyer),
            'consumption_formatted' => number_format(abs($consumoAyer), 0, ',', '.'),
            'date' => date('Y-m-d', strtotime('-1 day'))
        ]);

    } catch (Exception $e) {
        Response::error('Error al obtener datos: ' . $e->getMessage(), 500);
    }
}

/**
 * Datos del mes
 */
function handleMonth($accountname) {
    try {
        $item = 'customeraccount';
        $dashboard = ControllersDashboard::ctrMostrarDatosMes($item, $accountname);

        $totalFeeMes = 0;
        $feesArray = [];
        $datesArray = [];

        foreach ($dashboard as $subarray) {
            foreach ($subarray as $key) {
                if (isset($key['fee'])) {
                    $totalFeeMes += $key['fee'];
                    $feesArray[] = abs($key['fee']);
                }
                if (isset($key['date'])) {
                    $timestampSeconds = intval($key['date'] / 1000);
                    $datesArray[] = date("Y-m-d", $timestampSeconds);
                }
            }
        }

        Response::success([
            'total_consumption' => abs($totalFeeMes),
            'total_consumption_formatted' => number_format(abs($totalFeeMes), 0, ',', '.'),
            'daily_data' => array_map(function($date, $fee) {
                return [
                    'date' => $date,
                    'fee' => $fee,
                    'fee_formatted' => number_format($fee, 0, ',', '.')
                ];
            }, $datesArray, $feesArray),
            'month' => date('Y-m')
        ]);

    } catch (Exception $e) {
        Response::error('Error al obtener datos: ' . $e->getMessage(), 500);
    }
}

/**
 * Datos del año
 */
function handleYear($accountname) {
    try {
        $item = 'customeraccount';
        $dashboard = ControllersDashboard::ctrMostrarDatosAno($item, $accountname);

        $totalFeeAno = 0;

        foreach ($dashboard as $subarray) {
            foreach ($subarray as $key) {
                if (isset($key['fee'])) {
                    $totalFeeAno += $key['fee'];
                }
            }
        }

        Response::success([
            'total_consumption' => abs($totalFeeAno),
            'total_consumption_formatted' => number_format(abs($totalFeeAno), 0, ',', '.'),
            'year' => date('Y')
        ]);

    } catch (Exception $e) {
        Response::error('Error al obtener datos: ' . $e->getMessage(), 500);
    }
}

/**
 * Resumen completo
 */
function handleSummary($accountname) {
    try {
        // Datos de hoy
        $item = 'account';
        $dashboardHoy = ControllersDashboard::ctrMostrarDatosHoy($item, $accountname);
        $consumoHoy = abs(@$dashboardHoy['todayconsumption']);
        $saldoActual = @$dashboardHoy['money'];

        // Datos de ayer
        $item = 'customeraccount';
        $dashboardAyer = ControllersDashboard::ctrMostrarDatosAyer($item, $accountname);
        $consumoAyer = abs(@$dashboardAyer['fee']);

        // Calcular porcentaje de cambio
        $diferencia = $consumoHoy - $consumoAyer;
        if ($consumoAyer != 0) {
            $porcentaje = ($diferencia / $consumoAyer) * 100;
        } else {
            $porcentaje = $consumoHoy > 0 ? 100 : -100;
        }

        Response::success([
            'today' => [
                'consumption' => $consumoHoy,
                'consumption_formatted' => number_format($consumoHoy, 0, ',', '.'),
                'balance' => $saldoActual,
                'balance_formatted' => number_format($saldoActual, 0, ',', '.')
            ],
            'yesterday' => [
                'consumption' => $consumoAyer,
                'consumption_formatted' => number_format($consumoAyer, 0, ',', '.')
            ],
            'comparison' => [
                'difference' => $diferencia,
                'percentage' => round($porcentaje, 2),
                'percentage_formatted' => round(abs($porcentaje), 2) . '%',
                'trend' => $diferencia > 0 ? 'up' : 'down'
            ]
        ]);

    } catch (Exception $e) {
        Response::error('Error al obtener resumen: ' . $e->getMessage(), 500);
    }
}
