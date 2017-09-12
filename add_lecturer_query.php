<?php
   require_once dirname(__FILE__).'/config.php';
    list($dept, $fac) = explode(',', $_POST['department']);
    
    
	$query_builder = '';
	$query_builder2 = '';
	
   $course_faculty = $fac;
	$course_dept = $dept;
$mydate = date("Y-m-d");
	$last_id = array();
	$monitor = 0;
	$to_add_username =array();
	$clean_list = array();
	
	
	
	
	
	if(empty($_POST['surname'])&&empty($_POST['firstname'])&&empty($_POST['username'])&&empty($_POST['password'])) {
		
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$certificate.'?i=0');	
		exit('No Work Available');
	
	}
	
	
	foreach( $_POST['username'] as $k=>$cc ) {
		if(!empty($cc) ) {
			if( !in_array($cc, 	$to_add_username ) ) {
				$clean_list[$cc] = array('surname'=>cleansql($_POST['surname'][$k]),'firstname'=>cleansql($_POST['firstname'][$k]), 'othernames'=>cleansql($_POST['othernames'][$k]),'username'=>$cc,'password'=>cleansql($_POST['password'][$k]),'title'=>cleansql($_POST['title'][$k]));

				$to_add_username[$k] =$_POST['username'][$k];
				
			}
			
		}
	}

		$username = array();
	foreach($clean_list as $kk=>$vv ){

		$username[] = $vv['username'];
	}

	//$queryab = 'SELECT * from exam_officers WHERE eo_username  IN ("'.implode('","', $username).'")');
$sqlab = mysqli_query( $GLOBALS['connect'], 'SELECT * from exam_officers WHERE eo_username  IN ("'.implode('","', $username).'")') or die(mysqli_error($GLOBALS['connect']));

	
	
	if( mysqli_num_rows($sqlab) >0 ) {
		while( $f = mysqli_fetch_assoc($sqlab) ) {
			
			// deletes it
			unset($clean_list[$f['eo_username']]);
		
		
	}}


if(count($clean_list) != 0)
{
	$monitor = 0;
	foreach( $clean_list as $key => $value) {	

	  $eo_username = $clean_list[$key]['username'];
      $eo_surname = $clean_list[$key]['surname'];
      $eo_firstname = $clean_list[$key]['firstname'];
      $eo_othernames = $clean_list[$key]['othernames'];
      $eo_password = $clean_list[$key]['password'];
      $eo_title =$clean_list[$key]['title'];
			
	
		
		$query_builder2 .= '(0,"'.$course_faculty.'", "'.$course_dept.'",0, "'.$eo_username.'", "'.$eo_password.'", "'.$eo_title.'", "'.$eo_surname.'", "'.$eo_firstname.'", "'.$eo_othernames.'","no_emal", "'.$mydate.'",1,3,0,0,2),';
		$monitor++;
		
	}

	$query_builder2 = substr($query_builder2,0,-1);



	$query2ab = mysqli_query($GLOBALS['connect'],'INSERT INTO exam_officers(programme_id,faculty_id,department_id,fos,eo_username,eo_password,eo_title,eo_surname,eo_firstname,eo_othernames,eo_email,eo_date_reg,eo_status,mstatus,eo_course,edit_allow_logon,user_right) VALUES'.$query_builder2) or die(mysqli_error($GLOBALS['connect']));
	
	}
	$certificate = isset( $_POST['certificate'] ) ? $_POST['certificate'] : 'add_lecturer.php'; 
	if( mysqli_affected_rows($GLOBALS['connect'])>0 ) {

		mysqli_close($GLOBALS['connect']);
		header('HTTP/1.1 301 Moved Permanently');
	     header('Location: '.$certificate.'?i=1');
		exit;
	
	}

		//done
		mysqli_close($GLOBALS['connect']);
	    header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$certificate.'?i=0');	
	
	

	
?>