<?php

class ModeloConsumption {

    static private function obtenerColumnasCDR($tieneGateway, $tipoTabla){
        if ($tieneGateway) {
            return [
                'select' => "callergatewayid AS gatewayid, callerip AS ip, SUM(feetime) AS feetime, SUM(fee) AS fee",
                'groupBy' => "GROUP BY callergatewayid, callerip"
            ];
        }

        if ($tipoTabla === 'phone') {
            return [
                'select' => "calleee164 AS e164, SUM(feetime) AS feetime, SUM(fee) AS fee",
                'groupBy' => "GROUP BY calleee164"
            ];
        }

        if ($tipoTabla === 'customerclearinglocation') {
            return [
                'select' => "calleeareacode AS areacode, SUM(feetime) AS feetime, SUM(fee) AS fee",
                'groupBy' => "GROUP BY calleeareacode"
            ];
        }

        return [
            'select' => "SUM(feetime) AS feetime, SUM(fee) AS fee",
            'groupBy' => ""
        ];
    }

    static private function consultarTablaCDR($tabla, $item, $valor, $tieneGateway, $tipoTabla){
        $columnasCDR = self::obtenerColumnasCDR($tieneGateway, $tipoTabla);

        $sql = "SELECT {$columnasCDR['select']} FROM `$tabla` WHERE $item = :valor";
        if (!empty($columnasCDR['groupBy'])) {
            $sql .= " {$columnasCDR['groupBy']}";
        }

        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    static public function MdlMostrarLlamadasCdrXDia($tabla, $item, $valor, $tieneGateway = true, $tipoTabla = 'gatewaymapping'){

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE :tabla");
            $stmt->bindParam(':tabla', $tabla, PDO::PARAM_STR);
            $stmt->execute();

            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $report = array();

            foreach ($tables as $table) {
                $report[$table] = self::consultarTablaCDR($table, $item, $valor, $tieneGateway, $tipoTabla);
            }

            return $report;
        }

        $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
        $stmt->execute();

        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $table_date = substr($table, -8);

            if ($table_date >= '20230301' && $table_date <= '20230831') {
                $columnasCDR = self::obtenerColumnasCDR($tieneGateway, $tipoTabla);
                $sql = "SELECT {$columnasCDR['select']} FROM $table";
                if (!empty($columnasCDR['groupBy'])) {
                    $sql .= " {$columnasCDR['groupBy']}";
                }

                $stmt = Conexion::conectar()->prepare($sql);
                $stmt->execute();

                return $stmt->fetchAll();
            }
        }

        $stmt = null;
    }

    static public function MdlMostrarConsumoCdrMes($tabla, $item, $valor, $tieneGateway = true, $tipoTabla = 'gatewaymapping'){

        if ($item != null) {

            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE :tabla");
            $stmt->bindParam(':tabla', $tabla, PDO::PARAM_STR);
            $stmt->execute();

            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $report = array();

            if (isset($_POST['FechaInicio']) && !empty($_POST['FechaInicio'])){
                $fechaini = str_replace("-", "", $_POST['FechaInicio']);
                $fechafin = str_replace("-", "", $_POST['FechaFin']);
            } else {
                $fechaini = date('Ym01');
                $fechafin = date('Ymd');
            }

            foreach ($tables as $table) {
                $table_date = substr($table, -8);

                if ($table_date >= $fechaini && $table_date <= $fechafin) {
                    $report[$table] = self::consultarTablaCDR($table, $item, $valor, $tieneGateway, $tipoTabla);
                }
            }

            return $report;
        }

        $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE $tabla");
        $stmt->execute();

        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $table_date = substr($table, -8);

            if ($table_date >= '20230301' && $table_date <= '20230831') {
                $columnasCDR = self::obtenerColumnasCDR($tieneGateway, $tipoTabla);
                $sql = "SELECT {$columnasCDR['select']} FROM $table";
                if (!empty($columnasCDR['groupBy'])) {
                    $sql .= " {$columnasCDR['groupBy']}";
                }

                $stmt = Conexion::conectar()->prepare($sql);
                $stmt->execute();

                return $stmt->fetchAll();
            }
        }

        $stmt = null;
    }

}
