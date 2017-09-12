<?php

	require '../config.php';
	session_start();
	
	if( isset($_GET['comm']) ):
	
		switch( $_GET['comm'] ) {
			case 'Pupdate':
				
				list( $dept, $fac ) = explode(',', $_POST['department']);
				
				$u = mysqli_query( $GLOBALS['connect'], 'UPDATE students_profile SET surname = "'.$_POST['sn'][0].'", firstname = "'.$_POST['fn'][0].'", othernames = "'.$_POST['on'][0].'", matric_no = "'.$_POST['mn'][0].'", stdfaculty_id = '.$fac.', stddepartment_id = '.$dept.', stdcourse = '.$_POST['course'].', stddegree_id = '.$_POST['degree'].' WHERE std_id = '.$_POST['sp'][0].' LIMIT 1');
				if( 1==mysqli_affected_rows( $GLOBALS['connect'] ) ) {
					
					mysqli_query( $GLOBALS['connect'], 'UPDATE login SET log_surname = "'.$_POST['sn'][0].'", log_othernames = "'.trim($_POST['fn'][0].' '.$_POST['on'][0]).'", log_username = "'.$_POST['mn'][0].'" && log_faculty = '.$fac.' && log_department = '.$dept.' && log_study = '.$_POST['course'].' && log_degree = '.$_POST['degree'].' WHERE log_id = '.$_POST['lg'][0].' LIMIT 1');
					
					mysqli_query( $GLOBALS['connect'], 'UPDATE students_reg SET matric_no = "'.$_POST['mn'][0].'", faculty_id = '.$fac.', department_id = '.$dept.' WHERE std_id = '.$_POST['sp'][0].'');		
					
					mysqli_query( $GLOBALS['connect'], 'UPDATE students_results SET matric_no = "'.$_POST['mn'][0].'" WHERE std_id = '.$_POST['sp'][0].'');
					
					$_SESSION['info'] = 11;
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: edit_std_rec.php?');
					exit('SUCCESS');
					
				}
				
					$_SESSION['info'] = 12;
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: edit_std_rec.php');
					exit('SUCCESS');
									
			break;
		}
	
	endif;
	
	function get_querystring() {
		$querystring = '';
		foreach( $_GET as $k=>$v )
			$querystring .= !empty($querystring) ? '&'.$k.'='.urlencode($v) : $k.'='.urlencode($v);	
			
		return $querystring;
	}

?>