<?php

class ControllersConsumption{

    static public function ctrMostrarLlamadasHoy($item, $valor){


        if(isset($_POST['fechabusc'])){
            $dia = str_replace("-", "", $_POST['fechabusc']);
        }else {
            $dia = null;
        }
        
        $tabla = "e_reportgatewaymappingfee_".$dia;

        $respuesta = ModeloConsumption::MdlMostrarLlamadasCdrXDia( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarConsumoXMes($item, $valor){
        
        $tabla = "e_reportgatewaymappingfee_". date('Y') ."%";

        $respuesta = ModeloConsumption::MdlMostrarConsumoCdrMes( $tabla, $item, $valor);

        return $respuesta;
    }


}