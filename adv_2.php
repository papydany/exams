<?php ini_set('memory_limit', -1);
include_once("auth.inc.php");
	require_once './config.php';
	session_start();
	$std_id = $_POST['std'];
	$matric_no = $_POST['mat'];
	$period = $_POST['period'];
	$examofficer =$_SESSION['myexamofficer_id'];
	$date_posted =date('Y-m-d h:i:s');

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
				if( 4 == $size ) {
					//UPDATE EXISTING RESULT
					$session = $xc[0];
					$courseid = $xc[1];
					$cu= $xc[2];
					$lvl = $xc[3];
					

					
					$x = mm( $v, $cu );	
					// new code by me 2018/6/4
					$ss_ = mysqli_query($GLOBALS['connect'], 'SELECT * FROM students_results WHERE std_id = "'.$std_id.'" && level_id = "'.$lvl.'" && std_mark_custom2 = "'.$session.'" && stdcourse_id = "'.$courseid.'"  && period = "'.$period.'"' );
							
					// if not exists alone
					
					if( mysqli_num_rows($ss_) == 1 ) {
						//var_dump(mysqli_num_rows($ss_)).'<br/>';
					while ($value = mysqli_fetch_assoc($ss_))
					{
					
						if($value['std_grade'] == $v)
						{//echo $v.'~'.$value['std_grade'];

						}else{
							//echo $v.'~'.$value['std_grade'];
						

						//echo $value['std_grade'];
					
					$qB .= 'UPDATE `students_results` SET `std_mark` = "'.$x['mark'].'", std_grade = "'.$v.'", cp = '.$x['cp'].',examofficer="'.$examofficer.'",date_posted="'.$date_posted.'" WHERE std_id = '.$std_id.' && level_id = '.$lvl.' && stdcourse_id = '.$courseid.' && std_mark_custom2 = "'.$session.'" && period = "'.$period.'";';
					//echo $qB.'<br/>';
				}
					}
						
					
				}
					
					
				} else {
					//INSERT FRESH RESULT
					$session = $xc[0];
					$courseid = $xc[1];
					$level = $xc[2];
					$cu= $xc[3];
					$sem = $xc[4];
					
					$x = mm( $v, $cu );
					
					
					
					$s_ = mysqli_query($GLOBALS['connect'], 'SELECT 1 FROM students_results WHERE std_id = "'.$std_id.'" && level_id = "'.$level.'" && std_mark_custom2 = "'.$session.'" && stdcourse_id = "'.$courseid.'" && period = "'.$period.'"' );
					
					// if not exists alone
					if( 0 == mysqli_num_rows($s_) ) {
						mysqli_free_result($s_);					
						$qB .= 'INSERT INTO `students_results`(`std_id`,  `matric_no`,  `level_id`,  `stdcourse_id`, `std_mark`,  `std_grade`,  `cu`,  `cp`,  `std_cstatus`,  `std_mark_custom1`,  `std_mark_custom2`, `period`,`examofficer`,`date_posted` ) VALUES ('.$std_id.', "'.$matric_no.'", '.$level.', '.$courseid.', "'.$x['mark'].'", "'.$v.'", '.$cu.', '.$x['cp'].', "YES", "'.$sem.'", "'.$session.'", "'.$period.'","'.$examofficer.'","'.$date_posted.'");';
						
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
			mysqli_close($GLOBALS['connect']);
		}		
		
		
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: set__adv_2.php?s='.$std_id.'&m='.$matric_no.'&p='.$period);
		exit('Peace Reigns');
		
		

		
	endif;
	
	if( isset($_POST['submit']) && $_POST['submit'] == 'Delete Result' ):
		
		if( !isset( $_POST['d']) ) {
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: set__adv_2.php?s='.$std_id.'&m='.$matric_no.'&p='.$period);
			exit('Peace Reigns');		
		}
		
		$qB = '';
		foreach( $_POST['d'] as $k=>$v ) {
			
			$xc = explode( '~', $k );
			$v = strtoupper($v);
			
			$size = count( $xc );
			if( 3==$size ) {
				// DELETE COURSE_REG AND STUDENTSRESULT
				$session = $xc[0];
				$courseid = $xc[1];
				$lvl = $xc[2];
				
				$qB .= 'DELETE FROM course_reg WHERE std_id = '.$std_id.' && thecourse_id = '.$courseid.' && clevel_id = '.$lvl.' && cyearsession = "'.$session.'" && course_season = "'.$period.'" LIMIT 1;
				DELETE FROM students_results WHERE std_id = '.$std_id.' && stdcourse_id = '.$courseid.' && level_id = '.$lvl.' && std_mark_custom2 = "'.$session.'" && period = "'.$period.'" LIMIT 1;';
													
			}
			
		}

		if( !empty($qB) ) {
			$qB .= 'Optimize table course_reg, students_results';
			mysqli_multi_query( $GLOBALS['connect'], $qB );
		}		
		
		
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: set__adv_2.php?s='.$std_id.'&m='.$matric_no.'&p='.$period);
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