<?php require_once dirname(__FILE__).'/config.php';

$mydate = date("Y-m-d");
$level = $_POST['level'];
$fac = $_POST['fac'];
$dept = $_POST['dept'];
$fos = $_POST['fos'];
$year = $_POST['year'];
$programme_id = $_POST['programme_id'];
$monitor = 0;
$lecturer_id = $_POST['lecturer_id'];

	if(isset($_POST['submit']) && !empty($_POST['lecturer_id'])) {

	foreach( $_POST['thecourse_id'] as $k=>$cc ) {
		if( !empty($cc) ) {
			 $thecourse_id = $cc;

		
		$query_builder2 .= '("'.$fac.'","'.$dept.'", "'.$thecourse_id.'","'.$lecturer_id.'", "'.$programme_id.'", "'.$level.'", "'.$year.'", "'.$fos.'", "'.$mydate.'"),';
		$monitor++;
		
	}
}
	$query_builder2 = substr($query_builder2,0,-1);

$ccc = 'INSERT INTO assign_courses(fac_id,dept_id,thecourse_id,lecturer_id,programme_id,level,year,fos,`date`) VALUES'.$query_builder2;
echo $ccc ;


	$query2ab = mysqli_query($GLOBALS['connect'],'INSERT INTO assign_courses(fac_id,dept_id,thecourse_id,lecturer_id,programme_id,level,year,fos,`date`) VALUES'.$query_builder2) or die(mysqli_error($GLOBALS['connect']));
	
	

	if( mysqli_affected_rows($GLOBALS['connect'])>0 ) {

		mysqli_close($GLOBALS['connect']);
		header('HTTP/1.1 301 Moved Permanently');
	     header('Location: assigncourses1.php?info=1');
		exit;
	
	}


}
else{
		mysqli_close($GLOBALS['connect']);
		header('HTTP/1.1 301 Moved Permanently');
	     header('Location: assigncourses1.php?info=0');
		exit;
	
	
}
?>
