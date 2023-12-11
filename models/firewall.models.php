<?php

    require_once 'conexion.php';

    class ModeloFirewall{

        static public function MdlMostrarIps($tabla, $item, $valor){

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

        static public function mdlEditarUsuario($tabla, $datos){

            $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre= :nombre, password = :password, perfil = :perfil, foto = :foto WHERE usuario = :usuario");
    
            $stmt ->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $stmt ->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
            $stmt ->bindParam(":password", $datos["password"], PDO::PARAM_STR);
            $stmt ->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
            $stmt ->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
    
            if($stmt->execute()){
    
                return "ok";
    
            }else{
    
                return "error";
    
            }
    
            $stmt -> close();
    
            $stmt = null;
    
        }

        /*********************************
        * ACTUALIZAR USUARIO
        ********************************/
    
        static public function mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2){

            $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1= :$item1 WHERE $item2 = :$item2");
    
            $stmt ->bindParam(":".$item1, $valor1, PDO::PARAM_STR);
            $stmt ->bindParam(":".$item2, $valor2, PDO::PARAM_STR);
    
            if($stmt->execute()){
    
                return "ok";
    
            }else{
    
                return "error";
    
            }
    
            $stmt -> close();
    
            $stmt = null;
    
        }

        static public function mdlIngresarIP($tabla, $datos){

            $date = date('Y-m-d');
            $type = 'Anti Hack';
            $name = '';
            $time = '0000-00-00 00:00:00';
        
            $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(ip_address, date, type, name, time) VALUES (:ip, :date, :type, :name, :time)");
        
            $stmt ->bindParam(":ip", $datos["ip"], PDO::PARAM_STR);
            $stmt ->bindParam(":date", $date, PDO::PARAM_STR);
            $stmt ->bindParam(":type", $type, PDO::PARAM_STR);
            $stmt ->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt ->bindParam(":time", $time, PDO::PARAM_STR);
        
            if ($stmt->execute()){
                return "ok";
        
            }else {
                return "error";
            }

            $stmt -> close(); // Mover esta línea aquí
            $stmt = null;
        }
        

        /*********************************
        * BORRAR IP
        ********************************/

        static public function mdlBorrarIP($tabla, $datos){

            $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

            $stmt -> bindParam(":id", $datos , PDO::PARAM_INT);

            if($stmt -> execute()){
                return "ok";
            }else {
                return "error";
            }

            $stmt -> close();
            $stmt = null;

        }
    
    } 