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
            $dbname = getenv('DB_NAME') ?: '';
            $username = getenv('DB_USER') ?: '';
            $password = getenv('DB_PASS') ?: '';
        } else {
            // Desarrollo local
            $host = getenv('DB_HOST_DEV') ?: 'sw1.bestvoiper.com';
            $dbname = getenv('DB_NAME_DEV') ?: '';
            $username = getenv('DB_USER_DEV') ?: '';
            $password = getenv('DB_PASS_DEV') ?: '';
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
