<?php

    class MyErrorController extends Controller{

        public static function response(){
            $title = "ERROR404 : Not Found";
            // call view
            require_once('../app/template/header.phtml');
            require_once('../app/view/error/body.phtml');
            require_once('../app/template/footer.phtml');

        }

    }
 ?>
