<?php

class ControllersMiaccount{

    static public function ctrMostrarFeesRate($item, $valor){
        
        $tabla = "e_feerate";

        $respuesta = ModeloMiaccount::MdlMostrarFeesRate( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarAreasName($item2, $valor){
        
        $tabla = "e_areacode";

        $respuesta = ModeloMiaccount::MdlMostrarAreasName( $tabla, $item2, $valor);

        return $respuesta;
    }

    static public function ctrMostrarRateGroup($item, $valor){
        
        $tabla = "e_feerategroup";

        $respuesta = ModeloMiaccount::MdlMostrarRateGroup( $tabla, $item, $valor);

        return $respuesta;
    }

}