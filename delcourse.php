<?php
    require_once 'config.php';
	session_start();
	
	if( isset($_GET['d']) && !empty($_GET['d']) ) {
	
		$del_id = $_GET['d'];
		unset( $_GET['d'] );
		$query_string = get_querystring();
		
		$session = $_GET['yearsession'];
		$course_of_studee = $_GET['course'];
		$level = $_GET['level'];
		
		
		
		$qB = 'DELETE FROM all_courses WHERE thecourse_id = '.$del_id.' && level_id = '.$level.' && course_custom2 = '.$course_of_studee.' && course_custom5 = "'.$session.'"  LIMIT 1;
		DELETE FROM course_reg WHERE thecourse_id = '.$del_id.' && std_id IN ( SELECT std_id FROM students_profile WHERE stdcourse = '.$course_of_studee.' ) && clevel_id = '.$level.' && cyearsession = "'.$session.'";
		DELETE FROM students_results WHERE stdcourse_id = '.$del_id.' && std_id IN ( SELECT std_id FROM students_profile WHERE stdcourse = '.$course_of_studee.' ) && level_id = '.$level.' && std_mark_custom2 = "'.$session.'";
		OPTIMIZE TABLE `all_courses`, `course_reg`, `students_results`';
		
		
		if( $r = mysqli_multi_query( $GLOBALS['connect'], $qB ) ) {	
			do {if ($result = mysqli_store_result($GLOBALS['connect'])) { while ($row = mysqli_fetch_row($result)) {} mysqli_free_result($result);}
			} while (mysqli_next_result($GLOBALS['connect']));
		}		
		
		
		if( $r ) {
			
			$_SESSION['info'] = 1;
			
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: viewcourses.php?'.$query_string);
			exit('WORK EXECUTED SUCCESSFULLY');
		
		} 
			$_SESSION['info'] = 0;
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: viewcourses.php?'.$query_string);
			exit('ACTION COULD NOT COMPLETE');
			
	}
	
	
	function get_querystring() {
		$querystring = '';
		foreach( $_GET as $k=>$v )
			$querystring .= !empty($querystring) ? '&'.$k.'='.urlencode($v) : $k.'='.urlencode($v);	
			
		return $querystring;
	}
?>