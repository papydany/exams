<?php
ob_start("ob_gzhandler");
	include_once './config.php';
	//include_once'./myloginquery.php';
	include_once './include_report.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Undergraduate Correctional Report</title>
<link type="text/css" href="report.css" rel="stylesheet" />
<base target="_blank">
</head>
<body>

<?php

	set_time_limit(0);
	$d = (int)$_POST['department_id'];
	$l = (int)$_POST['s_level'];
	$f = (int)$_POST['faculty_id'];
	$s = (int)$_POST['s_session'];
	$fos = (int)$_POST['course'];
	$c_duration = get_course_duration( $fos );
	$p = empty($_POST['programme']) ? 2 : $_POST['programme'];	
	$s_sem = "IN ('First Semester','Second Semester')";
	$special = isset($_POST['special']) ? $_POST['special'] : false;
	$month = $_POST['month'];
	$level_reps = get_levelreps();
	$faculty_title = G_faculty($f);
	$terminal =$c_duration + 2;
	
//report design setting

	if($l > 1 ){ // $special
		
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
		//$set['cpga'] = array(0=>'<th>CGPA</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['chr'] = array(1=>'', 2=>'');
		$set['plus'] = 0;
		$set['wrong_fix'] = '<p style=" text-align:right;">CH</p>';

	}
	
	if( $special ){
		
		$set['class'] = array(0=>'<th>CLASS OF DEGREE</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
				if($terminal == $l){
          if($s >= 2013){
		$set['dr'] ='CORRECTIONAL TERMINAL DEGREE RESULTS';
	}
}else{
		$set['dr'] = 'CORRECTIONAL DEGREE RESULTS';
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
		
		
		$set['dr'] = 'CORRECTIONAL UNDERGRADUATE RESULTS';	
		
		
		
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

						    <p>	
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(EXTERNAL EXAMINER)</span>
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

	$xsession = $s.'/'.($s+1);

	$academic_semester ="FIRST & SECOND";
	$result_first_semester ="FIRST SEMESTER RESULTS";
	$result_second_semester ="SECOND SEMESTER RESULTS";


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
			<p>SEMESTER:', $academic_semester,'</p>
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
			'<th colspan="',$aa+$set['plus']+1,'">',$result_first_semester,'</th>',
			'<th colspan="',$ab+$set['plus']+1,'">',$result_second_semester,'</th>',
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
				echo '<th class="tB"><p class="ups">',@strtoupper($list[$i]['stdcourse_custom2']),'</p></th>';
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
				echo '<th class="tB">',@$list[$i]['course_unit'],'</th>';
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


//$std_off_list = fetch_student_mat( $d, $p, $l, $f, $s, $fos, $special, $c_duration );



$c=0;

 foreach ($_POST['id'] as $key => $value) {
    
    
$sql = 'SELECT DISTINCT std_id, matric_no,  surname, firstname,othernames
FROM  students_profile  WHERE std_id = '.$value.' ORDER BY matric_no, surname ASC';


	$r = mysqli_query($GLOBALS['connect'],  $sql ) or die('App Command Ended: No Students Found - ERROR'.mysql_error($GLOBALS['connect']));
	while( $ind_std = mysqli_fetch_assoc($r) ){
	$std_off_list[]= $ind_std['std_id'];
	
	$c++;
	$fullname = $ind_std['surname'].' '.$ind_std['firstname'].' '.$ind_std['othernames'];
	$recid = $recid . $ind_std['std_id'].",";
	echo '<tr>';
		echo '<td>',$c,'<br/>Corrected Entries</td>',
			 '<td>',strtoupper($fullname),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>';
			 
			 if( $l > 1 )
			{
								
				#just to aid the get_fake_chr function
				$rpt_list = get_repeatresult_repeat( $l, $s, $ind_std['std_id'] );
				$carryov_list = get_repeatresult_carry_over($l, $s, $p, $f, $d, $ind_std['std_id'], $fos );
				
				//$carryov_list = array();
				
				//$grc = get_repeat_courses_111($l, $s, $ind_std['std_id'], $d);
				$grc = get_repeat_courses_reworked($l, $s, $ind_std['std_id'], $d);
				echo '<td class="s9">',$grc,'</td>',
				 	 '<td class="s9">',get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos),'</td>';
			 }
			 	
				
			 	#gets student res 4 1st and 2nd semester then merge them
				if( $aa != 0 || $ab != 0 ) {
					
					if( $l > 1){

             if (($f == 6) || ($d == 25)) {
							
							if ($l == 5 ) {
								//echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'First Semester'),get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
								echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'First Semester'),result_check22(3, $ind_std['std_id'], $d,'First Semester'),
								'</td>';
							
							} else if ($l > 5 ) {
								echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'First Semester'),result_check22(3, $ind_std['std_id'], $d,'First Semester'),'</td>';
							} else {
									echo '<td class="tB s9">',
									get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
									'</td>';
							}
						}else{


						echo '<td class="tB s9">',get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';

                        }

					}
					
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
							//echo '<td class="tB s9">',$electives[1],'</td>';
							echo '<td class="tB s9">',fetch_electives( $ind_std['std_id'], $s, $l, 1),'</td>';
							continue;
						}
						if( $i == $sizeb ) {
							// input 2nd elective
							//echo '<td class="tB s9">',$electives[2],'</td>';
							echo '<td class="tB s9">',fetch_electives( $ind_std['std_id'], $s, $l, 2),'</td>';
							continue;
						}
						
						if( $i == ($aa + 1) ) {
							if( $l > 1 ) { //11




if (($f == 6) || ($d == 25)) {
									
									if ($l == 5 ) {
										//echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'Second Semester'),get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
										echo '<td class="tB s9">', 
										result_check(3, $ind_std['std_id'], 'Second Semester'),
										//result_check22(3, $ind_std['std_id'], $d,'Second Semester'),
										'</td>';
									
									} else if ($l > 5 ) {
										echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'Second Semester'),result_check22(3, $ind_std['std_id'], $d,'Second Semester'),'</td>';
									} else {
									echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
									}
									//$s_take='';
								} else {
									echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
								}
								//????????????????????????????????????????????????????????????
							}
						}
								
						
						else {
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
				//$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
				
				$finalyear = ( $l == $c_duration ) ? true : false;
				//$remark = get_remarksD($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear );
				if($special){
					$remark = result_check_pass_2($l, $ind_std['std_id'], $s, $cgpa);
				}else{
				$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true );
			}
			   // $remark = result_check_pass_2($l, $ind_std['std_id'], $s, $cgpa);
				//$remark = str_replace("RPT","RESIT",$remark);
//				$remark = str_replace("WITHDRAW","Certificate of Attaindance",$remark);

				$ignore = substr($remark,0,4) == 'PASS' ? false : true;

				
				if( $l > 1 )
					echo '<td class="B">',$cgpa,'</td>';
			 	
				#class of degree section
				if( $special )
					echo '<td class="B tc">',G_degree($cgpa, $ignore),'</td>';
			 
		echo '<td class="s9"><div class="dw">', $remark,'</div></td>',
			 '</tr>';


//???????????????????????????????????????????????????????????????????????????
//???????????????????????????????????????????????????????????????????????????

//$c++;
	$fullname = $ind_std['surname'].' '.$ind_std['firstname'].' '.$ind_std['othernames'];
	$recid = $recid . $ind_std['std_id'].",";
	echo '<tr>';
		echo '<td>Prev</td>',
			 '<td>',strtoupper($fullname),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>';
			// var_dump($ind_std['std_id']);
			 
			 if( $l > 1 )
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
					
					if( $l > 1) // 11
						echo '<td class="tB s9">',get_fake_chr_corr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
					
					$regdcourses = fetch_Ireg($ind_std['std_id'], $s, $l);
					$first_semester = fetch_student_RESULT_corr($ind_std['std_id'], $arr1, $s, $regdcourses);
					$first_semester = empty($first_semester) ? array('') : $first_semester;
					$second_semester = fetch_student_RESULT_corr($ind_std['std_id'], $arr2, $s, $regdcourses);
					$second_semester = empty($second_semester) ? array('') : $second_semester;
					$ll = array_merge($first_semester, array(1=>array()), array(1=>array()), $second_semester, array(1=>array()) );
					//var_dump($second_semester);
					$electives = std_elective_result( $ind_std['std_id'], $s, $l, $fos );
					
					for($i=0; $i<$k; $i++) {
						
						
						if( $i == $sizea ) {
							// input 1st elective
							//echo '<td class="tB s9">',$electives[1],'</td>';
							echo '<td class="tB s9">',fetch_electives_corr( $ind_std['std_id'], $s, $l, 1),'</td>';
							continue;
						}
						if( $i == $sizeb ) {
							// input 2nd elective
							//echo '<td class="tB s9">',$electives[2],'</td>';
							echo '<td class="tB s9">',fetch_electives_corr( $ind_std['std_id'], $s, $l, 2),
							'</td>';
							continue;
						}
						
						if( $i == ($aa + 1) ) {
							if( $l > 1 ) { //11

								#2 - for second semester and 1 for 1st semester
								echo '<td class="tB s9">',get_fake_chr_corr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
							}
						}
						else {
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
	

		echo '<td>',get_gpa_corr($s, $ind_std['std_id']),'</td>';
		
				//$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
				$cgpa = get_cgpa_corr($s, $ind_std['std_id']);
			//	$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
				$finalyear = ( $l == $c_duration ) ? true : false;

		
				//$remark = get_remarksD1_resit($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear );
				$remark = get_remarks_corr($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear,false );
				//var_dump($remark);
				//$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, false,$fail_cu );
				//}
				$ignore = substr($remark,0,4) == 'PASS' ? false : true;
				
				if( $l > 1 )
					echo '<td class="B">',$cgpa,'</td>';
			 	
				#class of degree section
				if( $special )
					echo '<td class="B tc">',G_degree($cgpa, $ignore),'</td>';
			 
		echo '<td class="s9"><div class="dw">', $remark,'</div></td>',
			 '</tr>';
			 
}

}
//echo $recid;
$dcount = count(explode(',',$recid)) - 1;

	
echo '</tbody></table>';
$dd=substr($l, -1);
echo "Course Duration (".$c_duration." years.)<br>";


// ===============================                 reason for correction codes                   ================================
if($c > 0){

	echo'<div class="sph block bl" style="margin-top:40px;border:0px; ">';

	echo'<h2><b> Reason For Correctional Result</b></h2>';
	echo'<table width="100%" cellpadding="0" cellspacing="0" class="TABLE">';
	echo '<tr>';
		echo 
		'<td style="border-top:0px;">',
			 '<div class="rrr">REASON  FOR CORRECTION</div></td>
			 <td> <div class="ddd">DATE OF CORRECTION</div></td>
			  <td><div class="eee">EXAM OFFICER </div></td></tr>';
	$cc=0;
$reason_array=  array();

foreach( $std_off_list as $reason ) {




	$queryresit = "SELECT * FROM students_results 
			WHERE std_id = " . $reason. " 
			AND std_mark_custom1 $s_sem 
			AND	std_mark_custom2 = '".$_POST['s_session']."' 
			AND result_flag = 'Correctional' 
			AND level_id = '".$_POST['s_level']. "'
			ORDER BY stdresult_id DESC ";
			
			//echo $queryresit;
			//exit;
			
			$resultresit = mysqli_query( $GLOBALS['connect'],  $queryresit ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
	
			$num_reason   = mysqli_num_rows ( $resultresit );
			
	     
	if($num_reason != 0) { 
	
while($row_reason  = mysqli_fetch_assoc( $resultresit )){
    
	$reason_array[]= $reason;
}
}

}

//var_dump($reason_array);


	
		


$sql_c = 'SELECT *  FROM students_results_backup WHERE std_id = '.$reason_array[0].' && result_flag="Correctional" && std_mark_custom2='.$s.' && period = "'.NORMAL.'"';
		
//echo $sql_c;
			$r_c = mysqli_query( $GLOBALS['connect'], $sql_c ) or die(mysqli_error($GLOBALS['connect']));
			if( 0!=mysqli_num_rows($r_c) ) {
				//while( $data_c=mysqli_fetch_assoc($r_c) ) {
					$data_c=mysqli_fetch_assoc($r_c);
    echo '<tr>';
     echo '<td><div class="rrr"><p><b>'. ucwords($data_c['reason_of_correction']).'</b></p></div></td>
     <td><div class="ddd"><p>'.$data_c['date_of_correction'].'</p></div></td>';
    
       
     if($data_c['examofficer']!=''){
     $query = "SELECT * FROM exam_officers WHERE examofficer_id =". $data_c['examofficer'];
	$result = mysqli_query( $GLOBALS['connect'], $query) or 
	die(mysqli_error($GLOBALS['connect']));
	
     $row = mysqli_fetch_assoc($result);
     $examofficer=trim( stripslashes($row['eo_surname']).' '.stripslashes($row['eo_firstname']).' '.stripslashes($row['eo_othernames']) );
      
    echo' <td><div class="eee"><p>'.$examofficer.'</p></div></td>';
}
			echo' </tr>';	//}
			}



			
			 

//}
//}
echo'</table>';



	echo'</div>';
}

	echo '<div class="sph block bl" style="margin-top:30px; ">
	<div style="border-bottom:1px solid #000; padding:4px 10px;" class="block B">STATISTICS</div>
	<div class="st block">
	<div><p class="a">No Of Students Registered</p> <p class="b">',$c,'</p></div>
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