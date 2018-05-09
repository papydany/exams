<?php
	//register take courses / carry over courses
	
	require_once dirname(__FILE__).'/config.php';
	include_once "include_report.php";

	$c_lv = $_POST['c_lv'];
	$c_sess = $_POST['c_sess'];
	
	$n_lv = $_POST['n_lv'];
	$n_sess = $_POST['n_sess'];
	
	$fos = $_POST['fos'];
	
	$season = $_POST['season'];
	
	$all_course_id = array();



 /*   $wel_id = mysqli_query( $GLOBALS['connect'], 'SELECT sp.`std_id` FROM  `students_profile` as sp, suspend_student as ss WHERE  sp.std_logid=ss.login_id  && sp.stdcourse='.$fos.' && ss.student_status = "active" && ss.welcome_back_session <="'.$n_sess.'"') 
	or die(mysqli_error($GLOBALS['connect'])  );	
	$wel_array = array();

   while($fet=mysqli_fetch_assoc($wel_id)){
   	$wel_array[]=$fet;
   
   }


var_dump($wel_array);
	
	$ls_std = mysqli_query( $GLOBALS['connect'], 'SELECT  `std_id` FROM `registered_semester` WHERE `rslevelid` = '.$c_lv.' && `ysession` = "'.$c_sess.'" && `season` = "NORMAL" && std_id IN (SELECT std_id FROM `students_profile` WHERE stdcourse='.$fos.' && std_id IN ( SELECT std_id FROM students_results WHERE level_id = '.$c_lv.' && std_mark_custom2 = "'.$c_sess.'")) GROUP BY std_id') or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>"  );*/
	
	
$ls_std = mysqli_query( $GLOBALS['connect'], 'SELECT  `std_id` FROM `registered_semester` WHERE `rslevelid` = '.$c_lv.' && `ysession` = "'.$c_sess.'" && `season` = "NORMAL" && std_id IN ( SELECT sp.`std_id` FROM  `students_profile` as sp, suspend_student as ss WHERE  sp.std_logid=ss.login_id  && sp.stdcourse='.$fos.' && ss.student_status = "active" && ss.welcome_back_session <='.$n_sess. '&& sp.std_id IN ( SELECT std_id FROM students_results WHERE level_id = '.$c_lv.' && std_mark_custom2 = "'.$c_sess.'")) GROUP BY std_id') or die(mysqli_error($GLOBALS['connect']));
	
	
	
	if( 0!=mysqli_num_rows($ls_std) ) {
		$list_std1 = array();
		$list_std = array();
		while( $data=mysqli_fetch_assoc($ls_std) )
     $list_std1[] = $data['std_id'];
	 mysqli_free_result($ls_std);
	// var_dump($list_std1);

foreach ($list_std1 as $key => $value) {




if($c_sess== 2012 && $c_lv==1|| $c_sess >2012 && $c_lv>=1 && $c_lv<9){
	
	$cc=($c_sess<=2011 && $c_lv>0 && $c_lv<=7 || $c_sess<=2012 && $c_lv>1 && $c_lv<=7 || $c_sess== 2012 && $c_lv==2 || $c_sess== 2013 && $c_lv==3 || $c_sess== 2014 && $c_lv==4 || $c_sess== 2015 && $c_lv==5 || $c_sess== 2016 && $c_lv==6 || $c_sess== 2017 && $c_lv==7);
	if(!$cc){
	
		$cgpa = get_cgpa($c_sess, $value);
	
	$fail_cu=get_fail_crunit($c_lv,$value,$c_sess);
	if($cgpa >1.50 && $fail_cu < 15){
 
		//if(!in_array($value,$wel_array)){
		$list_std[]=$value;
		
//}

	}
}
}else{


  $cgpa = get_cgpa($c_sess, $value);
  if($cgpa >=1.00){

//if(!in_array($value,$wel_array)){

$list_std[]=$value;

//}
}

}

}
//var_dump($list_std);

//exit();
		//mysqli_query( $GLOBALS['connect'], 'SELECT course_id FROM `all_courses` WHERE thecourse_id NOT IN ( SELECT thecourse_id FROM course_reg ) && `level_id` = '.$n_lv.' && `course_custom2` = '.$fos.' && `course_custom5` = "'.$n_sess.'"');
		
		foreach( $list_std as $k=>$v ) {
			
			
		/*	mysqli_query( $GLOBALS['connect'], 'DELETE FROM `course_reg` WHERE `std_id` = '.$v.' 
			&& thecourse_id IN ( SELECT thecourse_id FROM all_courses WHERE level_id = '.$c_lv.' && course_custom2 = '.$fos.' && course_status IN ("C","E") && course_custom5 = "'.$c_sess.'" ) 
			&& thecourse_id NOT IN ( SELECT stdcourse_id FROM `students_results` WHERE std_id = '.$v.')') or die (mysqli_error( $GLOBALS['connect'] ));
			//&& `clevel_id` = '.$c_lv.' && `cyearsession` = "'.$c_sess.'" 
			// && `level_id` = '.$c_lv.' && `std_mark_custom2` = "'.$c_sess.'"
			//thecourse_id IN ( SELECT thecourse_id FROM `course_reg` WHERE std_id = '.$v.' && `clevel_id` = '.$c_lv.'") && 
			*/
			
			
			
			
			//old query before update
			//$ae = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM all_courses WHERE level_id = '.$c_lv.' && course_custom2 = '.$fos.' && course_status IN ("C","E") && course_custom5 = "'.$c_sess.'" && thecourse_id NOT IN ( SELECT thecourse_id FROM `course_reg` WHERE std_id = '.$v.')');
			
			
			//new update query
			$sql = 'SELECT * FROM all_courses WHERE level_id = '.$c_lv.' && course_custom5 = "'.$c_sess.'" && course_custom2 = '.$fos.' && course_status IN ("C","E") && thecourse_id NOT IN ( SELECT thecourse_id FROM `course_reg` WHERE std_id = '.$v.' && cyearsession <= '.$n_sess.') ';
			
			$sql .= ' UNION SELECT * FROM all_courses WHERE level_id = '.$c_lv.' && course_custom5 = "'.$c_sess.'" && course_custom2 = '.$fos.' && course_status IN ("C","E") && thecourse_id IN ( SELECT thecourse_id FROM `course_reg` WHERE std_id = '.$v.' && cyearsession <= '.$n_sess.') && thecourse_id NOT IN ( SELECT stdcourse_id FROM students_results WHERE std_id = '.$v.' && std_mark_custom2 <= '.$n_sess.')';
			
			$ae = mysqli_query( $GLOBALS['connect'], $sql );
			
			
			if( 0!=mysqli_num_rows($ae) ) {

				$course_ls = array();
				$all_data = array();
				
				while( $data = mysqli_fetch_assoc($ae) ):
					$all_course_id[] = $data['thecourse_id'];
					$course_ls[] = $data['thecourse_id'];

					$all_data[ $data['thecourse_id'] ] = $data;			
				endwhile;
				
				
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
												
						$ins = mysqli_query( $GLOBALS['connect'], 'INSERT INTO `course_reg` (`std_id`,  `thecourse_id`,  `c_unit`,  `clevel_id`,  `cyearsession`,  `csemester`,  `cdate_reg`,  `stdcourse_custom1`,  `stdcourse_custom2`,  `stdcourse_custom3`,  `course_season`) VALUES '.$values );
						if( mysqli_affected_rows( $GLOBALS['connect'] )>0 ) {
							
							//Registered Semester and students_reg
							$chk = mysqli_query( $GLOBALS['connect'], 'SELECT 1 FROM registered_semester WHERE std_id = '.$v.' && rslevelid = "'.$n_lv.'" && ysession = "'.$n_sess.'"  LIMIT 1');
							if( 0==mysqli_num_rows($chk) ) {
								mysqli_query( $GLOBALS['connect'], 'INSERT INTO registered_semester(std_id, sem, ysession, rslevelid, season) VALUES ("'.$v.'","First Semester","'.$n_sess.'","'.$n_lv.'", "'.$season.'"), ("'.$v.'","Second Semester","'.$n_sess.'","'.$n_lv.'", "'.$season.'") ');								
							}
							
							$chk2 = mysqli_query( $GLOBALS['connect'], 'SELECT 1 FROM students_reg WHERE std_id ='.$v.' && level_id = '.$n_lv.' && yearsession = "'.$n_sess.'" && season = "'.$season.'"');
							if( 0==mysqli_num_rows($chk2) ) {
								$ds = get_profiledetail( $v );
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
				
				$D = get_course_detail( $cid, $c_lv, $c_sess, $fos);
				$values .= '('.$cid.', "'.$D['course_title'].'", "'.$D['course_code'].'", '.$D['course_unit'].', '.$D['programme_id'].', '.$D['faculty_id'].', '.$D['department_id'].', '.$n_lv.', "'.$D['course_semester'].'", "'.$course_status.'", "'.$course_custom1.'", '.$fos.', "'.$n_sess.'"),';
			
			}
			
			$values = substr($values, 0,-1);
			
			$create_curricullum = mysqli_query( $GLOBALS['connect'], 'INSERT INTO `all_courses` ( `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`,  `programme_id`,  `faculty_id`,  `department_id`,  `level_id`,  `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom5` ) VALUES '.$values);
			if( mysqli_affected_rows( $GLOBALS['connect'] )>0 ) {
			}
		}
		
	}
	
	
	header('HTTP/1.1 301 Moved Permanently');
   header('Location: set__adv_3.php?i=1');
	exit('Peace And Out');
	
	
	function get_course_detail( $course, $l, $cs, $fos) {
		
		$ge = mysqli_query( $GLOBALS['connect'], 'SELECT `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`,  `programme_id`,  `faculty_id`,  `department_id`,  `level_id`,  `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom5` FROM `all_courses` WHERE `thecourse_id` = '.$course.' && level_id = '.$l.' && `course_custom2` = '.$fos.' && `course_custom5` = "'.$cs.'"  LIMIT 1');
		
		if( 0!=mysqli_num_rows($ge) ) {
			$data = mysqli_fetch_assoc($ge);
			mysqli_free_result($ge);
			
			return $data;
		}
			return 0;

	}
	
	
	function get_profiledetail( $std ) {
		$ae = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM students_profile WHERE std_id = '.$std.' LIMIT 1');
		if( 0!=mysqli_num_rows($ae) ) {
			$data = mysqli_fetch_assoc($ae);
			mysqli_free_result($ae);
			return $data;
		}
		return '';
	}


//SELECT course_id FROM `all_courses` WHERE thecourse_id NOT IN ( SELECT stdcourse_id FROM students_results where level_id=4 && std_mark_custom2=2011 && period="NORMAL" ) && `level_id` = 4 && `course_custom2` = 21 && `course_custom5` = "2011" && course_status="E"
?>