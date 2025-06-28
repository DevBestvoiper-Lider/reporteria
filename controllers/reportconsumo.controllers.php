<?php


class ControllersReportConsumo{

################ MOSTRAR USUARIO ################

    static public function ctrMostrarDatosCdrMes($item, $valor){

        $tabla = "e_cdr_%";

        $respuesta = ModeloReportConsumo::MdlMostrarDatosCdrMes( $tabla, $item, $valor);

        return $respuesta;
    }

}