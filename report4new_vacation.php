<?php
	ob_start("ob_gzhandler");
	include_once './config.php';
	include_once './include_report.php';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mid Vacation Report</title>
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
	
	$special = isset( $_GET['special'] ) ? $_GET['special'] : false; 

	$level_reps = get_levelreps();	

$_SESSION['agric_setup'] = ($f == 6)? true: false;

	if( $special ){
		
		$set['class'] = array(0=>'<th>CLASS OF DEGREE</th>', 1=>'<th></th>', 2=>'<th></th>');
		$set['dr'] = 'MID VACATION DEGREE RESULTS';
		
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
		$set['dr'] = 'MID VACATIONAL RESULTS';
		
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



$year_of_study = $level_reps[$l].'/'.$c_duration;

	$list_courses_first = fetch_vacation_courses( $d, $p, $l, 'first semester', $s, $f, $fos );
	$list_courses_sec = fetch_vacation_courses( $d, $p, $l, 'second semester', $s, $f, $fos );
	
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
			<p>SEMESTER: MID VACATION</p>
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
$c=0;
$no_reg = count($std_off_list);
$after_delay=array();



if( !empty($std_off_list) ){


foreach( $std_off_list as $value ) {

		$welcome=welcomeback($value['std_id'],$_GET['s_session']);
		$delay = test_result($value['std_id'], '3', ($_GET['s_session']-1), 'delay');
		
	
				
					
//?????????????????????????????????????????????/
	//if ($delay == 'true'){	
	
$after_delay[]= $value;

	//}
}

foreach ($after_delay as $key => $ind_std) {

	 if (	(has_vac_result($ind_std['std_id'], $s, $l))) {
	 //??????????????????????????????????????????????????????????????
	 
			$c++;
			echo '<tr>';
							#just to aid the get_fake_chr function
							$rpt_list = get_repeatresult_repeat( $l, $s, $ind_std['std_id'], true );
							$carryov_list = get_repeatresult_carry_over($l, $s, $p, $f, $d, $ind_std['std_id'], $fos);

				
				$grc = get_repeat_courses_mid_year($l, $s, $ind_std['std_id'], $d);
			 
				echo '<td>',$c,'</td>',
					 '<td>',G_name($ind_std['std_id']),'</td>',
					 '<td>',$ind_std['matric_no'],'</td>',
					 '<td class="s9">',$grc,'</td>',
					 '<td class="s9">',mid_year_carryover_courses($fos,$l,$s,$ind_std['std_id']),'</td>';
					//( $ind_std['std_id'], $l, $s );
					// $carry = get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos, "VACATION");
					 
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
								//echo '<td class="tB"></td>';$ll[$i]['std_mark']
									echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
									}
							}	
						}
	
	else {
							
							echo '<td></td>',
								 '<td></td>';
						
						}
	
				
				$yr = substr($year_of_study,0,1);
				$calc = $yr - $c_duration;
				$magic_s = ($calc == 0) ? $s : $s-$calc;
		
				
				$_cpga = adv_get_cgpa_xlus($magic_s, $ind_std['std_id'], $l, 'ASC', $c_duration, ($c_duration+$calc), true);
				//$remark = get_remarks_ver_VACATION($l, $s, $ind_std['std_id'], $d, $fos);
				$remark = result_check_pass_Mid_year($l, $ind_std['std_id'], $s, $_cpga,'','vac');
				//$totake = get_course_to_take_verREMARK($p, $f, $d, $l, $s-1, $ind_std['std_id'], $fos, false);
				
			
				//$remark = result_check_pass_Mid_year($l, $ind_std['std_id'], $s, $_cpga,'true','','');
				
						
				$ignore = $remark == 'PASS' ? false : true;
				
				echo '<td>',tget_gpa($s, $ind_std['std_id'],'vacation'),'</td>',
					 '<td class="B">',$_cpga,'</td>';
					 
		
				
				if($_GET["special"]=="final")
				{
					if($ignore)
					{
						//$remark=str_replace ("RPT ","CARRY F <br>",$remark)." <br>PASS";
						$ignore=false;
					}
					//$ignore=true;	
				}
				//echo !empty($cod[2]) ? '<td class="tc B">'.G_degree($_cpga, $ignore).'</td>' : '<td class="tc B">'.G_degree($_cpga, $ignore).'</td>';			
				//print_r $totake;
				
				
				echo '<td class="s9"><div class="dw" style="width:140px;">',$remark,'</div></td>',
					 '</tr>';		 
		
		} //??????????????????????????????????????????? END
		
	}
}
	
	
echo '</tbody></table>';


	echo '<div class="sph block bl" style="margin-top:30px; ">
	<div style="border-bottom:1px solid #000; padding:4px 10px;" class="block B">STATISTICS</div>
	<div class="st block">
	<div><p class="a">No Of Students Registered</p> <p class="b">',$no_reg,'</p></div>
	<div><p class="a">No of Results Published</p> <p class="b">',$c,'</p></div>
	</div>
	</div>';//count( $std_off_list )

// get_count_numstd_reg( $d, $s, $l, $c_duration, $fos, true ) replaced with $no_reg

#signature placeholder
echo '<div class="sph block" style="margin-top:40px; display:block: overflow:hidden">',$set['bottom'],'</div>';
echo '<div class="sph center" style="text-align:center; font-size:15px; font-weight:700;">Date of Senate Approval :  .......................................................................</div>';
mysqli_close($connect);
ob_end_flush();
?>
</body>
</html>


<?php
function result_check_v($l, $std_id)
{	$fail=''; $pass='';$c=0;
	$sql = 'Select DISTINCT stdcourse_id, cu From students_results Where std_id='.$std_id.' && level_id='.$l.' && std_grade IN ("F","N")';
	$r = mysqli_query($GLOBALS['connect'],$sql);
	if (mysqli_num_rows($r)!=0){ // found failed courses in the level
		while ($row = mysqli_fetch_assoc($r))
		{ $c++;
			$sql1 = 'Select DISTINCT stdcourse_id From students_results Where std_id='.$std_id.' && level_id='.$l.' && std_grade NOT IN ("F","N") && stdcourse_id='.$row['stdcourse_id'];
			$r1 = mysqli_query($GLOBALS['connect'],$sql1);
			if (mysqli_num_rows($r1)!=0){ //found that failed course passed in the level
				while ($row1 = mysqli_fetch_assoc($r1)){
					$pass .= ','.$row1['stdcourse_id'];
				}
			}else {
				$sqlc = 'Select stdcourse_custom2 From course_reg Where thecourse_id='.$row['stdcourse_id'] .' && std_id='.$std_id;
				$rc = mysqli_query($GLOBALS['connect'], $sqlc);
				$n = mysqli_num_rows($rc);
				$rowc = mysqli_fetch_assoc($rc);
				$fail .= ', '.substr($rowc['stdcourse_custom2'],0,3).' '.substr($rowc['stdcourse_custom2'],3,4);//. ' '.$row['cu']; //fail or not taken
			}
		}
	}
	$fail = $fail != ''? substr($fail,2): '';
	return $fail;
}


function has_vac_result($stdid, $s, $l)
{
	$sql = "Select 1 From students_results Where std_id=$stdid && period='VACATION' && level_id=$l && std_mark_custom2=$s";
	$q = mysqli_query($GLOBALS['connect'],$sql);
	$no = mysqli_num_rows($q);
	if ($no != 0)
	{
		return true;
	} else {
		return false;
	}
	
	
}

?>