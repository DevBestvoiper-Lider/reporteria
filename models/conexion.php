<?php

class Conexion{

    static public function conectar(){
        $link = new PDO("mysql:host=localhost;dbname=vos3000", 
                        "root", 
                        "d57hgtfresh3rdxy78D");

        $link->exec("set names utf8");

        return $link;
    }
}