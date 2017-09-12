<?php
	
	ob_start("ob_gzhandler");
	include_once './config.php';
	include_once './include_report.php';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Diploma Resit Report</title>
<link type="text/css" href="report.css" rel="stylesheet" />
<base target="_blank">
</head>
<body>

<?php

	set_time_limit(0);
	$d = (int)$_GET['department_id'];
	$l = (int)$_GET['s_level'];
	$f = (int)$_GET['faculty_id'];
	$s = (int)$_GET['s_session'];
	$fos = (int)$_GET['course'];
	$c_duration = get_course_duration( $fos );
	$p = empty($_GET['programme']) ? 2 : $_GET['programme'];	
	$s_sem = "IN ('First Semester','Second Semester')";
	$special = isset($_GET['special']) ? $_GET['special'] : false;
	
	$level_reps = get_levelreps();
	$faculty_title = G_faculty($f);


$querycourseid = "SELECT DISTINCT thecourse_id FROM all_courses WHERE programme_id = '". $_GET['programme']."' AND faculty_id =  '$f' AND department_id = '$d' AND level_id = '$l' AND course_custom5 = '$s' AND course_semester $s_sem";
//echo $querycourseid;
//exit;
$resultcourseid = mysqli_query( $GLOBALS['connect'],  $querycourseid) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

while($crows = mysqli_fetch_array($resultcourseid)){

	extract($crows);
	$cc = $cc. ",'". $thecourse_id ."'";
}
$thc = " IN (". substr($cc, 1, strlen($cc)). ")";
//echo $thc;
//exit;


//report design setting

	if($special ){
		
		$set['rpt'] = array(0=>'<th>REPEAT COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['carry'] = array(0=>'<th>CARRY OVER COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB">CH</th>');
		$set['cpga'] = array(0=>'<th>CGPA</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['chr'] = array(1=>'<th class="tB s9 bbt">Repeat Result</th>', 2=>'<th class="tB"></th>');
		$set['plus'] = 1;
		$set['wrong_fix'] = '';
		
	} else {
		
		$set['rpt'] = array(0=>'', 1=>'', 2=>'');
		$set['carry'] = array(0=>'', 1=>'', 2=>'');
		//$set['cpga'] = array(0=>'', 1=>'', 2=>'');
		$set['cpga'] = array(0=>'<th>CGPA</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['chr'] = array(1=>'', 2=>'');
		$set['plus'] = 0;
		$set['wrong_fix'] = '<p style=" text-align:right;">CH</p>';

	}
	
	if( $special ){
		
		$set['class'] = array(0=>'<th>CLASS OF DEGREE</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['dr'] = 'DEGREE RESULTS';
		
		$set['bottom'] = '<p style="margin-left:50px">
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(HEAD OF DEPT)</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  <p>	
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(DEAN OF '.strtoupper($faculty_title).')</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  <p>	
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(EXTERNAL EXAMINER)</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  
					  <p style="margin-right:0;">	
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(CHAIRMAN SERVC)</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>';
	
	} else {
		
		$set['class'] = array(0=>'', 1=>'', 2=>'');
		$set['dr'] = 'DIPLOMA RESIT RESULTS';
		
		$set['bottom'] = '<p style="margin-left:80px">
							  <span>____________________________________________</span>
							  <span style="color:#000; padding-left:3px"></span>
							  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(HEAD OF DEPT)</span>
							  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
						  </p>
						  <p>	
							  <span>____________________________________________</span>
							  <span style="color:#000; padding-left:3px"></span>
							  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(DEAN OF '.strtoupper($faculty_title).')</span>
							  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
						  </p>
						  
						  <p style="margin-right:0;">	
							  <span>____________________________________________</span>
							  <span style="color:#000; padding-left:3px" ></span>
							  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(CHAIRMAN, SERVC)</span>
							  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
						  </p>';
						  
	}
	
	
//report design setting




	$fetch_year = $l;
	
	$list_courses_first = fetch_courses( $d, $p, $fetch_year, 'first semester', $s, $f, $fos );
	$list_courses_sec = fetch_courses( $d, $p, $fetch_year, 'second semester', $s, $f, $fos );


	
foreach( $list_courses_first as $lcf )
	$arr1[] = $lcf['thecourse_id'];

foreach( $list_courses_sec as $lcs )
	$arr2[] = $lcs['thecourse_id'];
	

$aa = count( $list_courses_first );
if( empty($aa) ) {
	$aa = 1;
	$list_courses_first = array('');
}


$ab = count( $list_courses_sec );
if( empty($ab) ) {
	$ab = 1;
	$list_courses_sec = array('');
}

$year_of_study = $level_reps[$l].'/'.$c_duration;
if( $p == 7 || $p == '7') {
	$xsession = $s;
} else {
	$xsession = $s.'/'.($s+1);
}

echo '<div class="bl">
	<div style="text-align:center; margin-bottom:2px;">
		<p class="br" style="font-size:18px; font-weight:700;">UNIVERSITY OF CALABAR</p>
		<p class="br" style="font-size:16px; font-weight:700;">CALABAR</p>
	</div>
	<div style="margin-bottom:2px; display:block; overflow:hidden;">
		<div class="fl">
			<p>FACULTY: ',$faculty_title,'</p>
			<p>DEPARTMENT: ',G_department($d),'</p>
			<p>PROGRAMME: ',G_programme($fos),'</p>
		</div>
		<div class="fr">
			<p>YEAR OF STUDY: ',$year_of_study,'</p>
			<p>SESSION: ',$xsession,'</p>
			<p>SEMESTER: FIRST & SECOND</p>
		</div>
	</div>
	<div style="color:#222;text-align:center; padding:3px 0; background:#f7f7fe; font-weight:700; font-size:14px;">
		<p>EXAMINATION REPORTING SHEET</p>
		<p>',$set['dr'],'</p>
	</div>
	</div>';
	

echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="TABLE">',
	'<thead>',
		'<tr class="thead">',
			'<th>S/N</th>',
			'<th>NAME</th>',
			'<th>REG NO</th>',
			$set['rpt'][0],
			$set['carry'][0],
			'<th colspan="',$aa+$set['plus']+1,'">FIRST SEMESTER RESULTS</th>',
			'<th colspan="',$ab+$set['plus']+1,'">SECOND SEMESTER RESULTS</th>',
			'<th>GPA</th>',
			$set['cpga'][0],
			$set['class'][0],
			'<th>REMARKS</th>',
		'</tr>';
	

	echo '<tr class="thead">',
		 '<th></th>',
		 '<th></th>',
		 '<th></th>',
		 $set['rpt'][1],
		 $set['carry'][1];
	

	if( $aa != 0 || $ab != 0 ) {
		
		echo $set['chr'][1];
		
		$sizea = $aa;
		$sizeb =  $aa + 1 + $ab + 1;
		
		$k = (int)($aa + $ab) + 1 + 2; // additional 2 is for the two elective spaces
		$list = array_merge( $list_courses_first, array(1=>'elective'), array(1=>''), $list_courses_sec, array(1=>'elective') );
		
		
		for($i=0; $i<$k; $i++) {

			if( $i == $sizea ) {
				// input 1st elective
				echo '<th class="tB s9 bbt">Elective</th>';
				continue;
			}
			if( $i == $sizeb ) {
				// input 2nd elective
				echo '<th class="tB s9 bbt">Elective</th>';
				continue;
			}
			
			if( $i == ($aa + 1) )
				echo $set['chr'][1];
			else {
				echo '<th class="tB"><p class="ups">',strtoupper($list[$i]['stdcourse_custom2']),'</p></th>';
			}
		}
	
	} else {
		echo '<th></th>';
	}
	
	echo 
		'<th></th>',
		 $set['cpga'][1],
		 $set['class'][1],
		 '<th></th>',
		 '</tr>';		 

	echo '<tr class="thead">',
		 '<th class="tB"></th>',
		 '<th class="tB"></th>',
		 '<th class="tB">',$set['wrong_fix'],'</th>',
		 $set['rpt'][2],
		 $set['carry'][2];

	if( $aa != 0 || $ab != 0 ) {
		//echo $k, $sizea, $sizeb;
		echo $set['chr'][2];
		
		for($i=0; $i<$k; $i++) {

			if( $i == $sizea ) {
				// input 1st elective
				echo '<th class="tB s9"></th>';
				continue;
			}
			if( $i == $sizeb ) {
				// input 2nd elective
				echo '<th class="tB s9"></th>';
				continue;
			}
			
			if( $i == ($aa + 1) )
				echo $set['chr'][2];
			else
				echo '<th class="tB">',$list[$i]['course_unit'],'</th>';
		}
	
	} else
		echo '<th></th>';
	
	
	echo '<th class="tB"></th>',
		 $set['cpga'][2],
		 $set['class'][2],
		 '<th class="tB"></th>',
		 '</tr>';		 
	echo '</thead>';
	echo '<tbody>';


$std_off_list = fetch_student_mat( $d, $p, $l, $f, $s, $fos, $special, $c_duration );


$c=0;

//To display the results

foreach( $std_off_list as $ind_std ) {
	
	$queryresit = "SELECT * FROM students_results 
			WHERE std_id = " . $ind_std['std_id']. " 
			AND std_mark_custom1 $s_sem 
			AND	std_mark_custom2 = '".$_GET['s_session']."' 
			AND std_grade IN ('F', 'N') 
			AND level_id = '".$_GET['s_level']. "'
			ORDER BY stdresult_id DESC ";
			
			
			$resultresit = mysqli_query( $GLOBALS['connect'],  $queryresit ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
	
			$num2ac   = mysqli_num_rows ( $resultresit );
			$row2ac   = mysqli_fetch_array( $resultresit );
			extract($rows);
			
			
	
	if($num2ac == 0) {
		
//################################### Diplay previous results from table students_results_backup###################################

		
	$querybk = "SELECT * FROM students_results_backup 
			WHERE stdresult_id = " . $ind_std['std_id']. " 
			AND std_mark_custom1 $s_sem 
			AND std_grade IN ('F', 'N') 
			AND std_mark_custom2 = '$s'
			AND level_id = '".$_GET['s_level']. "'
		
			ORDER BY stdresult_id DESC ";
			
			//echo $querybk;
			//exit;
			
	$resultbk = mysqli_query( $GLOBALS['connect'],  $querybk ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
	
		$bkrows = mysqli_fetch_assoc($resultbk);
		extract($bkrows);

//################################################### End display backup results

	
	} else {
	
	
//################################################### Display current result from table students_results 	
		
		$querybk = "SELECT * FROM students_results 
			WHERE stdresult_id = " . $ind_std['std_id']. " 
			AND std_mark_custom1 $s_sem 
			and stdcourse_id = ". $_GET['course']."
			AND std_mark_custom2 = '$s'
			AND level_id = '".$_GET['s_level']. "'
			AND std_grade IN ('F', 'N') 
			
			ORDER BY stdresult_id DESC ";
			
			//echo $querybk;
			//exit;
			
	$resultbk = mysqli_query( $GLOBALS['connect'],  $querybk ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
	
		$bkrows = mysqli_fetch_assoc($resultbk);
		extract($bkrows);
	
//Displaying previous records

	$fullname = $ind_std['surname'].' '.$ind_std['firstname'].' '.$ind_std['othernames'];
	$recid = $recid . $ind_std['std_id'].",";
	$cp= "Prev";
	echo '<tr>';
		echo '<td>',$cp,'</td>',
			 '<td>',strtoupper($fullname),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>';
			 
			 /*
			 $sql = 'SELECT all_courses.thecourse_id,students_results.stdcourse_id, students_results.std_grade, students_results.cu, all_courses.course_code, all_courses.course_semester, all_courses.course_status FROM all_courses LEFT JOIN students_results ON all_courses.thecourse_id = students_results.stdcourse_id WHERE all_courses.level_id = students_results.level_id && all_courses.course_custom5 = students_results.std_mark_custom2 && all_courses.course_status = "E" && all_courses.course_custom2 = '.$fos.' && students_results.std_id = "'.$ind_std['std_id'].'" && students_results.level_id = '.$l.' && students_results.std_mark_custom2 = "'.$s.'" && students_results.std_cstatus = "YES" && students_results.period = '."NORMAL".' && students_results.stdcourse_id NOT IN ( SELECT thecourse_id FROM course_reg WHERE std_id = '.$ind_std['std_id'].' && cyearsession < "'.$s.'")';
			 */
			 //echo $sql;
			 //exit;
			 
			 
//Displaying previous records for First semester Core courses


				$queryprev1 = "SELECT * FROM `course_reg`
							   WHERE clevel_id =$l
							   AND csemester = 'First Semester'
							   AND cyearsession =$s
							   AND std_id =".$ind_std['std_id']."
							   AND stdcourse_custom3 = 'C'
							   ORDER BY `course_reg`.`thecourse_id` DESC";
							   
				$resultprev1 =  mysqli_query( $GLOBALS['connect'],  $queryprev1 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
				
				 while($rowprev1 = mysqli_fetch_assoc($resultprev1)){
					 extract($rowprev1);
					 
			
					 
					 $queryprev1a = "select students_results_backup.std_grade,students_results_backup.result_flag,students_results_backup.stdresult_id
					 				 from students_results_backup,course_reg
					 				 where students_results_backup.std_id = course_reg.std_id
									 and students_results_backup.std_id = $std_id
					 				 and students_results_backup.level_id = $l
									 and students_results_backup.std_mark_custom1 ='First Semester'
									 and students_results_backup.std_mark_custom2 = $s
									 and students_results_backup.stdcourse_id = $thecourse_id
									 AND course_reg.stdcourse_custom3 = 'C'
									 AND students_results_backup.std_grade IN ('F', 'N') 
									 and course_reg.cyearsession = '$s'
									 and course_reg.stdcourse_custom2 = '".$stdcourse_custom2."'
									 group by course_reg.stdcourse_custom2";
									 
									 //echo $queryprev1a."<br /><br />";
									 //exit;
					 
					 $resultprev1a =  mysqli_query( $GLOBALS['connect'],  $queryprev1a ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
					 
					 $numprev1a  = mysqli_num_rows($resultprev1a);
					 
					 if($numprev1a != 0){
						 
					  while($rowprev1a = mysqli_fetch_assoc($resultprev1a)){
					 extract($rowprev1a);
					 
					 	echo "<td>".$std_grade."</td>";
					  }
					 }else{
						 
						 $queryprev5a = "select students_results.std_grade as std_grade3, students_results.result_flag,students_results.stdresult_id
					 				 from students_results,course_reg
					 				 where students_results.std_id = course_reg.std_id
									 and students_results.std_id = $std_id
					 				 and students_results.level_id = $l
									 and students_results.std_mark_custom1 ='First Semester'
									 and students_results.std_mark_custom2 = $s
									 and students_results.stdcourse_id = $thecourse_id
									 AND course_reg.stdcourse_custom3 = 'C'
									
									 and course_reg.cyearsession = '$s'
									 and course_reg.stdcourse_custom2 = '".$stdcourse_custom2."'
									 group by course_reg.stdcourse_custom2";
									 
									 //echo $queryprev5a."<br /><br />";
									 //exit;
					 
					 $resultprev5a =  mysqli_query( $GLOBALS['connect'],  $queryprev5a ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
					 
					 $numprev5a  = mysqli_num_rows($resultprev5a);
					 
					 if($numprev5a != 0){
						 
						  while($rowprev5a = mysqli_fetch_assoc($resultprev5a)){
					 extract($rowprev5a);
					 
						 echo "<td>".$std_grade3."</td>";
						  }
					 }else{
						 
						 echo "<td>&nbsp;</td>";
					 }
				 }
			}		
//Previous result first semester Elective
			 $normal = "NORMAL";
			 $e = "E";
			 $yes = "YES";

		$queryprev1e = "SELECT all_courses.thecourse_id,students_results.stdcourse_id,students_results.std_id, students_results.std_grade, students_results.cu, all_courses.course_code, all_courses.course_semester, all_courses.course_status 
		FROM all_courses 
		LEFT JOIN students_results 
		ON all_courses.thecourse_id = students_results.stdcourse_id 
		WHERE all_courses.level_id = students_results.level_id && all_courses.course_semester = 'First Semester' && all_courses.course_custom5 = students_results.std_mark_custom2 && all_courses.course_status = '$e' && all_courses.course_custom2 = '$fos' && students_results.std_id = ".$ind_std["std_id"]." && students_results.level_id = '$l' && students_results.std_mark_custom2 = '$s' && students_results.std_cstatus ='$yes' && students_results.period = '$normal' && students_results.stdcourse_id NOT IN ( SELECT thecourse_id FROM course_reg WHERE std_id = " .$ind_std['std_id']." && cyearsession < '$s')";
									 
									//echo $queryprev1e."<br /><br />";
									 //exit;
					 
					 $resultprev1e =  mysqli_query( $GLOBALS['connect'],  $queryprev1e ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
					 
					 $numprev1e  = mysqli_num_rows($resultprev1e);
					 
					 if($numprev1e != 0){
						 echo "<td class='tB s9'>";
					  while($rowprev1e = mysqli_fetch_assoc($resultprev1e)){
					 extract($rowprev1e);
					 	//echo $std_id. ", ".$cu.", ".$course_code. ", ". $std_grade."<br />";
						echo $cu." ".$course_code. ", ". $std_grade."<br />";
					  }
					  echo "</td>";
					 }else {
						 
						 $queryprev11e = "select students_results.std_grade as std_grade10,students_results.result_flag,students_results.stdresult_id
					 				 from students_results,course_reg
					 				 where students_results.std_id = course_reg.std_id
									 and students_results.std_id = $std_id
					 				
									 and students_results.std_mark_custom1 ='First Semester'
									 and students_results.std_mark_custom2 = $s
									 and students_results.stdcourse_id = $thecourse_id
									 AND course_reg.stdcourse_custom3 = 'E'
									 and course_reg.cyearsession = $s
									 
									  and students_results.result_flag = 'Resit'
									 group by course_reg.stdcourse_custom2";
									 
									 //echo $queryprev11e."<br /><br />";
									 //exit;
					 
					 $resultprev11e =  mysqli_query( $GLOBALS['connect'],  $queryprev11e ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
					 
					 $numprev11e  = mysqli_num_rows($resultprev11e);
					 
					 if($numprev11e != 0){
						 
						 while($rowprev11e = mysqli_fetch_assoc($resultprev11e)){
					 extract($rowprev11e);
					 
					 	echo "<td>".$std_grade10."</td>";
						 }
					 }else{
						 
						 echo "<td>&nbsp;</td>";
					 }
				}
					 
				
		
//Code for the second semester Core courses



                               $queryprev2 = "SELECT * FROM `course_reg`
							   WHERE clevel_id =$l
							   AND csemester = 'Second Semester'
							   AND cyearsession =$s
							   AND std_id =".$ind_std['std_id']."
							   AND stdcourse_custom3 = 'C'
							   ORDER BY `course_reg`.`thecourse_id` DESC";
							   
							  // echo $queryprev2;
							  // exit;
							   
				$resultprev2 =  mysqli_query( $GLOBALS['connect'],  $queryprev2 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
				
				$secondnumrec = mysqli_num_rows($resultprev2);
				//exit;
				
				 while($rowprev2 = mysqli_fetch_assoc($resultprev2)){
					 extract($rowprev2);
					 
					 //echo $stdcourse_custom2;
					 
				// }
					 
					 $queryprev2a = "select students_results_backup.std_grade,students_results_backup.result_flag,students_results_backup.stdresult_id
					 				 from students_results_backup,course_reg
					 				 where students_results_backup.std_id = course_reg.std_id
									 and students_results_backup.std_id = $std_id
					 				 and students_results_backup.level_id = $l
									 and students_results_backup.std_mark_custom1 ='Second Semester'
									 and students_results_backup.std_mark_custom2 = $s
									 and students_results_backup.stdcourse_id = $thecourse_id
									 AND course_reg.stdcourse_custom3 = 'C'
									 AND students_results_backup.std_grade IN ('F', 'N')
									 and course_reg.stdcourse_custom2 = '".$stdcourse_custom2."'
									 group by course_reg.stdcourse_custom2";
									 
									//echo $queryprev2a."<br />";
									//exit;
					 
					 $resultprev2a =  mysqli_query( $GLOBALS['connect'],  $queryprev2a ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
					 
					 $numprev2a  = mysqli_num_rows($resultprev2a);
					// echo $numprev2a;
					 //exit;
					  
					 
					  
					 //if($numprev2a != 0){
						 if($numprev2a != 0){
						 
					  while($rowprev2a = mysqli_fetch_assoc($resultprev2a)){
					 extract($rowprev2a);
					 
					 	echo "<td>".$std_grade."</td>";
					  }
					  
					 }else {
						 
						 $queryprev3a = "select students_results.std_grade as std_grade2, students_results.result_flag,students_results.stdresult_id
					 				 from students_results,course_reg
					 				 where students_results.std_id = course_reg.std_id
									 and students_results.std_id = $std_id
					 				 and students_results.level_id = $l
									 and students_results.std_mark_custom1 ='Second Semester'
									 and students_results.std_mark_custom2 = $s
									 and students_results.stdcourse_id = $thecourse_id
									 AND course_reg.stdcourse_custom3 = 'C'
								
									 and course_reg.cyearsession = $s
									 and course_reg.stdcourse_custom2 = '".$stdcourse_custom2."'
									 group by course_reg.stdcourse_custom2";
									 
									//echo $queryprev3a."<br /><br />";
									//exit;
					 
					 $resultprev3a =  mysqli_query( $GLOBALS['connect'],  $queryprev3a ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
					 
					 $numprev3a  = mysqli_num_rows($resultprev3a);
					 
					  if($numprev3a != 0){
						  while($rowprev3a = mysqli_fetch_assoc($resultprev3a)){
					 extract($rowprev3a);
						 
						 echo "<td>".$std_grade2."</td>";
						  }
					  }else {
						  
						  echo "<td>&nbsp;</td>";
						  //echo "<td>".$std_grade."</td>";
					  }
					 }
				}

//Prevoius result second semester Elective


$queryprev2e = "SELECT all_courses.thecourse_id,students_results.stdcourse_id, students_results.std_grade, students_results.cu, all_courses.course_code, all_courses.course_semester, all_courses.course_status 
FROM all_courses 
LEFT JOIN students_results 
ON all_courses.thecourse_id = students_results.stdcourse_id 
WHERE all_courses.level_id = students_results.level_id && all_courses.course_semester = 'Second Semester' && all_courses.course_custom5 = students_results.std_mark_custom2 && all_courses.course_status = '$e' && all_courses.course_custom2 = '$fos' && students_results.std_id = ".$ind_std["std_id"]." && students_results.level_id = '$l' && students_results.std_mark_custom2 = '$s' && students_results.std_cstatus ='$yes' && students_results.period = '$normal' && students_results.stdcourse_id NOT IN ( SELECT thecourse_id FROM course_reg WHERE std_id = " .$ind_std['std_id']." && cyearsession < '$s')";
									 
									// echo $queryprev1a;
									 //exit;
					 
					 $resultprev2e =  mysqli_query( $GLOBALS['connect'],  $queryprev2e ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
					 
					 $numprev2e  = mysqli_num_rows($resultprev2e);
					 
					 if($numprev2e != 0){
						 echo "<td class='tB s9'>";
					  while($rowprev2e = mysqli_fetch_assoc($resultprev2e)){
					 extract($rowprev2e);
					 
					 	//echo "<td class='tB s9'>".$cu.", ".$course_code. ", ". $std_grade."</td>";
						echo $cu." ".$course_code. ", ". $std_grade."<br />";
					  }
					  echo "</td>";
					 }else {
						 
						 $queryprev22e = "select students_results.std_grade as std_grade55, students_results.result_flag,students_results.stdresult_id
					 				 from students_results,course_reg
					 				 where students_results.std_id = course_reg.std_id
									 and students_results.std_id = $std_id
					 				
									 and students_results.std_mark_custom1 ='Second Semester'
									 and students_results.std_mark_custom2 = $s
									 and students_results.stdcourse_id = $thecourse_id
									 AND course_reg.stdcourse_custom3 = 'E'
									 AND students_results.std_grade NOT IN ('F', 'N')
									 and students_results.result_flag = 'Resit'
									 group by course_reg.stdcourse_custom2";
									 
									// echo $queryprev1a;
									 //exit;
					 
					 $resultprev22e =  mysqli_query( $GLOBALS['connect'],  $queryprev22e ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
					 
					 $numprev22e  = mysqli_num_rows($resultprev22e);
					 
					 if($numprev22e != 0){
						 echo "<td>";
						  while($rowprev22e = mysqli_fetch_assoc($resultprev22e)){
					 extract($rowprev22e);
					 
					 	echo $std_grade55.", ";
						  }
						  }else{
						 
						 //echo "<td>&nbsp;</td>";
						 }
						 echo "</td>";
					 }
	echo "<td>&nbsp;</td>";
	echo "<td>&nbsp;</td>";
	echo "<td>&nbsp;</td>";
	echo '<tr/>';
	
	 //exit;
	 
//################################## End of Displaying previous records
	 
		 $c++;
	$fullname = $ind_std['surname'].' '.$ind_std['firstname'].' '.$ind_std['othernames'];
	$recid = $recid . $ind_std['std_id'].",";
	echo '<tr>';
		echo '<td>',$c,'</td>',
			 '<td>',strtoupper($fullname),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>';
			 
			 if( $l > 20 )
			{
								
				#just to aid the get_fake_chr function
				$rpt_list = get_repeatresult_repeat( $l, $s, $ind_std['std_id'] );
				$carryov_list = get_repeatresult_carry_over($l, $s, $p, $f, $d, $ind_std['std_id'], $fos );
				
				//$carryov_list = array();
				
				$grc = get_repeat_courses_111($l, $s, $ind_std['std_id'], $d);
				
				echo '<td class="s9">',$grc,'</td>',
				 	 '<td class="s9">',get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos),'</td>';
			 }
			 	
				
			 	#gets student res 4 1st and 2nd semester then merge them
				if( $aa != 0 || $ab != 0 ) {
					
					if( $l > 11)
						echo '<td class="tB s9">',get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
					
					$regdcourses = fetch_Ireg($ind_std['std_id'], $s, $l);
					$first_semester = fetch_student_RESULT($ind_std['std_id'], $arr1, $s, $regdcourses);
					$first_semester = empty($first_semester) ? array('') : $first_semester;
					$second_semester = fetch_student_RESULT($ind_std['std_id'], $arr2, $s, $regdcourses);
					$second_semester = empty($second_semester) ? array('') : $second_semester;
					$ll = array_merge($first_semester, array(1=>array()), array(1=>array()), $second_semester, array(1=>array()) );
					
					$electives = std_elective_result( $ind_std['std_id'], $s, $l, $fos );
					
					for($i=0; $i<$k; $i++) {
						
						
						if( $i == $sizea ) {
							// input 1st elective
							echo '<td class="tB s9">',$electives[1],'</td>';
							continue;
						} if( $i == $sizeb ) {
							// input 2nd elective
							echo '<td class="tB s9">',$electives[2],'</td>';
							continue;
						}
						
						if( $i == ($aa + 1) ) {
							if( $l > 20 ) {

								#2 - for second semester and 1 for 1st semester
								echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
							}
						} else {
							//echo '<td class="tB" style="background:red"></td>';
							if( $ll[$i]['std_grade'] == '&nbsp;&nbsp;' ) {
								echo '<td class="tB" style="background:yellow"></td>';
							} else {
								echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
							}
						}
					}	
				
				} else {
					
					echo '<td></td>',
						 '<td></td>';
				
				}
	

		echo '<td>',get_gpa($s, $ind_std['std_id']),'</td>';
		
				$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
				$finalyear = ( $l == $c_duration ) ? true : false;
				$remark = get_remarksD($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear );
				
				$remark = str_replace("RPT","RESIT",$remark);
//				$remark = str_replace("WITHDRAW","Certificate of Attaindance",$remark);
				
				$ignore = substr($remark,0,4) == 'PASS' ? false : true;
				
				if( $l > 1 )
					echo '<td class="B">',$cgpa,'</td>';
			 	
				#class of degree section
				if( $special )
					echo '<td class="B tc">',G_degreed($cgpa, $ignore),'</td>';
			 
		echo '<td class="s9"><div class="dw">', $remark,'</div></td>',
			 '</tr>';

}

//################################### End display results

}
//echo $recid;
$dcount = count(explode(',',$recid)) - 1;

	
echo '</tbody></table>';
$dd=substr($l, -1);
echo "Course Duration (".$c_duration." years.)<br>";

	echo '<div class="sph block bl" style="margin-top:30px; ">
	<div style="border-bottom:1px solid #000; padding:4px 10px;" class="block B">STATISTICS</div>
	<div class="st block">
	<div><p class="a">No Of Students Registered</p> <p class="b">',count( $std_off_list ),'</p></div>
	<div><p class="a">No of Results Published</p> <p class="b">',$c,'</p></div>
	</div>
	</div>';


#signature placeholder
echo '<div class="sph block" style="margin-top:40px;">',$set['bottom'],'</div>';

echo '<div class="sph center" style="text-align:center; font-size:15px; font-weight:700;">Date of Senate Approval :  .......................................................................</div>';



$set = array();
unset($set,$course_duration, $cpga, $remark, $ignore);
mysqli_close($connect);
ob_end_flush();
?>
</body>
</html>