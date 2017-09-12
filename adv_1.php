<?php session_start();
	require_once dirname(__FILE__).'/config.php';

	if($_SESSION['myprogramme_id'] == 7 && empty($_POST['month']))
	{
	
		echo '<div style="font-family:arial; font-size:16px; padding:20px; text-align:center;">Entry Month Must be Selected <a href="set__adv_1.php">Return To Repeat Courses</a></div>';
		exit;
	}
	
	if( empty($_POST['c_sess']) || empty( $_POST['n_sess']) || empty( $_POST['c_lv']) || empty( $_POST['n_lv']) || empty( $_POST['fos']) || empty( $_POST['season']) ) {
		echo '<div style="font-family:arial; font-size:16px; padding:20px; text-align:center;">All Field Must be Filled <a href="set__adv_1.php">Return To Repeat Courses</a></div>';
		exit;
	}

	$processed = 0;
	$month = $_POST['month'];
	$apend =$month == 1?"-AP":"-AG";
	if($_SESSION['myprogramme_id'] == 7)
	{
$n_sess = $_POST['n_sess'].$apend;
	$c_sess = $_POST['c_sess'].$apend;
	  $new_prob ="2012".$apend;
	}else{
		$n_sess = $_POST['n_sess'];
			$c_sess = $_POST['c_sess'];
		  $new_prob =2012;	
	}

	$c_lv = $_POST['c_lv'];

	
	$n_lv = $_POST['n_lv'];
	
	
	$fos = $_POST['fos'];
	
	$season = $_POST['season'];
	
	$all_course_id = array();


  $sus_id = mysqli_query( $GLOBALS['connect'], 'SELECT  `std_id` FROM  `students_profile` as sp, studentstatus as ss WHERE  sp.std_logid=ss.std_logid  && sp.stdcourse='.$fos.' && ss.studentstatus = "suspension" && ss.suspensionyear <="'.$n_sess.'"') 
	or die(mysqli_error($GLOBALS['connect'])  );	
	$sus_array = array();

   while($fet=mysqli_fetch_assoc($sus_id)){
   	$sus_array[]=$fet;
   }


	
	$ls_std = mysqli_query( $GLOBALS['connect'], 'SELECT  `std_id` FROM `registered_semester` WHERE `rslevelid` = '.$c_lv.' && `ysession` = "'.$c_sess.'" && `season` = "NORMAL" && std_id IN (SELECT std_id FROM `students_profile` WHERE stdcourse='.$fos.') GROUP BY std_id') or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>"  );

	if( 0!=mysqli_num_rows($ls_std) ) {
		
		$list_std = array();
		while( $data=mysqli_fetch_assoc($ls_std) )
			$list_std[] = $data['std_id'];
		mysqli_free_result($ls_std);



		foreach ($sus_array as $key => $value) {
			if(in_array($value, $list_std)){
		unset($list_std[$key]);
}
		}

		if( isset($_POST['prob']) ) { // Ingnores Probation Student and withdrawer
			
			require_once dirname(__FILE__).'/include_report.php';
			
			$prob_ls = $list_std;
		
			
			foreach( $prob_ls as $k=>$v ) {
			$entry_year = get_entry_sesssion($v);
				//$cgpa = adv_get_cgpa($c_sess, $v, $c_lv);
				$cgpa = get_cgpa($c_sess, $v);
				
	            $fail_cu=get_fail_crunit($c_lv,$v,$c_sess); 
	     
if($entry_year['std_custome2'] >= $new_prob ){

			/*if($c_sess== 2012 && $c_lv==1|| $c_sess >2012 && $c_lv>=1 && $c_lv<9){
	
	$cc=($c_sess<=2011 && $c_lv>0 && $c_lv<=7 || $c_sess<=2012 && $c_lv>1 && $c_lv<=7 || $c_sess== 2012 && $c_lv>=2 || $c_sess== 2013 && $c_lv>=3 || $c_sess== 2014 && $c_lv>=4 || $c_sess== 2015 && $c_lv>=5 || $c_sess== 2016 && $c_lv>=6 || $c_sess== 2017 && $c_lv>=7 || $c_sess== 2018 && $c_lv>=8 );
	if(!$cc){*/
	
      
       if($cgpa <1.5 || $fail_cu >=15){
       
       	unset( $list_std[$k] );
       
       
       } // end of this condition will be remove later.
   }else{


  /*$cgpa = get_cgpa($c_sess, $value);
  if($cgpa >=1.00){
if(!in_array($value, $sus_array)){
$list_std[]=$value;
}

}
}
}else{ */
	              if( $cgpa < 1.00 ){
					unset( $list_std[$k] );
				}
			
			}			
		
		}
	
	
	//var_dump($list_std);
	//die();
		
	if( !empty( $list_std ) ) {
		foreach( $list_std as $k=>$v ) {
			
		
				$sql = 'SELECT  `stdresult_id`,  `std_id`,  `matric_no`,  `level_id`,  `stdcourse_id`,  `std_ca`,  `std_exam`,  `std_mark`,  `std_grade`,  `cu`,  `cp`,  `std_cstatus`,  `std_mark_custom1`,  `std_mark_custom2`, `std_mark_custom3` FROM `students_results` WHERE std_id = '.$v.' && `level_id` = '.$c_lv.' && `std_grade` IN ("F","N") && `std_mark` >= 0 && `std_mark_custom2` = "'.$c_sess.'" ';
			
			
			
			$ae = mysqli_query( $GLOBALS['connect'], $sql );
			

			if( 0!=mysqli_num_rows($ae) ) {
				
				$course_ls = array();
				$all_data = array();
				
				while( $data = mysqli_fetch_assoc($ae) ):
					$all_course_id[] = $data['stdcourse_id'];
					$course_ls[] = $data['stdcourse_id'];
					$all_data[ $data['stdcourse_id'] ] = $data;			
				endwhile;
				
			
				}
				
				
					
				
				if( isset($_POST['chk']) ) {
					$has_carryF = has_carryF( $v, $course_ls, $fos );
					if( !empty($has_carryF) ) {
						// Delete trace from array list
						foreach( $has_carryF as $value ) {
							$keys = '';
							$keys2 = '';
							unset( $all_data[ $value ] );
							
							$keys = array_keys( $all_course_id, $value );
							if( !empty($keys) ){
								foreach( $keys as $d )
									unset( $all_course_id[$d] );
							}
							
							$keys2 = array_keys( $course_ls, $value );
							if( !empty($keys2) ){
								foreach( $keys2 as $d )
									unset( $course_ls[$d] );
							}		
								
						}
					}

				}


				if( !empty($course_ls) ) {
					
					$ce = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id FROM course_reg WHERE `thecourse_id` IN ('.implode(',', $course_ls).') && `std_id` = '.$v.' && `clevel_id` = '.$n_lv.' && `cyearsession` = "'.$n_sess.'"');
					if( 0!=mysqli_num_rows($ce) ) {
						while( $sd = mysqli_fetch_assoc($ce) ) {
							unset( $all_data[ $sd['thecourse_id'] ] );
						}
						mysqli_free_result($ce);
					}
					
	
					
					if( !empty($all_data) ) 
					{
						$date_reg = date('Y-m-d');
						$values = '';
						foreach( $all_data as $k=>$data ) 
						{
							

							$cts = get_course_detail( $data['stdcourse_id'], $c_lv, $c_sess, $fos );

							if(empty($cts)){
							$pcts = get_probation_detail($data['stdcourse_id'], $c_lv, $c_sess, $fos );
							
							$values .= '('.$v.', '.$data['stdcourse_id'].', '.$data['cu'].', '.$n_lv.', "'.$n_sess.'", "'.$data['std_mark_custom1'].'", "'.$date_reg.'", "'.$pcts['ccourse_title'].'", "'.$pcts['course_code'].'", "'.$pcts['course_status'].'", "'.$season.'"),';
						    } 
							
			
	

							if( !empty($cts) ) 
							{
								//echo "Not Empty!<br>";
								$values .= '('.$v.', '.$data['stdcourse_id'].', '.$data['cu'].', '.$n_lv.', "'.$n_sess.'", "'.$data['std_mark_custom1'].'", "'.$date_reg.'", "'.$cts['ccourse_title'].'", "'.$cts['course_code'].'", "'.$cts['course_status'].'", "'.$season.'"),';
							
							}	


						}
						$values = substr($values,0,-1);

						
						$ins = mysqli_query( $GLOBALS['connect'], 'INSERT INTO `course_reg` (`std_id`,  `thecourse_id`,  `c_unit`,  `clevel_id`,  `cyearsession`,  `csemester`,  `cdate_reg`,  `stdcourse_custom1`,  `stdcourse_custom2`,  `stdcourse_custom3`,  `course_season`) VALUES '.$values );
						

							
						if( mysqli_affected_rows( $GLOBALS['connect'] )>0 ) 
						{
							
							//$processed++;
							//Registered Semester and students_reg
							$chk = mysqli_query( $GLOBALS['connect'], 'SELECT 1 FROM registered_semester WHERE std_id = '.$v.' && ysession = "'.$n_sess.'" LIMIT 1');
							if( 0==mysqli_num_rows ($chk) ) {
								mysqli_query( $GLOBALS['connect'], 'INSERT INTO registered_semester(std_id, sem, ysession, rslevelid, season) VALUES ("'.$v.'","First Semester","'.$n_sess.'","'.$n_lv.'", "'.$season.'"), ("'.$v.'","Second Semester","'.$n_sess.'","'.$n_lv.'", "'.$season.'") ');								
							}
							
							$chk2 = mysqli_query( $GLOBALS['connect'], 'SELECT 1 FROM students_reg WHERE std_id ='.$v.' && yearsession = "'.$n_sess.'" && season = "'.$season.'"');
							if( 0==mysqli_num_rows ($chk2) ) {
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
		}
	
		
		//die();
	


	if( !empty($all_course_id) ) 
	{
		//echo "In<br>";
		
			
		$all_course_id = array_unique($all_course_id);
		
		//print_r($all_course_id);
		
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
		//echo "<hr>";
		//print_r($all_course_id);



		$course_status = 'E';
		$course_custom1 = 'YES';

		
		if( !empty($all_course_id) ) 
		{
			//echo "IN2<br>";

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
			
			/*echo 'INSERT INTO `all_courses` ( `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`,  `programme_id`,  `faculty_id`,  `department_id`,  `level_id`,  `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom5` ) VALUES '.$values."<hr>";*/

			$create_curricullum = mysqli_query( $GLOBALS['connect'], 'INSERT INTO `all_courses` ( `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`,  `programme_id`,  `faculty_id`,  `department_id`,  `level_id`,  `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom5` ) VALUES '.$values);
			if( mysqli_affected_rows( $GLOBALS['connect'] )>0 ) 
			{ 
				$processed++; 
			}
			//echo "PROCESSED:".$processed."<br>";
		}
		
		
	}
	
	
	header('HTTP/1.1 301 Moved Permanently');
	if( $processed=="0" )
	{
		header('Location: set__adv_1.php?i=1');
		
	}
	else
	{
		header('Location: set__adv_1.php?i=9');
		
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
	
	
	function get_probation_detail( $course, $l, $cs, $fos) {
	
	
		$ge = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM `all_probation_course` WHERE `thecourse_id` = '.$course.' && level_id = '.$l.' && `course_custom2` = '.$fos.' && `course_custom5` = "'.$cs.'"  LIMIT 1')or die(mysqli_error($GLOBALS['connect']));
		
      
		if( 0!=mysqli_num_rows($ge) ) 
		{
			$data = mysqli_fetch_assoc($ge);
			mysqli_free_result($ge);
			
			return $data;
		}
			return 0;

	}
	
	
	function get_profiledetail( $std ) {
		$ae = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM students_profile WHERE std_id = '.$std.' LIMIT 1');
		if( 0!=mysqli_num_rows ($ae) ) {
			$data = mysqli_fetch_assoc($ae);
			mysqli_free_result($ae);
			return $data;
		}
		return '';
	}
	
	
	function has_carryF( $s_id, $course_id_list, $fos ) {
		
		/* All this for Gss sake */
		$course_codes = array();
		$_ = mysqli_query( $GLOBALS['connect'], 'SELECT course_code, thecourse_id FROM all_courses WHERE thecourse_id IN ('.implode(',', $course_id_list).') && all_courses.course_custom2 = '.$fos.' GROUP BY thecourse_id' );
		if( 0!=mysqli_num_rows($_) ) {
			while( $d = mysqli_fetch_assoc($_) )
				$course_codes[ $d['thecourse_id'] ] = $d['course_code'];
			mysqli_free_result($_);
		}
		/* All this for Gss sake */
		
		$_ = mysqli_query( $GLOBALS['connect'], 'SELECT count(1) as nos, stdcourse_id FROM students_results WHERE std_id = '.$s_id.' && stdcourse_id IN ('.implode(',', $course_id_list).') && std_grade IN ( "F", "N" ) GROUP BY stdcourse_id' );
		if( 0!=mysqli_num_rows($_) ) {
			$has_carryF = array();
			while( $d=mysqli_fetch_assoc($_) ) {
				
				$check = strtolower( substr( $course_codes[ $d['stdcourse_id'] ], 0, 3) ) == 'gss' ? false : true;
				
				if( $d['nos'] >= 3 && $check )
					$has_carryF[] = $d['stdcourse_id'];
			
			}
			mysqli_free_result($_);
			return $has_carryF;
		}
		return false;
		
	}

?>