<?php session_start();
	require_once dirname(__FILE__).'/config.php';
	require_once dirname(__FILE__).'/include_report.php';
	

	$month = $_POST['month'];
	$apend =$month == 1?"-AP":"-AG";
	if($_SESSION['myprogramme_id'] == 7)
	{
    $next = $_POST['c_sess'] + 1;
	$c_sess = $_POST['c_sess'].$apend;	
    $n_sess = $next.$apend;
	
	  $new_prob ="2012".$apend;
	}else{
		
		$c_sess = $_POST['c_sess'];
		$n_sess = $c_sess + 1;
		$new_prob =2012;	
	}
	$season = 'NORMAL';
	$c_lv = $_POST['c_lv'];
	$fos = $_POST['fos'];
	$n_lv = $c_lv;
	
	
	//============== get take courses registered ===
	
	require_once dirname(__FILE__).'/adv_6_take.php';
	$c_lv_t = $_POST['c_lv'];
	$c_sess_t = $c_sess;
	
	$n_lv_t = $n_lv; //$_POST['n_lv'];
	$n_sess_t = $n_sess; //$_POST['n_sess'];
	
	$fos_t = $_POST['fos'];
	
	$season_t = $season; //$_POST['season'];
	
	$all_course_id = array();
	
		
	//============== end take courses registered ===

	$sus_id = mysqli_query( $GLOBALS['connect'], 'SELECT  `std_id` FROM  `students_profile` as sp, studentstatus as ss WHERE  sp.std_logid=ss.std_logid  && sp.stdcourse='.$fos.' && ss.studentstatus = "suspension" && ss.suspensionyear <="'.$n_sess_t.'"') 
	or die(mysqli_error($GLOBALS['connect'])  );	
	$sus_array = array();

   while($fet=mysqli_fetch_assoc($sus_id)){
   	$sus_array[]=$fet;
   //	var_dump($sus_array);
   }

	$ls_std = mysqli_query( $GLOBALS['connect'], 'SELECT  `std_id` FROM `registered_semester` WHERE `rslevelid` = '.$c_lv.' && `ysession` = "'.$c_sess.'" && `season` = "NORMAL" && std_id IN (SELECT std_id FROM `students_profile` WHERE stdcourse='.$fos.' ) GROUP BY std_id') 
	or die(mysqli_error($GLOBALS['connect']));	
	
	if( 0!=mysqli_num_rows($ls_std) ) {
		
		$stdlist = array();
		while( $d=mysqli_fetch_assoc($ls_std) ) {
			$stdlist[ $d['std_id'] ] = $d;
		}
		mysqli_free_result($ls_std);

		$prob_ls = array();
		foreach( $stdlist as $k=>$v ) {
		//$cgpa = adv_get_cgpa($c_sess, $k, $c_lv);
		 $cgpa = get_cgpa($c_sess, $k);	
		 
          $fail_cu=get_fail_crunit($c_lv,$k,$c_sess); 

         $entry_year = get_entry_sesssion($k);
	if(!in_array($k, $sus_array)){

	if($entry_year['std_custome2'] >= $new_prob ){	
			
       if($cgpa >=1.00 && $cgpa <=1.49 && $fail_cu<=15 || $fail_cu==15){
      
       	$prob_ls[] =$k;
     	
     	
       }
   }
   else{
   		if( $cgpa >= 0.75 && $cgpa < 1.00 ) {
   			$prob_ls[] = $k;
	    }
   }
   
}
}

if( !empty($prob_ls) ):

	register_take2($c_lv_t, $c_sess_t, $n_lv_t, $n_sess_t, $fos_t, $season_t, $prob_ls);
			
			foreach( $prob_ls as $std ) {
					
					$fl = mysqli_query( $GLOBALS['connect'], 'SELECT `std_id`,  `matric_no`,  `students_results`.`level_id`,  `stdcourse_id`,  `std_ca`,  `std_exam`,  `std_mark`,  `std_grade`,  `cu`, `std_mark_custom1`,  `std_mark_custom2`,  `std_mark_custom3`,  `period`, `course_title`, `course_code`, `course_status` FROM students_results INNER JOIN all_courses ON students_results.stdcourse_id = all_courses.thecourse_id WHERE std_id = '.$std.' && students_results.std_grade IN ("F") && students_results.std_mark >= 0 && students_results.level_id = '.$c_lv.' && students_results.std_mark_custom2 = "'.$c_sess.'" && all_courses.course_custom2 = '.$fos.' && all_courses.level_id = '.$c_lv.'' );
					if( 0!=mysqli_num_rows($fl) ) {

						$accpt = array();
						while( $d=mysqli_fetch_array($fl) )
						{
							$accpt[ $d['stdcourse_id'] ] = $d;
							$all_course_id[] = $d['stdcourse_id'];
						}
							if($std ==6)
							{
								var_dump($accpt);
							}
						mysqli_free_result($fl);
						
						/* my name is checker */
							$courseskey = array_keys($accpt);
							$chk = mysqli_query( $GLOBALS['connect'], 'select * from course_reg where std_id = '.$std.' && course_reg.clevel_id = '.$n_lv.' && course_reg.cyearsession = "'.$n_sess.'" && course_reg.thecourse_id IN ('.implode(',', $courseskey).')');
							if( 0!=mysqli_num_rows($chk) ) {
								while( $cut = mysqli_fetch_assoc($chk) ) {
									unset( $accpt[$cut['thecourse_id']] );
								}
								mysqli_free_result($chk);
							}
						/* my name is checker */
						
						/* my name is generator */
							$qB = '';
							if( !empty($accpt) ) {
								
								foreach( $accpt as $k=>$v ) {
									$qB .= '('.$std.', '.$k.', '.$v['cu'].', '.$n_lv.', "'.$n_sess.'", "'.$v['std_mark_custom1'].'", CURDATE(), "'.$v['course_title'].'", "'.$v['course_code'].'", "'.$v['course_status'].'", "'.$v['period'].'" ),';
									
									
				
							$values_probation .= '('.$k.',"'.$v['course_title'].'","'.$v['course_code'].'",'.$v['cu'].', "'.$_SESSION['myprogramme_id'].'","'.$_SESSION['myfaculty_id'].'","'.$_SESSION['mydepartment_id'].'",'.$n_lv.',"'.$v['std_mark_custom1'].'","'.$v['course_status'].'","'.$fos.'", "'.$n_sess.'"),';					
								}
								
								$qB = substr($qB, 0, -1);
								
								$values_probation = substr($values_probation,0,-1);
								
								$ins = mysqli_query( $GLOBALS['connect'], 'INSERT INTO course_reg (`std_id`,  `thecourse_id`,  `c_unit`,  `clevel_id`,  `cyearsession`,  `csemester`,  `cdate_reg`,  `stdcourse_custom1`, `stdcourse_custom2`,  `stdcourse_custom3`,  `course_season`) VALUES '.$qB );
								
								
	// check if the course exist on all_probation_course table
									$select_probation = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM `all_probation_course` WHERE `thecourse_id` = '.$k.' && level_id = '.$n_lv.' && `course_custom2` = '.$fos.' && `course_custom5` = "'.$n_sess.'"  LIMIT 1')or die(mysqli_error($GLOBALS['connect']));
		
	if( mysqli_num_rows($select_probation) == 0 ) {
	 
	 $ins_prob = mysqli_query( $GLOBALS['connect'], 'INSERT INTO all_probation_course(`thecourse_id`,`course_title`,`course_code`, `course_unit`,`programme_id`,`faculty_id`,`department_id`,  `level_id`,`course_semester`,`course_status`,`course_custom2`,`course_custom5`) VALUES '.$values_probation ) or die(mysqli_error($GLOBALS['connect']));
	}
							
							}

								//if( 0!=mysqli_affected_rows($GLOBALS['connect']) ) {}
								
									//Registered Semester and students_reg
									$chk = mysqli_query( $GLOBALS['connect'], 'SELECT 1 FROM registered_semester WHERE std_id = '.$std.' && ysession = "'.$n_sess.'" && rslevelid = "'.$n_lv.'" LIMIT 1');
									if( 0==mysqli_num_rows ($chk) ) {
										mysqli_query( $GLOBALS['connect'], 'INSERT INTO registered_semester(std_id, sem, ysession, rslevelid, season) VALUES ("'.$std.'","First Semester","'.$n_sess.'","'.$n_lv.'", "'.$season.'"), ("'.$std.'","Second Semester","'.$n_sess.'","'.$n_lv.'", "'.$season.'") ');								
									}
									
									$chk2 = mysqli_query( $GLOBALS['connect'], 'SELECT 1 FROM students_reg WHERE std_id ='.$std.' && yearsession = "'.$n_sess.'" && season = "'.$season.'" && level_id = '.$n_lv.'');
									if( 0==mysqli_num_rows ($chk2) ) {
										$ds = get_profiledetail( $std );
										mysqli_query( $GLOBALS['connect'], 'INSERT INTO students_reg(std_id, matric_no, yearsession, semester, programme_id, faculty_id, department_id, level_id, date_reg, season) VALUES ('.$std.', "'.$ds['matric_no'].'", "'.$n_sess.'", "First Semester", '.$ds['stdprogramme_id'].', '.$ds['stdfaculty_id'].', '.$ds['stddepartment_id'].', '.$n_lv.', CURDATE(), "'.$season.'"), ('.$std.', "'.$ds['matric_no'].'", "'.$n_sess.'", "Second Semester", '.$ds['stdprogramme_id'].', '.$ds['stdfaculty_id'].', '.$ds['stddepartment_id'].', '.$n_lv.', CURDATE(), "'.$season.'")');
										
									}
								
								//}
								
								
							//}
						/* my name is generator */

					}
				}
				
			//	var_dump($all_course_id);
					//	exit();

					header('HTTP/1.1 301 Moved Permanently');
					header('Location:set__adv_6.php?i=1');
					exit('Return favour');
				
			endif;
			
			
		}

// --------please not new codes here----------
if( !empty($all_course_id) ) 
	{
		echo "In<br>";
		
			
		$all_course_id = array_unique($all_course_id);
		
		print_r($all_course_id);
		
		$values = '';
		$ae = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id FROM `all_courses` WHERE thecourse_id IN ('.implode(',', $all_course_id).') && `level_id` = '.$n_lv.' && `course_custom2` = '.$fos.' && `course_custom5` = "'.$n_sess.'"');
		if( 0!=mysqli_num_rows ($ae) ) 
		{
			while( $data=mysqli_fetch_assoc($ae) ) 
			{
				
				$key = array_search( $data['thecourse_id'], $all_course_id);
				if( false!=$key || $key==0 ) {
					unset( $all_course_id[$key] );
				}
			}
			mysqli_free_result($ae);
		}
		echo "<hr>";
		print_r($all_course_id);



		$course_status = 'E';
		$course_custom1 = 'YES';

		
		if( !empty($all_course_id) ) 
		{
			echo "IN2<br>";

			$values = '';
			foreach( $all_course_id as $cid ) 
			{
				
				$D = get_course_detail( $cid, $c_lv, $c_sess, $fos);
				if(!empty($D['course_unit']))
				{
				$values .= '('.$cid.', "'.$D['course_title'].'", "'.$D['course_code'].'", '.$D['course_unit'].', '.$D['programme_id'].', '.$D['faculty_id'].', '.$D['department_id'].', '.$n_lv.', "'.$D['course_semester'].'", "'.$course_status.'", "'.$course_custom1.'", '.$fos.', "'.$n_sess.'"),';
				}
				//echo $values."<br><br>";
			}
			
			$values = substr($values, 0,-1);
			
			echo 'INSERT INTO `all_courses` ( `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`,  `programme_id`,  `faculty_id`,  `department_id`,  `level_id`,  `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom5` ) VALUES '.$values."<hr>";

			$create_curricullum = mysqli_query( $GLOBALS['connect'], 'INSERT INTO `all_courses` ( `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`,  `programme_id`,  `faculty_id`,  `department_id`,  `level_id`,  `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom5` ) VALUES '.$values);
			if( mysqli_affected_rows( $GLOBALS['connect'] )>0 ) 
			{ 
				$processed++; 
			}
			echo "PROCESSED:".$processed."<br>";
		}
		
		
	}
	
	
	//header('HTTP/1.1 301 Moved Permanently');
	if( $processed=="0" )
	{
		header('Location: set__adv_6.php?i=0');
		
	}
	else
	{
	
	header('Location: set__adv_6.php?i=1');
		
	}
	exit('Peace And Out');
	
		
	function get_course_detail( $course, $l, $cs, $fos) {
		
		$ge = mysqli_query( $GLOBALS['connect'], 'SELECT `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`,  `programme_id`,  `faculty_id`,  `department_id`,  `level_id`,  `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom5` FROM `all_courses` WHERE `thecourse_id` = '.$course.' && level_id = '.$l.' && `course_custom2` = '.$fos.' && `course_custom5` = "'.$cs.'"  LIMIT 1');
		

		if( 0!=mysqli_num_rows($ge) ) 
		{
			$data = mysqli_fetch_assoc($ge);
			mysqli_free_result($ge);
			
			return $data;
		}
			return 0;

	}
	
	
	
// --------------- ends here ------------------


	function get_profiledetail( $std ) {
		$ae = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM students_profile WHERE std_id = '.$std.' LIMIT 1');
		if( 0!=mysqli_num_rows ($ae) ) {
			$data = mysqli_fetch_assoc($ae);
			mysqli_free_result($ae);
			return $data;
		}
		return '';
	}		
	
	
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: set__adv_6.php?i=0');
	exit('Scream');
	
?>