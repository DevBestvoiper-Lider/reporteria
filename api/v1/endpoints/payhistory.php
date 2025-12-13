<?php
/**
 * Endpoint de Histórico de Pagos
 * GET /api/v1/payhistory - Histórico de pagos
 * Query params: ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD (opcional)
 */

$method = $_SERVER['REQUEST_METHOD'];

// Obtener usuario autenticado
$authUser = AuthMiddleware::getAuthenticatedUser();
$accountname = $authUser['customeraccountname'];

if ($method !== 'GET') {
    Response::error('Método no permitido. Use GET', 405);
}

handlePayHistory($accountname);

/**
 * Obtener histórico de pagos
 */
function handlePayHistory($accountname) {
    try {
        $input = Response::getInput();
        
        // Filtros opcionales de fecha
        $startDate = isset($input['start_date']) ? $input['start_date'] : null;
        $endDate = isset($input['end_date']) ? $input['end_date'] : null;

        $item = 'customeraccount';
        $payhistory = ControllersPayHistory::ctrMostrarPayHistory($item, $accountname);

        $dataArray = [];
        $totalPagado = 0;

        if (isset($payhistory)) {
            foreach ($payhistory as $fecha => $registros) {
                foreach ($registros as $registro) {
                    $timestamp = $registro['time'];
                    $fechaPago = date('Y-m-d', $timestamp);
                    
                    // Aplicar filtros de fecha si existen
                    if ($startDate && $fechaPago < $startDate) {
                        continue;
                    }
                    if ($endDate && $fechaPago > $endDate) {
                        continue;
                    }

                    $paymoney = abs($registro['paymoney']);
                    $totalPagado += $paymoney;

                    $dataArray[] = [
                        'customer_account' => $registro['customeraccount'],
                        'pay_money' => $paymoney,
                        'pay_money_formatted' => number_format($paymoney, 0, ',', '.'),
                        'time' => $timestamp,
                        'date' => $fechaPago,
                        'datetime' => date('Y-m-d H:i:s', $timestamp),
                        'memo' => isset($registro['memo']) ? $registro['memo'] : '',
                        'customer_money' => $registro['customermoney'],
                        'customer_money_formatted' => number_format($registro['customermoney'], 0, ',', '.'),
                        'login_name' => isset($registro['loginname']) ? $registro['loginname'] : ''
                    ];
                }
            }
        }

        // Ordenar por fecha descendente
        usort($dataArray, function($a, $b) {
            if ($a['time'] == $b['time']) return 0;
            return ($a['time'] < $b['time']) ? 1 : -1;
        });

        Response::success([
            'total_paid' => $totalPagado,
            'total_paid_formatted' => number_format($totalPagado, 0, ',', '.'),
            'total_records' => count($dataArray),
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'payments' => $dataArray
        ]);

    } catch (Exception $e) {
        Response::error('Error al obtener histórico de pagos: ' . $e->getMessage(), 500);
    }
}
