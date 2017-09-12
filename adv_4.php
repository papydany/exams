<?php
	session_start();
	require_once './config.php';
	
	
	$_SESSION['conclude']['level'] = $_POST['level'];
	$_SESSION['conclude']['session'] = $_POST['session'];
	$_SESSION['conclude']['season'] = $_POST['season'];
	
	
	$lvl = $_POST['level'];
	$session = $_POST['session'];
	$period = isset( $_POST['season'] ) ? $_POST['season'] : 'NORMAL';
	
	if( isset($_POST['submit']) && $_POST['submit'] == 'Save Result' && isset($_POST['b']) && !empty($_POST['b']) ):
		
		$qB = '';
				
		foreach( $_POST['b'] as $k=>$v ) {
			
			$xc = explode( '~', $k );
			$v = strtoupper($v);
			
			if( !in_array($v, array('A','B','C','D','E','F','N'))) {
				continue;
			}
						
			if( !empty($v) ) {
			
				$size = count( $xc );
				if( 6 == $size ) {
					
					//UPDATE EXISTING RESULT
					$std_id = $xc[0];
					$matric_no = $xc[1];
					$surname = $xc[2];
					$othername = $xc[3];
					$courseid = $xc[4];
					$cu= $xc[5];
					
					$_SESSION['conclude']['s'][ $std_id ] = "0~$matric_no~$surname~$othername";
					
					$x = mm( $v, $cu );			
					
					$qB .= 'UPDATE `students_results` SET `std_mark` = "'.$x['mark'].'", std_grade = "'.$v.'", cp = '.$x['cp'].' WHERE std_id = '.$std_id.' && level_id = '.$lvl.' && stdcourse_id = '.$courseid.' && std_mark_custom2 = "'.$session.'" && period = "'.$period.'";';
						
				} else {
				
					//INSERT FRESH RESULT
					$std_id = $xc[0];
					$matric_no = $xc[1];
					$surname = $xc[2];
					$othername = $xc[3];
					$courseid = $xc[4];
					$cu= $xc[5];
					$sem = $xc[6];
					
					$_SESSION['conclude']['s'][ $std_id ] = "0~$matric_no~$surname~$othername";
					
					$x = mm( $v, $cu );
					
					$s_ = mysqli_query($GLOBALS['connect'], 'SELECT 1 FROM students_results WHERE std_id = "'.$std_id.'" && level_id = "'.$lvl.'" && std_mark_custom2 = "'.$session.'" && stdcourse_id = "'.$courseid.'" && period = "'.$period.'"' );
					
					// if not exists alone
					if( 0 == mysqli_num_rows($s_) ) {
						mysqli_free_result($s_);
						$qB .= 'INSERT INTO `students_results`(`std_id`,  `matric_no`,  `level_id`,  `stdcourse_id`, `std_mark`,  `std_grade`,  `cu`,  `cp`,  `std_cstatus`,  `std_mark_custom1`,  `std_mark_custom2`, `period` ) VALUES ('.$std_id.', "'.$matric_no.'", '.$lvl.', '.$courseid.', "'.$x['mark'].'", "'.$v.'", '.$cu.', '.$x['cp'].', "YES", "'.$sem.'", "'.$session.'", "'.$period.'");';
					}
					
				}
				
			
			}
			
		}
		
		if( !empty($qB) ) {
			$qB = substr($qB, 0, -1);
			if( mysqli_multi_query( $GLOBALS['connect'], $qB ) ) {
				
				do {if ($result = mysqli_store_result($GLOBALS['connect'])) { while ($row = mysqli_fetch_row($result)) {} mysqli_free_result($result);}
				} while (mysqli_next_result($GLOBALS['connect']));			
			
			}
			
		}		
		
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: set__adv_4.php');
		exit('Peace Reigns');
		
	endif;
	
	
	
	if( isset($_POST['submit']) && $_POST['submit'] == 'Delete Result' ):
		
		if( !isset($_POST['d']) ) {
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: set__adv_4.php');
			exit('Peace Reigns');		
		}
		
		$qB = '';
		
		foreach( $_POST['d'] as $k=>$v ) {
			
			$xc = explode( '~', $k );
			$v = strtoupper($v);
			
			$size = count( $xc );
			if( 5==$size ) {
				// DELETE COURSE_REG AND STUDENTSRESULT
				$std_id = $xc[0];
				$matric_no = $xc[1];
				$surname = $xc[2];
				$othername = $xc[3];
				$courseid = $xc[4];
				
				$_SESSION['conclude']['s'][ $std_id ] = "0~$matric_no~$surname~$othername";
				
				$qB .= 'DELETE FROM course_reg WHERE std_id = '.$std_id.' && thecourse_id = '.$courseid.' && clevel_id = '.$lvl.' && cyearsession = "'.$session.'" && course_season = "'.$period.'" LIMIT 1;
				DELETE FROM students_results WHERE std_id = '.$std_id.' && stdcourse_id = '.$courseid.' && level_id = '.$lvl.' && std_mark_custom2 = "'.$session.'" && period = "'.$period.'" LIMIT 1;';
													
			}
			
		}
		
		if( !empty($qB) ) {
			$qB .= 'Optimize table course_reg, students_results';
			mysqli_multi_query( $GLOBALS['connect'], $qB );
		}		
	
		
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: set__adv_4.php');
		exit('Peace Reigns');		
	
	endif;

		function mm( $G, $U ) {
			
			$return = array();
			switch( $G ) {
				case 'A':
					$return['mark'] = 75;
					$return['cp'] = 5 * $U;
				break;
				case 'B':
					$return['mark'] = 65;
					$return['cp'] = 4 * $U;			
				break;
				case 'C':
					$return['mark'] = 55;
					$return['cp'] = 3 * $U;			
				break;
				case 'D':
					$return['mark'] = 47;
					$return['cp'] = 2 * $U;			
				break;
				case 'E':
					$return['mark'] = 43;
					$return['cp'] = 1 * $U;			
				break;
				case 'F':
					$return['mark'] = 20;
					$return['cp'] = 0 * $U;			
				break;
				case 'N':
					$return['mark'] = 0;
					$return['cp'] = 0 * $U;			
				break;
			}
			return $return;
		}	
	
?>