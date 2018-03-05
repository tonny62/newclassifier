<?php
    require '../app/model/databaseModel.php';
    // session_start();();
    class databaseController extends Controller{
        public static function get($param){
            $title = "Database";

            if (isset($param[0])) {
                if ($param[0] == 'confirm') {
                    // move temp to jobads
                    $dbname = $param[1];
                    $result = databaseModel::moveToJobads($dbname);
                    // insert meta to masterdb
                    require_once('../app/template/header.phtml');
                    require_once('../app/view/database/body3.phtml');
                    require_once('../app/template/footer.phtml');
                }
            }else{
                require_once('../app/template/header.phtml');
                require_once('../app/view/database/body.phtml');
                require_once('../app/template/footer.phtml');
            }
        }

        public static function post($param){
            $title = "Database";
            // call view
            if ($param[0] == 'create') {
                var_dump($_FILES);
                $result = databaseModel::insertToTemp($_FILES['dbfile']['tmp_name'], $_POST['dbname']);
                $rows = databaseModel::getHead($_POST['dbname']);
                require_once('../app/template/header.phtml');
                require_once('../app/view/database/body2.phtml');
                require_once('../app/template/footer.phtml');
            }
        }


    }
 ?>
