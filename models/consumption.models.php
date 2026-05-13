<?php

class ModeloConsumption {

    /**
     * Determinar si una tabla es del día actual y está vacía
     * En ese caso, usar e_cdr_YYYYMMDD en tiempo real
     */
    static private function debeUsarCDR($nombreTabla){
        // Extraer la fecha de la tabla (últimos 8 caracteres)
        $fechaTabla = substr($nombreTabla, -8);
        $fechaHoy = date('Ymd');
        
        return ($fechaTabla === $fechaHoy);
    }

    /**
     * Obtener el nombre de la tabla CDR correspondiente
     */
    static private function obtenerTablaCDR($nombreTablaReporte){
        $fecha = substr($nombreTablaReporte, -8);
        return "e_cdr_" . $fecha;
    }

    static public function MdlMostrarLlamadasCdrXDia($tabla, $item, $valor, $tieneGateway = true, $tipoTabla = 'gatewaymapping'){

        if ($item != null) {
        
            // Buscar la tabla específica del día
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE :tabla");
            $stmt->bindParam(':tabla', $tabla, PDO::PARAM_STR);
            $stmt->execute();
        
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
            $report = array();

            // Seleccionar columnas según el tipo de tabla
            if ($tieneGateway) {
                $columnas = "gatewayid, ip, feetime, fee";
            } elseif ($tipoTabla === 'phone') {
                $columnas = "e164, feetime, fee";
            } elseif ($tipoTabla === 'customerclearinglocation') {
                $columnas = "areacode, feetime, fee";
            } else {
                $columnas = "feetime, fee";
            }
        
            foreach ($tables as $table) {
                // Verificar si debemos usar e_cdr en lugar de la tabla de reportes
                if (self::debeUsarCDR($table)) {
                    $tablaCDR = self::obtenerTablaCDR($table);
                    
                    // Verificar que la tabla CDR existe
                    $stmtCheck = Conexion::conectar()->prepare("SHOW TABLES LIKE :tablaCDR");
                    $stmtCheck->bindParam(':tablaCDR', $tablaCDR, PDO::PARAM_STR);
                    $stmtCheck->execute();
                    
                    if ($stmtCheck->rowCount() > 0) {
                        // Mapear campos de e_cdr a formato de reportes según tipo de tabla
                        if ($tieneGateway) {
                            // gatewaymapping, gatewayrouting
                            $columnasCDR = "callergatewayid AS gatewayid, callerip AS ip, SUM(feetime) AS feetime, SUM(fee) AS fee";
                            $groupBy = "GROUP BY callergatewayid, callerip";
                        } elseif ($tipoTabla === 'phone') {
                            // phone: agrupa por e164
                            $columnasCDR = "calleee164 AS e164, SUM(feetime) AS feetime, SUM(fee) AS fee";
                            $groupBy = "GROUP BY calleee164";
                        } elseif ($tipoTabla === 'customerclearinglocation') {
                            // customerclearinglocation: agrupa por areacode
                            $columnasCDR = "calleeareacode AS areacode, SUM(feetime) AS feetime, SUM(fee) AS fee";
                            $groupBy = "GROUP BY calleeareacode";
                        } else {
                            // Otros tipos: solo totales
                            $columnasCDR = "SUM(feetime) AS feetime, SUM(fee) AS fee";
                            $groupBy = "";
                        }
                        
                        $stmt = Conexion::conectar()->prepare("SELECT $columnasCDR FROM `$tablaCDR` WHERE customeraccount = :valor $groupBy");
                        $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
                        $stmt->execute();
                        
                        $report[$table] = $stmt->fetchAll();
                    } else {
                        // Si no existe tabla CDR, usar tabla de reportes vacía
                        $stmt = Conexion::conectar()->prepare("SELECT $columnas FROM `$table` WHERE $item = :valor");
                        $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
                        $stmt->execute();
                        
                        $report[$table] = $stmt->fetchAll();
                    }
                } else {
                    // Usar tabla de reportes normal
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
            if ($tieneGateway) {
                $columnas = "gatewayid, ip, feetime, fee";
            } elseif ($tipoTabla === 'phone') {
                $columnas = "e164, feetime, fee";
            } elseif ($tipoTabla === 'customerclearinglocation') {
                $columnas = "areacode, feetime, fee";
            } else {
                $columnas = "feetime, fee";
            }
        
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
    
    static public function MdlMostrarConsumoCdrMes($tabla, $item, $valor, $tieneGateway = true, $tipoTabla = 'gatewaymapping'){

        if ($item != null) {
        
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE :tabla");
            $stmt->bindParam(':tabla', $tabla, PDO::PARAM_STR);
            $stmt->execute();
        
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
            $report = array();

            // Seleccionar columnas según el tipo de tabla
            if ($tieneGateway) {
                $columnas = "gatewayid, ip, feetime, fee";
            } elseif ($tipoTabla === 'phone') {
                $columnas = "e164, feetime, fee";
            } elseif ($tipoTabla === 'customerclearinglocation') {
                $columnas = "areacode, feetime, fee";
            } else {
                $columnas = "feetime, fee";
            }

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
                    
                    // Verificar si debemos usar e_cdr en lugar de la tabla de reportes
                    if (self::debeUsarCDR($table)) {
                        $tablaCDR = self::obtenerTablaCDR($table);
                        
                        // Verificar que la tabla CDR existe
                        $stmtCheck = Conexion::conectar()->prepare("SHOW TABLES LIKE :tablaCDR");
                        $stmtCheck->bindParam(':tablaCDR', $tablaCDR, PDO::PARAM_STR);
                        $stmtCheck->execute();
                        
                        if ($stmtCheck->rowCount() > 0) {
                            // Mapear campos de e_cdr a formato de reportes según tipo de tabla
                            if ($tieneGateway) {
                                // gatewaymapping, gatewayrouting
                                $columnasCDR = "callergatewayid AS gatewayid, callerip AS ip, SUM(feetime) AS feetime, SUM(fee) AS fee";
                                $groupBy = "GROUP BY callergatewayid, callerip";
                            } elseif ($tipoTabla === 'phone') {
                                // phone: agrupa por e164
                                $columnasCDR = "calleee164 AS e164, SUM(feetime) AS feetime, SUM(fee) AS fee";
                                $groupBy = "GROUP BY calleee164";
                            } elseif ($tipoTabla === 'customerclearinglocation') {
                                // customerclearinglocation: agrupa por areacode
                                $columnasCDR = "calleeareacode AS areacode, SUM(feetime) AS feetime, SUM(fee) AS fee";
                                $groupBy = "GROUP BY calleeareacode";
                            } else {
                                // Otros tipos: solo totales
                                $columnasCDR = "SUM(feetime) AS feetime, SUM(fee) AS fee";
                                $groupBy = "";
                            }
                            
                            $stmt = Conexion::conectar()->prepare("SELECT $columnasCDR FROM `$tablaCDR` WHERE customeraccount = :valor $groupBy");
                            $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
                            $stmt->execute();
                            
                            $report[$table] = $stmt->fetchAll();
                        } else {
                            // Si no existe tabla CDR, usar tabla de reportes
                            $stmt = Conexion::conectar()->prepare("SELECT $columnas FROM `$table` WHERE $item = :valor");
                            $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
                            $stmt->execute();
                            
                            $report[$table] = $stmt->fetchAll();
                        }
                    } else {
                        // Usar tabla de reportes normal
                        $stmt = Conexion::conectar()->prepare("SELECT $columnas FROM `$table` WHERE $item = :valor");
                        $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
                        $stmt->execute();
            
                        $report[$table] = $stmt->fetchAll();
                    }
                }
            }
            return $report;
        }else {
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
            $stmt -> execute();
        
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
            $report = array();

            // Seleccionar columnas según el tipo de tabla
            if ($tieneGateway) {
                $columnas = "gatewayid, ip, feetime, fee";
            } elseif ($tipoTabla === 'phone') {
                $columnas = "e164, feetime, fee";
            } elseif ($tipoTabla === 'customerclearinglocation') {
                $columnas = "areacode, feetime, fee";
            } else {
                $columnas = "feetime, fee";
            }
        
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