<?php

require_once 'conexion.php';

class ModeloReportCdr
{

    static public function MdlMostrarDatosCdrMes($tabla, $item, $valor)
    {
        try {
            // Validar entrada básica - permitir % para patrones LIKE
            if (empty($tabla) || !preg_match('/^[a-zA-Z0-9_%]+$/', $tabla)) {
                return array();
            }

            if ($item != null) {
                $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla%'");
                $stmt->execute();
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Recopila los datos del formulario con validación
                    $caller = isset($_POST["caller"]) ? trim($_POST["caller"]) : '';
                    $callee = isset($_POST["callee"]) ? trim($_POST["callee"]) : '';
                    $callerSolic = isset($_POST["callerSolic"]) ? trim($_POST["callerSolic"]) : '';

                    // Filtros de duración
                    $operador_duracion1 = isset($_POST["operador_duracion1"]) ? $_POST["operador_duracion1"] : '';
                    $duracion_valor1 = isset($_POST["duracion_valor1"]) ? $_POST["duracion_valor1"] : '';
                    $duracion_hasta1 = isset($_POST["duracion_hasta1"]) ? $_POST["duracion_hasta1"] : '';
                    $operador_duracion2 = isset($_POST["operador_duracion2"]) ? $_POST["operador_duracion2"] : '';
                    $duracion_valor2 = isset($_POST["duracion_valor2"]) ? $_POST["duracion_valor2"] : '';
                    $duracion_hasta2 = isset($_POST["duracion_hasta2"]) ? $_POST["duracion_hasta2"] : '';
                    $logica_duracion = isset($_POST["logica_duracion"]) ? $_POST["logica_duracion"] : 'AND';

                    if (isset($_POST['fechainicio']) && isset($_POST['fechafin'])) {
                        $fechaini = str_replace("-", "", $_POST['fechainicio']);
                        $fechafin = str_replace("-", "", $_POST['fechafin']);
                        
                        // Validar formato de fecha
                        if (!preg_match('/^\d{8}$/', $fechaini) || !preg_match('/^\d{8}$/', $fechafin)) {
                            return array();
                        }
                    } else {
                        $fechaini = null;
                        $fechafin = null;
                    }

                    // Filtrar tablas por rango de fechas
                    $validTables = array();
                    if ($fechaini && $fechafin) {
                        foreach ($tables as $table) {
                            $table_date = substr($table, -8);
                            if (is_numeric($table_date) && $table_date >= $fechaini && $table_date <= $fechafin) {
                                $validTables[] = $table;
                            }
                        }
                    } else {
                        // Si no hay fechas, usar todas las tablas (con límite de seguridad)
                        $validTables = array_slice($tables, 0, 10); // Máximo 10 tablas
                    }

                    if (empty($validTables)) {
                        return array();
                    }

                    // Filtrar tablas que realmente tienen datos para este account
                    $tablesWithData = array();
                    foreach ($validTables as $table) {
                        $checkStmt = Conexion::conectar()->prepare("SELECT COUNT(*) as count FROM $table WHERE $item = ? LIMIT 1");
                        $checkStmt->execute([$valor]);
                        $count = $checkStmt->fetch(PDO::FETCH_ASSOC);
                        if ($count['count'] > 0) {
                            $tablesWithData[] = $table;
                        }
                    }

                    if (empty($tablesWithData)) {
                        return array();
                    }

                    // Construir condiciones WHERE con prepared statements seguros
                    $conditions = array();
                    $params = array();
                    
                    if (!empty($caller)) {
                        $conditions[] = 'callertogatewaye164 LIKE ?';
                        $params[] = "%$caller%";
                    }

                    if (!empty($callerSolic)) {
                        $conditions[] = 'calleraccesse164 LIKE ?';
                        $params[] = "%$callerSolic%";
                    }

                    if (!empty($callee)) {
                        $conditions[] = 'calleee164 LIKE ?';
                        $params[] = "%$callee%";
                    }

                    // Construir filtros de duración
                    $condiciones_duracion = array();
                    
                    // Primer filtro de duración
                    if (!empty($operador_duracion1) && !empty($duracion_valor1)) {
                        if ($operador_duracion1 == 'BETWEEN' && !empty($duracion_hasta1)) {
                            $condiciones_duracion[] = "holdtime BETWEEN ? AND ?";
                            $params[] = $duracion_valor1;
                            $params[] = $duracion_hasta1;
                        } else if ($operador_duracion1 != 'BETWEEN') {
                            $condiciones_duracion[] = "holdtime $operador_duracion1 ?";
                            $params[] = $duracion_valor1;
                        }
                    }

                    // Segundo filtro de duración
                    if (!empty($operador_duracion2) && !empty($duracion_valor2)) {
                        if ($operador_duracion2 == 'BETWEEN' && !empty($duracion_hasta2)) {
                            $condiciones_duracion[] = "holdtime BETWEEN ? AND ?";
                            $params[] = $duracion_valor2;
                            $params[] = $duracion_hasta2;
                        } else if ($operador_duracion2 != 'BETWEEN') {
                            $condiciones_duracion[] = "holdtime $operador_duracion2 ?";
                            $params[] = $duracion_valor2;
                        }
                    }

                    // Agregar condiciones de duración con la lógica correspondiente
                    if (count($condiciones_duracion) == 1) {
                        $conditions[] = $condiciones_duracion[0];
                    } else if (count($condiciones_duracion) == 2) {
                        $operador_logico = ($logica_duracion == 'OR') ? 'OR' : 'AND';
                        $conditions[] = "(" . $condiciones_duracion[0] . " $operador_logico " . $condiciones_duracion[1] . ")";
                    }

                    // Validar item y valor para prevenir inyección SQL
                    if (!preg_match('/^[a-zA-Z0-9_]+$/', $item)) {
                        return array();
                    }

                    $conditions[] = "$item = ?";
                    $params[] = $valor;

                    $whereClause = implode(" AND ", $conditions);

                    // Construir UNION ALL solo con tablas que tienen datos
                    $unionQueries = array();
                    $allParams = array();
                    
                    foreach ($tablesWithData as $index => $table) {
                        // Consulta simple sin JOINs para mejor rendimiento
                        $unionQueries[] = "(SELECT 
                            calleraccesse164, 
                            calleee164, 
                            callertogatewaye164, 
                            callergatewayid, 
                            starttime, 
                            callerareacode, 
                            callerip, 
                            holdtime, 
                            fee, 
                            endreason,
                            '$table' as source_table
                            FROM $table 
                            WHERE $whereClause)";
                        
                        // Agregar parámetros para esta consulta específica
                        $allParams = array_merge($allParams, $params);
                    }

                    $finalQuery = implode(" UNION ALL ", $unionQueries) . " ORDER BY starttime DESC";
                    
                    // Aplicar límite solo si no viene desde la API
                    $limit = isset($_POST['api_no_limit']) && $_POST['api_no_limit'] === true ? null : 500;
                    if ($limit !== null) {
                        $finalQuery .= " LIMIT " . $limit;
                    }

                    try {
                        $stmt = Conexion::conectar()->prepare($finalQuery);
                        
                        // Bind parameters usando índices posicionales
                        for ($i = 0; $i < count($allParams); $i++) {
                            $stmt->bindValue($i + 1, $allParams[$i], PDO::PARAM_STR);
                        }
                        
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        return $result;
                    } catch (Exception $e) {
                        error_log("Error en consulta CDR UNION ALL: " . $e->getMessage());
                        return array();
                    }
                }
            } else {
                $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE ?");
                $stmt->execute([$tabla]);
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

                $report = array();

                foreach ($tables as $table) {
                    // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                    $table_date = substr($table, -8);

                    // Comprobar si la fecha está dentro del rango
                    if ($table_date >= '20230301' && $table_date <= '20230831') {
                        $sql = "SELECT date, fee FROM $table";
                        $stmt = Conexion::conectar()->prepare($sql);
                        $stmt->execute();

                        return $stmt->fetchAll();
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Error en MdlMostrarDatosCdrMes: " . $e->getMessage());
            return array();
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