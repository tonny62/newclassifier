<?php
    require '../app/model/schemeModel.php';

    class schemeController extends Controller{
        public static function get($param){
            if (isset($param[0])) {
                if ($param[0] == "setup") {
                    // get scheme_temp
                    $schemename = $param[1];
                    $rowcount = schemeModel::getCount($schemename);
                    // get master db
                    $masterdbs = schemeModel::getMasterDB();
                    // get sampling percentage

                    $title = "Setup Sampling";

                    require_once('../app/template/header.phtml');
                    require_once('../app/view/scheme/body_setup.phtml');
                    require_once('../app/template/footer.phtml');

                }elseif ($param[0] == "view") {
                    $title = "view schema";

                    $schemalist = schemeModel::getSchemaTable();

                    require_once('../app/template/header.phtml');
                    require_once('../app/view/scheme/body_viewscheme.phtml');
                    require_once('../app/template/footer.phtml');
                }else{
                    header("Location: /");
                }
            }else{
                header("Location: /");
            }
        }

        public static function post($param){
            if (isset($param[0])) {
                if($param[0] == "confirm"){
                    // insert a row in scheme -> tags -> category

                    // do sampling
                    $title = "Done";
                    $masterdbname = schemeModel::getMasterDBName($_POST['masterdbid']);
                    // insert scheme table
                    $result = schemeModel::insertIntoScheme($_POST['schemename'], $_POST['masterdbid'], $_POST['percent']);
                    // insert tags
                    $result = schemeModel::schemeInsertProcedure($_POST['schemename']);
                    // do sampling
                    $numsamples = schemeModel::doSampling($_POST['schemename'], $_POST['masterdbid'], $_POST['percent']);

                    require_once('../app/template/header.phtml');
                    require_once('../app/view/scheme/body_confirm.phtml');
                    require_once('../app/template/footer.phtml');
                }elseif ($param[0] == "review") {
                    // file upload to here
                    // review
                    $title = "New Scheme";
                    $result = schemeModel::insertToTemp($_FILES['schemefile']['tmp_name'],$_POST['schemename']);
                    $rows = schemeModel::getHead($_POST['schemename']);

                    // call view
                    require_once('../app/template/header.phtml');
                    require_once('../app/view/scheme/body.phtml');
                    require_once('../app/template/footer.phtml');
                }
            }else{
                header("Location: /");
            }

        }


    }
 ?>
