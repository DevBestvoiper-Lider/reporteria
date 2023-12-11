<?php


$active = "Dashboard";

// el siguiente script nos ayuda a obtener el customeraccountname del usuario actual para traer la data

$item = 'nombre';

$valor = $_SESSION["nombre"];

$usuarios = ControllersUsuarios::ctrMostrarUsuarios($item, $valor);

$accountname = $usuarios['customeraccountname'];

######################## DASHBOARD ###############################

// el siguiente codigo nos ayuda a obtener el consumo del dia de hoy para luego formatearlo y mostrarlo.

$item = 'account';

$valor = $accountname;

$dashboard = ControllersDashboard::ctrMostrarDatosHoy($item, $valor);

$consumohoysinformat = $dashboard['todayconsumption'];

$saldoactual = $dashboard['money'];

$consumohoyformat = number_format(abs($consumohoysinformat), 0, ',', '.');

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
            $feesFormat = number_format(abs($key['fee']), 0, ',', '.');
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

$consumoEsteMesFormat = number_format(abs($totalEsteMes), 0, ',', '.'); // formateamos el consumo mensual

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

$porcentaje2 = ($diferencia2 / $valorAntMes) * 100;


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

         
        $sum_holdtime += @$row['feetime'];
        $feetime = abs(@$row['feetime']);
        $fee2 = abs(@$row['fee']);

        $feeconsum[] = $fee2;
        $ipgateway[] = @$row['ip'];
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

$Consumarea = array();
$Consumfee2 = 0;

if(isset($dataArray2)){
        foreach ($dataArray2 as $row) {
            $Consumfee2 += abs(@$row['fee']);

            $Consumfee2format = number_format($Consumfee2, 0, ',', '.');

            $areacode = @$row['callerareacode'];

            $Consumarea = array(
                'value' => $Consumfee2format,
                'name' => @$areacode,
            );
    }
}

$ConsumAreaCode = json_encode($Consumarea);
####################################################################################################
############# RECARGAS REALIZADAS HOY #######################


$dashboard2 = ControllersDashboard::ctrMostrarUltimPay($item, $valor);

$tuArray = $dashboard2;

?>