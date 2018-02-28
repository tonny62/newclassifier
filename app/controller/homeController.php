<?php
    require '../app/model/homeModel.php';

    class HomeController extends Controller{
        public static function get($param){            
            $title = "Home";
            $mystring = "I come from GET";

            $rows = homeModel::getScheme();
            require_once('../app/template/header.phtml');
            require_once('../app/view/home/body.phtml');
            require_once('../app/template/footer.phtml');
        }

        public static function post($param){
            $title = "Home";
            $mystring = "I come from POST";
            // call view
            require_once('../app/template/header.phtml');
            require_once('../app/view/home/body.phtml');
            require_once('../app/template/footer.phtml');
        }


    }
 ?>
