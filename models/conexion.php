<?php

class Conexion
{

    static public function conectar()
    {
        // Detectar si estamos en Docker
        $isDocker = file_exists('/.dockerenv') || getenv('DOCKER_ENV') === 'true';
        $environment = getenv('APP_ENV');

        if ($isDocker) {
            // Configuración para Docker
            $host = getenv('DB_HOST') ?: 'db';
            $dbname = getenv('DB_NAME') ?: 'reporteria_db';
            $username = getenv('DB_USER') ?: 'reporteria_user';
            $password = getenv('DB_PASS') ?: 'reporteria_pass';
        } elseif ($environment === 'production') {
            // Producción (servidor real)
            $host = getenv('DB_HOST') ?: 'localhost';
            $dbname = getenv('DB_NAME') ?: 'vos3000';
            $username = getenv('DB_USER') ?: 'root';
            $password = getenv('DB_PASS') ?: 'd57hgtfresh3rdxy78D';
        } else {
            // Desarrollo local
            $host = getenv('DB_HOST') ?: 'sw1.bestvoiper.com';
            $dbname = getenv('DB_NAME') ?: 'vos3000';
            $username = getenv('DB_USER') ?: 'developers';
            $password = getenv('DB_PASS') ?: 'Luisda0806*++';
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
