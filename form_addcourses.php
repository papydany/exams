<?php

	require_once dirname(__FILE__).'/config.php';
	
	list($dept, $fac) = explode(',', $_POST['department']);
    
    
	$query_builder = '';
	$query_builder2 = '';
	
	$course_semester = $_POST['semester'];
	$course_faculty = $fac;
	$course_dept = $dept;
	$course_level = $_POST['level'];
	$course_programme = $_POST['programme'];
	$course_field = $_POST['course'];
	$curricullum_year = $_POST['yearsession'];
	$last_id = array();
	$monitor = 0;
	
	
	
	$to_add_toCI = array();
	$to_add_toC = array();
	$to_add_toU = array();
	$to_add_toT = array();
	$to_add_toCAT = array();
	
	$to_add_courses  = array();
	$to_add_units = array();
	$to_add_title = array();
	$to_add_cat = array();
	
	
	if( empty($_POST['cc']) || empty($_POST['cu']) ) {
		
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$certificate.'?i=0');	
		exit('No Work Available');
	
	}
	
	
	foreach( $_POST['cc'] as $k=>$cc ) {
		if( !empty($cc) ) {
			if( !in_array($cc, $to_add_courses) ) {
				$to_add_toCI[$k] = 'emptyid';
				$to_add_toC[$k] = strtoupper( str_ireplace(" ","",$_POST['cc'][$k]) );
				$to_add_toU[$k] = $_POST['cu'][$k];
				$to_add_toT[$k] = $_POST['ct'][$k];
				$to_add_toCAT[$k] = $_POST['cct'][$k];

				$to_add_courses[$k] = strtoupper( str_ireplace(" ","",$_POST['cc'][$k]) );				
				$to_add_units[$k] = $_POST['cu'][$k];
				$to_add_title[$k] = $_POST['ct'][$k];
				$to_add_cat[$k] = $_POST['cct'][$k];
			}
			
		}
	}

	

	/* Quick Load Purpose */
		$cCcid = array();
		$al = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id FROM all_courses WHERE level_id = '.$course_level.'  && course_custom2 = '.$course_field.' && course_status="C" && course_custom5 = '.$curricullum_year.'' );
		if( 0!=mysqli_num_rows($al)) {
			while( $data=mysqli_fetch_assoc($al) ) {
				$cCcid[] = $data['thecourse_id'];
			}
			mysqli_free_result($al);
		}
	/* Quick Load Purpose */
	

	/* Courses Check Existsing - FOR DEPARTMENT PURPOSES */
	$check_existing = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id, thecourse_title, thecourse_code, thecourse_unit FROM courses WHERE thecourse_code IN ("'.implode('","', $to_add_courses).'") && thedept_id = '.$course_dept.' && levels = "'.$course_level.'"' );
	if( 0!=mysqli_num_rows($check_existing) ) {
		
		while( $f=mysqli_fetch_assoc($check_existing) ) {
		
			$f['thecourse_code'] = strtoupper($f['thecourse_code']);
			$key = array_search( $f['thecourse_code'], $to_add_courses );
			unset( $to_add_courses[$key], $to_add_units[$key], $to_add_title[$k], $to_add_cat[$k] );
			
			if( in_array($f['thecourse_id'], $cCcid) ) { //meaning it exists also in the course_curricullum of this course of study
				unset( $to_add_toCI[$key], $to_add_toC[$key], $to_add_toU[$key], $to_add_toT[$key], $to_add_toCAT[$key] );


			} else {
				// Update the new rec with existing properties... Because The dept already has the course
				$to_add_toCI[$key] = $f['thecourse_id'];
				$to_add_toC[$key] = $f['thecourse_code'];
				/* $to_add_toU[$key] = $f['thecourse_unit']; */ // Mercy Because it is going into fos not dept again this time so unit might be diff
				/* $to_add_toT[$key] = $f['thecourse_title']; */ // Mercy for same reason of unit diff title

			}
			
		}
		
	}
	
//exit();
	/* Courses Check Existsing - FOR DEPARTMENT PURPOSES */
	
	
	$ins = mysqli_prepare($GLOBALS['connect'], 'INSERT INTO courses(`thecourse_title`, `thecourse_code`, `thecourse_unit`, `thedept_id`, `levels`, `semester`) VALUES (?,?,?,?,?,?)');
	# begin compilation

	foreach( $to_add_courses as $i=>$v ) {
		
		$monitor++;
		$course_code = $to_add_courses[$i];
		$course_unit = $to_add_units[$i];
		$course_title = strtoupper($to_add_title[$i]);


		$ins->bind_param('ssiiis', $course_title, $course_code, $course_unit, $course_dept, $course_level, $course_semester);
		$ins->execute();
		$lid = mysqli_insert_id($GLOBALS['connect']);
		$last_id[] = $lid;
		$to_add_toCI[$i] = $lid;
	
	}


	
	$monitor = 0;
	foreach( $to_add_toC as $i=>$v  ) {	
			
		$course_code = $to_add_toC[$i];
		$course_unit = $to_add_toU[$i];
		$course_title = strtoupper($to_add_toT[$i]);
		$course_status = $to_add_toCAT[$i];
		
		$thecourse_id = $to_add_toCI[$i];
		
		$query_builder2 .= '("'.$thecourse_id.'", "'.$course_title.'", "'.$course_code.'", "'.$course_unit.'", "'.$course_programme.'", "'.$course_faculty.'", "'.$course_dept.'", "'.$course_level.'", "'.$course_semester.'", "'.$course_status.'", "YES", "'.$course_field.'", "'.$curricullum_year.'"),';
		$monitor++;
		
	}


	$query_builder2 = substr($query_builder2,0,-1);

	
	$ins2 = mysqli_query($GLOBALS['connect'], 'INSERT INTO all_courses(`thecourse_id`, `course_title`, `course_code`, `course_unit`, `programme_id`, `faculty_id`, `department_id`, `level_id`, `course_semester`, `course_status`, `course_custom1`,`course_custom2`, `course_custom5`) VALUES '.$query_builder2) or die(mysqli_error($GLOBALS['connect']));
	
	
	$certificate = isset( $_POST['certificate'] ) ? $_POST['certificate'] : 'addcourse.php'; 
	if( mysqli_affected_rows($GLOBALS['connect']) > 0 ) {

		mysqli_close($GLOBALS['connect']);
		header('HTTP/1.1 301 Moved Permanently');
	     header('Location: '.$certificate.'?i=1');
		exit;
	
	}

		//done
		mysqli_close($GLOBALS['connect']);
	    header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$certificate.'?i=0');	
	

	
?>