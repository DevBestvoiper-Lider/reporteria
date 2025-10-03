<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
ob_start();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$accountname = $_SESSION['customeraccountname'];
$item = 'customeraccount';
$valor = $accountname;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'exportar') {

    $tabla = "e_cdr_%";
    $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $caller = $_POST["caller"];
    $callee = $_POST["callee"];
    $callerSolic = $_POST["callerSolic"];
    $fechaini = isset($_POST['fechainicio']) ? str_replace("-", "", $_POST['fechainicio']) : null;
    $fechafin = isset($_POST['fechafin']) ? str_replace("-", "", $_POST['fechafin']) : null;

    $report = [];

    foreach ($tables as $table) {
        $table_date = substr($table, -8);
        if ($table_date >= $fechaini && $table_date <= $fechafin) {
            $sql = "SELECT calleraccesse164,callertogatewaye164, calleee164, callerareacode, callergatewayid, callerip, starttime, holdtime, fee, endreason FROM $table WHERE";

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

    $endr = Conexion::conectar()->query("SELECT * FROM e_end_reason")->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_ASSOC);

    // Función para obtener location por areacode con caché local
    function getAreaCodeLocation($areacode) {
        static $cache = [];
        if (!$areacode) return '';
        if (isset($cache[$areacode])) return $cache[$areacode];
        $stmt = Conexion::conectar()->prepare("SELECT location FROM e_areacode WHERE areacode = :areacode LIMIT 1");
        $stmt->bindParam(':areacode', $areacode, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $cache[$areacode] = $row ? $row['location'] : '';
        return $cache[$areacode];
    }

    $fileName = 'ReportCdr_' . date('Ymd_His') . '.txt';
    $zipName = 'CdrReport_' . date('Ymd_His') . '.zip';
    $tmpDir = sys_get_temp_dir();
    $filePath = $tmpDir . DIRECTORY_SEPARATOR . $fileName;
    $fileZip = $tmpDir . DIRECTORY_SEPARATOR . $zipName;

    // Escribir encabezado y datos en streaming
    $fh = fopen($filePath, 'w');
    fwrite($fh, "NumeroSolicitado,NumeroMostrado;NumeroMarcado;DestinoMarcado;ServidorOrigen;IpOrigen;HoraInicio;Duracion;motivodeterminacion;CostoLlamada;\n");

    foreach ($report as $registros) {
        foreach ($registros as $registro) {
            // Buscar la descripción del endreason por code en $endr
            $endreason = '';
            if (isset($registro['endreason']) && $registro['endreason'] !== '') {
                foreach ($endr as $row) {
                    if (
                        (isset($row['code']) && $row['code'] == $registro['endreason'])
                    ) {
                        $desc = $row['description'];
                        if ($desc === "Caller Hangup") {
                            $endreason = "Empresa";
                        } else if ($desc === "Callee Hangup") {
                            $endreason = "Cliente";
                        } else {
                            $endreason = $desc;
                        }
                        break;
                    }
                }
            }
            $areacode = getAreaCodeLocation($registro['callerareacode']);
            $timestampColombia = (int)($registro['starttime'] / 1000); // Convertir a entero para evitar el warning
            $starttime = date("Y-m-d H:i:s", $timestampColombia);
            $feeFormatted = number_format($registro['fee'], 2, ',', '');
            $linea = "{$registro['calleraccesse164']};{$registro['callertogatewaye164']};{$registro['calleee164']};{$areacode};{$registro['callergatewayid']};{$registro['callerip']};{$starttime};{$registro['holdtime']};{$endreason};{$feeFormatted};\n";
            fwrite($fh, $linea);
        }
    }
    fclose($fh);

    $zip = new ZipArchive();
    if ($zip->open($fileZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        http_response_code(500);
        die("Error al crear el archivo ZIP.");
    }
    $zip->addFile($filePath, $fileName);
    $zip->close();

    if (!file_exists($fileZip)) {
        http_response_code(500);
        die("ERROR: ZIP no encontrado.");
    }

    ob_end_clean();
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipName . '"');
    header('Content-Length: ' . filesize($fileZip));
    readfile($fileZip);

    // Limpieza
    unlink($filePath);
    unlink($fileZip);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] == 'exportarPagos') {

            $dataArray = []; // Inicializar $dataArray
            $report = array();

            if (isset($_POST['fechainicio'])) {
                $fechaini = str_replace("-", "", $_POST['fechainicio']);
                $fechafin = str_replace("-", "", $_POST['fechafin']);
            } else {
                $fechaini = null;
                $fechafin = null;
            }

            // Definición de $tables que faltaba
            $tabla = "e_payhistory_" . date('Y') . "%";
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE :tabla");
            $stmt->bindParam(":tabla", $tabla, PDO::PARAM_STR);
            $stmt->execute();
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Fetch records from database 
            foreach ($tables as $table) {
                //     // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
                $table_date = substr($table, -8);

                //     // Comprobar si la fecha está dentro del rango
                if ($table_date >= "$fechaini" && $table_date <= "$fechafin") {
                    $sql = "SELECT customeraccount, paymoney, memo, loginname, customermoney, time FROM $table WHERE";

                    $sql .= " $item = :$item";

                    $sql = rtrim($sql, " AND");

                    $stmt = Conexion::conectar()->prepare($sql);
                    $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
                    $stmt->execute();

                    $report[$table] = $stmt->fetchAll();
                }
            }

            foreach ($report as $fecha => $registros) {
                foreach ($registros as $registro) {
                    $dataArray[] = array(
                        'customeraccount' => $registro['customeraccount'],
                        'paymoney' => $registro['paymoney'],
                        'memo' => $registro['memo'],
                        'loginname' => $registro['loginname'],
                        'customermoney' => $registro['customermoney'],
                        'time' => $registro['time'],
                    );
                }
            }

            // Encabezado para el archivo de texto
            $archivo_txt2 = "FechaPago;Cuenta;Monto;MontoDespues;Usuario;Observacion;\n";

            // Convertir y agregar datos al archivo de texto
            foreach ($dataArray as $data) {

                $timestamp = $data['time'] / 1000; // Convertir a segundos
                $colombiaOffset = -7 * 3600; // Offset en segundos (5 horas atrás)

                $timestampColombia = $timestamp + $colombiaOffset;

                $starttime = date("Y-m-d H:i:s", $timestampColombia);

                $PagoFormateado = number_format($data['paymoney'], 2, ',', '.');

                $numeroFormateado = number_format($data['customermoney'], 2);

                $account = $data['customeraccount'];
                $User = $data['loginname'];
                $Observa = $data['memo'];

                $linea = "$starttime;$account;$PagoFormateado;$numeroFormateado;$User;$Observa;\n";
                $archivo_txt2 .= $linea;
            }

            $fileName2 = 'HistoricoPagos_' . date('Ymd') . '.txt';
            $zipFileName2 = 'PagosHistory.zip';

            // Crear un archivo de texto y escribir los datos comprimidos
            if (file_put_contents($fileName2, $archivo_txt2) === false) {
                ob_end_clean();
                error_log("Error: No se pudo escribir el archivo TXT: " . $fileName2);
                echo "Error: No se pudo generar el archivo de reporte de pagos. Contacte a soporte.";
                exit;
            }

            // Crear un archivo ZIP y agregar el archivo de texto
            $zipPay = new ZipArchive(); 
            if ($zipPay->open($zipFileName2, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                ob_end_clean();
                error_log("Error: No se pudo abrir/crear el archivo ZIP de pagos: " . $zipFileName2);
                echo "Error: No se pudo crear el archivo ZIP de pagos. Contacte a soporte (open).";
                unlink($fileName2);
                exit;
            }
            
            if (!$zipPay->addFile($fileName2, $fileName2)) {
                ob_end_clean();
                error_log("Error: No se pudo agregar el archivo TXT de pagos al ZIP: " . $fileName2);
                echo "Error: No se pudo crear el archivo ZIP de pagos. Contacte a soporte (addFile).";
                $zipPay->close();
                unlink($fileName2);
                unlink($zipFileName2);
                exit;
            }
            
            if (!$zipPay->close()) {
                ob_end_clean();
                error_log("Error: No se pudo cerrar/finalizar el archivo ZIP de pagos: " . $zipFileName2);
                echo "Error: No se pudo crear el archivo ZIP de pagos. Contacte a soporte (close).";
                unlink($fileName2);
                unlink($zipFileName2);
                exit;
            }

            ob_end_clean();

            // Descargar el archivo ZIP
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFileName2 . '"');
            header('Content-Length: ' . filesize($zipFileName2));
            readfile($zipFileName2);

            // // Eliminar los archivos temporales
            unlink($fileName2);
            unlink($zipFileName2);
            exit;
        }
    }
}
if (isset($_POST['exportarMonth'])) {
    $dataArray = []; // Inicializar $dataArray
    $report = array();

    $tabla = "e_reportgatewaymappingfee_" . date('Y') . "%";

    $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
    $stmt->execute();

    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (isset($_POST['fechainicio'])) {
        $fechaini = str_replace("-", "", $_POST['fechainicio']);
        $fechafin = str_replace("-", "", $_POST['fechafin']);
    } else {
        $fechaini = null;
        $fechafin = null;
    }

    // Fetch records from database 
    foreach ($tables as $table) {
        //     // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
        $table_date = substr($table, -8);

        //     // Comprobar si la fecha está dentro del rango
        if ($table_date >= "$fechaini" && $table_date <= "$fechafin") {
            $sql = "SELECT gatewayid, ip, feetime, fee FROM $table WHERE $item = :$item";

            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();

            $report[$table] = $stmt->fetchAll();
        }
    }

    foreach ($report as $fecha => $registros) {
        foreach ($registros as $registro) {
            $dataArray[] = array(
                'ip' => $registro['ip'],
                'gatewayid' => $registro['gatewayid'],
                'feetime' => $registro['feetime'],
                'fee' => $registro['fee'],
            );
        }
    }

    // Encabezado para el archivo de texto
    $archivo_txt3 = "ip;ServidorOrigen;DuracionLlamadas;Consumo;\n";

    // Convertir y agregar datos al archivo de texto
    $datosAgrupados = [];

    if (isset($dataArray)) {
        foreach ($dataArray as $dato) {
            $ip = $dato['ip'];
            $gateway = $dato['gatewayid'];
            $fee = $dato['fee'];
            $holdtime = $dato['feetime'];

            // Verificar si la IP ya existe en el array de datos agrupados
            if (!isset($datosAgrupados[$ip])) {
                $datosAgrupados[$ip] = [];
            }

            // Verificar si el Gateway ya existe en el array de datos agrupados para esta IP
            if (!isset($datosAgrupados[$ip][$gateway])) {
                $datosAgrupados[$ip][$gateway] = ['sum_holdtime' => 0, 'sum_fee' => 0]; // Inicializar la suma de holdtime y valores de fee
            }

            // Sumar holdtime para esta combinación de IP y Gateway
            $datosAgrupados[$ip][$gateway]['sum_holdtime'] += $holdtime;

            // Agregar el valor de fee
            $datosAgrupados[$ip][$gateway]['sum_fee'] += $fee;
        }
    }

    foreach ($datosAgrupados as $ip => $gateways) {
        foreach ($gateways as $gateway => $data) {
            $sum_holdtime = $data['sum_holdtime'];
            $holdtime = $data['sum_holdtime'];
            $sum_fee = $data['sum_fee'];

            $numeroFormateado = number_format(abs($sum_fee), 0, ',', '.');

            $horas = floor($sum_holdtime / 3600); // Calcula las horas completas
            $sum_holdtime %= 3600; // Obtiene los segundos restantes
            $minutos = floor($sum_holdtime / 60); // Calcula los minutos completos
            $sum_holdtime %= 60; // Obtiene los segundos restantes

            $formathor = $horas . ":" . $minutos . ":" . $sum_holdtime;

            $linea2 = "$ip,$gateway,$holdtime,$sum_fee,\n";
            $archivo_txt3 .= $linea2;
        }
    }


    $fileNameMonth = 'ConsumoMes_' . date('Ymd') . '.txt';
    $zipFileNameMonth = 'ConsumoMes.zip';

    // Crear un archivo de texto y escribir los datos comprimidos
    if (file_put_contents($fileNameMonth, $archivo_txt3) === false) {
        ob_end_clean();
        error_log("Error: No se pudo escribir el archivo TXT de consumo mensual: " . $fileNameMonth);
        echo "Error: No se pudo generar el archivo de consumo mensual. Contacte a soporte.";
        exit;
    }
    
    // Crear un archivo ZIP y agregar el archivo de texto
    $zipMonth = new ZipArchive();
    if ($zipMonth->open($zipFileNameMonth, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        ob_end_clean();
        error_log("Error: No se pudo abrir/crear el archivo ZIP de consumo mensual: " . $zipFileNameMonth);
        echo "Error: No se pudo crear el archivo ZIP de consumo mensual. Contacte a soporte (open).";
        unlink($fileNameMonth);
        exit;
    }

    if (!$zipMonth->addFile($fileNameMonth, $fileNameMonth)) {
        ob_end_clean();
        error_log("Error: No se pudo agregar el archivo TXT de consumo mensual al ZIP: " . $fileNameMonth);
        echo "Error: No se pudo crear el archivo ZIP de consumo mensual. Contacte a soporte (addFile).";
        $zipMonth->close();
        unlink($fileNameMonth);
        unlink($zipFileNameMonth);
        exit;
    }
    
    if (!$zipMonth->close()) {
        ob_end_clean();
        error_log("Error: No se pudo cerrar/finalizar el archivo ZIP de consumo mensual: " . $zipFileNameMonth);
        echo "Error: No se pudo crear el archivo ZIP de consumo mensual. Contacte a soporte (close).";
        unlink($fileNameMonth);
        unlink($zipFileNameMonth);
        exit;
    }

    ob_end_clean();

    // Descargar el archivo ZIP
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileNameMonth . '"');
    header('Content-Length: ' . filesize($zipFileNameMonth));
    readfile($zipFileNameMonth);

    // Eliminar los archivos temporales
    unlink($fileNameMonth);
    unlink($zipFileNameMonth);
    exit;
}

if (isset($_POST['exportarDay'])) {
    $dataArray = []; // Inicializar $dataArray
    $report = array();

    $tabla = "e_reportgatewaymappingfee_" . date('Y') . "%";

    $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
    $stmt->execute();

    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $report = array();

    if (isset($_POST['fechabusc'])) {
        $fechaini = str_replace("-", "", $_POST['fechabusc']);
    } else {
        $fechaini = null;
    }

    // Fetch records from database 
    foreach ($tables as $table) {
        //     // Extraer la fecha de la tabla (últimos 8 caracteres del nombre)
        $table_date = substr($table, -8);

        //     // Comprobar si la fecha está dentro del rango
        if ($table_date == "$fechaini") {
            $sql = "SELECT gatewayid, ip, feetime, fee FROM $table WHERE $item = :$item";

            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();

            $report[$table] = $stmt->fetchAll();
        }
    }

    foreach ($report as $fecha => $registros) {
        foreach ($registros as $registro) {
            $dataArray[] = array(
                'ip' => $registro['ip'],
                'gatewayid' => $registro['gatewayid'],
                'feetime' => $registro['feetime'],
                'fee' => $registro['fee'],
            );
        }
    }

    // Encabezado para el archivo de texto
    $archivo_txt4 = "ip;ServidorOrigen;DuracionLlamadas;Consumo;\n";

    // Convertir y agregar datos al archivo de texto
    // foreach ($dataArray as $dato) {

    //     $ip = $dato['ip'];
    //     $gatewayid = $dato['gatewayid'];
    //     $feetime = $dato['feetime']; // Puedes cambiar esto según tus datos
    //     $fee = $dato['fee'];

    //     $linea3 = "$ip,$gatewayid,$feetime,$fee,\n";
    //     $archivo_txt4 .= $linea3;
    // }

    $datosAgrupados = [];

    if (isset($dataArray)) {
        foreach ($dataArray as $dato) {
            $ip = $dato['ip'];
            $gateway = $dato['gatewayid'];
            $fee = $dato['fee'];
            $holdtime = $dato['feetime'];

            // Verificar si la IP ya existe en el array de datos agrupados
            if (!isset($datosAgrupados[$ip])) {
                $datosAgrupados[$ip] = [];
            }

            // Verificar si el Gateway ya existe en el array de datos agrupados para esta IP
            if (!isset($datosAgrupados[$ip][$gateway])) {
                $datosAgrupados[$ip][$gateway] = ['sum_holdtime' => 0, 'sum_fee' => 0]; // Inicializar la suma de holdtime y valores de fee
            }

            // Sumar holdtime para esta combinación de IP y Gateway
            $datosAgrupados[$ip][$gateway]['sum_holdtime'] += $holdtime;

            // Agregar el valor de fee
            $datosAgrupados[$ip][$gateway]['sum_fee'] += $fee;
        }
    }

    foreach ($datosAgrupados as $ip => $gateways) {
        foreach ($gateways as $gateway => $data) {
            $sum_holdtime = $data['sum_holdtime'];
            $holdtime = $data['sum_holdtime'];
            $sum_fee = $data['sum_fee'];

            $numeroFormateado = number_format(abs($sum_fee), 0, ',', '.');

            $horas = floor($sum_holdtime / 3600); // Calcula las horas completas
            $sum_holdtime %= 3600; // Obtiene los segundos restantes
            $minutos = floor($sum_holdtime / 60); // Calcula los minutos completos
            $sum_holdtime %= 60; // Obtiene los segundos restantes

            $formathor = $horas . ":" . $minutos . ":" . $sum_holdtime;

            $linea3 = "$ip;$gateway;$holdtime;$sum_fee;\n";
            $archivo_txt4 .= $linea3;
        }
    }




    $fileNameDay = 'ConsumoDia_' . date('Ymd') . '.txt';
    $zipFileNameDay = 'ConsumoDia.zip';

    // Crear un archivo de texto y escribir los datos comprimidos
    if (file_put_contents($fileNameDay, $archivo_txt4) === false) {
        ob_end_clean();
        error_log("Error: No se pudo escribir el archivo TXT de consumo diario: " . $fileNameDay);
        echo "Error: No se pudo generar el archivo de consumo diario. Contacte a soporte.";
        exit;
    }

    // Crear un archivo ZIP y agregar el archivo de texto
    $zipDay = new ZipArchive();
    if ($zipDay->open($zipFileNameDay, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        ob_end_clean();
        error_log("Error: No se pudo abrir/crear el archivo ZIP de consumo diario: " . $zipFileNameDay);
        echo "Error: No se pudo crear el archivo ZIP de consumo diario. Contacte a soporte (open).";
        unlink($fileNameDay);
        exit;
    }

    if (!$zipDay->addFile($fileNameDay, $fileNameDay)) {
        ob_end_clean();
        error_log("Error: No se pudo agregar el archivo TXT de consumo diario al ZIP: " . $fileNameDay);
        echo "Error: No se pudo crear el archivo ZIP de consumo diario. Contacte a soporte (addFile).";
        $zipDay->close();
        unlink($fileNameDay);
        unlink($zipFileNameDay);
        exit;
    }
    
    if (!$zipDay->close()) {
        ob_end_clean();
        error_log("Error: No se pudo cerrar/finalizar el archivo ZIP de consumo diario: " . $zipFileNameDay);
        echo "Error: No se pudo crear el archivo ZIP de consumo diario. Contacte a soporte (close).";
        unlink($fileNameDay);
        unlink($zipFileNameDay);
        exit;
    }
    
    ob_end_clean();

    // Descargar el archivo ZIP
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileNameDay . '"');
    header('Content-Length: ' . filesize($zipFileNameDay));
    readfile($zipFileNameDay);

    // Eliminar los archivos temporales
    unlink($fileNameDay);
    unlink($zipFileNameDay);
    exit;
}