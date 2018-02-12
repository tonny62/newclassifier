<?php
    require '../app/model/summaryModel.php';
    session_start();

    class summaryController extends Controller{
        public static function get($param){
            $title = "summary";
            $summaryrows = summaryModel::getSummaryFrame($_SESSION['schemename']);
            $category = summaryModel::getCategory($_SESSION['schemename']);
            $jobsource = summaryModel::getJobsource();

            require_once('../app/template/header.phtml');
            require_once('../app/view/summary/body.phtml');
            require_once('../app/template/footer.phtml');
        }

        public static function post($param){
            $title = "summary";
            $mystring = "I come from POST";
            // call view
            require_once('../app/template/header.phtml');
            require_once('../app/view/summary/body.phtml');
            require_once('../app/template/footer.phtml');
        }


    }
 ?>
