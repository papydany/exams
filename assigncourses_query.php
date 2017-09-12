<?php require_once dirname(__FILE__).'/config.php';

$mydate = date("Y-m-d");
$level = $_POST['level'];
$fac = $_POST['fac'];
$dept = $_POST['dept'];
$fos = $_POST['fos'];
$year = $_POST['year'];
$programme_id = $_POST['programme_id'];
$monitor = 0;

	if(isset($_POST['submit']) && !empty($_POST['lecturer_id'])) {

	foreach( $_POST['lecturer_id'] as $k=>$cc ) {
		if( !empty($cc) ) {
			$clean_list[] = array('lecturer_id'=>$cc,'thecourse_id'=>($_POST['thecourse_id'][$k]));
}
	}
//var_dump($clean_list);
//die();
	if(count($clean_list) != 0)
{
	$monitor = 0;
	foreach( $clean_list as $key => $value) {	

	  $lecturer_id = $clean_list[$key]['lecturer_id'];
      $thecourse_id = $clean_list[$key]['thecourse_id'];
     
			
	
		
		$query_builder2 .= '("'.$fac.'","'.$dept.'", "'.$thecourse_id.'","'.$lecturer_id.'", "'.$programme_id.'", "'.$level.'", "'.$year.'", "'.$fos.'", "'.$mydate.'"),';
		$monitor++;
		
	}

	$query_builder2 = substr($query_builder2,0,-1);



	$query2ab = mysqli_query($GLOBALS['connect'],'INSERT INTO assign_courses(fac_id,dept_id,thecourse_id,lecturer_id,programme_id,level,year,fos,`date`) VALUES'.$query_builder2) or die(mysqli_error($GLOBALS['connect']));
	
	}

	if( mysqli_affected_rows($GLOBALS['connect'])>0 ) {

		mysqli_close($GLOBALS['connect']);
		header('HTTP/1.1 301 Moved Permanently');
	     header('Location: assigncourses.php?info=1');
		exit;
	
	}


}
else{
		mysqli_close($GLOBALS['connect']);
		header('HTTP/1.1 301 Moved Permanently');
	     header('Location: assigncourses.php?info=0');
		exit;
	
	
}
?>
