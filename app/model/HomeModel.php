<?php

    class HomeModel extends Model{
        public static function getAll(){
            $db = Model::getDB();
            $stmt = $db->query('SELECT * FROM jobads WHERE jobid = "1"');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
 ?>
