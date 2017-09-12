
<?php
	//register take courses / carry over courses
function register_take($c_lv, $c_sess, $n_lv, $n_sess, $fos, $season, $mylist = array())
{
	
	require_once dirname(__FILE__).'/config.php';

	
	
	
	$all_course_id = array();
	
	
		
		//set the students list ================================================
		$list_std = $mylist;
		
		
		foreach( $list_std as $k=>$v ) {
			
			$ae = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM all_courses WHERE level_id <= '.$c_lv.' && course_custom2 = '.$fos.' && department_id='.$_SESSION['mydepartment_id'].' && faculty_id='.$_SESSION['myfaculty_id'].' && course_status IN ("C","E") && thecourse_id  NOT IN ( SELECT thecourse_id FROM `course_reg` WHERE std_id = '.$v.' && clevel_id='.$n_lv.' && cyearsession <="'.$n_sess.'")')  or die (mysql_error($GLOBALS['connect']));
		
			
			if( 0!=mysqli_num_rows($ae) ) {

				$course_ls = array();
				$all_data = array();
				
				while( $data = mysqli_fetch_assoc($ae) ):
					
					$all_course_id[] = $data['thecourse_id'];
					$course_ls[] = $data['thecourse_id'];

					$all_data[ $data['thecourse_id'] ] = $data;	


				endwhile;
			//var_dump($all_data);

				
				if( !empty($course_ls) ) {
					
					$ce = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id FROM course_reg WHERE `thecourse_id` IN ('.implode(',', $course_ls).') && `std_id` = '.$v.' && `clevel_id` = '.$n_lv.' && `cyearsession` = "'.$n_sess.'"');
					if( 0!=mysqli_num_rows($ce) ) {						
						while( $sd = mysqli_fetch_assoc($ce) ) {
							unset( $all_data[ $sd['thecourse_id'] ] );
						}
						mysqli_free_result($ce);
					}
					
					if( !empty($all_data) ) {
						$date_reg = date('Y-m-d');
						$values = '';
										
						foreach( $all_data as $k=>$data ) {
							
							$values .= '('.$v.', '.$data['thecourse_id'].', '.$data['course_unit'].', '.$n_lv.', "'.$n_sess.'", "'.$data['course_semester'].'", "'.$date_reg.'", "'.$data['course_title'].'", "'.$data['course_code'].'", "'.$data['course_status'].'", "'.$season.'"),';						

						}

						$values = substr($values,0,-1);
					//var_dump($values);
												
						$ins = mysqli_query( $GLOBALS['connect'], 'INSERT INTO `course_reg` (`std_id`,  `thecourse_id`,  `c_unit`,  `clevel_id`,  `cyearsession`,  `csemester`,  `cdate_reg`,  `stdcourse_custom1`,  `stdcourse_custom2`,  `stdcourse_custom3`,  `course_season`) VALUES '.$values );
						if( mysqli_affected_rows( $GLOBALS['connect'] )>0 ) {
							
							//Registered Semester and students_reg
							$chk = mysqli_query( $GLOBALS['connect'], 'SELECT 1 FROM registered_semester WHERE std_id = '.$v.' && ysession = "'.$n_sess.'" && rslevelid = "'.$n_lv.'" LIMIT 1');
							if( 0==mysqli_num_rows($chk) ) {
								mysqli_query( $GLOBALS['connect'], 'INSERT INTO registered_semester(std_id, sem, ysession, rslevelid, season) VALUES ("'.$v.'","First Semester","'.$n_sess.'","'.$n_lv.'", "'.$season.'"), ("'.$v.'","Second Semester","'.$n_sess.'","'.$n_lv.'", "'.$season.'") ');								
							}
							
							$chk2 = mysqli_query( $GLOBALS['connect'], 'SELECT 1 FROM students_reg WHERE std_id ='.$v.' && yearsession = "'.$n_sess.'" && season = "'.$season.'" && level_id = '.$n_lv.'');
							if( 0==mysqli_num_rows($chk2) ) {
								$ds = get_profiledetail_t( $v );
								mysqli_query( $GLOBALS['connect'], 'INSERT INTO students_reg(std_id, matric_no, yearsession, semester, programme_id, faculty_id, department_id, level_id, date_reg, season) VALUES ('.$v.', "'.$ds['matric_no'].'", "'.$n_sess.'", "First Semester", '.$ds['stdprogramme_id'].', '.$ds['stdfaculty_id'].', '.$ds['stddepartment_id'].', '.$n_lv.', "'.$date_reg.'", "'.$season.'"), ('.$v.', "'.$ds['matric_no'].'", "'.$n_sess.'", "Second Semester", '.$ds['stdprogramme_id'].', '.$ds['stdfaculty_id'].', '.$ds['stddepartment_id'].', '.$n_lv.', "'.$date_reg.'", "'.$season.'")');
								
							}
							
							continue;
						}	

					}
					
				}
				
				
			}
		
			
		}
	}
	
	
	if( !empty($all_course_id) ) {
		$all_course_id = array_unique($all_course_id);
				
		$values = '';
		$ae = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id FROM `all_courses` WHERE thecourse_id IN ('.implode(',', $all_course_id).') && `level_id` = '.$n_lv.' && `course_custom2` = '.$fos.' && `course_custom5` = "'.$n_sess.'"');
		if( 0!=mysqli_num_rows($ae) ) {
			while( $data=mysqli_fetch_assoc($ae) ) {
				
				$key = array_search( $data['thecourse_id'], $all_course_id);
				if( false!=$key || $key==0 ) {
					unset( $all_course_id[$key] );
				}
			}
			mysqli_free_result($ae);
		}
		
		$course_status = 'E';
		$course_custom1 = 'YES';
		if( !empty($all_course_id) ) {
			$values = '';
			foreach( $all_course_id as $cid ) {
				
				$D = get_course_detail_t( $cid, $c_lv, $c_sess, $fos);
				$values .= '('.$cid.', "'.$D['course_title'].'", "'.$D['course_code'].'", '.$D['course_unit'].', '.$D['programme_id'].', '.$D['faculty_id'].', '.$D['department_id'].', '.$n_lv.', "'.$D['course_semester'].'", "'.$course_status.'", "'.$course_custom1.'", '.$fos.', "'.$n_sess.'"),';
			
			}
			
			$values = substr($values, 0,-1);
			
			$create_curricullum = mysqli_query( $GLOBALS['connect'], 'INSERT INTO `all_courses` ( `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`,  `programme_id`,  `faculty_id`,  `department_id`,  `level_id`,  `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom5` ) VALUES '.$values);
			if( mysqli_affected_rows( $GLOBALS['connect'] )>0 ) {
			}
		}
			
} 

function register_take2($c_lv, $c_sess, $n_lv, $n_sess, $fos, $season, $mylist = array())
{
	require_once dirname(__FILE__).'/config.php';

	$all_course_id = array();
	//set the students list ================================================
		$list_std = $mylist;
		
		
		foreach( $list_std as $k=>$v ) {
		//	var_dump($v);
			$ae = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM all_courses WHERE level_id = '.$c_lv.' && course_custom2 = '.$fos.' && course_status IN ("C") && course_custom5 = "'.$c_sess.'" && thecourse_id NOT IN ( SELECT thecourse_id FROM `course_reg` WHERE std_id = '.$v.' && clevel_id='.$c_lv.' && cyearsession ="'.$c_sess.'" && stdcourse_custom3 IN("C") )');
		

			if( 0!=mysqli_num_rows($ae) ) {

	         //$all_course_id = array();
				$course_ls = array();
				$all_data = array();
				
				while( $data = mysqli_fetch_assoc($ae) ):
					
					$all_course_id[] = $data['thecourse_id'];
					$course_ls[] = $data['thecourse_id'];

					$all_data[ $data['thecourse_id'] ] = $data;	

               
				endwhile;
			
                // var_dump($all_data);
               //   exit();

				
				if( !empty($course_ls) ) {
					
					$ce = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id FROM course_reg WHERE `thecourse_id` IN ('.implode(',', $course_ls).') && `std_id` = '.$v.' && `clevel_id` = '.$n_lv.' && `cyearsession` = "'.$n_sess.'"');
					if( 0!=mysqli_num_rows($ce) ) {						
						while( $sd = mysqli_fetch_assoc($ce) ) {
							unset( $all_data[ $sd['thecourse_id'] ] );
						}
						mysqli_free_result($ce);
					}

					
					if( !empty($all_data) ) {
						$date_reg = date('Y-m-d');
						$values = '';
										
						foreach( $all_data as $k=>$data ) {
							
							$values .= '('.$v.', '.$data['thecourse_id'].', '.$data['course_unit'].', '.$n_lv.', "'.$n_sess.'", "'.$data['course_semester'].'", "'.$date_reg.'", "'.$data['course_title'].'", "'.$data['course_code'].'", "'.$data['course_status'].'", "'.$season.'"),';						

						}

						$values = substr($values,0,-1);
					//var_dump($values);
												
						$ins = mysqli_query( $GLOBALS['connect'], 'INSERT INTO `course_reg` (`std_id`,  `thecourse_id`,  `c_unit`,  `clevel_id`,  `cyearsession`,  `csemester`,  `cdate_reg`,  `stdcourse_custom1`,  `stdcourse_custom2`,  `stdcourse_custom3`,  `course_season`) VALUES '.$values );
						if( mysqli_affected_rows( $GLOBALS['connect'] )>0 ) {
							
							//Registered Semester and students_reg
							$chk = mysqli_query( $GLOBALS['connect'], 'SELECT 1 FROM registered_semester WHERE std_id = '.$v.' && ysession = "'.$n_sess.'" && rslevelid = "'.$n_lv.'" LIMIT 1');
							if( 0==mysqli_num_rows($chk) ) {
								mysqli_query( $GLOBALS['connect'], 'INSERT INTO registered_semester(std_id, sem, ysession, rslevelid, season) VALUES ("'.$v.'","First Semester","'.$n_sess.'","'.$n_lv.'", "'.$season.'"), ("'.$v.'","Second Semester","'.$n_sess.'","'.$n_lv.'", "'.$season.'") ');								
							}
							
							$chk2 = mysqli_query( $GLOBALS['connect'], 'SELECT 1 FROM students_reg WHERE std_id ='.$v.' && yearsession = "'.$n_sess.'" && season = "'.$season.'" && level_id = '.$n_lv.'');
							if( 0==mysqli_num_rows($chk2) ) {
								$ds = get_profiledetail_t( $v );
								mysqli_query( $GLOBALS['connect'], 'INSERT INTO students_reg(std_id, matric_no, yearsession, semester, programme_id, faculty_id, department_id, level_id, date_reg, season) VALUES ('.$v.', "'.$ds['matric_no'].'", "'.$n_sess.'", "First Semester", '.$ds['stdprogramme_id'].', '.$ds['stdfaculty_id'].', '.$ds['stddepartment_id'].', '.$n_lv.', "'.$date_reg.'", "'.$season.'"), ('.$v.', "'.$ds['matric_no'].'", "'.$n_sess.'", "Second Semester", '.$ds['stdprogramme_id'].', '.$ds['stdfaculty_id'].', '.$ds['stddepartment_id'].', '.$n_lv.', "'.$date_reg.'", "'.$season.'")');
								
							}
							
							continue;
						}	

					}
					
				}
				
				
			}
		
			
		}
	
	//}
	
	if( !empty($all_course_id) ) {
		$all_course_id = array_unique($all_course_id);
				
		$values = '';
		$ae = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id FROM `all_courses` WHERE thecourse_id IN ('.implode(',', $all_course_id).') && `level_id` = '.$n_lv.' && `course_custom2` = '.$fos.' && `course_custom5` = "'.$n_sess.'"');
		if( 0!=mysqli_num_rows($ae) ) {
			while( $data=mysqli_fetch_assoc($ae) ) {
				
				$key = array_search( $data['thecourse_id'], $all_course_id);
				if( false!=$key || $key==0 ) {
					unset( $all_course_id[$key] );
				}
			}
			mysqli_free_result($ae);
		}
		//var_dump($all_course_id);
		
		$course_status = 'E';
		$course_custom1 = 'YES';
		if( !empty($all_course_id) ) {
			$values = '';
			foreach( $all_course_id as $cid ) {
				
				$D = get_course_detail_t( $cid, $c_lv, $c_sess, $fos);
				$values .= '('.$cid.', "'.$D['course_title'].'", "'.$D['course_code'].'", '.$D['course_unit'].', '.$D['programme_id'].', '.$D['faculty_id'].', '.$D['department_id'].', '.$n_lv.', "'.$D['course_semester'].'", "'.$course_status.'", "'.$course_custom1.'", '.$fos.', "'.$n_sess.'"),';
			
			}
			
			$values = substr($values, 0,-1);
			
			$create_curricullum = mysqli_query( $GLOBALS['connect'], 'INSERT INTO `all_courses` ( `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`,  `programme_id`,  `faculty_id`,  `department_id`,  `level_id`,  `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom5` ) VALUES '.$values);
			if( mysqli_affected_rows( $GLOBALS['connect'] )>0 ) {
			}
		}
		
	} 
	

	
	
} // end the main take function here





	function get_course_detail_t( $course, $l, $cs, $fos) {
		
		$ge = mysqli_query( $GLOBALS['connect'], 'SELECT `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`,  `programme_id`,  `faculty_id`,  `department_id`,  `level_id`,  `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom5` FROM `all_courses` WHERE `thecourse_id` = '.$course.' && level_id = '.$l.' && `course_custom2` = '.$fos.' && `course_custom5` = "'.$cs.'"  LIMIT 1');
		
		if( 0!=mysqli_num_rows($ge) ) {
			$data = mysqli_fetch_assoc($ge);
			mysqli_free_result($ge);
			
			return $data;
		}
			return 0;

	}
	
	
	function get_profiledetail_t( $std ) {
		$ae = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM students_profile WHERE std_id = '.$std.' LIMIT 1');
		if( 0!=mysqli_num_rows($ae) ) {
			$data = mysqli_fetch_assoc($ae);
			mysqli_free_result($ae);
			return $data;
		}
		return '';
	}

?>