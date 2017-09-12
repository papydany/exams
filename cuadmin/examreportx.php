<?php

  $s_session = $_GET['s_session'];
  $faculty_id = $_GET['faculty'];
  $department_id = $_GET['department'];
  $rtype = $_GET['rtype'];
  $s_level = $_GET['s_level'];
  $course = $_GET['course'];
  $programme = $_GET['programme'];
  
/*  if( empty($s_session) || empty($faculty_id) || empty($department_id) || empty($rtype) || empty($s_level) || empty($course) || empty($programme) ) {
	header('Location: results_spreadsheetx.php');
	header('HTTP/1.1 301 Moved Permanently');
	exit;
  }
  */
  
  switch ($rtype) {
      case '0':
          header("location:probational_report.php?s_session=$s_session&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme");
      break;
      case '1':
	  
          $spillcheck = substr($s_level, -1);
          switch( $spillcheck ) {
			  case 's':
			  	header( "Refresh: 2; URL=report4.php?s_session=$s_session&s_level=" .substr($s_level, 0, 1). "&faculty_id=$faculty_id&department_id=$department_id&special=spillover&course=$course&programme=$programme" );
              	//header("location:report4.php?s_session=$s_session&s_level=" .substr($s_level, 0, 1). "&faculty_id=$faculty_id&department_id=$department_id&special=spillover&course=$course&programme=$programme");
			  break;
			  case 'f':
     	         header( "Refresh: 2; URL=report4.php?s_session=$s_session&s_level=" .substr($s_level, 0, 1). "&faculty_id=$faculty_id&department_id=$department_id&special=regular&course=$course&programme=$programme");
			  break;
			  default:
              	header( "Refresh: 2; URL=report4.php?s_session=$s_session&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme");
			  break;
		  }
          
      break;
      case '2':
          header( "Refresh: 2; URL=correction_report.php?s_session=$s_session&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme");
      break;
      case '3':
          header( "Refresh: 2; URL=omitted_report.php?s_session=$s_session&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme");
      break;
      case '4':
          header( "Refresh: 2; URL=vacation_report.php?s_session=$s_session&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme");
      break;
	  case '5':
	  	header( "Refresh: 2; URL=f_report4.php?s_session=$s_session&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme" );
	  break;
  }
  
  exit('<span style="font-family:tahoma; font-size:13px;">Loading Result Report Environment ..</span>');
  
  
?>