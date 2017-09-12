<?php
session_start();
//require( "inc/header.php" );
include_once './config.php';
require( dirname(__FILE__)."/updates.php");
include_once './include_report.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Student Results</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<base target="_blank">
</head>
<body>
<style type="text/css">
@media print {
	.www{width: 500;float: left;}
	.ww{width: 500;float: right;}
}
</style>
<?php

$_SESSION['s_session']  = $_GET['s_session'];

$_SESSION['s_level']    = $_GET['s_level'];


$xyz = explode('-', $_GET['cs_code']);

$cs_code = $xyz[0];

//echo $cs_code;
$sem = $xyz[1];
$fos_ids [] = $xyz[2];
$fos  = $xyz[2];

$_SESSION['s_semester'] = $sem;
//$_GET['s_semester'] = $sem;
$department = $_GET['department'];
$fac = $_GET['s_faculty'];
if( empty($_SESSION['s_semester']) ) {
	exit('Please Restart This Action');
}

$query = 'SELECT course_title, course_code FROM all_courses WHERE thecourse_id = '.$cs_code.' LIMIT 1';

$result = mysqli_query( $GLOBALS['connect'],  $query ) or die( mysqli_error($GLOBALS['connect']) );

while ( $row = mysqli_fetch_assoc( $result ) ) {
    $stdcourse_custom1 = $row["course_title"];
    $stdcourse_custom2 = $row["course_code"];
}



?>
<div class="row">
<div class="col-sm-10 col-sm-offset-1">

<table  class="table table-bordered">
<tr><td>
<p class="text-center" style="font-size:18px; font-weight:700;">UNIVERSITY OF CALABAR</p>
		<p class="text-center" style="font-size:16px; font-weight:700;">CALABAR</p>
	
		<div class="col-sm-6 www">
		<?php
		echo'<p>FACULTY: ',G_faculty($fac),'</p>
			<p>DEPARTMENT: ',G_department($department),'</p>
			<p>PROGRAMME: ',G_programme($fos),'</p>';
			?>
			</div>
	<div class="col-sm-6 ww">
			<p> <?php echo $_SESSION['myprogramme_id'] == 7 ? ' Contact '.substr(GetlLevel($_GET['s_level']),0,1) : ' Level '.GetlLevel($_GET['s_level']); ?> </u></p>
			<p>
	<?php
		
		echo "SESSION :";
		echo $_SESSION['myprogramme_id'] == 7 ? " ".$_GET['s_session'] : " ".$_GET['s_session'].'/'.($_GET['s_session'] + 1); ?> 
		</p>
        <p>Course Code (<?php echo $stdcourse_custom2; echo !empty($stdcourse_custom1) ? '&nbsp;&nbsp;-&nbsp;&nbsp;'.$stdcourse_custom1 : ''; ?>)</p>

		</div>

		</td></tr>
 
  
  
</table>


<?php
		echo '<form name="form1" method="post" action="result_insert_lecturer.php" autocomplete="off" onSubmit="return OnSubmitForm(this);">';		
?>
<?php
		if( $_GET['season']=='VACATION' ) {
			$season = 'VACATION';
			$s_sem = ' IN ("First Semester", "Second Semester")';
		} else {
			$season = 'NORMAL';
			$s_sem = ' IN ("First Semester", "Second Semester")';
		}
	
	



    
		$query3 = "SELECT course_reg.*, `dept_options`.`do_id`, `dept_options`.`programme_option`, students_profile.matric_no, students_profile.surname, students_profile.firstname, students_profile.othernames 
		FROM `dept_options`, course_reg LEFT JOIN students_profile ON (course_reg.std_id = students_profile.std_id) WHERE `dept_options`.`dept_id` = students_profile.`stddepartment_id` && `dept_options`.`do_id` = `students_profile`.stdcourse && `course_reg`.clevel_id = $_GET[s_level] && course_reg.thecourse_id ='".$cs_code."' AND course_reg.csemester $s_sem AND course_reg.cyearsession = '$_GET[s_session]' && course_reg.course_season = '$season' && students_profile.stdfaculty_id = '".$fac."' && students_profile.stddepartment_id = '".$department."' && `dept_options`.do_id IN (".implode(',', $fos_ids).")
		GROUP BY course_reg.std_id {$_SESSION['orderBy']}";
//echo $query3;


		$result3  = mysqli_query( $GLOBALS['connect'],  $query3 ) or die(mysqli_error($GLOBALS['connect']));
		$i = 0;
		
		
	

if( 0!=mysqli_num_rows ($result3) ) {

	
} else {
	echo '<div style="width:300px; padding:5px 40px;margin:5px 0; color:red; border:1px solid red;">No Record Found</div>';
}

?>



  <table class="table table-bordered">
    <?php



if( 0!=mysqli_num_rows ($result3) ) {
	
	$num      = mysqli_num_rows ( $result3 );
	$thisrows = $num;

	$rowbegin = /*$rowstart +*/ 1;
	$rowend   = /*$rowstart +*/ $thisrows;
	$themembers = $num > 1 ? 'Students' : 'Student';

	$list = array();
	while( $r=mysqli_fetch_array($result3) ) {
		$list[ $r['do_id'] ][] = $r;
	}
	mysqli_free_result($result3);
	
		echo <<<TH
		  <tr>
		  
		  <th width="3%"><strong>S/No</strong></th>
		    <th width="13%"><strong>Matric No</strong></th>
		  <th width="15%"><strong>Surname</strong></th>
		 
		  <th width="15%"><strong>Names</strong></th>
		
		
		  <th width="15%" ><b>Grade</b></th>
		</tr>
TH;
	foreach( $list as $k=>$v ) {
			
	
		foreach( $v as $kk ) {
			
			$i++;
			
			$std_id            = $kk["std_id"];
			$course_id         = $kk["thecourse_id"];
			$c_unit            = $kk["c_unit"];
			$stdcourse_custom3 = $kk["stdcourse_custom3"];
			$matric_no         = $kk["matric_no"];
			$surname           = stripslashes( $kk["surname"] );
			$firstname         = stripslashes( $kk["firstname"] );
			$othernames        = stripslashes( $kk["othernames"] );
		
			
			$rsquery2 = "SELECT * FROM registered_semester WHERE sem $s_sem AND ysession = '$_GET[s_session]' AND std_id = '$std_id' && season = '$season'";
			$rsquery2 = mysqli_query( $GLOBALS['connect'],  $rsquery2 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
			$numrs     = mysqli_num_rows ( $rsquery2 );
			$rowrs     = mysqli_fetch_array( $rsquery2 );
			$rslevelid = $rowrs["rslevelid"];			
			
			$query2ac = "SELECT * FROM students_results WHERE std_id = '$std_id' AND std_mark_custom1 $s_sem AND
			std_mark_custom2 = '$_GET[s_session]' AND stdcourse_id = '$course_id' && period = '$season' ";

			$query2ac = mysqli_query( $GLOBALS['connect'],  $query2ac ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
			$num2ac   = mysqli_num_rows ( $query2ac );
			$row2ac   = mysqli_fetch_array( $query2ac );
				
							
		$std_grade = $row2ac["std_grade"];
			
			
			
			if ( $std_id ) {
				
				$x = '0~~'.$_SESSION['s_semester'].'~~'.$_GET['s_level'];
				
				echo '<tr>',
				          '<td>',$i,'</td>
						  <td>',$matric_no,'</td>
						<td><b>',strtoupper( $surname ),'</b></td>',
						'<td>',strtoupper( $firstname ).' '.strtoupper( $othernames ),'</td>';
						
					
						
						if ( $num2ac > 0 ) {
								
								
							
								echo <<<TD
										<td>$std_grade
										
										</td>
TD;
					
							}else{
				echo <<<TD
				<td></td>
TD;
							}
																				
			echo '</tr>';	
			
			}
		
		}
	}














}


?>
<!-- content ends here -->
</table>
</div>
</div>
</body>
</html>



