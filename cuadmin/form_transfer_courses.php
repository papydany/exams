<?php

	require_once '../config.php';
	
	if( $_POST['schk'] ) {
		
		if ( $_POST['delete'])
		{
			$stdcourse = $_POST['ocourse'];
		$oldcourse = $_POST['ocourse'];
		$year = $_POST['ysess'];
		$level = $_POST['level'];
		 //delete transfer courses
		 foreach( $_POST['schk'] as $k=>$v ) { 
		 $courses[] = $v;//$_POST['del'][$k];
		  }//$v; }
		  //var_dump ($_POST['del']);
			//echo 'delete'; //echo $_POST['del'];
			$del_course = 'DELETE From all_courses WHERE all_courses.level_id = "'.$level.'" AND all_courses.course_custom5 = "'.$year.'" AND all_courses.course_custom2 = "'.$stdcourse.'" AND  all_courses.thecourse_id IN ('.implode(',', $courses).') && all_courses.course_custom2 = "'.$oldcourse.'"';
			//echo $del_course;
		
			$r = mysqli_multi_query( $GLOBALS['connect'], $del_course );
			if( $r ) {
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: transfer_courses.php?del=1');
				exit;
			}
		
		} else if ( $_POST['submit']) { //transfer courses
		
		list( $department_id, $faculty_id ) = explode(',', $_POST['ndepartment']);
		$stdcourse = $_POST['ncourse'];
		$oldcourse = $_POST['ocourse'];
		$year = $_POST['nsess'];
		$level = $_POST['nlevel'];
		
		$stds = array();
		foreach( $_POST['schk'] as $k=>$v ) { $courses[] = $v; }
		
			$all_courses_table = 'UPDATE all_courses SET all_courses.level_id = "'.$level.'", all_courses.course_custom5 = "'.$year.'", all_courses.course_custom2 = "'.$stdcourse.'" WHERE all_courses.thecourse_id IN ('.implode(',', $courses).') && all_courses.course_custom2 = "'.$oldcourse.'"';
			
			$courses_table = 'UPDATE courses SET levels = "'.$level.'" WHERE thedept_id = "'.$department_id.'" and thecourse_id IN ('.implode(',', $courses).')';
				
		$r = mysqli_multi_query( $GLOBALS['connect'], $all_courses_table.';'.$courses_table );
		if( $r ) {
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: transfer_courses.php?info=1');
			exit;
		}
		echo 'transfer';
		
		
		} // end switch
		
	}
	
?>