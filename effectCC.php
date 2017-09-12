<?php
	require './config.php';
	session_start();
	
	if( isset($_POST['MODIFY']) ) {
	
		$level = $_GET['level'];
		$fos = $_GET['course'];
	
		unset( $_GET['cid'] );
		$querystring = get_querystring();
				
		mysqli_query( $GLOBALS['connect'], 'UPDATE `all_courses` SET `all_courses`.`course_title` = "'.$_POST['ct'][0].'", `all_courses`.`course_code` = "'.$_POST['cc'][0].'", `all_courses`.`course_unit` = '.$_POST['cu'][0].', `all_courses`.`course_status` = "'.$_POST['cs'][0].'" WHERE thecourse_id = '.$_POST['cid'][0].' && course_custom2 = '.$fos.' && course_custom5 = "'.$_GET['yearsession'].'" LIMIT 1');
		
		
		if( 1==mysqli_affected_rows( $GLOBALS['connect'] ) ) {
							
				$a = mysqli_query( $GLOBALS['connect'], 'UPDATE course_reg SET `course_reg`.`c_unit` = "'.$_POST['cu'][0].'", `course_reg`.`stdcourse_custom2` = "'.$_POST['cc'][0].'", `course_reg`.`stdcourse_custom1` = "'.$_POST['ct'][0].'", `course_reg`.`stdcourse_custom3` = "'.$_POST['cs'][0].'" WHERE `course_reg`.`thecourse_id` = '.$_POST['cid'][0].' && `course_reg`.`clevel_id` = '.$level.' && `course_reg`.`cyearsession` = "'.$_GET['yearsession'].'" && std_id IN ( SELECT std_id FROM students_profile WHERE stdcourse = '.$fos.')');
				
				
				$b = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM students_results WHERE stdcourse_id = '.$_POST['cid'][0].' && std_mark_custom2 = "'.$_GET['yearsession'].'" && std_id IN ( SELECT std_id FROM students_profile WHERE stdcourse = '.$fos.') ');
				if( 0!=mysqli_num_rows ($b) ) {
					
					$upd_list = array();
					while( $r=mysqli_fetch_assoc($b) ) {
						if( $_POST['cu'][0]!=$r['cu'] ) {
							$upd_list[] = $r;
						}
					}
					mysqli_free_result($b);
					
					if( !empty($upd_list) ) {
						$cp = '';
						foreach( $upd_list as $g ) {

							$mark = return_mark($g['std_grade']);
							$cp = $mark * $_POST['cu'][0];
							mysqli_query( $GLOBALS['connect'], 'UPDATE students_results SET cu = "'.$_POST['cu'][0].'", cp = "'.$cp.'" WHERE stdresult_id = '.$g['stdresult_id'].'');
						}
					}
					
				}
			
			$_SESSION['info'] = 11;
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: viewcourses.php?'.$querystring);
			exit('SUCCESS');	
		
		}
		
			$_SESSION['info'] = 12;
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: viewcourses.php?'.$querystring);
			exit('DONE');			
	
	
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