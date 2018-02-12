<?php

    class classifierModel extends Model{
        public static function getschemename($name){
            $db = Model::getDB();
            $stmt = $db->query('SELECT * FROM scheme WHERE schemename = "'.$name.'"');
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                return 1;
            }else{
                return 0;
            }
        }

        public static function getOneJob($schemename){
            $db = Model::getDB();
            $stmt = $db->query("SELECT * FROM jobads WHERE jobads.jobid NOT IN
	                               (SELECT jobid FROM jobads_is_tagged WHERE schemename = '".$schemename."')
                                ORDER BY RAND()
	                            LIMIT 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        public static function getCategory($schemename){
            $db = Model::getDB();
            $stmt = $db->query("SELECT categoryname FROM category WHERE schemename = '".$schemename."'" );
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public static function getTagsFromCategory($categoryname){
            $db = Model::getDB();
            $stmt = $db->query("SELECT * FROM tags WHERE categoryname = '".$categoryname."'" );
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public static function getTagID($categoryname, $code, $schemename){
            $db = Model::getDB();
            $q = "SELECT * FROM tags WHERE categoryname = '".$categoryname."' AND code = '".$code."' AND schemename ='".$schemename."' ";
            $stmt = $db->query($q);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if(count($result)>0){
                return $result['tagid'];
            }else{
                throw new Exception('Could not find tagid');
            }
        }

        public static function insertJobIsTagged($jobid, $code, $categoryname, $schemename){
            $db = Model::getDB();
            if($code == ''){
                $tagid = 'null';
            }else{
                $tagid = classifierModel::getTagID($categoryname, $code, $schemename);
            }
            $q = "INSERT INTO jobads_is_tagged VALUES ('".$jobid."', ".$tagid.", '".$categoryname."', '".$schemename."', CURRENT_TIMESTAMP)";
            $stmt = $db->query($q);
            if(!$stmt){
                throw new Exception('Insert Fail');
            }
        }
    }
 ?>
