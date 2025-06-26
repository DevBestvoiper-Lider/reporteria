<?php

$active = "CDR Reportes";

$accountname = $_SESSION['customeraccountname'];

$item = 'customeraccount';

$valor = $accountname;

$reportcdr = ControllersReportCdr::ctrMostrarDatosCdrMes($item, $valor);

$item = null;

$valor = null;

$reportcdr2 = ControllersReportCdr::ctrMostrarEndReason($item, $valor);

$tuArray = $reportcdr;

if (isset($tuArray)) {
    foreach ($tuArray as $fecha => $registros) {
        foreach ($registros as $registro) {

            foreach ($reportcdr2 as $subarray2) {
                if (@$subarray2['code'] == $registro['endreason']) {


                    if ($subarray2['description'] == "Caller Hangup") {
                        @$endreason = "Empresa";
                    } else if ($subarray2['description'] == "Callee Hangup") {
                        @$endreason = "Cliente";
                    } else {
                        @$endreason = $subarray2['description'];
                    }
                }
            }

            $item = "areacode";
            $valor = $registro['callerareacode'];
            $AreaCode = ControllersReportCdr::ctrMostrarAreaCode($item, $valor);

            $areacodeLocation = (is_array($AreaCode) && isset($AreaCode['location'])) ? $AreaCode['location'] : 'N/A';

            $dataArray[] = array(
                'calleraccesse164' => $registro['calleraccesse164'],
                'callertogatewaye164' => $registro['callertogatewaye164'],
                'areacode' => $areacodeLocation,
                'endreason' => @$endreason,
                'calleee164' => $registro['calleee164'],
                'callergatewayid' => $registro['callergatewayid'],
                'starttime' => $registro['starttime'],
                'holdtime' => $registro['holdtime'],
                'fee' => $registro['fee'],
            );
        }
    }
}
