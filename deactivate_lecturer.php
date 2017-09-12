<?php  require_once 'config.php';
if(isset($_GET['id']))
{
	$id = $_GET['id'];
	$query ="UPDATE exam_officers SET eo_status =0 where examofficer_id ='$id'";
		$run = mysqli_query($GLOBALS['connect'],$query) or die(mysqli_error($GLOBALS['connect']));

		if($run)
		{
			header('HTTP/1.1 301 Moved Permanently');
		    header('Location: viewlecturers.php?i=1');
		}
}else{echo "ff";}



?>