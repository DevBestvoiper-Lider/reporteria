<?php

class ControllersConsumption{

    // Tipos de tablas disponibles
    static private $tiposTabla = array(
        'gatewaymapping' => 'e_reportgatewaymappingfee_',
        'gatewayrouting' => 'e_reportgatewayroutingfee_',
        'phone' => 'e_reportphonefee_',
        'customerclearinglocation' => 'e_reportcustomerclearinglocationfee_'
    );

    // Tipos que tienen gatewayid
    static private $tiposConGateway = array('gatewaymapping', 'gatewayrouting');

    static public function ctrObtenerTiposTabla(){
        return self::$tiposTabla;
    }

    static public function ctrTieneGatewayId($tipo){
        return in_array($tipo, self::$tiposConGateway);
    }

    static public function ctrMostrarLlamadasHoy($item, $valor, $tipoTabla = 'gatewaymapping'){

        if(isset($_POST['fechabusc']) && !empty($_POST['fechabusc'])){
            $dia = str_replace("-", "", $_POST['fechabusc']);
        }else {
            $dia = date('Ymd'); // Usar fecha actual si no se proporciona
        }

        // Validar tipo de tabla
        if(!isset(self::$tiposTabla[$tipoTabla])){
            $tipoTabla = 'gatewaymapping';
        }
        
        $tabla = self::$tiposTabla[$tipoTabla].$dia;
        $tieneGateway = self::ctrTieneGatewayId($tipoTabla);

        $respuesta = ModeloConsumption::MdlMostrarLlamadasCdrXDia($tabla, $item, $valor, $tieneGateway, $tipoTabla);

        return $respuesta;
    }

    static public function ctrMostrarConsumoXMes($item, $valor, $tipoTabla = 'gatewaymapping'){
        
        // Validar tipo de tabla
        if(!isset(self::$tiposTabla[$tipoTabla])){
            $tipoTabla = 'gatewaymapping';
        }

        $tabla = self::$tiposTabla[$tipoTabla]."%";
        $tieneGateway = self::ctrTieneGatewayId($tipoTabla);

        $respuesta = ModeloConsumption::MdlMostrarConsumoCdrMes($tabla, $item, $valor, $tieneGateway, $tipoTabla);

        return $respuesta;
    }

    // Método para obtener consumo de TODAS las tablas del día
    static public function ctrMostrarLlamadasHoyTodas($item, $valor){

        if(isset($_POST['fechabusc']) && !empty($_POST['fechabusc'])){
            $dia = str_replace("-", "", $_POST['fechabusc']);
        }else {
            $dia = date('Ymd');
        }

        $resultadoConsolidado = array();

        foreach(self::$tiposTabla as $tipo => $prefijo){
            $tabla = $prefijo.$dia;
            $tieneGateway = self::ctrTieneGatewayId($tipo);
            
            $datos = ModeloConsumption::MdlMostrarLlamadasCdrXDia($tabla, $item, $valor, $tieneGateway);
            
            if(!empty($datos)){
                $resultadoConsolidado[$tipo] = array(
                    'data' => $datos,
                    'tieneGateway' => $tieneGateway
                );
            }
        }

        return $resultadoConsolidado;
    }

    // Método para obtener consumo de TODAS las tablas del mes
    static public function ctrMostrarConsumoXMesTodas($item, $valor){

        $resultadoConsolidado = array();

        foreach(self::$tiposTabla as $tipo => $prefijo){
            $tabla = $prefijo."%";
            $tieneGateway = self::ctrTieneGatewayId($tipo);
            
            $datos = ModeloConsumption::MdlMostrarConsumoCdrMes($tabla, $item, $valor, $tieneGateway);
            
            if(!empty($datos)){
                $resultadoConsolidado[$tipo] = array(
                    'data' => $datos,
                    'tieneGateway' => $tieneGateway
                );
            }
        }

        return $resultadoConsolidado;
    }

}