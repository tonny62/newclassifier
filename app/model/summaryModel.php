<?php

    class summaryModel extends Model{
        public static function getCounts($schemename){
            $db = Model::getDB();
            // get counts
            $q = "SELECT jobads.adssource, jobads_is_tagged.categoryname, count(*) FROM jobads_is_tagged
            LEFT JOIN jobads ON jobads_is_tagged.jobid = jobads.jobid
            WHERE jobads_is_tagged.schemename = '".$schemename."'
            GROUP BY jobads.adssource, jobads_is_tagged.categoryname";
            $stmt = $db->query($q);
            $countRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $countRows;
        }

        public static function getCategory($schemename){
            $db = Model::getDB();
            // get category
            $q = "SELECT categoryname FROM category WHERE schemename = '".$schemename."'";
            $stmt = $db->query($q);
            $category =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $category;
        }

        public static function getJobsource(){
            $db = Model::getDB();
            $q = "SELECT * FROM jobsource";
            $stmt = $db->query($q);
            $jobsource = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $jobsource;
        }

        public static function getSummaryFrame($schemename){
            $counts = summaryModel::getCounts($schemename);
            $category = summaryModel::getCategory($schemename);
            $jobsource = summaryModel::getJobsource();

            $summaryFrame = array();
            foreach ($jobsource as $key => $value) {
                $summaryFrame[$value['jobsourcename']] = array();
                foreach ($category as $keyin => $valuein) {
                    $summaryFrame[$value['jobsourcename']][$valuein['categoryname']] = 0;
                }
            }

            foreach ($counts as $key => $value) {
                $summaryFrame[$value['adssource']][$value['categoryname']] = $value['count(*)'];
            }

            return $summaryFrame;

        }
    }
 ?>
