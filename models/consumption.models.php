<?php

class ModeloConsumption {

    static public function MdlMostrarLlamadasCdrXDia($tabla, $item, $valor, $tieneGateway = true){

        if ($item != null) {
        
            // Buscar la tabla específica del día
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE :tabla");
            $stmt->bindParam(':tabla', $tabla, PDO::PARAM_STR);
            $stmt->execute();
        
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
            $report = array();

            // Seleccionar columnas según el tipo de tabla
            // Las tablas gateway tienen: gatewayid, ip, feetime, fee
            // Las otras tablas solo tienen: feetime, fee (y otras columnas específicas)
            $columnas = $tieneGateway ? "gatewayid, ip, feetime, fee" : "feetime, fee";
        
            foreach ($tables as $table) {
                $stmt = Conexion::conectar()->prepare("SELECT $columnas FROM `$table` WHERE $item = :valor");
                $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
                $stmt->execute();
        
                $report[$table] = $stmt->fetchAll();
            }
            return $report;
        }else {
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
            $stmt -> execute();
        
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
            $report = array();

            // Seleccionar columnas según el tipo de tabla
            $columnas = $tieneGateway ? "gatewayid, ip, feetime, fee" : "feetime, fee";
        
            foreach ($tables as $table) {
                // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                $table_date = substr($table, -8);
                
                // Comprobar si la fecha está dentro del rango
                if ($table_date >= '20230301' && $table_date <= '20230831') {
                    $sql = "SELECT $columnas FROM $table";
                    $stmt = Conexion::conectar()->prepare($sql);
                    $stmt->execute();
        
                   return $stmt -> fetchAll();
                }
            }
        
            $stmt -> close();
            
            $stmt = null;
        }
        
        }
    
    static public function MdlMostrarConsumoCdrMes($tabla, $item, $valor, $tieneGateway = true){

        if ($item != null) {
        
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE :tabla");
            $stmt->bindParam(':tabla', $tabla, PDO::PARAM_STR);
            $stmt->execute();
        
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
            $report = array();

            // Seleccionar columnas según el tipo de tabla
            // Las tablas gateway tienen: gatewayid, ip, feetime, fee
            // Las otras tablas solo tienen: feetime, fee
            $columnas = $tieneGateway ? "gatewayid, ip, feetime, fee" : "feetime, fee";

            // Obtener fechas del POST o usar el mes actual como predeterminado
            if (isset($_POST['FechaInicio']) && !empty($_POST['FechaInicio'])){
                $fechaini = str_replace("-", "", $_POST['FechaInicio']);
                $fechafin = str_replace("-", "", $_POST['FechaFin']);
            } else {
                // Usar el primer y último día del mes actual como predeterminado
                $fechaini = date('Ym01');
                $fechafin = date('Ymd');
            }
        
            foreach ($tables as $table) {
                // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                $table_date = substr($table, -8);
                
                // Comprobar si la fecha está dentro del rango
                if ($table_date >= $fechaini && $table_date <= $fechafin) {
                    $stmt = Conexion::conectar()->prepare("SELECT $columnas FROM `$table` WHERE $item = :valor");
                    $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
                    $stmt->execute();
        
                    $report[$table] = $stmt->fetchAll();
                }
            }
            return $report;
        }else {
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
            $stmt -> execute();
        
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
            $report = array();

            // Seleccionar columnas según el tipo de tabla
            $columnas = $tieneGateway ? "gatewayid, ip, feetime, fee" : "feetime, fee";
        
            foreach ($tables as $table) {
                // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                $table_date = substr($table, -8);
                
                // Comprobar si la fecha está dentro del rango
                if ($table_date >= '20230301' && $table_date <= '20230831') {
                    $sql = "SELECT $columnas FROM $table";
                    $stmt = Conexion::conectar()->prepare($sql);
                    $stmt->execute();
        
                   return $stmt -> fetchAll();
                }
            }
        
            $stmt -> close();
            
            $stmt = null;
        }
        
        }
        
}