<?php
$active = "CDR Reportes";

$accountname = $_SESSION['customeraccountname'];

$item = 'customeraccount';

$valor = $accountname;

$reportcdr = ControllersReportCdr::ctrMostrarDatosCdrMes($item, $valor);

$item = null;

$valor = null;

$reportcdr2 = ControllersReportCdr::ctrMostrarEndReason($item, $valor);

// Con la nueva implementación UNION ALL, $reportcdr es un array simple de registros
if (isset($reportcdr) && is_array($reportcdr) && !empty($reportcdr)) {
    foreach ($reportcdr as $registro) {
        // Verificar que $registro sea un array antes de acceder a sus elementos
        if (!is_array($registro)) {
            continue;
        }

        $endreason = 'N/A'; // Valor por defecto
        
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
                    break; // Salir del loop una vez encontrado
                }
            }
        }

        // Obtener información del área code
        $item = "areacode";
        $valor = $registro['callerareacode'] ?? '';
        $AreaCode = ControllersReportCdr::ctrMostrarAreaCode($item, $valor);

        $areacodeLocation = (is_array($AreaCode) && isset($AreaCode['location'])) ? $AreaCode['location'] : 'N/A';

        $dataArray[] = array(
            'calleraccesse164' => $registro['calleraccesse164'] ?? '',
            'callertogatewaye164' => $registro['callertogatewaye164'] ?? '',
            'areacode' => $areacodeLocation,
            'endreason' => $endreason,
            'calleee164' => $registro['calleee164'] ?? '',
            'callergatewayid' => $registro['callergatewayid'] ?? '',
            'starttime' => $registro['starttime'] ?? 0,
            'holdtime' => $registro['holdtime'] ?? 0,
            'fee' => $registro['fee'] ?? 0,
        );
    }
}
