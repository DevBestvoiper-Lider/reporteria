<?php

class ControllersConsumption{

    static public function ctrMostrarLlamadasHoy($item, $valor){


        if(isset($_POST['fechabusc']) && !empty($_POST['fechabusc'])){
            $dia = str_replace("-", "", $_POST['fechabusc']);
        }else {
            $dia = date('Ymd'); // Usar fecha actual si no se proporciona
        }
        
        $tabla = "e_reportgatewaymappingfee_".$dia;

        $respuesta = ModeloConsumption::MdlMostrarLlamadasCdrXDia( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarConsumoXMes($item, $valor){
        
        $tabla = "e_reportgatewaymappingfee_%";

        $respuesta = ModeloConsumption::MdlMostrarConsumoCdrMes( $tabla, $item, $valor);

        return $respuesta;
    }


}