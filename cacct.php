<?php

	require_once dirname(__FILE__).'/config.php';
	global $connect;
		
	if( isset($_POST['type']) ):
	
		switch( $_POST['type'] ) {
			case '1':
			case 1:

				$values = '';
				foreach( $_POST['department'] as $os ) {
					
					list($dept, $fac, $prog) = explode(',', $os);
					
					$values .= '( '.$prog.', '.$fac.', '.$dept.', "'.$_POST['username'].'", "'.$_POST['password'].'", "","", "'.$_POST['fullname'].'", "",1,3,0),';
				
				}
				
				$values = substr($values, 0, -1);
				$a = mysqli_query( $connect, 'INSERT INTO `exam_officers` (`programme_id`, `faculty_id`, `department_id`, `eo_username`, `eo_password`, `eo_title`, `eo_surname`, `eo_firstname`, `eo_othernames`, `eo_status`, `mstatus`, `eo_course`) VALUES '. $values );
					
				if( mysqli_affected_rows($connect)>0 ) {
					
					mysqli_close( $connect );
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: createaccount.php?info=1');
				
				} else {
					
					mysqli_close( $connect );
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: createaccount.php?info=0');
				
				}

			break;
			case '2':
			case 2:
			
				$chk = mysqli_query( $connect, 'SELECT 1 FROM `exam_officers` WHERE eo_username = "'.$_POST['username'].'" LIMIT 1' );
				if( 1==mysqli_num_rows($chk) ) {

					  mysqli_close( $connect );
					  header('HTTP/1.1 301 Moved Permanently');
					  header('Location: createaccount.php?info=0');
					  					
				} else {

				  $a = mysqli_query( $connect, 'INSERT INTO `exam_officers` (`programme_id`, `faculty_id`, `department_id`, `eo_username`, `eo_password`, `eo_title`, `eo_surname`, `eo_firstname`, `eo_othernames`, `eo_status`, `mstatus`, `eo_course`) VALUES ( 2, 0, 0, "'.$_POST['username'].'", "'.$_POST['password'].'", "","", "'.$_POST['fullname'].'", "",1,7,0 )' );			
				
				  if( mysqli_affected_rows($connect)>0 ) {
					  
					  mysqli_close( $connect );
					  header('HTTP/1.1 301 Moved Permanently');
					  header('Location: createaccount.php?info=1');
				  
				  } else {
					  
					  mysqli_close( $connect );
					  header('HTTP/1.1 301 Moved Permanently');
					  header('Location: createaccount.php?info=0');
				  
				  }
								
				}
					
			break;
		}	
		
		exit('DONE');
	
	endif;
	
?>