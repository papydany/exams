<?php
	
	session_start();
	
	$from = 'info@unical.nucdb.edu.ng';
	$to = 'tobymagic@ovi.com';
	
	require_once dirname(__FILE__).'/config.php';
	if( isset($_GET['m']) ) {
		
		$name = "Unical Exams and Records";
		$from = "$website_cooperateemail";
		$today = date("j/m/Y, H:m");
		$headers = "From:   $name <$from> \n";
		
		$body = "Mail sent on $today by $from\n\nBug was found on the unical examsandrecords error is ".$_GET['m'];
		
		@mail( "$to", "Unical Exams and Records, $website_name", $body, $headers );
		exit('<div style="text-align:center color:green; padding:5px; font-size:15px;font-family:"Lucida Console", Monaco, monospace ">Error Has Been Reported!<br/> T h a n k   Y o u   V e r y   M u c h   F o r   Y o u r   R e p o r t i n g</div>');		

	}
	
	
	
	if( isset($_POST['mbody'], $_POST['msubj']) ) {
		
		mysqli_query( $GLOBALS['connect'], 'INSERT INTO messaging (`m_to`, `m_by`,  `m_subj`,  `m_bdy`, `m_dt`) VALUES ("ADMIN", "'.$_SESSION['myexamofficer_id'].'", "'.$_POST['msubj'].'", "'.$_POST['mbody'].'", CURDATE())' );
		if( mysqli_affected_rows($GLOBALS['connect']) > 0 ) {
			exit('1');
		} else {
			exit('0');
		}
		
	}

?>