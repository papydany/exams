<?php
	set_time_limit(0);
	ini_set('max_execution_time',0);
	require_once dirname(__FILE__).'/config.php';
	global $connect;
	
	if( !isset($_POST['stds'], $_POST['courses'], $_POST['level']) && !empty($_POST['level']) ) {
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: maxregcourses.php');

		exit('<span style="font-family:tahoma; color:orange">What Exactly is Your Wish</span>');
	}
	
	$level = $_POST['level'];
	$month = $_POST['month'];
	$prog = $_POST['prog'];

	if($prog == "7")
	{
$apend =$month == 1?"-AP":"-AG";
	$y_sess = $_POST['ysess'].$apend;
	}else{
		$y_sess = $_POST['ysess'];
	}

	//echo $y_sess;
	//die();
	$season = $_POST['season'];
	$semester = $_POST['semester'];
	
	if( empty($level) ) {
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: maxregcourses.php');
		
		exit('<span style="font-family:tahoma; color:orange">What Exactly is Your Wish</span>');	
	}
		//echo $y_sess;
	//die();
	foreach( $_POST['stds'] as $k=>$v ):
		
		list($std_id, $std_prog, $std_fac, $std_dept, $matric_no) = explode('~', $v);
		$date_reg = date('Y-m-d');
		$course_type = '';
		
		
		$indCOURSES = $_POST['courses'];
		

		/* --------------- CHECK FOR DUPLICATE ENTRY -----------------*/
			$courses_x = array();
			foreach( $indCOURSES as $k=>$v ) {
				list($course_id, $course_unit, $course_code, $semester, $course_type) = explode('~', $v);
				$courses_x[ $k ] = $course_id;
			}
			
			$check = mysqli_query( $connect, 'SELECT * FROM course_reg WHERE thecourse_id IN ('.implode(',', $courses_x).') && std_id = '.$std_id.' && clevel_id = '.$level.' && cyearsession = "'.$y_sess.'" && course_season = "'.$season.'"');
			if( 0!=mysqli_num_rows($check) ) {
				while( $f=mysqli_fetch_assoc($check) ) {
					$key = array_search( $f['thecourse_id'], $courses_x );
					unset( $indCOURSES[$key] );
				}
				mysqli_free_result($check);
			}
		/* --------------- CHECK FOR DUPLICATE ENTRY -----------------*/		
		
				
		$regcourse = mysqli_prepare($connect, 'INSERT INTO course_reg (std_id, thecourse_id, c_unit, clevel_id, cyearsession, csemester, cdate_reg, stdcourse_custom2, stdcourse_custom3, course_season) VALUES (?,?,?,?,?,?,?,?,?,?)');
		
		foreach( $indCOURSES as $k=>$v ) {
			//echo 'courses';
			list($course_id, $course_unit, $course_code, $semester, $course_type) = explode('~', $v);
			$regcourse->bind_param('iiiissssss', $std_id, $course_id, $course_unit, $level, $y_sess, $semester, $date_reg, $course_code, $course_type, $season );
			$regcourse->execute();
			
		}
		$regcourse->close();
		
		$reg = mysqli_query( $connect, 'SELECT 1 FROM students_reg WHERE std_id = '.$std_id.' && yearsession = "'.$y_sess.'" && season = "'.$season.'" LIMIT 1' );
		//echo mysqli_num_rows($reg);// echo "<br>$std_id ". $y_sess . ' student ' . $season;
		if( 0==mysqli_num_rows($reg) ) {

			mysqli_query($connect, 'INSERT INTO students_reg (std_id, matric_no, yearsession, semester, programme_id, faculty_id, department_id, level_id, date_reg, season) VALUES ('.$std_id.', "'.$matric_no.'", "'.$y_sess.'", "First Semester", '.$std_prog.', '.$std_fac.', '.$std_dept.', '.$level.', "'.$date_reg.'", "'.$season.'"), ('.$std_id.', "'.$matric_no.'", "'.$y_sess.'", "Second Semester", '.$std_prog.', '.$std_fac.', '.$std_dept.', '.$level.', "'.$date_reg.'", "'.$season.'")');
		$reg->close();
		}

	//mysqli_query($connect, 'delete from students_reg where std_id = '.$std_id.' && yearsession = "'.$y_sess.'" && season = "'.$season.'"');
		/*$q = mysqli_query( $connect, 'select * from students_reg WHERE std_id = '.$std_id.' && yearsession = "'.$y_sess.'" && season = "'.$season.'" LIMIT 1');
		$r = mysqli_fetch_assoc($q);
		echo $r['std_id'], $r['matric_no'], $r['yearsession'], $r['semester'], $r['programme_id'], $r['faculty_id'], $r['department_id'], $r['level_id'], $r['date_reg'], $r['season'];
		
		*/
		$reg = mysqli_query( $connect, 'SELECT 1 FROM registered_semester WHERE std_id = "'.$std_id.'" && ysession = "'.$y_sess.'" && season = "'.$season.'" LIMIT 1' );
		//echo mysqli_num_rows($reg); //echo "<br>$std_id ". $y_sess . ' semester ' . $season;
		if( 0==mysqli_num_rows($reg) ) {
			
			//echo 'semester';
			mysqli_query($connect, 'INSERT INTO registered_semester (std_id, sem, ysession, rslevelid, season) VALUES ("'.$std_id.'","First Semester","'.$y_sess.'","'.$level.'", "'.$season.'"), ("'.$std_id.'","Second Semester","'.$y_sess.'","'.$level.'", "'.$season.'") ');
		$reg->close();	
		}
		
		//mysqli_query($connect, 'delete from registered_semester where std_id = '.$std_id.' && ysession = "'.$y_sess.'" && season = "'.$season.'"');
		//echo 'cycle';
	//}
	endforeach;
	//exit;
	mysqli_close($connect);
	//echo "9999";
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: maxregcourses.php?i=1');

?>