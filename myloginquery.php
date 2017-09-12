<?php

	session_start();
	require_once dirname(__FILE__).'/config.php';
	
	// in case linking is fiddled with
	if( isset($_SESSION['mydepartment_id']) ) {
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: index.php');
		exit('Talent Explored');
	}
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$query = "SELECT * FROM exam_officers WHERE eo_username = '$username' AND  eo_password = '$password' LIMIT 1";
	$result = mysqli_query( $GLOBALS['connect'], $query) or 
	die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	
	
	if( 1==mysqli_num_rows ($result) ) {
	
		$row = mysqli_fetch_assoc($result);


		$_SESSION['logged'] = 22;
		$_SESSION['myusername'] = $username;
		$_SESSION['myeo_status'] = $row["eo_status"];
		$_SESSION['mymstatus'] = $row["mstatus"];
		$_SESSION['myeo_surname'] = $row["eo_surname"];
		$_SESSION['myeo_firstname'] = $row["eo_firstname"];
		$_SESSION['myeo_othernames'] = $row["eo_othernames"];
		$_SESSION['myeo_email'] = $row["eo_email"];
		$_SESSION['myexamofficer_id'] = $row["examofficer_id"];
		$_SESSION['myeo_fullname'] = trim( stripslashes($row['eo_surname']).' '.stripslashes($row['eo_firstname']).' '.stripslashes($row['eo_othernames']) );
		$_SESSION['myprogramme_id'] = $row["programme_id"];
		$_SESSION['myfaculty_id'] = $row["faculty_id"];
		$_SESSION['mydepartment_id'] = $row["department_id"];
		$_SESSION['user_right'] = $row["user_right"]; //--------------------------
		$_SESSION['trans_right'] = $row["trans_right"]; //-------------------------- To determine the right to Transcript
		
		if( $row['edit_allow_logon'] > 0 ) {
			
			$_SESSION['myedit_permission'] = 1;
			
			$logon_left = $row['edit_allow_logon'] == 1 ? 0 : $row['edit_allow_logon'] - 1;
			$_ = mysqli_query( $GLOBALS['connect'], 'UPDATE exam_officers SET edit_allow_logon = '.$logon_left.' WHERE eo_username = "'.$username.'"');
			
		}
		
		session_regenerate_id(true);
		session_write_close();
				
		
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.str_replace('amp;', '&', $_POST['redirect']) );
		
	
	} else {
		
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$_POST['redirect'].'?error='.urlencode('invalid logon detail'));
		exit;	
	
	}

?>