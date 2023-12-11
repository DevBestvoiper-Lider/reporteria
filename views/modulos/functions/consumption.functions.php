<?php

$active = "Reporte Consumo Diario";

$item = 'nombre';

$valor = $_SESSION["nombre"];

$usuarios = ControllersUsuarios::ctrMostrarUsuarios($item, $valor);

$accountname = $usuarios['customeraccountname'];

######################## DASHBOARD ###############################

// el siguiente codigo nos ayuda a obtener el consumo del dia de hoy para luego formatearlo y mostrarlo.

$item = 'account';

$valor = $accountname;

$dashboard = ControllersDashboard::ctrMostrarDatosHoy($item, $valor);

$consumohoysinformat = @$dashboard['todayconsumption'];

$saldoactual = @$dashboard['money'];

$accountname = @$_SESSION['customeraccountname'];

$item = 'customeraccount';

$valor = $accountname;

// $consumohoyformat = number_format(abs($consumohoysinformat), 0, ',', '.');

$saldoactualformat = number_format($saldoactual, 0, ',', '.');

$consumption = ControllersConsumption::ctrMostrarLlamadasHoy($item, $valor);

$tuArray = $consumption;

foreach ($tuArray as $fecha => $registros) {
    foreach ($registros as $registro) {
        $dataArray[] = array(
            'ip' => $registro['ip'],
            'gatewayid' => $registro['gatewayid'],
            'feetime' => $registro['feetime'],
            'fee' => $registro['fee'],
        );
    }
}

$sum_holdtime = 0;

                
$consumototal = 0;

$feeconsum = array();
$ipgateway = array();
$feetimes = array();

if(isset($dataArray)){
    foreach ($dataArray as $row) {

        $consumototal += abs($row['fee']);
        $sum_holdtime += $row['feetime'];
        $feetime = abs($row['feetime']);
        $fee2 = abs($row['fee']);
        $feeconsum[] = $fee2;
        $ipgateway[] = $row['gatewayid'];
        $feetimes[] = $feetime;
    }
}

$feesdayconsum = json_encode($feeconsum);
$serverdayconsum = json_encode($ipgateway);
$feestimedayconsum = json_encode($feetimes);

$horas = floor($sum_holdtime / 3600); // Calcula las horas completas
$sum_holdtime %= 3600; // Obtiene los segundos restantes
$minutos = floor($sum_holdtime / 60); // Calcula los minutos completos
$sum_holdtime %= 60; // Obtiene los segundos restantes


$formathor2 = $horas . ":" . $minutos . ":" . $sum_holdtime;

$numeroFormateadoTotal2 = number_format($consumototal, 0, ',', '.');