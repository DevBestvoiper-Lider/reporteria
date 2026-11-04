<?php

require_once __DIR__ . '/../../../helpers/NumberHelper.php';

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
            $numsolic = isset($registro['calleraccesse164']) ? $registro['calleraccesse164'] : null;
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
$horasTot = floor($totalTime / 3600);
$minTot = floor(($totalTime % 3600) / 60);
$segTot = $totalTime % 60;
$totalTimeFormat = $horasTot . ':' . $minTot . ':' . $segTot;
$totalFeeFormat = NumberHelper::formatFeeClean(abs($totalFee));
$totalLineas = count($solicData);

$lineLabels = array();
$lineFees = array();
$lineTimes = array();
$consumoPie = array();

if (!empty($solicData)) {
    uasort($solicData, function ($a, $b) {
        $fa = abs((float) $a['fee']);
        $fb = abs((float) $b['fee']);
        if ($fa == $fb) {
            return 0;
        }
        return ($fa < $fb) ? 1 : -1;
    });

    $topLines = array_slice($solicData, 0, 12, true);
    $otrosFee = 0;
    $i = 0;
    foreach ($solicData as $numsolic => $row) {
        $feeAbs = abs((float) $row['fee']);
        if ($i < 12) {
            $lineLabels[] = (string) $numsolic;
            $lineFees[] = $feeAbs;
            $lineTimes[] = (int) $row['feetime'];
            $consumoPie[] = array('name' => (string) $numsolic, 'value' => round($feeAbs, 2));
        } else {
            $otrosFee += $feeAbs;
        }
        $i++;
    }
    if ($otrosFee > 0) {
        $consumoPie[] = array('name' => 'Otras líneas', 'value' => round($otrosFee, 2));
    }
}

$lineLabelsJson = json_encode($lineLabels);
$lineFeesJson = json_encode($lineFees);
$lineTimesJson = json_encode($lineTimes);
$consumoPieJson = json_encode($consumoPie);


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
