<?php

define('vacation', 'VACATION');	
	

function fetch_courses( $d, $p, $l, $sem, $s, $f, $fos, $season='NORMAL' ){


	$sql = 'SELECT all_courses.thecourse_id, all_courses.course_unit, all_courses.course_code as stdcourse_custom2 FROM all_courses WHERE all_courses.level_id = '.$l.' &&
	all_courses.course_semester = "'.$sem.'" &&
	all_courses.programme_id = '.$p.' &&
	all_courses.faculty_id = '.$f.' &&
	all_courses.department_id = '.$d.' &&
	all_courses.course_status = "C" &&
	all_courses.course_custom5 = "'.$s.'" &&
	all_courses.course_custom2 = '.$fos.'
	ORDER BY all_courses.course_code DESC';
//	echo $sql;

	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$result = array();
	while( $a = mysqli_fetch_assoc($r) ){
		$result[] = $a;
	}
	
	mysqli_free_result($r);
	unset($d,$p,$l,$c,$f,$r,$a);
	return $result;	

}

function get_fake_chrx( $sem, $rpt_list, $carryov_list, $s, $std ) {
	
	$to_go = array();
	//$merger = array_merge($rpt_list, $carryov_list);
	
	if( !empty($merger) ) {
		foreach( $merger as $on ) {
			$g = substr($on, 0, 1);
			if( $g == $sem )
				$to_go[] = substr($on, 2);
		}

		
	$sql = 'SELECT `students_results`.std_grade, `students_results`.cu, `students_results`.std_mark_custom2,`all_courses`.course_code, `all_courses`.thecourse_id FROM students_results, all_courses WHERE `students_results`.stdcourse_id=`all_courses`.thecourse_id && `all_courses`.course_code IN (\''.implode("','", $to_go).'\') && `students_results`.std_mark_custom2 = '.$s.' && `students_results`.std_id = '.$std.' && `students_results`.period != "'.vacation.'" GROUP BY course_code';

	
	$r = mysqli_query($GLOBALS['connect'],  $sql ) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	$return = '';
	if( mysqli_num_rows($r) > 0 ){
		while( $g = mysqli_fetch_array($r) )
			$return .= " ". $g['cu'].$g['course_code'].$g['std_grade'];

	}
		
		$return = $return;
		//echo strtoupper($return);
	}
		//echo "";
return strtoupper($return);
	
}


function fetch_vacation_courses($d, $p, $l, $sem, $s, $f, $fos) {
$vac="vacation";

	$sql = 'SELECT distinct all_courses.thecourse_id, all_courses.course_unit, all_courses.course_code as stdcourse_custom2 FROM all_courses INNER JOIN course_reg using (thecourse_id) WHERE 

	all_courses.level_id = course_reg.clevel_id &&
	all_courses.course_semester = course_reg.csemester &&
	course_reg.course_season = "'.$vac.'" &&
	all_courses.level_id = '.$l.' &&
	all_courses.course_semester = "'.$sem.'" &&
	all_courses.programme_id = '.$p.' &&
	all_courses.faculty_id = '.$f.' &&
	all_courses.department_id = '.$d.' &&
	all_courses.course_custom5 = "'.$s.'" &&
	all_courses.course_custom2 = '.$fos.'
	ORDER BY all_courses.course_code DESC';

	//echo $sql;


	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$result = array();
	while( $a = mysqli_fetch_assoc($r) )
	{
		$result[] = $a;

	}
	mysqli_free_result($r);
	unset($d,$p,$l,$c,$f,$r,$a);
	return $result;	
	
}




function fetch_student_matric( $d, $p, $l, $f, $s ){
	
$sql = 'SELECT DISTINCT students_results.std_id, students_results.std_grade,students_results.stdcourse_id, students_reg.std_id, students_results.matric_no, students_reg.matric_no
FROM students_reg,students_results
WHERE students_reg.std_id = students_results.std_id AND
    students_reg.programme_id = '.$p.' AND
    students_reg.faculty_id = '.$f.' AND
    students_reg.department_id = '.$d.' AND
    students_reg.level_id = '.$l.' AND students_results.std_mark_custom2 = '.$s.' AND
    students_reg.yearsession = '.$s.' AND
    (students_results.matric_no != " " OR
    students_reg.matric_no != " ")
GROUP BY students_results.std_id,students_reg.std_id
ORDER BY students_reg.matric_no,students_results.matric_no';


	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$result = array();
	while( $a = mysqli_fetch_assoc($r) ){
		$result[] = $a;
	}
	mysqli_free_result($r);
	unset($d,$p,$l,$c,$f,$r,$a);
	return $result;

}

function fetch_Ireg($std, $s, $l) {
	
	$ls = array();
	$sql = 'SELECT thecourse_id FROM course_reg WHERE std_id = '.$std.' && clevel_id = '.$l.' && cyearsession = "'.$s.'"';
	$run = mysqli_query($GLOBALS['connect'], $sql);
	if( 0!=mysqli_num_rows($run) ) {
		while( $data = mysqli_fetch_assoc($run) ) {
			$ls[] = $data['thecourse_id'];
		}
		mysqli_free_result($run);
		return $ls;
	}
	return '';
	
}


function fetch_student_RESULT($sid, $arr, $s , $sid_coursereg=false) {
	
	if( empty($arr) )
		return array();
	
	$return = array();	
	
	$sql = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results WHERE std_id = '.$sid.' and stdcourse_id IN ('.implode(',', $arr).') && std_mark_custom2='.$s.' && period != "'.vacation.'"';
	
	
	$r = mysqli_query( $GLOBALS['connect'], $sql );
	$all = array();
	if( 0!=mysqli_num_rows($r) ) {
		while( $data=mysqli_fetch_assoc($r) ) {
			$all[ $data['stdcourse_id'] ] = $data;
		}
		mysqli_free_result($r);
	}
	
	
	/*coursereg*/
		$creglist = array();
		$_ = mysqli_query($GLOBALS['connect'], 'SELECT thecourse_id FROM course_reg WHERE std_id = '.$sid.' && cyearsession = "'.$s.'" && course_season != "'.vacation.'"' );
		if( 0!=mysqli_num_rows($_) ) {
			while( $data=mysqli_fetch_assoc($_) ) {
				$creglist[] = $data['thecourse_id'];
			}
			mysqli_free_result($_);
		}
	/*coursereg*/
	
	
	$keys = array_keys($all);
	foreach( $arr as $k=>$v ) {

		if( in_array($v, $keys) ) {
			if( empty($all[$v]['std_mark']) || $all[$v]['std_mark']==0 ) {
				$result[] = array( 'std_mark'=>$all[$v]['std_mark'], 'std_grade'=>'NR' );
			} else {
				$result[] = array( 'std_mark'=>$all[$v]['std_mark'], 'std_grade'=>$all[$v]['std_grade'] );
			}
		} else {
			if( in_array($v, $creglist) )
				$result[] = array('std_mark'=>'', 'std_grade'=>'&nbsp;&nbsp;');
			else
				$result[] = array('std_mark'=>'', 'std_grade'=>'');
		}
	}
	
	unset($d,$p,$l,$c,$f,$r,$a,$cc);
	return $result;
	
}

function fetch_student_RESULT_corr($sid, $arr, $s , $sid_coursereg=false) {
	
	if( empty($arr) )
		return array();
	
	$return = array();	
	
	$sql = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results WHERE std_id = '.$sid.' and stdcourse_id IN ('.implode(',', $arr).') && std_mark_custom2='.$s.' && period != "'.vacation.'"';
	
	$r = mysqli_query( $GLOBALS['connect'], $sql );
	$all = array();
	
	
	
	if( 0!=mysqli_num_rows($r) ) {
		while( $data=mysqli_fetch_assoc($r) ) {
			$sql_c = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results_backup WHERE std_id = '.$sid.' and stdcourse_id IN ('.$data['stdcourse_id'].') && std_mark_custom2='.$s.' && period != "'.vacation.'"';
			$r_c = mysqli_query( $GLOBALS['connect'], $sql_c );
			if( 0!=mysqli_num_rows($r_c) ) {
				while( $data_c=mysqli_fetch_assoc($r_c) ) {
					$all[ $data_c['stdcourse_id'] ] = $data_c;
				}
			} else {
			//mysqli_free_result($r_c);
				$all[ $data['stdcourse_id'] ] = $data;
			}
		}
		mysqli_free_result($r);
	}
	//??????????????????????????????
	//$sql_c = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results_backup WHERE std_id = '.$sid.' and stdcourse_id IN ('.implode(',', $arr).') && std_mark_custom2='.$s.' && period != "'.vacation.'"';
	
	
	//$r_c = mysqli_query( $GLOBALS['connect'], $sql_c );
	//$all_c = array();
	//if( 0!=mysqli_num_rows($r_c) ) {
	//	while( $data_c=mysqli_fetch_assoc($r_c) ) {
	//		$all_c[ $data_c['stdcourse_id'] ] = $data_c;
//		}
//		mysqli_free_result($r_c);
//	}
	
	
	/*coursereg*/
		$creglist = array();
		$_ = mysqli_query($GLOBALS['connect'], 'SELECT thecourse_id FROM course_reg WHERE std_id = '.$sid.' && cyearsession = "'.$s.'" && course_season != "'.vacation.'"' );
		if( 0!=mysqli_num_rows($_) ) {
			while( $data=mysqli_fetch_assoc($_) ) {
				$creglist[] = $data['thecourse_id'];
			}
			mysqli_free_result($_);
		}
	/*coursereg*/
	
	
	$keys = array_keys($all);
	foreach( $arr as $k=>$v ) {

		if( in_array($v, $keys) ) {
			if( empty($all[$v]['std_mark']) || $all[$v]['std_mark']==0 ) {
				$result[] = array( 'std_mark'=>$all[$v]['std_mark'], 'std_grade'=>'NR' );
			} else {
				
				//if ($all[$v]['std_grade'] != $all_c[$v]['std_grade']) {
				//	$result[] = array( 'std_mark'=>$all_c[$v]['std_mark'], 'std_grade'=>$all_c[$v]['std_grade'] );
				//} else {
					$result[] = array( 'std_mark'=>$all[$v]['std_mark'], 'std_grade'=>$all[$v]['std_grade'] );
				//}
				
			}
		} else {
			if( in_array($v, $creglist) )
				$result[] = array('std_mark'=>'', 'std_grade'=>'&nbsp;&nbsp;');
			else
				$result[] = array('std_mark'=>'', 'std_grade'=>'');
		}
	}
	
	unset($d,$p,$l,$c,$f,$r,$a,$cc);
	return $result;
	
}


function fetch_student_RESULT_resit($sid, $arr, $s , $sid_coursereg=false) {
	
	if( empty($arr) )
		return array();
	
	$return = array();	
	
	$sql = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results WHERE std_id = '.$sid.' and stdcourse_id IN ('.implode(',', $arr).') && std_mark_custom2='.$s.' && period != "'.vacation.'"';
	
	$r = mysqli_query( $GLOBALS['connect'], $sql );
	$all = array();
	
	
	
	if( 0!=mysqli_num_rows($r) ) {
		while( $data=mysqli_fetch_assoc($r) ) {
			$sql_c = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results_backup WHERE std_id = '.$sid.' and stdcourse_id IN ('.$data['stdcourse_id'].') && std_mark_custom2='.$s.' && period != "'.vacation.'" ORDER BY stdresult_id DESC';
			$r_c = mysqli_query( $GLOBALS['connect'], $sql_c );
			if( 0!=mysqli_num_rows($r_c) ) {
				while( $data_c=mysqli_fetch_assoc($r_c) ) {
					$all[ $data_c['stdcourse_id'] ] = $data_c;
				}
			} else {
			//mysqli_free_result($r_c);
				$all[ $data['stdcourse_id'] ] = $data;
			}
		}
		mysqli_free_result($r);
	}
	//??????????????????????????????
	//$sql_c = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results_backup WHERE std_id = '.$sid.' and stdcourse_id IN ('.implode(',', $arr).') && std_mark_custom2='.$s.' && period != "'.vacation.'"';
	
	
	//$r_c = mysqli_query( $GLOBALS['connect'], $sql_c );
	//$all_c = array();
	//if( 0!=mysqli_num_rows($r_c) ) {
	//	while( $data_c=mysqli_fetch_assoc($r_c) ) {
	//		$all_c[ $data_c['stdcourse_id'] ] = $data_c;
//		}
//		mysqli_free_result($r_c);
//	}
	
	
	/*coursereg*/
		$creglist = array();
		$_ = mysqli_query($GLOBALS['connect'], 'SELECT thecourse_id FROM course_reg WHERE std_id = '.$sid.' && cyearsession = "'.$s.'" && course_season != "'.vacation.'"' );
		if( 0!=mysqli_num_rows($_) ) {
			while( $data=mysqli_fetch_assoc($_) ) {
				$creglist[] = $data['thecourse_id'];
			}
			mysqli_free_result($_);
		}
	/*coursereg*/
	
	
	$keys = array_keys($all);
	foreach( $arr as $k=>$v ) {

		if( in_array($v, $keys) ) {
			if( empty($all[$v]['std_mark']) || $all[$v]['std_mark']==0 ) {
				$result[] = array( 'std_mark'=>$all[$v]['std_mark'], 'std_grade'=>'NR' );
			} else {
				
				//if ($all[$v]['std_grade'] != $all_c[$v]['std_grade']) {
				//	$result[] = array( 'std_mark'=>$all_c[$v]['std_mark'], 'std_grade'=>$all_c[$v]['std_grade'] );
				//} else {
					$result[] = array( 'std_mark'=>$all[$v]['std_mark'], 'std_grade'=>$all[$v]['std_grade'] );
				//}
				
			}
		} else {
			if( in_array($v, $creglist) )
				$result[] = array('std_mark'=>'', 'std_grade'=>'&nbsp;&nbsp;');
			else
				$result[] = array('std_mark'=>'', 'std_grade'=>'');
		}
	}
	
	unset($d,$p,$l,$c,$f,$r,$a,$cc);
	return $result;
	
}


function spillover_level( $s_id, $l ) {
	
	$run = mysqli_query($GLOBALS['connect'], 'SELECT rslevelid, count(ysession) as seen FROM registered_semester WHERE std_id = "'.$s_id.'" && rslevelid='.$l.' && sem IN (\'First Semester\', \'Second Semester\') GROUP BY rslevelid HAVING seen > 2');
	
	$res = mysqli_fetch_assoc($run);
	if( $res['seen'] == 2 )
		return false; #Means - No Spillover for student
	
	return floor($res['seen']/3);

}



/*** Original ***/
function std_result_chker( $list=array(), $s, $type=false, $duration = 4 ) {
	
	$slist = array();
	$olist = array();
	$dlist = array();
	
	foreach( $list as $k=>$v ) {
		$slist[ $k ] = $v['std_id'];
		$olist[ $k ] = $v;
	}
	
	switch ( $type ) {
		case 'spillover':
		   
		  $sql = 'SELECT DISTINCT `students_results`.std_id FROM `students_results` WHERE `students_results`.std_mark_custom3 = 0 && `students_results`.std_mark_custom2 = '.$s.'&& std_id IN ( SELECT `students_results`.std_id FROM `students_results` WHERE 
level_id = '.$duration.' && `students_results`.std_mark_custom2 IN ('.implode(',', range(($s-2), ($s-1))).') && `students_results`.std_mark_custom3 = 0 )';

		break;
		case 'regular':
			
		  $sql = 'SELECT `students_results`.std_id FROM `students_results` WHERE `students_results`.std_mark_custom3 = 0 && `students_results`.std_mark_custom2 = '.$s.'&& std_id NOT IN ( SELECT `students_results`.std_id FROM `students_results` WHERE 
level_id = '.$duration.' && `students_results`.std_mark_custom2 IN ('.implode(',', range(($s-2), ($s-1))).') && `students_results`.std_mark_custom3 = 0 )';	
		
		break;
		default:
		  
		  $sql = 'select DISTINCT std_id from `students_results` where `students_results`.`std_mark_custom2` = '.$s.' && `students_results`.`std_mark_custom3` = 0 && std_id IN ('.implode(',', $slist).')';

		break;
	}

	
	$init = mysqli_query( $GLOBALS['connect'],  $sql ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
	
	if( mysqli_num_rows($init)>0 ) {
		while( $f=mysqli_fetch_assoc($init) ) {
			$dlist[] = $f['std_id'];
		}
	}
	
	foreach( $olist as $kk=>$vv ) {
		if( !in_array( $vv['std_id'], $dlist) ){
			unset($olist[$kk]);
		}
	}
	
	return $olist;
	
}



function get_levelreps() {
	
	$ae = mysqli_query( $GLOBALS['connect'], 'SELECT level_id, level_name, programme_id FROM level' );
	if( 0 != mysqli_num_rows($ae) ) {
		$result = array();
		while( $d=mysqli_fetch_assoc($ae) ) {
			switch( $d['programme_id'] ) {
				case '2':
					$result[ $d['level_id'] ] = substr($d['level_name'], 0, 1);
				break;
				case '1':
					$result[ $d['level_id'] ] = substr($d['level_name'], 0, 1);
				break;
				case '7':
					$result[ $d['level_id'] ] = substr($d['level_name'], 0, 1);
				break;
			}
		}
		mysqli_free_result($ae);
		return $result;
	}
	return false;
	
}



function fetch_student_mat( $d, $p, $l, $f, $s, $fos, $special = false, $duration ){
	
	#features exploration
	if( $special ) {
		switch( $special ) {
			case 'spillover':

				$sql = 'SELECT DISTINCT sr.std_id, sp.matric_no, sr.level_id, sp.surname, sp.firstname, sp.othernames
FROM students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.department_id = sp.stddepartment_id && sr.yearsession="'.$s.'" && sr.programme_id='.$p.' && sr.faculty_id='.$f.' && sr.department_id='.$d.' && sr.level_id='.$l.' && sp.stdcourse = '.$fos.' ORDER BY sp.matric_no, sp.surname ASC';

			break;
			case 'regular':
			
			$sql = 'SELECT DISTINCT sr.std_id, sp.matric_no, sr.level_id, sp.surname, sp.firstname, sp.othernames
FROM students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.department_id = sp.stddepartment_id && sr.yearsession="'.$s.'" && sr.programme_id='.$p.' && sr.faculty_id='.$f.' && sr.department_id='.$d.' && sr.level_id='.$duration.' && sp.stdcourse = '.$fos.' ORDER BY sp.matric_no, sp.surname ASC';	//echo $sql;		
			break;
			default:
			$sql = 'SELECT DISTINCT sr.std_id, sp.matric_no, sr.level_id, sp.surname, sp.firstname, sp.othernames
FROM students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.department_id = sp.stddepartment_id && sr.yearsession="'.$s.'" && sr.programme_id='.$p.' && sr.faculty_id='.$f.' && sr.department_id='.$d.' && sr.level_id='.$l.' && sp.stdcourse = '.$fos.' ORDER BY sp.matric_no, sp.surname ASC';
		}
	} 
	else 
	{

				$sql = 'SELECT DISTINCT sr.std_id, sp.matric_no, sr.level_id, sp.surname, sp.firstname, sp.othernames
FROM students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.department_id = sp.stddepartment_id && sr.yearsession="'.$s.'" && sr.programme_id='.$p.' && sr.faculty_id='.$f.' && sr.department_id='.$d.' && sr.level_id='.$l.' && sp.stdcourse = '.$fos.' ORDER BY sp.matric_no, sp.surname ASC';

	}
	
	//echo $sql."<br>";
//exit('Bye');
	$result = array();
	$r = mysqli_query($GLOBALS['connect'],  $sql ) or die('App Command Ended: No Students Found - ERROR'.mysql_error());
	while( $a = mysqli_fetch_assoc($r) ){
		$result[] = $a;
	}
	mysqli_free_result($r);
	unset($d,$p,$l,$c,$f,$r,$a);
	if( empty($result) ) {
		exit('Report Sheet: No Students Found');
	}

	$result = std_result_chker( $result, $s, $special, $duration );
	return $result;
	
}



function fetch_student_mat_vacation( $d, $p, $l, $f, $s, $fos ){
	
$sql = 'SELECT DISTINCT sr.std_id, sp.matric_no, sr.level_id, sp.surname, sp.firstname, sp.othernames
FROM students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.department_id = sp.stddepartment_id && sr.yearsession="'.$s.'" && sr.programme_id='.$p.' && sr.faculty_id='.$f.' && sr.department_id='.$d.' && sr.level_id='.$l.' && sp.stdcourse = '.$fos.' && sr.season = "'.vacation.'" ORDER BY sp.matric_no, sp.surname ASC';	

	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$result = array();
	while( $a = mysqli_fetch_assoc($r) ){
		$result[] = $a;
	}
	mysqli_free_result($r);
	unset($d,$p,$l,$c,$f,$r,$a);
	return $result;

}




function vacation_carrytodo( $sid, $l, $s ){
 
	$r = mysqli_query($GLOBALS['connect'],  'SELECT `course_reg`.stdcourse_custom2 FROM `course_reg` WHERE `course_reg`.course_season = "'.vacation.'"
 && `course_reg`.std_id = '.$sid.'
 && `course_reg`.clevel_id = '.$l.'
 && `course_reg`.cyearsession = '.$s.'' ) or die( mysqli_error( $GLOBALS['connect'] ) );
	
	 while( $a = mysqli_fetch_assoc($r) ) {
		echo substr_replace($a['stdcourse_custom2'],' ',3, 0).'<br/>';
	 }
	 mysqli_free_result($r);
	 unset($r, $result, $a);
	
}




function surface_arr_search($needle, $arr){
	
	$arr2 = array();
	foreach( $arr as $k=>$v ) {
		$arr2[] = $k;
	}
	unset($k, $arr);
	$key = array_search($needle, $arr2);
	return !empty( $arr2[$key] ) ? $arr2[ $key ] : 0;

}




function get_remarks_ver_VACATION($l, $s, $s_id, $d, $fos) 
{
	
	$repeat = get_repeat_courses_reloaded($l, $s, $s_id, $d, $fos, true);  //  my code below
	//$repeat = get_repeat_courses_reloaded($l, $s, $s_id, $d, $fos, false, true);
	
    $query = "SELECT `course_reg`.clevel_id, `course_reg`.thecourse_id, `course_reg`.stdcourse_custom2, `students_results`.std_mark, `students_results`.std_grade FROM `students_results`, `course_reg`
WHERE `course_reg`.std_id = `students_results`.std_id
&& `course_reg`.thecourse_id = `students_results`.stdcourse_id
&& `course_reg`.course_season = `students_results`.period
&& `course_reg`.course_season in ('vacation')
&& `course_reg`.std_id = '".$s_id."'";
//	echo $query;
	
	$rpt = '';
	$carryf = '';
	/*
	foreach($repeat as $rep1)
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
		
	*/
	$r = mysqli_query($GLOBALS['connect'],  $query );
	$x=0;
	while( $f = mysqli_fetch_assoc($r) )
	{
	//echo "C";
		if( $f['std_grade'] == 'F' && !empty( $f['std_mark'] ) ) 
		{
		//echo "here $x<br>";
			$x++;
			if($x==2)
			{
				//echo "2<br>";
			}
			if( !empty($repeat) ) 
			{
				
				$searchkey = surface_arr_search( $f['stdcourse_custom2'], $repeat );
				if( !empty( $searchkey ) ) 
				{
					echo $repeat[ $searchkey ]['num_'];
					 
					if( $repeat[ $searchkey ]['num_'] === 3 ) 
					{
						$carryf .= $f['stdcourse_custom2'];
						//echo $carryf."Carri";
					} 
					else 
					{
						$rpt .= $f['stdcourse_custom2'];
					}
				} 
				else 
				{
					$rpt .= $f['stdcourse_custom2'];
				}
			} 
			else 
			{
				$rpt .= $f['stdcourse_custom2'];
			}
		}
	}
	
	$return = '';
	$return = !empty( $carryf ) ? 'CARRY F '.$carryf.'<br/>' : '';
	$return .= !empty( $rpt ) ? 'RPT '.$rpt : '';
	
	$return = empty( $return ) ? 'PASS' : $return;
	
	unset( $carryf, $rpt, $query, $repeat );
	return $return;

}















function get_course_to_take($p, $f, $d, $l, $s, $s_id){

	$fetch = get_fake_carry_courses_loader($l, $s, $p, $f, $d, $s_id, $fos);
	if( !empty($fetch) ){
		foreach($fetch as $f){
			$result .= $f.',';
		}
		$result = substr($result, 0, -1);
		return $result;
	}

}



function get_course_to_take_verREMARK($p, $f, $d, $l, $s, $s_id, $fos, $new=false){
	$result = '';
	$fetch = get_fake_carry_courses_loader_verREMARK($l, $s, $p, $f, $d, $s_id, $fos, $new);
	if( !empty($fetch) ){
		foreach($fetch as $f){
			$result .= substr_replace($f,' ',3, 0).',';
		}
		$result = strtoupper(substr($result, 0, -1));
		return $result;
	}

	
}



function get_fake_carry_courses_loader($l, $s, $p, $f, $d, $s_id, $fos, $vacation = 'NORMAL'){

	$sess_count_used = get_count_session_used( $s_id, $l );

	#reason this gets the exact number of registration year this std has done = exact number of years used in school! so -1 to allow math work perfectly
	$sess_count_used = $sess_count_used-1; 
	
	//if( $l == 2 )
		//$adv__ = 1; #reason: carryover is to show previous year carryover course and cos l == 2 prv year is automagically 1
		#$adv__ = implode( ',', range( ($l-1), $l ) );
	if( $l > 1 ) 
	{
		$sl = $l - 1;
		$adv__ = implode( ',', range( ($l-$sl), ($l-1) ) ); #reason of ($l-1) to loop upto previous study year - unical style of exams report
	} else
		$adv__ = $l; #useless! reason: because there is no previous year in year 1;
		
	
	$adv_ = range( ($s-$sess_count_used), ($s-1) ); #reason: jst like ($l-1) above,loop upto previous year of study only in real years e.g(2001)
	$adv_ = implode( ',', $adv_ );
	
	
	//displays carryover only if course result exists in current year else hides course

$adc1=$s;
if($vacation == "NORMAL")
{
	$adc=($s-1);
	$li=($l-1);
}
else if($vacation == "VACATION")
{
	$adc=$s;
	$vacation="NORMAL";
	$li=$l;
}
//echo $adc;

//$sql = 'SELECT course_code, course_unit FROM all_courses WHERE programme_id = '.$p.' && faculty_id = '.$f.' && department_id = '.$d.' && level_id IN ('.$adv__.') && course_custom2 = '.$fos.' && course_custom5 IN ('.$adv_.') && thecourse_id NOT IN ( SELECT `course_reg`.thecourse_id FROM course_reg WHERE `course_reg`.std_id = '.$s_id.' && `course_reg`.cyearsession IN ('.$adv_.') && course_season = "'.$vacation.'" ) && thecourse_id IN ( SELECT stdcourse_id FROM students_results WHERE std_id = '.$s_id.' && std_mark_custom2 = '.$s.' && std_mark > 0 && period = "'.$vacation.'") GROUP BY course_code ORDER BY course_custom5 DESC';
//$sql = 'SELECT course_code, course_unit FROM all_courses WHERE programme_id = '.$p.' && faculty_id = '.$f.' && department_id = '.$d.' && level_id IN ('.$l.') && course_custom2 = '.$fos.' && course_custom5 IN ('.$adv_.')  && thecourse_id not IN ( SELECT stdcourse_id FROM students_results WHERE std_id = '.$s_id.' && std_mark_custom2 ='.$adc.' && std_mark > 0 && period = "'.$vacation.'") GROUP BY course_code ORDER BY course_custom5 DESC';
$sql = 'SELECT course_code, course_unit FROM all_courses WHERE all_courses.level_id = '.$li.' &&
	all_courses.course_semester in ("first semester","second semester") &&
	all_courses.programme_id = '.$p.' &&
	all_courses.faculty_id = '.$f.' &&
	all_courses.department_id = '.$d.' &&
	all_courses.course_status = "C" &&
	all_courses.course_custom5 = "'.$adc.'" &&
	all_courses.course_custom2 = '.$fos.' && thecourse_id NOT IN ( SELECT `course_reg`.thecourse_id FROM course_reg WHERE `course_reg`.std_id = '.$s_id.' && `course_reg`.cyearsession IN ('.$adc.') && course_season = "'.$vacation.'" ) && 
	thecourse_id NOT IN ( SELECT stdcourse_id FROM students_results WHERE std_id ='.$s_id.' && std_mark_custom2 < '.$s.' )'; // here is my code line

//echo $sql;

	$r = mysqli_query($GLOBALS['connect'],  $sql ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	if( mysqli_num_rows($r) > 0 ){
		$result = array();
		while( $a = mysqli_fetch_assoc($r) ) {
			$result[] = $a['course_code'];//."&nbsp;($a[course_unit])";
		}
		return $result;
	} else
		return 0;
	
}



function get_fake_carry_courses_loader_verREMARK($l, $s, $p, $f, $d, $s_id, $fos, $new=false){
	
	$smart_sess = get_smart_sess_used($s_id, $l);
		
	$max_sess = $s;
	$xql = '';
	foreach( $smart_sess as $level=>$sess ) {
		$xql .= ' SELECT `all_courses`.`thecourse_id`,`all_courses`.`course_code` FROM all_courses WHERE programme_id = '.$p.' && faculty_id = '.$f.' && department_id = '.$d.' && level_id = "'.$level.'" && `all_courses`.course_status = "C" && `all_courses`.course_custom2 = '.$fos.' && `all_courses`.course_custom5 = "'.$sess.'" && `all_courses`.`thecourse_id` NOT IN ( SELECT `course_reg`.thecourse_id FROM course_reg WHERE `course_reg`.std_id = '.$s_id.' && `course_reg`.cyearsession <= "'.$max_sess.'" ) UNION';
	}
	
	$xql = substr($xql, 0, -6);
	
	if( true === $new ) {
	
		$plus = 'SELECT course_reg.thecourse_id,course_reg.stdcourse_custom2 as course_code FROM course_reg WHERE course_reg.thecourse_id NOT IN ( SELECT stdcourse_id FROM students_results WHERE students_results.std_id = '.$s_id.' && students_results.std_mark_custom2 <= "'.$max_sess.'" ) && course_reg.std_id = '.$s_id.' && course_reg.cyearsession <= "'.$max_sess.'"';
		
		$xql .= 'UNION '.$plus;
	
	}
	

	$r = mysqli_query($GLOBALS['connect'],  $xql );
	if( mysqli_num_rows($r) > 0 ){
		$result = array();
		while( $a = mysqli_fetch_assoc($r) ) {
			$result[] = $a['course_code'];
		}
		return $result;
	} else
		return 0;
	
}


#this function Note that its to be used under the final year cos it can't diff btw prob and spill over
function probation_exists( $s_id ) {
	
	$i = mysqli_query($GLOBALS['connect'], 'SELECT rslevelid, count(ysession) as seen FROM registered_semester WHERE std_id = "'.$s_id.'" && sem IN (\'First Semester\', \'Second Semester\') && season = "NORMAL" GROUP BY rslevelid HAVING seen > 2');
	if( mysqli_num_rows($i)>0 ) {
		mysqli_free_result($i);
		return true;
	}
	return false;
	
}


function auto_cgpa( $s, $s_id, $l, $duration, $year_of_study ) {
	
	$info = get_count_session_used( $s_id );
	if( $l < $duration ) {
		
		$probation_found = probation_exists( $s_id );
		if( $probation_found )
			return adv_get_cgpa($s, $s_id, $l, 'DESC');
		else
			return get_cgpa($s, $s_id);		
		
	} elseif( $info == $duration ) {
		//final year std who has had no probation
		return get_cgpa($s, $s_id);
	} else {
		//helpiing with final year + spill over cgpa calc
		$yr = substr($year_of_study,0,1);
		$calc = $yr - $duration;
		$magic_s = ($calc == 0) ? $s : $s-$calc;
		
		//return adv_get_cgpa_xlus($magic_s, $s_id, $l, 'ASC', $duration, ($duration+$calc) );
		return get_cgpa($s, $s_id);
	}
	
}


function adv_get_cgpa($s, $s_id, $l, $sort='DESC') {
	
	$cu = 0;
	$cp = 0;
	$ct = 0;
	$count = count( range(1, $l) );
	for( $i=1; $i<=$count; $i++ ):

		$sql = 'SELECT * FROM ( SELECT * FROM students_results WHERE level_id = '.$i.' && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && std_mark_custom2 <= '.$s.' && period = "NORMAL" ORDER BY std_mark_custom2 '.$sort.' ) as resultff GROUP BY stdcourse_id ORDER BY null';

		$run = mysqli_query($GLOBALS['connect'],  $sql );
		if( mysqli_num_rows($run)>0 ) {

			while( $v = mysqli_fetch_assoc($run) ) {
				$ct++;
				$cu += $v['cu'];
				$cp += $v['cp'];
			}
			mysqli_free_result($run);
		}
		
	endfor;
	
	if( $cu == 0 ) {
		return 'Credit Unit Is Zero';
	} else {
		$result = round(($cp / $cu),2);
		return number_format($result,2);
	}
}




function adv_get_cgpa_xlus($s, $s_id, $l, $sort='ASC', $ignore=4, $level=4, $vacation=false) {
	
	#Default: year 4 is final year $ignore=4
	#Note: try to always put the right year 4 year inside $s - to allow better calc
	$cu = 0;
	$cp = 0;

	$count = count( range(1, $l) );
	//var_dump( $vacation );
	for( $i=1; $i<=$count; $i++ ):
		
		if( $i == $ignore ) {
			$stage = $level - $ignore;

			$ext = $vacation ? 'period IN ("NORMAL", "VACATION")' : 'period = "NORMAL"';
			#Cares For: spill-over & normal
			$sql = 'SELECT * FROM students_results WHERE std_id = "'.$s_id.'" && level_id != 0 && std_mark_custom2 IN (\''.implode( "','", range($s, $s+$stage) ).'\') && std_grade != "N" && std_cstatus = "yes" && '.$ext.'';

		} else {

			$run = mysqli_query($GLOBALS['connect'],  'SELECT count( DISTINCT ysession ) as kount FROM registered_semester WHERE 
								 std_id = "'.$s_id.'" && rslevelid = "'.$i.'" && sem IN ("First Semester", "Second Semester")' ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
			$res = mysqli_fetch_assoc( $run );
			mysqli_free_result($run);
			$sort = $res['kount'] > 1 ? 'DESC' : 'ASC';		
			
			$sql = 'SELECT * FROM ( SELECT * FROM students_results WHERE level_id = '.$i.' && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period = "NORMAL" ORDER BY std_mark_custom2 '.$sort.' ) as resultff GROUP BY stdcourse_id ORDER BY null';
		
		}

		$run = mysqli_query($GLOBALS['connect'],  $sql ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
		if( mysqli_num_rows($run)>0 ) {

			while( $v = mysqli_fetch_assoc($run) ) {
				$cu += $v['cu'];
				$cp += $v['cp'];
			}
			mysqli_free_result($run);
		}
		
	endfor;
	
	$result = !empty($cu) ? round(($cp / $cu),3) : '000';
	return number_format($result,2);

}



function get_cgpa($s, $s_id){
/*
// Additional level_id != 0 cos of uncaught error
$sql = 'SELECT ROUND((SUM(cp)/ SUM(cu)),2) AS gpa FROM students_results WHERE level_id != 0 && std_mark_custom2 <= "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period IN ("NORMAL","VACATION")'; 

	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$row = mysqli_fetch_array( $r );
	
	$gpa = $row['gpa'];
	mysqli_free_result($r);
	
	unset($sql, $r, $row, $scu, $wgp);
	return $gpa;
*/

$tcu = 0; $tgp = 0;
	
	$sql = 'SELECT stdcourse_id, std_grade, std_mark_custom2 FROM students_results WHERE level_id !=0 && std_mark_custom2 <= "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 <= "'.$s.'")';
	
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	//return mysqli_num_rows($r);
	//var_dump ($sql);
	while ($row = mysqli_fetch_assoc( $r )){
		//echo $r['stdcourse_id'];
		$cu = get_crunit($row['stdcourse_id'], $row['std_mark_custom2']);
		$gp = get_gradepoint ( $row['std_grade'], $cu );
		//$c .= $r['std_grade'].' - ';
		//return $r['std_grade'];
		$tcu += $cu;
		$tgp += $gp;
		//$c++;
	}
	//return $tgp .'/'. $tcu. ' - '.$c;
	//$row = mysqli_fetch_array( $r );
	 
	$gpa = $tgp / $tcu ;
	$gpa = number_format ($gpa,2); 
	
	unset($sql, $r, $row);
	return $gpa;
}


function get_cgpa_corr($s, $s_id){
	
	//if( true === $correctional 
	//	return get_gpa_omitted( $s, $s_id );
	
		
	//$sql = 'SELECT SUM(cu) AS scu, SUM(cp) AS wgp, (SUM(cp)/ SUM(cu)) AS gpa FROM students_results WHERE std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_cstatus = "yes" && std_mark_custom3 = 0 && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
	$cu = 0;
	$cp = 0;
	$sql_c = 'SELECT cu, cp, stdcourse_id FROM students_results WHERE level_id != 0 && std_mark_custom2 <= "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period IN ("NORMAL","VACATION")';// && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
	$r_c = mysqli_query($GLOBALS['connect'],  $sql_c );
	//echo $sql_c.mysqli_num_rows($r_c);exit;
	while ($row_c = mysqli_fetch_array( $r_c )){
	 
	
	
		$sql = 'SELECT cu, cp FROM students_results_backup WHERE level_id != 0 && std_mark_custom2 <= "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period IN ("NORMAL","VACATION") && stdcourse_id ='.$row_c['stdcourse_id'];// && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE thecourse_id = '.$row_c['stdcourse_id'].' && std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
		
		$r = mysqli_query($GLOBALS['connect'],  $sql );
		
		if (0!=mysqli_num_rows($r)){
			$row = mysqli_fetch_array( $r );
			if ($row['cp'] != $row_c['cp']){
				$cu += $row['cu'];
				$cp += $row['cp'];//echo $row['cp'];
			} else {
				
				$cu += $row_c['cu'];
				$cp += $row_c['cp'];
			}
		
		} else {
			$cu += $row_c['cu'];
			$cp += $row_c['cp'];
		}
	}
	$gpa = $cp / $cu;
	$gpa = number_format ($gpa,2); 
	//exit;
	unset($sql, $r, $row);
	return $gpa;

}



function get_cgpa_resit($s, $s_id){
	
	//if( true === $correctional 
	//	return get_gpa_omitted( $s, $s_id );
	
		
	//$sql = 'SELECT SUM(cu) AS scu, SUM(cp) AS wgp, (SUM(cp)/ SUM(cu)) AS gpa FROM students_results WHERE std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_cstatus = "yes" && std_mark_custom3 = 0 && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
	$cu = 0;
	$cp = 0;
	$sql_c = 'SELECT cu, cp, stdcourse_id FROM students_results WHERE level_id != 0 && std_mark_custom2 <= "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period IN ("NORMAL","VACATION")';// && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
	$r_c = mysqli_query($GLOBALS['connect'],  $sql_c );
	//echo $sql_c.mysqli_num_rows($r_c);exit;
	while ($row_c = mysqli_fetch_array( $r_c )){
	 
	
	
		$sql = 'SELECT cu, cp FROM students_results_backup WHERE level_id != 0 && std_mark_custom2 <= "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period IN ("NORMAL","VACATION") && stdcourse_id ='.$row_c['stdcourse_id'].' ORDER BY stdresult_id DESC';// && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE thecourse_id = '.$row_c['stdcourse_id'].' && std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
		
		$r = mysqli_query($GLOBALS['connect'],  $sql );
		
		if (0!=mysqli_num_rows($r)){
			$row = mysqli_fetch_array( $r );
			//if ($row['cp'] != $row_c['cp']){
				$cu += $row['cu'];
				$cp += $row['cp'];//echo $row['cp'];
			/*} else {
				
				$cu += $row_c['cu'];
				$cp += $row_c['cp'];
			}*/
		
		} else {
			$cu += $row_c['cu'];
			$cp += $row_c['cp'];
		}
	}
	$gpa = $cp / $cu;
	$gpa = number_format ($gpa,2); 
	//exit;
	unset($sql, $r, $row);
	return $gpa;

}


function vac_get_cgpa( $s, $s_id ){
	
	$sql = 'SELECT SUM(cu) AS scu, SUM(cp) AS wgp, ROUND((SUM(cp)/ SUM(cu)),2) AS gpa FROM students_results WHERE level != 0 && std_mark_custom2 <= "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period IN ("NORMAL","VACATION") LIMIT 1'; 

	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$row = mysqli_fetch_array( $r );
	
	$gpa = $row['gpa'];
	
	unset($sql, $r, $row, $scu, $wgp);
	return $gpa;	

}


/*
function get_gpa_correction( $s, $s_id) {
	
	$sql = 'SELECT * FROM (SELECT cu, cp, stdcourse_id FROM students_results WHERE level_id != 0 && std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'") ORDER BY std_mark_custom3 DESC ) as derivy GROUP BY stdcourse_id';
	
	$run = mysqli_query( $GLOBALS['connect'], $sql );
	if( $run && 0!=mysqli_num_rows($run) ) {
		$scu = 0;
		$scp = 0;
		while( $d = mysqli_fetch_assoc($run) ) {
			$scu += (int)$d['cu'];
			$scp += (int)$d['cp'];
		}
		$run->close();
		
		return number_format(($scp/$scu),2);
	}
	
	return false;
	
}
*/


/*	
This is a straight-up caricature of portals gpa with little tune-up that also identifies properly omitted result
*/
function get_gpa($s, $s_id){
	
	//if( true === $correctional 
	//	return get_gpa_omitted( $s, $s_id );
	
		
	//$sql = 'SELECT SUM(cu) AS scu, SUM(cp) AS wgp, (SUM(cp)/ SUM(cu)) AS gpa FROM students_results WHERE std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_cstatus = "yes" && std_mark_custom3 = 0 && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
	
	/*$sql = 'SELECT SUM(cu) AS scu, SUM(cp) AS wgp, (SUM(cp)/ SUM(cu)) AS gpa FROM students_results WHERE level_id != 0 && std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'") LIMIT 1';
	
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$row = mysqli_fetch_array( $r );
	 
	$gpa = $row['gpa'];
	$gpa = number_format ($gpa,2);
*/	
	$tcu = 0; $tgp = 0;
	
	$sql = 'SELECT stdcourse_id, std_grade FROM students_results WHERE level_id !=0 && std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
	
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	//return mysqli_num_rows($r);
	//var_dump ($sql);
	while ($row = mysqli_fetch_assoc( $r )){
		//echo $r['stdcourse_id'];
		$cu = get_crunit($row['stdcourse_id'], $s);
		$gp = get_gradepoint ( $row['std_grade'], $cu );
		//$c .= $r['std_grade'].' - ';
		//return $r['std_grade'];
		$tcu += $cu;
		$tgp += $gp;
		//$c++;
	}
	//return $tgp .'/'. $tcu. ' - '.$c;
	//$row = mysqli_fetch_array( $r );
	 
	$gpa = $tgp / $tcu ;
	$gpa = number_format ($gpa,2); 
	
	unset($sql, $r, $row);
	return $gpa;

}

function get_crunit ( $stdcourseid, $s ) {
	$sql = 'SELECT course_unit FROM all_courses WHERE thecourse_id = '.$stdcourseid.' && course_custom5 = '.$s.' LIMIT 1';
	$r = mysqli_query($GLOBALS['connect'], $sql );
	//return mysqli_num_rows($r);
	$row = mysqli_fetch_assoc( $r );
	$cu = $row['course_unit'];
	return $cu;
}

function get_gradepoint ( $grade, $cu ){
	if ($grade == 'A' )
		return 5.0 * $cu;
	else if ($grade == 'B' )
		return 4.0 * $cu;
	else if ($grade == 'C' )
		return 3.0 * $cu;
	else if ($grade == 'D' )
		return 2.0 * $cu;
	else if ($grade == 'E' )
		return 1.0 * $cu;
	else if ($grade == 'F' )
		return 0.0 * $cu ;
	else if ($grade == 'N' )
		return 0.0 * $cu;
	
}

function get_gpa_corr($s, $s_id){
	
	//if( true === $correctional 
	//	return get_gpa_omitted( $s, $s_id );
	
		
	//$sql = 'SELECT SUM(cu) AS scu, SUM(cp) AS wgp, (SUM(cp)/ SUM(cu)) AS gpa FROM students_results WHERE std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_cstatus = "yes" && std_mark_custom3 = 0 && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
	$cu = 0;
	$cp = 0;
	$sql_c = 'SELECT cu, cp, stdcourse_id FROM students_results WHERE level_id != 0 && std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
	$r_c = mysqli_query($GLOBALS['connect'],  $sql_c );
	//echo $sql_c.mysqli_num_rows($r_c);exit;
	while ($row_c = mysqli_fetch_array( $r_c )){
	 
	
	
		$sql = 'SELECT cu, cp FROM students_results_backup WHERE level_id != 0 && std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE thecourse_id = '.$row_c['stdcourse_id'].' && std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
		
		$r = mysqli_query($GLOBALS['connect'],  $sql );
		
		if (0!=mysqli_num_rows($r)){
			$row = mysqli_fetch_array( $r );
			if ($row['cp'] != $row_c['cp']){
				$cu += $row['cu'];
				$cp += $row['cp'];//echo $row['cp'];
			} else {
				
				$cu += $row_c['cu'];
				$cp += $row_c['cp'];
			}
		
		} else {
			$cu += $row_c['cu'];
			$cp += $row_c['cp'];
		}
	}
	$gpa = $cp / $cu;
	$gpa = number_format ($gpa,2); 
	//exit;
	unset($sql, $r, $row);
	return $gpa;

}

function get_gpa_resit($s, $s_id){
	
	$cu = 0;
	$cp = 0;
	$sql_c = 'SELECT cu, cp, stdcourse_id FROM students_results WHERE level_id != 0 && std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'")';
	$r_c = mysqli_query($GLOBALS['connect'],  $sql_c );
	//echo $sql_c.mysqli_num_rows($r_c);exit;
	while ($row_c = mysqli_fetch_array( $r_c )){
	 
	
	
		$sql = 'SELECT cu, cp FROM students_results_backup WHERE level_id != 0 && std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period = "NORMAL" && stdcourse_id IN ( SELECT thecourse_id FROM course_reg WHERE thecourse_id = '.$row_c['stdcourse_id'].' && std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'") ORDER BY stdresult_id DESC';
		
		$r = mysqli_query($GLOBALS['connect'],  $sql );
		
		if (0!=mysqli_num_rows($r)){
			$row = mysqli_fetch_array( $r );
			//if ($row['cp'] != $row_c['cp']){
				$cu += $row['cu'];
				$cp += $row['cp'];//echo $row['cp'];
			/*} else {
				
				$cu += $row_c['cu'];
				$cp += $row_c['cp'];
			}*/
		
		} else {
			$cu += $row_c['cu'];
			$cp += $row_c['cp'];
		}
	}
	$gpa = $cp / $cu;
	$gpa = number_format ($gpa,2); 
	//exit;
	unset($sql, $r, $row);
	return $gpa;

}

function get_remarksD($p, $f, $d, $l, $s, $s_id, $cgpa, $fos, $finalyear = false, $new=false){

/*	if( $cgpa < 0.75 )
		return 'WITHDRAW';
	elseif( $cgpa >= 0.75 && $cgpa < 1.00 )
		return 'PROBATION';
	*/			
	$return = '';
	$carryf = '';
	$take = get_course_to_take_verREMARK($p, $f, $d, $l, $s, $s_id, $fos, $new);
	$repeat = get_repeat_courses_reloaded($l, $s, $s_id, $d, $fos);
	$repeat1 = get_repeat_courses_reloaded1($l, $s, $s_id, $d, $fos);
	
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

function get_remarks($p, $f, $d, $l, $s, $s_id, $cgpa, $fos, $finalyear = false, $new=false){

	if( $cgpa < 0.75 )
		return 'WITHDRAW';
	elseif( $cgpa >= 0.75 && $cgpa < 1.00 )
		return 'PROBATION';
				
	$return = '';
	$carryf = '';
	$take = get_course_to_take_verREMARK($p, $f, $d, $l, $s, $s_id, $fos, $new);
	$repeat = get_repeat_courses_reloaded($l, $s, $s_id, $d, $fos);
	$repeat1 = get_repeat_courses_reloaded1($l, $s, $s_id, $d, $fos);
	
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



function get_remarks_verCORR($p, $f, $d, $l, $s, $s_id, $cgpa, $fos ){
	
	if( $cgpa < 0.75 )
		return 'WITHDRAW';
	elseif( $cgpa >= 0.75 && $cgpa < 1.00 )
		return 'PROBATION';

	$return = '';
	$take = get_course_to_take_verREMARK($p, $f, $d, $l, $s, $s_id, $fos);
	$repeat = get_repeat_courses_reloaded($l, $s, $s_id, $d, $fos, true);
	
	
	if( !empty( $repeat ) ) {			
		
		$return = '';
		$carryf = '';
		foreach($repeat as $rep){
			if( $rep['num_'] == 3 )
				$carryf .= substr_replace($rep['code'],' ',3, 0).',';
			else
				$return .= substr_replace($rep['code'],' ',3, 0).',';
		}
		
		$carryf = empty($carryf) ? '' : 'CARRY F '.$carryf." <br/>";
		$return = empty($return) ? '' : 'RPT '.$return;
		$return = $carryf.$return;
		
		unset($carryf,$rep);
		
	}
	
	if( empty($take) && $return=='' )
		return $cgpa > 0.99 ? 'PASS' : '';
	else if( !empty($take) )
		$return .= !empty($return) ? " <br/> TAKE ".$take : "TAKE ".$take;
		
	unset( $sql, $l, $s, $s_id, $cpga, $take, $a );
	return $return;

}



function get_carryover_courses($l, $s, $p, $f, $d, $s_id, $fos, $period = "NORMAL"){
	
	$fetch = get_fake_carry_courses_loader($l, $s, $p, $f, $d, $s_id, $fos, $period);
	if( !empty( $fetch ) ) 
	{
		foreach($fetch as $f)
		{
			echo strtoupper(substr_replace($f,' ',3, 0)),'<br/>';
		}
	}

}


function get_course_duration( $fos ) {
	
	$a = mysqli_query($GLOBALS['connect'],  'SELECT duration FROM dept_options WHERE do_id = '.(int)$fos.' LIMIT 1' );
	if( mysqli_num_rows($a)>0 ) {
		$return = mysqli_fetch_assoc($a);
		mysqli_free_result($a);
		return $return['duration'];
	}
		return '';

}


function get_count_session_used( $std_id, $l = 6 ) {
	
	$a = mysqli_query($GLOBALS['connect'],  'SELECT count(DISTINCT `registered_semester`.ysession) FROM `registered_semester` WHERE `registered_semester`.std_id = "'.$std_id.'" && sem IN ("First Semester","Second Semester") && rslevelid <= '.$l.' ');
	if( mysqli_num_rows($a)>0 ) {
		$return = mysqli_fetch_array($a);
		mysqli_free_result($a);
		return $return[0];
	}
		return '';	

}



function get_smart_sess_used( $sid, $l=6) {
	
	$_ = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM `registered_semester` WHERE `registered_semester`.std_id = "'.$sid.'" and sem = "First Semester" and rslevelid <= '.$l.'  ORDER BY registered_semester.ysession DESC' );
	
		if( 0!=mysqli_num_rows($_) ) {
			$ret = array();	
			while( $d = mysqli_fetch_assoc($_) ) {
				$ret[ $d['rslevelid'] ] = $d['ysession'];
			}
			mysqli_free_result($_);
			
			return $ret;
		}
	return array();

}



function help_repeat_courses_loader_spread_year( $duration, $sess_used_counter, $year, $level ){
	
	$return = '';
	if( $sess_used_counter <= $duration ) {
		if( $level == 2 )
			$subtract = 1;
		elseif( $level > 1 )
			$subtract = 2;
		else
			$subtract = 0;
	} else {
		$subtract = 2;
	}
		
	$return = range( ($year-$subtract), $year );
	return $return;
	
}






function get_repeat_courses_reloaded($l, $s, $s_id, $dept, $fos, $correctional = false, $vacation= false ) {

	$adv_ = array();
	$duration = get_course_duration( $fos );
	$sess_used_counter = get_count_session_used( $s_id, $l );
	$adv_ = help_repeat_courses_loader_spread_year( $duration, $sess_used_counter, $s, $l );
	$adv_ = implode( ',', $adv_ );
	
if( $correctional ) {

	$sql = 'SELECT `students_results`.stdcourse_id, `students_results`.std_grade, `course_reg`.clevel_id, `course_reg`.cyearsession, `course_reg`.stdcourse_custom2 FROM students_results, course_reg WHERE 
	`students_results`.std_id = `course_reg`.std_id && 
	`students_results`.std_mark_custom2 = `course_reg`.cyearsession && 
	`students_results`.stdcourse_id = `course_reg`.thecourse_id && 
	`students_results`.std_mark_custom2 IN ('.$adv_.') && 
	`students_results`.std_grade IN ("F","N") && `students_results`.std_mark >= 0 && 
	`students_results`.std_id = '.$s_id.' && 
	`students_results`.stdcourse_id NOT IN ( SELECT `students_results_backup`.stdcourse_id FROM `students_results_backup` WHERE `students_results_backup`.std_id='.$s_id.' && `students_results_backup`.std_grade != "F" && `students_results_backup`.std_mark > 0 Order by null )
	UNION
	SELECT `students_results_backup`.stdcourse_id, `students_results_backup`.std_grade,`course_reg`.clevel_id, `students_results_backup`.std_mark_custom2,`course_reg`.stdcourse_custom2 FROM students_results_backup, course_reg WHERE
	`students_results_backup`.std_id = `course_reg`.std_id && 
	`students_results_backup`.std_mark_custom2 = `course_reg`.cyearsession && 
	`students_results_backup`.stdcourse_id = `course_reg`.thecourse_id && 
	`students_results_backup`.std_mark_custom2 IN ('.$adv_.') && 
	`students_results_backup`.std_grade IN ("F") && `students_results_backup`.std_mark > 0
	 Order by cyearsession DESC';
 
} else {
	
	$ext = $vacation ? '&& period IN ("NORMAL","VACATION")' : '&& period = "NORMAL"';
	$sql = 'SELECT `students_results`.stdcourse_id, `students_results`.std_grade, `course_reg`.clevel_id, `course_reg`.cyearsession, `course_reg`.stdcourse_custom2 FROM students_results, course_reg WHERE 
	`students_results`.std_id = `course_reg`.std_id &&
	`students_results`.std_mark_custom2 = `course_reg`.cyearsession &&
	`students_results`.stdcourse_id = `course_reg`.thecourse_id &&
	`students_results`.std_mark_custom2 IN ('.$adv_.') && 
	`students_results`.std_grade IN ("F") && `students_results`.std_mark >= 0 &&
	`students_results`.std_id = '.$s_id.' && `students_results`.stdcourse_id 
	NOT IN 
	( SELECT `students_results`.stdcourse_id FROM `students_results` 
	WHERE `students_results`.std_grade != "N" && `students_results`.std_grade != "F" && 
	`students_results`.std_mark_custom2 IN ('.$adv_.') && 
	`students_results`.std_id = '.$s_id.' '.$ext.' ) 
	Order by `course_reg`.cyearsession DESC';
//echo $sql."<hr>";
	
}


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
						
	endif;
	
	mysqli_free_result($r);
	unset($sql, $s, $l, $s_id, $r, $adv_);
	return empty($inc) ? '' : $inc;
	
}




function get_repeat_courses_reloaded1($l, $s, $s_id, $dept, $fos, $correctional = false, $vacation= false ) {

	$adv_ = array();
	$duration = get_course_duration( $fos );
	$sess_used_counter = get_count_session_used( $s_id, $l );
	$adv_ = help_repeat_courses_loader_spread_year( $duration, $sess_used_counter, $s, $l );
	$adv_ = implode( ',', $adv_ );
	
if( $correctional ) {

	$sql = 'SELECT `students_results`.stdcourse_id, `students_results`.std_grade, `course_reg`.clevel_id, `course_reg`.cyearsession, `course_reg`.stdcourse_custom2 FROM students_results, course_reg WHERE 
	`students_results`.std_id = `course_reg`.std_id && 
	`students_results`.std_mark_custom2 = `course_reg`.cyearsession && 
	`students_results`.stdcourse_id = `course_reg`.thecourse_id && 
	`students_results`.std_mark_custom2 IN ('.$adv_.') && 
	`students_results`.std_grade IN ("N") && `students_results`.std_mark >= 0 && 
	`students_results`.std_id = '.$s_id.' && 
	`students_results`.stdcourse_id NOT IN ( SELECT `students_results_backup`.stdcourse_id FROM `students_results_backup` WHERE `students_results_backup`.std_id='.$s_id.' && `students_results_backup`.std_grade != "F" && `students_results_backup`.std_mark > 0 Order by null )
	UNION
	SELECT `students_results_backup`.stdcourse_id, `students_results_backup`.std_grade,`course_reg`.clevel_id, `students_results_backup`.std_mark_custom2,`course_reg`.stdcourse_custom2 FROM students_results_backup, course_reg WHERE
	`students_results_backup`.std_id = `course_reg`.std_id && 
	`students_results_backup`.std_mark_custom2 = `course_reg`.cyearsession && 
	`students_results_backup`.stdcourse_id = `course_reg`.thecourse_id && 
	`students_results_backup`.std_mark_custom2 IN ('.$adv_.') && 
	`students_results_backup`.std_grade IN ("F") && `students_results_backup`.std_mark > 0
	 Order by cyearsession DESC';
 
} else {
	
	$ext = $vacation ? '&& period IN ("NORMAL","VACATION")' : '&& period = "NORMAL"';
	$sql = 'SELECT `students_results`.stdcourse_id, `students_results`.std_grade, `course_reg`.clevel_id, `course_reg`.cyearsession, `course_reg`.stdcourse_custom2 FROM students_results, course_reg WHERE 
	`students_results`.std_id = `course_reg`.std_id &&
	`students_results`.std_mark_custom2 = `course_reg`.cyearsession &&
	`students_results`.stdcourse_id = `course_reg`.thecourse_id &&
	`students_results`.std_mark_custom2 IN ('.$adv_.') && 
	`students_results`.std_grade IN ("N") && `students_results`.std_mark >= 0 &&
	`students_results`.std_id = '.$s_id.' && `students_results`.stdcourse_id 
	NOT IN 
	( SELECT `students_results`.stdcourse_id FROM `students_results` 
	WHERE `students_results`.std_grade != "N" && `students_results`.std_grade != "F" && 
	`students_results`.std_mark_custom2 IN ('.$adv_.') && 
	`students_results`.std_id = '.$s_id.' '.$ext.' ) 
	Order by `course_reg`.cyearsession DESC';
//echo $sql."<hr>";
	
}


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
						
	endif;
	
	mysqli_free_result($r);
	unset($sql, $s, $l, $s_id, $r, $adv_);
	return empty($inc) ? '' : $inc;
	
}




function get_repeat_courses($l, $s, $s_id){

	$adv_ = '';		
	
	if( $l > 2 ) {
		$ns = $s - 2;
		$adv_ = ' && `students_results`.std_mark_custom2 > '.$ns;
	} else {
		$adv_ = '&& `course_reg`.clevel_id = '.$l.'';
	}
	
	
	//NICKY WORKING
	$sql = 'SELECT DISTINCTROW `course_reg`.cyearsession, `students_results`.std_grade, `students_results`.std_mark, `students_results`.matric_no, `course_reg`.stdcourse_custom2 FROM 
course_reg, students_results WHERE `course_reg`.std_id = `students_results`.std_id && `course_reg`.thecourse_id = `students_results`.stdcourse_id && `students_results`.std_id = '.$s_id.' && `students_results`.std_mark < 40 './*&& `course_reg`.clevel_id = '.$l.'*/ '&& `students_results`.std_mark_custom2 < "'.$s.'"'.$adv_;

	$r = mysqli_query($GLOBALS['connect'],  $sql );
	if( mysqli_num_rows($r) > 0 ){
		
		$return = '';
		$ignore = array();
		while( $a = mysqli_fetch_assoc($r) ){
			
			#demo release version
			if( !in_array($a['stdcourse_custom2'], $ignore) ){
				$ignore[] = $a['stdcourse_custom2'];
				$return .= "<br/>".$a['stdcourse_custom2'].' '.$a['std_grade'];
			}

		}
		$return = substr($return, 5);
		
	}
	mysqli_free_result($r);
	unset($sql, $s, $l, $s_id, $r, $adv_, $ignore);
	return empty($return) ? '' : $return;
	
}


/*
*	This function is similar to the parent function help_repeat% func only tweaked for get_repeat_courses_222
*/
function help_get_repeat_courses_loader( $duration, $sess_used_counter, $year, $level ){
	
	$return = '';
	if( $sess_used_counter <= $duration ) {
		if( $level == 2 )
			$subtract = 1;
		elseif( $level == 3 )
			$subtract = 2;
		elseif( $level > 1 )
			$subtract = 3;
		else
			$subtract = 0;
	} else {
		$subtract = 3;
	}
		
	$return = range( ($year-$subtract), ($year-1) );
	return $return;
	
}



#i feel this func lacks with probation students
function get_repeat_courses_222($l, $s, $s_id, $dept,$fos ){	
	
	$adv_ = array();
	$duration = get_course_duration( $fos );
	$sess_used_counter = get_count_session_used( $s_id, $l );
	$adv_ = help_get_repeat_courses_loader( $duration, $sess_used_counter, $s, $l );
	$adv_ = implode( ',', $adv_ );
	
	
$sql = 'SELECT `students_results`.stdcourse_id, `students_results`.std_grade, `course_reg`.cyearsession, `course_reg`.stdcourse_custom2 FROM students_results, course_reg WHERE 
`students_results`.std_id = `course_reg`.std_id && 
`students_results`.std_mark_custom2 = `course_reg`.cyearsession && 
`students_results`.stdcourse_id = `course_reg`.thecourse_id && 
`students_results`.std_mark_custom2 IN ('.$adv_.') && 
`students_results`.std_grade = "F" && 
`students_results`.std_mark >= 0 && 
`students_results`.std_id = '.$s_id.' && 
`students_results`.stdcourse_id NOT IN ( SELECT `students_results`.stdcourse_id FROM `students_results` WHERE `students_results`.std_grade != "F" && `students_results`.std_mark_custom2 IN ('.$adv_.') && `students_results`.std_id = '.$s_id.' ) Order by `course_reg`.cyearsession DESC';

	$s = $s-1;
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	if( mysqli_num_rows($r) > 0 ):
		
		$return = '';
		$inc = array();
		$reporter = false;

		while( $a = mysqli_fetch_assoc($r) ):
		
			#advance features
			if( $a['cyearsession'] == $s ) {
				#size must always be 1. cos std cant fail a course twice in the same study year ::: IMPOSSICANT	
				$inc[$a['stdcourse_id']] = array( 'course_id'=>trim($a['stdcourse_id']), 'sizem'=>1, 'code'=>$a['stdcourse_custom2'] );
				continue;			
			}
				
				$reporter = false;	#reset reporter all the time		
				
				if( isset($inc[ $a['stdcourse_id'] ]) ) {
					$inc[ $a['stdcourse_id'] ]['sizem']++;
					$reporter = true;
				}
				
								
				if( $reporter == false ) {
					$inc[] = array( 'course_id'=>trim($a['stdcourse_id']), 'size'=>1, 'code'=>$a['stdcourse_custom2'] );
				}

		endwhile;
		
		$return = '';
		foreach( $inc as $v ) {
			if( $v['sizem']!=3 )
				$return .= $v['sizem'] == 2 ? substr_replace($v['code'],' ',3,0)." F/F<br/>" : substr_replace($v['code'],' ',3,0)." F<br/>";
		}
		$return = substr( $return, 0, -5);
		
	endif;
	
	unset($sql, $r, $adv_, $reporter, $inc);
	return empty($return) ? '' : $return;
	
}




function get_repeat_courses_111($l, $s, $s_id, $dept, $vacation=false )
{	


$sql = 'SELECT `students_results`.stdcourse_id, `students_results`.std_grade, `course_reg`.clevel_id, `course_reg`.cyearsession, `course_reg`.stdcourse_custom2 FROM students_results, course_reg WHERE 
`students_results`.std_id = `course_reg`.std_id && 
`students_results`.std_mark_custom2 = `course_reg`.cyearsession && 
`students_results`.stdcourse_id = `course_reg`.thecourse_id && 
`students_results`.std_mark_custom2 <= '.($s-1).' && 
`students_results`.std_grade IN("N","F") && 
`students_results`.std_mark >= 0 && 
`students_results`.std_id = '.$s_id.' Order by `course_reg`.cyearsession DESC';



if( true === $vacation ) 
{
	// studemts should fail course same year only in NORMAL SECTION 
	$sql = 'SELECT `students_results`.stdcourse_id, `students_results`.std_grade, `course_reg`.clevel_id, `course_reg`.cyearsession, `course_reg`.stdcourse_custom2 FROM students_results, course_reg WHERE 
	`students_results`.std_id = `course_reg`.std_id && 
	`students_results`.std_mark_custom2 = `course_reg`.cyearsession && 
	`students_results`.stdcourse_id = `course_reg`.thecourse_id &&
	`students_results`.period = course_reg.course_season && 
	`students_results`.std_mark_custom2 = '.$s.' && 
	`students_results`.std_grade IN ("N","F","","NR") && 
	`students_results`.std_mark >= 0 && 
	`students_results`.std_id = '.$s_id.' && `students_results`.period = "NORMAL" Order by `course_reg`.cyearsession DESC';

}

	$return = '';
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	if( mysqli_num_rows($r) > 0 ):
		
		$total = array();
		while( $data = mysqli_fetch_assoc($r) )
			$total[] = $data;
		
		mysqli_free_result($r);
		
		$b=array();
		$c=array();
		
		$sql2 = 'SELECT `students_results`.stdcourse_id FROM `students_results` WHERE `students_results`.std_grade NOT IN ("N","F") && `students_results`.std_mark_custom2 <= '.($s-1).' && `students_results`.std_id = '.$s_id.' ORDER BY NULL';
		
		
		$r = mysqli_query( $GLOBALS['connect'], $sql2);
		if( 0!=mysqli_num_rows($r) ) {
			while( $d=mysqli_fetch_assoc($r) ) {
				$c[] = $d['stdcourse_id'];
			}
			mysqli_free_result($r);
		}
		

		
		if( !empty($c) ) {
			
			foreach( $total as $k=>$v ) {
				
				if( in_array($v['stdcourse_id'], $c) ) {
					unset( $total[$k] );
				}
			}
		}
		
		
		if( true === $vacation ) {
			
			$get_list = array();
			foreach( $total as $k=>$v  ) {$get_list[] = $v['stdcourse_id'];}

			$gl = array();
			$r = mysqli_query( $GLOBALS['connect'], 'SELECT stdcourse_id FROM students_results WHERE stdcourse_id IN ('.implode(',', $get_list).') && std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && period = "VACATION"' );
						
			if( 0!=mysqli_num_rows($r) ) {
				while( $d = mysqli_fetch_assoc($r) ) {
					$gl[] = $d['stdcourse_id'];
				}
				mysqli_free_result($r);

				foreach( $total as $k=>$v ) {				
					if( !in_array($v['stdcourse_id'], $gl) ) {
						unset( $total[$k] );
					}
				}				
				
			} else {
				// reset it since no result in vacation
				$total=array();
			}
		
		}		
		
		
		$return = '';
		$inc = array();
		$reporter = false;

		$s--;

		foreach( $total as $k=>$a ):
			
			if( $a['cyearsession'] == $s ) {		
				$inc[ $a['stdcourse_id'] ] = array( 'sizem'=>1, 'code'=>$a['stdcourse_custom2'], 'level'=>$a['clevel_id'] );
				continue;
			}
					
			
			$reporter = false;
			if( isset($inc[ $a['stdcourse_id'] ]) ) { #ok i exists
				
				if( $a['clevel_id'] == $inc[ $a['stdcourse_id'] ]['level'] ) {} #check whether am same level with exists, cos u cant fail a cos 2ice in d same study year
				elseif( strtolower(substr($a['stdcourse_custom2'],0,3)) == 'gss' ){} #No carry F for GSS courses
				else {
					$inc[ $a['stdcourse_id'] ]['sizem']++;
					$inc[ $a['stdcourse_id'] ]['level'] = $a['clevel_id']; 
					$reporter = true;
				}
				
			}
			
			
			if( $reporter == false ) {
				$inc[ $a['stdcourse_id'] ] = array( 'sizem'=>1, 'code'=>$a['stdcourse_custom2'], 'level'=>$a['clevel_id'] );
			}

		endforeach;		
		
		
		
		$return = '';
		
		foreach( $inc as $v ) {

		//print_r($v);
		//echo "<hr>";

			if( $v['sizem']!=3 ) 
			{
				$return .= $v['sizem'] == 2 ? substr_replace($v['code'],' ',3,0)." F/F<br/>" : substr_replace($v['code'],' ',3,0)." F<br/>";
			}
		}
		$return = substr( $return, 0, -5);

		
	endif;
	
	unset($sql, $r, $adv_, $reporter, $inc);
	return strtoupper($return);
	
}



//===================================reepete functions===============================================to make life easy=========


function get_repeatresult_repeat( $l, $s, $s_id, $vacation = false ) {

	$sql = 'SELECT `students_results`.stdcourse_id, `students_results`.std_grade, `students_results`.std_mark_custom1, `course_reg`.c_unit, `course_reg`.cyearsession, `course_reg`.stdcourse_custom2 FROM students_results, course_reg WHERE 
	`students_results`.std_id = `course_reg`.std_id &&
	`students_results`.std_mark_custom2 = `course_reg`.cyearsession &&
	`students_results`.stdcourse_id = `course_reg`.thecourse_id &&
	`students_results`.std_mark_custom2 = '.($s).' && `students_results`.std_id = '.$s_id.' Order by `course_reg`.cyearsession DESC';
	
				
	$return = array();
	$run = mysqli_query($GLOBALS['connect'],  $sql );
	if( mysqli_num_rows($run)>0 ) {
		
		//first while
		$total = array();
		while( $data = mysqli_fetch_assoc($run) )
			$total[] = $data;
		
		mysqli_free_result($run);
		
		$c = array();	
		
		$sql2 = 'SELECT `students_results`.stdcourse_id FROM `students_results` WHERE `students_results`.std_grade in( "F","N") && `students_results`.std_mark >= 0 && `students_results`.std_mark_custom2 = '.($s-1).' && `students_results`.std_id = '.$s_id.'';
		
		if( true === $vacation ) {
			// Change Of Query
			$sql2 = 'SELECT `students_results`.stdcourse_id FROM `students_results` WHERE `students_results`.std_grade = "F" && `students_results`.std_mark >= 0 && `students_results`.std_mark_custom2 = '.$s.' && `students_results`.std_id = '.$s_id.' && period = "NORMAL"';
		}
		
		$run = mysqli_query( $GLOBALS['connect'], $sql2 );
		if( 0!=mysqli_num_rows($run) ) {
			$c = array();
			while( $d=mysqli_fetch_assoc($run) ) {
				$c[] = $d['stdcourse_id'];
			}
			mysqli_free_result($run);
		
			foreach( $total as $k=>$v ) {
				if( !in_array($v['stdcourse_id'], $c) ) {			
					unset($total[$k]);
				}
			}		
			
			foreach( $total as $k=>$g ) {
				$sem = $g['std_mark_custom1'] == 'First Semester' ? 1 : 2;
				$return[] = $sem.' '.$g['stdcourse_custom2'];
			}

			unset($a,$b,$c);
			
		}
		
	}
	return $return;

}


function no_rpt_result_found_autofix( $s, $s_id, $m_no, $fos, $l ) {
	
		$sql = 'select * from students_results as sr where sr.std_grade = "F" && sr.std_mark >= 0 && std_id = '.$s_id.' and sr.std_mark_custom2 = "'.($s-1).'"';
		$load = mysqli_query( $GLOBALS['connect'], $sql );
		if( 0!=mysqli_num_rows($load) ) {
			
			$list = array();
			while( $data=mysqli_fetch_assoc($load) )
				$list[] = $data['stdcourse_id'];
				
			mysqli_free_result($load);
				
			  $sqlchk = 'SELECT * FROM all_courses as ac WHERE ac.thecourse_id IN ('.implode(',', $list).') && ac.course_custom2 = '.$fos.' && course_custom5 = "'.($s-1).'" && thecourse_id NOT IN ( SELECT stdcourse_id FROM students_results WHERE std_id = '.$s_id.' and std_mark_custom2 = "'.$s.'" )';
			  
			  $soji = mysqli_query( $GLOBALS['connect'], $sqlchk );
			  if( 0!=mysqli_num_rows($soji) ) {
				  
				  $list = array();
				  $x_del_sake = array();
				  while( $data = mysqli_fetch_assoc($soji) ) {
					  $list[] = $data;
					  $x_del_sake[] = $data['thecourse_id'];
				  }
				  mysqli_free_result( $soji );
			  
				  mysqli_query( $GLOBALS['connect'], 'DELETE FROM course_reg WHERE thecourse_id IN ('.implode(',', $x_del_sake).') && std_id = '.$s_id.' && cyearsession = "'.$s.'"' );
				  mysqli_query( $GLOBALS['connect'], 'OPTIMIZE TABLE course_reg' );
				  
				  $ins1 = mysqli_prepare( $GLOBALS['connect'], 'INSERT INTO course_reg( `std_id`,  `thecourse_id`,  `c_unit`,  `clevel_id`,  `cyearsession`,  `csemester`,  `cdate_reg`,  `stdcourse_custom1`,  `stdcourse_custom2`,  `stdcourse_custom3` ) VALUES ( ?,?,?,?,?,?,?,?,?,? )' );
				  
				  $date = date('Y-M-D');
				  foreach( $list as $k=>$v ) {
					  $ins1->bind_param('iiiissssss', $s_id, $v['thecourse_id'], $v['course_unit'], $l, $s, $v['course_semester'], $date, $v['course_title'], $v['course_code'], $v['course_status'] );
					  $ins1->execute();
				  }
  
				  $ins2 = mysqli_prepare( $GLOBALS['connect'], 'INSERT INTO students_results( `std_id`,  `matric_no`,  `level_id`,  `stdcourse_id`,  `std_mark`,  `std_grade`,  `cu`,  `std_cstatus`,  `std_mark_custom1`,  `std_mark_custom2` ) VALUES ( ?,?,?,?,?,?,?,?,?,? )' );
				  
				  $std_mark = 20;
				  $std_grade = 'F';
				  $yes = 'YES';
				  foreach( $list as $k=>$v ) {
					  $ins2->bind_param('isiissssss', $s_id, $m_no, $l, $v['thecourse_id'], $std_mark, $std_grade, $v['course_unit'], $yes, $v['course_semester'], $s );
					  $ins2->execute();
				  }
				  
				  
			  }
			
			
		}
	
}



function get_repeatresult_carry_over( $l, $s, $p, $f, $d, $s_id, $fos, $period = 'NORMAL' ) {
	
	$xy = get_count_session_used($s_id, $l); 

	$se =$xy-1;
	$in = range( ($s-$se), ($s-1) );
	
	#this carryover course(s) only appears if result is seen for the student in the current section else hidden
	$sql = 'SELECT course_code, course_unit, course_semester FROM all_courses WHERE programme_id = '.$p.' && faculty_id = '.$f.' && department_id = '.$d.' && level_id <= '.($l-1).' && all_courses.course_custom2 = '.$fos.' && all_courses.course_custom5 IN ('.implode(',', $in).') && all_courses.thecourse_id NOT IN ( SELECT `course_reg`.thecourse_id FROM course_reg WHERE `course_reg`.std_id = '.$s_id.' && `course_reg`.cyearsession IN ('.implode(',', $in).') && course_season = "'.$period.'") && thecourse_id IN ( SELECT stdcourse_id FROM students_results WHERE std_id = '.$s_id.' && std_mark_custom2 = '.$s.' && period = "'.$period.'" ) GROUP BY course_code';
if($s_id=="6459")
{
	//echo $sql."TEST";
}
else
{
}
	
	$return = array();
	$run = mysqli_query($GLOBALS['connect'],  $sql );
	if( mysqli_num_rows($run)>0 ) {
		while( $g = mysqli_fetch_assoc($run) ) {
			$sem = $g['course_semester'] == 'First Semester' ? 1 : 2;
			$return[] = $sem.' '.$g['course_code'];
		}
	}
	
	return $return;
	
}


//=================================================================================================================================





function get_chr( $l, $semester, $s, $s_id ){
	
	$sql = 'SELECT DISTINCT `students_results`.stdcourse_id, `course_reg`.stdcourse_custom2, `students_results`.std_grade, `students_results`.cu FROM students_results, course_reg WHERE `students_results`.stdcourse_id = `course_reg`.thecourse_id && `students_results`.std_id = '.$s_id.' && `course_reg`.clevel_id < '.$l.' && `students_results`.std_cstatus = "yes" && `students_results`.std_mark_custom1 = "'.trim($semester).'" && `students_results`.std_mark_custom2 = "'.$s.'" && `students_results`.stdcourse_id IN (SELECT course_id FROM course_reg WHERE cyearsession < '.$s.' && clevel_id < '.$l.')';

	$r = mysqli_query($GLOBALS['connect'],  $sql );
	if( mysqli_num_rows($r) > 0 ){
		$return = '';
		while( $a = mysqli_fetch_assoc($r) ){
			$return .= "<br/>".$a['cu']."&nbsp;".trim($a['stdcourse_custom2'])."&nbsp;".$a['std_grade'];
		}
		$return = substr($return, 5);
	}
	mysqli_free_result($r);
	unset($sql, $s, $l, $s_id, $r);
	return empty($return) ? '' : $return;
	
}


/*This function gets the result of course of the student after he or she failed or carried course over*/
function get_fake_chr( $sem, $rpt_list, $carryov_list, $s, $std ) {
	
	$to_go = array();
	$merger = array_merge($rpt_list, $carryov_list);

	if( !empty($merger) ) 
	{
		foreach( $merger as $on ) 
		{
			$g = substr($on, 0, 1);
			if( $g == $sem )
			$to_go[] = substr($on, 2);
		}

	$sql = 'SELECT `students_results`.std_grade, `students_results`.cu, `students_results`.std_mark_custom2,`all_courses`.course_code, `all_courses`.thecourse_id FROM students_results, all_courses WHERE `students_results`.stdcourse_id=`all_courses`.thecourse_id && `all_courses`.course_code IN (\''.implode("','", $to_go).'\') && `students_results`.std_mark_custom2 = '.$s.' && `students_results`.std_id = '.$std.' && `students_results`.period != "'.vacation.'" GROUP BY course_code';

	
	$r = mysqli_query($GLOBALS['connect'],  $sql ) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	$return = '';
	if( mysqli_num_rows($r) > 0 ){
		while( $g = mysqli_fetch_array($r) ){
			$grade = $g['std_grade'];
			/*$sql_c = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results_backup WHERE std_id = '.$std.' and stdcourse_id IN ('.$g['stdcourse_id'].') && std_mark_custom2='.$s.'';// && period != "'.vacation.'"';
				$r_c = mysqli_query( $GLOBALS['connect'], $sql_c );
				if( 0!=mysqli_num_rows($r_c) ) {
					while( $data_c=mysqli_fetch_assoc($r_c) ) {
						if ($d['std_grade'] != $data_c['std_grade']){
							$grade = $data_c['std_grade'];
						} else {
						//	$grade = $d['std_grade'];
						}
					}
				}*/
			$return .= "<br/>". $g['cu'].' '.substr_replace($g['course_code']," ",3, 0).' '.$grade;
			
			
		}

	}
		
		$return = substr($return, 5);
		echo strtoupper($return);
	}
		echo "";
	
}

function get_fake_chr_corr( $sem, $rpt_list, $carryov_list, $s, $std ) {
	
	$to_go = array();
	$merger = array_merge($rpt_list, $carryov_list);

	if( !empty($merger) ) 
	{
		foreach( $merger as $on ) 
		{
			$g = substr($on, 0, 1);
			if( $g == $sem )
			$to_go[] = substr($on, 2);
		}

	$sql = 'SELECT `students_results`.std_grade, `students_results`.cu, `students_results`.std_mark_custom2,`all_courses`.course_code, `all_courses`.thecourse_id, `students_results`.stdcourse_id FROM students_results, all_courses WHERE `students_results`.stdcourse_id=`all_courses`.thecourse_id && `all_courses`.course_code IN (\''.implode("','", $to_go).'\') && `students_results`.std_mark_custom2 = '.$s.' && `students_results`.std_id = '.$std.' && `students_results`.period != "'.vacation.'" GROUP BY course_code';

	
	$r = mysqli_query($GLOBALS['connect'],  $sql ) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	$return = '';
	if( mysqli_num_rows($r) > 0 ){
		while( $g = mysqli_fetch_array($r) ){
			$grade = $g['std_grade'];
			$sql_c = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results_backup WHERE std_id = '.$std.' and stdcourse_id IN ('.$g['stdcourse_id'].') && std_mark_custom2='.$s.'';// && period != "'.vacation.'"';
			//echo $sql_c;exit;
				$r_c = mysqli_query( $GLOBALS['connect'], $sql_c );
				if( 0!=mysqli_num_rows($r_c) ) {
					while( $data_c=mysqli_fetch_assoc($r_c) ) {
						if ($g['std_grade'] != $data_c['std_grade']){
							$grade = $data_c['std_grade'];
						} else {
						//	$grade = $d['std_grade'];
						}
					}
				}
			$return .= "<br/>". $g['cu'].' '.substr_replace($g['course_code']," ",3, 0).' '.$grade;
			
			
		}

	}
		
		$return = substr($return, 5);
		echo strtoupper($return);
	}
		echo "";
	
}


function get_fake_chr_resit( $sem, $rpt_list, $carryov_list, $s, $std ) {
	
	$to_go = array();
	$merger = array_merge($rpt_list, $carryov_list);

	if( !empty($merger) ) 
	{
		foreach( $merger as $on ) 
		{
			$g = substr($on, 0, 1);
			if( $g == $sem )
			$to_go[] = substr($on, 2);
		}

	$sql = 'SELECT `students_results`.std_grade, `students_results`.cu, `students_results`.std_mark_custom2,`all_courses`.course_code, `all_courses`.thecourse_id, `students_results`.stdcourse_id FROM students_results, all_courses WHERE `students_results`.stdcourse_id=`all_courses`.thecourse_id && `all_courses`.course_code IN (\''.implode("','", $to_go).'\') && `students_results`.std_mark_custom2 = '.$s.' && `students_results`.std_id = '.$std.' && `students_results`.period != "'.vacation.'" GROUP BY course_code';

	
	$r = mysqli_query($GLOBALS['connect'],  $sql ) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	$return = '';
	if( mysqli_num_rows($r) > 0 ){
		while( $g = mysqli_fetch_array($r) ){
			$grade = $g['std_grade'];
			$sql_c = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results_backup WHERE std_id = '.$std.' and stdcourse_id IN ('.$g['stdcourse_id'].') && std_mark_custom2='.$s.'';// && period != "'.vacation.'"';
			//echo $sql_c;exit;
				$r_c = mysqli_query( $GLOBALS['connect'], $sql_c );
				if( 0!=mysqli_num_rows($r_c) ) {
					while( $data_c=mysqli_fetch_assoc($r_c) ) {
						if ($g['std_grade'] != $data_c['std_grade']){
							$grade = $data_c['std_grade'];
						} else {
						//	$grade = $d['std_grade'];
						}
					}
				}
			$return .= "<br/>". $g['cu'].' '.substr_replace($g['course_code']," ",3, 0).' '.$grade;
			
			
		}

	}
		
		$return = substr($return, 5);
		echo strtoupper($return);
	}
		echo "";
	
}





#---------------------------|
#  CORRECTIONAL             |
#  RESULT                   |
#  SECTION                  |
#---------------------------|


function get_correctional_students( $list, $s ) {
	
	$slist = array();
	$olist = array();
	$dlist = array();
	
	foreach( $list as $k=>$v ) {
		$slist[ $k ] = $v['std_id'];
		$olist[ $k ] = $v;
	}
	
	  $init = mysqli_query( $GLOBALS['connect'], 'SELECT DISTINCTROW `students_results`.std_id FROM `students_results`, `students_results_backup` WHERE 
`students_results`.std_id = `students_results_backup`.std_id &&
`students_results`.matric_no = `students_results_backup`.matric_no &&
`students_results`.stdcourse_id = `students_results_backup`.stdcourse_id &&
`students_results`.std_mark_custom2 = `students_results_backup`.std_mark_custom2 && `students_results`.std_mark_custom2 = '.$s.' && `students_results_backup`.std_id IN ('.implode(',', $slist).')' ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

	if( mysqli_num_rows($init)>0 ) 
	{
		while( $f=mysqli_fetch_assoc($init) ) 
		{
			$dlist[] = $f['std_id'];
				echo "<hr>".$f['std_id'];
		}
	}
	
	foreach( $olist as $kk=>$vv ) 
	{
		if( !in_array( $vv['std_id'], $dlist) )
		{
			unset($olist[$kk]);
		}
	}
	
	return $olist;
			
}



function fetch_student_mat_correctional( $d, $p, $l, $f, $s, $fos ){

	$sql = 'SELECT DISTINCT sr.std_id, sp.matric_no, sr.level_id, sp.surname, sp.firstname, sp.othernames
FROM students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.department_id = sp.stddepartment_id && sr.yearsession="'.$s.'" && sr.programme_id='.$p.' && sr.faculty_id='.$f.' && sr.department_id='.$d.' && sr.level_id='.$l.' && sp.stdcourse = '.$fos.' ORDER BY sp.matric_no, sp.surname ASC';

	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$result = array();
	if( mysqli_num_rows($r) > 0 ){
		while( $a = mysqli_fetch_assoc($r) ){
			$result[] = $a;
		}
		
		$result = get_correctional_students($result, $s);
		return $result;
	}
	return 0;

}



function fetch_student_RESULT_correctional($sid, $l, $c, $s){
	
	$result = array();
	foreach( $l as $cc ) {

		$sql = 'select `students_results`.std_mark, `students_results`.std_grade,`students_results_backup`.std_mark, `students_results_backup`.std_grade FROM students_results, students_results_backup WHERE 
		`students_results`.stdcourse_id = `students_results_backup`.stdcourse_id &&
		`students_results`.std_id = `students_results_backup`.std_id &&
		`students_results`.std_mark_custom2 = `students_results_backup`.std_mark_custom2 &&
		`students_results`.stdcourse_id = '.$cc.' &&
		`students_results`.std_id="'.$sid.'" && 
		`students_results`.std_mark_custom1="'.$c.'" &&  
		`students_results`.std_mark_custom2='.$s.' 
		order by `students_results`.stdcourse_id desc LIMIT 1';

		$r = mysqli_query($GLOBALS['connect'],  $sql );
		if( mysqli_num_rows($r) > 0 ){
			
			$a = mysqli_fetch_row($r);
			$a[1] = empty($a[0]) || $a[0]=='' ? '' : $a[1];
			$a[3] = empty($a[2]) || $a[2]=='' ? '' : $a[3];
			$result[] = $a;
			
		} else
			$result[] = array('std_mark'=>'', 'std_grade'=>'','std_mark2'=>'', 'std_grade2'=>'');
				
	}
	mysqli_free_result($r);
	return $result;
	
}



function corr_get_cgpa($s, $s_id, $l){

	$cu = 0;
	$cp = 0;
	$ct = 0;
	$count = count( range(1, $l) );
	
	for( $i=1; $i<=$count; $i++ ):
		
		if( $i==$l ) {
			$sql = 'SELECT * FROM ( SELECT * FROM students_results_backup WHERE level_id = "'.$i.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" ORDER BY std_mark_custom2 DESC ) as resultff GROUP BY stdcourse_id ORDER BY null';
			
		} else {
 			$sql = 'SELECT * FROM ( SELECT * FROM students_results WHERE level_id = "'.$i.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period IN ("NORMAL","VACATION") ORDER BY std_mark_custom2 DESC ) as resultff GROUP BY stdcourse_id ORDER BY null';
		}
		
		$run = mysqli_query($GLOBALS['connect'],  $sql );
		if( mysqli_num_rows($run)>0 ) {

			while( $v = mysqli_fetch_assoc($run) ) {
				$ct++;
				$cu += $v['cu'];
				$cp += $v['cp'];
			}
	
		}
		
	endfor;
	
	$result = round(($cp / $cu),2);
	return number_format($result,2);

}



function corr_get_gpa($s, $s_id){
	
	$sql = 'SELECT ROUND((SUM(cp)/ SUM(cu)),2) AS gpa FROM students_results_backup WHERE level_id != 0 && std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && std_grade != "N" && std_cstatus = "yes" && period = "NORMAL" LIMIT 1'; 

	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$row = mysqli_fetch_array( $r );
	
	$gpa = $row['gpa'];
	
	unset($sql, $r, $row);
	return $gpa;

}



/*This function gets the result of course of the student after he or she failed or carried course over*/
function get_fake_chr_verCORRECTIONAL( $sem, $rpt_list, $carryov_list, $s, $std ) {
	$to_go = array();
	
	$merger = array_merge($rpt_list, $carryov_list);
	
	if( !empty($merger) ) {
		foreach( $merger as $on ){
			$g = substr($on, 0, 1);
			if( $g == $sem )
				$to_go[] = substr($on, 2);
		}
		
	$sql = 'SELECT `students_results`.std_grade, `students_results_backup`.std_grade, `students_results`.cu, `students_results`.std_mark_custom2,`all_courses`.course_code, `all_courses`.course_id FROM students_results, students_results_backup, all_courses WHERE
	`students_results`.std_id = `students_results_backup`.std_id &&
	`students_results`.stdcourse_id = `students_results_backup`.stdcourse_id &&
	`students_results`.std_mark_custom2 = `students_results_backup`.std_mark_custom2 &&
	`students_results`.stdcourse_id=`all_courses`.course_id && 
	`all_courses`.course_code IN (\''.implode("','", $to_go).'\') && `students_results`.std_mark_custom2 = '.$s.' && `students_results`.std_id = '.$std.'';
	
	$r = mysqli_query($GLOBALS['connect'],  $sql ) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	$return = '';
	if( mysqli_num_rows($r) > 0 ){
		while( $g = mysqli_fetch_array($r) ) {
			if( $g[0] != $g[1] )
				$return .= "<br/>". $g['cu'].' '.substr_replace($g['course_code']," ",3, 0).' '.$g['std_grade'].'*';
			else
				$return .= "<br/>". $g['cu'].' '.substr_replace($g['course_code']," ",3, 0).' '.$g['std_grade'];
		}

	}
		
		$return = substr($return, 5);
		echo $return;
	}
		echo "";
	
}



function G_correction_reason( $s_id_arr ){
	
	if( empty($s_id_arr) )
		return false;
		
	$r = mysqli_query($GLOBALS['connect'],  'SELECT reason_of_correction FROM students_results_backup WHERE `students_results_backup`.std_id IN ('.implode(',', $s_id_arr).') && reason_of_correction != NULL or reason_of_correction !=""' );
	if( mysqli_num_rows($r) > 0 ) {
		while( $ret = mysqli_fetch_assoc($r) )
			$return[] = $ret['reason_of_correction'];
		
		return $return;
	}
		return '';

}




















#---------------------------|
#  PROBATIO0NAL             |
#  RESULT                   |
#  SECTION                  |
#---------------------------|


function fetch_student_mat_probational( $d, $p, $l, $f, $s, $fos ){
	

	$current_session_list = array();
	$run = mysqli_query( $GLOBALS['connect'], 'SELECT DISTINCT sr.std_id, sp.matric_no, sr.level_id, sp.surname, sp.firstname, sp.othernames
	FROM students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.department_id = sp.stddepartment_id && sr.yearsession="'.($s).'" && sr.programme_id='.$p.' && sr.faculty_id='.$f.' && sr.department_id='.$d.' && sr.level_id='.$l.' && sp.stdcourse = '.$fos.' ORDER BY sp.matric_no, sp.surname ASC' ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
	
	if( 0!=mysqli_num_rows($run) ) {
		while( $data = mysqli_fetch_assoc($run) ) {
			$current_session_list[] = $data['std_id'];
		}
		mysqli_free_result($run);
	}
	
	
	$sql = 'SELECT DISTINCT sr.std_id, sp.matric_no, sr.level_id, sp.surname, sp.firstname, sp.othernames
	FROM students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.department_id = sp.stddepartment_id && sr.yearsession="'.($s-1).'" && sr.programme_id='.$p.' && sr.faculty_id='.$f.' && sr.department_id='.$d.' && sr.level_id='.$l.' && sp.stdcourse = '.$fos.' ORDER BY sp.matric_no, sp.surname ASC';	
	
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$result = array();
	if( mysqli_num_rows($r) > 0 ){
		while( $a = mysqli_fetch_assoc($r) ) {
		
	
			
		if( in_array($a['std_id'], $current_session_list) ) {
			$result[] = $a;
		}

		
		}
		return $result;
	}

		return 0;

}


function fetch_courses_verPROBATION( $d, $p, $l, $c, $s, $f, $fos ){
	// course_reg.stdcourse_custom2 included in the select query
	$sql = 'SELECT DISTINCT course_reg.thecourse_id, course_reg.stdcourse_custom2, all_courses.course_unit 
	FROM all_courses,course_reg
WHERE all_courses.programme_id = '.$p.' AND
all_courses.faculty_id = '.$f.' AND
all_courses.department_id = '.$d.' AND
course_reg.clevel_id = '.$l.' AND
course_reg.csemester = "'.$c.'" AND
all_courses.level_id = '.$l.' AND
all_courses.course_custom2 = '.$fos.' AND
course_reg.cyearsession = "'.($s-1).'" AND
all_courses.course_custom5 = '.$s.' AND 
course_reg.thecourse_id = all_courses.thecourse_id && course_reg.course_season = "NORMAL"
ORDER BY course_reg.thecourse_id DESC';
	
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$result = array();
	while( $a = mysqli_fetch_assoc($r) )
		$result[] = $a;

	mysqli_free_result($r);
	unset($d,$p,$l,$c,$f,$r,$a);
	return $result;	

}


function fetch_probation_courses($l, $s, $std){

	$sql = 'SELECT DISTINCT students_results.stdcourse_id, students_results.std_grade, course_reg.stdcourse_custom2 FROM students_results, course_reg WHERE
students_results.std_id = course_reg.std_id
&& students_results.stdcourse_id = course_reg.thecourse_id
&& students_results.std_mark_custom2 = course_reg.cyearsession
&& students_results.std_id = '.$std.'
&& students_results.level_id = '.$l.'
&& students_results.std_mark_custom2 = '.($s-1).'
&& students_results.std_mark NOT IN ("",0," ") 
&& students_results.std_mark < 40';
	
	$return = '';
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	if( mysqli_num_rows($r) > 0 ){
		while( $f = mysqli_fetch_assoc($r) )
			$return .= "<br/>".substr_replace($f['stdcourse_custom2'],' ',3, 0).' F';
			
		$return = substr($return, 5);
		echo $return;
	}
	mysqli_free_result($r);
	
}



function prob_get_carry_over_courses( $s, $s_id, $l, $d, $p, $fos ) {
	
	$return = '';
	$sql = 'SELECT course_code, course_unit FROM all_courses WHERE programme_id = '.$p.' && department_id = '.$d.' && level_id <= '.$l.' && course_status = "C" && course_custom2 = '.$fos.' && `course_custom5` = "'.($s-1).'" && course_code NOT IN ( SELECT `course_reg`.stdcourse_custom2 FROM course_reg WHERE `course_reg`.std_id = '.$s_id.' && `course_reg`.cyearsession = "'.($s-1).'" ORDER BY cyearsession DESC )';
	
	
	$r = mysqli_query($GLOBALS['connect'],  $sql ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	if( mysqli_num_rows($r) > 0 ){
		$result = array();
		while( $a = mysqli_fetch_assoc($r) ) {
			$result[] = $a['course_code'];//."&nbsp;($a[course_unit])";
		}
	}

	if( !empty($result) ){
		foreach($result as $f)
			$return .= substr_replace($f,' ',3, 0).',';

		$return = substr($return, 0, -1);
		return $return;
	}
	return 0;

}


function prob_get_repeat_failed_courses($l, $s, $s_id, $dept,$fos) {

	$adv_ = array();
	$duration = get_course_duration( $fos );
	$sess_used_counter = get_count_session_used( $s_id, $l );
	$adv_ = help_repeat_courses_loader_spread_year( $duration, $sess_used_counter, $s, $l );
	$adv_ = implode( ',', $adv_ );
	
	$sql = 'SELECT * FROM ( SELECT `students_results`.stdcourse_id, `students_results`.std_grade, `course_reg`.cyearsession, `course_reg`.stdcourse_custom2 FROM students_results, course_reg WHERE 
	`students_results`.std_id = `course_reg`.std_id &&
	`students_results`.std_mark_custom2 = `course_reg`.cyearsession &&
	`students_results`.stdcourse_id = `course_reg`.thecourse_id &&
	`students_results`.std_mark_custom2 IN ('.$adv_.') &&
	`students_results`.std_mark NOT IN ("",0," ") &&
	`students_results`.std_id = '.$s_id.' Order by `students_results`.std_mark_custom2 DESC ) as resultff GROUP BY stdcourse_id ORDER BY null';
	
	$resultier = array();
	$ignore = array();
	$inc = array();
	$run = mysqli_query($GLOBALS['connect'],  $sql );
	if( mysqli_num_rows($run)>0 ) {
		while( $a=mysqli_fetch_assoc($run) ) {
			
			if( $a['std_grade'] != 'F' )
				continue;
		
			
			if( $a['cyearsession'] == $s ) {
				$inc[] = trim($a['stdcourse_id']);
				$resultier[$a['stdcourse_custom2']] = array('code'=>$a['stdcourse_custom2'], num_=>1);
				continue;				
			}
			
			if( in_array($a['stdcourse_id'], $inc) ) {

					$key = '';
					$k = count($ignore);
					if( !empty($k) ) {
						for($i=0; $i<$k; ++$i){
							if( $a['stdcourse_custom2'] == $ignore[$i]['custom2'] ) {
								$key = (int)$i;
								break;
							}
						}
						unset($i, $k);
					}
					
					if( is_int($key) )
						$resultier[$a['stdcourse_custom2']] = array('code'=>$a['stdcourse_custom2'], num_=>3);
					else
						$resultier[$a['stdcourse_custom2']] = array('code'=>$a['stdcourse_custom2'], num_=>2);
					
					
					$ignore[] = array('custom2'=>$a['stdcourse_custom2'], 'year'=>$a['cyearsession']);
				
			}
			
			
		}
	}
	
	return empty($resultier) ? '' : $resultier;
	
}



function get_remarks_verPROBATION($s, $s_id, $l,  $d, $cgpa, $p, $fos){
	
	if( $cgpa < 0.75 )
		return 'WITHDRAW';
	
	if( $cgpa < 1.00 && $cgpa > 0.74 )
		return 'WITHDRAW OR CHANGE PROGRAMME';
		
		
	$return = '';
	$repeat = prob_get_repeat_failed_courses($l, $s, $s_id, $d,$fos);
	$take = prob_get_carry_over_courses( $s, $s_id, $l, $d, $p, $fos );
	
			
	if( !empty( $repeat ) ) {
		
		$return = '';
		$carryf = '';
		foreach($repeat as $rep){
			if( $rep['num_'] == 3 )
				$carryf .= substr_replace($rep['code'],' ',3, 0).',';
			else
				$return .= substr_replace($rep['code'],' ',3, 0).',';
		}
		
		$carryf = empty($carryf) ? '' : 'CARRY F '.$carryf." <br/>";
		$return = empty($return) ? '' : 'RPT '.$return;
		$return = $carryf.$return;
		
		$return = substr($return, 0, -1);
		
		unset($carryf,$rep);
		
	}
	
	if( empty($take) && $return=='' )
		return $cgpa > 0.99 ? 'PASS' : '';
	else if( !empty($take) )
		$return .= !empty($return) ? " <br/> TAKE ".$take : "TAKE ".$take;	
	
	
	return $return;

}

































#---------------------------|
#  OMMITTED                 |
#  RESULT                   |
#  SECTION                  |
#---------------------------|

function get_omitted_students( $list, $s ) {
	
	$slist = array();
	$olist = array();
	$dlist = array();
	
	foreach( $list as $k=>$v ) {
		$slist[ $k ] = $v['std_id'];
		$olist[ $k ] = $v;
	}
	
	  $init = mysqli_query( $GLOBALS['connect'], 'SELECT DISTINCTROW `students_results`.std_id FROM `students_results` WHERE `students_results`.std_mark_custom3 = 1 && `students_results`.std_mark_custom2 = '.$s.' && std_id IN ('.implode(',', $slist).')' ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

	if( mysqli_num_rows($init)>0 ) {
		while( $f=mysqli_fetch_assoc($init) ) {
			$dlist[] = $f['std_id'];
		}
	}
	
	foreach( $olist as $kk=>$vv ) {
		if( !in_array( $vv['std_id'], $dlist) ){
			unset($olist[$kk]);
		}
	}
	
	return $olist;	
}

function fetch_student_mat_omitted( $d, $p, $l, $f, $s, $fos ){

	$sql = 'SELECT DISTINCT sr.std_id, sp.matric_no, sr.level_id, sp.surname, sp.firstname, sp.othernames
FROM students_reg as sr LEFT JOIN students_profile as sp USING (std_id) WHERE sr.department_id = sp.stddepartment_id && sr.yearsession="'.$s.'" && sr.programme_id='.$p.' && sr.faculty_id='.$f.' && sr.department_id='.$d.' && sr.level_id='.$l.' && sp.stdcourse = '.$fos.' ORDER BY sp.matric_no, sp.surname ASC';

	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$result = array();
	if( mysqli_num_rows($r) > 0 ){
		while( $a = mysqli_fetch_assoc($r) ){
			$result[] = $a;
		}
		
		$result = get_omitted_students( $result, $s );
		return $result;
	}
	return 0;

}

































#---------------------------|
#  VACATION                 |
#  RESULT                   |
#  SECTION                  |
#---------------------------|

/*
This type of result allows a maximum of 3 course with a compulsory GSS course from the student
The courses registered and result is actually what is displayed in this report
*/

function fetch_student_RESULT_vacational($sid, $arr, $s, $sid_coursereg){
	
	if( empty($arr) )
		return array();
	
	$return = array();	
	
	$sql = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results WHERE std_id = '.$sid.' and stdcourse_id IN ('.implode(',', $arr).') && std_mark_custom2='.$s.' && period = "'.vacation.'"';
		
	$r = mysqli_query( $GLOBALS['connect'], $sql );
	$all = array();
	if( 0!=mysqli_num_rows($r) ) 
	{
		while( $data=mysqli_fetch_assoc($r) ) 
		{
			$all[ $data['stdcourse_id'] ] = $data;
			
		}
		mysqli_free_result($r);
	}
	
	
	$keys = array_keys($all);
	foreach( $arr as $k=>$v ) {

		if( in_array($v, $keys) ) {
			if( empty($all[$v]['std_mark']) || $all[$v]['std_mark']==0 ) 
			{
				$result[] = array( 'std_mark'=>$all[$v]['std_mark'], 'std_grade'=>'NR' );
			} 
			else 
			{
				$result[] = array( 'std_mark'=>$all[$v]['std_mark'], 'std_grade'=>$all[$v]['std_grade'] );
			}
		} 
		else 
		{
			$result[] = array('std_mark'=>'', 'std_grade'=>'');
		}
	}
	
	unset($d,$p,$l,$c,$f,$r,$a,$cc);
	return $result;
	
	
}




function get_fake_chr_verVACATION( $sem, $rpt_list, $carryov_list, $s, $std ) {
	
	$to_go = array();
	$merger = array_merge($rpt_list, $carryov_list);
	
	if( !empty($merger) ) {
		foreach( $merger as $on ) {
			$g = substr($on, 0, 1);
			if( $g == $sem )
				$to_go[] = substr($on, 2);
		}

		
	$sql = 'SELECT `students_results`.std_grade, `students_results`.cu, `students_results`.std_mark_custom2,`all_courses`.course_code, `all_courses`.thecourse_id FROM students_results, all_courses WHERE `students_results`.stdcourse_id=`all_courses`.thecourse_id && `all_courses`.course_code IN (\''.implode("','", $to_go).'\') && `students_results`.std_mark_custom2 = '.$s.' && `students_results`.std_id = '.$std.' && `students_results`.period = "'.vacation.'" GROUP BY course_code';

	
	$r = mysqli_query($GLOBALS['connect'],  $sql ) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	$return = '';
	if( mysqli_num_rows($r) > 0 ){
		while( $g = mysqli_fetch_array($r) )
			$return .= "<br/>". $g['cu'].' '.substr_replace($g['course_code']," ",3, 0).' '.$g['std_grade'];

	}
		
		$return = substr($return, 5);
		echo strtoupper($return);
	}
		echo "";
	
}































//========================================================
function G_faculty( $f ){
	
	$r = mysqli_query($GLOBALS['connect'], 'SELECT faculties_name FROM faculties WHERE faculties_id='.$f.' LIMIT 1');
	$return = mysqli_fetch_assoc($r);
	mysqli_free_result($r);
	unset( $r, $f );
	return $return['faculties_name'];
	
}


function G_department( $d ){

	$r = mysqli_query($GLOBALS['connect'], 'SELECT departments_name FROM departments WHERE departments_id='.$d.' LIMIT 1');
	$return = mysqli_fetch_assoc($r);
	mysqli_free_result($r);
	unset( $r, $d );
	return $return['departments_name'];

}


function G_name( $s_id )
{
	
	$r = mysqli_query($GLOBALS['connect'], 'SELECT surname, firstname, othernames FROM students_profile WHERE std_id = '.$s_id.' LIMIT 1');
	$return = mysqli_fetch_assoc($r);
	mysqli_free_result($r);
	unset( $r, $s_id );
	return strtoupper($return['surname'].' '.$return['firstname'].' '.$return['othernames']);
	
}


function G_degree( $cpga, $ignore = false ) 
{
	
	if( $ignore )
		return '';
		
	switch( $cpga ){
		case $cpga <= 1.49 && $cpga >= 1.00 :
			return 'PASS';
		break;
		case $cpga <= 2.39 && $cpga >= 1.50 :
			return 'THIRD CLASS';
		break;
		case $cpga <= 3.49 && $cpga >= 2.40 :
			return 'SECOND CLASS LOWER';
		break;
		case $cpga <= 4.49 && $cpga >= 3.50 :
			return 'SECOND CLASS UPPER';
		break;
		case $cpga <= 5.00 && $cpga >= 4.50:
			return 'FIRST CLASS';
		break;
		default:
			return '---';
		break;
	}
	
}

function G_degreed( $cpga, $ignore = false ) 
{
	
	if( $ignore )
		return '';
		
	switch( $cpga ){
		case $cpga <= 1.49 && $cpga >= 1.00 :
			return 'PASS';
		break;
		case $cpga <= 2.39 && $cpga >= 1.50 :
			return 'MERIT';
		break;
		case $cpga <= 3.49 && $cpga >= 2.40 :
			return 'LOWER CREDIT';
		break;
		case $cpga <= 4.49 && $cpga >= 3.50 :
			return 'UPPER CREDIT';
		break;
		case $cpga <= 5.00 && $cpga >= 4.50:
			return 'DISTINCTION';
		break;
		default:
			return '---';
		break;
	}
	
}


function std_spillover_count( $s_id, $duration=4 ) {
	
	$sql = 'SELECT 1 FROM registered_semester WHERE std_id = '.$s_id.' && rslevelid = '.$duration.' GROUP BY rslevelid';
	$r = mysqli_query( $GLOBALS['connect'], $sql );
	$f = mysqli_num_rows($r);
	return ($f - 1); // -1 because of excluding the real final year...cos its afterward that spillover counts
}


function G_duration($s_id){
	
	$sql = 'SELECT `dept_options`.duration FROM `dept_options`,`students_profile` WHERE `dept_options`.dept_id = `students_profile`.stddepartment_id && `dept_options`.prog_id = `students_profile`.stdprogramme_id && `students_profile`.std_id = '.$s_id.' LIMIT 1';
	
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$f = mysqli_fetch_assoc($r);
	mysqli_free_result($r);
	return $f['duration'];
	
}



function G_programme($fos){
	
	$r = mysqli_query($GLOBALS['connect'], 'SELECT programme_option FROM dept_options WHERE do_id='.$fos );
	$f = mysqli_fetch_assoc($r);
	mysqli_free_result($r);
	return $f['programme_option'];
}



function total_reg_count( $s_id ){
	
	$sql = 'SELECT count(1) as res FROM course_reg WHERE std_id = '.$s_id.'';
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$return = mysqli_fetch_assoc( $r );
	mysqli_free_result($r);
	return $return['res'];
	
}


//seasonal gpa:
function tget_gpa($s, $s_id, $type='NORMAL' ){

	$r = mysqli_query($GLOBALS['connect'],  'SELECT ROUND((SUM(cp)/ SUM(cu)),2) AS gpa FROM students_results WHERE level_id != 0 && std_mark_custom2 = "'.$s.'" && std_id = '.$s_id.' && period = "'.$type.'" && std_grade != "N" && std_cstatus = "yes" LIMIT 1' );
	$row = mysqli_fetch_array( $r );
	
	$gpa = $row['gpa'];
	
	unset($sql, $r, $row);
	return $gpa;

}


function fast_filter( $s_id, $s, $l, $fos, $period = 'NORMAL' ) {
	
	$sql = 'SELECT stdcourse_id FROM students_results WHERE std_id = '.$s_id.' && std_mark_custom2 = "'.$s.'" && stdcourse_id IN (SELECT thecourse_id FROM all_courses WHERE course_custom2 = '.$fos.' && course_custom5 < "'.$s.'" && thecourse_id IN ( SELECT thecourse_id FROM course_reg WHERE std_id ='.$s_id.' && cyearsession < "'.$s.'" ))';
	
	$response = array();
	$_ = mysqli_query( $GLOBALS['connect'], $sql );
	if( 0!=mysqli_num_rows($_) ) {
		while( $d=mysqli_fetch_array($_) ) {
			$response[] = $d[0];
		}
		mysqli_free_result($_);
	}
	
	return $response;

}



/*
*
* 
*/
function std_elective_result_resit( $s_id, $s, $l, $fos, $period = 'NORMAL' ) {
	
	$sem = array();
	
	$carry_over_ignorelist = fast_filter( $s_id, $s, $l, $fos );
	//var_dump( $carry_over_ignorelist );
	//exit;
	
	$_ = mysqli_query( $GLOBALS['connect'], 'SELECT all_courses.thecourse_id,students_results.stdcourse_id, students_results.std_grade, students_results.cu, all_courses.course_code, all_courses.course_semester, all_courses.course_status FROM all_courses LEFT JOIN students_results ON all_courses.thecourse_id = students_results.stdcourse_id WHERE all_courses.level_id = students_results.level_id && all_courses.course_custom5 = students_results.std_mark_custom2 && all_courses.course_status = "E" && all_courses.course_custom2 = '.$fos.' && students_results.std_id = "'.$s_id.'" && students_results.level_id = '.$l.' && students_results.std_mark_custom2 = "'.$s.'" && students_results.std_cstatus = "YES" && students_results.period = "'.$period.'" && students_results.stdcourse_id NOT IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && cyearsession < "'.$s.'")' );
	
	/*$_ = mysqli_query( $GLOBALS['connect'], 'SELECT all_courses.thecourse_id,students_results.stdcourse_id, students_results.std_grade, students_results.cu, all_courses.course_code, all_courses.course_semester, all_courses.course_status FROM all_courses LEFT JOIN students_results ON all_courses.thecourse_id = students_results.stdcourse_id WHERE all_courses.level_id = students_results.level_id && all_courses.course_custom5 = students_results.std_mark_custom2 && all_courses.course_status = "E" && all_courses.course_custom2 = '.$fos.' && students_results.std_id = "'.$s_id.'" && students_results.level_id = '.$l.' && students_results.std_mark_custom2 = "'.$s.'" && students_results.std_cstatus = "YES" && students_results.period = "'.$period.'" && students_results.stdcourse_id NOT IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && cyearsession < "'.$s.'")' );
	*/
	
	if($s_id=='6459')
{
//echo "<hr><hr>".'SELECT students_results.stdcourse_id, students_results.std_grade, students_results.cu, all_courses.course_code, all_courses.course_semester, all_courses.course_status FROM all_courses LEFT JOIN students_results ON all_courses.thecourse_id = students_results.stdcourse_id WHERE all_courses.level_id = students_results.level_id && all_courses.course_custom5 = students_results.std_mark_custom2 && all_courses.course_status = "E" && all_courses.course_custom2 = '.$fos.' && students_results.std_id = "'.$s_id.'" && students_results.level_id = '.$l.' && students_results.std_mark_custom2 = "'.$s.'" && students_results.std_cstatus = "YES" && students_results.period = "'.$period.'" && students_results.stdcourse_id NOT IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && cyearsession < "'.$s.'")' ;
}
else
{
}
	
	if( 0 !=mysqli_num_rows($_) ) {
		
		$sem[1] = '';
		$sem[2] = '';
		$semi[1] = '';
		$semi[2] = '';

		while( $d = mysqli_fetch_assoc($_) ) 
		{
			//echo $d['stdcourse_id']."<br>";

			if( in_array($d['stdcourse_id'],$carry_over_ignorelist) ) 
			{
				//echo "Test";
				continue;
			}
			
			
			//'SELECT thecourse_id FROM all_courses WHERE thecourse_id = '.$d['thecourse_id'].' && course_custom5 <= '. ($s) .' && course_code = "'.$d['course_code']. '"'
			//exit; THIS MY CODES : THIS QUERY LOGIC =================================
			$_e = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id FROM all_courses WHERE thecourse_id = '.$d['thecourse_id'].' && course_status = "C" && course_custom5 < '. ($s) .' && thecourse_id NOT IN (SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && cyearsession < '. ($s) .' && stdcourse_custom2 = "'.$d['course_code']. '")' );
			if (0==mysqli_num_rows($_e)) 
			{
				//$_er = mysqli_num_rows($r_c);
				//??????????????????????????????????
						$sql_c = 'SELECT stdcourse_id, std_mark, std_grade FROM students_results_backup WHERE std_id = '.$s_id.' and stdcourse_id IN ('.$d['stdcourse_id'].') && std_mark_custom2='.$s.' && period != "'.vacation.'" ORDER BY stdresult_id DESC';
						$r_c = mysqli_query( $GLOBALS['connect'], $sql_c );
						if( 0!=mysqli_num_rows($r_c) ) {
							while( $data_c=mysqli_fetch_assoc($r_c) ) {
								$grade = $data_c['std_grade'];
							}
						} else {
						//mysqli_free_result($r_c);
							//$all[ $data['stdcourse_id'] ] = $data;
						
				//???????????????????????????????????????????????
				$grade = $d['std_grade']; //real grade
						}
				
				
				if( $d['course_semester'] == 'First Semester') 
				{
					$sem[1] .= $d['cu'].' '.substr_replace($d['course_code'], ' ',3,0).' '.$grade.",<br/>";
	//echo "1st";
				} 
				else 
				{
					$sem[2] .= $d['cu'].' '.substr_replace($d['course_code'], ' ',3,0).' '.$grade.",<br/>";
	//echo "2nd";
				}
			}
			
			
			
		}
		//echo "<hr>";
//print_r($carry_over_ignorelist);
		mysqli_free_result($_);
		
	}
//	print_r($sem[1]);
	return $sem;
	
}


function std_elective_result( $s_id, $s, $l, $fos, $period = 'NORMAL' ) {
	
	$sem = array();
	
	$carry_over_ignorelist = fast_filter( $s_id, $s, $l, $fos );
	//var_dump( $carry_over_ignorelist );
	//exit;
	
	$_ = mysqli_query( $GLOBALS['connect'], 'SELECT all_courses.thecourse_id,students_results.stdcourse_id, students_results.std_grade, students_results.cu, all_courses.course_code, all_courses.course_semester, all_courses.course_status FROM all_courses LEFT JOIN students_results ON all_courses.thecourse_id = students_results.stdcourse_id WHERE all_courses.level_id = students_results.level_id && all_courses.course_custom5 = students_results.std_mark_custom2 && all_courses.course_status = "E" && all_courses.course_custom2 = '.$fos.' && students_results.std_id = "'.$s_id.'" && students_results.level_id = '.$l.' && students_results.std_mark_custom2 = "'.$s.'" && students_results.std_cstatus = "YES" && students_results.period = "'.$period.'" && students_results.stdcourse_id NOT IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && cyearsession < "'.$s.'")' );
	
	/*$_ = mysqli_query( $GLOBALS['connect'], 'SELECT all_courses.thecourse_id,students_results.stdcourse_id, students_results.std_grade, students_results.cu, all_courses.course_code, all_courses.course_semester, all_courses.course_status FROM all_courses LEFT JOIN students_results ON all_courses.thecourse_id = students_results.stdcourse_id WHERE all_courses.level_id = students_results.level_id && all_courses.course_custom5 = students_results.std_mark_custom2 && all_courses.course_status = "E" && all_courses.course_custom2 = '.$fos.' && students_results.std_id = "'.$s_id.'" && students_results.level_id = '.$l.' && students_results.std_mark_custom2 = "'.$s.'" && students_results.std_cstatus = "YES" && students_results.period = "'.$period.'" && students_results.stdcourse_id NOT IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && cyearsession < "'.$s.'")' );
	*/
	
	if($s_id=='6459')
{
//echo "<hr><hr>".'SELECT students_results.stdcourse_id, students_results.std_grade, students_results.cu, all_courses.course_code, all_courses.course_semester, all_courses.course_status FROM all_courses LEFT JOIN students_results ON all_courses.thecourse_id = students_results.stdcourse_id WHERE all_courses.level_id = students_results.level_id && all_courses.course_custom5 = students_results.std_mark_custom2 && all_courses.course_status = "E" && all_courses.course_custom2 = '.$fos.' && students_results.std_id = "'.$s_id.'" && students_results.level_id = '.$l.' && students_results.std_mark_custom2 = "'.$s.'" && students_results.std_cstatus = "YES" && students_results.period = "'.$period.'" && students_results.stdcourse_id NOT IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && cyearsession < "'.$s.'")' ;
}
else
{
}
	
	if( 0 !=mysqli_num_rows($_) ) {
		
		$sem[1] = '';
		$sem[2] = '';
		$semi[1] = '';
		$semi[2] = '';

		while( $d = mysqli_fetch_assoc($_) ) 
		{
			//echo $d['stdcourse_id']."<br>";

			if( in_array($d['stdcourse_id'],$carry_over_ignorelist) ) 
			{
				//echo "Test";
				continue;
			}
			
			
			//'SELECT thecourse_id FROM all_courses WHERE thecourse_id = '.$d['thecourse_id'].' && course_custom5 <= '. ($s) .' && course_code = "'.$d['course_code']. '"'
			//exit; THIS MY CODES : THIS QUERY LOGIC =================================
			
			//$_e = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id FROM all_courses WHERE thecourse_id = '.$d['thecourse_id'].' && course_custom5 <= '. ($s) .' && course_code = "'.$d['course_code']. '"' ); //original codes
			
			//$_e = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id FROM course_reg WHERE thecourse_id = '.$d['thecourse_id'].' && cyearsession = '. ($s) .' && stdcourse_custom3 = "E" && stdcourse_custom2 = "'.$d['course_code']. '"' );
			
			$_e = mysqli_query( $GLOBALS['connect'], 'SELECT thecourse_id FROM all_courses WHERE thecourse_id = '.$d['thecourse_id'].' && course_status IN ("C") && course_custom5 <= '. ($s) .' && thecourse_id NOT IN (SELECT thecourse_id FROM course_reg WHERE std_id = '.$s_id.' && cyearsession <= '. ($s) .' && stdcourse_custom2 = "'.$d['course_code']. '")' ); // my codes that works somehow
			if (0==mysqli_num_rows($_e)) 
			{
				//$_er = mysqli_num_rows($r_c);
				$grade = $d['std_grade']; //real grade
				
				
				
				if( $d['course_semester'] == 'First Semester') 
				{
					$sem[1] .= $d['cu'].' '.substr_replace($d['course_code'], ' ',3,0).' '.$grade.",<br/>";
	//echo "1st";
				} 
				else 

				{
					$sem[2] .= $d['cu'].' '.substr_replace($d['course_code'], ' ',3,0).' '.$grade.",<br/>";
	//echo "2nd";
				}
			}
			
			
			
		}
		//echo "<hr>";
//print_r($carry_over_ignorelist);
		mysqli_free_result($_);
		
	}
//	print_r($sem[1]);
	return $sem;
	
}

function fetch_electives($std_id, $s, $l, $sem, $season="'NORMAL'" ) {
	$sem = ($sem == 1) ? "'First Semester'" : "'Second Semester'";
	/*$sql = "Select stdresult_id, std_grade, cu From students_results LEFT JOIN course_reg ON (stdcourse_id=thecourse_id) 
	Where std_id=$stdid 
	&& stdcourse_id IN (Select thecourse_id From course_reg Where stdcourse_custom3 = 'E' && std_id = $std_id && csemester=$sem && cyearsession=$s && course_season=$season) 
	&& std_mark_custom2=$s && level_id=$l && period='$season'";*/
	
	$sql = "Select cr.*, ac.thecourse_code, ac.thecourse_title From course_reg cr LEFT JOIN courses ac ON (cr.thecourse_id = ac.thecourse_id)
	WHERE cr.std_id = $std_id && cr.stdcourse_custom3 = 'E' && cr.csemester=$sem && cr.cyearsession=$s && cr.course_season=$season && cr.thecourse_id IN (Select thecourse_id From all_courses Where course_status = 'E' && level_id=$l && course_custom5=$s && course_semester=$sem)";
	// && cr.thecourse_id IN (Select thecourse_id From all_courses Where course_status != 'E' && course_custom5 < $s && course_semester=$sem)";
	$query = mysqli_query( $GLOBALS['connect'], $sql);
	//echo $sql;
	$no = mysqli_num_rows($query);
	if( 0!=$no ) { $elec = '';
		while ($r = mysqli_fetch_assoc($query)) {
			/* now since we've filtered all elective courses registered by this students,
				we now need to check if the course has been a core course before his registration
				
			*/
			$q = mysqli_query( $GLOBALS['connect'], "Select thecourse_id From all_courses Where thecourse_id = " .$r['thecourse_id']. " && course_status = 'C' && level_id < $l" );
			//echo $sql;
			if (0==mysqli_num_rows($q)) { //if not found as C, the its E, compile
			
				// ignore all courses registered by student in previous sessions
				$sql_ig = "Select thecourse_id from course_reg Where cyearsession < $s && std_id = $std_id && thecourse_id = ".$r['thecourse_id']."";
				$q_ig = mysqli_query( $GLOBALS['connect'], $sql_ig );
				if (0==mysqli_num_rows($q_ig)) {
					
					$grade = get_grade($r['std_id'],$r['thecourse_id'],$r['cyearsession'],$r['clevel_id'],$r['course_season']);
					if ($grade!=NULL && $grade!=''){
						$elec .= $r['c_unit'].' '.substr_replace($r['thecourse_code'], ' ',3,0).' '.$grade."<br/>";
						
					}
				}
				
			}
			
		}
	} else {
		$elec;
	}
	
	return $elec;
}

function get_grade($stdid,$cid,$s,$l,$season) {
	$sql = "Select stdresult_id, std_grade From students_results Where std_id=$stdid && stdcourse_id=$cid && std_mark_custom2=$s && level_id=$l && period='$season'";
	$query = mysqli_query( $GLOBALS['connect'], $sql);
	//echo $sql;
	$r = mysqli_fetch_assoc($query);
	return  $r['std_grade'] ;
}
/*
*
*	Used to get the number of registerd student in a department for a parrticular section
*
*/

function get_count_numstd_reg1( $d, $s, $l, $duration, $fos, $vacation = false ) {

//echo "LEVEL ID".$l;

	$plus = '';

	if( $vacation ) 
	{
		$vacation = ' && `students_profile`.std_id IN ( SELECT `course_reg`.std_id FROM course_reg WHERE cyearsession = "'.$s.'" && clevel_id = '.$l.' && course_season = \'VACATION\' )';
	}
	
	if( $l > $duration ) 
	{
		// spillover
		//echo "spi";
		$l = "1".$duration;
		$calc = $l - $duration;
		$l2 = "1".$duration; 
		$plus = ' && `students_profile`.std_id IN ( SELECT `students_reg`.std_id FROM students_reg WHERE yearsession IN ('.implode( ',', range($s-$calc, ($s-1)) ).') && `students_reg`.level_id = '.$l2.' )';
	} 
	else 
	{
		// normal
		//echo "norm";
		//echo "LEVEL ID".$l;
		//$plus = ' && std_id NOT IN ( SELECT std_id FROM students_reg WHERE yearsession IN ('.implode( ',', range(($s-2), ($s-1)) ).') && level_id = '.$l.' )';
	}

	$castle = mysqli_query($GLOBALS['connect'], 'SELECT COUNT(DISTINCT(students_reg.std_id)) AS size FROM students_reg INNER JOIN students_profile ON students_reg.std_id = students_profile.std_id AND students_reg.department_id =students_profile.stddepartment_id WHERE department_id = '.$d.' && yearsession="'.$s.'" && level_id = '.$l.' && students_profile.stdcourse = '.$fos.''.$vacation.$plus);


		 
	
//echo 'SELECT COUNT(DISTINCT(students_reg.std_id)) AS size FROM students_reg INNER JOIN students_profile ON students_reg.std_id = students_profile.std_id AND students_reg.department_id =students_profile.stddepartment_id WHERE department_id = '.$d.' && yearsession="'.$s.'" && level_id = '.$l.' && students_profile.stdcourse = '.$fos.''.$vacation.$plus;
//echo "LEVEL ID".$l;	
										
	$wall = mysqli_fetch_assoc( $castle );
	mysqli_free_result( $castle );
	return $wall['size'];

}


function get_count_numstd_reg( $d, $s, $l, $duration, $fos, $vacation = false ) {

//echo "LEVEL ID".$l;

	$plus = '';

	if( $vacation ) 
	{
		$vacation = ' && `students_profile`.std_id IN ( SELECT `course_reg`.std_id FROM course_reg WHERE cyearsession = "'.$s.'" && clevel_id = '.$l.' && course_season = \'VACATION\' )';
	}
	
	if( $l > $duration ) 
	{
		// spillover
		//echo "spi";
		$l = $duration;
		$calc = $l - $duration;
		$l2 = $duration;
		$plus = ' && `students_profile`.std_id IN ( SELECT `students_reg`.std_id FROM students_reg WHERE yearsession IN ('.implode( ',', range($s-$calc, ($s-1)) ).') && `students_reg`.level_id = '.$l2.' )';
	} 
	else 
	{
		// normal
		//echo "norm";
		//echo "LEVEL ID".$l;
		//$plus = ' && std_id NOT IN ( SELECT std_id FROM students_reg WHERE yearsession IN ('.implode( ',', range(($s-2), ($s-1)) ).') && level_id = '.$l.' )';
	}

	$castle = mysqli_query($GLOBALS['connect'], 'SELECT COUNT(DISTINCT(students_reg.std_id)) AS size FROM students_reg INNER JOIN students_profile ON students_reg.std_id = students_profile.std_id AND students_reg.department_id =students_profile.stddepartment_id WHERE department_id = '.$d.' && yearsession="'.$s.'" && level_id = '.$l.' && students_profile.stdcourse = '.$fos.''.$vacation.$plus);


	
//echo 'SELECT COUNT(DISTINCT(students_reg.std_id)) AS size FROM students_reg INNER JOIN students_profile ON students_reg.std_id = students_profile.std_id AND students_reg.department_id =students_profile.stddepartment_id WHERE department_id = '.$d.' && yearsession="'.$s.'" && level_id = '.$l.' && students_profile.stdcourse = '.$fos.''.$vacation.$plus;
//echo "LEVEL ID".$l;	
										
	$wall = mysqli_fetch_assoc( $castle );
	mysqli_free_result( $castle );
	return $wall['size'];

}



//custom remark fix
function fiTin( $str, $sz = 27 ){

	$stopif = array('PROBATION', 'PASS', 'WITHDRAW','Certificate of Attaindance');
	foreach( $stopif as $g ) {
		if( trim($str) == $g || substr($str,0,4) == $g /* Done to accommodate final year pass but student hass carry F*/ ){
			return $str;
		}
	}

    $keyword = array('RESIT','RPT', 'TAK', 'CAR');
    $str = str_replace('<br/>', '', $str);
    $arr = explode(',', $str);
    $arrange = array();
    $final = '';

    for( $i=0, $siz = count($arr); $i < $siz; $i++ ) {
        $arr[$i] = trim($arr[$i]);

        $ch = trim(substr($arr[$i], 0, 3));

        if( in_array($ch, $keyword) ) {
            $arrange[] = substr($final,0,-1);
            $final = '~';
        }

        if( (strlen($arr[$i]) + strlen($final)) >= $sz ) {
            $arrange[] = $final;
            $final = $arr[$i].',';
            continue;
        } else {
            $final .= $arr[$i].',';
            if( $i == ($siz - 1) ) {
                $arrange[] = substr($final,0,-1);
            }
        }


    }
    $return = '';
    foreach( $arrange as $one ) {
        $one = str_replace('~','',$one).'<br/>';
        $return .= $one;
    }
    $return = substr_replace($return,'',0,5);
    unset($arrange, $final, $ch, $i, $one, $keyword, $str, $sz);
    $return = trim($return);


    if( substr($return, -5) == '<br/>' )
        $return = substr_replace($return, '', -5);

    $g = substr($return, -1);

    if( substr($return, -1) == ',' )
        $return = substr($return, 0, -1);

        return $return;

}


//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ MY RESULT MODULES BEGIN

function test_result($stdid, $l, $s, $type) {
		$querycusum1 = "SELECT stdresult_id, std_id, matric_no, stdcourse_id, cu, COUNT(*) AS i FROM students_results
		WHERE students_results.std_grade IN ('F') 
		AND students_results.std_mark_custom1 IN ('First Semester','Second Semester') 
		AND students_results.std_id = ".$stdid."
		AND students_results.level_id = ".$l."
		AND students_results.std_mark_custom2 <=". $s."
		GROUP BY students_results.stdcourse_id";
			// echo $querycusum1."<br /><br />";
		$resultcusum1 = mysqli_query( $GLOBALS['connect'],  $querycusum1 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
		$sum =0; $return ='';
		//$num = mysqli_num_rows( $resultcusum1 );
		while($ccurow = mysqli_fetch_assoc($resultcusum1)){ // check if pass the course in vacation
			$query = "SELECT stdresult_id, std_id FROM students_results
				WHERE students_results.std_grade IN ('A','B','C','D','E') 
				AND students_results.std_mark_custom1 IN ('First Semester','Second Semester') 
				AND students_results.std_id = ".$stdid."
				AND stdcourse_id = ".$ccurow['stdcourse_id']."
				AND students_results.level_id = ".$l."
				AND students_results.std_mark_custom2 <=". $s;//"
				//GROUP BY students_results.stdcourse_id";
					// echo $querycusum1."<br /><br />";
				$result = mysqli_query( $GLOBALS['connect'],  $query );
				//$num = mysqli_num_rows( $result );
				if (0 == mysqli_num_rows($result)){ // there is fail without pass courses
					//$custd=$custd.",".$ccurow['stdresult_id'];// echo 'you';
					/*if ( $num < 3 ) {
						$sum += $ccurow['cu'];
					}*/
					if (chk_gss_course($stdid, $ccurow['stdcourse_id']) == 'true') {
						$sum += $ccurow['cu'];//add unit if gss course
					} else {
						// add unit if non-gss course and not carryF; that is less than 3 times failed
						if ( $ccurow['i'] < 3 ) {
							$sum += $ccurow['cu'];
						}
					}
				}// echo 'me';//$query;
				//$matno = $ccurow['matric_no'];
		}
		
			switch ($type) {
				case 'delay':
					if ($sum > 15) { return 'true'; }// else { return $sum; } // ^^^^^^^^^^^^^^^^^^^^^^^^^ DELYED
					break;
				case 'vacation':
					if (($sum > 7) && ($sum <= 15)) { return 'true'; } // ^^^^^^^^^^^^^^^^^^^^^^^ VACATION
					break;
				case 'fail':
					if ($sum <= 7) { return 'true'; } // ^^^^^^^^^^^^^^^^^^^^^^^^ HAS FAIL AND CONTINUING
					break;
				default:
					return '';
					break;
			}
	}

function chk_gss_course($std_id, $stdcourse_id){
	
	$sqlc = 'Select stdcourse_custom2, stdcourse_custom3, cyearsession From course_reg Where thecourse_id='.$stdcourse_id .' && std_id='.$std_id.' LIMIT 1';// && clevel_id <='.$l.' && cyearsession <='.$s.' && std_id='.$std_id;//.' ORDER BY cyearsession ASC';
				$rc = mysqli_query($GLOBALS['connect'], $sqlc);
				//$n = mysqli_num_rows($rc);
				$rowc = mysqli_fetch_assoc($rc);
				//$fail .= ', '.$rowc['stdcourse_custom2'];//. ' '.$row['cu']; //fail or not taken
				$code = substr($rowc['stdcourse_custom2'],0,3).' '.substr($rowc['stdcourse_custom2'],3,4);
				//$type = $rowc['stdcourse_custom3']; // C or E
				$type = substr($rowc['stdcourse_custom2'],0,3); // GSSS
				if ($type == 'GSS'){
					return 'true';
				} else {
					return 'false';
				}
	
}

function test_result1($stdid, $l, $s, $type) {
		$querycusum1 = "SELECT stdresult_id, std_id, matric_no, stdcourse_id, cu FROM students_results
		WHERE students_results.std_grade IN ('F') 
		AND students_results.std_mark_custom1 IN ('First Semester','Second Semester') 
		AND students_results.std_id = ".$stdid."
		AND students_results.level_id = ".$l."
		AND students_results.std_mark_custom2 =". $s."
		GROUP BY students_results.stdcourse_id";
			// echo $querycusum1."<br /><br />";
		$resultcusum1 = mysqli_query( $GLOBALS['connect'],  $querycusum1 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
		$sum =0; $return ='';
		$num = mysqli_num_rows( $resultcusum1 );
		while($ccurow = mysqli_fetch_assoc($resultcusum1)){ // check if pass the course in vacation
			$query = "SELECT stdresult_id, std_id FROM students_results
				WHERE students_results.std_grade IN ('A','B','C','D','E') 
				AND students_results.std_mark_custom1 IN ('First Semester','Second Semester') 
				AND students_results.std_id = ".$stdid."
				AND stdcourse_id = ".$ccurow['stdcourse_id']."
				AND students_results.level_id = ".$l."
				AND students_results.std_mark_custom2 =". $s;//"
				//GROUP BY students_results.stdcourse_id";
					// echo $querycusum1."<br /><br />";
				$result = mysqli_query( $GLOBALS['connect'],  $query );
				
				if (0 == mysqli_num_rows($result)){ // there is fail without pass courses
					//$custd=$custd.",".$ccurow['stdresult_id'];// echo 'you';
					if ( $num < 3 ) {
						$sum += $ccurow['cu'];
					}
					
				}// echo 'me';//$query;
				//$matno = $ccurow['matric_no'];
		}
		
			switch ($type) {
				case 'delay':
					if ($sum > 15) { return $sum; } else { return $sum; } // ^^^^^^^^^^^^^^^^^^^^^^^^^ DELYED
					break;
				case 'vacation':
					if (($sum > 7) && ($sum <= 15)) { return 'true'; } // ^^^^^^^^^^^^^^^^^^^^^^^ VACATION
					break;
				case 'fail':
					if ($sum <= 7) { return 'true'; } // ^^^^^^^^^^^^^^^^^^^^^^^^ HAS FAIL AND CONTINUING
					break;
				default:
					return '';
					break;
			}
	}



function result_check_pass($l, $std_id, $s, $cgpa, $take_ignore=false)
{	$fail=''; $pass='';$c=0;// echo '['.$s.']';
	
	if( $cgpa < 0.75 )
		return 'WITHDRAW';
	elseif( $cgpa >= 0.75 && $cgpa < 1.00 )
		return 'PROBATION';
		
		
	$sql = 'Select COUNT(*) as c, stdcourse_id, cu From students_results Where std_id='.$std_id.' && level_id <='.$l.' && std_mark_custom2 <='.$s.' && std_grade IN ("F") GROUP BY stdcourse_id';
	$r = mysqli_query($GLOBALS['connect'],$sql);
	if (mysqli_num_rows($r)!=0){ // found failed courses in the level
		while ($row = mysqli_fetch_assoc($r))
		{ $c++;
			$sql1 = 'Select DISTINCT stdcourse_id From students_results Where std_id='.$std_id.' && level_id <='.$l.' && std_mark_custom2 <='.$s.' && std_grade NOT IN ("F","N") && stdcourse_id='.$row['stdcourse_id'];
			$r1 = mysqli_query($GLOBALS['connect'],$sql1);
			if (mysqli_num_rows($r1)!=0){ //found that failed course passed in the level
				while ($row1 = mysqli_fetch_assoc($r1)){
					$pass .= ','.$row1['stdcourse_id'];
				}
			} else {
				$sqlc = 'Select stdcourse_custom2, stdcourse_custom3, cyearsession From course_reg Where thecourse_id='.$row['stdcourse_id'] .' && clevel_id <='.$l.' && cyearsession <='.$s.' && std_id='.$std_id;//.' ORDER BY cyearsession ASC';
				$rc = mysqli_query($GLOBALS['connect'], $sqlc);
				//$n = mysqli_num_rows($rc);
				$rowc = mysqli_fetch_assoc($rc);
				//$fail .= ', '.$rowc['stdcourse_custom2'];//. ' '.$row['cu']; //fail or not taken
				$code = substr($rowc['stdcourse_custom2'],0,3).' '.substr($rowc['stdcourse_custom2'],3,4);
				//$type = $rowc['stdcourse_custom3']; // C or E
				$type = substr($rowc['stdcourse_custom2'],0,3); // GSSS
				$n = $row['c'];
				if ($n >= 3){
					if ($type != 'GSS'){ //($type == 'C'){
						if (ignore_carryF ( $std_id, $row['stdcourse_id'], $s ) == '') $carryf .= ', '.$code;
					} else {
						$rept .= ', '.$code;
					}
				} else if ($n < 3) {
					$rept .= ', '.$code;
				}
			}
		}
	}
	//$fail = $fail != '' ? 'RPT '.substr($fail,2): 'PASS';
	$take = $take_ignore == true? '' : take_courses($std_id, $l, $s);
	//$rept = $carryf == $rept? '': $rept;
	$carryf = $carryf != '' ? 'CARRY F '.substr($carryf,2)."<br>" : '';
	$rept = $rept != '' ? 'RPT '. substr($rept,2) : '';
	$rept = $take != '' ? 'TAKE '. $take ."<br>".$rept : $rept;
	$dur = G_duration($std_id);
	
	if (($l >= $dur) && ($rept == '')) {
		$fail = "PASS <br>".$carryf;
	} else if (($carryf != '') && ($rept != '')) {
		$fail = $carryf . $rept;
	} else if (($carryf != '') && ($rept == '')) {
		$fail = "PASS <br>".$carryf;
	} else if (($carryf != '') || ($rept != '')) {
		$fail = $carryf . $rept;
	} else { $fail = 'PASS' ;}
	
	return $fail;
}
	
	

function result_check_pass_2($l, $std_id, $s, $cgpa)
{	$fail=''; $pass='';$c=0;// echo '['.$s.']';

	if( $cgpa < 0.75 )
		return 'WITHDRAW';
	elseif( $cgpa >= 0.75 && $cgpa < 1.00 )
		return 'PROBATION';
		
	
	
	$sql = 'Select COUNT(*) as c, stdcourse_id, cu From students_results Where std_id='.$std_id.' && level_id <='.$l.' && std_mark_custom2 <='.$s.' && std_grade IN ("F") && period="NORMAL" GROUP BY stdcourse_id';
	$r = mysqli_query($GLOBALS['connect'],$sql);
	if (mysqli_num_rows($r)!=0){ // found failed courses in the level
		while ($row = mysqli_fetch_assoc($r))
		{ $c++;
			$sql1 = 'Select DISTINCT stdcourse_id From students_results Where std_id='.$std_id.' && level_id <='.$l.' && std_mark_custom2 <='.$s.' && std_grade NOT IN ("F") && period="NORMAL" && stdcourse_id='.$row['stdcourse_id'];
			$r1 = mysqli_query($GLOBALS['connect'],$sql1);
			
			$sql2 = 'Select DISTINCT stdcourse_id From students_results Where std_id='.$std_id.' && level_id < '.$l.' && std_mark_custom2 < '.$s.' && std_grade NOT IN ("F") && period="VACATION" && stdcourse_id='.$row['stdcourse_id'];
			$r2 = mysqli_query($GLOBALS['connect'],$sql2);
			
			if (mysqli_num_rows($r1)!=0){ //found that failed course passed in the level
				//while ($row1 = mysqli_fetch_assoc($r1)){
				//	$pass .= ','.$row1['stdcourse_id'];
				//}
			} else if (mysqli_num_rows($r2)!=0){ //found that failed course passed in the previous vacation level
				//while ($row2 = mysqli_fetch_assoc($r2)){
				//	$pass .= ','.$row1['stdcourse_id'];
				//}
			} else {
				$sqlc = 'Select stdcourse_custom2, stdcourse_custom3, cyearsession From course_reg Where thecourse_id='.$row['stdcourse_id'] .' && clevel_id <='.$l.' && cyearsession <='.$s.' && std_id='.$std_id;//.' ORDER BY cyearsession ASC';
				$rc = mysqli_query($GLOBALS['connect'], $sqlc);
				//$n = mysqli_num_rows($rc);
				$rowc = mysqli_fetch_assoc($rc);
				//$fail .= ', '.$rowc['stdcourse_custom2'];//. ' '.$row['cu']; //fail or not taken
				$code = substr($rowc['stdcourse_custom2'],0,3).' '.substr($rowc['stdcourse_custom2'],3,4);
				//$type = $rowc['stdcourse_custom3']; // C or E
				$type = substr($rowc['stdcourse_custom2'],0,3); // GSSS
				$n = $row['c'];
				if ($n >= 3){
					if ($type != 'GSS'){ //($type == 'C'){
						if (ignore_carryF ( $std_id, $row['stdcourse_id'], $s ) == '') $carryf .= ', '.$code;
					} else {
						$rept .= ', '.$code;
					}
				} else if ($n < 3) {
					$rept .= ', '.$code;
				}
			}
		}
	}
	//$fail = $fail != '' ? 'RPT '.substr($fail,2): 'PASS';
	$take = take_courses($std_id, $l, $s);
	$carryf = $carryf != '' ? 'CARRY F '.substr($carryf,2)."<br>" : '';
	$rept = $rept != '' ? 'RPT '. substr($rept,2) : '';
	$rept = $take != '' ? 'TAKE '. $take ."<br>".$rept : $rept;
	$dur = G_duration($std_id);
	
	if (($l >= $dur) && ($rept == '')) {
		$fail = "PASS <br>".$carryf;
	} else if (($carryf != '') && ($rept != '')) {
		$fail = $carryf . $rept;
	} else if (($carryf != '') && ($rept == '')) {
		$fail = "PASS <br>".$carryf;
	} else if (($carryf != '') || ($rept != '')) {
		$fail = $carryf . $rept;
	} else { $fail = 'PASS' ;}
	
	return $fail;
}


function take_courses($stdid, $l, $s) 
{
	$fos = std_course( $stdid );
	//$sql = 'Select stdcourse_custom2 From course_reg Where std_id='.$stdid.' && clevel_id='.$l.' && cyearsession='.$s.' && thecourse_id NOT IN ( Select stdcourse_id From students_results Where std_id='.$stdid.' && level_id='.$l.' && std_mark_custom2='.$s.' )';
	$sql = 'Select course_code From all_courses Where course_custom2 = '.$fos.' && level_id='.$l.' && course_custom5='.$s.' && course_status = "C" && thecourse_id NOT IN ( Select stdcourse_id From students_results Where std_id='.$stdid.' && level_id='.$l.' && std_mark_custom2='.$s.' )';
	$q = mysqli_query($GLOBALS['connect'], $sql );
	$take = '';
	if (0!=mysqli_num_rows($q)) {
		while ($r = mysqli_fetch_assoc($q)){
			//$take .= ', '.substr($r['stdcourse_custom2'],0,3).' '.substr($r['stdcourse_custom2'],3,4);
			$take .= ', '.substr($r['course_code'],0,3).' '.substr($r['course_code'],3,4);
		}
	}
	
	return $take != '' ? substr($take,2) : '';
	
}

function std_course( $s_id ){
	// get field of study OR course of students
	$sql = 'SELECT stdcourse FROM students_profile WHERE std_id = '.$s_id.'';
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$return = mysqli_fetch_assoc( $r );
	mysqli_free_result($r);
	return $return['stdcourse'];
	
}


function result_check_pass_3($l, $std_id, $s, $cgpa)
{	$fail=''; $pass='';$c=0;// echo '['.$s.']';
	
	if( $cgpa < 0.75 )
		return 'WITHDRAW';
	elseif( $cgpa >= 0.75 && $cgpa < 1.00 )
		return 'PROBATION';
		
		
	$sql = 'Select COUNT(*) as c, stdcourse_id, cu From students_results Where std_id='.$std_id.' && level_id <='.$l.' && std_mark_custom2 <='.$s.' && std_grade IN ("F","N") GROUP BY stdcourse_id';
	$r = mysqli_query($GLOBALS['connect'],$sql);
	if (mysqli_num_rows($r)!=0){ // found failed courses in the level
		while ($row = mysqli_fetch_assoc($r))
		{ $c++;
			$sql1 = 'Select DISTINCT stdcourse_id From students_results Where std_id='.$std_id.' && level_id <='.$l.' && std_mark_custom2 <='.$s.' && std_grade NOT IN ("F","N") && stdcourse_id='.$row['stdcourse_id'];
			$r1 = mysqli_query($GLOBALS['connect'],$sql1);
			if (mysqli_num_rows($r1)!=0){ //found that failed course passed in the level
				while ($row1 = mysqli_fetch_assoc($r1)){
					$pass .= ','.$row1['stdcourse_id'];
				}
			} else {
				$sqlc = 'Select stdcourse_custom2, stdcourse_custom3, cyearsession From course_reg Where thecourse_id='.$row['stdcourse_id'] .' && clevel_id <='.$l.' && cyearsession <='.$s.' && std_id='.$std_id;//.' ORDER BY cyearsession ASC';
				$rc = mysqli_query($GLOBALS['connect'], $sqlc);
				//$n = mysqli_num_rows($rc); //old testing ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
				$rowc = mysqli_fetch_assoc($rc);
				//$fail .= ', '.$rowc['stdcourse_custom2'];//. ' '.$row['cu']; //fail or not taken
				$code = substr($rowc['stdcourse_custom2'],0,3).' '.substr($rowc['stdcourse_custom2'],3,4);
				//$type = $rowc['stdcourse_custom3']; // C or E
				$type = substr($rowc['stdcourse_custom2'],0,3); // GSSS
				$n = $row['c'];
				if ($n >= 3){
					if ($type != 'GSS'){ //($type == 'C'){ 
						if (ignore_carryF ( $std_id, $row['stdcourse_id'], $s ) == '') $carryf .= ', '.$code;//.'-'.$row['c']; ignore if not exist in current year
					} else {
						$rept .= ', '.$code;//.'-'.$row['c'];
					}
				} else if ($n < 3) {
					$rept .= ', '.$code;//.'-'.$row['c'];
				}
			}
		}
	}
	//$fail = $fail != '' ? 'RPT '.substr($fail,2): 'PASS';
	$take = take_courses($std_id, $l, $s);
	$carryf = $carryf != '' ? 'CARRY F '.substr($carryf,2)."<br>" : '';
	$rept = $rept != '' ? 'RPT '. substr($rept,2) : '';
	$rept = $take != '' ? 'TAKE '. $take ."<br>".$rept : $rept;
	$dur = G_duration($std_id);
	
	if (($l >= $dur) && ($rept == '')) {
		$fail = "PASS <br>".$carryf;
	} else if (($carryf != '') && ($rept != '')) {
		$fail = $carryf . $rept;
	} else if (($carryf != '') && ($rept == '')) {
		$fail = "PASS <br>".$carryf;
	} else if (($carryf != '') || ($rept != '')) {
		$fail = $carryf . $rept;
	} else { $fail = 'PASS' ;}
	
	return $fail;
}
	
	
	
function ignore_carryF ( $stdid, $thecourse_id, $s ){
	
	$sql = 'SELECT cyearsession FROM course_reg WHERE std_id = '.$stdid.' && thecourse_id = '.$thecourse_id.' && cyearsession = '.$s.'';
	$q = mysqli_query($GLOBALS['connect'], $sql );
	if ( 0 != mysqli_num_rows( $q ) ) { // carryF course not exist in same year but may exist in previous year, ignore
		return 'true';
	} else { // add this carryF course since it exist in same year
		return '';
	}
	
}
	
	// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ MY RESULT MODULES ENDS
	
	

//---------------------------------------------

function get_remarks_all($p, $f, $d, $l, $s, $s_id, $cgpa, $fos, $finalyear = false, $new=false){

	/*if( $cgpa < 0.75 )
		return 'WITHDRAW';
	elseif( $cgpa >= 0.75 && $cgpa < 1.00 )
		return 'PROBATION';*/
				
	$return = '';
	$carryf = '';
	$take = get_course_to_take_verREMARK($p, $f, $d, $l, $s, $s_id, $fos, $new);
	$repeat = get_repeat_courses_reloaded($l, $s, $s_id, $d, $fos);
	$repeat1 = get_repeat_courses_reloaded1($l, $s, $s_id, $d, $fos);
	
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

?>