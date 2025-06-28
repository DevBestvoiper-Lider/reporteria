<?php

require_once 'conexion.php';

class ModeloReportConsumo
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
                $callerSolic = $_POST["callerSolicConsumo"];

                if (isset($_POST['fechainicioConsumo'])) {
                    $fechaini = str_replace("-", "", $_POST['fechainicioConsumo']);
                    $fechafin = str_replace("-", "", $_POST['fechafinConsumo']);
                } else {
                    $fechaini = null;
                    $fechafin = null;
                }

                foreach ($tables as $table) {
                    //     // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                    $table_date = substr($table, -8);

                    //     // Comprobar si la fecha está dentro del rango
                    if ($table_date >= "$fechaini" && $table_date <= "$fechafin") {
                        $sql = "SELECT calleraccesse164, fee, feetime FROM $table WHERE";

                         if (!empty($callerSolic)) {
                            $sql .= ' calleraccesse164 LIKE "%' . $callerSolic . '" AND';
                        }

                        $sql .= " $item = :$item ORDER BY id DESC";

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
}