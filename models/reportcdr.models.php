<?php

require_once 'conexion.php';

class ModeloReportCdr
{

    static public function MdlMostrarDatosCdrMes($tabla, $item, $valor)
    {

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
            $stmt->execute();

            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $report = array();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Recopila los datos del formulario
                $caller = $_POST["caller"];
                $callee = $_POST["callee"];

                if (isset($_POST['fechainicio'])) {
                    $fechaini = str_replace("-", "", $_POST['fechainicio']);
                    $fechafin = str_replace("-", "", $_POST['fechafin']);
                } else {
                    $fechaini = null;
                    $fechafin = null;
                }

                foreach ($tables as $table) {
                    //     // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                    $table_date = substr($table, -8);

                    //     // Comprobar si la fecha está dentro del rango
                    if ($table_date >= "$fechaini" && $table_date <= "$fechafin") {
                        $sql = "SELECT calleraccesse164, calleee164, callertogatewaye164, callergatewayid, starttime, callerip, holdtime, fee, endreason FROM $table WHERE";

                        if (!empty($caller)) {
                            $sql .= " callertogatewaye164 = $caller AND";
                        }

                        if (!empty($callee)) {
                            $sql .= " calleee164 = $callee AND";
                        }

                        $sql .= " $item = :$item ORDER BY id DESC LIMIT 500";

                        $sql = rtrim($sql, " AND");

                        $stmt = Conexion::conectar()->prepare($sql);
                        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
                        $stmt->execute();

                        $report[$table] = $stmt->fetchAll();
                    }
                }

                return $report;
            }
        } else {
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
            $stmt->execute();

            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $report = array();

            foreach ($tables as $table) {
                // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                $table_date = substr($table, -8);

                // Comprobar si la fecha está dentro del rango
                if ($table_date >= '20230301' && $table_date <= '20230831') {
                    $sql = "SELECT date, fee FROM $table";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    return $stmt->fetchAll();
                }
            }

            $stmt->close();

            $stmt = null;
        }
    }
    static public function MdlMostrarEndReason($tabla, $item, $valor)
    {
        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();

            return $stmt->fetchAll();
        }

        $stmt->close();

        $stmt = null;
    }


    // static public function MdlMostrarDatosMesAnt($tabla, $item, $valor){

    //     if ($item != null) {

    //         $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
    //         $stmt -> execute();

    //         $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    //          $report = array();

    //          $restmes = (date('m') - 1);
    //          $mesant = sprintf("%02d", $restmes);

    //          foreach ($tables as $table) {
    //              // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
    //             $table_date = substr($table, -8);

    //              // Comprobar si la fecha está dentro del rango
    //              if ($table_date >= '2023'. $mesant .'01' && $table_date <= '2023'. $mesant .'31') {
    //                  $stmt = Conexion::conectar()->prepare("SELECT date, fee FROM $table WHERE $item = :$item");
    //                  $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
    //                  $stmt -> execute();

    //                  $report[$table] = $stmt->fetchAll();
    //              }
    //         }
    //         return $report;
    //     }else {
    //         $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
    //         $stmt -> execute();

    //         $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    //         $report = array();

    //         foreach ($tables as $table) {
    //             // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
    //             $table_date = substr($table, -8);

    //             // Comprobar si la fecha está dentro del rango
    //             if ($table_date >= '2023'. $mesant .'01' && $table_date <= '2023'. $mesant .'31') {
    //                 $sql = "SELECT date, fee FROM $table";
    //                 $stmt = $conn->prepare($sql);
    //                 $stmt->execute();

    //                return $stmt -> fetchAll();
    //             }
    //         }

    //         $stmt -> close();

    //         $stmt = null;
    //     }

    // }


    // static public function MdlMostrarDatosAno($tabla, $item, $valor){

    //     if ($item != null) {

    //         $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
    //         $stmt -> execute();

    //         $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    //          $report = array();

    //          // $mesact = date('m');

    //          $anoact = date('Y');

    //          foreach ($tables as $table) {
    //              // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
    //             $table_date = substr($table, -8);

    //              // Comprobar si la fecha está dentro del rango
    //              if ($table_date >= $anoact .'0101' && $table_date <= $anoact . '1231') {
    //                  $stmt = Conexion::conectar()->prepare("SELECT date, fee FROM $table WHERE $item = :$item");
    //                  $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
    //                  $stmt -> execute();

    //                  $report[$table] = $stmt->fetchAll();
    //              }
    //         }
    //         return $report;
    //     }else {
    //         $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
    //         $stmt -> execute();

    //         $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    //         $report = array();

    //         foreach ($tables as $table) {
    //             // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
    //             $table_date = substr($table, -8);

    //             // Comprobar si la fecha está dentro del rango
    //             if ($table_date >= '20230301' && $table_date <= '20230831') {
    //                 $sql = "SELECT date, fee FROM $table";
    //                 $stmt = $conn->prepare($sql);
    //                 $stmt->execute();

    //                return $stmt -> fetchAll();
    //             }
    //         }

    //         $stmt -> close();

    //         $stmt = null;
    //     }

    // }

    // static public function MdlMostrarDatosAnoAnt($tabla, $item, $valor){

    //     if ($item != null) {

    //         $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
    //         $stmt -> execute();

    //         $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    //          $report = array();

    //          $restano = (date('Y') - 1);
    //          $anoant = sprintf("%02d", $restano);

    //          foreach ($tables as $table) {
    //              // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
    //             $table_date = substr($table, -8);

    //              // Comprobar si la fecha está dentro del rango
    //              if ($table_date >= $anoant .'0101' && $table_date <= $anoant . '1231') {
    //                  $stmt = Conexion::conectar()->prepare("SELECT date, fee FROM $table WHERE $item = :$item");
    //                  $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
    //                  $stmt -> execute();

    //                  $report[$table] = $stmt->fetchAll();
    //              }else {
    //                 $report[$table] = null;
    //              }
    //         }
    //         return $report;
    //     }else {
    //         $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
    //         $stmt -> execute();

    //         $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    //         $report = array();

    //         foreach ($tables as $table) {
    //             // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
    //             $table_date = substr($table, -8);

    //             // Comprobar si la fecha está dentro del rango
    //             if ($table_date >= $anoant .'0101' && $table_date <= $anoant . '1231') {
    //                 $sql = "SELECT date, fee FROM $table";
    //                 $stmt = $conn->prepare($sql);
    //                 $stmt->execute();

    //                return $stmt -> fetchAll();
    //             }
    //         }

    //         $stmt -> close();

    //         $stmt = null;
    //     }

    // }
}
