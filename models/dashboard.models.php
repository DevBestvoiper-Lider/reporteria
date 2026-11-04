<?php

    require_once 'conexion.php';

    class ModeloDashboard{

        static public function MdlMostrarDatosHoy($tabla, $item, $valor){

            if ($item != null) {

                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
                $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                $stmt -> execute();

                return $stmt -> fetch();
            }else {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
                $stmt -> execute();

                return $stmt -> fetchAll();
            }

            $stmt -> close();
            
            $stmt = null;
        }

        static public function MdlMostrarDatosAyer($tabla, $item, $valor){

            if ($item != null) {
                try {
                    $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
                    $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                    $stmt -> execute();

                    return $stmt -> fetch();
                } catch (PDOException $e) {
                    return array('fee' => 0);
                }
            }else {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
                $stmt -> execute();

                return $stmt -> fetchAll();
            }

            $stmt -> close();
            
            $stmt = null;
        }

        static public function MdlMostrarDatosMes($tabla, $item, $valor){

            if ($item != null) {
                $report = array();
                $year = date('Y');
                $mesact = date('m');
                $diaActual = (int)date('d');

                for ($dia = 1; $dia <= $diaActual; $dia++) {
                    $table = "e_reportcustomerfee_" . $year . $mesact . sprintf("%02d", $dia);

                    try {
                        $stmt = Conexion::conectar()->prepare("SELECT date, fee FROM $table WHERE $item = :$item LIMIT 100");
                        $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                        $stmt -> execute();
                        $rows = $stmt->fetchAll();

                        if (!empty($rows)) {
                            $report[$table] = $rows;
                        }
                    } catch (PDOException $e) {
                        continue;
                    }
                }

                return $report;
            }else {
                $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
                $stmt -> execute();

                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

                $report = array();

                foreach ($tables as $table) {
                    // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                    $table_date = substr($table, -8);
                    
                    // Comprobar si la fecha está dentro del rango
                    if ($table_date >= '20220101' && $table_date <= '20230831') {
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

        static public function MdlMostrarDatosMesAnt($tabla, $item, $valor){

            if ($item != null) {
                $report = array();
                $prev = new DateTime('first day of last month');
                $year = $prev->format('Y');
                $mesant = $prev->format('m');
                $diasMes = (int)$prev->format('t');

                for ($dia = 1; $dia <= $diasMes; $dia++) {
                    $table = "e_reportcustomerfee_" . $year . $mesant . sprintf("%02d", $dia);

                    try {
                        $stmt = Conexion::conectar()->prepare("SELECT date, fee FROM $table WHERE $item = :$item LIMIT 100");
                        $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                        $stmt -> execute();
                        $rows = $stmt->fetchAll();

                        if (!empty($rows)) {
                            $report[$table] = $rows;
                        }
                    } catch (PDOException $e) {
                        continue;
                    }
                }

                return $report;
            }else {
                $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
                $stmt -> execute();

                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

                $report = array();

                $year = date('Y');

                foreach ($tables as $table) {
                    // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                    $table_date = substr($table, -8);
                    
                    // Comprobar si la fecha está dentro del rango
                    if ($table_date >= '2022'. $mesant .'01' && $table_date <= $year. $mesant .'31') {
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


        static public function MdlMostrarDatosAno($tabla, $item, $valor){

            if ($item != null) {

                $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
                $stmt -> execute();

                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

                 $report = array();

                 // $mesact = date('m');

                 $anoact = date('Y');

                 foreach ($tables as $table) {
                     // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                    $table_date = substr($table, -8);
                    
                     // Comprobar si la fecha está dentro del rango
                     if ($table_date >= $anoact .'0101' && $table_date <= $anoact . '1231') {
                         $stmt = Conexion::conectar()->prepare("SELECT date, fee FROM $table WHERE $item = :$item LIMIT 100");
                         $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                         $stmt -> execute();

                         $report[$table] = $stmt->fetchAll();
                     }
                }
                return $report;
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

        static public function MdlMostrarDatosAnoAnt($tabla, $item, $valor){

            if ($item != null) {

                $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
                $stmt -> execute();

                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

                 $report = array();

                 $restano = (date('Y') - 1);
                 $anoant = sprintf("%02d", $restano);

                 foreach ($tables as $table) {
                     // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                    $table_date = substr($table, -8);
                    
                     // Comprobar si la fecha está dentro del rango
                     if ($table_date >= $anoant .'0101' && $table_date <= $anoant . '1231') {
                         $stmt = Conexion::conectar()->prepare("SELECT date, fee FROM $table WHERE $item = :$item LIMIT 100");
                         $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                         $stmt -> execute();

                         $report[$table] = $stmt->fetchAll();
                     }else {
                        $report[$table] = null;
                     }
                }
                return $report;
            }else {
                $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
                $stmt -> execute();

                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

                $report = array();

                foreach ($tables as $table) {
                    // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                    $table_date = substr($table, -8);
                    
                    // Comprobar si la fecha está dentro del rango
                    if ($table_date >= $anoant .'0101' && $table_date <= $anoant . '1231') {
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

        static public function MdlMostrarLlamadasCdrMes($tabla, $item, $valor){

            if ($item != null) {
                try {
                    $stmt = Conexion::conectar()->prepare("SELECT callergatewayid, callerip, holdtime, fee, callerareacode, endreason FROM $tabla WHERE $item = :$item LIMIT 100");
                    $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                    $stmt -> execute();

                    return array($tabla => $stmt->fetchAll());
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
                        $sql = "SELECT callergatewayid, callerip, COUNT(*) AS cantidad FROM $table";
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
