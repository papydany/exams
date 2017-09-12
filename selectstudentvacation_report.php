<?php
	session_start();
	
	ob_start("ob_gzhandler");
	include_once './config.php';
	include_once './include_report.php';
	//include_once './include_report_ext.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LONG VACATIONAL RESULTS</title>
<link type="text/css" href="report.css" rel="stylesheet" />

<base target="_blank">
</head>
<body>

<?php
require("inc/headerlogin.php");
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
	$terminal =$c_duration + 2;
	//var_dump($special);
	$fetch_level = $l;
	$fetch_sess = $special ? $s - ($l-$status) : $s;
//------------------ Facuty of Agric and the like module setup
$_SESSION['mydept'] = $d;
$_SESSION['myfac'] = $f;
//$_SESSION['agric_setup'] = (($d == 25) || ($f == 6))? true: false;
$_SESSION['agric_setup'] = ($f == 6)? true: false;

$year_of_study = $level_reps[$l].'/'.$c_duration;
if ( $l < $c_duration ) {
		echo "Page not available for level ".$level_reps[$l]." Please use the Mid-Year Vacation List Page/Option";
		exit;
	}

	$xsession = $s.'/'.($s+1);

	$academic_semester ="FIRST & SECOND";
	$result_first_semester ="FIRST SEMESTER RESULTS";
	$result_second_semester ="SECOND SEMESTER RESULTS";


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
			<p>SEMESTER:', $academic_semester,'</p>
		</div>
	</div>
	<div style="color:#222;text-align:center; padding:3px 0; background:#f7f7fe; font-weight:700; font-size:14px;">
		<p>EXAMINATION REPORTING SHEET</p>
		<p>',$set['dr'],'</p>
	</div>
	</div>';

	$std_off_list=fetch_student_mat_vacation( $d, $p, $l, $f, $s, $fos );

	?>
	<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
	<form class="form-group" method="post" action="selectstudentvacation_display.php" target="_blank">
	<input type="hidden" name="s_session" value="<?php echo $s; ?>">
	<input type="hidden" name="faculty_id" value="<?php echo $f; ?>">
	<input type="hidden" name="department_id" value="<?php echo $d; ?>">
	<input type="hidden" name="course" value="<?php echo $fos; ?>">
	<input type="hidden" name="s_level" value="<?php echo $l; ?>">
	<input type="hidden" name="programme" value="<?php echo $p; ?>">
    <input type="hidden" name="special" value="<?php echo $special; ?>">
<table class="table table-bordered">
<tr>
<th>select</th><th>S/n</th><th>matric number</th><th>name</th>
</tr>
<?php
$cc =0;
foreach ($std_off_list as $key => $value) {

	echo'<tr>
<th><input type="checkbox" name="id[]" value="'.$value['std_id'].'"/></th><th>'.++$c.'</th><th>'.$value['matric_no'].'</th><th>'.$value['surname']." ".$value['firstname']." ".$value['othernames'].'</th>
</tr>';
}


?>
	
</table>
<input type="submit" class="btn btn-danger" value="Continue" >
</form>
</div>
</div>
<?php
$set = array();
unset($set,$course_duration, $cpga, $remark, $ignore);
mysqli_close($connect);
ob_end_flush();
?>
</body>
</html>