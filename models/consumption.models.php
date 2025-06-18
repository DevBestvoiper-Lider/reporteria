<?php

class ModeloConsumption {

    static public function MdlMostrarLlamadasCdrXDia($tabla, $item, $valor){

        if ($item != null) {
        
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
            $stmt -> execute();
        
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
             $report = array();

             $añoact = date('Y')."0101";
             $añoact2 = date('Y')."1231";

        
             foreach ($tables as $table) {
                 // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                $table_date = substr($table, -8);
                
                 // Comprobar si la fecha está dentro del rango
                 if ($table_date >= $añoact && $table_date <= $añoact2) {
                     $stmt = Conexion::conectar()->prepare("SELECT gatewayid, ip, feetime, fee FROM $table WHERE $item = :$item");
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
    
    static public function MdlMostrarConsumoCdrMes($tabla, $item, $valor){

        if ($item != null) {
        
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
            $stmt -> execute();
        
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
             $report = array();

             if (isset($_POST['FechaInicio'])){
                 $fechaini = str_replace("-", "", $_POST['FechaInicio']);
                 $fechafin = str_replace("-", "", $_POST['FechaFin']);
             } else {
                 $fechaini = null;
                 $fechafin = null;
             }
        
             foreach ($tables as $table) {
                 // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                $table_date = substr($table, -8);
                
                 // Comprobar si la fecha está dentro del rango
                 if ($table_date >= "$fechaini" && $table_date <= "$fechafin") {
                     $stmt = Conexion::conectar()->prepare("SELECT gatewayid, ip, feetime, fee FROM $table WHERE $item = :$item");
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