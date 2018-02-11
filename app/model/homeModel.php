<?php

    class homeModel extends Model{
        public static function getAll(){
            $db = Model::getDB();
            $stmt = $db->query('SELECT * FROM jobads WHERE jobid = "1"');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function getScheme(){
            $db = Model::getDB();
            $q = "SELECT * FROM scheme"
            $stmt = $db->query($q);
            return $stmt;
        }
    }
 ?>
