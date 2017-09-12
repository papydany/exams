<?php  require_once 'config.php';
if(isset($_GET['assign_course_id']))
{
	$id = $_GET['assign_course_id'];
	$query ="DELETE From assign_courses where id ='$id'";
		$run = mysqli_query($GLOBALS['connect'],$query) or die(mysqli_error($GLOBALS['connect']));

		if($run)
		{
			header('HTTP/1.1 301 Moved Permanently');
		    header('Location: viewassigncourses.php?i=1');
		}
}else{echo "ff";}



?>