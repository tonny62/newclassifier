<?php
    require '../app/model/classifierModel.php';
    // session_start();();

    class classifierController extends Controller{
        public static function get($param){
            // set scheme first
            if(!isset($_SESSION['scheme'])){
                header("Location: /");
            }
            $title = "Classifier";
            // get one jobads
            if(!isset($_SESSION['jobads'])){
                $_SESSION['jobads'] = classifierModel::getOneJob($_SESSION['scheme']);
                if ($_SESSION['jobads'] == 0) {
                    header("Location: /classifier?done");
                }
            }
            // set empty cart
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }
            // get categories
            $category = classifierModel::getCategory($_SESSION['scheme']);
            // handle routes
            if (isset($_GET['action'])) {
                if($_GET['action'] == 'set'){
                    // insert into db
                    if (isset($_GET['categoryname'])) {
                        // tagged with category
                        classifierModel::insertSampleIsTaggedByCategory($_SESSION['jobads'], $_GET['categoryname'],$_SESSION['scheme']);
                        unset($_SESSION['jobads']);
                        unset($_SESSION['cart']);
                        header("Location: /classifier");
                    }else{
                        // tagged with array of tags
                        $cart = classifierModel::decodeCart($_SESSION['cart']);
                        classifierModel::insertSampleIsTaggedByArray($_SESSION['jobads'],$cart);
                        unset($_SESSION['jobads']);
                        unset($_SESSION['cart']);
                        header("Location: /classifier");
                    }
                }elseif($_GET['action'] == 'skip'){
                    // skip
                    classifierModel::unlockJobads($_SESSION['jobads']);
                    unset($_SESSION['jobads']);
                    header("Location: /classifier");
                }elseif($_GET['action'] == 'add'){
                    // add to cart
                    array_push($_SESSION['cart'], $_GET['code']);
                    header("Location: /classifier");
                }elseif($_GET['action'] == 'remove'){
                    unset($_SESSION['cart'][$_GET['index']]);
                    header("Location: /classifier");
                }
            }else{
                // decode cart
                if ($_SESSION['jobads']['idsample'] == NULL) {
                    // try release
                    if (classifierModel::tryRelease($_SESSION['scheme'])) {
                        unset($_SESSION['jobads']);
                        unset($_SESSION['cart']);
                        header("Location: /classifier");
                    }else{
                        require_once('../app/template/header.phtml');
                        require_once('../app/view/classifier/body_done.phtml');
                        require_once('../app/template/footer.phtml');
                    }
                    return 0;
                }
                $cart = classifierModel::decodeCart($_SESSION['cart']);
                if(!isset($_GET['categoryname'])){
                    // call view
                    require_once('../app/template/header.phtml');
                    require_once('../app/view/classifier/body.phtml');
                    require_once('../app/template/footer.phtml');
                }else{
                    $tags = classifierModel::getTagsFromCategory($_GET['categoryname'], $_SESSION['scheme']);
                    require_once('../app/template/header.phtml');
                    require_once('../app/view/classifier/body2.phtml');
                    require_once('../app/template/footer.phtml');
                }
            }


        }

        public static function post($param){
            $title = "Classifier";
            // from home
            if (classifierModel::getSchemeInfo($_POST['schemename'])) {
                // get scheme info and hold in $_SESSION
                session_destroy();
                session_start();
                $_SESSION['scheme'] = classifierModel::getSchemeInfo($_POST['schemename']);
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = array();
                }

                header("Location: /classifier");
            }else{
                // no schemename go home
                var_dump($_POST);
                // header("Location: /");
            }
        }


    }
 ?>
