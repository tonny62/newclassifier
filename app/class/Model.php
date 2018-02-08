<?php
    include '../app/conf/dbconf.php';

    class Model{
        public static function getDB(){
            static $db = null;
            if($db == null){
                try{
                    $dsn = 'mysql:host='. dbConnection::DBHOST.';dbname='.dbConnection::DBNAME.';charset=utf8';
                    $db = new PDO($dsn, dbConnection::DBUSER,dbConnection::DBPASSWORD);
                    // Throw an Exception when an error occurs
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e){
                    $PDO_error = $e;
                    echo 'Connection failed: ' . $e->getMessage();
                }
            }
            return $db;
        }

        public static function dbStatus(){
            if (!Model::getDB()) {
                return "error";
            }else {
                return "ok";
            }
        }
    }

 ?>
