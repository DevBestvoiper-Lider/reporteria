<?php

class Conexion
{

    static public function conectar()
    {
        $environment = getenv('APP_ENV');
        
        if ($environment === 'production') {
            // Producción (servidor real)
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $dbname = getenv('DB_NAME') ?: 'vos3000';
            $username = getenv('DB_USER') ?: 'root';
            $password = getenv('DB_PASS') ?: 'd57hgtfresh3rdxy78D';
        } else {
            // Desarrollo local
            $host = getenv('DB_HOST_DEV') ?: 'colombus.bestvoiper.com';
            $dbname = getenv('DB_NAME_DEV') ?: 'vos3000';
            $username = getenv('DB_USER_DEV') ?: '';
            $password = getenv('DB_PASS_DEV') ?: 'Luisda0806*++';
        }

        $link = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8",
            $username,
            $password,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );

        $link->exec("set names utf8");

        return $link;
    }
}
