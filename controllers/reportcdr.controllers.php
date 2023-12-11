<?php

class ControllersReportCdr{

################ MOSTRAR USUARIO ################

    static public function ctrMostrarDatosCdrMes($item, $valor){

        $tabla = "e_cdr_2023%";

        $respuesta = ModeloReportCdr::MdlMostrarDatosCdrMes( $tabla, $item, $valor);

        return $respuesta;
    }


    static public function ctrMostrarEndReason($item, $valor) {
        $tabla = "e_end_reason";

        $respuesta = ModeloReportCdr::MdlMostrarEndReason($tabla, $item, $valor);

        return $respuesta;
    }

    // static public function ctrMostrarDatosMesAnt($item, $valor){

    //     $restmes = (date('m') - 1);
    //     $mes = sprintf("%02d", $restmes);

    //     $tabla = "e_reportcustomerfee_2023".$mes."%";

    //     $respuesta = ModeloDashboard::MdlMostrarDatosMesAnt( $tabla, $item, $valor);

    //     return $respuesta;
    // }

    // static public function ctrMostrarDatosAno($item, $valor){

    //     $ano = date('Y');
        
    //     $tabla = "e_reportcustomerfee_".$ano."%";

    //     $respuesta = ModeloDashboard::MdlMostrarDatosAno( $tabla, $item, $valor);

    //     return $respuesta;
    // }

    // static public function ctrMostrarDatosAnoAnt($item, $valor){

    //     $restano = (date('Y') - 1);
    //     $ano = sprintf("%02d", $restano);

    //     $tabla = "e_reportcustomerfee_".$ano."%";

    //     $respuesta = ModeloDashboard::MdlMostrarDatosAnoAnt( $tabla, $item, $valor);

    //     return $respuesta;
    // }


}