<?php

$accountname = $_SESSION['customeraccountname']

$item = 'customeraccount';

$valor = $accountname;


ob_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'exportar') {


        $tabla = "e_cdr_2023%";

        $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
        $stmt -> execute();

        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $report = array();

        $caller = $_POST["caller"];
        $callee = $_POST["callee"];

        if (isset($_POST['fechainicio'])){
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
                    $sql = "SELECT callere164, calleee164, callergatewayid, starttime, holdtime, fee, endreason FROM $table WHERE";

                    if (!empty($caller)){
                        $sql .= " callere164 = $caller AND";
                    }

                    if (!empty($callee)){
                        $sql .= " calleee164 = $callee AND";
                    }

                    $sql .= " $item = :$item";

                    $sql = rtrim($sql, " AND");

                    $stmt = Conexion::conectar()->prepare($sql);
                    $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                    $stmt -> execute();

                    $report[$table] = $stmt->fetchAll();

                    }
                }

                $table2 = "e_end_reason";

                $stmt = Conexion::conectar()->prepare("SELECT * FROM $table2");
                $stmt -> execute();

                 $endr = $stmt->fetchAll();

                foreach ($report as $fecha => $registros) {
                foreach ($registros as $registro) {

                    foreach($endr as $subarray2){
                        if(@$subarray2['code'] == $registro['endreason']){
                            @$endreason = $subarray2['description'];
                        }
                    }

                    $dataArray[] = array(
                        'callere164' => $registro['callere164'],
                        'calleee164' => $registro['calleee164'],
                        'callergatewayid' => $registro['callergatewayid'],
                        'endreason' => $endreason,
                        'starttime' => $registro['starttime'],
                        'holdtime' => $registro['holdtime'],
                        'fee' => $registro['fee'],
                    );
                }
            }

                // Encabezado para el archivo de texto
        $archivo_txt = "NumeroMostrado,NumeroMarcado,ServidorOrigen,HoraInicio,Duracion,motivodefinalizacion,CostoLlamada,\n";

        // Convertir y agregar datos al archivo de texto
        foreach ($dataArray as $dato) {

            $timestamp = $dato['starttime'] / 1000; // Convertir a segundos
            $colombiaOffset = -7 * 3600; // Offset en segundos (5 horas atrás)
            
            $timestampColombia = $timestamp + $colombiaOffset;
            
            $starttime = date("Y-m-d H:i:s", $timestampColombia);


            $callerE164 = $dato['callere164'];
            $endreason2 = $dato['endreason'];
            $calleeE164 = $dato['calleee164'];
            $server = $dato['callergatewayid']; // Puedes cambiar esto según tus datos
            $holdtime = $dato['holdtime'];
            $fee = $dato['fee'];
            
            $linea = "$callerE164,$calleeE164,$server,$starttime,$holdtime,$endreason2,$fee,\n";
            $archivo_txt .= $linea;
        }

        $fileName = 'ReportCdr_' . date('Ymd') . '.txt';

        // Crear un archivo de texto y escribir los datos comprimidos
        file_put_contents($fileName, $archivo_txt);

        // Crear un archivo ZIP y agregar el archivo de texto
        $zip = new ZipArchive();
        if ($zip->open('CdrReport.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $zip->setArchiveComment(''); // Esto elimina cualquier comentario del archivo ZIP
            $zip->addFile($fileName, $fileName);
            $zip->close();
        }

        ob_clean();

        // Descargar el archivo ZIP
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="CdrReport.zip"');
        readfile('CdrReport.zip');

        // Eliminar los archivos temporales
        unlink($fileName);
        unlink('CdrReport.zip');
            }
        }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] == 'exportarPagos') {

            $tabla = "e_payhistory_".date('Y')."%";

            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
            $stmt -> execute();
    
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
            $report = array();
    
            if (isset($_POST['fechainicio'])){
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
                        $sql = "SELECT customeraccount, paymoney, memo, loginname, customermoney, time FROM $table WHERE";

                        $sql .= " $item = :$item";

                        $sql = rtrim($sql, " AND");

                        $stmt = Conexion::conectar()->prepare($sql);
                        $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                        $stmt -> execute();

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
            $archivo_txt2 = "FechaPago,Cuenta,Monto,MontoDespues,Usuario,Observacion,\n";
    
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
                
                $linea = "$starttime,$account,$PagoFormateado,$numeroFormateado,$User,$Observa,\n";
                $archivo_txt2 .= $linea;
             }
    
            $fileName2 = 'HistoricoPagos_' . date('Ymd') . '.txt';
    
            // Crear un archivo de texto y escribir los datos comprimidos
            file_put_contents($fileName2, $archivo_txt2);
    
            // Crear un archivo ZIP y agregar el archivo de texto
            $zip3 = new ZipArchive();
            if ($zip3->open('PagosHistory.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                $zip3->setArchiveComment(''); // Esto elimina cualquier comentario del archivo ZIP
                $zip3->addFile($fileName2, $fileName2);
                $zip3->close();
            }


            ob_clean();

            // Descargar el archivo ZIP
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="PagosHistory.zip"');
            readfile('PagosHistory.zip');
    
            // // Eliminar los archivos temporales
            unlink($fileName2);
            unlink('PagosHistory.zip');
        }
    }
}
if (isset($_POST['exportarMonth'])) {
        $tabla = "e_reportgatewaymappingfee_". date('Y') ."%";

        $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
        $stmt -> execute();

        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $report = array();

        if (isset($_POST['fechainicio'])){
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
                    $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                    $stmt -> execute();

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
                $archivo_txt3 = "ip,ServidorOrigen,DuracionLlamadas,Consumo,\n";

        // Convertir y agregar datos al archivo de texto
        $datosAgrupados = [];

        if(isset($dataArray)){
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


        $fileName2 = 'Consumo_' . date('Ymd') . '.txt';

        // Crear un archivo de texto y escribir los datos comprimidos
        file_put_contents($fileName2, $archivo_txt3);

        // Crear un archivo ZIP y agregar el archivo de texto
        $zip3 = new ZipArchive();
        if ($zip3->open('Consumo.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $zip3->setArchiveComment(''); // Esto elimina cualquier comentario del archivo ZIP
            $zip3->addFile($fileName2, $fileName2);
            $zip3->close();
        }

        ob_clean();

        // Descargar el archivo ZIP
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="Consumo.zip"');
        readfile('Consumo.zip');

        // Eliminar los archivos temporales
        unlink($fileName2);
        unlink('Consumo.zip');
}

if (isset($_POST['exportarDay'])) {
    $tabla = "e_reportgatewaymappingfee_". date('Y') ."%";

    $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE '$tabla'");
    $stmt -> execute();

    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $report = array();

    if (isset($_POST['fechabusc'])){
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
                $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                $stmt -> execute();

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
            $archivo_txt4 = "ip,ServidorOrigen,DuracionLlamadas,Consumo,\n";

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

            if(isset($dataArray)){
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

                    $linea3 = "$ip,$gateway,$holdtime,$sum_fee,\n";
                    $archivo_txt4 .= $linea3;

                }
            }




    $fileName2 = 'Consumo_' . date('Ymd') . '.txt';

    // Crear un archivo de texto y escribir los datos comprimidos
    file_put_contents($fileName2, $archivo_txt4);

    // Crear un archivo ZIP y agregar el archivo de texto
    $zip3 = new ZipArchive();
    if ($zip3->open('Consumo.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        $zip3->setArchiveComment(''); // Esto elimina cualquier comentario del archivo ZIP
        $zip3->addFile($fileName2, $fileName2);
        $zip3->close();
    }

    ob_clean();

    // Descargar el archivo ZIP
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="Consumo.zip"');
    readfile('Consumo.zip');

    // Eliminar los archivos temporales
    unlink($fileName2);
    unlink('Consumo.zip');
}

?>