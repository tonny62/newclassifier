<?php
    class MycsvModel extends Model{
        public static function opencsvfile($file){
            $handle = fopen($file["myfile"]["tmp_name"],'r');
            for ($i=0; $i < 5; $i++) {
                if ($i == 0) {
                    $data = fgetcsv($handle);
                }else{
                    $data = fgetcsv($handle);
                    if (MycsvModel::insertToDB($data)) {
                        # error
                        return "Error";
                        break;
                    }
                }
            }
            return "Done";
        }

        public static function insertToDB($row){
            $db = Model::getDB();
            $stmt = $db->query("INSERT INTO `jobads_test` (`jobid`, ` company`, ` pos`, ` description`, ` url`, ` dpost`, ` qual`, ` source`)
                                VALUES ( NULL, '".$row[1]."', '".$row[2]."', '".$row[3]."', '".$row[4]."', '".$row[5]."', '".$row[6]."', '".$row[7]."')");
            if (!$stmt) {
                return 0;
            }
        }

        
    }


 ?>
