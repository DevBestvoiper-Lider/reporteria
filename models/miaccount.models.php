<?php

    require_once 'conexion.php';

    class ModeloMiaccount{

        static public function MdlMostrarFeesRate($tabla, $item, $valor){

            if ($item != null) {

                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
                $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                $stmt -> execute();

                return $stmt -> fetchAll();
            }else {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
                $stmt -> execute();

                return $stmt -> fetchAll();
            }

            $stmt -> close();
            
            $stmt = null;
        }

        static public function MdlMostrarAreasName($tabla, $item2, $valor){

            if ($item2 != null) {

                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item2 = :$item2");
                $stmt -> bindParam(":".$item2 , $valor, PDO::PARAM_STR);
                $stmt -> execute();

                return $stmt -> fetchAll();
            }else {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
                $stmt -> execute();

                return $stmt -> fetchAll();
            }

            $stmt -> close();
            
            $stmt = null;
        }

        static public function MdlMostrarRateGroup($tabla, $item, $valor){

            if ($item != null) {

                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item LIMIT 1 ");
                $stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);
                $stmt -> execute();

                return $stmt -> fetchAll();
            }else {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
                $stmt -> execute();

                return $stmt -> fetchAll();
            }

            $stmt -> close();
            
            $stmt = null;
        }

    }