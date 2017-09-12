<?php
	
	ob_start("ob_gzhandler");
	include_once './config.php';
	include_once './include_report.php';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Undergraduate Delayed Result List</title>
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
	
	$special = isset($_GET['special']) ? $_GET['special'] : false;
	$status = isset( $_GET['state'] ) ? $_GET['state'] : $l;
	
	
	$level_reps = get_levelreps();
	$faculty_title = G_faculty($f);
	
	
	$fetch_level = $l;
	$fetch_sess = $special ? $s - ($l-$status) : $s;



//report design setting

	if( $l > 1 )
	{

		
		$set['rpt'] = array(0=>'<th>REPEAT COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['carry'] = array(0=>'<th>CARRY OVER COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB">CH</th>');
		$set['cpga'] = array(0=>'<th>CGPA</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['chr'] = array(1=>'<th class="tB s9 bbt">Repeat/Carryover Result</th>', 2=>'<th class="tB"></th>');
		$set['plus'] = 1;
		$set['wrong_fix'] = '';
		
	} else {
		
		$set['rpt'] = array(0=>'', 1=>'', 2=>'');
		$set['carry'] = array(0=>'', 1=>'', 2=>'');
		$set['cpga'] = array(0=>'', 1=>'', 2=>'');
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
		$set['dr'] = 'UNDERGRADUATE DELAYED RESULTS';
		
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
	
	
//report design setting

	
	$list_courses_first = fetch_courses( $d, $p, $fetch_level, 'first semester', $fetch_sess, $f, $fos );
	$list_courses_sec = fetch_courses( $d, $p, $fetch_level, 'second semester', $fetch_sess, $f, $fos );

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
	
//############################### To Display Header Information

echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="TABLE">';
	echo '<tbody>';
	echo '<tr>';
		echo '<td><b>SN</b></td>',
			 '<td><b>NAME</b></td>',
			 '<td><b>REG NO</b></td>',
			 '<td><b>FIRST SEMESTER CARRY-OVER COURSES</b></td>',
			 '<td><b>SECOND SEMESTER CARRY-OVER COURSES</b></td>',
			 '<td><b>COMMENT</b></td>',
		'</tr>';
		
//############################ To Display the result of students that will proceed to do their IT
							
							$queryresit = 'SELECT DISTINCT sr.std_id, sp.matric_no, sr.level_id, sp.surname, sp.firstname, sp.othernames
FROM students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.department_id = sp.stddepartment_id && sr.yearsession="'.$s.'" && sr.programme_id='.$p.' && sr.faculty_id='.$f.' && sr.department_id='.$d.' && sr.level_id='.$l.' && sp.stdcourse = '.$fos.' ORDER BY sp.matric_no, sp.surname ASC';
							
			//echo $queryresit;
			//exit;
			
			$resultresit = mysqli_query( $GLOBALS['connect'],  $queryresit ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
	
			$num2ac   = mysqli_num_rows ( $resultresit );
						
			
			
	while($carryrow = mysqli_fetch_array( $resultresit )){

$sqlsum = "SELECT stdresult_id,matric_no FROM students_results
WHERE students_results.std_grade IN ('F','N') 
AND students_results.std_mark_custom1 IN ('First Semester','Second Semester') 
AND students_results.matric_no = '".$carryrow['matric_no']."'
AND students_results.std_mark_custom2 =". $_GET['s_session']."
GROUP BY students_results.matric_no";
//echo $sqlsum."<br /><br />";
 
$resultsum = mysqli_query( $GLOBALS['connect'],  $sqlsum ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );


 $numsum   = mysqli_num_rows ( $resultsum );
 
 // while($rowcount = mysqli_fetch_assoc($resultsum)){
	 
	

 
 if($numsum == ""){
	 /*
	 $fullname = $carryrow['surname']. ", ". $carryrow['lastname']. " ". $carryrow['othernames'];
		echo '<tr>';
		echo '<td>',$c,'</td>',
			 '<td>',$fullname,'</td>',
			 '<td>',$carryrow['matric_no'],'</td>',
		     '<td class="tB s9"> &nbsp;';
			
$sqlsem1 = "SELECT course_reg.stdcourse_custom2,students_results.cu FROM students_results,course_reg 
WHERE students_results.std_grade IN ('F','N') 
AND students_results.std_id = course_reg.std_id 
AND students_results.stdcourse_id = course_reg.thecourse_id
AND students_results.std_mark_custom1 = 'First Semester' 
AND students_results.matric_no = '".$carryrow['matric_no']."'
GROUP BY course_reg.stdcourse_custom2";
			

$resultsem1 = mysqli_query( $GLOBALS['connect'],  $sqlsem1 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

while($rowsem1 = mysqli_fetch_array( $resultsem1 )){
	echo " ".$rowsem1['stdcourse_custom2']." - ".$rowsem1['cu'].", " ;
}  

			echo '</td>';
			echo '<td class="tB s9"> &nbsp;';
			
	$sqlsem2 = "SELECT course_reg.stdcourse_custom2,students_results.cu FROM students_results,course_reg 
WHERE students_results.std_grade IN ('F','N') 
AND students_results.std_id = course_reg.std_id 
AND students_results.stdcourse_id = course_reg.thecourse_id
AND students_results.std_mark_custom1 = 'Second Semester' 
AND students_results.matric_no = '".$carryrow['matric_no']."'
GROUP BY course_reg.stdcourse_custom2";
			

	$resultsem2 = mysqli_query( $GLOBALS['connect'],  $sqlsem2 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

	while($rowsem2 = mysqli_fetch_array( $resultsem2 )){
	echo " ".$rowsem2['stdcourse_custom2']." - ".$rowsem2['cu'].", " ;
}  

			echo '</td>';
			echo '<td>Proceed for IT</td>',
			'</tr>';
	 */
	 
 }else{
	 
	 while($rowcount = mysqli_fetch_assoc($resultsum)){
		 
		  $querycusum1 = "SELECT stdresult_id,matric_no FROM students_results
WHERE students_results.std_grade IN ('F','N') 
AND students_results.std_mark_custom1 IN ('First Semester','Second Semester') 
AND students_results.matric_no = '".$rowcount['matric_no']."'
AND students_results.std_mark_custom2 =". $_GET['s_session']."
GROUP BY students_results.stdcourse_id";
	// echo $querycusum1."<br /><br />";
$resultcusum1 = mysqli_query( $GLOBALS['connect'],  $querycusum1 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

while($ccurow = mysqli_fetch_assoc($resultcusum1)){
	
	$custd=$custd.",".$ccurow['stdresult_id'];
}
	
		//echo $custd."<br /><br />";
		
		$querydsum = "SELECT sum(cu) as sumcu FROM students_results where matric_no = '".$rowcount['matric_no']."' AND std_mark_custom2 = ".$_GET['s_session']." AND stdresult_id IN (".substr($custd,1,strlen($custd)).")";
//echo $querydsum."<br /><br />";

$resultdsum = mysqli_query( $GLOBALS['connect'],  $querydsum ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

$ddsum = mysqli_fetch_assoc($resultdsum);
//echo $ddsum['sumcu']."<br /><br />";
 if( $ddsum['sumcu'] > 15 ){
 
			$fullname = $carryrow['surname']. ", ". $carryrow['lastname']. " ". $carryrow['othernames'];
			echo '<tr>';
			echo '<td>',$cc,'</td>',
			'<td>',$fullname,'</td>',
			'<td>',$carryrow['matric_no'],'</td>',
		    '<td class="tB s9"> &nbsp;';
		
$sqlsem1 = "SELECT course_reg.stdcourse_custom2,students_results.cu FROM students_results,course_reg 
WHERE students_results.std_grade IN ('F','N') 
AND students_results.std_id = course_reg.std_id 
AND students_results.stdcourse_id = course_reg.thecourse_id
AND students_results.std_mark_custom1 = 'First Semester' 
AND course_reg.cyearsession = students_results.std_mark_custom2
AND course_reg.cyearsession = ". $_GET['s_session']."
AND students_results.matric_no = '".$carryrow['matric_no']."'
GROUP BY course_reg.stdcourse_custom2";
			
$resultsem1 = mysqli_query( $GLOBALS['connect'],  $sqlsem1 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

	while($rowsem1 = mysqli_fetch_array( $resultsem1 )){
	echo " ".$rowsem1['stdcourse_custom2']." - ".$rowsem1['cu'].", " ;
	}  

			echo '</td>';
			echo '<td class="tB s9"> &nbsp;';
			
$sqlsem2 = "SELECT course_reg.stdcourse_custom2,students_results.cu FROM students_results,course_reg 
WHERE students_results.std_grade IN ('F','N') 
AND students_results.std_id = course_reg.std_id 
AND students_results.stdcourse_id = course_reg.thecourse_id
AND students_results.std_mark_custom1 = 'Second Semester' 
AND course_reg.cyearsession = students_results.std_mark_custom2
AND course_reg.cyearsession = ". $_GET['s_session']."
AND students_results.matric_no = '".$carryrow['matric_no']."'
GROUP BY course_reg.stdcourse_custom2";
			
	$resultsem2 = mysqli_query( $GLOBALS['connect'],  $sqlsem2 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

	while($rowsem2 = mysqli_fetch_array( $resultsem2 )){
	echo " ".$rowsem2['stdcourse_custom2']." - ".$rowsem2['cu'].", " ;
}  

			echo '</td>';
			echo '<td>Retake Current Level</td>',
		'</tr>';
 		}

//echo $ddsum['sumcu']."<br /><br />";
unset($custd);
		 } //Closing for the while loop
		 
		// }
		 
	 }
	 
 }
//############################## End of loop	
	
echo '</tbody></table>';
$me = "me";

	echo '<div class="sph block bl" style="margin-top:30px; ">
	<div style="border-bottom:1px solid #000; padding:4px 10px;" class="block B">STATISTICS</div>
	<div class="st block">
	<div><p class="a">No Of Students Registered</p> <p class="b">',$num2ac,'</p></div>
	<div><p class="a">No of Results Published</p> <p class="b">',$numsums,'</p></div>
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