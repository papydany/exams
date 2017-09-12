<?php
	
	require_once("config.php");
	
	if( !empty($_GET['i'] ) ) {
		mysqli_query( $GLOBALS['connect'], 'DELETE FROM exam_officers WHERE examofficer_id = '.$_GET['i'].' LIMIT 1');
		mysqli_query( $GLOBALS['connect'], 'OPTIMIZE table exam_officers');
	}
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: viewaccounts.php');
		
?>