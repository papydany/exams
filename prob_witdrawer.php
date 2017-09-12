<?php
	
	ob_start("ob_gzhandler");
	include_once './config.php';
	include_once './include_report.php';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Probational Report</title>
<link type="text/css" href="report.css" rel="stylesheet" />
</head>
<body>

<?php

	$d = (int)$_GET['department_id'];
	$l  = (int)$_GET['s_level'];
	$f = (int)$_GET['faculty_id'];
	$s = (int)$_GET['s_session'];
	$fos = (int)$_GET['course'];
	$c_duration = get_course_duration( $fos );
	$p = empty($_GET['programme']) ? 2 : $_GET['programme'];
	
	$special = isset( $_GET['special'] ) ? $_GET['special'] : false; 

	$level_reps = get_levelreps();



	/*if( $special ){
		
		$set['class'] = array(0=>'<th>CLASS OF DEGREE</th>', 1=>'<th></th>', 2=>'<th></th>');
		$set['dr'] = 'PROBATION DEGREE RESULTS';
		$set['rpt_r'] = array(0=>'<th class="tB"></th>');
		$set['plus'] = 1;
		$set['show_rr'] = true;
		
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
	
	} else {*/
		
		$set['class'] = array(0=>'', 1=>'', 2=>'');
		$set['dr'] = array(0=>'PROBATION / WITHDRAW LIST',1=>'WITHDRAW RESULTS',2=>'WITHDRAW / CHANGE OF COURSE');
		$set['rpt_r'] = array(0=>'');
		$set['plus'] = 0;
		$set['pluw']=6;
		
		$set['show_rr'] = false;
		
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
						  
	//}




	$list_courses_first = fetch_courses_verPROBATION_me( $d, $p, $l, 'first semester', $s, $f, $fos );
	$list_courses_sec = fetch_courses_verPROBATION_me( $d, $p, $l, 'second semester', $s, $f, $fos );

	
$arr1 = array();
$arr2 = array();

//$arr1a = array();//mine
//$arr2b = array();//mine

foreach( $list_courses_first as $lcf )
	$arr1[] = $lcf['thecourse_id'];
	
	//$arr1[] = array_unique($arr1a); //my code

foreach( $list_courses_sec as $lcs )
	$arr2[] = $lcs['thecourse_id'];
	
	//$arr2[] = array_unique($arr2b); //my code
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

//$aa = count( $list_courses_first );
//$ab = count( $list_courses_sec );

//echo 'aa='.$aa.' ab='.$ab;

/*if( $special ){
	$cod = array('<th>CLASS OF DEGREE</th>','<th></th>','<td></td>');
} else*/
	$cod = array('','','');


$year_of_study = $level_reps[$l].'/'.$c_duration;
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
			<p>SEMESTER: FIRST & SECOND</p>
		</div>
	</div>
	<div style="color:#222;text-align:center; padding:3px 0; background:#f7f7fe; font-weight:700; font-size:14px;">
		<p>EXAMINATION REPORTING SHEET</p>
		<p>',$set['dr']['0'],'</p>
	</div>
	</div>';

echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="TABLE">',
	'<thead>',
		'<tr class="thead">',
			'<th>S/N</th>',
			'<th>NAME</th>',
			'<th>REG NO</th>',
			
			'<th colspan="',$aa+$set['plus'],'">FIRST SEMESTER RESULTS</th>',
			'<th colspan="',$ab+$set['plus'],'">SECOND SEMESTER RESULTS</th>',
			'<th>GPA</th>',
			'<th>CGPA</th>',
			$cod[0],
			'<th>REMARKS</th>',
		'</tr>';
	
	echo '<tr class="thead">',
		 '<th></th>',
		 '<th></th>',
		 '<th></th>';
		 
	
	if( $aa != 0 || $ab != 0 ) {
	
		echo $set['rpt_r'][0];
		
		$k = (int)($aa + $ab) + 1;
		$list = array_merge( $list_courses_first, array(1=>array()), $list_courses_sec );
	
		for($i=0; $i<$k; $i++) {
			if( $i == $aa ){
				if( $set['show_rr'] ) {
					echo '<th class="tB"></th>';
				}
			} else
				echo '<th class="tB"><p class="ups">',isset($list[$i]['stdcourse_custom2']) ? strtoupper($list[$i]['stdcourse_custom2']) : '','</p></th>';
		}
	
	} else {
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
		 '<th class="tB"></th>';
		

	if( $aa != 0 && $ab != 0 ) {

		echo $set['rpt_r'][0];
		
		for($i=0; $i<$k; $i++) {
			if( $i == $aa ){
				if( $set['show_rr'] ) {
					echo '<th class="tB"></th>';
				}
			}else{
				echo '<th class="tB">',isset($list[$i]['course_unit']) ? $list[$i]['course_unit'] : '','</th>';
			}
				}
	
	} else {
		
		echo '<th></th>';
		
	}
	
	echo '<th class="tB"></th>',
		 '<th class="tB"></th>',
		 $cod[1],
		 '<th class="tB"></th>',
		 '</tr>';
		 
	echo '</thead>';
	echo '<tbody>';

$std_off_list = fetch_student_mat_probational2( $d, $p, $l, $f, $s, $fos );
//$std_off_list = fetch_student_matric( $d, $p, $l, $f, $s );
//if( !empty($std_off_list) ){
if($s== 2012 && $l==1|| $s >2012 && $l >=1 && $l<9){
	
	$cc=($s<=2011 && $l>0 && $l<=6 || $s<=2012 && $l>1 && $l<=6 || $s== 2012 && $l==2 || $s== 2013 && $l==3 || $s== 2014 && $l==4 || $s== 2015 && $l==5 || $s== 2016 && $l==6);
	if(!$cc){
		
$b=0;
foreach( $std_off_list as $ind_std ) {
	
	$cgpa = get_cgpa($s, $ind_std['std_id']);
	
	$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
	if($fail_cu <= 15){
	
	if($cgpa >=1.00 && $cgpa <= 1.49 || $fail_cu ==15){
	
	
	$b++;
	echo '<tr>';
		echo '<td>',$b,'</td>',
			 '<td>',G_name($ind_std['std_id']),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>';
		 		if( $aa != 0 && $ab != 0 ) {
					if( $set['show_rr'] )
						echo '<td class="tB s9">',get_chr( $l, 'first semester', $s, $ind_std['std_id'] ),'</td>';
					$first_semester= fetch_student_RESULT($ind_std['std_id'], $arr1, $s);
					$second_semester= fetch_student_RESULT($ind_std['std_id'], $arr2, $s);
					$ll = array_merge($first_semester, array(''), $second_semester);
					for($i=0; $i<$k; $i++) {
						if( $i == $aa ) {
							if( $set['show_rr'] ) {
								echo '<td class="tB s9">',get_chr( $l, 'second semester', $s, $ind_std['std_id'] ),'</td>';
							}
						} else{
							echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
						}
					}	
					} else {
					
					echo '<td></td>',
						 '<td></td>';
						 }
		
		echo '<td>',get_gpa($s, $ind_std['std_id']),'</td>',
			 '<td class="B">',$cgpa,'</td>';
	    echo !empty($cod[2]) ? '<td></td>' : '';
		echo '<td class="s9"><div class="dw">',get_remarks_verPROBATION_W($s, $ind_std['std_id'],$l, $d, $cgpa, $p, $fos),'</div></td>',
			 '</tr>';		 
					
			}else{
				
			}
		}
		}
		if($b==0){
		echo '<tr><td colspan="',$aa+$ab+$set['pluw'],'">';
	
	echo '<div style="color:red;text-align:center;font-weight:bold;">Report Sheet: No Probational Student Found</div>
	</td></tr>';
	
}
  
	  //withdrawer/change of course condition

echo'<tr><td colspan="',$aa+$ab+$set['pluw'],'"><div style="color:#222;text-align:center; padding:3px 0; background:#f7f7fe; font-weight:700; font-size:14px;"><p>',
$set['dr']['2'],
'</p></div></td></tr>';

 

 $a=0;
foreach( $std_off_list as $ind_std_wc) {
	$cgpa = get_cgpa($s, $ind_std_wc['std_id']);
  $fail_cu=get_fail_crunit($l,$ind_std_wc['std_id'],$s);
if(  $cgpa >1.49 && $cgpa <= 1.5 && $fail_cu ==15 ){
	$a++;
	echo '<tr>';
		echo '<td>',$a,'</td>',
			 '<td>',G_name($ind_std_wc['std_id']),'</td>',
			 '<td>',$ind_std_wc['matric_no'],'</td>';
		 		if( $aa != 0 && $ab != 0 ) {
					
					if( $set['show_rr'] )
						echo '<td class="tB s9">',get_chr( $l, 'first semester', $s, $ind_std_wc['std_id'] ),'</td>';
										
					
					$first_semester= fetch_student_RESULT($ind_std_wc['std_id'], $arr1, $s);
					$second_semester= fetch_student_RESULT($ind_std_wc['std_id'], $arr2, $s);
					$ll = array_merge($first_semester, array(''), $second_semester);
	
					for($i=0; $i<$k; $i++) {
						if( $i == $aa ) {
							if( $set['show_rr'] ) {
								echo '<td class="tB s9">',get_chr( $l, 'second semester', $s, $ind_std_wc['std_id'] ),'</td>';
							}
						} else{
							echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
						}
					}	
					
				} else {
					echo '<td></td>',
						'<td></td>';
				
				}
		echo '<td>',get_gpa($s, $ind_std_wc['std_id']),'</td>',
			 '<td class="B">',$cgpa,'</td>';
	    echo !empty($cod[2]) ? '<td></td>' : '';
		echo '<td class="s9"><div class="dw">',get_remarks_verPROBATION_W($s, $ind_std_wc['std_id'],$l, $d, $cgpa, $p, $fos),'</div></td>',
			 '</tr>';		 
}



}
	if($a==0){
		echo '<tr><td colspan="',$aa+$ab+$set['pluw'],'">';
	
	echo '<div style="color:red;text-align:center;font-weight:bold;">Report Sheet: No Withdrawer/Change Of Course Student Found</div>
	</td></tr>';
	
}

echo'<tr><td colspan="',$aa+$ab+$set['pluw'],'"><div style="color:#222;text-align:center; padding:3px 0; background:#f7f7fe; font-weight:700; font-size:14px;"><p>',$set['dr']['1'],'</p></div></td></tr>';
	


$c=0;
foreach( $std_off_list as $ind_std_w) {
	$cgpa = get_cgpa($s, $ind_std_w['std_id']);
$fail_cu=get_fail_crunit($l,$ind_std_w['std_id'],$s);


		if($fail_cu > 15|| $cgpa >=0.00 && $cgpa <=0.99 ){
			// condition for withdrawer		
		$c++;
	echo '<tr>';
		echo '<td>',$c,'</td>',
			 '<td>',G_name($ind_std_w['std_id']),'</td>',
			 '<td>',$ind_std_w['matric_no'],'</td>';
		 		if( $aa != 0 && $ab != 0 ) {
					
					if( $set['show_rr'] )
						echo '<td class="tB s9">',get_chr( $l, 'first semester', $s, $ind_std_w['std_id'] ),'</td>';
											
					
					$first_semester= fetch_student_RESULT($ind_std_w['std_id'], $arr1, $s);
					$second_semester= fetch_student_RESULT($ind_std_w['std_id'], $arr2, $s);
					$ll = array_merge($first_semester, array(''), $second_semester);
	
					for($i=0; $i<$k; $i++) {
						if( $i == $aa ) {
							if( $set['show_rr'] ) {
								echo '<td class="tB s9">',get_chr( $l, 'second semester', $s, $ind_std_w['std_id'] ),'</td>';
							}
						} else{
							echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
						}
					}	
					
				} else {
					
					echo '<td></td>',
			
				 '<td></td>';
				
				}
		echo '<td>',get_gpa($s, $ind_std_w['std_id']),'</td>',
			 '<td class="B">',$cgpa,'</td>';
	    echo !empty($cod[2]) ? '<td></td>' : '';
		echo '<td class="s9"><div class="dw">',get_remarks_verPROBATION_W($s, $ind_std_w['std_id'],$l, $d, $cgpa, $p, $fos),'</div></td>',
			 '</tr>';	
		}
}

	if($c==0){
		echo '<tr><td colspan="',$aa+$ab+$set['pluw'],'">';
	echo '<div style="color:red;text-align:center;font-weight:bold;">Report Sheet: No Withdrawer Student Found</div>
	</td></tr>';
}
	}
}else{
	//"condition for students whose year session is below 2012";

$b=0;
foreach( $std_off_list as $ind_std ) {
	$cgpa = get_cgpa($s, $ind_std['std_id']);
	$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
		// probation condition	
	if( $cgpa >= 0.75 && $cgpa < 1.00  ){
		
	
	$b++;
	echo '<tr>';
		echo '<td>',$b,'</td>',
			 '<td>',G_name($ind_std['std_id']),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>';
		 		if( $aa != 0 && $ab != 0 ) {
					
					if( $set['show_rr'] )
						echo '<td class="tB s9">',get_chr( $l, 'first semester', $s, $ind_std['std_id'] ),'</td>';
					
					$first_semester= fetch_student_RESULT($ind_std['std_id'], $arr1, $s);
					$second_semester= fetch_student_RESULT($ind_std['std_id'], $arr2, $s);
					$ll = array_merge($first_semester, array(''), $second_semester);
	                //$ll = array_merge($first_semester, $second_semester);
					for($i=0; $i<$k; $i++) {
						if( $i == $aa ) {
							if( $set['show_rr'] ) {
								echo '<td class="tB s9">',get_chr( $l, 'second semester', $s, $ind_std['std_id'] ),'</td>';
							}
						} else
							echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
					}	
					
				} else {
					
					echo '<td></td>',
						 '<td></td>';
				
				}
		echo '<td>',get_gpa($s, $ind_std['std_id']),'</td>',
			 '<td class="B">',$cgpa,'</td>';
	    echo !empty($cod[2]) ? '<td></td>' : '';
		echo '<td class="s9"><div class="dw">',get_remarks_verPROBATION_W($s, $ind_std['std_id'],$l, $d, $cgpa, $p, $fos),'</div></td>',
			 '</tr>';		 
}
}
	if($b==0){
		echo '<tr><td colspan="',$aa+$ab+$set['pluw'],'">';
	echo '<div style="color:red;text-align:center;font-weight:bold;">Report Sheet: No Probational Student Found</div></td></tr>';
}
echo '</tbody>';
echo'<tr><td colspan="',$aa+$ab+$set['pluw'],'"><div style="color:#222;text-align:center; padding:3px 0; background:#f7f7fe; font-weight:700; font-size:14px;">
		
		<p>',$set['dr']['1'],'</p>
	</div></td></tr>';
	
$c=0;
foreach( $std_off_list as $ind_std_ww) {
	$cgpa = get_cgpa($s, $ind_std_ww['std_id']);
	$fail_cu=get_fail_crunit($l,$ind_std_ww['std_id'],$s);
	
	// condition withdrawer
	if( $cgpa < 0.75  ){
	$c++;
	echo '<tr>';
		echo '<td>',$c,'</td>',
			 '<td>',G_name($ind_std_ww['std_id']),'</td>',
			 '<td>',$ind_std_ww['matric_no'],'</td>';
		 		if( $aa != 0 && $ab != 0 ) {
					
					if( $set['show_rr'] )
						echo '<td class="tB s9">',get_chr( $l, 'first semester', $s, $ind_std_ww['std_id'] ),'</td>';
					$first_semester= fetch_student_RESULT($ind_std_ww['std_id'], $arr1, $s);
					$second_semester= fetch_student_RESULT($ind_std_ww['std_id'], $arr2, $s);
					$ll = array_merge($first_semester, array(''), $second_semester);
	
					for($i=0; $i<$k; $i++) {
						if( $i == $aa ) {
							if( $set['show_rr'] ) {
								echo '<td class="tB s9">',get_chr( $l, 'second semester', $s, $ind_std_ww['std_id'] ),'</td>';
							}
						} else
							echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
					}	
					
				} else {
					
					echo '<td></td>',
					     '<td></td>';
						 }
		$cgpa = get_cgpa($s, $ind_std_ww['std_id']);
		
		echo '<td>',get_gpa($s, $ind_std_ww['std_id']),'</td>',
			 '<td class="B">',$cgpa,'</td>';
	    echo !empty($cod[2]) ? '<td></td>' : '';
		echo '<td class="s9"><div class="dw">',get_remarks_verPROBATION_W($s, $ind_std_ww['std_id'],$l, $d, $cgpa, $p, $fos),'</div></td>',
			 '</tr>';		 
			 }
			 }
      if($c==0){
		echo '<tr><td colspan="',$aa+$ab+$set['pluw'],'">';
      echo '<div style="color:red;text-align:center;font-weight:bold;">Report Sheet: No Withdrawer Student Found</div>
	</td></tr>';
	}
	}

	
echo '</tbody></table>';

	echo '<div class="sph block bl" style="margin-top:30px; ">
	<div style="border-bottom:1px solid #000; padding:4px 10px;" class="block B">STATISTICS</div>
	<div class="st block">
	<div><p class="a">No Of Students Registered</p> <p class="b">',get_count_numstd_reg( $d, $s, $l, $c_duration, $fos ),'</p></div>
	<div><p class="a">No Of Students On Probation</p><p class="b">'.$b.'</p></div>';
	if($s== 2012 && $l==1|| $s >2012 && $l >=1 && $l<7){
		
	echo'<div><p class="a">No Of Students To Withdraw / Change Courses</p><p class="b">'.$a.'</p></div>';
	}
		
	echo'<div><p class="a">No Of Students To Withdraw</p><p class="b">'.$c.'</p></div>';
	if($s== 2012 && $l==1|| $s >2012 && $l >=1 && $l<7){
		echo'<div><p class="a">No Of Results Published</p> <p class="b">',$c+$b+$a,'</p></div>';
	}else{
		
	echo'<div><p class="a">No Of Results Published</p> <p class="b">',$c+$b,'</p></div>';
	}
	echo'</div>
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
	
	$q = mysqli_query( $GLOBALS['connect'], "Select thecourse_id, c_unit From course_reg Where thecourse_id = $thecourse_id AND cyearsession = $s ORDER BY 1 ASC LIMIT 1" );
	$r = mysqli_fetch_assoc( $q );
	return $r['c_unit'];
}
?>

<?php

function fetch_courses_verPROBATION_me( $d, $p, $l, $c, $s, $f, $fos ){
	//$s = $s - 1;
	
	$sql = 'SELECT all_courses.thecourse_id, all_courses.course_unit, all_courses.course_code as stdcourse_custom2 FROM all_courses WHERE all_courses.level_id = '.$l.' &&
	all_courses.course_semester = "'.$c.'" &&
	all_courses.programme_id = '.$p.' &&
	all_courses.faculty_id = '.$f.' &&
	all_courses.department_id = '.$d.' &&
	all_courses.course_custom5 = "'.$s.'" &&
	all_courses.course_custom2 = '.$fos.'
	ORDER BY all_courses.course_code DESC';
	
	$r = mysqli_query($GLOBALS['connect'],  $sql );
	$result = array();
	
	while( $a = mysqli_fetch_assoc($r) )
		$result[] = $a;

	mysqli_free_result($r);
	unset($d,$p,$l,$c,$f,$r,$a);
	return $result;	

}
function get_remarks_verPROBATION_W($s, $s_id, $l,  $d, $cgpa, $p, $fos){
	
	
	
	$new_prob=new_Probtion($l,$s_id, $s,$cgpa);
	if($new_prob==true){
		
	return $new_prob;}
	
	
	
/*if(	$s<=2011 && $l>0&& $l<=6||  $s<=2012 && $l>1 && $l<=6 || $s== 2012 && $l==2 || $s== 2013 && $l==3 || $s== 2014 && $l==4 || $s== 2015 && $l==5 || $s== 2016 && $l==6){
if( $cgpa < 0.75 )
		return 'WITHDRAW';
	elseif( $cgpa > 0.74 && $cgpa < 1.00 )
		return 'PROBATION';
	}
	if($s== 2012 && $l==1|| $s >2012 && $l >=1 && $l<7 ){
		if($cgpa >=1.50 && $cgpa < 2.39 && $fail_cu >= 10 && $fail_cu <=15){
			return 'PROBATION';
		}elseif($cgpa >=1.00 && $cgpa < 1.5 && $fail_cu >= 10 && $fail_cu <=15 ){
		return 'WITHDRAW OR CHANGE PROGRAMME';
		}elseif($cgpa < 1.00){
		return 'WITHDRAW';
		}
		}*/
	/*$return = '';
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
	
	
	return $return;*/

}
?>