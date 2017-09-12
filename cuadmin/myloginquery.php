<?php

session_start();
include_once("../config.php");

$username = $_POST['username'];
$password = $_POST['password'];
$query = "SELECT * FROM exam_officers WHERE eo_username = '$username' AND eo_password = '$password' LIMIT 1";
$result = mysqli_query( $GLOBALS['connect'], $query) or die(mysqli_error( $GLOBALS['connect'] ));


if( 1==mysqli_num_rows($result) ) {

	$row = mysqli_fetch_assoc($result);
	
	$examofficer_id = $row['examofficer_id'];
	$eo_username = $row["eo_username"];
	$eo_password = $row["eo_password"];
	$examofficer_id = $row["examofficer_id"];
	$programme_id = $row["programme_id"];
	$faculty_id = $row["faculty_id"];
	$department_id = $row["department_id"];
	$eo_title = $row["eo_title"];
	$eo_surname = $row["eo_surname"];
	$eo_firstname = $row["eo_firstname"];
	$eo_othernames = $row["eo_othernames"];
	$eo_email = $row["eo_email"];
	$eo_date_reg = $row["eo_date_reg"];
	$eo_status = $row["eo_status"];
	$mstatus = $row["mstatus"];
	$user_right = $rows["user_right"];
	$trans_right = $rows["trans_right"];
	
	
	$eo_surname = stripslashes($eo_surname);
	$eo_firstname = stripslashes($eo_firstname);
	$eo_othernames = stripslashes($eo_othernames);
	
	$eo_fullname = trim("$eo_title $eo_surname $eo_firstname $eo_othernames");
	
	$_SESSION['logged'] = 22;
	$_SESSION['myusername'] = $username;
	$_SESSION['myeo_status'] = $eo_status;
	$_SESSION['mymstatus'] = $mstatus;
	$_SESSION['myeo_surname'] = $eo_surname;
	$_SESSION['myeo_firstname'] = $eo_firstname;
	$_SESSION['myeo_othernames'] = $eo_othernames;
	$_SESSION['myeo_email'] = $eo_email;
	$_SESSION['myexamofficer_id'] = $examofficer_id;
	$_SESSION['myeo_fullname'] = $eo_fullname;
	$_SESSION['myprogramme_id'] = $programme_id;
	$_SESSION['myfaculty_id'] = $faculty_id;
	$_SESSION['mydepartment_id'] = $department_id;
	$_SESSION['myeo_fullname'] = $eo_fullname;
	$_SERVER['user_right'] = $user_right;
	$_SERVER['trans_right'] = $trans_right;
	
	$sc = "JESUS IS LORD";
	$ksc = "$sc$mstatus";
	$tksc = md5($kcs);
	$ttksc = "a=$tksc&b=$mstatus&";
	$key = "?$ttksc";
	
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: index.php');
	exit('Amazing Grace');


} else {
	
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: login.php?error='.urlencode('invalid logon detail'));
	exit;	
}

?>