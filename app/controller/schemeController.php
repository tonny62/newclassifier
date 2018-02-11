<?php
    require '../app/model/schemeModel.php';

    class schemeController extends Controller{
        public static function get($param){
            if ($param[0] == "comfirm") {
                $title = "Success";
                // move from temp table to real table
                schemeModel::addToTagsTable();
                $stmt = schemeModel::getSchemeInfo($param[1]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                require_once('../app/template/header.phtml');
                require_once('../app/view/scheme/body_confirm.phtml');
                require_once('../app/template/footer.phtml');
            }else {
                header("Location: /");
            }
        }

        public static function post($param){
            // file upload to here
            $title = "New Scheme";
            $result = schemeModel::loadData($_FILES['schemefile']['tmp_name'],$_POST['schemename']);
            $status = (!$result) ? "failed" : "success";
            $result = schemeModel::selectFromTags_Temp();

            // call view
            require_once('../app/template/header.phtml');
            require_once('../app/view/scheme/body.phtml');
            require_once('../app/template/footer.phtml');
        }


    }
 ?>
