<?php
include_once'include_report.php';
function newCorrectional_Remarks($p, $f, $d, $l, $s, $s_id, $cgpa, $fos, $finalyear = false, $new=false,$fail_cu){




if(	$s<=2011 && $l>1 && $l<=6 || $s<=2012 && $l>1 && $l<=6 ||  $s== 2012 && $l==2 || $s== 2013 && $l==3 || $s== 2014 && $l==4 || $s== 2015 && $l==5 || $s== 2016 && $l==6){
	
	
	 	
	if( $cgpa < 0.75 )
		return 'WITHDRAW';
	elseif( $cgpa >= 0.75 && $cgpa < 1.00 )
		return 'PROBATION';
	}elseif($s== 2012 && $l==1|| $s >2012 && $l >=1 && $l<7 ){



		if($cgpa >=1.50 && $cgpa < 2.39 && $fail_cu >= 10 && $fail_cu <=15){
			return 'PROBATION';
		}elseif($cgpa >=1.00 && $cgpa < 1.5 && $fail_cu >= 10 && $fail_cu <=15 ){
		return 'WITHDRAW OR CHANGE PROGRAMME';
		}elseif($cgpa < 1.00){
		return 'WITHDRAW';
		}
		}

$return = '';
	$carryf = '';
	//$take = get_course_to_take_verREMARK($p, $f, $d, $l, $s, $s_id, $fos, $new);
	$repeat = get_repeat_courses_reloaded_NEW($l, $s, $s_id, $d, $fos);
	$repeat1 = get_repeat_courses_reloaded1_NEW($l, $s, $s_id, $d, $fos);
	
	if( !empty( $repeat1 ) ) 
	{
		
		foreach($repeat1 as $rep1)
		{
			
			if( $rep1['num_'] == 3 )
			{
				
				//$carryf .= substr_replace($rep1['code'],' ',3, 0).',';
			}
			else
			{
				$return .= substr_replace($rep1['code'],' ',3, 0).',';
			}
		}
		
		//$carryf = empty($carryf) ? '' : 'CARRY F '.$carryf." <br/>";
		$return = empty($return) ? '' : 'RPT '.$return;
		
	}
	if( !empty( $repeat ) ) 
	{
		if(!empty($return))
		{
			$return=str_replace("RPT","",$return);
		}
		
		foreach($repeat as $rep)
		{
			
			if( $rep['num_'] == 3 )
			{
				
				$carryf .= substr_replace($rep['code'],' ',3, 0).',';

			}
			else
			{
				$return .= substr_replace($rep['code'],' ',3, 0).',';
			}
		
		}
		$return=substr($return, 0,-1);
		
		$carryf = empty($carryf) ? '' : 'CARRY F '.$carryf." <br/>";
		$return = empty($return) ? '' : 'RPT '.$return;
					
	}

//	echo $carryf.":";		
		if(( $cgpa > 9.99 ) && $finalyear == true ) 
		{
			$return = "PASS <br/>".$carryf;
			$return = $carryf;
		} 
		
		if(!empty($return) || !empty($carryf))
		{
			$return = $carryf.$return;
			//echo "here".$return;
		}
	
		if(empty($take) && !empty($carryf) &&  empty($return)) 
		{
			//echo "here1";		
			return $cgpa > 0.99 ? "PASS </br> ".$carryf : '';
		}
		else if(empty($take) && $return=='') 
		{
			//echo "here2";
			return $cgpa > 0.99 ? "PASS": '';
		}
		else if( !empty($take) )
		$return .= !empty($return) ? " <br/> TAKE ".$take : "TAKE ".$take;
		
	unset( $sql, $l, $s, $s_id, $cgpa, $take, $a );
	return strtoupper($return);
}


function get_repeat_courses_reloaded_NEW($l, $s, $s_id, $dept, $fos, $vacation= false ) {

	$adv_ = array();
	$duration = get_course_duration( $fos );
	$sess_used_counter = get_count_session_used( $s_id, $l );
	$adv_ = help_repeat_courses_loader_spread_year( $duration, $sess_used_counter, $s, $l );
	$adv_ = implode( ',', $adv_ );
	

	$sql = 'SELECT `students_results`.stdcourse_id, `students_results`.std_grade, `course_reg`.clevel_id, `course_reg`.cyearsession, `course_reg`.stdcourse_custom2 FROM students_results, course_reg WHERE 
	`students_results`.std_id = `course_reg`.std_id && 
	`students_results`.std_mark_custom2 = `course_reg`.cyearsession && 
	`students_results`.stdcourse_id = `course_reg`.thecourse_id && 
	`students_results`.std_mark_custom2 IN ("'.$adv_.'") && 
	`students_results`.std_grade IN ("F","N") && `students_results`.std_mark >= 0 && 
	`students_results`.std_id = "'.$s_id.'" && 
	`students_results`.stdcourse_id NOT IN ( SELECT `students_results_backup`.stdcourse_id FROM `students_results_backup` WHERE `students_results_backup`.std_id="'.$s_id.'" && `students_results_backup`.std_grade != "F" && `students_results_backup`.std_grade != "N"&& `students_results_backup`.std_mark > 0 Order by null )
	UNION
	SELECT `students_results_backup`.stdcourse_id, `students_results_backup`.std_grade,`course_reg`.clevel_id, `students_results_backup`.std_mark_custom2,`course_reg`.stdcourse_custom2 FROM students_results_backup, course_reg WHERE
	`students_results_backup`.std_id = `course_reg`.std_id && 
	`students_results_backup`.std_mark_custom2 = `course_reg`.cyearsession && 
	`students_results_backup`.stdcourse_id = `course_reg`.thecourse_id && 
	`students_results_backup`.std_mark_custom2 IN ("'.$adv_.'") && 
	`students_results_backup`.std_grade IN ("F","N") &&`students_results_backup`.std_mark > 0
	 Order by cyearsession DESC';

$r = mysqli_query($GLOBALS['connect'],  $sql );
	if( mysqli_num_rows($r) > 0 ):
	echo "daniel!";
		
		$ignore = array();
		
		$inc = array();
		$resultier = array();

		while( $a = mysqli_fetch_assoc($r) ):



			if( $a['cyearsession'] == $s) { #No check happens to course if it does not exist in this year(e.g 2001) as failed
				$inc[ $a['stdcourse_id'] ] = array('code'=>$a['stdcourse_custom2'], 'num_'=>1, 'level'=>$a['clevel_id'] );
				continue;
			}
			
			if( isset( $inc[ $a['stdcourse_id'] ] ) ) {
				if( $a['clevel_id'] == $inc[ $a['stdcourse_id'] ]['level'] ) {}
				elseif( strtolower(substr($a['stdcourse_custom2'],0,3)) == 'gss' ){} # no need to check gss.. cos no carry F for them
				else {
					$inc[ $a['stdcourse_id'] ]['num_']++;
					$inc[ $a['stdcourse_id'] ]['level'] = $a['clevel_id'];
					$inc[ $a['stdcourse_id'] ]['std_grade'] = $a['std_grade'];
				}
			}

		endwhile;
						
	endif;
	mysqli_free_result($r);
	unset($sql, $s, $l, $s_id, $r, $adv_);
	return empty($inc) ? '' : $inc;
	
}
function get_repeat_courses_reloaded1_NEW($l, $s, $s_id, $dept, $fos,  $vacation= false ) {

	$adv_ = array();
	$duration = get_course_duration( $fos );
	$sess_used_counter = get_count_session_used( $s_id, $l );
	$adv_ = help_repeat_courses_loader_spread_year( $duration, $sess_used_counter, $s, $l );
	$adv_ = implode( ',', $adv_ );
	


	$sql = 'SELECT `students_results`.stdcourse_id, `students_results`.std_grade, `course_reg`.clevel_id, `course_reg`.cyearsession, `course_reg`.stdcourse_custom2 FROM students_results, course_reg WHERE 
	`students_results`.std_id = `course_reg`.std_id && 
	`students_results`.std_mark_custom2 = `course_reg`.cyearsession && 
	`students_results`.stdcourse_id = `course_reg`.thecourse_id && 
	`students_results`.std_mark_custom2 IN ("'.$adv_.'") && 
	`students_results`.std_grade IN ("F","N") && `students_results`.std_mark >= 0 && 
	`students_results`.std_id = "'.$s_id.'" && 
	`students_results`.stdcourse_id NOT IN ( SELECT `students_results_backup`.stdcourse_id FROM `students_results_backup` WHERE `students_results_backup`.std_id="'.$s_id.'" && `students_results_backup`.std_grade != "F" && `students_results_backup`.std_mark > 0 Order by null )
	UNION
	SELECT `students_results_backup`.stdcourse_id, `students_results_backup`.std_grade,`course_reg`.clevel_id, `students_results_backup`.std_mark_custom2,`course_reg`.stdcourse_custom2 FROM students_results_backup, course_reg WHERE
	`students_results_backup`.std_id = `course_reg`.std_id && 
	`students_results_backup`.std_mark_custom2 = `course_reg`.cyearsession && 
	`students_results_backup`.stdcourse_id = `course_reg`.thecourse_id && 
	`students_results_backup`.std_mark_custom2 IN ("'.$adv_.'") && 
	`students_results_backup`.std_grade IN ("F","N") && `students_results_backup`.std_mark > 0
	 Order by cyearsession DESC';


$r = mysqli_query($GLOBALS['connect'],  $sql );
	if( mysqli_num_rows($r) > 0 ):
		
		$return = '';
		$ignore = array();
		
		$inc = array();
		$resultier = array();

		while( $a = mysqli_fetch_assoc($r) ):



			if( $a['cyearsession'] == $s) { #No check happens to course if it does not exist in this year(e.g 2001) as failed
				$inc[ $a['stdcourse_id'] ] = array('code'=>$a['stdcourse_custom2'], 'num_'=>1, 'level'=>$a['clevel_id'] );
				continue;
			}
			
			if( isset( $inc[ $a['stdcourse_id'] ] ) ) {
				if( $a['clevel_id'] == $inc[ $a['stdcourse_id'] ]['level'] ) {}
				elseif( strtolower(substr($a['stdcourse_custom2'],0,3)) == 'gss' ){} # no need to check gss.. cos no carry F for them
				else {
					$inc[ $a['stdcourse_id'] ]['num_']++;
					$inc[ $a['stdcourse_id'] ]['level'] = $a['clevel_id'];
					$inc[ $a['stdcourse_id'] ]['std_grade'] = $a['std_grade'];
				}
			}

		endwhile;
						
	endif;mysqli_free_result($r);
	unset($sql, $s, $l, $s_id, $r, $adv_);
	return empty($inc) ? '' : $inc;
	
}


	
?>