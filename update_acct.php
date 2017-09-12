<?php

	include_once("auth.inc.php");
	include_once("config.php");
	
	$eo_surname = addslashes($eo_surname);
	$eo_firstname = addslashes($eo_firstname);
	$eo_title = addslashes($eo_title);
	
	$update="UPDATE exam_officers SET eo_username='$eo_username', eo_password='$eo_password', eo_surname='$eo_surname', eo_firstname = '$eo_firstname', eo_title='$eo_title', eo_othernames='$eo_othernames', eo_email='$eo_email' WHERE examofficer_id='$myexamofficer_id'";
	$results = mysqli_query( $GLOBALS['connect'], $update);
	
	if ( 0!=mysqli_affected_rows($GLOBALS['connect']) ) {
		header("Refresh: 1; URL=view_acct.php?w_update=Account was updated");
	} else {
		header("Refresh: 1; URL=view_acct.php?w_update=Sorry, Account was not successfully updated.");
	}
	exit;

?>