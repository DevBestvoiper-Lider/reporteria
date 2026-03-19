<?php

require_once __DIR__ . '/../../../helpers/NumberHelper.php';

$active = "Mis Prefijos";

$accountname = $_SESSION['customeraccountname'];

$item = 'account';

$valor = $accountname;

$miaccount = ControllersDashboard::ctrMostrarDatosHoy($item, $valor);

$idfeerategroup = $miaccount['feerategroup_id'];

$item = 'feerategroup_id';

$valor = $idfeerategroup;

$miaccount = ControllersMiaccount::ctrMostrarFeesRate($item, $valor);
// print_r ($miaccount);

$rateminutearray = array();
$ratesecarray = array();

foreach ($miaccount as $subarray) {
    $feeprefix = $subarray['feeprefix'];
    $areacode = $subarray['areacode'];
    $fee = $subarray['fee'];
    $period = $subarray['period'];

    $prefijosdis = $feeprefix . $areacode;

    $feeminute = (60/$period)*$fee;

    $ratesecarray[] = array (
        'rate(sec)' => $fee,
        'prefix'       => $prefijosdis
    );
    
    $rateminutearray[] = array (
        'rate(minute)' => $feeminute,
        'prefix'       => $prefijosdis
    );
}


?>