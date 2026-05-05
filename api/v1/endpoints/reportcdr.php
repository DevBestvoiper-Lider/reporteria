<?php
/**
 * Endpoint de Reportes CDR
 * GET /api/v1/reportcdr/month - Reporte CDR del mes
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
        handleCdrMonth($accountname);
        break;

    default:
        Response::error('Acción no válida', 400);
        break;
}

/**
 * Reporte CDR del mes
 * Query params: ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD (opcional)
 */
function handleCdrMonth($accountname) {
    try {
        $input = Response::getInput();
        
        // Configurar filtros de fecha (por defecto: mes actual)
        $startDate = isset($input['start_date']) ? $input['start_date'] : date('Y-m-01');
        $endDate = isset($input['end_date']) ? $input['end_date'] : date('Y-m-d');
        
        // Validar formatos
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
            Response::error('Formato de fecha inválido. Use YYYY-MM-DD', 400);
        }

        // Simular $_POST para que el controlador funcione
        $_POST['fechainicio'] = $startDate;
        $_POST['fechafin'] = $endDate;
        $_POST['api_no_limit'] = true; // Sin límite para la API
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        $item = 'customeraccount';
        $reportcdr = ControllersReportCdr::ctrMostrarDatosCdrMes($item, $accountname);
        
        $item2 = null;
        $valor2 = null;
        $reportcdr2 = ControllersReportCdr::ctrMostrarEndReason($item2, $valor2);

        $dataArray = [];

        if (isset($reportcdr) && is_array($reportcdr) && !empty($reportcdr)) {
            foreach ($reportcdr as $registro) {
                if (!is_array($registro)) {
                    continue;
                }

                $endreason = 'N/A';
                
                // Buscar descripción del endreason
                if (is_array($reportcdr2) && !empty($reportcdr2)) {
                    foreach ($reportcdr2 as $subarray2) {
                        if (is_array($subarray2) && isset($subarray2['code']) && 
                            isset($registro['endreason']) && $subarray2['code'] == $registro['endreason']) {
                            
                            if (isset($subarray2['description'])) {
                                if ($subarray2['description'] == "Caller Hangup") {
                                    $endreason = "Empresa";
                                } else if ($subarray2['description'] == "Callee Hangup") {
                                    $endreason = "Cliente";
                                } else {
                                    $endreason = $subarray2['description'];
                                }
                            }
                            break;
                        } else {
                            $endreason = 'Server';
                        }
                    }
                }

                // Obtener información del área code
                $item = "areacode";
                $valor = isset($registro['callerareacode']) ? $registro['callerareacode'] : '';
                $AreaCode = ControllersReportCdr::ctrMostrarAreaCode($item, $valor);

                $areacodeLocation = (is_array($AreaCode) && isset($AreaCode['location'])) ? $AreaCode['location'] : 'N/A';

                $holdtime = isset($registro['holdtime']) ? $registro['holdtime'] : 0;
                $fee = isset($registro['fee']) ? abs($registro['fee']) : 0;

                $dataArray[] = [
                    'caller_access_e164' => isset($registro['calleraccesse164']) ? $registro['calleraccesse164'] : '',
                    'caller_to_gateway_e164' => isset($registro['callertogatewaye164']) ? $registro['callertogatewaye164'] : '',
                    'areacode' => $areacodeLocation,
                    'endreason' => $endreason,
                    'callee_e164' => isset($registro['calleee164']) ? $registro['calleee164'] : '',
                    'caller_gateway_id' => isset($registro['callergatewayid']) ? $registro['callergatewayid'] : '',
                    'starttime' => isset($registro['starttime']) ? $registro['starttime'] : 0,
                    'starttime_formatted' => isset($registro['starttime']) ? date('Y-m-d H:i:s', $registro['starttime']) : '',
                    'holdtime' => $holdtime,
                    'holdtime_formatted' => gmdate("H:i:s", $holdtime),
                    'fee' => $fee,
                    'fee_formatted' => number_format($fee, 0, ',', '.')
                ];
            }
        }

        Response::success([
            'total_records' => count($dataArray),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'records' => $dataArray
        ]);

    } catch (Exception $e) {
        Response::error('Error al obtener reporte CDR: ' . $e->getMessage(), 500);
    }
}
