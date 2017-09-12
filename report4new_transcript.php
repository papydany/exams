<?php
	
	//ob_start("ob_gzhandler");
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

/*
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Transcript Report</title>
<link type="text/css" href="report1.css" rel="stylesheet" />
<style>	
html,body,div,span,applet,object,iframe,h1,h2,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,font,img,ins,kbd,q,s,samp,small,strike,sub,sup,tt,var,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,caption,table{border:0 none;font-family:inherit;font-size:100%;font-style:inherit;font-weight:inherit;vertical-align:baseline;margin:0;padding:0;}
*{margin:0;padding:0;}
body{ font-family:arial; font-size:10px; margin:0px;}
.btd {border-right:1px solid  #06C; border-bottom:1px solid #06C; padding:0px;}
.btable {border:1px solid #06C; }
.db { border-bottom: 1px dotted #06C; }
</style>
<base target="_blank">
</head>
<body><div align="center" >
<div style="width:1180px;">
<?php
*/ //@@@@@@@@@@@@@@@@@@@@@@
$matno = $_POST['txtmatricno'];	
//echo $_POST['txtmatricno'];
 /*
<form id="form1" name="form1" method="post" action="" autocomplete="off">
  <table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" align="center"><span style="font-weight:bold;font-size:22px;">UNIVERSITY OF CALABAR</span><br />
        <span style="font-size:18px;">CALABAR, NIGERIA</span><br />
        <span style="font-size:14px;">STUDENTS ACADEMIC TRANSCRIPT</span></td>
    </tr>
    <tr>
      <td height="29" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="71%">&nbsp;</td>
          <td width="29%"><div style="width:200px;font-size:12px;">STUDENT'S REGISTRATION NO</div>
      <div style="width:100px; margin-left:200px; margin-top:-20px; border-bottom-style: dotted; font-size:14px;">03/72019</div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50%" height="49"><div style="width:70px;">NAME </div>
          <div style="width:470px; margin-left:70px; margin-top:-20px; border-bottom-style: dotted; font-size:14px;">
          <div style="border:0px;border-style:outset;width:150px;">&nbsp;&nbsp;IBUJE</div>
          <div style="border:0px;border-style:outset;width:150px; margin-left:160px; margin-top:-18px;">MANNY</div>
          <div style="border:0px;border-style:outset;width:150px; margin-left:320px; margin-top:-18px;">IGHO</div>
          </div>
          <div style="width:470px; margin-left:65px; font-size:12px;">&nbsp;&nbsp;(Surname)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(First Name) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Middle Name)</div></td>
          <td width="21%" valign="bottom"><div style="width:80px;font-size:12px;">Male/Female</div>
          <div style="width:150px; margin-left:80px; margin-top:-20px; border-bottom-style: dotted; font-size:14px;">MALE</div></td>
          <td width="29%">&nbsp;</td>
        </tr>
        <tr>
          <td height="30"><div style="width:110px;font-size:12px;">Permanent Address</div>
          <div style="width:430px; margin-left:110px; margin-top:-20px; border-bottom-style: dotted; font-size:14px;">THE ADDRESS</div></td>
          <td><div style="width:80px;font-size:12px;">Place of Birth</div>
          <div style="width:150px; margin-left:80px; margin-top:-20px; border-bottom-style: dotted; font-size:14px;">UYO</div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="29"><div style="width:90px;font-size:12px;">State of Origin</div>
          <div style="width:150px; margin-left:90px; margin-top:-20px; border-bottom-style: dotted; font-size:14px;">THE STATE</div>
          <div style="width:150px; margin-left:250px; margin-top:-20px;">Division (LGA)</div>
           <div style="width:180px; margin-left:360px; margin-top:-20px; border-bottom-style: dotted; font-size:14px;">THE LGA</div></td>
          <td><div style="width:80px;font-size:12px;">Date of Birth</div>
          <div style="width:150px; margin-left:80px; margin-top:-20px; border-bottom-style: dotted; font-size:14px;">30TH APRIL, 1984</div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="29"><div style="width:150px;font-size:12px;">Parent's or Guardian's Name</div>
          <div style="width:390px; margin-left:150px; margin-top:-20px; border-bottom-style: dotted; font-size:14px;">DR & MRS CHARLES OKODUA</div></td>
          <td><div style="width:80px;font-size:12px;">Marital Status</div>
          <div style="width:150px; margin-left:80px; margin-top:-20px; border-bottom-style: dotted; font-size:14px;">SINGLE</div></td>
          <td>&nbsp;</td>
        </tr>

        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td width="213">&nbsp;</td>
      <td width="862">&nbsp;</td>
    </tr>
  </table>
</form> */ 
/*?>
<?php */
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
		$major = $row_p['major'];
		$minor = $row_p['minor'];
		$date_of_graduation = $row_p['date_of_graduation'];
		$last_institution = $row_p['last_institution'];
		
		$f = $row_p['stdfaculty_id'];
		$d = $row_p['stddepartment_id'];
		$p = $row_p['stdprogramme_id'];
		$fos = $row_p['stdcourse'];
		
} else {
	echo "<h3>No Student Found. Retry</h3>";
	exit("<p><h5>Thank You...!</h5></p>");
} //-----------------------------------------------
//   `stdfaculty_id` = '6' && `stddepartment_id` = '36' && `stdprogramme_id` = '2' && `stdcourse` = '80' && matric_no = '06/101007'

//$d = (int)$_GET['department_id'];
	//$l = (int)$_GET['s_level'];
	//$f = (int)$_GET['faculty_id'];
	//$s = (int)$_GET['s_session'];
	//$fos = (int)$_GET['course'];
	$c_duration = get_course_duration( $fos );
	//$p = empty($_GET['programme']) ? 2 : $_GET['programme'];	
	
	//$special = isset($_GET['special']) ? $_GET['special'] : false;
	//$status = isset( $_GET['state'] ) ? $_GET['state'] : $l;
	
	
	
	$level_reps = get_levelreps();
	$faculty_title = G_faculty($f);
	
	
	//$fetch_level = $l;
	//$fetch_sess = $special ? $s - ($l-$status) : $s;


//$year_of_study = $level_reps[$l].'/'.$c_duration;

//if( $p == 7 || $p == '7') {
//	$xsession = $s;
//} else {
	//$xsession = $s.'/'.($s+1);
//}


// 7777777777777777777777  CHECK SEMESTER STUDENTS REGISTERS
if ( 0 != $profile ) {
	$reg_sems = array(); //multi dimensional arrays for semesters registered
	$str_sem = "Select DISTINCT std_id, ysession, rslevelid, season From registered_semester Where std_id = '$stdid' ORDER BY rslevelid";// Group By rslevelid"; // NOTE: the grouping semester
	//$str_sem = 'SELECT  `rs_id`,  `std_id`,  `sem`,  `ysession`,  `rslevelid`,  `season` FROM `registered_semester` WHERE std_id = "'.$stdid.'"  GROUP BY `ysession`';
	
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
	$sc_q = @mysqli_query($GLOBALS['connect'], "Select * From school_certificates Where std_id='$stdid' LIMIT 1");
	$sc_num = @mysqli_num_rows( $sc_q );
	while ( $sc_row = @mysqli_fetch_assoc( $sc_q ) ) {
		$sc_result[] = $sc_row;
	}
	
}

//77777777777777777777777777777777  END SEMESTER CHECK
// Begin registered semester for student
//echo $stdid;

$i_sem = 0; // set counter for semester registered	
		
if (( 0 != $profile ) && ( 0 != $semester_flag )) {
	
	foreach ($reg_sems as $index=>$row) {
		$i_sem += 1;// echo $row['season'];
		//  444444444444444444444  FIRST SEMESTER CHECK
			
			$sql_fc_1 = "Select cr.* From course_reg AS cr Where 
							cr.clevel_id='".$row['rslevelid']."'
							AND cr.cyearsession='".$row['ysession']."' 
							AND cr.csemester='First Semester' 
							AND cr.course_season='".$row['season']."'
							AND cr.std_id='".$row['std_id']."'";
							
			/*$sql_fc_1 = "Select ac.*, cr.* From all_courses AS ac, course_reg AS cr Where 
							ac.thecourse_id=cr.thecourse_id 
							
							AND ac.course_custom5=cr.cyearsession 
							 
							AND ac.course_custom5='".$row['ysession']."'
							AND cr.course_season='".$row['season']."'
							AND ac.course_semester='First Semester' 
							AND cr.std_id='".$row['std_id']."'";*/
					//echo $sql_fc_1;	
					//echo $index, '--',$row['ysession'],'<br>';
					$q_fc_1 = mysqli_query($GLOBALS['connect'], $sql_fc_1);
					$i1 = mysqli_num_rows($q_fc_1);
			
			// AND ac.level_id=cr.clevel_id 
			// AND ac.level_id='".$row['rslevelid']."'
			
			//  4444444444444444444  SECOND SEMESTER CHECK	
			$sql_fc_2 = "Select cr.* From course_reg AS cr Where 
							cr.clevel_id='".$row['rslevelid']."'
							AND cr.cyearsession='".$row['ysession']."' 
							AND cr.csemester='Second Semester'
							AND cr.course_season='".$row['season']."'  
							AND cr.std_id='".$row['std_id']."'";
			//echo 	$sql_fc_2;				
			/*$sql_fc_2 = "Select ac.*, cr.* From all_courses AS ac, course_reg AS cr Where 
							ac.thecourse_id=cr.thecourse_id 
							
							AND ac.course_custom5=cr.cyearsession 
							
							AND cr.clevel_id='".$row['rslevelid']."'
							AND cr.cyearsession='".$row['ysession']."' 
							
							AND ac.course_semester='Second Semester' 
							AND cr.std_id='".$row['std_id']."'";
					*/
					
					//$sql_fc_2 = 'SELECT * FROM course_reg WHERE std_id = '.$row['std_id'].' && clevel_id = '.$row['rslevelid'].' && cyearsession = "'.$row['ysession'].' ORDER BY stdcourse_custom2';
							
							//AND cr.course_season='".$row['season']."'
					//AND ac.course_custom5='".$row['ysession']."'
					//AND ac.level_id='".$row['rslevelid']."' 
					//AND ac.level_id=cr.clevel_id 
					
					//echo $sql_fc_1;	
					//echo $index, '--',$row['ysession'],'<br>';
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
/*@@@@@@@@@@@@@
?>


                    <div style="border:1px solid #06C;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-image:url(images/icon_trans1.jpg); background-repeat:no-repeat; background-position:center;">
                    <tr><td colspan="2"><?php
                    echo '<div style="padding:0px; padding-top:0px; padding-bottom:0px; border:1px solid #06C;">
                        <div style="text-align:center; margin-bottom:1px;">
                            <p class="br" style="font-size:18px; font-weight:700;">UNIVERSITY OF CALABAR</p>
                            <p class="br" style="font-size:16px; font-weight:700;">CALABAR, NIGERIA</p>
                            <p class="br" style="font-size:14px; font-weight:700;">STUDENTS ACADEMIC TRANSCRIPT</p>
                        </div>';
					*///@@@@@@@@@@@@	//$pdf->SetX(10);
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
         /*@@@@@@@               echo '<div style="margin-bottom:1px #06C; display:block; overflow:hidden;">
                            <div >
                            <table border="0" cellpadding="0" cellspacing="5">
                                <tr><td width="500" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0">
                                    <tr><td valign="top"><br><br><div class="db" ><strong>NAME: </strong>',$surname,' ',$firstname,' ',$othernames,'</div><br></td></tr>
                                        
                                        <tr><td width="500"><div class="db" ><strong>Parmanent Address:</strong> ', $contact_address , '</div></td></tr>
                                        <tr><td><div class="db" ><strong>State of Origin: </strong> ', $state_of_origin ,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Division (LGA): </strong> ', $local_gov , '</div ></td></tr>
                                        <tr><td><div class="db" ><strong>Parent\'s or Guardian\'s Name: </strong>', $parents_name , '</div></td></tr>
                                        
                                    </table>
                                </td><td width="300" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0">
                                        <tr><td width="300" ><br><br><div class="db" ><strong>Male/Female: </strong> ', $gender ,'</div></td></tr>
                                        <tr><td><div class="db" ><strong>Place of Birth: </strong> ', $place_of_birth ,'</div></td></tr>
                                        <tr><td><div class="db" ><strong>Date of Birth: </strong> ', $birthdate ,'</div></td></tr>
                                        <tr><td><div class="db" ><strong>Marital Status: </strong> ', $marital_status ,'</div></td></tr>
                                    </table>
                                </td>
                            <td valign="top">
                            <div >
                                <table width="300" border="0" cellpadding="0" cellspacing="0">
                                    <tr><td><div class="db" ><strong>STUDENT\'S REGISTRATION NO: </strong> ', $matno , '</div></td></tr>
                                    <tr><td><div class="db" ><strong>Faculty: </strong> ',$faculty_title,'</div></td></tr>
                                    <tr><td><div class="db" ><strong>Department: </strong> ',G_department($d),'</div></td></tr>
                                    <tr><td><div class="db" ><strong>Year of Entry: </strong> ',$yearentry,'</div></td></tr>
                                    <tr><td><div class="db" >',$last_institution,'</div><strong>Last Institution Attended</strong></td></tr>
                                </table>
                            </div>
                            </td></tr></table></div><br><br>
                        </div>';
                    }
                    
                    echo '</div>'; */ //@@@@@@@@@@@@@@@@
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
	$pdf->Write($h-1,'Last Institution Attended: '); $pdf->MultiCell(100,$h-2,$last_institution,'','L'); $pdf->SetX(218);
	$pdf->MultiCell(100,$h-2,'....................................................................................................','','L');
	//$pdf->Cell(20,$h,'Marital Status ','B',0,'L'); $pdf->Cell(20,$h,$marital_status,'B',0,'L');$pdf->Ln(0);
	
	
	
	$pdf->SetLeftMargin('');
	//$pdf->Ln(0);
	//$pdf->Rect(10,55,280,110,'');
	
	//$pdf->Rect(10,55,50,110,'B'); // School certificates
	
	$pdf->Rect(60,55,230,105,'B'); // Result tables for 1st and 2nd semester
                    // End students profile 0000000000000000000000000000000000000000000000000000000
					
					
					
	/*@@@@@@@@@@@				 ?>
                    </td></tr>
                    <tr>
                        <td width="200" valign="top" class="btable" >
                                <table border="0" cellpadding="0" cellspacing="0" style="padding-right:5px">
                                    <tr><th class="btd" colspan="2">&nbsp;</th></tr>
                                    <tr>
                                      <td class="btd" colspan="2" align="center"><b>School Certificate / Gen. Cert of Education</b></td></tr>
                                    <tr><td class="btd" colspan="2">&nbsp;<strong><?php echo $school_cert;?></strong></td></tr>
                                    <tr><td class="btd" colspan="2">&nbsp;<strong><?php echo $school_cert_yr;?></strong></td></tr>
                                    <?php */ //@@@@@@@@@@@@@@
									//echo $sc_num;
									$pdf->SetLeftMargin(10); $pdf->SetY(55);
									$pdf->Cell(50,$h,'','LRBT',0,'L'); $pdf->Ln(); $pdf->SetFont('','B');
									$pdf->MultiCell(50,$h-1,'School Certificate / Gen. Cert of Education','LRBT','C'); $pdf->SetFont('','');
									$pdf->Cell(50,$h, $school_cert,'LRBT',0,'L'); $pdf->Ln();
									$pdf->Cell(50,$h,$school_cert_yr,'LRBT',0,'L'); $pdf->Ln();
									
									$sc = 0;
									if ( $sc_num != 0 ) {
										// get school certificates results
	/* @@@@@@@@@@@@									foreach ( $sc_result as $sc_index=>$sc_value ) {
											//$sc += 1;// echo 'me';
											echo '<tr><td class="btd">&nbsp;',$sc_value['subject'],'</td><td class="btd" align="center">&nbsp;',$sc_value['grade'],'</td></tr>';
											echo '<tr><td class="btd">&nbsp;',$sc_value['subject1'],'</td><td class="btd" align="center">&nbsp;',$sc_value['grade1'],'</td></tr>';
											echo '<tr><td class="btd">&nbsp;',$sc_value['subject2'],'</td><td class="btd" align="center">&nbsp;',$sc_value['grade2'],'</td></tr>';
											echo '<tr><td class="btd">&nbsp;',$sc_value['subject3'],'</td><td class="btd" align="center">&nbsp;',$sc_value['grade3'],'</td></tr>';
											echo '<tr><td class="btd">&nbsp;',$sc_value['subject4'],'</td><td class="btd" align="center">&nbsp;',$sc_value['grade4'],'</td></tr>';
											echo '<tr><td class="btd">&nbsp;',$sc_value['subject5'],'</td><td class="btd" align="center">&nbsp;',$sc_value['grade5'],'</td></tr>';
											echo '<tr><td class="btd">&nbsp;',$sc_value['subject6'],'</td><td class="btd" align="center">&nbsp;',$sc_value['grade6'],'</td></tr>';
											echo '<tr><td class="btd">&nbsp;',$sc_value['subject7'],'</td><td class="btd" align="center">&nbsp;',$sc_value['grade7'],'</td></tr>';
											echo '<tr><td class="btd">&nbsp;',$sc_value['subject8'],'</td><td class="btd" align="center">&nbsp;',$sc_value['grade8'],'</td></tr>'; */ //@@@@@@@@@@@@@@@@@
											$pdf->Cell(40,$h,$sc_value['subject'],'LRBT',0,'L'); $pdf->Cell(10,$h,$sc_value['grade'],'LRBT',0,'L'); $pdf->Ln();
											$pdf->Cell(40,$h,$sc_value['subject1'],'LRBT',0,'L'); $pdf->Cell(10,$h,$sc_value['grade1'],'LRBT',0,'L'); $pdf->Ln();
											$pdf->Cell(40,$h,$sc_value['subject2'],'LRBT',0,'L'); $pdf->Cell(10,$h,$sc_value['grade2'],'LRBT',0,'L'); $pdf->Ln();
											$pdf->Cell(40,$h,$sc_value['subject3'],'LRBT',0,'L'); $pdf->Cell(10,$h,$sc_value['grade3'],'LRBT',0,'L'); $pdf->Ln();
											$pdf->Cell(40,$h,$sc_value['subject4'],'LRBT',0,'L'); $pdf->Cell(10,$h,$sc_value['grade4'],'LRBT',0,'L'); $pdf->Ln();
											$pdf->Cell(40,$h,$sc_value['subject5'],'LRBT',0,'L'); $pdf->Cell(10,$h,$sc_value['grade5'],'LRBT',0,'L'); $pdf->Ln();
											$pdf->Cell(40,$h,$sc_value['subject6'],'LRBT',0,'L'); $pdf->Cell(10,$h,$sc_value['grade6'],'LRBT',0,'L'); $pdf->Ln();
											$pdf->Cell(40,$h,$sc_value['subject7'],'LRBT',0,'L'); $pdf->Cell(10,$h,$sc_value['grade7'],'LRBT',0,'L'); $pdf->Ln();
											$pdf->Cell(40,$h,$sc_value['subject8'],'LRBT',0,'L'); $pdf->Cell(10,$h,$sc_value['grade8'],'LRBT',0,'L'); $pdf->Ln();
										}
										
									}
									// generate max rows for school certificates. eg 14
									for ($si = 1; $si < (5); $si++) {
									/*	echo '<tr><td class="btd">&nbsp;</td><td class="btd" align="center">&nbsp;</td></tr>'; */
										$pdf->Cell(40,$h,'','LRBT',0,'L'); $pdf->Cell(10,$h,'','LRBT',0,'L'); $pdf->Ln();
									}
									unset ($sc, $si);
		/*@@@@@@@@@@@							?>
											
                                </table>
                                <br /><br />
                                <div align="center" style="line-height:1em">
                                	<div><strong><?php echo G_department($d);?></strong><br />.....................................................<br />Curriculum</div><br />
                                	<div><strong><?php echo get_degree( $stddegree_id );?></strong><br />.....................................................<br />Degree</div><br />
                                    <div><strong><?php echo func_degree_class($profile, $semester_flag, $reg_sems) ; ?></strong><br />.....................................................<br />Class of Degree</div><br />
                                    <div><strong><?php echo $date_of_graduation;?></strong><br />.....................................................<br />Date of Graduation</div><br />
                                    <div><strong><?php echo $major;?></strong><br />.....................................................<br />Major</div><br />
                                    <div><strong><?php echo $minor;?></strong><br />.....................................................<br />Withdrawal Date</div><br />
                                </div>
                        </td>
                        <td  valign="top" ><?php */ //@@@@@@@@@@@
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
					$pdf->MultiCell(50,$h-1,$date_of_graduation,'','C'); 
					$pdf->MultiCell(50,$h-3,'....................................','','C');
					$pdf->MultiCell(50,$h,'Date of Graduation','','C');// $pdf->Ln();
					$pdf->MultiCell(50,$h-1,$major,'','C');
					$pdf->MultiCell(50,$h-3,'....................................','','C'); 
					$pdf->MultiCell(50,$h,'Major','','C'); //$pdf->Ln();
					$pdf->MultiCell(50,$h-1,$minor,'','C');
					$pdf->MultiCell(50,$h-3,'....................................','','C'); 
					$pdf->MultiCell(50,$h,'Minor','','C');// $pdf->Ln();
					$pdf->MultiCell(50,$h-1,'','','C');
					$pdf->MultiCell(50,$h-3,'....................................','','C'); 
					$pdf->MultiCell(50,$h,'Withdrawal Date','','C'); //$pdf->Ln();
					//$pdf->Write($h,G_department($d));
                    
                    // MEANING: profile and semester registered then proceed result display
                    //if (( 0 != $profile ) && ( 0 != $semester_flag )) {
                        //AND sr.stdcourse_id=cr.stdcourse_id 
                        //echo '<table border="0" cellpadding="0" cellspacing="0" class="TABLE">';				
                        //foreach ($reg_sems as $index=>$row) {
                            
                    //echo '<td valign="top">';
                    
					$result_status = $row['season'] != 'NORMAL' ? ' ( '.$row['season'].' ) ' : '';
					//echo $row['season'];
 /* @@@@@@@@@@@@                 echo '<table border="0" cellpadding="0" cellspacing="0" class="btable"><tr><td width="50%" valign="top">';
					    echo '<table border="0" cellpadding="0" cellspacing="0" class="btable">';
                            
                            //77777777777777777777777777777 FIRST SEMESTER BEGINS
                        echo '<tr ><th colspan="7" class="btd" align="left">&nbsp; FIRST SEMESTER ', $row['rslevelid'],'/',$c_duration, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; YEAR ', $row['ysession'],'/',$row['ysession']+1, $result_status, '</th></tr>
                                <tr><td width="60" align="center" class="btd">DETP</td><td width="60" align="center" class="btd">Course<br>Number</td><td width="350" align="center" class="btd">Course Title</td><td width="60" align="center" class="btd">Sem<br>Hours</td><td width="60" align="center" class="btd">1ST</td><td width="60" align="center" class="btd">2ND</td><td width="60" align="center" class="btd">Quality <br>Points</td></tr>'; */ //@@@@@@@@@@@
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
						
						
						//$pdf->SetXY(151,63);
						//$pdf->Cell(7,$h,'1ST','LRBT','','C');$pdf->Cell(7,$h,'2ND','LRBT','','C');
						//$pdf->Cell(10,$h,'Points','LRB','','C');
						//$pdf->Cell(100,$h,$pdf->Write('',"This is \n the \r pro"),'LRTB',0,'L'); $pdf->Ln();
						//$pdf->Write(2,"This is \n the \r pro");
                        /*	$sql_fc_1 = "Select ac.*, cr.* From all_courses AS ac, course_reg AS cr Where 
                                    ac.thecourse_id=cr.thecourse_id 
                                    AND ac.level_id=cr.clevel_id 
                                    AND ac.course_custom5=cr.cyearsession 
                                    AND ac.level_id='".$row['rslevelid']."' 
                                    AND ac.course_custom5='".$row['ysession']."' 
                                    AND ac.course_semester='First Semester' 
                                    AND cr.std_id='".$row['std_id']."'";
                            //echo $sql_fc_1;	
                            //echo $index, '--',$row['ysession'],'<br>';
                            $q_fc_1 = mysqli_query($GLOBALS['connect'], $sql_fc_1);
                            $i1 = mysqli_num_rows($q_fc_1);*/
							
				$h = 3.8;
				
				
                            if ( 0 != $i1 ) {
                                
                                unset ($tot_cu_1);
                                while ( $code = mysqli_fetch_assoc( $q_fc_1 ) ) {
                                    $mygrade = get_gradeTrans($row['ysession'], $row['rslevelid'], $code['thecourse_id'], 'First Semester', $row['std_id'], $row['season']);
									$myctitle = get_c_title($code['thecourse_id'], $result_status, $row['ysession']);
                                    $mypoint = get_point( $mygrade, $code['c_unit'] );
             /*@@@@@@@@@@@@@@                       echo '<tr><td align="center" class="btd">',
                                        substr($code['stdcourse_custom2'],0,3),'</td><td align="center" class="btd">',
                                        substr($code['stdcourse_custom2'],-4),'</td><td class="btd">&nbsp;',
                                        ucwords($myctitle),'</td><td align="center" class="btd">',
                                        $code['c_unit'],'</td><td align="center" class="btd">&nbsp;',
                                        $mygrade,'</td><td align="center" class="btd">
                                        &nbsp;</td><td align="center" class="btd">&nbsp;',
                                        round($mypoint, 2),'</td></tr>';
                 */ //@@@@@@@@@@@@@@@@@                       
										if ($mygrade != '') {
											$tot_cu_1 += $code['c_unit'];
											$tot_pt_1 += $mypoint;
										}
										//$i1 = strlen($myctitle) > 35 ? $i1 + 2 : $i1 ;
										
						$pdf->Cell($tdc['1'],$h ,substr($code['stdcourse_custom2'],0,3),'LRBT','','C');
						$pdf->Cell($tdc['2'],$h , substr($code['stdcourse_custom2'],-4),'LRBT','','C');
						$pdf->Cell($tdc['3'],$h , substr(ucwords($myctitle),0,41),'LRBT','','L');
						$pdf->Cell($tdc['4'],$h ,$code['c_unit'],'LRBT','','C');
						$pdf->Cell($tdc['5'],$h , $mygrade,'LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  , round($mypoint, 2),'LRBT','','C'); $pdf->Ln();
						
                                } unset($mygrade); // while loop finishes
                                if ( $i1 != 0 ) {
        /* @@@@@@@@@@                        echo '<tr><td colspan="3" align="center" class="btable">&nbsp;</td>
                                    <td align="center" class="btable">',$tot_cu_1,'</td>
                                    <td align="center" class="btable">',round(@($tot_pt_1 / $tot_cu_1),2),'</td>
                                    <td align="center" class="btable">&nbsp;</td>
                                    <td align="center" class="btable">',round($tot_pt_1, 2),'</td></tr>
                                    <tr><td colspan="3" class="btd">&nbsp;</td>
                                        <td class="btd">&nbsp;</td>
                                        <td class="btd">&nbsp;</td>
                                        <td class="btd">&nbsp;</td>
                                        <td class="btd">&nbsp;</td></tr>'; */ //@@@@@@@@@@@@@
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'','BT','','C');
						$pdf->Cell($tdc['4'],$h ,$tot_cu_1,'LRBT','','C');
						$pdf->Cell($tdc['5'],$h,round(@($tot_pt_1 / $tot_cu_1),2),'LRBT','','C'); 
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
       /* @@@@@@@@@@@@@                                 echo '<tr><td colspan="3" class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td></tr>'; */ //@@@@@@@@@@@@@@@@
						
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
                            
                            /*$sql_fc_2 = "Select ac.*, cr.* From all_courses AS ac, course_reg AS cr Where 
                                    ac.thecourse_id=cr.thecourse_id 
                                    AND ac.level_id=cr.clevel_id 
                                    AND ac.course_custom5=cr.cyearsession 
                                    AND ac.level_id='".$row['rslevelid']."' 
                                    AND ac.course_custom5='".$row['ysession']."' 
                                    AND ac.course_semester='Second Semester' 
                                    AND cr.std_id='".$row['std_id']."'";
                            //echo $sql_fc_1;	
                            //echo $index, '--',$row['ysession'],'<br>';
                            $q_fc_2 = mysqli_query($GLOBALS['connect'], $sql_fc_2);
                            $i2 = mysqli_num_rows($q_fc_2);*/
 /* @@@@@@@@@@@@@@@                 echo '</table></td><td width="50%" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="btable">'; 
                           // if ($i2 != 0) {
                            echo '<tr><th colspan="7" class="btd" align="left">SECOND SEMESTER ', $row['rslevelid'],'/',$c_duration, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; YEAR ', $row['ysession'],'/',$row['ysession']+1, $result_status, '</th></tr>
							 <tr><td width="60" align="center" class="btd">DETP</td><td width="60" align="center" class="btd">Course<br>Number</td><td width="350" align="center" class="btd">Course Title</td><td width="60" align="center" class="btd">Sem<br>Hours</td><td width="60" align="center" class="btd">1ST</td><td width="60" align="center" class="btd">2ND</td><td width="60" align="center" class="btd">Quality <br>Points</td></tr>';
	*/ //@@@@@@@@@@@@@@@@@@
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
                                    $mygrade = get_gradeTrans($row['ysession'], $row['rslevelid'], $code['thecourse_id'], 'Second Semester', $row['std_id'], $row['season']);
									$myctitle = get_c_title($code['thecourse_id'], $result_status, $row['ysession']);
                                    $mypoint = get_point( $mygrade, $code['c_unit'] ); // $code['thecourse_id'];
      /* @@@@@@@@@                              echo '<tr><td align="center" class="btd">',
                                        substr($code['stdcourse_custom2'],0,3),'</td><td align="center" class="btd">',
                                        substr($code['stdcourse_custom2'],-4),'</td><td class="btd">&nbsp;',
                                        ucwords($myctitle),'</td><td align="center" class="btd">',
                                        $code['c_unit'],'</td><td align="center" class="btd">
                                        &nbsp;</td><td align="center" class="btd">&nbsp;',
                                        $mygrade,'</td><td align="center" class="btd">&nbsp;',
                                        round($mypoint, 2),'</td></tr>';
                          */ //@@@@@@@@@@@@@@@@@              
										if ($mygrade != '') {
											$tot_cu_2 += $code['c_unit'];
                                        	$tot_pt_2 += round($mypoint, 2);
										}
										//$i2 = strlen($myctitle) > 35 ? $i2 + 2 : $i2 ;
										
										
						$pdf->Cell($tdc['1'],$h,substr($code['stdcourse_custom2'],0,3),'LRBT','','C');
						$pdf->Cell($tdc['2'],$h , substr($code['stdcourse_custom2'],-4),'LRBT','','C');
						$pdf->Cell($tdc['3'],$h , substr(ucwords($myctitle),0,41),'LRBT','','L');
						$pdf->Cell($tdc['4'],$h ,$code['c_unit'],'LRBT','','C');
						$pdf->Cell($tdc['5'],$h , $mygrade,'LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  , round($mypoint, 2),'LRBT','','C'); $pdf->Ln();
						
                                } unset($mygrade); // while loop finishes
            /* @@@@@@@@@@@@@@@                    echo '<tr><td colspan="3" align="center" class="btable">&nbsp;</td>
                                    <td align="center" class="btable">',$tot_cu_2,'</td>
                                    <td class="btable">&nbsp;</td>
                                    <td align="center" class="btable">',round(@($tot_pt_2 / $tot_cu_2),2),'</td>
                                    <td align="center" class="btable">',round($tot_pt_2, 2),'</td></tr>
                                    <tr><td colspan="3" class="btd">&nbsp;</td>
                                        <td class="btd">&nbsp;</td>
                                        <td class="btd">&nbsp;</td>
                                        <td class="btd">&nbsp;</td>
                                        <td class="btd">&nbsp;</td></tr>';
					*/ //@@@@@@@@@@@@@@@@@@@					
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'','BT','','C');
						$pdf->Cell($tdc['4'],$h ,$tot_cu_2,'LRBT','','C');
						$pdf->Cell($tdc['5'],$h,round(@($tot_pt_2 / $tot_cu_2),2),'LRBT','','C'); 
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
                        /* @@@@@@@@@                echo '<tr><td colspan="3" class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td></tr>';
												*/ //@@@@@@@@@@@@@@@@@@@@@@
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'','BT','','C');
						$pdf->Cell($tdc['4'],$h ,'','LRBT','','C');
						$pdf->Cell($tdc['5'],$h,'','LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  ,'','LRBT','','C'); $pdf->Ln();
						
                                    }
                                }
								
								//if ( $row['season'] == 'NORMAL') { // NOTE: VACATION - not applicable in the final calculation of students CGPA
									$g_tot_cu += $tot_cu_1 + $tot_cu_2;
									$g_tot_pt += round($tot_pt_1 + $tot_pt_2, 2);
								//}
        /* @@@@@@@@@@@@@@                    echo '<tr><td colspan="3" align="right" class="btable">TOTAL Cum. Average</td>
                                <td align="center" class="btable">',$tot_cu_1 + $tot_cu_2,'</td>
                                <td align="center" class="btable">&nbsp;</td>
                                <td align="center" class="btable">',round(@(( $tot_pt_1 + $tot_pt_2 )/( $tot_cu_1 + $tot_cu_2 )),2),'</td>
                                <td align="center" class="btable">',$tot_pt_1 + $tot_pt_2,'</td></tr>';
				*/ //@@@@@@@@@@@@@@@@				
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'TOTAL Cum. Average','BT','','R');
						$pdf->Cell($tdc['4'],$h ,$tot_cu_1 + $tot_cu_2,'LRBT','','C');
						$pdf->Cell($tdc['5'],$h,round(@(( $tot_pt_1 + $tot_pt_2 )/( $tot_cu_1 + $tot_cu_2 )),2),'LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  ,$tot_pt_1 + $tot_pt_2,'LRBT','','C'); $pdf->Ln();
						
                            }
							//if ( $i_count == $i_sem ) { // final row for class of degree generated
							$mydegree = round(@(( $g_tot_pt )/( $g_tot_cu )),2);
		/* @@@@@@@@@@@@@@@@					echo '<tr><td colspan="3" class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td>
                                                <td class="btd">&nbsp;</td></tr>';
						*/ //@@@@@@@@@@@@@@@@@						
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'','BT','','C');
						$pdf->Cell($tdc['4'],$h ,'','LRBT','','C');
						$pdf->Cell($tdc['5'],$h,'','LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h  ,'','LRBT','','C'); $pdf->Ln();
		/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@				
							echo '<tr><td colspan="3" align="right" class="btable">TOTAL </td>
                                <td align="center" class="btable">',$g_tot_cu,'</td>
                                <td align="center" class="btable">&nbsp;</td>
                                <td align="center" class="btable">',$mydegree,'</td>
                                <td align="center" class="btable">',round($g_tot_pt, 2),'</td></tr>';
							//}
                            echo '</table>';
					*/ //@@@@@@@@@@@@@@@@@@@@@@		
						$pdf->Cell($tdc['1'],$h,'','LBT','','C');
						$pdf->Cell($tdc['2'],$h,'','BT','','C');
						$pdf->Cell($tdc['3'],$h ,'TOTAL','BT','','R');
						$pdf->Cell($tdc['4'],$h ,$g_tot_cu,'LRBT','','C');
						$pdf->Cell($tdc['5'],$h,$mydegree,'LRBT','','C'); 
						$pdf->Cell($tdc['6'],$h , round($g_tot_pt, 2),'LRBT','','C');// $pdf->Ln();
						
                            unset ($tot_cu_1, $tot_cu_2, $tot_pt_1, $tot_pt_2, $i2);
                        
						 
   /*@@@@@@                 echo '</td></tr></table>'; */ //@@@@@@@@@@@@@@@@@@@@@@
                            //',get_gpa($row['ysession'], $stdid),'
                        //} 
                        //echo '</table>';
       /* @@@@@@@@@@@@@@@             ?>
                    <br /><br />
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr><td style="width:5">&nbsp;</td>
                                <td valign="top">Remark<br />
                                <div style="border:#06C dotted 1px; padding:1px; padding-left:10px; padding-right:10px; margin-right:10px; font-size:8px;">
                                <table border="0" cellpadding="0" cellspacing="3">
                                        <tr><th colspan="4">CLASSIFICATION OF DEGREE</th></tr>
                                        <tr><td>(i)</td><td>4.50 - 5.00</td><td align="center" width="30">-</td><td align="left">First Class</td></tr>
                                        <tr><td>(ii)</td><td>3.50 - 4.49</td><td align="center" width="30">-</td><td align="left">Second Class Upper</td></tr>
                                        <tr><td>(iii)</td><td>2.40 - 3.49</td><td align="center" width="30">-</td><td align="left">Second Class Lower</td></tr>
                                        <tr><td>(iv)</td><td>1.50 - 2.39</td><td align="center" width="30">-</td><td align="left">Third Class</td></tr>
                                        <tr><td>(v)</td><td>1.00 - 1.49</td><td align="center" width="30">-</td><td align="left">Pass</td></tr>
                                        <tr><td>(vi)</td><td>0.00 - 0.99</td><td align="center" width="30">-</td><td align="left">Fail</td></tr>
                                	</table>
                                </div>
                                
                                </td>
                                <td valign="top"><br />
                                <div style="border:#06C dotted 1px; padding:0px; padding-left:10px; padding-right:10px; margin-right:20px; font-size:8px;">
                                
                                <table border="0" cellpadding="0" cellspacing="3">
                                        <tr><th colspan="4">GRADING SYSTEM</th></tr>
                                        <tr><td>A</td><td align="center" width="30">-</td><td align="left" width="70">Excellent</td><td align="left">5.00</td></tr>
                                        <tr><td>B</td><td align="center" width="30">-</td><td align="left">Very Good</td><td align="left">4.00</td></tr>
                                        <tr><td>C</td><td align="center" width="30">-</td><td align="left">Good</td><td align="left">3.00</td></tr>
                                        <tr><td>D</td><td align="center" width="30">-</td><td align="left">Average</td><td align="left">2.00</td></tr>
                                        <tr><td>E</td><td align="center" width="30">-</td><td align="left">Pass</td><td align="left">1.00</td></tr>
                                        <tr><td>F</td><td align="center" width="30">-</td><td align="left">Fail</td><td align="left">0</td></tr>
                                	</table>
                                 </div></td>
                                <td valign="top" align="center"><em>This is a true copy of the record on File in this office</em><br /><br /><img name="signature" src="images/registrars_signature.jpg" width="150" height="40" alt="signature" />
                                <div class="sph center" style="text-align:center; font-size:15px; font-weight:700; padding-top:0px; margin-top:0px">.....................................................................<br>Registrar</div></td>
                                <td style="width:25">&nbsp;</td>
                                </tr></table><br />
                            <?php 
				*/		//	@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
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
					
   /* @@@@@@@@@@@@@@                 ?>
                    
                    </td></tr>
                    </table></div><br /><br /><br /><br /><br /><?php  */ // @@@@@@@@@@@@@@@@@@@@@@@@@
	//}// END TEST FOR FIRST AND SECOND SEMESTES
	
	}
	
}
/* @@@@@@@@@@@
?>
</div>
<?php  */ /* @@@@@@@@@@@@@@@@@@@@@
<br /><br />
<div>
	<a href="mypdf.pdf"><img src="images/pdf.gif" alt="download" /> Download pdf</a> | <a href="viewpdf.pdf"><img src="images/mail.png" alt="view transcript" />View pdf</a>
</div>
<br /><br /><br />
</div>
*/ /* ?>


<?php 
*/

#signature placeholder

//echo '<div class="sph center" style="text-align:center; font-size:15px; font-weight:700;">  .......................................................................<br>Registrar</div>';


unset ($g_tot_pt, $g_tot_cu, $i_sem);
$set = array();
unset($set,$course_duration, $cpga, $remark, $ignore);
mysqli_close($connect);
//ob_end_flush();



$myfile = "downloads/". $stdid . ".pdf";

$pdf->Output($myfile ,"I");
/* @@@@@@@@@@@@@@@@@@@@@@@@@@
?>
<br />

<form action="mail_transcript.php" method="post" enctype="multipart/form-data" name="frmMain">
	<table width="400" border="0" cellpadding="0" cellspacing="5">
	<tr><td colspan="2" align="center"><h3><img src="images/pdf.gif" /> PDF Created Click <a href="<?php echo $myfile ;?>" target="_blank">here</a> to Download</h3><br /><br /></td></tr>
    <tr>
	<td><img src="images/mail.png" /> Email To</td>
	<td><input name="txtTo" type="text" size="40" >
    <input name="mypath" type="hidden" value="downloads" >
    <input name="mystdid" type="hidden" value="<?php echo $stdid; ?>" >
    <input name="txtSubject" type="hidden" value="Students transcript from UNICAL">
    <input name="txtDescription" type="hidden" value="
    Contact us for verification , info@advert-space.com, 07036689116">
    <input name="txtFormName" type="hidden"  value="REGISTRAR's Office">
    <input name="txtFormEmail"  type="hidden" value="info@unical.org">
    </td> 
	</tr> 
	<?php  @@@@@@@@@@@@@@@@@@@ */ /*<tr>
	<td>Subject</td>
	<td></td>
	</tr>
	<tr>
	<td>Description</td>
	<td></td>
	</tr>
	<tr>
	<td>Form Name</td>
	<td></td>
	</tr>
	<tr>
	<tr>
	<td>Form Email</td>
	<td></td>
	</tr>
	<tr>
	  <td>Attachment</td>
	  <td>
	  <input name="fileAttach[]" type="file"><br>
	  <input name="fileAttach[]" type="file"><br>
	  <input name="fileAttach[]" type="file"><br>
	  <input name="fileAttach[]" type="file"><br>
	  <input name="fileAttach[]" type="file"><br>
	  <input name="fileAttach[]" type="file"><br></td>
	</tr>*/ /*?>
	<tr>
	<td>&nbsp;</td>
	<td><input type="submit" name="Submit" value="Mail Students Transcript"></td>
	</tr>
	</table>
</form><BR /><BR />

</body>
</html>

<?php
*/ //@@@@@@@@@@@@@@@@@@@@@@@@@@
function get_gradeTrans($session, $l, $c_id, $semester, $std_id, $season)
{
	$sql = "Select std_grade From students_results 
			Where std_id='$std_id' 
			AND std_mark_custom2='$session' 
			AND std_mark_custom1='$semester'
			AND period='$season'
			AND stdcourse_id='$c_id'";
			//AND level_id='$l' 
			
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
		$p = 0;//5 * intval($course_unit)
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
			$sql_fc = "Select cr.* From course_reg AS cr Where 
							cr.clevel_id='".$row['rslevelid']."'
							AND cr.cyearsession='".$row['ysession']."' 
							AND cr.course_season='".$row['season']."'
							AND cr.csemester IN ('First Semester','Second Semester') 
							AND cr.std_id='".$row['std_id']."'";
							
							// AND cr.course_season='NORMAL'
							
							//".$row['season'].
							/* "Select ac.*, cr.* From all_courses AS ac, course_reg AS cr Where 
								ac.thecourse_id=cr.thecourse_id 
								AND ac.level_id=cr.clevel_id 
								AND ac.course_custom5=cr.cyearsession 
								AND ac.level_id='".$row['rslevelid']."' 
								AND ac.course_custom5='".$row['ysession']."'
								AND cr.course_season='NORMAL'
								AND ac.course_semester IN ('First Semester', 'Second Semester')  
								AND cr.std_id='".$row['std_id']."'";
						*/
						// NOTE: ".$row['season']." this is not applicable for calculating CGPA - VACATION
						
						$q_fc = mysqli_query($GLOBALS['connect'], $sql_fc);
						$i = mysqli_num_rows($q_fc);
						
						//echo $sql_fc;
						if ( 0 != $i ) {
									
							//unset ($tot_cu);
							while ( $code = mysqli_fetch_assoc( $q_fc ) )
							{
								$mygrade = '';
								$mygrade = get_gradeTrans($row['ysession'], $row['rslevelid'], $code['thecourse_id'], $code['csemester'], $row['std_id'], $row['season']);
								//if (($mygrade != '') && ($mygrade != ' ')){ $ii += 1 ;}
								if ($mygrade != '') {
									$mypoint = get_point( $mygrade, $code['c_unit'] );
									$tot_cu += $code['c_unit'];
									$tot_pt += $mypoint;
								}
									//echo '<tr><td align="center" class="btd">',
									//substr($code['course_code'],0,3),'</td><td align="center" class="btd">',
									//substr($code['course_code'],-4),'</td><td class="btd">&nbsp;',
									//ucwords($code['course_title']),'</td><td align="center" class="btd">',
									//$code['course_unit'],'</td><td align="center" class="btd">&nbsp;',
									//$mygrade,'</td><td align="center" class="btd">
									//&nbsp;</td><td align="center" class="btd">&nbsp;',
									//$mypoint,'</td></tr>';
									//echo $code['course_unit'], ', ';
									
							} unset($mygrade); // while loop finishes
						} 
		}
	}
	
	//echo $tot_cu, ', ';
	//$mydegree = ($tot_pt != '') ? round( ($tot_pt / $tot_cu) , 2 ) : 0;
	$mydegree = round( ($tot_pt / $tot_cu) , 2 );
	
	if (( $mydegree >= 4.5 ) && ( $mydegree <= 5.0 )) {
		$deg = 'FIRST CLASS';
	} elseif (( $mydegree >= 3.50 ) && ( $mydegree <= 4.49 )) {
		$deg = 'SECOND CLASS UPPER';
	} elseif (( $mydegree >= 2.40 ) && ( $mydegree <= 3.49 )) {
		$deg = 'SECOND CLASS LOWER';
	} elseif (( $mydegree >= 1.5 ) && ( $mydegree <= 2.39 )) {
		$deg = 'THIRD CLASS';
	} elseif (( $mydegree >= 1.00 ) && ( $mydegree <= 1.49 )) {
		$deg = 'PASS';
	} elseif (( $mydegree >= 0.00 ) && ( $mydegree <= 0.99 )) {
		$deg = 'FAIL';
	}
	
	return strtoupper($deg);
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