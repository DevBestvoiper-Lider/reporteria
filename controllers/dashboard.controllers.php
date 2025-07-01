<?php

class ControllersDashboard{

################ MOSTRAR USUARIO ################


    static public function ctrMostrarDatosHoy($item, $valor){
        
        $tabla = "e_customer";

        $respuesta = ModeloDashboard::MdlMostrarDatosHoy( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarDatosAyer($item, $valor){

        // Mejor forma: solo usa DateTime para obtener el día anterior
        $ayer = new DateTime();
        $ayer->modify('-1 day');
        $dia = $ayer->format('d');
        $mes = $ayer->format('m');
        $ano = $ayer->format('Y');

        $diaformat = $mes . $dia;
        $tabla = "e_reportcustomerfee_" . $ano . $diaformat;

        $respuesta = ModeloDashboard::MdlMostrarDatosAyer( $tabla, $item, $valor);
        return $respuesta;
    }

    static public function ctrMostrarDatosMes($item, $valor){

        $ano = date('Y');

        $mes = date('m');
        
        $tabla = "e_reportcustomerfee_". $ano .$mes."%";

        $respuesta = ModeloDashboard::MdlMostrarDatosMes( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarDatosMesAnt($item, $valor){

        $ano = date('Y');

        $restmes = (date('m') - 1);
        $mes = sprintf("%02d", $restmes);

        $tabla = "e_reportcustomerfee_".$ano.$mes."%";

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

        $dia01 = date('Ym').'03';

        if (date('Ymd') != $dia01) {
            $dia = sprintf("%02d", (date('d')));
        }
        $diaformat = date('m') . $dia;
        
        $tabla = "e_cdr_" . date('Y') .$diaformat;

         $respuesta = ModeloDashboard::MdlMostrarLlamadasCdrMes( $tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrMostrarUltimPay($item, $valor){

        $tabla = "e_payhistory_".date('Ymd');

        $respuesta = ModeloPayHistory::ctrMostrarUltimPay( $tabla, $item, $valor);

        return $respuesta;
    }

}