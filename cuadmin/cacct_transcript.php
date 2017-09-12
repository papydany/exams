<?php

	require_once '../config.php';
	global $connect;	
	
	if( isset($_POST['type']) ):
	
		switch( $_POST['type'] ) {
			case 4:
			
				$values = '';
				if( isset($_POST['fos']) && !empty($_POST['fos']) ) {

					$dept = $_POST['department'];
					$prog = $_POST['programme'];
					$fac = $_POST['fac'];
					
					$trans_right = '1';
					
					foreach( $_POST['fos'] as $fos ) {
						
						//list($dept, $fac, $prog) = explode(',', $os);
						$values .= '( '.$prog.', '.$fac.', '.$dept.', "'.$fos.'", "'.$_POST['username'].'", "'.$_POST['password'].'", "","", "'.$_POST['fullname'].'", "",1,3,0,'.$trans_right.' ),';					
					
					}
					
					
					$values = substr($values, 0, -1);
					
					$chk = mysqli_query($connect, 'SELECT 1 FROM exam_officers WHERE eo_username = "'.$_POST['username'].'" LIMIT 1');
					if( 0==mysqli_num_rows($chk) ) {
						mysqli_free_result($chk);
						$a = mysqli_query( $connect, 'INSERT INTO `exam_officers` (`programme_id`, `faculty_id`, `department_id`, `fos`, `eo_username`, `eo_password`, `eo_title`, `eo_surname`, `eo_firstname`, `eo_othernames`, `eo_status`, `mstatus`, `eo_course`, `trans_right`) VALUES '. $values );
							
						if( mysqli_affected_rows($connect)>0 ) {
							
							////mysqli_close( $connect );
							header('HTTP/1.1 301 Moved Permanently');
							header('Location: createaccount_transcript.php?info=1');
							exit;
						}
					
					}
				
				}
					
					////mysqli_close( $connect );
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: createaccount_transcript.php?info=0');
								
			break;
		}	
		
		exit('DONE');
	
	endif;
	
?>