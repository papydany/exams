<?php
session_start();
	ob_start("ob_gzhandler");
	include_once './config.php';
	include_once './include_report.php';
	
	
	set_time_limit(0);


$str = "Select * From students_profile Where matric_no = '".$_SESSION['matno']."' LIMIT 1";
$query = mysqli_query($GLOBALS['connect'], $str);
$profile = mysqli_num_rows($query); // set profile variable if found

if (0 != $profile) {
		$row_p = mysqli_fetch_assoc($query);
		$stdid = $row_p['std_id'];
		$surname = $row_p['surname'];
		$firstname = $row_p['firstname'];
		$othernames = $row_p['othernames'];
		$gender = $row_p['gender'];
		$marital_status = $row_p['marital_status'];
		$place_of_birth = $row_p['place_of_birth'];
		$birthdate = $row_p['birthdate'];
		$parents_name = $row_p['parents_name'];
		$stddegree_id = $row_p['stddegree_id'];
		//$_SESSION['stddegree_id'] = $row_p['stddegree_id'];
		$yearentry = $row_p['std_custome18'];
		$local_gov = $row_p['local_gov'];
		$state_of_origin = $row_p['state_of_origin'];
		$contact_address = $row_p['contact_address'];
		$school_cert = $row_p['school_cert'];
		$school_cert_yr = $row_p['school_cert_yr'];
		
		$f = $row_p['stdfaculty_id'];
		$d = $row_p['stddepartment_id'];
		$p = $row_p['stdprogramme_id'];
		$fos = $row_p['stdcourse'];
		$matno =  $_SESSION['matno'];
		
		
		
} else {
	echo "No Student Found. Retry";
	exit('Bye...!');
} //-----------------------------------------------
	
	//$c_duration = get_course_duration($row_p['stdcourse']);
	$c_duration = get_course_duration( $fos );
	$level_reps = get_levelreps();
	$faculty_title = G_faculty($f);
	
	
	function get_degree( $stddegree_id ) {
	$str = "Select degree_name From degree Where degree_id=$stddegree_id";
	$q = mysqli_query( $GLOBALS['connect'], $str );
	if ( 0 != mysqli_num_rows($q) ) {
		$r = mysqli_fetch_assoc($q);
		$deg = $r['degree_name'];
	}
	
	return $deg;
}
	
	
	if ( 0 != $profile ) {
	$reg_sems = array(); //multi dimensional arrays for semesters registered
	$str_sem = "Select DISTINCT std_id, ysession, rslevelid, season From registered_semester Where std_id = '$stdid' ORDER BY rslevelid";// Group By rslevelid"; // NOTE: the grouping semester
	$q_sem = mysqli_query($GLOBALS['connect'], $str_sem);
	$semester_flag = mysqli_num_rows($q_sem);
	if ( 0 != $semester_flag ) {
		while ($r_sem = mysqli_fetch_assoc($q_sem)) {
			$reg_sems[] = $r_sem; //echo $r_sem['season'];
			//echo $reg_sems[$r_sem['rs_id']][rslevelid];
		}
	}// echo $str_sem;
	
$i_count = count($reg_sems);// echo '<br>count semester: ',$i_count,'<br>'; // count the number of sessions used for the student

}



function get_grade($session, $l, $c_id, $semester, $std_id)
{
	$sql = "Select std_grade From students_results 
			Where std_id='$std_id' 
			AND std_mark_custom2='$session' 
			AND level_id='$l' 
			AND std_mark_custom1='$semester'
			AND stdcourse_id='$c_id'";
			
	$q = mysqli_query($GLOBALS['connect'], $sql);
	if ( 0 != mysqli_num_rows( $q ) ) {
		while ( $r = mysqli_fetch_assoc( $q ) ) {
			return $r['std_grade'];
		}
	}
}



function get_point( $mygrade, $course_unit )
{
	if ($mygrade == 'A'){
		$p = 5 * intval($course_unit);
	}else if ($mygrade == 'B'){
		$p = 4 * intval($course_unit);
	}else if ($mygrade == 'C'){
		$p = 3 * intval($course_unit);
	}else if ($mygrade == 'D'){
		$p = 2 * intval($course_unit);
	}else if ($mygrade == 'E'){
		$p = 1 * intval($course_unit);
	}else if ($mygrade == 'F'){
		$p = 0 * intval($course_unit);
	}else if ($mygrade == 'N'){
		$p = 0 * intval($course_unit);
	}else if ($mygrade == ''){
		$p = '';//5 * intval($course_unit)
	}
	
	return $p;
	
}



function func_degree_class($profile, $semester_flag, $reg_sems) {
	$tot_cu =0; $tot_pt =0; //$ii =0;
	
	if (( 0 != $profile ) && ( 0 != $semester_flag )) {
		//echo "here";
		foreach ($reg_sems as $index=>$row) {
			//$i_sem += 1;
			//  444444444444444444444  FIRST SEMESTER CHECK
			//echo "mydear";
			$sql_fc = "Select ac.*, cr.* From all_courses AS ac, course_reg AS cr Where 
								ac.thecourse_id=cr.thecourse_id 
								AND ac.level_id=cr.clevel_id 
								AND ac.course_custom5=cr.cyearsession 
								AND ac.level_id='".$row['rslevelid']."' 
								AND ac.course_custom5='".$row['ysession']."'
								AND cr.course_season='".$row['season']."'
								AND ac.course_semester IN ('First Semester', 'Second Semester')  
								AND cr.std_id='".$row['std_id']."'";
						
						$q_fc = mysqli_query($GLOBALS['connect'], $sql_fc);
						$i = mysqli_num_rows($q_fc);
						
						//echo $sql_fc;
						if ( 0 != $i ) {
									
							//unset ($tot_cu);
							while ( $code = mysqli_fetch_assoc( $q_fc ) )
							{
					$mygrade = get_grade($row['ysession'], $row['rslevelid'], $code['thecourse_id'], $code['course_semester'], $row['std_id']);
								//if (($mygrade != '') && ($mygrade != ' ')){ $ii += 1 ;}
								$mypoint = get_point( $mygrade, $code['course_unit'] );
								//echo '<tr><td align="center" class="btd">',
									//substr($code['course_code'],0,3),'</td><td align="center" class="btd">',
									//substr($code['course_code'],-4),'</td><td class="btd">&nbsp;',
									//ucwords($code['course_title']),'</td><td align="center" class="btd">',
									//$code['course_unit'],'</td><td align="center" class="btd">&nbsp;',
									//$mygrade,'</td><td align="center" class="btd">
									//&nbsp;</td><td align="center" class="btd">&nbsp;',
									//$mypoint,'</td></tr>';
									//echo $code['course_unit'], ', ';
									$tot_cu += $code['course_unit'];
									$tot_pt += $mypoint;
							} unset($mygrade); // while loop finishes
						} 
		}
	}
	
	//echo $tot_cu, ', ';
	$mydegree = round( ($tot_pt / $tot_cu) , 2 );
	
	if (( $mydegree >= 4.5 ) && ( $mydegree <= 5.0 )) {
		$deg = 'First Class';
	} elseif (( $mydegree >= 3.50 ) && ( $mydegree <= 4.49 )) {
		$deg = 'Second Class Upper';
	} elseif (( $mydegree >= 2.40 ) && ( $mydegree <= 3.49 )) {
		$deg = 'Second Class Lower';
	} elseif (( $mydegree >= 1.5 ) && ( $mydegree <= 2.39 )) {
		$deg = 'Third Class';
	} elseif (( $mydegree >= 1.00 ) && ( $mydegree <= 1.49 )) {
		$deg = 'Pass';
	} elseif (( $mydegree >= 0.00 ) && ( $mydegree <= 0.99 )) {
		$deg = 'Fail';
	}
	
	return $deg; //.'='.$ii;
}




if ( 0 != $profile ) {
	$sc_result = array();
	$sc_q = @mysqli_query($GLOBALS['connect'], "Select * From school_certificates1 Where std_id='$stdid'");
	$sc_num = @mysqli_num_rows( $sc_q );
	while ( $sc_row = @mysqli_fetch_assoc( $sc_q ) ) {
		$sc_result[] = $sc_row;
	}
	
}
	
	
	require('fpdf.php');

$pdf=new FPDF();

//for($a1 = 1; $a1<=2; $a1++){
	if ( 0 != $profile ) {
		
		$str_sem = "Select DISTINCT std_id, ysession, rslevelid, season From registered_semester Where std_id = $stdid ORDER BY rslevelid";// Group By rslevelid"; // NOTE: the grouping semester
		//echo $str_sem;
	$q_sem = mysqli_query($GLOBALS['connect'], $str_sem);
	$semester_flag = mysqli_num_rows($q_sem);
	while ($r_sem = mysqli_fetch_assoc($q_sem)) {
		
		$yrcount = $r_sem['ysession'];
		$year = (int)$r_sem['ysession'];
		$year2 = $year + 1;
$yyear1 = 'FIRST SEMESTER     '.$r_sem["rslevelid"].' / '.get_course_duration($row_p['stdcourse']).'        YEAR    '.$year.' / '.$year2;
$yyear2 = 'SECOND SEMESTER     '.$r_sem["rslevelid"].' / '.get_course_duration($row_p['stdcourse']).'        YEAR    '.$year.' / '.$year2;
 //echo $yyear1;
//$pdf->SetLeftMargin(0);
//$pdf->SetRightMargin(0);
//$pdf->SetTopMargin(10);
//$pdf->SetBottomMargin(10);
//$pdf->Rect(7, 7, 198, 275 ,'B');
$pdf->AddPage('P','A4'); //Create new PDF page
$pdf->Rect(7, 7, 198, 275 ,'B');
$pdf->SetFont('Arial','B',6);
$pdf->Image('images/logo.jpg',70,60,60,60);
  
  $pdf->Cell(70,5,"",0);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(50,5,"UNIVERSITY OF CALABAR",0);
  $pdf->MultiCell(70,5,"",0); //Necessary for generating a new line.
  $pdf->Cell(75,5,"",0);
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(60,5,"CALABAR, NIGERIA",0);
  $pdf->MultiCell(55,5,"",0);
  $pdf->Cell(67,5,"",0);
  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(68,5,"STUDENT'S ACADEMIC TRANSCRIPT",0);
  
  $pdf->SetFont('Arial','',5);
  $pdf->Cell(15,5,"Date ",0);
  $pdf->MultiCell(40,5,date("d-m-Y"),0);
  $pdf->SetFont('Arial','',5);
  $pdf->Cell(8,3,"Name: ",0);
  $sname = strtoupper($surname);
  $pdf->Cell(20,3,$sname,0);
  $fname = strtoupper($firstname);
  $pdf->Cell(20,3,$fname,0);
  $oname = strtoupper($othernames);
  $pdf->Cell(25,3,$oname,0);
  $pdf->Cell(42,3,"",0);
  $pdf->SetFont('Arial','',5);
  $pdf->Cell(35,3,"STUDENT'S REGISTRATION NO ",0);
  $pdf->MultiCell(25,3,$_SESSION['matno'],0);
  
  $pdf->Cell(12,0.5," ",0);
  $pdf->Cell(76,0.5,"------------------------------------------------------------------------------------------------",0);
  $pdf->Cell(62,0.5," ",0);
  $pdf->MultiCell(45,0.5,"-------------------------------------------------------------------------",0);
  
  $pdf->Cell(8,3," ",0);
  $pdf->SetFont('Arial','',4);
  $pdf->Cell(20,3,"(Surname)",0);
  $pdf->Cell(20,3,"(First Name)",0);
  $pdf->Cell(22,3,"(Middle Name)",0);
  $pdf->SetFont('Arial','',5);
  $pdf->Cell(15,3,"Male/Female ",0);
  $sgender = strtoupper($gender);
  $pdf->Cell(15,3,$sgender,0);
  //$pdf->SetFont('Arial','',4);
  $pdf->Cell(15,3,"Faculty ",0);
  //$fac = substr($faculty_title,0,35);
  $fac = strtoupper($faculty_title);
  $pdf->MultiCell(80,3,$fac,0);
  
  $pdf->Cell(85,0.5," ",0);
  $pdf->Cell(15,0.5,"----------------------",0);
  $pdf->Cell(15,0.5," ",0);
  $pdf->MultiCell(80,0.5,"------------------------------------------------------------------------------------------------------------------------------------",0);
  
  $pdf->Ln();
  $pdf->Ln();
  $pdf->Ln();
  $pdf->Cell(20,3,"Permanent Address ",0);
  $pdf->Cell(50,3,$contact_address,0);
  $pdf->Cell(15,3,"Place of Birth ",0);
  $pdf->Cell(15,3,$place_of_birth,0);
  $pdf->Cell(15,3,"Department ",0);
  $dept = strtoupper(G_department($d));
  $pdf->MultiCell(80,3,$dept,0);
  
  $pdf->Cell(20,0.5," ",0);
  $pdf->Cell(50,0.5,"-----------------------------------------------------------------------------------",0);
  $pdf->Cell(15,0.5," ",0);
  $pdf->Cell(15,0.5,"----------------------",0);
  $pdf->Cell(15,0.5," ",0);
  $pdf->MultiCell(80,0.5,"------------------------------------------------------------------------------------------------------------------------------------",0);
  
  $pdf->Ln();
  $pdf->Ln();
  $pdf->Ln();
  $pdf->Cell(16,3,"State of Origin ",0);
  $storigin = strtoupper($state_of_origin);
  $pdf->Cell(15,3,$storigin,0);
  $pdf->Cell(14,3,"Division (LGA) ",0);
  $lga = strtoupper($local_gov);
  $pdf->Cell(25,3,$lga,0);
  $pdf->Cell(15,3,"Date of Birth ",0);
  $bdate = strtoupper($birthdate);
  $pdf->Cell(15,3,$bdate,0);
  $pdf->Cell(15,3,"Year of Entry ",0);
  $pdf->MultiCell(80,3,$yearentry,0);
  
  $pdf->Cell(14,0.5," ",0);
  $pdf->Cell(15,0.5,"------------------------",0);
  $pdf->Cell(14,0.5," ",0);
  $pdf->Cell(25,0.5,"---------------------------------------",0);
  $pdf->Cell(15,0.5," ",0);
  $pdf->Cell(17,0.5,"-------------------------",0);
  $pdf->Cell(15,0.5," ",0);
  $pdf->MultiCell(80,0.5,"------------------------------------------------------------------------------------------------------------------------------------",0);
  
  $pdf->Ln();
  $pdf->Cell(25,3,"Parent's or Guardian's Name ",0);
  $pname = strtoupper($parents_name);
  $pdf->Cell(45,3,$pname,0);
  $pdf->Cell(15,3,"Marital Status ",0);
  $mstatus = strtoupper($marital_status);
  $pdf->Cell(15,3,$mstatus,0);
  $pdf->Cell(15,3,"Last Institution ",0);
  $pdf->MultiCell(80,3," ",0);
  
  $pdf->Cell(25,0.5," ",0);
  $pdf->Cell(45,0.5,"--------------------------------------------------------------------------",0);
  $pdf->Cell(13,0.5," ",0);
  $pdf->Cell(17,0.5,"--------------------------",0);
  $pdf->Cell(15,0.5," ",0);
  $pdf->MultiCell(80,0.5,"------------------------------------------------------------------------------------------------------------------------------------",0);
  
  //$pdf->Rect(0, 0, 70, 10 , 'B');
  $pdf->Ln();
  $pdf->Ln();
  $pdf->Ln();
  $pdf->Ln();
  $pdf->Ln();
  $pdf->Ln();
  $pdf->Ln();
  $pdf->Ln();
  $pdf->SetFont('Arial','B',5);
  $pdf->Cell(40,3," ",1);
  // Display of level and year
 // $yyear1 = 'FIRST SEMESTER '.$r_sem["rslevelid"].'/'.get_course_duration($row_p['stdcourse']).' YEAR '.$year.'/'.$year;
  $pdf->Cell(74,3,$yyear1,1,0,'C');
  //$yyear2 = "SECOND SEMESTER    YEAR ". "2005";
  $pdf->MultiCell(75,3,$yyear2,1,'C');
  
  //School Certificate information column
  $pdf->SetFont('Arial','',4);
  $pdf->Cell(39.5,3.5,"SCH./GEN. CERT. of EDUC.",1,0,'L');
  $pdf->Cell(0.5,3.5," ",1);
  
  //Displaying First Semester courses and grades
  $pdf->Cell(8,3.5,"DEPT",1,0,'C');
  $pdf->Cell(8,3.5,"Course No",1,0,'C');
  $pdf->Cell(33.5,3.5,"Course Title",1,0,'C');
  $pdf->Cell(8,3.5,"Sem Hours",1,0,'C');
  $pdf->Cell(8,3.5,"Grade",1,0,'C');
  $pdf->Cell(8,3.5,"Qty Pts",1,0,'C');
  $pdf->Cell(0.5,3.5," ",1);
  
  //Displaying Second Semester courses and grades
  $pdf->Cell(8,3.5,"DEPT",1,0,'C');
  $pdf->Cell(8,3.5,"Course No",1,0,'C');
  $pdf->Cell(34,3.5,"Course Title",1,0,'C');
  $pdf->Cell(8,3.5,"Sem Hours",1,0,'C');
  $pdf->Cell(8,3.5,"Grade",1,0,'C');
  $pdf->Cell(8,3.5,"Qty Pts",1,0,'C');
  $pdf->MultiCell(1,1.75," ",1);
  
  $pdf->Cell(39.5,2.5,$school_cert. "  ".$school_cert_yr,1,0,'L');
 // $pdf->Cell(5,2.5,$scrows['grade'],1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
  //Loop GCE subjects  $school_cert. "  ".$school_cert_yr
  $sc_q = mysqli_query($GLOBALS['connect'], "Select * From olevels Where std_id='$stdid'");
	$sc_num = @mysqli_num_rows( $sc_q );
   //for($i=1;$i<=10;$i++){
  while($scrows = mysqli_fetch_assoc($sc_q)){
  $pdf->Cell(14.5,2.5,$scrows['subject'],1,0,'C');
  $pdf->Cell(5,2.5,$scrows['grade'],1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
   }
  //Loop First Semester Courses and grades
  
  $pdf->setY(56.5);
 //for($i=1;$i<=25;$i++){
	 
	 $sql1 = "Select ac.*, cr.* From all_courses AS ac, course_reg AS cr Where 
							ac.thecourse_id=cr.thecourse_id 
							AND ac.level_id=cr.clevel_id 
							AND ac.course_custom5=cr.cyearsession 
							AND ac.level_id=".$r_sem['rslevelid']." 
							AND ac.course_custom5=".$r_sem['ysession']."
							AND cr.course_season='".$r_sem['season']."'
							AND ac.course_semester='First Semester' 
							AND cr.std_id= ".$r_sem['std_id'];
							
					//echo $sql1."<br /><br />";	
					//exit;
					//echo $index, '--',$row['ysession'],'<br>';
					$result1 = mysqli_query($GLOBALS['connect'], $sql1);
					$a1 = mysqli_num_rows($result1);
					unset ($tot_cu_1);
					//echo $a1;
					while($loop1 = mysqli_fetch_array($result1)){
						//print $loop1['course_code'];
		$mygrade = get_grade($r_sem['ysession'], $r_sem['rslevelid'], $loop1['thecourse_id'], 'First Semester', $r_sem['std_id']);
       $mypoint = get_point( $mygrade, $loop1['course_unit'] );
	    $tot_cu_1 += $loop1['course_unit'];
        $tot_pt_1 += $mypoint;
  //$pdf->setY(57.5);
  $pdf->setX(50);
  $pdf->Cell(8,2.5,substr($loop1['course_code'],0,3),1,0,'C');
  $pdf->Cell(8,2.5,substr($loop1['course_code'],-4),1,0,'C');
  $pdf->Cell(33.5,2.5,ucwords($loop1['course_title']),1,0,'L');
  $pdf->Cell(8,2.5,$loop1['course_unit'],1,0,'C');
  $pdf->Cell(8,2.5,$mygrade,1,0,'C');
  $pdf->Cell(8,2.5,$mypoint,1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  }
  
  //Loop Second Semester Courses and grades
  $pdf->setY(56.5);
  
  //for($i=1;$i<=25;$i++){
	  
	  $sql2 = "Select ac.*, cr.* From all_courses AS ac, course_reg AS cr Where 
							ac.thecourse_id=cr.thecourse_id 
							AND ac.level_id=cr.clevel_id 
							AND ac.course_custom5=cr.cyearsession 
							AND ac.level_id=".$r_sem['rslevelid']." 
							AND ac.course_custom5=".$r_sem['ysession']."
							AND cr.course_season='".$r_sem['season']."'
							AND ac.course_semester='Second Semester' 
							AND cr.std_id= ".$r_sem['std_id'];
							
					//echo $sql1."<br /><br />";	
					//exit;
					//echo $index, '--',$row['ysession'],'<br>';
					$result2 = mysqli_query($GLOBALS['connect'], $sql2);
					$a2 = mysqli_num_rows($result2);
					//echo $a2;
					unset ($tot_cu_2);
					while($loop2 = mysqli_fetch_array($result2)){
						//print $loop1['course_code'];
		$mygrade = get_grade($r_sem['ysession'], $r_sem['rslevelid'], $loop2['thecourse_id'], 'Second Semester', $r_sem['std_id']);
       $mypoint = get_point( $mygrade, $loop2['course_unit'] );
	   $tot_cu_2 += $loop2['course_unit'];
       $tot_pt_2 += $mypoint;
	  
  $pdf->setX(124);
  $pdf->Cell(8,2.5,substr($loop2['course_code'],0,3),1,0,'C');
  $pdf->Cell(8,2.5,substr($loop2['course_code'],-4),1,0,'C');
  $pdf->Cell(34,2.5,ucwords($loop2['course_title']),1,0,'L');
  $pdf->Cell(8,2.5,$loop2['course_unit'],1,0,'C');
  $pdf->Cell(8,2.5,$mygrade,1,0,'C');
  $pdf->Cell(8,2.5,$mypoint,1,0,'C');
  $pdf->MultiCell(1,1.25," ",1);
  }
  
  
  //Untouched lines under School Certificate / GCE
  if($sc_num != 0){
  $olevel1 = ($sc_num * 2.5) + 56.5 +2.5;
  }else{
	  $olevel1 = 56.5 +2.5;
  }
  $pdf->setY($olevel1);
  $pdf->Cell(39.5,2.5," ",1,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  $pdf->Cell(39.5,2.5," ",1,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  $pdf->Cell(39.5,2.5," ",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
  //Display of Curriculum and Class of Degree
  $pdf->SetFont('Arial','',3.5);
  $dept2 = strtoupper(G_department($d));
 // $dept2 = strtoupper(G_department($d));
  $pdf->Cell(39.5,2.5,$dept2,0,0,'C');
 // $pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  $pdf->Cell(39.5,2.5,"---------------------------",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->SetFont('Arial','',4);
  $pdf->MultiCell(0.5,1.25,"",1);
  $pdf->Cell(39.5,2.5,"Curriculum",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
  //Display of Curriculum and Class of Degree				
  $pdf->SetFont('Arial','B',4);
  $stddeg = strtoupper(get_degree($stddegree_id));
  $pdf->Cell(39.5,2.5,$stddeg,0,0,'C');
 // $pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  $pdf->Cell(39.5,2.5,"---------------------------",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->SetFont('Arial','',4);
  $pdf->MultiCell(0.5,1.25,"",1);
  $pdf->Cell(39.5,2.5,"Degree",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
   //Display of Curriculum and Class of Degree
   $gedclass = func_degree_class($profile, $semester_flag, $reg_sems);
   $getclass = strtoupper($gedclass);
  $pdf->SetFont('Arial','B',3.5);
  $pdf->Cell(39.5,2.5,$getclass,0,0,'C');
 // $pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  $pdf->Cell(39.5,2.5,"---------------------------",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->SetFont('Arial','',4);
  $pdf->MultiCell(0.5,1.25,"",1);
  $pdf->Cell(39.5,2.5,"Class of Degree",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
   //Display of Curriculum and Class of Degree
  $pdf->SetFont('Arial','B',5);
  $pdf->Cell(39.5,2.5,"28/10/95",0,0,'C');
 // $pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  $pdf->Cell(39.5,2.5,"---------------------------",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->SetFont('Arial','',4);
  $pdf->MultiCell(0.5,1.25,"",1);
  $pdf->Cell(39.5,2.5,"Date of Graduation",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
   //Display of Curriculum and Class of Degree
  $pdf->SetFont('Arial','B',5);
  $pdf->Cell(39.5,2.5,"",0,0,'C');
 // $pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  $pdf->Cell(39.5,2.5,"---------------------------",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->SetFont('Arial','',4);
  $pdf->MultiCell(0.5,1.25,"",1);
  $pdf->Cell(39.5,2.5,"Major",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
   //Display of Curriculum and Class of Degree
  $pdf->SetFont('Arial','B',5);
  $pdf->Cell(39.5,2.5,"",0,0,'C');
 // $pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  $pdf->Cell(39.5,2.5,"---------------------------",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->SetFont('Arial','',4);
  $pdf->MultiCell(0.5,1.25,"",1);
  $pdf->Cell(39.5,2.5,"Minor",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
   //Display of Curriculum and Class of Degree
  $pdf->SetFont('Arial','B',5);
  $pdf->Cell(39.5,2.5,"",0,0,'C');
 // $pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  $pdf->Cell(39.5,2.5,"---------------------------",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->SetFont('Arial','',4);
  $pdf->MultiCell(0.5,1.25,"",1);
  $pdf->Cell(39.5,2.5,"Withdrawal Date",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
  $pdf->Cell(39.5,2.5," ",0,0,'C');
  //$pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
  if($a1>$a2){
	  $rowdiff = ($a1) * 2.5;
	  $a1row = ($a1-$a2);
	  $diff = $rowdiff + 56.5;
	  $complete = ($a2 * 2.5) + 56.5;
	  //$diff = ($a1 * 2.5) + 57.5;
  }else if($a2>$a1){
	  $rowdiff = ($a2) * 2.5;
	  $a1row = ($a2-$a1);
	   $diff = $rowdiff + 56.5;
	   $complete = ($a1 * 2.5) + 56.5;
	  //$diff = ($a2 * 2.5) + 57.5;
  }else if($a1 == $a2){
	  $rowdiff = ($a1 * 2.5);
	  $a1row = 0;
	  $diff = 
	  $complete = ($a1 * 2.5) + 56.5;
  }
  if($a2>$a1){
  //$pdf->setY(122.5);
  $pdf->setY($complete);
  for($a=1;$a<=$a1row;$a++){
  $pdf->setX(50);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(33.5,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  }
  }
  
  //Loop Second Semester Continuing rows
   if($a1>$a2){
  $pdf->setY($complete);
  for($a=1;$a<=$a1row;$a++){
  $pdf->setX(124);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(34,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->MultiCell(1,1.25," ",1);
  }
  }
  
   //Semester 1 Average
  $pdf->setY($diff);
  
  $pdf->setX(50);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(33.5,2.5," ",1,0,'R');
  $pdf->Cell(8,2.5,$tot_cu_1,1,0,'C');
  $tot1 = round(($tot_pt_1 / $tot_cu_1),2);
  $pdf->Cell(8,2.5,$tot1,1,0,'C');
  $pdf->Cell(8,2.5,$tot_pt_1,1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
  //Semester 2 Average
  $pdf->setY($diff);
  
  $pdf->setX(124);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(34,2.5," ",1,0,'R');
  $pdf->Cell(8,2.5,$tot_cu_2,1,0,'C');
  $tot2 = round(($tot_pt_2 / $tot_cu_2),2);
  $pdf->Cell(8,2.5,$tot2,1,0,'C');
  $pdf->Cell(8,2.5,$tot_pt_2,1,0,'C');
  $pdf->MultiCell(1,1.25," ",1);
  
   //Semester 1 Average
   $diff1 = $diff + 2.5;
  $pdf->setY($diff1);
  
  $pdf->setX(50);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(33.5,2.5," ",1,0,'R');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
  //Semester 2 Average
  $pdf->setY($diff1);
  
  $pdf->setX(124);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(34,2.5," ",1,0,'R');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->MultiCell(1,1.25," ",1);
  
   //Semester 1 Average
   $diff2 = $diff + 5;
  $pdf->setY($diff2);
  
  $pdf->setX(50);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(33.5,2.5," ",1,0,'R');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
  //Semester 2 Average
  $pdf->setY($diff2);
  
  $pdf->setX(124);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(34,2.5," ",1,0,'R');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->MultiCell(1,1.25," ",1);
  
  //Cumulative Semesters 
   //Semester 1 Average
   $diff3 = $diff + 7.5;
  $pdf->setY($diff3);
  $pdf->setX(50);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(33.5,2.5," ",1,0,'R');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
  //Displaying cumulative semester Average
  $g_tot_cu += $tot_cu_1 + $tot_cu_2;
  $g_tot_pt += $tot_pt_1 + $tot_pt_2;
  
  $pdf->setY($diff3);
  
  $pdf->setX(124);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(34,2.5,"Total Cumulative Average",1,0,'R');
  $pdf->Cell(8,2.5,$tot_cu_1 + $tot_cu_2,1,0,'C');
  $pdf->Cell(8,2.5,round((( $tot_pt_1 + $tot_pt_2 )/( $tot_cu_1 + $tot_cu_2 )),2),1,0,'C');
  $pdf->Cell(8,2.5,$tot_pt_1 + $tot_pt_2,1,0,'C');
  $pdf->MultiCell(1,1.25," ",1);
  
  //Blank space between both totals
   //Semester 1 Average
   $diff4 = $diff + 10;
  $pdf->setY($diff4);
  
  $pdf->setX(50);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(33.5,2.5," ",1,0,'R');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
  //Semester 2 Average
  $pdf->setY($diff4);
  
  $pdf->setX(124);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(34,2.5," ",1,0,'R');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->MultiCell(1,1.25," ",1);
  
  //Display Totals
  
   //Semester 1 Average
   $diff5 = $diff + 12.5;
  $pdf->setY($diff5);
  $pdf->setX(50);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(33.5,2.5," ",1,0,'R');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  
  //Displaying all total
  $mydegree = round((( $g_tot_pt )/( $g_tot_cu )),2);
  $pdf->setY($diff5);
  $pdf->setX(124);
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(8,2.5,"",1,0,'C');
  $pdf->Cell(34,2.5,"Total",1,0,'R');
  $pdf->Cell(8,2.5,$g_tot_cu,1,0,'C');
  $pdf->Cell(8,2.5,$mydegree,1,0,'C');
  $pdf->Cell(8,2.5,$g_tot_pt,1,0,'C');
  $pdf->MultiCell(1,1.25," ",1);
  
  //Blank space after the Total is displayed
  
   //Semester 1 Average
 // $pdf->setY(101.5);
  
 // $pdf->setX(30);
 // $pdf->Cell(168,5,"great",1,0,'C');
 // $pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(33.5,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
 // $pdf->MultiCell(1.0,2.5," ",1);
  
  //Displaying remarks
  $pdf->SetFont('Arial','',4);
  $diff6 = $diff + 35;
  $pdf->setY($diff6);
  $pdf->setX(50);
  $pdf->Cell(84,5,"REMARK",0,0,'L');
 // $pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(33.5,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1.0,2.5," ",0);
  
  $pdf->setY($diff6);
  $pdf->setX(119);
  $pdf->Cell(79,5,"This is a true copy of the record on File in this office",0,0,'R');
 // $pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(33.5,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1.0,2.5," ",0);
  
  // First Semetry
  $diff7 = $diff + 40;
   $pdf->setY($diff7);
  
  $pdf->setX(50);
  $pdf->Cell(60,5,"-----------------------------------------------------------------------------------------------",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  // Second Semetry
   $pdf->setY($diff7);
  
  $pdf->setX(110);
  $pdf->Cell(60,5,"-----------------------------------------------------------------------------------------------",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  
  // Classification of Degree
  $diff8 = $diff + 45;
   $pdf->setY($diff8);
  
  $pdf->setX(50);
  $pdf->Cell(60,3,"                         CLASSIFICATION OF DEGREE",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  // Classification of Grading System
   $pdf->setY($diff8);
  $pdf->setX(110);
  $pdf->Cell(60,3,"                         GRADING SYSTEM",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  // Classification of Degree
  $diff9 = $diff + 50;
   $pdf->setY($diff9);
  $pdf->setX(50);
  $pdf->Cell(60,3,"          (I)     4.50  -  5.00               -               First Class",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
   $pdf->setY($diff9 + 2.5);
  $pdf->setX(50);
  $pdf->Cell(60,3,"          (I)     3.50  -  4.49               -               Second Class Upper",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  $pdf->setY($diff9 + 5);
  $pdf->setX(50);
  $pdf->Cell(60,3,"          (I)     2.40  -  3.49               -               Second Class Lower",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  $pdf->setY($diff9 + 7.5);
  $pdf->setX(50);
  $pdf->Cell(60,3,"          (I)     1.50  -  2.39               -               Third Class",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  $pdf->setY($diff9 + 10);
  $pdf->setX(50);
  $pdf->Cell(60,3,"          (I)     1.00  -  1.49               -               Pass",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  $pdf->setY($diff9 + 12.5);
  $pdf->setX(50);
  $pdf->Cell(60,3,"          (I)     0.00  -  0.99               -               Fail",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  // Classification of Grading System
   $pdf->setY($diff9);
  $pdf->setX(110);
  $pdf->Cell(60,3,"          A               -               Excellent               5.00",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  $pdf->setY($diff9 + 2.5);
  $pdf->setX(110);
  $pdf->Cell(60,3,"          B               -               Very Good             4.00",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  $pdf->setY($diff9 + 5);
  $pdf->setX(110);
  $pdf->Cell(60,3,"          C               -               Good                     3.00",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  $pdf->setY($diff9 + 7.5);
  $pdf->setX(110);
  $pdf->Cell(60,3,"          D               -               Average                2.00",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  $pdf->setY($diff9 + 10);
  $pdf->setX(110);
  $pdf->Cell(60,3,"          E               -               Pass                      1.00",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  $pdf->setY($diff9 + 12.5);
  $pdf->setX(110);
  $pdf->Cell(60,3,"          F               -               Fail                         0.00",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  $pdf->setY($diff9);
  $pdf->setX(160);
  $pdf->Image('images/registrars_signature.jpg',160,$diff9,25,6);
  //$pdf->Cell(60,3,"          C               -               Good                     3.00",0,0,'L');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  $pdf->setY($diff9 + 5);
  $pdf->setX(160);
  //$pdf->Image('images/registrars_signature.jpg',165,123,25,6);
  $pdf->Cell(25,2,"----------------------------------------------------------",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  // Signature
  $pdf->SetFont('Arial','B',8);
  $pdf->setY($diff9 + 7);
  $pdf->setX(160);
  //$pdf->Image('images/registrars_signature.jpg',165,123,25,6);
  $pdf->Cell(25,4,"Registrar",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(34,5," ",0,0,'R');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  //$pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  
  /*
  //Semester 2 Average
  $pdf->setY(101.5);
  
  $pdf->setX(114);
  $pdf->Cell(10,5,"",0,0,'C');
  $pdf->Cell(10,5,"",0,0,'C');
  $pdf->Cell(34,5," ",0,0,'R');
  $pdf->Cell(10,5,"",0,0,'C');
  $pdf->Cell(10,5,"",0,0,'C');
  $pdf->Cell(10,5,"",0,0,'C');
  $pdf->MultiCell(1,2.5," ",0);
  */
  
  /*
  $pdf->Cell(14.5,2.5,"none",1,0,'C');
  $pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
  $pdf->Cell(14.5,2.5,"none",1,0,'C');
  $pdf->Cell(5,2.5,"Score",1,0,'C');
  $pdf->MultiCell(0.5,1.25," ",1);
 */
   }
}


$pdf->Output(); //Finally shows the output.
//$stud = "Transcript_".$_SESSION['matno'].".pdf";
//$pdf->Output("filename.pdf","F"); //Finally saves the output.
//$pdf -> Output($stud,"D"); //Finally downloads the output.
unset ($g_tot_pt, $g_tot_cu, $i_sem);
$set = array();
unset($set,$course_duration, $cpga, $remark, $ignore);
mysqli_close($connect);
ob_end_flush();

?>