<?php

    class databaseModel extends Model{
        public static function insertToTemp($file, $dbname){
            $db = Model::getDB();
            $q = "DROP TABLE IF EXISTS `".$dbname."_temp`;";
            $stmt = $db->query($q);

            $q = "CREATE TABLE `".$dbname."_temp` (
                `company` varchar(300) NOT NULL,
                `position` varchar(300) NOT NULL,
                `url` varchar(300) NOT NULL,
                `description` text NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $stmt = $db->query($q);
            $q = "LOAD DATA LOCAL INFILE '".$file."'
                        IGNORE
                        INTO TABLE ".$dbname."_temp
                        CHARACTER SET 'utf8'
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\r\n'";
            $stmt = $db->query($q);
        }

        public static function getHead($dbname){
            $db = Model::getDB();
            $q = "SELECT * FROM ".$dbname."_temp LIMIT 5";
            $stmt = $db->query($q);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function moveToJobads($dbname){
            $db = Model::getDB();

            $q = "SELECT count(*) AS count FROM ".$dbname."_temp;";
            $stmt = $db->query($q);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $result['count'];

            $q = "INSERT INTO masterdb VALUES(NULL, '".$dbname."', ' ".$count."', CURRENT_TIMESTAMP)";
            $stmt = $db->query($q);
            $id = $db->lastInsertId();

            $q = "SELECT * FROM masterdb WHERE idmasterdb = ".$id.";";
            $stmt = $db->query($q);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $q = "INSERT INTO jobads(idjob, company, position, url, description, idmasterdb) SELECT NULL, company, position, url, description, ".$id." FROM ".$dbname."_temp;";
            $stmt = $db->query($q);

            $q = "DROP TABLE `".$dbname."_temp`";
            $stmt = $db->query($q);

            return $result;

        }
    }
 ?>
