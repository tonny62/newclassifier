<?php
    class schemeModel extends Model{

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

        public static function addToTagsTable(){
            $db = Model::getDB();
            $q = "SELECT categoryname,schemename FROM tags_temp";
            $stmt = $db->query($q);
            // create category_array
            $category_array = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                if(!in_array($row['categoryname'],$category_array)){
                    array_push($category_array,$row['categoryname']);
                }
                if(!isset($schemename)){
                    $schemename = $row['schemename'];
                }
            }
            // insert categories into category table
            foreach ($category_array as $key => $value) {
                $q = "INSERT INTO category VALUES ('".$value."','".$schemename."')";
                $stmt = $db->query($q);
            }
            // insert into tag table from tags_temp
            $q = "INSERT INTO tags(categoryname,code,tagname,tagdesc,schemename)
                    SELECT categoryname, code, tagname, tagdesc,schemename
                    FROM tags_temp";
            $stmt = $db->query($q);

            $q = "INSERT INTO scheme VALUES('".$schemename."', CURRENT_TIMESTAMP)";
            $stmt = $db->query($q);
        }

        public static function getSchemeInfo($scheme){
            $db = Model::getDB();
            $q = "SELECT * FROM scheme";
            $stmt = $db->query($q);
            return $stmt;
        }

    }


 ?>
