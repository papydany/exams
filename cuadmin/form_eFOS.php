<?php

	require_once '../config.php';
	
	if( isset($_POST['save'], $_POST['do_id']) ) {
		
		$affected_rows = 0;
		global $connect;
		
		foreach( $_POST['do_id'] as $k=>$id ) {
			
			mysqli_query($connect, 'UPDATE dept_options SET programme_option = "'.$_POST['fosN'][$k].'", duration = "'.$_POST['duration'][$k].'" WHERE do_id = '.$id.'');
			if( mysqli_affected_rows($connect)>0 )
				$affected_rows++;
				
		}
		
			header('Location: eFOS.php?'.$_GET['q']);	
			header('HTTP/1.1 301 Moved Permanently');
			exit('Freak Show');
		
	}
		
?>