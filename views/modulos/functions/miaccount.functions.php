<?php

require_once __DIR__ . '/../../../helpers/NumberHelper.php';

$active = "Mi Perfil";

$accountname = $_SESSION['customeraccountname'];

$item = 'account';

$valor = $accountname;

$miaccount = ControllersDashboard::ctrMostrarDatosHoy($item, $valor);

$idfeerategroup = @$miaccount['feerategroup_id'];

$item = 'feerategroup_id';

$valor = $idfeerategroup;

$miaccount = ControllersMiaccount::ctrMostrarFeesRate($item, $valor);

$item2 = null;
$valor = null;
$item3 = null;

$miaccount2 = ControllersMiaccount::ctrMostrarAreasName($item2, $valor);

$miaccount3 = ControllersMiaccount::ctrMostrarRateGroup($item3, $valor);

$rateminutearray = array();
$ratesecarray = array();

if(isset($miaccount)){

    foreach ($miaccount as $subarray) {
        $feeprefix = $subarray['feeprefix'];
        $areacode = $subarray['areacode'];
        $fee = $subarray['fee'];
        $period = $subarray['period'];
        $areaname = '';
        $memo = '';
    
        foreach ($miaccount2 as $subarray2){
            if (@$subarray2['areacode'] == $areacode){
                @$areaname = $subarray2['location'];
            }
        }
    
        foreach ($miaccount3 as $subarray3){
            if (@$subarray3['id'] == $idfeerategroup){
                @$memo = $subarray3['memo'];
            }
        }
    
    
        $prefijosdis = $feeprefix . $areacode;
    
        $feeminute = (60/$period)*$fee;
    
        $ratesecarray[] = array (
            'rate(sec)' => $fee,
            'prefix'       => $prefijosdis
        );
        
        $rateminutearray[] = array (
            'areaname' => $areaname,
            'memo' => $memo,
            'rate(minute)' => $feeminute,
            'prefix'       => $prefijosdis
        );
    }
    
}

?>