<?php

$active = "Reporte Consumo por Linea";

$accountname = $_SESSION['customeraccountname'];

$item = 'customeraccount';

$valor = $accountname;

$reportcdr = ControllersReportConsumo::ctrMostrarDatosCdrMes($item, $valor);

$item = 'account';

$valor = $accountname;

$dashboard = ControllersDashboard::ctrMostrarDatosHoy($item, $valor);

$saldoactual = @$dashboard['money'];

// $consumohoyformat = number_format(abs($consumohoysinformat), 0, ',', '.');

$saldoactualformat = number_format($saldoactual, 0, ',', '.');

$tuArray = $reportcdr;

$totalFee = 0;
$totalTime = 0;
$solicData = []; // Nuevo array para agrupar por numsolic (calleraccesse164)

if (isset($tuArray)) {
    foreach ($tuArray as $fecha => $registros) {
        foreach ($registros as $registro) {
            if (isset($registro['fee'])) {
                $totalFee += floatval($registro['fee']);
            }
            if (isset($registro['feetime'])) {
                $totalTime += intval($registro['feetime']);
            }
            // Agrupar por numsolic (calleraccesse164)
            $numsolic = $registro['calleraccesse164'] ?? null;
            if ($numsolic !== null) {
                if (!isset($solicData[$numsolic])) {
                    $solicData[$numsolic] = ['fee' => 0, 'feetime' => 0];
                }
                if (isset($registro['fee'])) {
                    $solicData[$numsolic]['fee'] += floatval($registro['fee']);
                }
                if (isset($registro['feetime'])) {
                    $solicData[$numsolic]['feetime'] += intval($registro['feetime']);
                }
            }
        }
    }
}
// Ahora $solicData tiene la estructura: [numsolic => ['fee' => totalFee, 'feetime' => totalFeetime]]


// if (isset($tuArray)) {
//     foreach ($tuArray as $fecha => $registros) {
//         foreach ($registros as $registro) {

//             foreach ($reportcdr2 as $subarray2) {
//                 if (@$subarray2['code'] == $registro['endreason']) {


//                     if ($subarray2['description'] == "Caller Hangup") {
//                         @$endreason = "Empresa";
//                     } else if ($subarray2['description'] == "Callee Hangup") {
//                         @$endreason = "Cliente";
//                     } else {
//                         @$endreason = $subarray2['description'];
//                     }
//                 }
//             }

//             $item = "areacode";
//             $valor = $registro['callerareacode'];
//             $AreaCode = ControllersReportCdr::ctrMostrarAreaCode($item, $valor);

//             $areacodeLocation = (is_array($AreaCode) && isset($AreaCode['location'])) ? $AreaCode['location'] : 'N/A';

//             $dataArray[] = array(
//                 'calleraccesse164' => $registro['calleraccesse164'],
//                 'callertogatewaye164' => $registro['callertogatewaye164'],
//                 'areacode' => $areacodeLocation,
//                 'endreason' => @$endreason,
//                 'calleee164' => $registro['calleee164'],
//                 'callergatewayid' => $registro['callergatewayid'],
//                 'starttime' => $registro['starttime'],
//                 'holdtime' => $registro['holdtime'],
//                 'fee' => $registro['fee'],
//             );
//         }
//     }
// }
