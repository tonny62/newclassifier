<?php
    require '../app/model/summaryModel.php';

    class summaryController extends Controller{
        public static function get($param){
            $title = "summary";
            if (isset($param[0])) {
                if ($param[0] == 'jobads') {
                    if (isset($_GET['action'])) {
                        if ($_GET['action'] == 'dump') {
                            $path = summaryModel::dumpCsvFile($_SESSION['scheme']);
                            header('Content-type: text/csv');
                            header('Content-disposition: attachment; filename=myfile.csv');
                            readfile('/tmp/'.$path.'.csv');
                            
                            exit();
                        header("Location:/".$path."");
                        }
                    }else{
                        if (isset($_GET['page'])) {
                            $page = $_GET['page'];
                        }else {
                            $page = 0;
                        }
                        if (isset($_GET['category'])) {
                            $category = $_GET['category'];
                        }else{
                            $category = 0;
                        }
                        $rows = summaryModel::getTaggedTable($_SESSION['scheme']);
                        require_once('../app/template/header.phtml');
                        require_once('../app/view/summary/body_jobads.phtml');
                        require_once('../app/template/footer.phtml');
                    }
                }
            }else {
                $summaryTable = summaryModel::getSummaryTable($_SESSION['scheme']);
                require_once('../app/template/header.phtml');
                require_once('../app/view/summary/body.phtml');
                require_once('../app/template/footer.phtml');
            }

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
