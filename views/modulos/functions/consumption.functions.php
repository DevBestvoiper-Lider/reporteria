<?php

require_once __DIR__ . '/../../../helpers/NumberHelper.php';

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

// Verificar si el tipo de tabla tiene gatewayid
$tieneGatewayId = ControllersConsumption::ctrTieneGatewayId($tipoTabla);

// Inicializar array de datos
$dataArray = array();

// Si se selecciona "todas", consultar todas las tablas
if($tipoTabla == 'todas'){
    $consumptionTodas = ControllersConsumption::ctrMostrarLlamadasHoyTodas($item, $valor);
    
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
    $consumption = ControllersConsumption::ctrMostrarLlamadasHoy($item, $valor, $tipoTabla);

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
$minutosTotales = $sum_holdtime / 60;
$servidoresTrafico = count(array_unique($ipgateway));
$costoPromedioMin = $minutosTotales > 0 ? ($consumototal / $minutosTotales) : 0;
$saldoNegativo = ((float) $saldoactual) < 0;
$saldoMostrar = ($saldoNegativo ? '-' : '') . '$' . number_format(abs((float) $saldoactual), 0, ',', '.');
$consumoMostrar = '$' . number_format($consumototal, 0, ',', '.');
$costoMostrar = '$' . number_format($costoPromedioMin, 0, ',', '.');
$minutosMostrar = number_format($minutosTotales, 0, ',', '.');

$palette = array('#2563eb', '#22c55e', '#38bdf8', '#fb923c', '#8b5cf6', '#14b8a6', '#f43f5e', '#84cc16');
$legendServidores = array();
for ($i = 0; $i < count($ipgateway); $i++) {
    $feeAbs = abs($feeconsum[$i]);
    $pct = $consumototal > 0 ? round(($feeAbs / $consumototal) * 100) : 0;
    $legendServidores[] = array(
        'name' => $ipgateway[$i],
        'fee' => $feeAbs,
        'pct' => $pct,
        'color' => $palette[$i % count($palette)],
    );
}
$paletteJson = json_encode($palette);

$horas = floor($sum_holdtime / 3600); // Calcula las horas completas
$sum_holdtime %= 3600; // Obtiene los segundos restantes
$minutos = floor($sum_holdtime / 60); // Calcula los minutos completos
$sum_holdtime %= 60; // Obtiene los segundos restantes


$formathor2 = $horas . ":" . $minutos . ":" . $sum_holdtime;

$numeroFormateadoTotal2 = NumberHelper::formatFeeClean($consumototal);