<?php

    class summaryModel extends Model{
        public static function getTaggedTable($schemearry){
            $db = Model::getDB();
            $q = "SELECT
                table2.idjob,
                jobads.company,
                jobads.position,
                jobads.url,
                jobads.description,
                table2.tag1,
                table2.tag2,
                table2.tag3,
                table2.tag4,
                table2.tag5,
                table2.tag6,
                table2.tag7,
                table2.tag8,
                table2.tag9,
                table2.tag10
            FROM
                (SELECT
                    table1.idjob,
                    SUBSTRING_INDEX(table1.myconcat,',',1) AS tag1,
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',2),',',-1)
                        ELSE NULL
                        END AS 'tag2',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',3),',',-1)
                        ELSE NULL
                        END AS 'tag3',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',4),',',-1)
                        ELSE NULL
                        END AS 'tag4',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',5),',',-1)
                        ELSE NULL
                        END AS 'tag5',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',6),',',-1)
                        ELSE NULL
                        END AS 'tag6',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',7),',',-1)
                        ELSE NULL
                        END AS 'tag7',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',8),',',-1)
                        ELSE NULL
                        END AS 'tag8',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',9),',',-1)
                        ELSE NULL
                        END AS 'tag9',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',10),',',-1)
                        ELSE NULL
                        END AS 'tag10'
                FROM
                    (SELECT jobads.idjob, (CASE WHEN tags.nametag IS NULL THEN CONCAT('|',category.namecategory,'|') ELSE GROUP_CONCAT(CONCAT('|',tags.namecategory,'-',tags.nametag,'|')) END) AS myconcat
                    FROM sample_has_tag
                    LEFT JOIN samples ON samples.idsample = sample_has_tag.idsample
                    LEFT JOIN jobads ON samples.idjob = jobads.idjob
                    LEFT JOIN tags ON sample_has_tag.idtag = tags.idtag
                    LEFT JOIN category ON category.idcategory = sample_has_tag.idcategory
                    WHERE samples.idscheme = ".$schemearry['idscheme']."
                    GROUP BY jobads.idjob) AS table1) as table2

            LEFT JOIN jobads ON table2.idjob = jobads.idjob";




            $stmt = $db->query($q);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }


        public static function dumpCsvFile($schemearry){
            $db = Model::getDB();
            date_default_timezone_set ("Asia/Bangkok");
            $t = time();
            $folder = "/tmp";
            $filename = date("Y-m-d_H:i:s_",$t).$schemearry['namescheme'];
            $path = $folder."/".$filename;

            $q = "SELECT
                table2.idjob,
                jobads.company,
                jobads.position,
                jobads.url,
                jobads.description,
                IFNULL(table2.tag1, 'NA'),
                IFNULL(table2.tag2, 'NA'),
                IFNULL(table2.tag3, 'NA'),
                IFNULL(table2.tag4, 'NA'),
                IFNULL(table2.tag5, 'NA'),
                IFNULL(table2.tag6, 'NA'),
                IFNULL(table2.tag7, 'NA'),
                IFNULL(table2.tag8, 'NA'),
                IFNULL(table2.tag9, 'NA'),
                IFNULL(table2.tag10, 'NA')
            FROM
                (SELECT
                    table1.idjob,
                    SUBSTRING_INDEX(table1.myconcat,',',1) AS tag1,
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',2),',',-1)
                        ELSE NULL
                        END AS 'tag2',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',3),',',-1)
                        ELSE NULL
                        END AS 'tag3',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',4),',',-1)
                        ELSE NULL
                        END AS 'tag4',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',5),',',-1)
                        ELSE NULL
                        END AS 'tag5',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',6),',',-1)
                        ELSE NULL
                        END AS 'tag6',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',7),',',-1)
                        ELSE NULL
                        END AS 'tag7',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',8),',',-1)
                        ELSE NULL
                        END AS 'tag8',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',9),',',-1)
                        ELSE NULL
                        END AS 'tag9',
                    CASE
                        WHEN table1.myconcat LIKE '|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|,|%|' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(table1.myconcat,',',10),',',-1)
                        ELSE NULL
                        END AS 'tag10'
                FROM
                (SELECT jobads.idjob, (CASE WHEN tags.nametag IS NULL THEN CONCAT('|',category.namecategory,'|') ELSE GROUP_CONCAT(CONCAT('|',tags.namecategory,'-',tags.nametag,'|')) END) AS myconcat
                FROM sample_has_tag
                LEFT JOIN samples ON samples.idsample = sample_has_tag.idsample
                LEFT JOIN jobads ON samples.idjob = jobads.idjob
                LEFT JOIN tags ON sample_has_tag.idtag = tags.idtag
                LEFT JOIN category ON category.idcategory = sample_has_tag.idcategory
                WHERE samples.idscheme = ".$schemearry['idscheme']."
                GROUP BY jobads.idjob) AS table1) as table2

            LEFT JOIN jobads ON table2.idjob = jobads.idjob
            INTO OUTFILE '".$path.".csv'
            FIELDS TERMINATED BY ',' ENCLOSED BY '`'
            LINES TERMINATED BY '\n';";
            $stmt = $db->query($q);
            return $filename;
        }


        public static function getSummaryTable($scheme){
            $db = Model::getDB();
            $q = "SELECT table1.myconcat, COUNT(table1.myconcat) AS mycount FROM(
                    SELECT sample_has_tag.idsample, GROUP_CONCAT(DISTINCT category.namecategory) as myconcat FROM sample_has_tag
                    LEFT JOIN category ON sample_has_tag.idcategory = category.idcategory
                    WHERE
                        sample_has_tag.idsample IN
                            (SELECT idsample FROM samples WHERE samples.idscheme = ".$scheme['idscheme'].")
                    GROUP BY sample_has_tag.idsample
                    ORDER BY sample_has_tag.idsample, sample_has_tag.idcategory) AS table1
                GROUP BY table1.myconcat";
            $stmt = $db->query($q);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }










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
