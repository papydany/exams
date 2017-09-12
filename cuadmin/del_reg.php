<?php

	require_once '../config.php';
	
	
	if( isset($_POST['deleter']) && $_POST['deleter'] == 'Delete Registration' && !empty($_POST['s']) ):
		
		$ysession = $_POST['sess'];
		$level = $_POST['lvl'];
		
		$qB = '';
		
		foreach( $_POST['s'] as $k=>$v ) {
			
			$std_id = $k;
			$ex = explode('~', $v);
			$fos = $ex[0];	

			// course_reg, students_reg, registered_semester, students_results
			$qB .= 'DELETE FROM course_reg WHERE std_id = '.$std_id.' && clevel_id = '.$level.' && cyearsession = "'.$ysession.'";
			DELETE FROM students_reg WHERE std_id = '.$std_id.' && yearsession = "'.$ysession.'" && level_id = '.$level.';
			DELETE FROM registered_semester WHERE std_id = '.$std_id.' && ysession = "'.$ysession.'" && rslevelid = '.$level.';
			DELETE FROM `students_results` WHERE `std_id` = '.$std_id.' && std_mark_custom2 = "'.$ysession.'";';
			
//			mysqli_query( $GLOBALS['connect'], 'DELETE FROM course_reg WHERE std_id = '.$std_id.' && clevel_id = '.$level.' && cyearsession = "'.$ysession.'"' );
//			mysqli_query( $GLOBALS['connect'], 'DELETE FROM students_reg WHERE std_id = '.$std_id.' && yearsession = "'.$ysession.'" && level_id = '.$level.'' );	
//			mysqli_query( $GLOBALS['connect'], 'DELETE FROM registered_semester WHERE std_id = '.$std_id.' && ysession = "'.$ysession.'" && rslevelid = '.$level.'' );
//			mysqli_query( $GLOBALS['connect'], 'DELETE FROM `students_results` WHERE `std_id` = '.$std_id.' && `stdcourse_id` IN ( SELECT thecourse_id FROM `all_courses` WHERE level_id = '.$level.' && `course_custom2` = '.$fos.' && `course_custom5` = "'.$ysession.'") && std_mark_custom2 = "'.$ysession.'"' );

		}
		
			if( !empty($qB) ) {
				//$qB = substr($qB, 0, -1);
				$qB .= "OPTIMIZE TABLE course_reg,students_reg,registered_semester,students_results";
				$results = mysqli_multi_query( $GLOBALS['connect'], $qB );
			}
					
//			mysqli_query( $GLOBALS['connect'], 'OPTIMIZE TABLE course_reg,students_reg,registered_semester,students_results' );			
//			//mysqli_close($GLOBALS['connect']);
			
			$query_string = $_POST['querystring'];
			header('Location: viewregstudent.php?'.$query_string);
			header('HTTP/1.1 301 Moved Permanently');
			exit('Zoom Out Hommie');			
		
	endif;


	
	$std_id = $_GET['s'];
	$ysession = $_GET['ysess'];
	$level = $_GET['level'];
	$fos = $_GET['c'];
	
	if( !empty($std_id) && !empty($ysession) && !empty($level) && !empty($fos) ):

		// course_reg, students_reg, registered_semester, students_results
		mysqli_query( $GLOBALS['connect'], 'DELETE FROM course_reg WHERE std_id = '.$std_id.' && clevel_id = '.$level.' && cyearsession = "'.$ysession.'"' );
		mysqli_query( $GLOBALS['connect'], 'DELETE FROM students_reg WHERE std_id = '.$std_id.' && yearsession = "'.$ysession.'" && level_id = '.$level.'' );	
		mysqli_query( $GLOBALS['connect'], 'DELETE FROM registered_semester WHERE std_id = '.$std_id.' && ysession = "'.$ysession.'" && rslevelid = '.$level.'' );
		mysqli_query( $GLOBALS['connect'], 'DELETE FROM `students_results` WHERE `std_id` = '.$std_id.' && std_mark_custom2 = "'.$ysession.'"' );

		
		mysqli_query( $GLOBALS['connect'], 'OPTIMIZE TABLE course_reg,students_reg,registered_semester,students_results' );
		
		//mysqli_close($GLOBALS['connect']);
		
		unset( $_GET['s'] );
		$query_string = get_querystring();
		header('Location: viewregstudent.php?'.$query_string);
		header('HTTP/1.1 301 Moved Permanently');
		exit('Zoom');
	
	else:
		exit('Welcome to The Dead End');
	endif;

	
	function get_querystring() {
		$querystring = '';
		foreach( $_GET as $k=>$v )
			$querystring .= !empty($querystring) ? '&'.$k.'='.urlencode($v) : $k.'='.urlencode($v);	
			
		return $querystring;
	}
	
?>