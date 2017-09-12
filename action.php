<?php

	require './config.php';
	session_start();
	
	if( isset($_GET['comm']) ):
	
		switch( $_GET['comm'] ) {
			case 'delete':
				
				if( !empty($_GET['sid']) ):
				
					$del = mysqli_query( $GLOBALS['connect'],  'DELETE FROM students_profile WHERE std_id = '.$_GET['sid'].' LIMIT 1' );
					if( 1==mysqli_affected_rows( $GLOBALS['connect'] ) ) {
						mysqli_query( $GLOBALS['connect'],  'DELETE FROM login WHERE log_id = '.$_GET['lid'].' LIMIT 1' );
						mysqli_query( $GLOBALS['connect'],  'DELETE FROM studentstatus WHERE std_logid = '.$_GET['lid'].'');
						mysqli_query( $GLOBALS['connect'],  'DELETE FROM course_reg WHERE std_id = '.$_GET['sid'].'' );
						mysqli_query( $GLOBALS['connect'],  'DELETE FROM students_reg WHERE std_id = '.$_GET['sid'].'' );
						mysqli_query( $GLOBALS['connect'],  'DELETE FROM students_results WHERE std_id = '.$_GET['sid'].'' );
						mysqli_query( $GLOBALS['connect'],  'DELETE FROM registered_semester WHERE std_id = '.$_GET['sid'].'');
						
						mysqli_query( $GLOBALS['connect'],  'OPTIMIZE TABLE students_profile, login, course_reg, students_reg, students_results, registered_semester, studentstatus' );	
											
						unset( $_GET['sid'], $_GET['lid'], $_GET['comm'] );
						$querystring = get_querystring();
						$_SESSION['info'] = 1;
						header('HTTP/1.1 301 Moved Permanently');
						header('Location: viewstudents_x.php?'.$querystring);
						exit('SUCCESS');
					}

				endif;
				
					unset( $_GET['sid'], $_GET['lid'], $_GET['comm'] );
					$querystring = get_querystring();				
					$_SESSION['info'] = 0;
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: viewstudents_x.php?'.$querystring);
					exit('FAIL');
				
			break;
			case 'update':
				
				unset( $_GET['sid'] );
				$querystring = get_querystring();				
				$sqlup = 'UPDATE students_profile SET surname = "'.$_POST['sn'][0].'", firstname = "'.$_POST['fn'][0].'", othernames = "'.$_POST['on'][0].'", matric_no = "'.$_POST['mn'][0].'" 
					, gender = "'.$_POST['gender'][0].'"
					, place_of_birth = "'.$_POST['pob'][0].'"
					, birthdate = "'.$_POST['dob'][0].'"
					, marital_status = "'.$_POST['ms'][0].'"
					, contact_address = "'.$_POST['pa'][0].'"
					, state_of_origin = "'.$_POST['soo'][0].'"
					, local_gov = "'.$_POST['lga'][0].'"
					, parents_name = "'.$_POST['pn'][0].'"
					, last_institution = "'.$_POST['lia'][0].'"
					, date_of_graduation = "'.$_POST['dog'][0].'"
					, major = "'.$_POST['major'][0].'"
					, minor = "'.$_POST['minor'][0].'"
					, school_cert = "'.$_POST['sc'][0].'"
					, school_cert_yr = "'.$_POST['cd'][0].'"
					WHERE std_id = '.$_POST['sp'][0];
			 
			 
		
			
				$u = mysqli_query( $GLOBALS['connect'], $sqlup); // students profile
				$u = mysqli_affected_rows( $GLOBALS['connect'] );	//echo mysqli_affected_rows( $GLOBALS['connect'] ).'---'.$_POST['sp'][0]; exit;
					
					//echo $sqlup; 
				if (( 1==$u )) { // test either of the update affected rows
					
					
					mysqli_query( $GLOBALS['connect'], 'UPDATE login SET log_surname = "'.$_POST['sn'][0].'", log_othernames = "'.trim($_POST['fn'][0].' '.$_POST['on'][0]).'", log_username = "'.$_POST['mn'][0].'"  WHERE log_id = '.$_POST['lg'][0].' LIMIT 1');
					
					$_SESSION['info'] = 11;
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: viewstudents_x.php?'.$querystring);
					exit('SUCCESS');		
					
				}
					$_SESSION['info'] = 12;
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: viewstudents_x.php?'.$querystring);
					exit('SUCCESS');			
				
			break;
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