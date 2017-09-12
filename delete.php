<?php
	
	require_once './config.php';
	session_start();
	
	if( !empty($_SESSION['s_semester']) && !empty($_SESSION['s_session']) && 
		!empty($_GET['std_id']) && !empty($_GET['cr_id']) && 
		!empty($_GET['season']) && $_GET['is_js_confirmed'] == 1
		):

			
			$delete = "DELETE FROM students_results
			WHERE stdcourse_id = $_GET[cr_id] AND
			std_id = $_GET[std_id] AND
			std_mark_custom1 = '$_SESSION[s_semester]' AND
			std_mark_custom2 = '$_SESSION[s_session]' && period = '$_GET[season]' LIMIT 1";

			
			$delete2 = "DELETE FROM course_reg
			WHERE thecourse_id = '$_GET[cr_id]' AND
			std_id = '$_GET[std_id]' AND
			csemester = '$_SESSION[s_semester]' AND
			cyearsession = '$_SESSION[s_session]' && course_season = '$_GET[season]'
			LIMIT 1";

			$results = mysqli_query( $GLOBALS['connect'], $delete);
			mysqli_query( $GLOBALS['connect'], 'OPTIMIZE TABLE students_results');			
			$results2 = mysqli_query( $GLOBALS['connect'], $delete2);
			mysqli_query( $GLOBALS['connect'], 'OPTIMIZE TABLE course_reg');
			
			$sep = explode('~~', $_GET['x']);
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: add_result3.php?std_id='.$_GET['std_id'].'&mt='.$sep[0].'&rslevelid='.$sep[1].'&season='.$_GET['season'].'&rowstart=0');
			exit('Bye Bye');
  
  endif;
    
?>