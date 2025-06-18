<?php

class ControllersPayHistory{

################ MOSTRAR USUARIO ################

    static public function ctrMostrarPayHistory($item, $valor){

        $tabla = "e_payhistory_".date('Y')."%";

        $respuesta = ModeloPayHistory::MdlMostrarPayHistory( $tabla, $item, $valor);

        return $respuesta;
    }

}