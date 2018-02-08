<?php
    require '../app/model/MycsvModel.php';

    class MycsvController extends Controller{
        public static function get($param){
            // call view
            $title = "MyCSV";
            $dbstatus = Model::dbStatus();
            require_once('../app/template/header.phtml');
            require_once('../app/view/mycsv/body.phtml');
            require_once('../app/template/footer.phtml');
        }

        public static function post($param){
            // call view
            $title = "MyCSV";
            $status = MycsvModel::opencsvfile($_FILES);
            $dbstatus = Model::dbStatus();

            require_once('../app/template/header.phtml');
            require_once('../app/view/mycsv/body.phtml');
            require_once('../app/template/footer.phtml');
        }

    }
 ?>
