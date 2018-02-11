<?php
    require '../app/model/homeModel.php';
    session_start();

    class homeController extends Controller{
        public static function get($param){
            $title = "Home";
            // call view
            $rows = homeModel::getScheme();
            require_once('../app/template/header.phtml');
            require_once('../app/view/home/body.phtml');
            require_once('../app/template/footer.phtml');
        }

        public static function post($param){
            $title = "Home";
            // call view
            require_once('../app/template/header.phtml');
            require_once('../app/view/home/body.phtml');
            require_once('../app/template/footer.phtml');
        }


    }
 ?>
