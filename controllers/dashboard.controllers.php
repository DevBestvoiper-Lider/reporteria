<?php

class ControllersDashboard{

################ MOSTRAR USUARIO ################


    static public function ctrMostrarDatosHoy($item, $valor){
        
        $tabla = "e_customer";

        $respuesta = ModeloDashboard::MdlMostrarDatosHoy( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarDatosAyer($item, $valor){

        $dia01 = date('Ym').'01';

        if (date('Ymd') != $dia01) {
            $dia = sprintf("%02d", (date('d')-2));
        } else {
            $restmes = (date('m') - 1);
            $mes = sprintf("%02d", $restmes);
            $dia = $mes.'30';
        }

        $diaformat = date('m') . $dia;

        $ayer = $diaformat;
        
        $tabla = "e_reportcustomerfee_2023".$ayer;

        $respuesta = ModeloDashboard::MdlMostrarDatosAyer( $tabla, $item, $valor);
    }

    static public function ctrMostrarDatosMes($item, $valor){

        $mes = date('m');
        
        $tabla = "e_reportcustomerfee_2023".$mes."%";

        $respuesta = ModeloDashboard::MdlMostrarDatosMes( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarDatosMesAnt($item, $valor){

        $restmes = (date('m') - 1);
        $mes = sprintf("%02d", $restmes);

        $tabla = "e_reportcustomerfee_2023".$mes."%";

        $respuesta = ModeloDashboard::MdlMostrarDatosMesAnt( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarDatosAno($item, $valor){

        $ano = date('Y');
        
        $tabla = "e_reportcustomerfee_".$ano."%";

        $respuesta = ModeloDashboard::MdlMostrarDatosAno( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarDatosAnoAnt($item, $valor){

        $restano = (date('Y') - 1);
        $ano = sprintf("%02d", $restano);

        $tabla = "e_reportcustomerfee_".$ano."%";

        $respuesta = ModeloDashboard::MdlMostrarDatosAnoAnt( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarLlamadasCdrMes($item, $valor){

        $dia01 = date('Ym').'01';

        if (date('Ymd') != $dia01) {
            $dia = sprintf("%02d", (date('d')));
        } else {
            $restmes = (date('m') - 1);
            $mes = sprintf("%02d", $restmes);
            $dia = $mes.'30';
        }

        $diaformat = date('m') . $dia;

    //    echo $diaformat;
        
        $tabla = "e_cdr_2023".$diaformat;

         $respuesta = ModeloDashboard::MdlMostrarLlamadasCdrMes( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarUltimPay($item, $valor){

        $tabla = "e_payhistory_".date('Ymd');

        $respuesta = ModeloPayHistory::ctrMostrarUltimPay( $tabla, $item, $valor);

        return $respuesta;
    }

}