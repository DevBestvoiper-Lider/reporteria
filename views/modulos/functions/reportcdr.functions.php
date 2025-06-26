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

$item = null;

$valor = null;

$AreaCode = ControllersReportCdr::ctrMostrarAreaCode($item, $valor);

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

            foreach ($AreaCode as $subarray3) {
                if (@$subarray3['areacode'] == $registro['callerareacode']) {
                    @$registro['callerareaname'] = $subarray3['location'];
                }
            }

            $dataArray[] = array(
                'calleraccesse164' => $registro['calleraccesse164'],
                'callertogatewaye164' => $registro['callertogatewaye164'],
                'areacode' => $registro['callerareaname'],
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
