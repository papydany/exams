<?php
	
	require_once './config.php';
	session_start();


	if( !isset( $_POST['check'] ) ) {
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$_POST['certificate']);
		exit('Bye Bye DELETE');			
	}
		
	if( isset($_POST['check']) && !empty($_POST['cs_code']) ) {
		
		$semm = $_SESSION['s_semester'];
		$sess = $_SESSION['s_session'];
		$season = $_POST['season'];
		$mn = $_POST['matric_no'];
		$cs_code = $_POST['cs_code'];
		$level = $_GET['level'];

		$std_id = '';
		
		$qB = '';
		
		foreach( $_POST['check'] as $v ) {
			if( empty($v) ) { continue; }
			$std_id = $_POST['del'][$v];		

//			$delete = "DELETE FROM students_results
//			WHERE stdcourse_id = $cs_code AND
//			std_id = $std_id AND
//			std_mark_custom2 = '$sess' && period = '$season' LIMIT 1";
//			
//			$delete2 = "DELETE FROM course_reg
//			WHERE thecourse_id = '$cs_code' AND
//			std_id = '$std_id' AND
//			cyearsession = '$sess' && course_season = '$season'
//			LIMIT 1";
			
			$qB .= "DELETE FROM students_results
			WHERE stdcourse_id = $cs_code AND
			std_id = $std_id AND
			std_mark_custom2 = '$sess' && period = '$season' LIMIT 1;
			DELETE FROM course_reg
			WHERE thecourse_id = '$cs_code' AND
			std_id = '$std_id' AND
			cyearsession = '$sess' && course_season = '$season'
			LIMIT 1;";

//			$results = mysqli_query( $GLOBALS['connect'], $delete);
//			mysqli_query( $GLOBALS['connect'], 'OPTIMIZE TABLE students_results');			
//			$results2 = mysqli_query( $GLOBALS['connect'], $delete2);
//			mysqli_query( $GLOBALS['connect'], 'OPTIMIZE TABLE course_reg');
					
		}
		
			if( !empty($qB) ) {
				//$qB = substr($qB, 0, -1);
				$qB .= "OPTIMIZE TABLE students_results, course_reg";
				$results = mysqli_multi_query( $GLOBALS['connect'], $qB );
			}


			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.$_POST['certificate']);
			exit('Bye Bye');
					
	}
	
?>