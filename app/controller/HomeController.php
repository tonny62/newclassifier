<?php
    require '../app/model/HomeModel.php';

    class HomeController extends Controller{
        public static function get($param){
            $title = "Home";
            $mystring = "I come from GET";

            // var_dump(HomeModel::getAll());

            // call view
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
