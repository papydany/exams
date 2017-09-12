<?php
	
	ob_start("ob_gzhandler");
	include_once './config.php';
	include_once './include_report.php';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sandwich Probational Report</title>
<link type="text/css" href="report.css" rel="stylesheet" />
</head>
<body>

<?php

	$d = (int)$_GET['department_id'];
	$l  = (int)$_GET['s_level'];
	$f = (int)$_GET['faculty_id'];
	$s = $_GET['s_session'];
	$fos = (int)$_GET['course'];
	$c_duration = get_course_duration( $fos );
	$p = empty($_GET['programme']) ? 7 : $_GET['programme'];
	$month = $_GET['month'];

	$month =$month == 1? "April" : "August";
	$special = isset( $_GET['special'] ) ? $_GET['special'] : false; 

	$level_reps = get_levelreps();





	//===============================================

if( $l > 17 )
	{

		
		$set['rpt'] = array(0=>'<th>REPEAT COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['carry'] = array(0=>'<th>CARRY OVER COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB">CH</th>');
		$set['cpga'] = array(0=>'<th>CGPA</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['chr'] = array(1=>'<th class="tB s9 bbt">Repeat/Carryover Result</th>', 2=>'<th class="tB"></th>');
		$set['plus'] = 1;
		$set['wrong_fix'] = '';
		
	} else {
		
		$set['rpt'] = array(0=>'<th>SANDWICH PROBATIONAL COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['carry'] = array(0=>'<th>CARRY OVER COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB">CH</th>');
		$set['cpga'] = array(0=>'<th>CGPA</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['chr'] = array(1=>'', 2=>'');
		$set['plus'] = 0;
		$set['wrong_fix'] = '';

	}
	
	if( $special ){
		
		$set['class'] = array(0=>'<th>CLASS OF DEGREE</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		
			
			if($terminal == $l){
          if($s >= 2013){
		$set['dr'] = 'TERMINAL DEGREE RESULTS';
	}
	}else{
		$set['dr'] = 'DEGREE RESULTS';
	}
		
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
		
$set['dr'] = 'SANDWICH PROBATIONAL RESULTS';
		
		
		
		
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

						  
	}


	$list_courses_first = fetch_courses_verPROBATION_me( $d, $p, $l, 'first semester', $s, $f, $fos );
	$list_courses_sec = fetch_courses_verPROBATION_me( $d, $p, $l, 'second semester', $s, $f, $fos );

	
$arr1 = array();
$arr2 = array();



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



if( $special ){
	$cod = array('<th>CLASS OF DEGREE</th>','<th></th>','<td></td>');
} else
	$cod = array('','','');


$year_of_study = $level_reps[$l].'/'.$c_duration;


	$xsession = $s;
 if($l == 17 || $l =='17')
 {
$academic_semester ="CONTACT 1 & CONTACT 2";
$result_first_semester ="CONTACT 1 RESULTS";
$result_second_semester ="CONTACT 2 RESULTS";
 }
 elseif($l == 18 || $l =='18')
 {
$academic_semester ="CONTACT 3 & CONTACT 4";
$result_first_semester ="CONTACT 3 RESULTS";
$result_second_semester ="CONTACT 4 RESULTS";
 }

 elseif($l == 19 || $l =='19')
 {
$academic_semester ="CONTACT 5 & CONTACT 6";
$result_first_semester ="CONTACT 5 RESULTS";
$result_second_semester ="CONTACT 6 RESULTS";
 }

 else
 {
 $academic_semester ="CONTACT 7 & CONTACT 8";
 $result_first_semester ="CONTACT 7 RESULTS";
$result_second_semester ="CONTACT 8 RESULTS";
 }	



echo '<div class="bl">
	<div style="text-align:center; margin-bottom:2px;">
		<p class="br" style="font-size:18px; font-weight:700;">UNIVERSITY OF CALABAR</p>
		<p class="br" style="font-size:16px; font-weight:700;">CALABAR</p>
	</div>
	<div style="margin-bottom:2px; display:block; overflow:hidden;">
		<div class="fl">
			<p>FACULTY: ',G_faculty($f),'</p>
			<p>DEPARTMENT: ',G_department($d),'</p>
			<p>PROGRAMME: ',G_programme($fos),'</p>
		</div>
		<div class="fr">
			<p>YEAR OF STUDY: ',$year_of_study,'</p>
			<p>SESSION: ',$xsession,'</p>
			<p>SEMESTER: ',$academic_semester,'</p>
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
			'<th colspan="',$aa+$set['plus'],'">',$result_first_semester,'</th>',
			'<th colspan="',$ab+$set['plus'],'">',$result_second_semester,'</th>',
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
		
		$sizea = $aa; //+ 1;
		$sizeb =  $aa + 1 + $ab + 1;
	
		$k = (int)($aa + $ab) + 1; // additional 2 is for the two elective spaces
		$list = array_merge( $list_courses_first,  array(1=>''), $list_courses_sec);
		

		for($i=0; $i<$k; $i++) {

			
			
			if( $i == $aa )
				echo $set['chr'][1];
			else {
				echo '<th class="tB"><p class="ups">',isset($list[$i]['stdcourse_custom2']) ? strtoupper($list[$i]['stdcourse_custom2']) : '','</p></th>';
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

			
			
			if( $i == ($aa) )
				echo $set['chr'][2];
			else
				echo '<th class="tB">',isset($list[$i]['course_unit']) ? $list[$i]['course_unit'] : '','</th>';
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

$std_off_list = fetch_student_mat_probational_sandwich( $d, $p, $l, $f, $s, $fos,$month );


if( !empty($std_off_list) ){

$c=0;
foreach( $std_off_list as $ind_std ) {
	$c++;
	echo '<tr>';
		echo '<td>',$c,'</td>',
			 '<td>',G_name($ind_std['std_id']),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>';
		 
		 	 	echo '<td class="s9">',get_repeat_courses_reworked($l, $s, $ind_std['std_id'], $d),'</td>',
		
			 
		 	'<td class="s9">',get_carryover_courses_probation($l, $s, $p, $f, $d, $ind_std['std_id'], $fos),
		 	'</td>';

		 	// modifigation 3/22/17
		 	//$l2 =$l-1;
		 	$s2 =$s-1;
		 	$cc=strtok($s,"-");
           $cc =$cc-1;
           $s2 =substr_replace($s,$cc,0,4);
			$rpt_list = get_repeatresult_repeat( $l, $s2, $ind_std['std_id'] );

			//var_dump($rpt_list );


				$carryov_list = get_repeatresult_carry_over($l, $s2, $p, $f, $d, $ind_std['std_id'], $fos );
			 	#gets student res 4 1st and 2nd semester then merge them
				if( $aa != 0 && $ab != 0 ) {
					
					if( $l > 17 )

						echo '<td class="tB s9">',get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
					//else
						//echo '<td class="tB s9">',get_chr( $l, 'first semester', $s, $ind_std['std_id'] ),'</td>';
											
					
					$first_semester= fetch_student_RESULT($ind_std['std_id'], $arr1, $s);
					$second_semester= fetch_student_RESULT($ind_std['std_id'], $arr2, $s);
					$ll = array_merge($first_semester, array(''), $second_semester);
	
					for($i=0; $i<$k; $i++) {
						if( $i == $aa) {
							//echo'<td></td>';
							if( $l > 17 ) {
								echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
							}
						} else
							echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
					}	
					
				} else {
					
					echo '<td></td>',
						
						 '<td></td>';
				
				}
		
	
		$cgpa = get_cgpa($s, $ind_std['std_id']);
		
		echo '<td>',get_gpa($s, $ind_std['std_id']),'</td>',
			 '<td class="B">',get_cgpa($s, $ind_std['std_id']),'</td>';
	    echo !empty($cod[2]) ? '<td></td>' : '';
		 /**/
	
		echo '<td class="s9"><div class="dw">',get_remarks_verPROBATION22($s, $ind_std['std_id'],$l, $d, $cgpa, $p, $fos),
		'</div></td>';
	
			echo '</tr>';		

}

} else {
	echo 'Report Sheet: No Probational Student Found';
	exit;
}

	
echo '</tbody></table>';

	echo '<div class="sph block bl" style="margin-top:30px; ">
	<div style="border-bottom:1px solid #000; padding:4px 10px;" class="block B">STATISTICS</div>
	<div class="st block">
	<div><p class="a">No Of Students Registered</p> <p class="b">',get_count_numstd_reg( $d, $s, $l, $c_duration, $fos ),'</p></div>
	<div><p class="a">No of Results Published</p> <p class="b">',count( $std_off_list ),'</p></div>
	</div>
	</div>';

#signature placeholder
echo '<div class="sph block" style="margin-top:40px; display:block: overflow:hidden">',$set['bottom'],'</div>';
echo '<div class="sph center" style="text-align:center; font-size:15px; font-weight:700;">Date of Senate Approval :  .......................................................................</div>';
mysqli_close($connect);
ob_end_flush();
?>
</body>
</html>


<?php

function get_course_unit( $thecourse_id, $s ) {
	
	$q = mysqli_query( $GLOBALS['connect'], "Select thecourse_id, c_unit From course_reg Where thecourse_id = $thecourse_id AND cyearsession ='".$s."' ORDER BY 1 ASC LIMIT 1" );
	$r = mysqli_fetch_assoc( $q );
	return $r['c_unit'];
}
?>

<?php

function fetch_courses_verPROBATION_me( $d, $p, $l, $c, $s, $f, $fos ){
	// course_reg.stdcourse_custom2 included in the select query

	$s = $s - 1;
	
	$sql = 'SELECT all_courses.thecourse_id, all_courses.course_unit, all_courses.course_code as stdcourse_custom2 FROM all_courses WHERE all_courses.level_id = '.$l.' &&
	all_courses.course_semester = "'.$c.'" &&
	all_courses.programme_id = '.$p.' &&
	all_courses.faculty_id = '.$f.' &&
	all_courses.department_id = '.$d.' &&
	all_courses.course_custom5 = "'.$s.'" &&
	all_courses.course_status = "C" &&
	all_courses.course_custom2 = '.$fos.'
	ORDER BY all_courses.course_code DESC';
	
	

	
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$result = array();
	//echo 'NO='. mysqli_num_rows($r)."<br>";
	
	while( $a = mysqli_fetch_assoc($r) )
		$result[] = $a;

	mysqli_free_result($r);
	unset($d,$p,$l,$c,$f,$r,$a);
	return $result;	

}

?>