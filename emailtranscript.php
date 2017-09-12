<?php
	session_start();
	//ob_start("ob_gzhandler");
	ini_set("Display_error","1");
	include_once './config.php';
	include_once './include_report.php';
	
	require('fpdf.php');

class PDF extends FPDF
{
//Load data
/*function LoadData($file)
{
	//Read file lines
	$lines=file($file);
	$data=array();
	foreach($lines as $line)
		$data[]=explode(';',chop($line));
	return $data;
} */
} 


$pdf=new PDF();




$matno = $_SESSION['matno'];	


set_time_limit(0);




$str = "Select * From students_profile Where matric_no = '$matno' LIMIT 1";
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
		
} else {
	//echo "No Student Found. Retry";
	//exit('Bye...!');
} //-----------------------------------------------

	$c_duration = get_course_duration( $fos );
	
	
	
	
	$level_reps = get_levelreps();
	$faculty_title = G_faculty($f);
	
	

if ( 0 != $profile ) {
	$reg_sems = array(); //multi dimensional arrays for semesters registered
	$str_sem = "Select DISTINCT std_id, ysession, rslevelid, season From registered_semester Where std_id = '$stdid' ORDER BY rslevelid";
	
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


if ( 0 != $profile ) {
	$sc_result = array();
	$sc_q = @mysqli_query($GLOBALS['connect'], "Select * From school_certificates Where std_id='$stdid'");
	$sc_num = @mysqli_num_rows( $sc_q );
	while ( $sc_row = @mysqli_fetch_assoc( $sc_q ) ) {
		$sc_result[] = $sc_row;
	}
	
}

//77777777777777777777777777777777  END SEMESTER CHECK
// Begin registered semester for student


$i_sem = 0; // set counter for semester registered	
		
if (( 0 != $profile ) && ( 0 != $semester_flag )) {
	
	foreach ($reg_sems as $index=>$row) {
		$i_sem += 1;// echo $row['season'];
		//  444444444444444444444  FIRST SEMESTER CHECK
			
			$sql_fc_1 = "Select cr.* From course_reg AS cr Where 
							cr.clevel_id='".$row['rslevelid']."'
							AND cr.cyearsession='".$row['ysession']."' 
							AND cr.csemester='First Semester' 
							AND cr.std_id='".$row['std_id']."'";
							
			
					$q_fc_1 = mysqli_query($GLOBALS['connect'], $sql_fc_1);
					$i1 = mysqli_num_rows($q_fc_1);
			
			
			
			//  4444444444444444444  SECOND SEMESTER CHECK	
			$sql_fc_2 = "Select cr.* From course_reg AS cr Where 
							cr.clevel_id='".$row['rslevelid']."'
							AND cr.cyearsession='".$row['ysession']."' 
							AND cr.csemester='Second Semester' 
							AND cr.std_id='".$row['std_id']."'";
			
					
					$q_fc_2 = mysqli_query($GLOBALS['connect'], $sql_fc_2);
					$i2 = mysqli_num_rows($q_fc_2);	
		
		//if (($i1 != 0) || ($i2 != 0)) {


$pdf->AddPage('L','A4');
$pdf->SetLeftMargin(10); $pdf->SetRightMargin(10); $pdf->SetTopMargin(10); $pdf->SetFontSize(8); $pdf->SetLineWidth(.1);
//$pdf->Ln(0.1);
//$pdf->SetY(30);	
$pdf->SetDrawColor(0,0,225);
$pdf->Rect(10,7,280,190,'B');
//$pdf->Cell('50','5','This is war','LRTB',0,'L');
//


                    
						//$pdf->SetX(10);
						$pdf->Rect(10,7,280,48,'B'); $pdf->SetFont('','B',13);
						$pdf->MultiCell('','4','UNIVERSITY OF CALABAR','','C'); $pdf->Ln(0.5); $pdf->SetFont('','B',11);
						$pdf->MultiCell('','4','CALABAR, NIGERIA','','C'); $pdf->Ln(0.5); $pdf->SetFont('','B',9);
						$pdf->MultiCell('','4','STUDENTS ACADEMIC TRANSCRIPT','','C'); $pdf->Ln(0.5); $pdf->SetFont('','',7);
						
						$pdf->Image('images/icon_trans1.jpg',90,40,110,110,'JPG');
						
                        // Get students profile data 000000000000000000000000000000000000000000
                    if (0 != $profile) {
                            //$row_p = mysqli_fetch_assoc($query);
                            //$stdid = $row_p['std_id'];
                            //echo '';
                       
                    }
                    
                     
	$h = 4; $ln = 6;
	$pdf->SetY(28);				
	$pdf->Write($h-1,'Name: '); $pdf->SetX(20); $pdf->Cell(35,$h-2,$surname,'',0,'L'); $pdf->SetX(55); $pdf->Cell(35,$h-2,$firstname,'',0,'L');  $pdf->SetX(90); $pdf->Cell(30,$h-2,$othernames,'',0,'L'); $pdf->Ln();
	 $pdf->SetX(20); $pdf->Cell(96,$h-2,'........................................................................................................................................','',0,'L');$pdf->Ln(); 
	 $pdf->SetX(20); $pdf->Cell(35,$h-2,'(Surname)','',0,'L'); $pdf->SetX(55); $pdf->Cell(35,$h-2,'(First Name)','',0,'L');  $pdf->SetX(90); $pdf->Cell(30,$h-2,'(Middle Name)','',0,'L'); $pdf->SetFontSize(5);  $pdf->SetFontSize(7); $pdf->Ln(); $pdf->Ln();
	$pdf->Write($h-1,'Parmanent Address: '); $pdf->SetX(35); $pdf->MultiCell(81,$h-2,$contact_address,'','L'); $pdf->SetX(35); $pdf->MultiCell(81,$h-2,'..................................................................................................................','','L'); $pdf->Ln();
	$pdf->Write($h-1,'State of Origin: '); $pdf->SetX(29); $pdf->Cell(35,$h-2,$state_of_origin,'',0,'L');$pdf->Write($h-1,'Division (LGA): '); $pdf->Cell(35,$h-2,$local_gov,'',0,'L'); $pdf->Ln(); $pdf->SetX(29); $pdf->Cell(36,$h-2,'................................................','',0,'L'); $pdf->SetX(83); $pdf->Cell(33,$h-2,'............................................','',0,'L'); $pdf->Ln();$pdf->Ln();
	$pdf->Write($h-1,'Parent\'s or Guardian\'s Name: '); $pdf->SetX(45); $pdf->MultiCell(71,$h-2,$parents_name,'','L'); $pdf->SetX(44); $pdf->MultiCell(74,$h-2,'....................................................................................................','','L'); $pdf->Ln();//$pdf->Cell(10,'',$firstname,'','L');
	
	//$pdf->SetX(100);
	$pdf->SetY(30);
	$pdf->SetLeftMargin(125);// $pdf->SetTopMargin(-100);
	$pdf->Write($h-1,'Male/Female: ');$pdf->SetX(142); $pdf->MultiCell(40,$h-2,$gender,'','L'); $pdf->SetX(142); $pdf->MultiCell(43,$h-2,'........................................................','','L');$pdf->Ln();
	$pdf->Write($h-1,'Place of Birth: '); $pdf->SetX(142); $pdf->MultiCell(40,$h-2, $place_of_birth,'','L');  $pdf->SetX(142); $pdf->MultiCell(43,$h-2,'........................................................','','L');$pdf->Ln();
	$pdf->Write($h-1,'Date of Birth: '); $pdf->SetX(142); $pdf->MultiCell(40,$h-2,$birthdate,'','L'); $pdf->SetX(142); $pdf->MultiCell(43,$h-2,'........................................................','','L');$pdf->Ln();
	$pdf->Write($h-1,'Marital Status: '); $pdf->SetX(142); $pdf->MultiCell(40,$h-2,$marital_status,'','L');  $pdf->SetX(142); $pdf->MultiCell(43,$h-2,'........................................................','','L');$pdf->Ln();
	
	$pdf->SetY(25);
	$pdf->SetLeftMargin(190);// $pdf->SetTopMargin(-100);
	$pdf->Write($h-1,'STUDENTS REGISTRATION NO: ');$pdf->MultiCell(60,$h-2,$matno,'','L'); $pdf->SetX(228);
	$pdf->MultiCell(97,$h-2,'......................................................................................','','L');
	$pdf->Ln();
	$pdf->Write($h-1,'Faculty: ');$pdf->MultiCell(100,$h-2,$faculty_title,'','L'); $pdf->SetX(199);
	$pdf->MultiCell(100,$h-2,'................................................................................................................................','','L');
	$pdf->Ln();
	$pdf->Write($h-1,'Department: ');$pdf->MultiCell(100,$h-2,G_department($d),'','L'); $pdf->SetX(204);
	$pdf->MultiCell(100,$h-2,'........................................................................................................................','','L');
	$pdf->Ln();
	$pdf->Write($h-1,'Year of Entry: ');$pdf->MultiCell(100,$h-2,$yearentry,'','L'); $pdf->SetX(206);
	$pdf->MultiCell(100,$h-2,'......................................................................................................................','','L');
	$pdf->Ln();
	$pdf->Write($h-1,'Last Institution Attended: '); $pdf->MultiCell(100,$h-2,'','','L'); $pdf->SetX(218);
	$pdf->MultiCell(100,$h-2,'....................................................................................................','','L');
	//$pdf->Cell(20,$h,'Marital Status ','B',0,'L'); $pdf->Cell(20,$h,$marital_status,'B',0,'L');$pdf->Ln(0);
	
	
	
	$pdf->SetLeftMargin('');
	//$pdf->Ln(0);
	//$pdf->Rect(10,55,280,110,'');
	
	//$pdf->Rect(10,55,50,110,'B'); // School certificates
	
	$pdf->Rect(60,55,230,105,'B'); // Result tables for 1st and 2nd semester
                    // End students profile 0000000000000000000000000000000000000000000000000000000
					
					
					
					
									//echo $sc_num;
									$pdf->SetLeftMargin(10); $pdf->SetY(55);
									$pdf->Cell(50,$h,'','LRBT',0,'L'); $pdf->Ln(); $pdf->SetFont('','B');
									$pdf->MultiCell(50,$h-1,'School Certificate / Gen. Cert of Education','LRBT','C'); $pdf->SetFont('','');
									$pdf->Cell(50,$h, $school_cert,'LRBT',0,'L'); $pdf->Ln();
									$pdf->Cell(50,$h,$school_cert_yr,'LRBT',0,'L'); $pdf->Ln();
									
									$sc = 0;
									if ( $sc_num != 0 ) {
										// get school certificates results
										foreach ( $sc_result as $sc_index=>$sc_value ) {
											$sc += 1;// echo 'me';
										
							$pdf->Cell(40,$h,$sc_value['subject'],'LRBT',0,'L'); $pdf->Cell(10,$h,$sc_value['grade'],'LRBT',0,'L'); $pdf->Ln();
										}
										
									}
									// generate max rows for school certificates. eg 14
									for ($si = 1; $si < (14 - $sc); $si++) {
										$pdf->Cell(40,$h,'','LRBT',0,'L'); $pdf->Cell(10,$h,'','LRBT',0,'L'); $pdf->Ln();
									}
									unset ($sc, $si);
									
                    $pdf->SetY(130);// $pdf->SetLineWidth(2);
					$pdf->MultiCell(50,$h-1,G_department($d),'','C');
					$pdf->MultiCell(50,$h-3,'....................................','','C');//$pdf->Ln();	
					$pdf->MultiCell(50,$h,'Curriculum','','C');// $pdf->Ln();
					$pdf->MultiCell(50,$h-1,get_degree( $stddegree_id ),'','C');
					$pdf->MultiCell(50,$h-3,'....................................','','C');
					$pdf->MultiCell(50,$h,'Degree','','C'); 
					$pdf->MultiCell(50,$h-1,func_degree_class($profile, $semester_flag, $reg_sems),'','C'); 
					$pdf->MultiCell(50,$h-3,'....................................','','C');
					$pdf->MultiCell(50,$h,'Class of Degree.','','C'); //$pdf->Ln();
					$pdf->MultiCell(50,$h-1,'','','C'); 
					$pdf->MultiCell(50,$h-3,'....................................','','C');
					$pdf->MultiCell(50,$h,'Date of Graduation','','C');// $pdf->Ln();
					$pdf->MultiCell(50,$h-1,'','','C');
					$pdf->MultiCell(50,$h-3,'....................................','','C'); 
					$pdf->MultiCell(50,$h,'Major','','C'); //$pdf->Ln();
					$pdf->MultiCell(50,$h-1,'','','C');
					$pdf->MultiCell(50,$h-3,'....................................','','C'); 
					$pdf->MultiCell(50,$h,'Minor','','C');// $pdf->Ln();
					$pdf->MultiCell(50,$h-1,'','','C');
					$pdf->MultiCell(50,$h-3,'....................................','','C'); 
					$pdf->MultiCell(50,$h,'Withdrawal Date','','C'); //$pdf->Ln();
					
                    
					$result_status = $row['season'] != 'NORMAL' ? ' ( '.$row['season'].' ) ' : '';
					//echo $row['season'];
                  
                            
                            //77777777777777777777777777777 FIRST SEMESTER BEGINS
                       	$pdf->Rect(61,55,114,105,'B'); //first semester
						$pdf->SetY(55); $pdf->SetLeftMargin(61);
						$pdf->SetFont('','B',7);
						$pdf->Cell(50,$h,'FIRST SEMESTER '. $row['rslevelid'].'/'.$c_duration,'LRTB','','L');
						$pdf->Cell(64,$h,'YEAR '. $row['ysession'] . '/' . (intval($row['ysession']) + 1) ,'LRTB','','L'); $pdf->Ln();
						$pdf->SetFont('','','');
						$tdc = array('1'=>8, '2'=>10, '3'=>66, '4'=>10, '5'=>10, '6'=>10);
						
						$pdf->Cell($tdc['1'],$h + 2,'DEPT','LRBT','','C');
						$pdf->Cell($tdc['2'],$h + 2,'Number','LRBT','','C');
						$pdf->Cell($tdc['3'],$h + 2,'Course Title','LRBT','','C');
						$pdf->Cell($tdc['4'],$h + 2,'Sem Hrs','LRBT','','C');
						$pdf->Cell($tdc['5'],$h + 2,'Grade','LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h + 2 ,'Points','LRBT','','C'); $pdf->Ln();
							
						$h = 3.8;
				
				
                            if ( 0 != $i1 ) {
                                
                                unset ($tot_cu_1);
                                while ( $code = mysqli_fetch_assoc( $q_fc_1 ) ) {
                              $mygrade = get_grade($row['ysession'], $row['rslevelid'], $code['thecourse_id'], 'First Semester', $row['std_id']);
									$myctitle = get_c_title($code['thecourse_id'], $result_status, $row['ysession']);
                                    $mypoint = get_point( $mygrade, $code['c_unit'] );
                                                                            $tot_cu_1 += $code['c_unit'];
                                        $tot_pt_1 += $mypoint;
										//$i1 = strlen($myctitle) > 35 ? $i1 + 2 : $i1 ;
										
						$pdf->Cell($tdc['1'],$h ,substr($code['stdcourse_custom2'],0,3),'LRBT','','C');
						$pdf->Cell($tdc['2'],$h , substr($code['stdcourse_custom2'],-4),'LRBT','','C');
						$pdf->Cell($tdc['3'],$h , substr(ucwords($myctitle),0,41),'LRBT','','L');
						$pdf->Cell($tdc['4'],$h ,$code['c_unit'],'LRBT','','C');
						$pdf->Cell($tdc['5'],$h , $mygrade,'LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  , round($mypoint, 2),'LRBT','','C'); $pdf->Ln();
						
                                } unset($mygrade); // while loop finishes
                                if ( $i1 != 0 ) {
                                
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'','BT','','C');
						$pdf->Cell($tdc['4'],$h ,$tot_cu_1,'LRBT','','C');
						$pdf->Cell($tdc['5'],$h,round(($tot_pt_1 / $tot_cu_1),2),'LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  ,round($tot_pt_1, 2),'LRBT','','C'); $pdf->Ln();
						
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'','BT','','C');
						$pdf->Cell($tdc['4'],$h ,'','LRBT','','C');
						$pdf->Cell($tdc['5'],$h,'','LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  ,'','LRBT','','C'); $pdf->Ln();
						
										$td_max1 = 24;
							if ($i2 != 0) { //if records found in sec
                                if ($i1 < $td_max1) {
                                    for ($t = 1; $t < ($td_max1 - $i1); $t++ ) {
						
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'','BT','','C');
						$pdf->Cell($tdc['4'],$h ,'','LRBT','','C');
						$pdf->Cell($tdc['5'],$h,'','LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  ,'','LRBT','','C'); $pdf->Ln();
						
                                    }
                                }
								
							}
                                }
                            }	
                            
                            // 7777777777777777777777777777 SECOND SEMESTER BEGINS
                            
						$pdf->Rect(176,55,114,105,'B'); //second semester	 
						$pdf->SetY(55); $pdf->SetLeftMargin(176);
						$pdf->SetFont('','B',7);
						$pdf->Cell(50,$h,'SECOND SEMESTER '. $row['rslevelid'].'/'.$c_duration,'LRTB','','L');
						$pdf->Cell(64,$h,'YEAR '. $row['ysession'] . '/' . (intval($row['ysession']) + 1) ,'LRTB','','L'); $pdf->Ln();
						$pdf->SetFont('','','');
						$tdc = array('1'=>8, '2'=>10, '3'=>66, '4'=>10, '5'=>10, '6'=>10);
						
						$pdf->Cell($tdc['1'],$h + 2,'DEPT','LRBT','','C');
						$pdf->Cell($tdc['2'],$h + 2,'Number','LRBT','','C');
						$pdf->Cell($tdc['3'],$h + 2,'Course Title','LRBT','','C');
						$pdf->Cell($tdc['4'],$h + 2,'Sem Hrs','LRBT','','C');
						$pdf->Cell($tdc['5'],$h + 2,'Grade','LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h + 2 ,'Points','LRBT','','C'); $pdf->Ln();
						
						
                           // }
                            
                            if ( 0 != $i2 ) {
                                unset ($tot_cu_2);
                                while ( $code = mysqli_fetch_assoc( $q_fc_2 ) ) {
                                    $mygrade = get_grade($row['ysession'], $row['rslevelid'], $code['thecourse_id'], 'Second Semester', $row['std_id']);
									$myctitle = get_c_title($code['thecourse_id'], $result_status, $row['ysession']);
                                    $mypoint = get_point( $mygrade, $code['c_unit'] ); // $code['thecourse_id'];
                                   
                                        $tot_cu_2 += $code['c_unit'];
                                        $tot_pt_2 += round($mypoint, 2);
										//$i2 = strlen($myctitle) > 35 ? $i2 + 2 : $i2 ;
										
										
						$pdf->Cell($tdc['1'],$h,substr($code['stdcourse_custom2'],0,3),'LRBT','','C');
						$pdf->Cell($tdc['2'],$h , substr($code['stdcourse_custom2'],-4),'LRBT','','C');
						$pdf->Cell($tdc['3'],$h , substr(ucwords($myctitle),0,41),'LRBT','','L');
						$pdf->Cell($tdc['4'],$h ,$code['c_unit'],'LRBT','','C');
						$pdf->Cell($tdc['5'],$h , $mygrade,'LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  , round($mypoint, 2),'LRBT','','C'); $pdf->Ln();
						
                                } unset($mygrade); // while loop finishes
                               
										
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'','BT','','C');
						$pdf->Cell($tdc['4'],$h ,$tot_cu_2,'LRBT','','C');
						$pdf->Cell($tdc['5'],$h,round(($tot_pt_2 / $tot_cu_2),2),'LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h , round($tot_pt_2, 2),'LRBT','','C'); $pdf->Ln();
						
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'','BT','','C');
						$pdf->Cell($tdc['4'],$h ,'','LRBT','','C');
						$pdf->Cell($tdc['5'],$h,'','LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  ,'','LRBT','','C'); $pdf->Ln();
						
                            }	
                            
                            //777777777777777777777777777777 FIRST & SECOND SEMESTERS ENDS HERE
                            $td_max2 = 24;
							if ($i2 != 0) { //if records found in sec
                                if ($i2 < $td_max2) {
                                    for ($t = 1; $t < ($td_max2 - $i2-3); $t++ ) {
												
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'','BT','','C');
						$pdf->Cell($tdc['4'],$h ,'','LRBT','','C');
						$pdf->Cell($tdc['5'],$h,'','LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  ,'','LRBT','','C'); $pdf->Ln();
						
                                    }
                                }
								$g_tot_cu += $tot_cu_1 + $tot_cu_2;
								$g_tot_pt += round($tot_pt_1 + $tot_pt_2, 2);
                           
								
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'TOTAL Cum. Average','BT','','R');
						$pdf->Cell($tdc['4'],$h ,$tot_cu_1 + $tot_cu_2,'LRBT','','C');
						$pdf->Cell($tdc['5'],$h,round((( $tot_pt_1 + $tot_pt_2 )/( $tot_cu_1 + $tot_cu_2 )),2),'LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  ,$tot_pt_1 + $tot_pt_2,'LRBT','','C'); $pdf->Ln();
						
                            }
							//if ( $i_count == $i_sem ) { // final row for class of degree generated
							$mydegree = round((( $g_tot_pt )/( $g_tot_cu )),2);
							
												
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'','BT','','C');
						$pdf->Cell($tdc['4'],$h ,'','LRBT','','C');
						$pdf->Cell($tdc['5'],$h,'','LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  ,'','LRBT','','C'); $pdf->Ln();
						
							
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'TOTAL','BT','','R');
						$pdf->Cell($tdc['4'],$h ,$g_tot_cu,'LRBT','','C');
						$pdf->Cell($tdc['5'],$h,$mydegree,'LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h , round($g_tot_pt, 2),'LRBT','','C');// $pdf->Ln();
						
                            unset ($tot_cu_1, $tot_cu_2, $tot_pt_1, $tot_pt_2, $i2);
                        
						 
                    
                    
						//	
						//$pdf->Rect(70,171,60,28,'B'); //classification of degree
						$pdf->SetFontSize(6);
						$h = 4;
						
						$pdf->SetXY(70,162); $pdf->SetLeftMargin(70); 
						
						$pdf->MultiCell(60,$h,'Remarks','','L'); // remarks
						
						$pdf->MultiCell(60,$h-3,'','LRT','C');
						$pdf->MultiCell(60,$h-1,'CLASSIFICATION OF DEGREE','LR','C');
						$pdf->Cell(8,$h-1,'(i)','L','','C');
						$pdf->Cell(15,$h-1,'4.50 - 5.00','','','C'); 
						$pdf->Cell(10,$h-1,'-','','','C');
						$pdf->Cell(27,$h-1,'First Class','R','','L'); $pdf->Ln();
						
						$pdf->Cell(8,$h-1,'(ii)','L','','C');
						$pdf->Cell(15,$h-1,'3.50 - 4.49','','','C'); 
						$pdf->Cell(10,$h-1,'-','','','C');
						$pdf->Cell(27,$h-1,'Second Class Upper','R','','L'); $pdf->Ln();
						
						$pdf->Cell(8,$h-1,'(iii)','L','','C');
						$pdf->Cell(15,$h-1,'2.40 - 3.49','','','C'); 
						$pdf->Cell(10,$h-1,'-','','','C');
						$pdf->Cell(27,$h-1,'Second Class Lower','R','','L'); $pdf->Ln();
						
						$pdf->Cell(8,$h-1,'(iv)','L','','C');
						$pdf->Cell(15,$h-1,'1.50 - 2.39','','','C'); 
						$pdf->Cell(10,$h-1,'-','','','C');
						$pdf->Cell(27,$h-1,'Third Class','R','','L'); $pdf->Ln();
						
						$pdf->Cell(8,$h-1,'(v)','L','','C');
						$pdf->Cell(15,$h-1,'1.00 - 1.49','','','C'); 
						$pdf->Cell(10,$h-1,'-','','','C');
						$pdf->Cell(27,$h-1,'Pass','R','','L'); $pdf->Ln();
						
						$pdf->Cell(8,$h-1,'(vi)','L','','C');
						$pdf->Cell(15,$h-1,'0.00 - 0.99','','','C'); 
						$pdf->Cell(10,$h-1,'-','','','C');
						$pdf->Cell(27,$h-1,'Fail','R','','L'); $pdf->Ln();
						$pdf->MultiCell(60,$h-3,'','LRB','C'); //$pdf->Ln(0);
						
						
						//$pdf->Rect(135,171,60,28,'B');
						$pdf->SetXY(135,162); $pdf->SetLeftMargin(135); 
						
						$pdf->MultiCell(60,$h,'','','C'); // remarks
						
						$pdf->MultiCell(60,$h-3,'','LRT','C');
						$pdf->MultiCell(60,$h-1,'GRADING SYSTEM','LR','C');
						$pdf->Cell(15,$h-1,'A','L','','C');
						$pdf->Cell(8,$h-1,'-','','','C'); 
						$pdf->Cell(27,$h-1,'Excellent','','','L');
						$pdf->Cell(10,$h-1,'5.00','R','','C'); $pdf->Ln();
						
						$pdf->Cell(15,$h-1,'B','L','','C');
						$pdf->Cell(8,$h-1,'-','','','C'); 
						$pdf->Cell(27,$h-1,'Very Good','','','L');
						$pdf->Cell(10,$h-1,'4.00','R','','C'); $pdf->Ln();
						
						$pdf->Cell(15,$h-1,'C','L','','C');
						$pdf->Cell(8,$h-1,'-','','','C'); 
						$pdf->Cell(27,$h-1,'Good','','','L');
						$pdf->Cell(10,$h-1,'3.00','R','','C'); $pdf->Ln();
						
						$pdf->Cell(15,$h-1,'D','L','','C');
						$pdf->Cell(8,$h-1,'-','','','C'); 
						$pdf->Cell(27,$h-1,'Average','','','L');
						$pdf->Cell(10,$h-1,'2.00','R','','C'); $pdf->Ln();
						
						$pdf->Cell(15,$h-1,'E','L','','C');
						$pdf->Cell(8,$h-1,'-','','','C'); 
						$pdf->Cell(27,$h-1,'Pass','','','L');
						$pdf->Cell(10,$h-1,'1.00','R','','C'); $pdf->Ln();
						
						$pdf->Cell(15,$h-1,'F','L','','C');
						$pdf->Cell(8,$h-1,'-','','','C'); 
						$pdf->Cell(27,$h-1,'Fail','','','L');
						$pdf->Cell(10,$h-1,'0','R','','C'); $pdf->Ln();
						$pdf->MultiCell(60,$h-3,'','LRB','C'); //$pdf->Ln(0);
						//$pdf->SetY(55); $pdf->SetLeftMargin(61);
                    //}
					
						
						$pdf->SetXY(220,162); $pdf->SetLeftMargin(220); 
						$pdf->SetFont('','I',6);
						$pdf->MultiCell(70,$h,'This is a true copy of the record on File in this office','','C'); // remarks
						$pdf->Image('images/registrars_signature.jpg',235,170,40,6,'JPG');
						$pdf->SetY(177);
						$pdf->MultiCell(70,$h-1,'.........................................................................................','','C');
						$pdf->SetFont('','B',10); $pdf->MultiCell(70,$h,'Registrar','','C');
						
						
					$pdf->SetXY('',''); $pdf->SetLeftMargin('');  $pdf->SetFontSize('');
					
                   
	//}// END TEST FOR FIRST AND SECOND SEMESTES
	
	}
	
}

?>

<?php /*
<br /><br />
<div>
	<a href="mypdf.pdf"><img src="images/pdf.gif" alt="download" /> Download pdf</a> | <a href="viewpdf.pdf"><img src="images/mail.png" alt="view transcript" />View pdf</a>
</div>
<br /><br /><br />
</div>
*/


#signature placeholder

//echo '<div class="sph center" style="text-align:center; font-size:15px; font-weight:700;">  .......................................................................<br>Registrar</div>';


unset ($g_tot_pt, $g_tot_cu, $i_sem);
$set = array();
unset($set,$course_duration, $cpga, $remark, $ignore);
mysqli_close($connect);
//ob_end_flush();



$myfile = "downloads/". $stdid . ".pdf";
//$myfile = $stdid . ".pdf";
$pdf->Output($myfile ,"F");



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

function get_c_title($thecourse_id, $result_status, $session)
{
	$sql = "Select course_title From all_courses 
			Where thecourse_id='$thecourse_id'";
			//AND course_custom5='$session'";
			/*std_mark_custom2='$session' 
			AND level_id='$l' 
			AND std_mark_custom1='$semester'
			AND stdcourse_id='$c_id'"; AND ='$result_status' LIMIT 1"; */
			
	$q = mysqli_query($GLOBALS['connect'], $sql);
	if ( 0 != mysqli_num_rows( $q ) ) {
		while ( $r = mysqli_fetch_assoc( $q ) ) {
			return $r['course_title'];
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
	
	return round($p, 2);
	
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
	
	return strtoupper($deg); //.'='.$ii;
}


function get_degree( $stddegree_id ) {
	$str = "Select degree_name From degree Where degree_id=$stddegree_id";
	$q = mysqli_query( $GLOBALS['connect'], $str );
	if ( 0 != mysqli_num_rows($q) ) {
		$r = mysqli_fetch_assoc($q);
		$deg = $r['degree_name'];
	}
	
	return strtoupper($deg);
}
?>