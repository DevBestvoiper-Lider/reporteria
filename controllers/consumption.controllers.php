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


        $fechaInicio = $_POST['FechaInicio']; // Ejemplo: "2024-12-01"
        $datetime = new DateTime($fechaInicio);
        $anio = $datetime->format('Y');
        echo $anio;
        
        $tabla = "e_reportgatewaymappingfee_". $anio ."%";

        $respuesta = ModeloConsumption::MdlMostrarConsumoCdrMes( $tabla, $item, $valor);

        return $respuesta;
    }


}