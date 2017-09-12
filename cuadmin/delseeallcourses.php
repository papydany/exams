<?php

	require_once '../config.php';
	
	if( isset($_POST['DELETE'], $_POST['id']) ) {
		
		$checkbx = '';
		$query = '';
		
		foreach( $_POST['id'] as $checkbx ) {
			list( $cid, $csess ) = explode('~', $checkbx);
			
			$query .= 'DELETE FROM course_reg WHERE std_id = '.$_POST['std'].' && thecourse_id = '.$cid.' && cyearsession = "'.$csess.'";
			DELETE FROM students_results WHERE std_id = '.$_POST['std'].' && stdcourse_id = '.$cid.' && std_mark_custom2 = "'.$csess.'";';
			
		}
		
		$_ = mysqli_multi_query( $GLOBALS['connect'], $query );
		
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: seeallcourses.php?std='.$_POST['std'].'&mn='.$_POST['mn'].'&cache='.md5($_SERVER['REQUEST_TIME'] * rand(0,9999)));
		exit;
				
	} else {
		
		if( isset($_GET['std'], $_GET['csess'], $_GET['cid'], $_GET['mn']) && !empty($_GET['std']) && !empty($_GET['cid']) && !empty( $_GET['csess']) ) {
			
			$query = 'DELETE FROM course_reg WHERE std_id = '.$_GET['std'].' && thecourse_id = '.$_GET['cid'].' && cyearsession = "'.$_GET['csess'].'";
			DELETE FROM students_results WHERE std_id = '.$_GET['std'].' && stdcourse_id = '.$_GET['cid'].' && std_mark_custom2 = "'.$_GET['csess'].'"';
			
			$_ = mysqli_multi_query( $GLOBALS['connect'], $query );
			
			
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: seeallcourses.php?std='.$_GET['std'].'&mn='.$_GET['mn'].'');
			exit;
		}
	
	}

?>