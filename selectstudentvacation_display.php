<?php

	ob_start("ob_gzhandler");
	include_once './config.php';
	include_once './include_report.php';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Vacation Report</title>
<link type="text/css" href="report.css" rel="stylesheet" />
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
	
	$special = isset( $_POST['special'] ) ? $_POST['special'] : false; 

	$level_reps = get_levelreps();	

	$year_of_study = $level_reps[$l].'/'.$c_duration;
	
	if ( $l < $c_duration ) {
		echo "Page not available for level ".$level_reps[$l]." Please use the Mid-Year Vacation List Page/Option";
		exit;
	}

	if( $special ){
		
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

/*if($l > $c_duration){
    $list_courses_first = fetch_vacation_courses( $d, $p, $c_duration, 'first semester', $s, $f, $fos );
	$list_courses_sec = fetch_vacation_courses( $d, $p, $c_duration, 'second semester', $s, $f, $fos );


}else{*/
	$list_courses_first = fetch_vacation_courses( $d, $p, $l, 'first semester', $s, $f, $fos );
	$list_courses_sec = fetch_vacation_courses( $d, $p, $l, 'second semester', $s, $f, $fos );
//}
	
foreach( $list_courses_first as $lcf )
	$arr1[] = $lcf['thecourse_id'];


foreach( $list_courses_sec as $lcs )
	$arr2[] = $lcs['thecourse_id'];


$aa = count( $list_courses_first );
$ab = count( $list_courses_sec );

if( $special ){
	$cod = array('<th>CLASS OF DEGREE</th>','<th></th>','<td></td>','<th class="tB"></th>');
} else{
	$cod = array('','','','');
}

   $s22 =$s+1;
	$sess_year =$s."/".$s22;

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

			<p>SESSION: ',$sess_year,'</p>
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
		
		//$aa = !empty($aa) ? $aa : 1;
		//$ab = !empty($ab) ? $ab : 1;
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
$c=0;
 foreach ($_POST['id'] as $key => $value) {
    
    
$sql = 'SELECT DISTINCT std_id, matric_no,  surname, firstname,othernames
FROM  students_profile  WHERE std_id = '.$value.' ORDER BY matric_no, surname ASC';

$std_off_list = array();
	$r = mysqli_query($GLOBALS['connect'],  $sql ) or die('App Command Ended: No Students Found - ERROR'.mysql_error($GLOBALS['connect']));
	while($ind_std = mysqli_fetch_assoc($r) ){	










	$c++;


	echo '<tr>';
					#just to aid the get_fake_chr function
					$rpt_list = get_repeatresult_repeat( $l, $s, $ind_std['std_id'], true );
					$carryov_list = get_repeatresult_carry_over($l, $s, $p, $f, $d, $ind_std['std_id'], $fos);
		
					$grc = get_repeat_courses_111($l, $s, $ind_std['std_id'], $d, true);
		//var_dump( $rpt_list, $carryov_list );
	$sp = spill_long_vacation($l,$ind_std['std_id'],$s,'First Semester');
	$sp2 = spill_long_vacation($l,$ind_std['std_id'],$s,'Second Semester');
				
		echo '<td>',$c,'</td>',
			 '<td>',G_name($ind_std['std_id']),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>',
		 	 '<td class="s9">',$grc,
		 	 '</td>',
		 	 '<td class="s9"></td>';
			 //vacation_carrytodo( $ind_std['std_id'], $l, $s )'/*,get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos, "VACATION"),*/'
			 
			 	#gets student res 4 1st and 2nd semester then merge them
				if( $aa != 0 || $ab != 0 ) {
													
					//echo '<td class="tB s9">',get_fake_chr_verVACATION( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
					echo '<td class="tB s9"></td>';
					
					$regdcourses = fetch_Ireg($ind_std['std_id'], $s, $l);
					$first_semester = fetch_student_RESULT_vacational($ind_std['std_id'], $arr1, $s, $regdcourses);

					$second_semester = fetch_student_RESULT_vacational($ind_std['std_id'], $arr2, $s, $regdcourses);
					
					$ll = array_merge($first_semester, array(1=>array()), $second_semester);
					
	//exit('UPDATE RUNNING 3%');
					for($i=0; $i<$k; $i++) 
					{
						if( $i == $aa ) {
							echo '<td class="tB s9"></td>';
							//echo '<td class="tB s9">',get_fake_chr_verVACATION( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
						} else {
						//echo $i;
						//echo '<td class="tB"></td>';
							echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
							}
					}	
					
				} else {
				
					echo '<td>',$sp,
					'</td>',
						 '<td>',$sp2,'</td>';
				
				}
		//$remark = get_remarks_ver_VACATION($l, $s, $ind_std['std_id'], $d, $fos); // ORIGINAL
		//$remark = result_check_pass($l, $ind_std['std_id'], $s, $_cpga);
		
		$yr = substr($year_of_study,0,1);
		$calc = $yr - $c_duration;
		$magic_s = ($calc == 0) ? $s : $s-$calc;

		$_cpga = auto_cgpa_vacation($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
		//$_cpga = adv_get_cgpa_xlus($magic_s, $ind_std['std_id'], $l, 'ASC', $c_duration, ($c_duration+$calc), true);
		//$remark = result_check_pass_3($l, $ind_std['std_id'], $s, $_cpga); // MY CODE
		$remark = result_check_pass($l, $ind_std['std_id'], $s, $_cpga,'','vac'); // MY CODE
		
		if ($f == 6 ) { // faculty of agric
			if ($l >= 5) 
			{
				$remark = result_check_pass($l, $ind_std['std_id'], $s, $_cpga,'','vac');
			}
		}

//$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, false );
				
		$ignore = substr($remark,0,4) == 'PASS' ? false : true;
		
		echo '<td>',tget_gpa($s, $ind_std['std_id'],vacation),'</td>',
			 '<td class="B">',$_cpga,
			 '</td>';
			 

		
		if ($_GET["special"]=="final")
		{
			if($ignore)
			{
				//$remark=str_replace ("RPT ","CARRY F <br>",$remark)." <br>PASS";
				//$ignore=false;
			}
			//$ignore=true;	
		}
	    echo !empty($cod[2]) ? '<td class="tc B">'.G_degree($_cpga, $ignore).'</td>' : '<td class="tc B">'.G_degree($_cpga, $ignore).'</td>';			
		echo '<td class="s9"><div class="dw" style="width:140px;">',fiTin( $remark, 200),'</div></td>',
			 '</tr>';		 

}

}

	
echo '</tbody></table>';


	echo '<div class="sph block bl" style="margin-top:30px; ">
	<div style="border-bottom:1px solid #000; padding:4px 10px;" class="block B">STATISTICS</div>
	<div class="st block">
	<div><p class="a">No Of Students Registered</p> <p class="b">',$c,'</p></div>
	<div><p class="a">No of Results Published</p> <p class="b">',$c,'</p></div>
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