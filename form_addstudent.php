<?php
	//require_once 'inc/header.php';
	require_once dirname(__FILE__).'/config.php';
	global $connect;
	
	list($dept, $fac) = explode(',', $_POST['department']);
	$failed_list = array();
    
	$certificate = isset( $_POST['certificate'] ) ? $_POST['certificate'] : 'addstudent.php';
    
	$course_faculty = $fac;
	$course_dept = $dept;
	$course_level = $_POST['level'];
	$course_programme = $_POST['programme'];
	$course_field = $_POST['course'];
	$degree = $_POST['degree'];
	$yearsession = $_POST['yearsession'];
	$em = array();
	$monitor = array();
	
	$one = 1;
	$zero = 0;
	$five = 5;
	$n = 'Nigeria';
	$nine = 9;
	$pass = 'pass';

	
	if( empty($_POST['mn']) || empty($_POST['sn']) ) {
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$certificate.'?i=0');
		exit('Not All Action Could Be Performed');		
	}
	
	
	$clean_list = array();
	foreach( $_POST['mn'] as $k=>$ll ) {
		
		if( isset($clean_list[$ll]) ) {
			$failed_list[] = array( 'fullname'=>$_POST['sn'][$k].' '.$_POST['on'][$k], 
												'mat_no'=>$ll );			
		} else if( !empty($ll) ) {
			$clean_list[$ll] = array('surname'=>$_POST['sn'][$k], 'othernames'=>$_POST['on'][$k], 'matric_no'=>$ll, 'email'=>'', 'log_id'=>'', 'std_id'=>'' );
		}
		
	}
	
	if( empty( $clean_list ) ) {
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$certificate.'?i=0');
		exit('Not All Action Could Be Performed');	
	}	
	
	$mat = array();
	foreach( $clean_list as $kk=>$vv )
		$mat[] = $vv['matric_no'];
	
	$checkall = mysqli_query( $connect, 'SELECT matric_no FROM students_profile WHERE matric_no IN ("'.implode('","', $mat).'")') or die( mysqli_error($connect) );
	
	if( mysqli_num_rows($checkall)>0 ) {
		while( $f = mysqli_fetch_assoc($checkall) ) {
			// adds to failed_list
			$failed_list[] = array( 'fullname'=>$clean_list[ $f['matric_no'] ]['surname'].' '.$clean_list[ $f['matric_no'] ]['othernames'], 
									'mat_no'=>$clean_list[ $f['matric_no'] ]['matric_no'] );
			// deletes it
			unset( $clean_list[ $f['matric_no'] ] );
		}
	}
	
	
	
	$login_save = mysqli_prepare($connect, "INSERT INTO login(log_surname,log_othernames,log_username,log_email,log_password,log_stdtypeid,log_programmeid,log_status,log_status2,log_status3,log_status4,log_status5,state,log_faculty,log_department,log_study,log_degree) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	foreach( $clean_list as $k=>$v ) {

			$clean_list[$k]['email'] = $_SERVER['REQUEST_TIME']+rand(9999, 99999999999).'@yahoo.com';
			$login_save->bind_param('sssssiiiiiissiiii', $clean_list[$k]['surname'], $clean_list[$k]['othernames'], $clean_list[$k]['matric_no'], $clean_list[$k]['email'], $pass, $one, $course_programme, $zero, $zero, $five, $one, $n, $nine, $course_faculty, $course_dept, $course_field, $degree);
			$login_save->execute();
			$clean_list[$k]['log_id'] = mysqli_insert_id($connect);
		
	}
	$login_save->close();
	
		
	
	
	
	
	$gender = 'Male';
	$marital = 'single';
	$home_town = 'xxx';
	$local_gov = 172;
	$state = 9;
	$contact = 'xxx';
	$mobile = 999;
	$std_custom3 = 'UME';
	$std_custom10 = 187;
	$new = 'new';
	//$std_custom6='month';
	if($course_programme==7){
		if($_POST['month'] == 1)
		{$std_custom6="April";}
		elseif($_POST['month'] == 2)
		{$std_custom6="August";}	
		
	}else{
		$std_custom6='january';
	}
	
	$student_profile_save = mysqli_prepare( $connect, "INSERT INTO students_profile (std_logid, matric_no, surname, othernames, gender, marital_status, home_town, local_gov, state_of_origin, nationality, contact_address, student_email, student_homeaddress, student_mobiletel, stdprogramme_id, stdfaculty_id, stddepartment_id, stdprogramme_type_id, stddegree_id, stdcourse, std_custome1, std_custome2, std_custome3, std_custome4, std_custome6, std_custome9, std_custome10, std_custome14, std_custome15, std_custome18, std_custome19, std_custome21) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)" );	
	foreach( $clean_list as $k=>$v ) {

		$student_profile_save->bind_param('isssssssssssssiiiiiissssssissssi', $clean_list[$k]['log_id'], $clean_list[$k]['matric_no'], $clean_list[$k]['surname'], $clean_list[$k]['othernames'], $gender, $marital, $home_town, $local_gov, $state, $n, $contact, $clean_list[$k]['email'], $contact, $mobile, $course_programme, $course_faculty, $course_dept, $one, $degree, $course_field, $course_level, $yearsession, $std_custom3, $pass, $std_custom6, $clean_list[$k]['matric_no'], $std_custom10, $one, $one, $yearsession, $new, $zero);
		$student_profile_save->execute();
		$clean_list[$k]['std_id'] = mysqli_insert_id( $connect );
	
	}
	$student_profile_save->close();
	
		
	
	$status = 'active';
	$student_status_save = mysqli_prepare( $connect, 'INSERT INTO studentstatus(std_logid,studentstatus,session)VALUES(?,?,?)' );
	foreach( $clean_list as $k=>$v ) {
		
		$student_status_save->bind_param('sss', $clean_list[$k]['log_id'], $status, $yearsession);
		$student_status_save->execute();	

	}
	$student_status_save->close();

	
	if( !empty($failed_list) ) {
		session_start();
		$_SESSION['failed'] = $failed_list;
	}
	
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: '.$certificate.'?i=1');
	exit("We have been Looking for Victory");
	
?>