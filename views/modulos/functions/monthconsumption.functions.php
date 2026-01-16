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

// Obtener tipo de tabla seleccionado (por defecto gatewaymapping)
$tipoTabla = isset($_POST['tipoTabla']) ? $_POST['tipoTabla'] : 'gatewaymapping';

// Inicializar array de datos
$dataArray = array();

// Si se selecciona "todas", consultar todas las tablas
if($tipoTabla == 'todas'){
    $consumptionTodas = ControllersConsumption::ctrMostrarConsumoXMesTodas($item, $valor);
    
    foreach ($consumptionTodas as $tipo => $info) {
        $tieneGateway = $info['tieneGateway'];
        foreach ($info['data'] as $fecha => $registros) {
            foreach ($registros as $registro) {
                $dataItem = array(
                    'tipo' => $tipo,
                    'feetime' => $registro['feetime'],
                    'fee' => $registro['fee'],
                );
                if($tieneGateway){
                    $dataItem['ip'] = $registro['ip'];
                    $dataItem['gatewayid'] = $registro['gatewayid'];
                } else {
                    $dataItem['ip'] = $tipo; // Usar tipo como identificador
                    $dataItem['gatewayid'] = $tipo;
                }
                $dataArray[] = $dataItem;
            }
        }
    }
} else {
    // Verificar si el tipo de tabla tiene gatewayid
    $tieneGatewayId = ControllersConsumption::ctrTieneGatewayId($tipoTabla);
    
    $consumption = ControllersConsumption::ctrMostrarConsumoXMes($item, $valor, $tipoTabla);

    $tuArray = $consumption;

    foreach ($tuArray as $fecha => $registros) {
        foreach ($registros as $registro) {
            $dataItem = array(
                'tipo' => $tipoTabla,
                'feetime' => $registro['feetime'],
                'fee' => $registro['fee'],
            );
            // Solo agregar gatewayid e ip si la tabla lo tiene
            if($tieneGatewayId){
                $dataItem['ip'] = $registro['ip'];
                $dataItem['gatewayid'] = $registro['gatewayid'];
            } else {
                $dataItem['ip'] = $tipoTabla; // Usar tipo como identificador
                $dataItem['gatewayid'] = $tipoTabla;
            }
            $dataArray[] = $dataItem;
        }
    }
}

$feeconsum = array();
$ipgateway = array();
$feetimes = array();


//  print_r ($dataArray);

 // Array para almacenar los datos agrupados por IP
$datosAgrupados = [];

if(isset($dataArray)){
    foreach ($dataArray as $dato) {
        $ip = $dato['ip'];
        $gateway = $dato['gatewayid'];
        $fee = $dato['fee'];
        $holdtime = $dato['feetime'];
    
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

$sum_fee = 0;
$sum_holdtime = 0;

foreach ($datosAgrupados as $ip => $gateways) {
    foreach ($gateways as $gateway => $data) {
        $sum_holdtime += $data['sum_holdtime'];
        $sum_fee += $data['sum_fee'];

        $feeconsum[] = $data['sum_fee'];
        $ipgateway[] = $gateway;
        $feetimes[] = $data['sum_holdtime'];
    }
        
}

$horas = floor($sum_holdtime / 3600); // Calcula las horas completas
$sum_holdtime %= 3600; // Obtiene los segundos restantes
$minutos = floor($sum_holdtime / 60); // Calcula los minutos completos
$sum_holdtime %= 60; // Obtiene los segundos restantes

$formathor2 = $horas . ":" . $minutos . ":" . $sum_holdtime;

$numeroFormateadoTotal2 = number_format($sum_fee, 0, ',', '.');


$feesdayconsum = json_encode($feeconsum);
$serverdayconsum = json_encode($ipgateway);
$feestimedayconsum = json_encode($feetimes);