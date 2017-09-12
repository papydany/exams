<?php

require_once 'config.php';




$query1a = "ALTER TABLE students_results ADD result_flag VARCHAR( 13 ) NOT NULL DEFAULT 'Sessional' AFTER period ";

$result1a = mysqli_query( $GLOBALS['connect'], $query1a) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");

echo "Column result_flg added to table results_students<br />";


$query1b = "ALTER TABLE students_results ADD result_approved VARCHAR( 5 ) NOT NULL DEFAULT 'N' AFTER result_flag ";

$result1b = mysqli_query( $GLOBALS['connect'], $query1b) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");

echo "Column result_approved added to table results_students<br />";


$query1c = "ALTER TABLE students_results ADD approval_date datetime AFTER result_approved ";

$result1c = mysqli_query( $GLOBALS['connect'], $query1c) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");

echo "Column approval_date added to table results_students<br />";



$query2a = "ALTER TABLE students_results_backup ADD result_flag VARCHAR( 13 ) AFTER period ";

$result2a = mysqli_query( $GLOBALS['connect'], $query2a) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");

echo "Column result_flg added to table results_students_backup<br />";



$query3a = "ALTER TABLE exam_officers ADD user_right INTEGER NOT NULL DEFAULT 1 AFTER edit_allow_logon ";

$result3a = mysqli_query( $GLOBALS['connect'], $query3a) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");

echo "Column user_right added to table exam_officers<br />";



$query3b = "ALTER TABLE exam_officers ADD trans_right INTEGER NOT NULL DEFAULT 0 AFTER user_right ";

$result3b = mysqli_query( $GLOBALS['connect'], $query3b) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");

echo "Column trans_right added to table exam_officers<br />";


$sqlprofile1 = "ALTER TABLE `students_profile` ADD `school_cert` VARCHAR( 100 ) , ADD `school_cert_yr` VARCHAR( 100 ) ";

$resultprofile1 = mysqli_query( $GLOBALS['connect'], $sqlprofile1) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");

echo "students_profile table altered successfully<br />";


$sqlprofile = "ALTER TABLE `students_profile` ADD `major` VARCHAR( 100 ) AFTER `school_cert_yr` , ADD `minor` VARCHAR( 100 ) AFTER `major` , ADD `date_of_graduation` DATE AFTER `minor` , ADD `last_institution` VARCHAR( 150 ) AFTER `date_of_graduation` ";

$resultprofile = mysqli_query( $GLOBALS['connect'], $sqlprofile) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");

echo "students_profile table altered 2 successfully<br />";

$sqlcert = "CREATE TABLE `school_certificates` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`std_id` INT( 11 ) NOT NULL ,`subject` VARCHAR( 25 ) ,`grade` VARCHAR( 3 ) ,`subject1` VARCHAR( 25 ) ,`grade1` VARCHAR( 3 ) ,`subject2` VARCHAR( 25 ) ,`grade2` VARCHAR( 3 ) ,`subject3` VARCHAR( 25 ) ,`grade3` VARCHAR( 3 ) ,`subject4` VARCHAR( 25 ) ,`grade4` VARCHAR( 3 ) ,`subject5` VARCHAR( 25 ) ,`grade5` VARCHAR( 3 ) ,`subject6` VARCHAR( 25 ) ,`grade6` VARCHAR( 3 ) ,`subject7` VARCHAR( 25 ) ,`grade7` VARCHAR( 3 ) ,`subject8` VARCHAR( 25 ) ,`grade8` VARCHAR( 3 ))";

$resultcert = mysqli_query( $GLOBALS['connect'], $sqlcert) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");

echo "Table school_certificates created successfully<br />";



?>