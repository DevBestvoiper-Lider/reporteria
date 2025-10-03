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
                $callerSolic = $_POST["callerSolic"];

                if (isset($_POST['fechainicio'])) {
                    $fechaini = str_replace("-", "", $_POST['fechainicio']);
                    $fechafin = str_replace("-", "", $_POST['fechafin']);
                } else {
                    $fechaini = null;
                    $fechafin = null;
                }
                $operador_duracion1 = isset($_POST["operador_duracion1"]) ? $_POST["operador_duracion1"] : null;
                $duracion1 = isset($_POST["duracion_valor1"]) ? $_POST["duracion_valor1"] : null;
                $operador_duracion2 = isset($_POST["operador_duracion2"]) ? $_POST["operador_duracion2"] : null;
                $duracion2 = isset($_POST["duracion_valor2"]) ? $_POST["duracion_valor2"] : null;
                $logica_duracion = isset($_POST["logica_duracion"]) ? $_POST["logica_duracion"] : null;

                foreach ($tables as $table) {
                    //     // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                    $table_date = substr($table, -8);

                    //     // Comprobar si la fecha está dentro del rango
                    if ($table_date >= "$fechaini" && $table_date <= "$fechafin") {
                        $sql = "SELECT calleraccesse164, calleee164, callertogatewaye164, callergatewayid, starttime, callerareacode, callerip, holdtime, fee, endreason FROM $table WHERE";

                        if (!empty($caller)) {
                            $sql .= ' callertogatewaye164 LIKE "%' . $caller . '" AND';
                        }

                         if (!empty($callerSolic)) {
                            $sql .= ' calleraccesse164 LIKE "%' . $callerSolic . '" AND';
                        }

                        if (!empty($callee)) {
                            $sql .= ' calleee164 LIKE "%' . $callee . '" AND';
                        }

                        // Filtros de duración mejorados
                        $condiciones_duracion = array();
                        
                        // Primer filtro de duración
                        if (!empty($operador_duracion1) && !empty($duracion1)) {
                            if ($operador_duracion1 == 'BETWEEN' && !empty($_POST['duracion_hasta1'])) {
                                $duracion_hasta1 = $_POST['duracion_hasta1'];
                                $condiciones_duracion[] = "holdtime BETWEEN $duracion1 AND $duracion_hasta1";
                            } else if ($operador_duracion1 != 'BETWEEN') {
                                $condiciones_duracion[] = "holdtime $operador_duracion1 $duracion1";
                            }
                        }

                        // Segundo filtro de duración
                        if (!empty($operador_duracion2) && !empty($duracion2)) {
                            if ($operador_duracion2 == 'BETWEEN' && !empty($_POST['duracion_hasta2'])) {
                                $duracion_hasta2 = $_POST['duracion_hasta2'];
                                $condiciones_duracion[] = "holdtime BETWEEN $duracion2 AND $duracion_hasta2";
                            } else if ($operador_duracion2 != 'BETWEEN') {
                                $condiciones_duracion[] = "holdtime $operador_duracion2 $duracion2";
                            }
                        }

                        // Agregar condiciones de duración con la lógica correspondiente
                        if (count($condiciones_duracion) == 1) {
                            $sql .= " " . $condiciones_duracion[0] . " AND";
                        } else if (count($condiciones_duracion) == 2) {
                            $operador_logico = ($logica_duracion == 'OR') ? 'OR' : 'AND';
                            $sql .= " (" . $condiciones_duracion[0] . " $operador_logico " . $condiciones_duracion[1] . ") AND";
                        }

                        $sql .= " $item = :$item ORDER BY id DESC";

                        // Limpiar AND sobrante antes del item final
                        $sql = str_replace(" AND $item = :$item", " AND $item = :$item", $sql);
                        $sql = str_replace("WHERE AND", "WHERE", $sql);

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

    static public function MdlMostrarAreaName($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT location FROM $tabla WHERE $item = :$item LIMIT 1");
            $stmt->bindParam(':' . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
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