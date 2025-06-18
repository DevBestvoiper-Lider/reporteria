<?php


$active = "Historico de Pagos";

$accountname = $_SESSION['customeraccountname'];

$item = 'customeraccount';

$valor = $accountname;

$payhistory = ControllersPayHistory::ctrMostrarPayHistory($item, $valor);

$tuArray = $payhistory;

if(isset($tuArray)){
    foreach ($tuArray as $fecha => $registros) {
        foreach ($registros as $registro) {
            $dataArray[] = array(
                'customeraccount' => $registro['customeraccount'],
                'paymoney' => $registro['paymoney'],
                'time' => $registro['time'],
                'memo' => $registro['memo'],
                'customermoney' => $registro['customermoney'],
                'loginname' => $registro['loginname'],
            );
        }
    }
}