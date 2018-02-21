<?php
    class schemeModel extends Model{

        public static function insertToTemp($file, $schemename){
            $db = Model::getDB();
            $q = "DROP TABLE IF EXISTS `".$schemename."_temp`;";
            $stmt = $db->query($q);

            $q = "CREATE TABLE `".$schemename."_temp` (
                          `namecategory` varchar(100) NOT NULL,
                          `code` varchar(30) NOT NULL,
                          `nametag` varchar(100) NOT NULL,
                          `description` text NOT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $stmt = $db->query($q);

            $q = "LOAD DATA LOCAL INFILE '".$file."'
                        IGNORE
                        INTO TABLE ".$schemename."_temp
                        CHARACTER SET 'utf8'
                        FIELDS TERMINATED BY '\t'
                        LINES TERMINATED BY '\r\n'";
            $stmt = $db->query($q);

            $q = "SELECT count(*) AS count FROM ".$schemename."_temp;";
            $stmt = $db->query($q);
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            return $count;
        }

        public static function getHead($schemename){
            $db = Model::getDB();
            $q = "SELECT namecategory, code, nametag, description FROM ".$schemename."_temp LIMIT 5";
            $stmt = $db->query($q);
            return $stmt;
        }

        public static function getCount($schemename){
            $db = Model::getDB();
            $q = "SELECT count(*) AS count FROM ".$schemename."_temp;";
            $stmt = $db->query($q);
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            return $count;
        }

        public static function getMasterDB(){
            $db = Model::getDB();
            $q = "SELECT namemasterdb, records, idmasterdb FROM masterdb";
            $stmt = $db->query($q);
            return $stmt->fetchAll(PDO::FETCH_NUM);
        }

        public static function insertIntoScheme($schemename, $masterdbid, $percent){
            $db = Model::getDB();
            $q = "INSERT INTO scheme VALUES(NULL, '".$schemename."', '".$masterdbid."', '".$percent."', CURRENT_TIMESTAMP)";
            $stmt = $db->query($q);
            return 1;
        }

        public static function getMasterDBName($idmasterdb){
            $db = Model::getDB();
            $q = "SELECT namemasterdb FROM masterdb WHERE idmasterdb=".$idmasterdb.";";
            $stmt = $db->query($q);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['namemasterdb'];
        }

        public static function insertIntoCategory($schemename){
            $db = Model::getDB();
            $q = "INSERT INTO category
                    SELECT namecategory FROM tags WHERE tags.idscheme IN
	                   (SELECT idscheme FROM scheme WHERE namescheme = '".$schemename."')
                       GROUP BY namecategory";
            $stmt = $db->query($q);
        }

        public static function schemeInsertProcedure($schemename){
            $db = Model::getDB();
            $schemeid = schemeModel::getSchemeId($schemename);

            // insert into tag table from tags_temp
            $q = "INSERT INTO tags(idtag, namecategory, code,nametag, description, idscheme)
                    SELECT '', namecategory, code, nametag, description , ".$schemeid."
                    FROM ".$schemename."_temp";
            $stmt = $db->query($q);

            $q = "SELECT namecategory FROM ".$schemename."_temp GROUP BY namecategory";
            $stmt = $db->query($q);
            $category_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // insert categories into category table
            foreach ($category_array as $key => $value) {
                $q = "INSERT INTO category VALUES ( NULL ,'".$value['namecategory']."', '".$schemeid."')";
                $stmt = $db->query($q);
            }

            // drop temp table
            $q = "DROP TABLE IF EXISTS `".$schemename."_temp`;";
            $stmt = $db->query($q);


        }

        public static function getSchemeId($schemename){
            $db = Model::getDB();
            $q = "SELECT idscheme FROM `scheme` WHERE namescheme = '".$schemename."';";
            $stmt = $db->query($q);
            $schemeid = $stmt->fetch(PDO::FETCH_NUM)[0];
            return $schemeid;
        }

        public static function doSampling($schemename, $masterdbid, $percentage){
            $db = Model::getDB();
            $schemeid = schemeModel::getSchemeId($schemename);

            $q = "SELECT COUNT(*) as count FROM jobads WHERE idmasterdb = $masterdbid";
            $stmt = $db->query($q);
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            $num_sampling_rows = (int) (ceil((($percentage / 100) * $count)));

            $q = "INSERT INTO samples SELECT '','".$masterdbid."', idjob, '".$schemeid."', 'none'
                    FROM jobads WHERE idmasterdb = ".$masterdbid." ORDER BY RAND() LIMIT ".$num_sampling_rows.";";
            $stmt = $db->query($q);

            return $num_sampling_rows;
        }







        public static function loadData($file,$schemename){
            $db = Model::getDB();

            $query = "TRUNCATE tags_temp";
            $stmt = $db->query($query);

            $query = "LOAD DATA LOCAL INFILE '".$file."'
            IGNORE
            INTO TABLE tags_temp
            CHARACTER SET 'utf8'
            FIELDS TERMINATED BY '\t'
            LINES TERMINATED BY '\r\n'
            SET schemename = '".$schemename."',
            tagid = '';";
            $stmt = $db->query($query);

            return $stmt;
        }

        public static function selectFromTags_Temp(){
            $db = Model::getDB();
            $q = "SELECT categoryname, code, tagname, tagdesc, schemename FROM tags_temp LIMIT 5";
            $stmt = $db->query($q);
            return $stmt;
        }



        public static function getSchemeInfo($scheme){
            $db = Model::getDB();
            $q = "SELECT * FROM scheme";
            $stmt = $db->query($q);
            return $stmt;
        }

    }


 ?>
