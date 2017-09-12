<?php

	require_once dirname(__FILE__).'/config.php';
	global $connect;
	
	unset( $_GET['info'] );
	$querystring = get_querystring();
	if( isset($_POST['bb'], $_POST['cid']) && !empty( $_POST['cid'] ) ) {
		
		$curricullum_year = '';
		switch( $_POST['bb'] ) {
			case 'Delete Course':
			
				$to_del = $_POST['cid'];
				$curricullum_year = $_POST['cyear_hidden'];
				foreach( $to_del as $v ) {
					$a = mysqli_query( $connect, 'DELETE FROM all_courses WHERE course_id = '.$v.' && course_custom5 = "'.$curricullum_year.'" LIMIT 1' );
				}
					mysqli_query( $connect, 'OPTIMIZE TABLE all_courses' );
			
			break;
			case 'Power Delete Course':
			
				//var_dump( $_POST ); exit('Brief Pause');
				$to_del = $_POST['cid'];
				$curricullum_year = $_POST['cyear_hidden'];
				$level = $_POST['clevel_hidden'];
				
				foreach( $to_del as $v ) {
					
					$b = mysqli_query( $connect, 'SELECT thecourse_id, course_custom2 FROM all_courses WHERE course_id = '.$v.' LIMIT 1' );
					
					if( 0!=mysqli_num_rows($b) ) {
					
						$data = mysqli_fetch_assoc($b);
						mysqli_free_result( $b );
						
						// GET All student studying FOS - $data['course_custom2']
						$b = mysqli_query( $connect, 'SELECT std_id FROM students_profile WHERE stdcourse = '.$data['course_custom2'].'' );
						$list_std = array();
						if( 0!=mysqli_num_rows($b) ) {
						
							while( $d=mysqli_fetch_array($b) ) {
								$list_std[] = $d['std_id'];
							}
							mysqli_free_result($b);
							
							if( !empty($data['thecourse_id']) && !empty($curricullum_year) && !empty($level) ) {
								// Begin Delete On Course_reg
								$del = mysqli_query( $connect, 'DELETE FROM course_reg WHERE std_id IN ('.implode(',', $list_std).') && thecourse_id = '.$data['thecourse_id'].' && clevel_id = '.$level.' && cyearsession = "'.$curricullum_year.'"' );
								
								mysqli_query( $connect, 'OPTIMIZE table course_reg' );
								
								// Begin Delete On Students_result
								$del = mysqli_query( $connect, 'DELETE FROM students_results WHERE std_id IN ('.implode(',', $list_std).') && level_id = '.$level.' && stdcourse_id = '.$data['thecourse_id'].' && std_mark_custom2 = "'.$curricullum_year.'"' );
								
								mysqli_query( $connect, 'OPTIMIZE TABLE students_results' );
							}
							
						}
						
						// Then Delete from all_courses i.e course curricullum proper
						$a = mysqli_query( $connect, 'DELETE FROM all_courses WHERE course_id = '.$v.' && course_custom5 = "'.$curricullum_year.'" LIMIT 1' );
						
						mysqli_query( $connect, 'OPTIMIZE TABLE all_courses' );
						
					}
					
					
				}

					
			break;
			case 'Add Course2Curricullum':

				$to_add = $_POST['cid'];
				$fac = $_POST['afac'];
				$dept = $_POST['adept'];
				$prog_id = $_POST['aprog'];
				$fos = $_POST['fos'];
				$yes = 'YES';
				$course_category= 'C';
				$curricullum_year = $_POST['cyear'];
				$clevel = $_POST['clevel'];
				
				$load = mysqli_query( $connect, 'SELECT * FROM courses WHERE thecourse_id IN ('.implode(',', $to_add).')' );
				if( 0!=mysqli_num_rows($load) ) {
					$all = array();
					while( $data=mysqli_fetch_assoc($load) ) {
						$all[] = $data;						
					}
					mysqli_free_result( $load );
					
					//delete past or existing traces
					mysqli_query( $connect, 'DELETE * FROM all_courses WHERE thecourse_id IN ('.implode(',', $to_add).') && level_id = '.$clevel.' && course_custom2 = '.$fos.' && course_custom5 = "'.$curricullum_year.'"' );
					mysqli_query( $connect, 'OPTIMIZE TABLE all_courses' );

					$values = '';
					foreach( $all as $k=>$v ) {
						
						$values .= '('.$v['thecourse_id'].', "'.$v['thecourse_title'].'", "'.$v['thecourse_code'].'", '.$v['thecourse_unit'].', '.$prog_id.', '.$fac.', '.$dept.', '.$clevel.', "'.$v['semester'].'", "'.$course_category.'", "'.$yes.'", "'.$fos.'", "'.$curricullum_year.'"),';
					
					}
					
					$values = substr($values,0,-1);

					$add = mysqli_query( $connect, 'INSERT INTO all_courses( `thecourse_id`, `course_title`, `course_code`, `course_unit`, `programme_id`,  `faculty_id`, `department_id`, `level_id`, `course_semester`, `course_status`, `course_custom1`, `course_custom2`, `course_custom5` ) VALUES '.$values );					
					
					
				}
		
			break;
		}
	
		
		header('HTTP/1.1 301 Moved Permanently');
		unset($_GET['info']);
		if( mysqli_affected_rows($connect)>0 ) {
			header('Location: curricullum.php?info=1&'.$querystring.'&cyear='.$curricullum_year);
		} else {
			header('Location: curricullum.php?info=0&'.$querystring.'&cyear='.$curricullum_year);
		}
		mysqli_close($connect);
		
		
	} else if( isset($_POST['bb']) && $_POST['bb'] == 'Save Changes' ) {
		
		$affected_rows = 0;
		foreach( $_POST['acid'] as $k=>$v ) {
			
			$upd_allC = mysqli_query( $connect,'UPDATE all_courses SET course_unit='.$_POST['acu'][$k].', programme_id = '.$_POST['ap'][$k].', course_semester = "'.$_POST['acsm'][$k].'", course_status="'.$_POST['acs'][$k].'" WHERE thecourse_id = '.$v.' && course_custom5 = "'.$_GET['cyear'].'"');
			
			if( mysqli_affected_rows($connect) ) {
				
				$affected_rows++;
				$upd_cR = mysqli_query($connect, 'UPDATE course_reg SET `course_reg`.`c_unit` = "'.$_POST['acu'][$k].'", `course_reg`.csemester = "'.$_POST['acsm'][$k].'", `course_reg`.`stdcourse_custom3` = "'.$_POST['acs'][$k].'" WHERE `course_reg`.`thecourse_id` = '.$v.' && `course_reg`.`clevel_id` = '.$_GET['level'].' && `course_reg`.`cyearsession` = "'.$_GET['cyear'].'"');		
				
				
				$b = mysqli_query($connect, 'SELECT * FROM students_results WHERE stdcourse_id = '.$v.' && std_mark_custom2 = "'.$_GET['cyear'].'"');
				if( 0!=mysqli_num_rows($b) ) {
					
					$upd_list = array();
					while( $r=mysqli_fetch_assoc($b) ) {
						if( $_POST['acu'][$k]!=$r['cu'] ) {
							$upd_list[] = $r;
						}
					}
					mysqli_free_result($b);
					
					if( !empty($upd_list) ) {
						$cp = '';
						foreach( $upd_list as $g ) {
							$mark = return_mark($g['std_grade']);
							$cp = $mark * $_POST['acu'][$k];
							mysqli_query($connect, 'UPDATE students_results SET cu = "'.$_POST['acu'][$k].'", cp = "'.$cp.'", std_mark_custom1 = "'.$_POST['acsm'][$k].'" WHERE stdresult_id = '.$g['stdresult_id'].'');
						}
					}
					
				}				
				
			}
				
		}
		
		
		header('HTTP/1.1 301 Moved Permanently');
		unset($_GET['info']);
		if( $affected_rows>0 ) {
			header('Location: curricullum.php?info=1&'.$querystring);
		} else {
			header('Location: curricullum.php?info=0&'.$querystring);
		}
		mysqli_close($connect);		
			
		
	}else {
		unset($_GET['info']);
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: curricullum.php?info=2&'.$querystring);
		exit;
	}
	
	
	function return_mark($grade) {
		switch( $grade ) {
			case 'F': case 'N': return 0; break;
			case 'E': return 1; break;
			case 'D': return 2; break;
			case 'C': return 3; break;
			case 'B': return 4; break;
			case 'A': return 5; break;
			default: return 'error'; break;
		}
	}
		
	function get_querystring() {
		$querystring = '';
		foreach( $_GET as $k=>$v )
			$querystring .= !empty($querystring) ? '&'.$k.'='.urlencode($v) : $k.'='.urlencode($v);	
			
		return $querystring;
	}
		
?>