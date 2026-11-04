<?php

require_once __DIR__ . '/../../../helpers/NumberHelper.php';

$active = "Dashboard";

// el siguiente script nos ayuda a obtener el customeraccountname del usuario actual para traer la data

$accountname = isset($_SESSION["customeraccountname"]) ? $_SESSION["customeraccountname"] : '';

if ($accountname === '' && !empty($_SESSION["nombre"])) {
    $item = 'nombre';
    $valor = $_SESSION["nombre"];
    $usuarios = ControllersUsuarios::ctrMostrarUsuarios($item, $valor);
    $accountname = isset($usuarios['customeraccountname']) ? $usuarios['customeraccountname'] : '';
}

######################## DASHBOARD ###############################

// el siguiente codigo nos ayuda a obtener el consumo del dia de hoy para luego formatearlo y mostrarlo.

$item = 'account';

$valor = $accountname;

$dashboard = ControllersDashboard::ctrMostrarDatosHoy($item, $valor);


$consumohoysinformat = (float)(isset($dashboard['todayconsumption']) ? $dashboard['todayconsumption'] : 0);

$saldoactual = (float)(isset($dashboard['money']) ? $dashboard['money'] : 0);

$consumohoyformat = NumberHelper::formatFeeClean(abs($consumohoysinformat));

$saldoactualformat = number_format($saldoactual, 0, ',', '.');

// este codigo nos ayudara a comparar el cosumo del dia de ayer con el dia de hoy para asi mostrar el porcentaje.

$item = 'customeraccount';

$valor = $accountname;

$dashboard = ControllersDashboard::ctrMostrarDatosAyer($item, $valor);

$consumoAyerSinFormat = @$dashboard['fee'];

$valorAyer = $consumoAyerSinFormat;
$valorHoy = $consumohoysinformat;

$diferencia = $valorHoy - $valorAyer;

if ($valorAyer != 0) {
    $porcentaje = ($diferencia / $valorAyer) * 100;
} else {
    // Manejo especial si $valorAntMes es 0
    if ($valorHoy > 0) {
        $porcentaje = 100;  // No podemos calcular el porcentaje, pero hay un incremento
    } elseif ($valorHoy <= 0) {
        $porcentaje = -100; // No podemos calcular el porcentaje, pero hay una disminución
    } 
    
}

if ($diferencia > 0) {
    $porcentajeSet = round($porcentaje, 2) . "%";
} elseif ($diferencia <= 0) {
    $porcentajeSet = "-" . round(abs($porcentaje), 2) . "%";
} 

// este codigo nos ayudara a Mostrar el consumo mensual y nos dara los datos para el reporte del mes.

$dashboard = ControllersDashboard::ctrMostrarDatosMes($item, $valor);

$tuArray = $dashboard;

$totalFee = 0;

$feesArray = array();

$totalFeeMes = 0;

foreach ($tuArray as $subarray) {
    foreach ($subarray as $key) {
        if (isset($key['fee'])) {
            @$totalFeeMes += @$key['fee'];
            $feesFormat = NumberHelper::formatFeeClean(abs($key['fee']));
            $feesArray[] = $feesFormat;
        }
    }
}

$totalEsteMes = $totalFeeMes + $consumohoysinformat;

$datesArray = array();

foreach ($tuArray as $subarray) {
    foreach ($subarray as $key) {
        if (isset($key['date'])) {
            $timestampSeconds = intval($key['date'] / 1000); // Convertir milisegundos a segundos
            $formattedDate = date("Y-m-d", $timestampSeconds);
            $datesArray[] = "$formattedDate";
        }
    }
}
// array_push($feesArray, $consumohoyformat);
// array_push($datesArray, date('Y-m-d'));

$consumoEsteMesFormat = NumberHelper::formatFeeClean(abs($totalEsteMes)); // formateamos el consumo mensual

// este codigo nos ayudara a comparar el cosumo del consumo del mes anterior con el actual para asi mostrar el porcentaje.

$dashboard = ControllersDashboard::ctrMostrarDatosMesAnt($item, $valor);
$tuArray = $dashboard;

// print_r ($dashboard);
$totalFeeAnt = 0;

foreach ($tuArray as $subarray) {
    foreach ($subarray as $key2) {
        if (isset($key2['fee'])) {
            $totalFeeAnt += $key2['fee'];
        }
    }
}

$valorAntMes = $totalFeeAnt;
$valorEsteMes = $totalEsteMes;

$diferencia2 = $valorEsteMes - $valorAntMes;

if ($valorAntMes != 0) {
    $porcentaje2 = ($diferencia2 / $valorAntMes) * 100;
} else {
    $porcentaje2 = 0;
}

$porcentajeSetMes = "0%";
if ($diferencia2 > 0) {
    $porcentajeSetMes = round($porcentaje2, 2) . "%";
} elseif ($diferencia2 < 0) {
    $porcentajeSetMes = "-" . round(abs($porcentaje2), 2) . "%";
}

$resthoy = (date('d'));
$fechahoy = sprintf("%02d", $resthoy);

$fechahoycomplet = date("Y-m")."-". $fechahoy;

array_push($feesArray, $consumohoyformat);
array_push($datesArray, $fechahoycomplet);

$fechas = json_encode($datesArray);
$feemes = json_encode($feesArray);

$datesArray = array();

foreach ($tuArray as $subarray) {
    foreach ($subarray as $key3) {
        if (isset($key3['date'])) {
            $timestampSeconds = intval($key3['date'] / 1000); // Convertir milisegundos a segundos
            $formattedDate = date("Y-m-d", $timestampSeconds);
            $datesArray[] = "$formattedDate";
        }
    }
}

$dashboard = ControllersDashboard::ctrMostrarLlamadasCdrMes($item, $valor);
// print_r ($dashboard);

$tuArray = $dashboard;

$dataArray = array();

foreach ($tuArray as $fecha => $registros) {
    foreach ($registros as $registro) {
        $dataArray[] = array(
            'callerip' => $registro['callerip'],
            'callergatewayid' => $registro['callergatewayid'],
            'holdtime' => $registro['holdtime'],
            'fee' => $registro['fee'],
            'callerareacode' => $registro['callerareacode'],
        );
    }
}

$feeconsum = array();
$ipgateway = array();
$feetimes = array();
$feetime = 0;
$sum_holdtime = 0;
$consumototal = 0;

if(isset($dataArray)){
    foreach ($dataArray as $row) {

         
        $sum_holdtime += isset($row['feetime']) ? $row['feetime'] : 0;
        $feetime = abs(isset($row['feetime']) ? $row['feetime'] : 0);
        $fee2 = abs(isset($row['fee']) ? $row['fee'] : 0);

        $feeconsum[] = $fee2;
        $ipgateway[] = isset($row['ip']) ? $row['ip'] : '';
        $feetimes[] = $feetime;
}
}

$feesdayconsum = json_encode($feeconsum);
$ipsdayconsum = json_encode($ipgateway);
$feestimedayconsum = json_encode($feetimes);

//  print_r ($dataArray);

 // Array para almacenar los datos agrupados por IP
$datosAgrupados = [];

if(isset($dataArray)){
    foreach ($dataArray as $dato) {
        $ip = $dato['callerip'];
        $gateway = $dato['callergatewayid'];
        $fee = $dato['fee'];
        $holdtime = $dato['holdtime'];
    
        // Verificar si la IP ya existe en el array de datos agrupados
        if (!isset($datosAgrupados[$ip])) {
            $datosAgrupados[$ip] = [];
        }
    
        // Verificar si el Gateway ya existe en el array de datos agrupados para esta IP
        if (!isset($datosAgrupados[$ip][$gateway])) {
            $datosAgrupados[$ip][$gateway] = ['sum_holdtime' => 0, 'sum_fee' => 0]; // Inicializar la suma de holdtime y valores de fee
        }
    
        // Sumar holdtime para esta combinación de IP y Gateway
        $datosAgrupados[$ip][$gateway]['sum_holdtime'] += $holdtime;
    
        // Agregar el valor de fee
        $datosAgrupados[$ip][$gateway]['sum_fee'] += $fee;
    }

}

####################################################################################################
############# CONSUMO POR CODIGO DE AREA #######################


$dataArray2 = array();

foreach ($tuArray as $fecha => $registros) {
    foreach ($registros as $registro) {
        $dataArray2[] = array(
            'fee' => $registro['fee'],
            'callerareacode' => $registro['callerareacode'],
        );
    }
}

// if(isset($dataArray)){
//     foreach ($dataArray as $row) {

            
//         $sum_holdtime += @$row['fee'];
//         $fee2 = abs(@$row['fee']);

//         $feeconsum[] = $fee2;
//         $ipgateway[] = @$row['ip'];
//         $feetimes[] = $feetime;
//     }
// }

$consumoPorArea = array();

if (isset($dataArray2)) {
    foreach ($dataArray2 as $row) {
        $areacode = isset($row['callerareacode']) && $row['callerareacode'] !== ''
            ? $row['callerareacode']
            : 'Sin código';
        if (!isset($consumoPorArea[$areacode])) {
            $consumoPorArea[$areacode] = 0;
        }
        $consumoPorArea[$areacode] += abs((float)$row['fee']);
    }
}

arsort($consumoPorArea);
$ConsumAreaChart = array();
foreach ($consumoPorArea as $name => $value) {
    $ConsumAreaChart[] = array('name' => (string)$name, 'value' => round($value, 2));
}

$ConsumAreaCode = json_encode($ConsumAreaChart);

$topIps = array();
foreach ($datosAgrupados as $ip => $gateways) {
    $feeIp = 0;
    $desc = '';
    foreach ($gateways as $gateway => $data) {
        $feeIp += abs((float)$data['sum_fee']);
        if ($desc === '') {
            $desc = $gateway;
        }
    }
    $topIps[] = array(
        'ip' => $ip,
        'desc' => $desc,
        'fee' => $feeIp,
        'holdtime' => array_sum(array_column($gateways, 'sum_holdtime')),
    );
}

usort($topIps, function ($a, $b) {
    if ($a['fee'] == $b['fee']) {
        return 0;
    }
    return ($a['fee'] < $b['fee']) ? 1 : -1;
});
$topIps = array_slice($topIps, 0, 5);
$maxTopIpFee = !empty($topIps) ? max(array_column($topIps, 'fee')) : 1;

$diasRestantes = (int)date('t') - (int)date('j');
$porcentajeAvanceMes = round(((int)date('j') / (int)date('t')) * 100, 1);
$porcentajeConsumoMes = ($saldoactual + abs($totalEsteMes)) > 0
    ? min(100, round((abs($totalEsteMes) / ($saldoactual + abs($totalEsteMes))) * 100, 1))
    : 0;

$totalLlamadasHoy = isset($dataArray) ? count($dataArray) : 0;
$sumHoldtimeHoy = 0;
foreach ($datosAgrupados as $gateways) {
    foreach ($gateways as $data) {
        $sumHoldtimeHoy += (int)$data['sum_holdtime'];
    }
}
$horasHoy = floor($sumHoldtimeHoy / 3600);
$minHoy = floor(($sumHoldtimeHoy % 3600) / 60);
$segHoy = $sumHoldtimeHoy % 60;
$duracionHoyFormat = sprintf('%dh %02dm %02ds', $horasHoy, $minHoy, $segHoy);
####################################################################################################
############# RECARGAS REALIZADAS HOY #######################


$dashboard2 = ControllersDashboard::ctrMostrarUltimPay($item, $valor);

$tuArray = $dashboard2;

$hora = (int) date('G');
if ($hora < 12) {
    $saludo = 'Buenos días';
} elseif ($hora < 19) {
    $saludo = 'Buenas tardes';
} else {
    $saludo = 'Buenas noches';
}

$clienteNombre = $accountname !== '' ? $accountname : (isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Cliente');

$diaMes = max(1, (int) date('j'));
$diasMes = (int) date('t');
$consumoMesAbs = abs((float) $totalEsteMes);
$consumoHoyAbs = abs((float) $consumohoysinformat);
$consumoAyerAbs = abs((float) $valorAyer);
$promedioDia = $consumoMesAbs / $diaMes;
$proyeccionMes = $promedioDia * $diasMes;
$diasEquivalentes = $promedioDia > 0 ? (int) round(abs($saldoactual) / $promedioDia) : 0;
$saldoNegativo = $saldoactual < 0;

$deltaHoyNum = isset($porcentaje) ? (float) $porcentaje : 0;
$deltaMesNum = isset($porcentaje2) ? (float) $porcentaje2 : 0;
$deltaProy = $valorAntMes != 0 ? (($proyeccionMes - abs((float) $valorAntMes)) / abs((float) $valorAntMes)) * 100 : 0;

$sparkHoy = array($consumoAyerAbs, $consumoHoyAbs);
$sparkMes = array_map('floatval', array_slice($feesArray, -12));
if (count($sparkMes) < 2) {
    $sparkMes = array($consumoMesAbs * 0.7, $consumoMesAbs);
}
$sparkProy = $sparkMes;
$sparkProy[] = $proyeccionMes;

if (!function_exists('bvSparkline')) {
    function bvSparkline($values, $color = '#2563eb') {
        $values = array_values(array_map('floatval', (array) $values));
        if (count($values) < 2) {
            $values = array(0, 1);
        }
        $min = min($values);
        $max = max($values);
        $range = ($max - $min) ?: 1;
        $w = 92;
        $h = 38;
        $n = count($values);
        $pts = array();
        foreach ($values as $i => $v) {
            $x = ($i / ($n - 1)) * $w;
            $y = $h - (($v - $min) / $range) * ($h - 6) - 3;
            $pts[] = round($x, 1) . ',' . round($y, 1);
        }
        $points = implode(' ', $pts);
        return '<svg width="' . $w . '" height="' . $h . '" viewBox="0 0 ' . $w . ' ' . $h . '" aria-hidden="true"><polyline fill="none" stroke="' . htmlspecialchars($color, ENT_QUOTES, 'UTF-8') . '" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" points="' . $points . '"/></svg>';
    }
}

if (!function_exists('bvMoney')) {
    function bvMoney($n, $withSign = true) {
        $sign = ($withSign && $n < 0) ? '-' : '';
        return $sign . '$' . number_format(abs((float) $n), 0, ',', '.');
    }
}

if (!function_exists('bvDeltaHtml')) {
    function bvDeltaHtml($pct) {
        $up = $pct >= 0;
        $cls = $up ? 'up' : 'down';
        $prefix = $up ? '+' : '';
        return '<span class="bv-delta ' . $cls . '">' . $prefix . number_format($pct, 1, ',', '.') . '%</span>';
    }
}

$minutosHoy = (int) round($sumHoldtimeHoy / 60);

$ultimoPagoMonto = 0;
$ultimoPagoFecha = 'Sin registros';
if (is_array($tuArray) && !empty($tuArray)) {
    $pago = reset($tuArray);
    if (is_array($pago) && isset($pago['paymoney'])) {
        $ultimoPagoMonto = (float) $pago['paymoney'];
        $timestamp = ((float) $pago['time'] / 1000) - (7 * 3600);
        $ultimoPagoFecha = date('d M Y · H:i', (int) $timestamp);
    }
}

$actualizadoHora = date('H:i');

?>