<?php

require_once 'conexion.php';

class ModeloPayHistory{

    static public function MdlMostrarPayHistory($tabla, $item, $valor){

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
            $stmt -> execute();

            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

                $report = array();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_POST['fechainicio'])){
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
                        $sql = "SELECT customeraccount, paymoney, memo, loginname, customermoney, time FROM $table WHERE";

                        $sql .= " $item = :$item";

                        $sql = rtrim($sql, " AND");

                        $stmt = Conexion::conectar()->prepare($sql);
                        $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                        $stmt -> execute();

                        $report[$table] = $stmt->fetchAll();

                        }
                    }

                return $report;

                }
        }else {
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
            $stmt -> execute();

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

                    return $stmt -> fetchAll();
                }
            }

            $stmt -> close();
            
            $stmt = null;
        }
    }

    static public function ctrMostrarUltimPay($tabla, $item, $valor){

        if ($item != null) {
                try {
                    $sql = "SELECT customeraccount, paymoney, memo, loginname, customermoney, time FROM $tabla WHERE";
                    $sql .= " $item = :$item LIMIT 4";
                    $sql = rtrim($sql, " AND");

                    $stmt = Conexion::conectar()->prepare($sql);
                    $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                    $stmt -> execute();

                    return $stmt->fetchAll();
                } catch (PDOException $e) {
                    return array();
                }

        }else {
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
            $stmt -> execute();

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

                    return $stmt -> fetchAll();
                }
            }

            $stmt -> close();
            
            $stmt = null;
        }

        

    }

}
