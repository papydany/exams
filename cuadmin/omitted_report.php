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

	$d = (int)$_GET['department_id'];
	$l  = (int)$_GET['s_level'];
	$f = (int)$_GET['faculty_id'];
	$s = (int)$_GET['s_session'];
	$fos = (int)$_GET['course'];
	$c_duration = get_course_duration( $fos );
	$p = empty($_GET['programme']) ? 2 : $_GET['programme'];
	
	


	if( $l > 3 ){
		
		$set['class'] = array(0=>'<th>CLASS OF DEGREE</th>', 1=>'<th></th>', 2=>'<th></th>');
		$set['dr'] = 'OMITTED DEGREE RESULTS';
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
	
	} else {
		
		$set['class'] = array(0=>'', 1=>'', 2=>'');
		$set['dr'] = 'OMITTED RESULTS';
		$set['rpt_r'] = array(0=>'');
		$set['plus'] = 0;
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
						  
	}
	



	$list_courses_first = fetch_courses( $d, $p, $l, 'first semester', $s, $f, $fos );
	$list_courses_sec = fetch_courses( $d, $p, $l, 'second semester', $s, $f, $fos );
	
foreach( $list_courses_first as $lcf )
	$arr1[] = $lcf['thecourse_id'];

foreach( $list_courses_sec as $lcs )
	$arr2[] = $lcs['thecourse_id'];


$aa = count( $list_courses_first );
$ab = count( $list_courses_sec );

if( $l>3 ){
	$cod = array('<th>CLASS OF DEGREE</th>','<th></th>','<td></td>');
} else
	$cod = array('','','');

$year_of_study = $l.'/'.$c_duration;
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
		 '<th></th>',
		 '<th></th>',
		 '<th></th>';
	
	if( $aa != 0 && $ab != 0 ) {
	
		//set 
		echo $set['rpt_r'][0];
		
		$k = (int)($aa + $ab) + 1;
		$list = array_merge( $list_courses_first, array(1=>array()), $list_courses_sec );
	
		for($i=0; $i<$k; $i++) {
			if( $i == $aa ) {
				if( $set['show_rr'] ) {
					//set
					echo '<th class="tB">h</th>';
				}
			} else
				echo '<th class="tB"><p class="ups">',$list[$i]['stdcourse_custom2'],'</p></th>';
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
		 '<th class="tB"></th>',
		 '<th class="tB"></th>',
		 '<th class="tB">CH</th>';

	if( $aa != 0 && $ab != 0 ) {

		//set
		echo $set['rpt_r'][0];
		
		for($i=0; $i<$k; $i++) {
			if( $i == $aa ){
				if( $set['show_rr'] ) {
					//set 
					echo '<th class="tB"></th>';
				}
			}else
				echo '<th class="tB">',$list[$i]['course_unit'],'</th>';
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
//exit;


$std_off_list = fetch_student_mat_omitted( $d, $p, $l, $f, $s, $fos );
if( !empty($std_off_list) ){

$c=0;
foreach( $std_off_list as $ind_std ) {
	$c++;
	echo '<tr>';
		echo '<td>',$c,'</td>',
			 '<td>',G_name($ind_std['std_id']),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>',
		 	 '<td class="s9">',get_repeat_courses_222($l, $s, $ind_std['std_id'],$fos),'</td>',
		 	 '<td class="s9">',get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id']),'</td>';
			 	#gets student res 4 1st and 2nd semester then merge them
				if( $aa != 0 && $ab != 0 ) {
					
					if( $set['show_rr'] ) {
						//set
						echo '<td class="tB s9">',get_chr( $l, 'first semester', $s, $ind_std['std_id'] ),'</td>';
					}
					
					$first_semester= fetch_student_RESULT($ind_std['matric_no'], $arr1, 'first semester', $s);
					$second_semester= fetch_student_RESULT($ind_std['matric_no'], $arr2, 'second semester', $s);
					$ll = array_merge($first_semester, array(''), $second_semester);
	
					for($i=0; $i<$k; $i++) {
						if( $i == $aa ) {
							if( $set['show_rr'] ) {
								//set
								echo '<td class="tB s9">',get_chr( $l, 'second semester', $s, $ind_std['std_id'] ),'</td>';
							}
						} else
							echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
					}	
					
				} else {
					
					echo '<td></td>',
						 //'<td></td>',
						 //'<td></td>',
						 '<td></td>';
				
				}
		$cgpa = get_cgpa($s, $ind_std['std_id']);
		echo '<td>',get_gpa($s, $ind_std['std_id']),'</td>',
			 '<td class="B">',$cgpa,'</td>';
	    echo !empty($cod[2]) ? '<td></td>' : '';
		echo '<td class="s9"><div class="dw">',wordwrap( get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos), 8, "\n", true ),'</div></td>',
			 '</tr>';		 

}

}

	
echo '</tbody></table>';



#signature placeholder
echo '<div class="sph block" style="margin-top:40px; display:block: overflow:hidden">',$set['bottom'],'</div>';
echo '<div class="sph center" style="text-align:center; font-size:15px; font-weight:700;">Date of Senate Approval :  .......................................................................</div>';
mysqli_close($connect);
?>
</body>
</html>
