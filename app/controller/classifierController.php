<?php
    require '../app/model/classifierModel.php';
    class classifierController extends Controller{
        public static function get($param){
            session_start();
            $title = "Classifier";
            if(!isset($_SESSION['jobads'])){
                $_SESSION['jobads'] = classifierModel::getOneJob($_SESSION['schemename']);
            }
            $category = classifierModel::getCategory($_SESSION['schemename']);

            if (isset($_GET['action'])) {
                if($_GET['action'] == 'set'){
                    // insert into db
                    if (isset($_GET['code'])) {
                        // tagged with code
                        classifierModel::insertJobIsTagged($_SESSION['jobads']['jobid'], $_GET['code'], $_GET['categoryname'], $_SESSION['schemename']);
                        unset($_SESSION['jobads']);
                        header("Location: /classifier");
                    }else{
                        classifierModel::insertJobIsTagged($_SESSION['jobads']['jobid'], '', $_GET['categoryname'], $_SESSION['schemename']);
                        unset($_SESSION['jobads']);
                        header("Location: /classifier");
                        // tagged with category
                    }
                }elseif($_GET['action'] == 'skip'){
                    // insert into db
                }
            }else{
                if(!isset($_GET['categoryname'])){
                    // call view
                    require_once('../app/template/header.phtml');
                    require_once('../app/view/classifier/body.phtml');
                    require_once('../app/template/footer.phtml');
                }else{
                    $tags = classifierModel::getTagsFromCategory($_GET['categoryname']);
                    require_once('../app/template/header.phtml');
                    require_once('../app/view/classifier/body2.phtml');
                    require_once('../app/template/footer.phtml');
                }
            }


        }

        public static function post($param){
            $title = "Classifier";
            session_start();
            if (classifierModel::getschemename($_POST['schemename'])) {
                // set session schemename and send to get request
                $_SESSION['schemename'] = $_POST['schemename'];
                header("Location: /classifier");
            }else{
                // no schemename go home
                header("Location: /");
            }
        }


    }
 ?>
