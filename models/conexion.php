<?php

class Conexion
{

    static public function conectar()
    {


        $environment = getenv('APP_ENV');

        if ($environment === 'production') {
            // If the environment is production, use the production database credentials
            $host = 'localhost';
            $dbname = 'vos3000';
            $username = 'root';
            $password = 'd57hgtfresh3rdxy78D';
        } else {
            // If the environment is not production, use the development database credentials
            $host = 'sw1.bestvoiper.com';
            $dbname = 'vos3000';
            $username = 'developers';
            $password = 'Luisda0806*++';
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
