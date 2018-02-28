<?php

    class classifierModel extends Model{

        public static function getSchemeInfo($name){
            $db = Model::getDB();
            $q = "SELECT * FROM scheme WHERE namescheme = '".$name."';";
            $stmt = $db->query($q);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public static function getOneJob($schemearry){
            $db = Model::getDB();
            $q = "SELECT * FROM `samples`
                WHERE idscheme = ".$schemearry['idscheme']." AND status = 'none'
                ORDER BY RAND() LIMIT 1";
            $stmt = $db->query($q);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (count($result) == 0) {
                return 0;
            }
            $idsample = $result['idsample'];

            $q = "SELECT idjob, company, position, url FROM `jobads`
            WHERE `idjob` = '".$result['idjob']."';";
            $stmt = $db->query($q);
            $result = $stmt->fetch(PDO::FETCH_ASSOC); // hold jobads fileds

            $q = "UPDATE `samples` SET `status` = 'locked' WHERE `idsample` = '".$idsample."'";
            $stmt = $db->query($q);

            $result['idsample'] = $idsample;
            return $result;
        }

        public static function getCategory($schemearry){
            $db = Model::getDB();
            $stmt = $db->query("SELECT namecategory FROM category WHERE idscheme = '".$schemearry['idscheme']."'" );
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public static function getTagsFromCategory($categoryname,$schemearry){
            $db = Model::getDB();
            $stmt = $db->query("SELECT * FROM tags WHERE namecategory = '".$categoryname."' AND idscheme = ".$schemearry['idscheme'].";");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public static function getTagFromID($tagid){
            $db = Model::getDB();
            $q = "SELECT * FROM `tags` WHERE idtag = '".$tagid."'";
            $stmt = $db->query($q);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        public static function unlockJobads($jobadsarry){
            $db = Model::getDB();
            $q = "UPDATE `samples` SET `status` = 'none' WHERE `idsample` = '".$jobadsarry['idsample']."'";
            $stmt = $db->query($q);
        }

        public static function decodeCart($cart){
            $db = Model::getDB();
            $outcart = array();
            foreach ($cart as $key => $value) {
                $q = "SELECT * FROM tags WHERE idtag='".$value."'";
                $stmt = $db->query($q);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $outcart[$key] = $result;
            }
            return $outcart;
        }

        public static function insertSampleIsTaggedByArray($jobads,$tagarray){
            $db = Model::getDB();
            foreach ($tagarray as $key => $value) {
                $q = "SELECT idcategory FROM category WHERE namecategory = '".$value['namecategory']."' AND idscheme = '".$value['idscheme']."'; ";
                $stmt = $db->query($q);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $idcategory = $result['idcategory'];

                $q = "INSERT INTO `sample_has_tag` (`idsample`, `idtag`, `idcategory`, `idsamplehastag`, `timestamp`)
                VALUES ('".$jobads['idsample']."', '".$value['idtag']."', '".$idcategory."', NULL, CURRENT_TIMESTAMP)";
                $stmt = $db->query($q);
            }
            $q = "UPDATE `samples` SET `status` = 'tagged' WHERE `samples`.`idsample` = ".$jobads['idsample']." ";
            $stmt = $db->query($q);
        }

        public static function insertSampleIsTaggedByCategory($jobads,$category,$scheme){
            $db = Model::getDB();
            $q = "SELECT idcategory FROM category WHERE namecategory = '".$category."' AND idscheme = '".$scheme['idscheme']."'; ";
            $stmt = $db->query($q);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $idcategory = $result['idcategory'];

            $q = "INSERT INTO `sample_has_tag` (`idsample`, `idcategory`, `idsamplehastag`, `timestamp`)
            VALUES ('".$jobads['idsample']."', '".$idcategory."', NULL, CURRENT_TIMESTAMP)";
            $stmt = $db->query($q);

            $q = "UPDATE `samples` SET `status` = 'tagged' WHERE `samples`.`idsample` = ".$jobads['idsample']." ";
            $stmt = $db->query($q);
        }

        public static function tryRelease($scheme){
            $db = Model::getDB();
            $q = "UPDATE `samples` SET `status` = 'none' WHERE `samples`.`status` = 'locked' AND idscheme = '".$scheme['idscheme']."'";
            $stmt = $db->query($q);
            $count = $stmt->rowCount();
            if($count>0){
                return 1;
            }else{
                return 0;
            }
        }

        // public static function getschemename($name){
        //     $db = Model::getDB();
        //     $stmt = $db->query('SELECT * FROM scheme WHERE namescheme = "'.$name.'"');
        //     $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //     if (count($result) > 0) {
        //         return 1;
        //     }else{
        //         return 0;
        //     }
        // }
        //
        // public static function getTagID($categoryname, $code, $schemename){
        //     $db = Model::getDB();
        //     $q = "SELECT * FROM tags WHERE categoryname = '".$categoryname."' AND code = '".$code."' AND schemename ='".$schemename."' ";
        //     $stmt = $db->query($q);
        //     $result = $stmt->fetch(PDO::FETCH_ASSOC);
        //     if(count($result)>0){
        //         return $result['tagid'];
        //     }else{
        //         throw new Exception('Could not find tagid');
        //     }
        // }
        //
        // public static function insertJobIsTagged($jobid, $code, $categoryname, $schemename){
        //     $db = Model::getDB();
        //     if($code == ''){
        //         $tagid = 'null';
        //     }else{
        //         $tagid = classifierModel::getTagID($categoryname, $code, $schemename);
        //     }
        //     $q = "INSERT INTO jobads_is_tagged VALUES ('".$jobid."', ".$tagid.", '".$categoryname."', '".$schemename."', CURRENT_TIMESTAMP)";
        //     $stmt = $db->query($q);
        //     if(!$stmt){
        //         throw new Exception('Insert Fail');
        //     }
        // }
    }
 ?>
