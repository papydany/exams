<?php
	
	include_once '../config.php';
	include_once '../include_report.php';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link type="text/css" href="report.css" rel="stylesheet" />
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





	if( $l > 3 ){
		
		$set['class'] = array(0=>'<th>CLASS OF DEGREE</th>', 1=>'<th></th>', 2=>'<th></th>');
		$set['dr'] = 'LONG VACATION DEGREE RESULTS';
		
		$set['bottom'] = '<p style="margin-left:50px">
						  <span>_________________________________</span>
						  <span style="color:#333; padding-left:3px"></span>
						  <span style="color:#333; padding-left:3px; font-size:10px;">(HEAD OF DEPT)</span>
						  <span style="color:#333; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  <p>	
						  <span>_________________________________</span>
						  <span style="color:#333; padding-left:3px"></span>
						  <span style="color:#333; padding-left:3px"></span>
						  <span style="color:#333; padding-left:3px; font-size:10px;">(DEAN)</span>
						  <span style="color:#333; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  <p>	
						  <span>_________________________________</span>
						  <span style="color:#333; padding-left:3px"></span>
						  <span style="color:#333; padding-left:3px; font-size:10px;">(EXTERNAL EXAMINER)</span>
						  <span style="color:#333; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  
					  <p style="margin-right:0;">	
						  <span>_________________________________</span>
						  <span style="color:#333; padding-left:3px"></span>
						  <span style="color:#333; padding-left:3px; font-size:10px;">(CHAIRMAN SERVC)</span>
						  <span style="color:#333; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>';
	
	} else {
		
		$set['class'] = array(0=>'', 1=>'', 2=>'');
		$set['dr'] = 'LONG VACATIONAL RESULTS';
		
		$set['bottom'] = '<p style="margin-left:80px">
							  <span>____________________________________________</span>
							  <span style="color:#333; padding-left:3px"></span>
							  <span style="color:#333; padding-left:3px; font-size:10px;">(HEAD OF DEPT)</span>
							  <span style="color:#333; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
						  </p>
						  <p>	
							  <span>____________________________________________</span>
							  <span style="color:#333; padding-left:3px"></span>
							  <span style="color:#333; padding-left:3px; font-size:10px;">(DEAN)</span>
							  <span style="color:#333; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
						  </p>
						  
						  <p style="margin-right:0;">	
							  <span>____________________________________________</span>
							  <span style="color:#333; padding-left:3px"></span>
							  <span style="color:#333; padding-left:3px; font-size:10px;">(CHAIRMAN, SERVC)</span>
							  <span style="color:#333; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
						  </p>';
						  
	}



$year_of_study = $l.'/'.$c_duration;

	$list_courses_first = fetch_courses( $d, $p, $l, 'first semester', $s, $f, $fos );
	$list_courses_sec = fetch_courses( $d, $p, $l, 'second semester', $s, $f, $fos );
	
foreach( $list_courses_first as $lcf )
	$arr1[] = $lcf['thecourse_id'];

foreach( $list_courses_sec as $lcs )
	$arr2[] = $lcs['thecourse_id'];


$aa = count( $list_courses_first );
$ab = count( $list_courses_sec );

if( $l>3 ){
	$cod = array('<th>CLASS OF DEGREE</th>','<th></th>','<td></td>','<th class="tB"></th>');
} else
	$cod = array('','','','');


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
			<p>SESSION: ',$s,'/',$s+1,'</p>
			<p>SEMESTER: LONG VACATION</p>
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
			'<th>REPEAT COURSES</th>',
			'<th>CARRY OVER COURSES</th>',
			'<th colspan="',$aa+1,'">FIRST SEMESTER RESULTS</th>',
			'<th colspan="',$ab+1,'">SECOND SEMESTER RESULTS</th>',
			'<th>GPA</th>',
			'<th>CGPA</th>',
			$cod[0],
			'<th>REMARKS</th>',
		'</tr>';
	
	echo '<tr class="thead">',
		 '<th></th>',
		 '<th></th>',
		 '<th></th>',
		 '<th></th>',
		 '<th></th>';
	
	if( $aa != 0 || $ab != 0 ) {
	
		echo '<th class="tB"></th>';

		$k = (int)($aa + $ab) + 1;
		$list = array_merge( $list_courses_first, array(1=>array()), $list_courses_sec );
	
		for($i=0; $i<$k; $i++) {
			if( $i == $aa ){
				echo '<th class="tB"></th>';
			} else
				echo '<th class="tB"><p class="ups">',$list[$i]['stdcourse_custom2'],'</p></th>';
		}
	
	} else {
		echo '<th></th>';
		echo '<th></th>';
	}
	
	echo '<th></th>',
		 '<th></th>',
		 $cod[1],
		 '<th></th>',
		 '</tr>';		 

	echo '<tr class="thead">',
		 '<th class="tB"></th>',
		 '<th class="tB"></th>',
		 '<th class="tB"></th>',
		 '<th class="tB"></th>',
		 '<th class="tB">CH</th>';

	if( $aa != 0 || $ab != 0 ) {

		echo '<th class="tB"></th>';
		
		for($i=0; $i<$k; $i++) {
			if( $i == $aa )
				echo '<th class="tB"></th>';
			else
				echo '<th class="tB">',$list[$i]['course_unit'],'</th>';
		}
	
	} else {
		
		echo '<th></th>';
		echo '<th></th>';
		
	}
	
	echo '<th class="tB"></th>',
		 '<th class="tB"></th>',
		 $cod[3],
		 '<th class="tB"></th>',
		 '</tr>';		 
	echo '</thead>';
	echo '<tbody>';

$std_off_list = fetch_student_mat_vacation( $d, $p, $l, $f, $s, $fos );


if( !empty($std_off_list) ){

$c=0;
foreach( $std_off_list as $ind_std ) {
	$c++;
	echo '<tr>';
					#just to aid the get_fake_chr function
					$rpt_list = get_repeatresult_repeat( $l, $s, $ind_std['std_id'], true );
					$carryov_list = get_repeatresult_carry_over($l, $s, $p, $f, $d, $ind_std['std_id'], $fos);
		
					$grc = get_repeat_courses_111($l, $s, $ind_std['std_id'], $d, true);
		//var_dump( $rpt_list, $carryov_list );
		
		echo '<td>',$c,'</td>',
			 '<td>',G_name($ind_std['std_id']),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>',
		 	 '<td class="s9">',$grc,'</td>',
		 	 '<td class="s9">',get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos, "VACATION"),'</td>';
			 //vacation_carrytodo( $ind_std['std_id'], $l, $s )
			 
			 	#gets student res 4 1st and 2nd semester then merge them
				if( $aa != 0 || $ab != 0 ) {
													
					//echo '<td class="tB s9">',get_chr( $l, 'first semester', $s, $ind_std['std_id'] ),'</td>';
					echo '<td class="tB s9">',get_fake_chr_verVACATION( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
					
					
					//$first_semester= fetch_student_RESULT_vacational($ind_std['std_id'], $arr1, $s);
					//$second_semester= fetch_student_RESULT_vacational($ind_std['std_id'], $arr1, $s);
					//$ll = array_merge($first_semester, array(''), $second_semester);

					$regdcourses = fetch_Ireg($ind_std['std_id'], $s, $l);
					$first_semester = fetch_student_RESULT_vacational($ind_std['std_id'], $arr1, $s, $regdcourses);
					$first_semester = empty($first_semester) ? array('') : $first_semester;
					$second_semester = fetch_student_RESULT_vacational($ind_std['std_id'], $arr2, $s, $regdcourses);
					$second_semester = empty($second_semester) ? array('') : $second_semester;
					$ll = array_merge($first_semester, array(1=>array()), $second_semester);					
	
					for($i=0; $i<$k; $i++) {
						if( $i == $aa )
							echo '<td class="tB s9">',get_fake_chr_verVACATION( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
						else
							echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
					}	
					
				} else {
					
					echo '<td></td>',
						 '<td></td>';
				
				}
		$remark = get_remarks_ver_VACATION($l, $s, $ind_std['std_id'], $d, $fos);
		//$_cpga = vac_get_cgpa($s, $ind_std['std_id']); 
		
		$yr = substr($year_of_study,0,1);
		$calc = $yr - $c_duration;
		$magic_s = ($calc == 0) ? $s : $s-$calc;
		//echo $duration;
		$_cpga = adv_get_cgpa_xlus( $magic_s, $ind_std['std_id'], $l, 'ASC', $c_duration, ($c_duration+$calc), true);
		
		$ignore = $remark == 'PASS' ? false : true;
		echo '<td>',tget_gpa($s, $ind_std['std_id'],vacation),'</td>',
			 '<td class="B">',$_cpga,'</td>';
	    echo !empty($cod[2]) ? '<td class="tc B">'.G_degree($_cpga, $ignore).'</td>' : '';	
		
		echo '<td class="s9"><div class="dw" style="width:140px;">',fiTin( $remark, 200),'</div></td>',
			 '</tr>';		 

}

}

	
echo '</tbody></table>';


	echo '<div class="sph block bl" style="margin-top:30px; ">
	<div style="border-bottom:1px solid #000; padding:4px 10px;" class="block B">STATISTICS</div>
	<div class="st block">
	<div><p class="a">No Of Students Registered</p> <p class="b">',get_count_numstd_reg( $d, $s, $l, $c_duration, $fos, true ),'</p></div>
	<div><p class="a">No of Results Published</p> <p class="b">',count( $std_off_list ),'</p></div>
	</div>
	</div>';


#signature placeholder
echo '<div class="sph block" style="margin-top:40px; display:block: overflow:hidden">',$set['bottom'],'</div>';
echo '<div class="sph center" style="text-align:center; font-size:15px; font-weight:700;">Date of Senate Approval :  .......................................................................</div>';
mysqli_close($connect);
?>
</body>
</html>