<?php

class Conexion
{

    static public function conectar()
    {
        $environment = getenv('APP_ENV') ?: 'production';
        $isProduction = in_array(strtolower($environment), array('production', 'prod'), true);

        if ($isProduction) {
            // Producción (servidor real)
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $dbname = getenv('DB_NAME') ?: 'vos3000';
            $username = getenv('DB_USER') ?: 'root';
            $password = getenv('DB_PASS') ?: 'd57hgtfresh3rdxy78D';
        } else {
            // Desarrollo local
            $host = getenv('DB_HOST_DEV') ?: (getenv('DB_HOST') ?: '127.0.0.1');
            $dbname = getenv('DB_NAME_DEV') ?: (getenv('DB_NAME') ?: 'vos3000');
            $username = getenv('DB_USER_DEV') ?: (getenv('DB_USER') ?: 'root');
            $password = getenv('DB_PASS_DEV') ?: (getenv('DB_PASS') ?: '');
        }

        if ($host === '' || $dbname === '' || $username === '') {
            throw new RuntimeException('Configuracion de base de datos incompleta. Revisa APP_ENV y variables DB_* en el servidor.');
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
