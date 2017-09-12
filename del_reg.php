<?php

	require_once './config.php';
	
	
	if( isset($_POST['deleter']) && $_POST['deleter'] == 'Delete Registration' && !empty($_POST['s']) ):
		
		$ysession = $_POST['sess'];
		$level = $_POST['lvl'];
			
	$season = $_POST['period'];
			
			
		$qB = '';
		
		foreach( $_POST['s'] as $k=>$v ) {
			
			$std_id = $k;
			$ex = explode('~', $v);
			$fos = $ex[0];	

	mysqli_query( $GLOBALS['connect'], 'DELETE FROM course_reg WHERE std_id = '.$std_id.' && course_season="'.$season.'" && clevel_id = '.$level.' && cyearsession = "'.$ysession.'"' ) or die('DELETE FROM course_reg WHERE std_id = '.$std_id.' && course_season="'.$season.'" && clevel_id = '.$level.' && cyearsession = "'.$ysession.'"');
	mysqli_query( $GLOBALS['connect'], 'DELETE FROM students_reg WHERE std_id = '.$std_id.' && season="'.$season.'" && yearsession = "'.$ysession.'" && level_id = '.$level.'' ) or die("one");	
		mysqli_query( $GLOBALS['connect'], 'DELETE FROM registered_semester WHERE std_id = '.$std_id.' && season="'.$season.'" && ysession = "'.$ysession.'" && rslevelid = '.$level.'' ) or die("two");
		mysqli_query( $GLOBALS['connect'], 'DELETE FROM `students_results` WHERE `std_id` = '.$std_id.'  && period="'.$season.'" && level_id = '.$level.' && std_mark_custom2 = "'.$ysession.'"' ) or die("three");

			mysqli_query( $GLOBALS['connect'], 'DELETE FROM `students_results_backup` WHERE `std_id` = '.$std_id.'  && period="'.$season.'" && level_id = '.$level.' && std_mark_custom2 = "'.$ysession.'"' ) or die("four");
		}
		
			if( !empty($qB) ) {
				//$qB = substr($qB, 0, -1);
				$qB .= "OPTIMIZE TABLE course_reg,students_reg,registered_semester,students_results,students_results_backup";
				//$results = mysqli_multi_query( $GLOBALS['connect'], $qB );
			}
					
		mysqli_query( $GLOBALS['connect'], 'OPTIMIZE TABLE course_reg,students_reg,registered_semester,students_results,students_results_backup');			
//			//mysqli_close($GLOBALS['connect']);
			
			$query_string = $_POST['querystring'];
		header('Location: viewregstudent.php?'.$query_string);
			header('HTTP/1.1 301 Moved Permanently');
			exit('Zoom Out Hommie');			
	
	echo "1st";	
	endif;


	$season=	$_GET['season'];
	$std_id = $_GET['s'];
	$ysession = $_GET['yearsession'];
	$level = $_GET['level'];
	$fos = $_GET['c'];
	
	if( !empty($std_id) && !empty($ysession) && !empty($level) && !empty($fos) ):

		// course_reg, students_reg, registered_semester, students_results
		
		mysqli_query( $GLOBALS['connect'], 'DELETE FROM course_reg WHERE std_id = '.$std_id.' && course_season="'.$season.'" && clevel_id = '.$level.' && cyearsession = "'.$ysession.'"' ) or die('DELETE FROM course_reg WHERE std_id = '.$std_id.' && course_season="'.$season.'" && clevel_id = '.$level.' && cyearsession = "'.$ysession.'"');
	mysqli_query( $GLOBALS['connect'], 'DELETE FROM students_reg WHERE std_id = '.$std_id.' && season="'.$season.'" && yearsession = "'.$ysession.'" && level_id = '.$level.'' ) or die("one");	
		mysqli_query( $GLOBALS['connect'], 'DELETE FROM registered_semester WHERE std_id = '.$std_id.' && season="'.$season.'" && ysession = "'.$ysession.'" && rslevelid = '.$level.'' ) or die("two");
		mysqli_query( $GLOBALS['connect'], 'DELETE FROM `students_results` WHERE `std_id` = '.$std_id.'  && period="'.$season.'" && level_id = '.$level.' && std_mark_custom2 = "'.$ysession.'"' ) or die("three");
mysqli_query( $GLOBALS['connect'], 'DELETE FROM `students_results_backup` WHERE `std_id` = '.$std_id.'  && period="'.$season.'" && level_id = '.$level.' && std_mark_custom2 = "'.$ysession.'"' ) or die("four");
		
		mysqli_query( $GLOBALS['connect'], 'OPTIMIZE TABLE course_reg,students_reg,registered_semester,students_results,students_results_backup' );
		
		//mysqli_close($GLOBALS['connect']);
		 $_GET['s'] = '';
		//unset( $_GET['s'] );
		$query_string = get_querystring();
		header('Location: viewregstudent.php?'.$query_string);
		header('HTTP/1.1 301 Moved Permanently');
		exit('Zoom');
	
	echo "2nd";
	else:
		/*header('Location: viewregstudent.php');
		header('HTTP/1.1 301 Moved Permanently');
		exit('Zoom OUT');*/
	endif;

	
	function get_querystring() {
		$querystring = '';
		foreach( $_GET as $k=>$v )
			$querystring .= !empty($querystring) ? '&'.$k.'='.urlencode($v) : $k.'='.urlencode($v);	
			
		return $querystring;
	}
	
?>