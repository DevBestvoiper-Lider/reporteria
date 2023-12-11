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

if(isset($tuArray)){
    foreach ($tuArray as $fecha => $registros) {
        foreach ($registros as $registro) {

            foreach($reportcdr2 as $subarray2){
                if(@$subarray2['code'] == $registro['endreason']){
                    @$endreason = $subarray2['description'];
                }
            }

            $dataArray[] = array(
                'callere164' => $registro['callere164'],
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

?>