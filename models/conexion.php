<?php

class conexion{

        static public function conectar(){

            $server = '10.10.10.142:3307';
            $username = 'root';
            $password = 'TCI3649##';
            $database = 'TCI';

            try {
                $link = new PDO("mysql:host=$server;dbname=$database;charset=utf8", $username, $password);
                return $link;
            } 
            catch (PDOException $e) {
                die('Connection Failed MYSQL: ' . $e->getMessage());
            }
        }

        static public function conectarSQL(){

            $server = '10.10.10.25';
            //$server = '10.10.10.25\\S22SQLEXPRESS';
            $username = 'sa';
            $password = 'Passw0rd';
            $database = 'saet_prod';

            try {
                $link = new PDO("sqlsrv:server=$server,1433;database=saet_prod", $username, $password);
                       /* array(
                            //PDO::ATTR_PERSISTENT => true,
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                        )*/
                //);
                return $link;
            } 
            catch (PDOException $e) {
                die('Connection Failed SQLSERVER: ' . $e->getMessage());
            }
        }
    }

?>