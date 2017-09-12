<?php

//fixy

$connect = mysqli_connect('localhost', 'unicalnu_user', 'jasonx', 'unicalnu_exams');


$drop = array();
$ls = mysqli_query( $connect, 'SELECT std_id, stdcourse FROM students_profile');
if( 0!=mysqli_num_rows($ls) ) {
	$list = array();
	while( $d = mysqli_fetch_assoc($ls) ) {
		$list[] = $d;
	}
	mysqli_free_result($ls);
	
	foreach( $list as $k=>$v ) {
		$c = mysqli_query($connect, 'SELECT * FROM registered_semester WHERE std_id = '.$v['std_id'].' && sem = "First Semester"');
		if( 0!=mysqli_num_rows($c) ) {
			$regls = array();
			while( $d=mysqli_fetch_assoc($c) ) {
				$regls[] = $d;
			}
			mysqli_free_result($c);
			
			foreach( $regls as $sk=>$sv ) {
				$sr = mysqli_query( $connect, 'SELECT * FROM students_results WHERE std_id = '.$v['std_id'].' && level_id = '.$sv['rslevelid'].' && std_mark_custom2  = "'.$sv['ysession'].'" && stdcourse_id NOT IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$v['std_id'].' &&  clevel_id = '.$sv['rslevelid'].' &&  cyearsession = "'.$sv['ysession'].'")' );
				
				if( 0!=mysqli_fetch_assoc($sr) ) {
					// PAUSE VERSION HERE
					echo $v['std_id'], ' Year ', $sv['ysession'], ' Level ', $sv['rslevelid'], '<br/>';
					
					
					// SEE PLUG VERSION HERE
					while( $d=mysqli_fetch_assoc($sr) ) {
						var_dump($d);
					}
					//mysqli_free_result($sr);
				
					
					// DROP PLUGS VERSION HERE
					mysqli_data_seek($sr, 0);
					while( $d=mysqli_fetch_assoc($sr) ) {
						$drop[] = $d['stdresult_id'];
					}
					mysqli_free_result($sr);
					
				}
			}
		}
	}
}


/* COMMAND DROP ORDERED */
	if( !empty($drop) ) {
		$del = mysqli_query($connect, 'DELETE FROM students_results WHERE stdresult_id IN ('.implode(',', $drop).')');
		$opt = mysqli_query($connect, 'OPTIMIZE TABLE students_results');
	}
/* COMMAND DROP ORDERED */

?>