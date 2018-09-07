<?php
ini_set('memory_limit', -1);
//require( "inc/header.php" );
include_once("auth.inc.php");
include_once("config.php");


$qB = '';
$return = '';
if( !isset($_POST['check']) ) {
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: '.$_POST['certificate']);	
	exit;
}

//$v = $_POST['check'];
//$v = new SplFixedArray(500);
//print_r($v);
//exit("");
if ( is_array($_POST['check'])){
	$allcheck = array_values ($_POST['check']);
	foreach ($allcheck as $value) {

		$check=$value; 
		$no=$check;
		$thecos_id = $_POST['cos_id'][$no];
		$thestd_mark = $_POST['std_mark'][$no];
		$thec_unit = $_POST['c_unit'][$no];
		$thestd_id = $_POST['std_id'][$no];
		$thecourse_custom1 = $_POST['course_custom1'][$no];
		$thematric_no = $_POST['matric_no'][$no];
		$fos_ids = $_POST['fos_id'];
//echo $thestd_mark;
		$mysss = "thestd_mark";
		if ($$mysss >= 70 ) {
			$mygrade = 'A';
			$cp = '5.00';
		}
		elseif (($$mysss >= 60) && ($$mysss < 70)) {
			$mygrade = 'B';
			$cp = '4.00';
		}
		elseif (($$mysss >= 50) && ($$mysss < 60)) {
			$mygrade = 'C';
			$cp = '3.00';
		}
		elseif (($$mysss >= 45) && ($$mysss < 50)) {
			$mygrade = 'D';
			$cp = '2.00';
		}
		elseif (($$mysss >= 40) && ($$mysss < 45)) {
			$mygrade = 'E';
			$cp = '1.00';
		}
		elseif (($$mysss >= 1) && ($$mysss  < 40)) {
			$mygrade = 'F';
			$cp = '0.00';
		}
		elseif ($$mysss == 0) {
			$mygrade = 'N';
			$cp = '0.00';
		}

		else {
			echo "<span style='color: red'>Error: Bad Mark Input, Check the mark</span>";
			exit;
		}

		$mycp = $cp*$thec_unit;


		if( $_POST['season'] == 'VACATION' ) {
			$semm1 = 'std_mark_custom1 IN ("First Semester","Second Semester")';
			$ext = ' && period = \'VACATION\'';
			$any = 'First Semester';
			$season = 'VACATION';
		} else {
			$semm1 = 'std_mark_custom1 = "'.$_SESSION['s_semester'].'"';
			$ext = ' && period = \'NORMAL\'';
			$any = $_SESSION['s_semester'];
			$season = 'NORMAL';
		}
	 
	  //echo $_POST['result_type'];exit;
	  switch( $_POST['result_type'] ):
		  
		  case 'correctional':

// CORRECTIONAL : INSET INTO STUDENT RESULT ==========================================================
/* NORMAL SESSIONAL ENTERING */
		    //exit('Am here Sessional'); | NOTE - error was on this select query with semester
			$result_flag = 'Correctional';   //      Note this flag
			$doc = date('Y-m-d');  // date of correction
			$query2ac = "SELECT *
			FROM students_results
			WHERE stdcourse_id = '$thecos_id' AND
			std_id = '$thestd_id' AND
			std_mark_custom2 = $semm1 AND
			std_mark_custom2 = '$_SESSION[yearsession]' $ext";
			//exit( $query2ac );
			$query2ac = mysqli_query( $GLOBALS['connect'], $query2ac)
			or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
			$num2ac = mysqli_num_rows ($query2ac);
			
			// RESULT NOT FOUND  
			if ($num2ac == 0 ) { 
				
			// IF records not found in students result table, insert
			} elseif( $num2ac != 0 ) { // if records found in students result table, Update students result AND insert backup
				//get the previous student result in order to insert into the backup database, before update
				
					$row_c = mysqli_fetch_assoc($query2ac);
					$stdresult_id = $row_c['stdresult_id'];
					
					$result_flag = 'Correctional';   //      Note this flag
					$doc = date('Y-m-d h:i:s');  // date of correction
					$query3ac = "SELECT *
					FROM students_results_backup
					WHERE stdcourse_id = '$thecos_id' AND
					std_id = '$thestd_id' AND
					std_mark_custom2 = $semm1 AND
					std_mark_custom2 = '$_SESSION[yearsession]' $ext";
					//exit( $query2ac );
					$query3ac = mysqli_query( $GLOBALS['connect'], $query3ac)
					or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
					$num3ac = mysqli_num_rows ($query3ac);
					
					// RESULT NOT FOUND IN BACKUP 
					if ($num3ac == 0 ) {
						//"insert into student result backup";
						$qB .= "INSERT INTO students_results_backup (stdresult_id, std_id, matric_no, level_id, stdcourse_id, std_mark, std_grade, cu, cp, std_cstatus, std_mark_custom1, std_mark_custom2, period, reason_of_correction, date_of_correction, result_flag,examofficer)
						VALUES ('$stdresult_id', '$thestd_id', '$thematric_no', '".$_SESSION['s_level']."','$thecos_id', '$thestd_mark', '" . $row_c['std_grade'] . "', '$thec_unit', '" . $row_c['cp']."', '$thecourse_custom1', '$any', '".$_SESSION['yearsession']."', '$season', '" . $_POST['reason'][$no] . "', '$doc', 'Correctional','".$_SESSION['myexamofficer_id']."' );";
						
						//then Update student previous result
						$qB .= "UPDATE students_results SET std_mark='$thestd_mark', std_grade='$mygrade', cu='$thec_unit', cp='$mycp', level_id = '$_SESSION[s_level]', std_cstatus='$thecourse_custom1', period = '$season', result_flag = 'Correctional', examofficer = '".$_SESSION['myexamofficer_id']."',date_posted = '$doc'
						WHERE stdcourse_id = '$thecos_id' AND
						std_id = '$thestd_id' AND
						std_mark_custom1 = '$any' AND
						std_mark_custom2 = '".$_SESSION['yearsession']."' $ext;";
						
					} else { //RESULT FOUND IN BACKUP
						
						
							//then Update student previous result
						//$qB .= "UPDATE students_results_backup SET std_mark='$row_c[std_mark]', std_grade='$row_c[std_grade]', cu='$row_c[cu]', cp='$row_c[cp]', level_id = '$row_c[level_id]', std_cstatus='$row_c[std_cstatus]', period = '$row_c[period]', result_flag = 'Correctional', reason_of_correction = '$row_c[reason_of_correction]',examofficer ='$_SESSION[myexamofficer_id]'
						$qB .= "UPDATE students_results_backup SET std_mark='$row_c[std_mark]', std_grade='$row_c[std_grade]', cu='$row_c[cu]', cp='$row_c[cp]', level_id = '$row_c[level_id]', std_cstatus='$row_c[std_cstatus]', period = '$row_c[period]', result_flag = 'Correctional', reason_of_correction = '" . $_POST['reason'][$no] . "',examofficer ='$_SESSION[myexamofficer_id]'
						
						WHERE stdcourse_id = '$thecos_id' AND
						std_id = '$thestd_id' AND
						std_mark_custom1 = '$any' AND
						std_mark_custom2 = '$_SESSION[yearsession]' $ext;";
						
						//then Update student previous result
						$qB .= "UPDATE students_results SET std_mark='$thestd_mark', std_grade='$mygrade', cu='$thec_unit', cp='$mycp', level_id = '$_SESSION[s_level]', std_cstatus='$thecourse_custom1', period = '$season', result_flag = '$result_flag',examofficer ='$_SESSION[myexamofficer_id]',date_posted ='$doc'
						WHERE stdcourse_id = '$thecos_id' AND
						std_id = '$thestd_id' AND
						std_mark_custom1 = '$any' AND
						std_mark_custom2 = '$_SESSION[yearsession]' $ext;";
						//  testing
						
						
					}
							
			}
			
			$return = 'add_result2p_correctional.php';  
			break;
// END CORRECTIONAL

			case 'omitted':
			
			// OMITTED : INSET INTO STUDENT RESULT ==========================================================
			/* NORMAL SESSIONAL ENTERING 
			
			Can omitted result be allowed to enter the database when the same result has already been in the database ?
			
			*/
				//exit('Am here Sessional'); | NOTE - error was on this select query with semester
				$result_flag = 'Omitted';   //      Note this flag
				$doc = date('Y-m-d');  // date of correction
				$query2ac = "SELECT *
				FROM students_results
				WHERE stdcourse_id = '$thecos_id' AND
				std_id = '$thestd_id' AND
				std_mark_custom2 = $semm1 AND
				std_mark_custom2 = '$_SESSION[yearsession]' $ext";
				//exit( $query2ac );
				$query2ac = mysqli_query( $GLOBALS['connect'], $query2ac)
				or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
				$num2ac = mysqli_num_rows ($query2ac);
				
				// RESULT NOT FOUND
				if ($num2ac == 0 ) { // IF records not found in students result table, insert
					//inser into student result			
					$qB .= "INSERT INTO students_results (std_id, matric_no, level_id, stdcourse_id, std_mark, std_grade, cu, cp, std_cstatus, std_mark_custom1, std_mark_custom2, period, result_flag, examofficer, date_posted)
					VALUES ('$thestd_id', '$thematric_no', '$_SESSION[s_level]','$thecos_id', '$thestd_mark', '$mygrade', '$thec_unit', '$mycp', '$thecourse_custom1', '$any', '$_SESSION[yearsession]', '$season', '$result_flag','$_SESSION[myexamofficer_id]', '$doc');";
				// RESULT FOUND	((((((((((((((((((((  Number of times to be determined for OMITTED result entry  )))))))))))))))
				} elseif( $num2ac != 0 ) { // if records found in students result table, Update students result AND insert backup
					
				}
				
			$return = 'add_result2p_omitted.php';  
			break;
			// END OMITTED
			
			case 'resit':
			
			// RESET : INSET INTO STUDENT RESULT ==========================================================
			/* NORMAL SESSIONAL ENTERING 
			
			Can someone resit of a course he/she did not partake/write/failed ?
			
			*/
				//exit('Am here Sessional'); | NOTE - error was on this select query with semester
				$result_flag = 'Resit';   //      Note this flag
				$doc = date('Y-m-d');  // date of correction
				$query2ac = "SELECT *
				FROM students_results
				WHERE stdcourse_id = '$thecos_id' AND
				std_id = '$thestd_id' AND
				std_mark_custom2 = $semm1 AND
				std_mark_custom2 = '$_SESSION[yearsession]' $ext";
				//exit( $query2ac );
				$query2ac = mysqli_query( $GLOBALS['connect'], $query2ac)
				or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
				$num2ac = mysqli_num_rows ($query2ac);
				
				// RESULT NOT FOUND
				if ($num2ac == 0 ) { // IF records not found in students result table, insert
					//inser into student result			
					$qB .= "INSERT INTO students_results (stdresult_id, std_id, matric_no, level_id, stdcourse_id, std_mark, std_grade, cu, cp, std_cstatus, std_mark_custom1, std_mark_custom2, period, result_flag, examofficer, date_posted)
					VALUES ('$stdresult_id', '$thestd_id', '$thematric_no', '$_SESSION[s_level]','$thecos_id', '$thestd_mark', '$mygrade', '$thec_unit', '$mycp', '$thecourse_custom1', '$any', '$_SESSION[yearsession]', '$season', 'Resit', '$_SESSION[myexamofficer_id]', '$doc' );";
					//                 ALSO
					//insert into student result backup
					$qB .= "INSERT INTO students_results_backup (stdresult_id, std_id, matric_no, level_id, stdcourse_id, std_mark, std_grade, cu, cp, std_cstatus, std_mark_custom1, std_mark_custom2, period, reason_of_correction, date_of_correction, result_flag, examofficer) 
					VALUES ('$stdresult_id', '$thestd_id', '$thematric_no', '$_SESSION[s_level]','$thecos_id', '$thestd_mark', '$mygrade', '$thec_unit', '$mycp', '$thecourse_custom1', '$any', '$_SESSION[yearsession]', '$season', '" . $_POST['reason'][$no] . "', '$doc', 'Resit', '$_SESSION[myexamofficer_id]' );";
					
				// RESULT FOUND	 - ((((((((((((((((((  1 entry ONLY allowed for resit corses   )))))))))))))))))  
				} elseif( $num2ac != 0 ) { // if records found in students result table, Update students result AND insert backup
					//get the previous student result in order to insert into the backup database, before update
					$row_r = mysqli_fetch_assoc($query2ac);
					$stdresult_id = $row_r['stdresult_id'];
					//insert into student result backup
					$qB .= "INSERT INTO students_results_backup (stdresult_id, std_id, matric_no, level_id, stdcourse_id, std_mark, std_grade, cu, cp, std_cstatus, std_mark_custom1, std_mark_custom2, period, reason_of_correction, date_of_correction, result_flag, examofficer)
					VALUES ('$stdresult_id', '$thestd_id', '$thematric_no', '$_SESSION[s_level]','$thecos_id', '$thestd_mark', '" . $row_r['std_grade'] . "', '$thec_unit', '" . $row_r['cp'] . "', '$thecourse_custom1', '$any', '$_SESSION[yearsession]', '$season', '" . $_POST['reason'][$no] . "', '$doc', 'Resit', '$_SESSION[myexamofficer_id]');";
					
					//then Update student previous result
					$qB .= "UPDATE students_results SET std_mark='$thestd_mark', std_grade='$mygrade', cu='$thec_unit', cp='$mycp', level_id = '$_SESSION[s_level]', std_cstatus='$thecourse_custom1', period = '$season', result_flag = 'Resit', examofficer = '$_SESSION[myexamofficer_id]', date_posted ='$doc'
					WHERE stdcourse_id = '$thecos_id' AND
					std_id = '$thestd_id' AND
					std_mark_custom1 = '$any' AND
					std_mark_custom2 = '$_SESSION[yearsession]' $ext;";
								
				}
				
			$return = 'add_result2p_resit.php';  
			break;
		// END RESIT

		  /* NORMAL SESSIONAL ENTERING */

				
		  		  	
		 // break;
		  case 'sessional':
		  default:
		  
		  /* NORMAL SESSIONAL ENTERING */
		    //exit('Am here Sessional');
			$result_flag = 'Sessional'; //                     Note this flag
			$doc=date('y-m-d');
			$query2ac = "SELECT *
			FROM students_results
			WHERE stdcourse_id = '$thecos_id' AND
			std_id = '$thestd_id' AND
			$semm1 AND
			std_mark_custom2 = '$_SESSION[yearsession]' $ext";
			//exit( $query2ac );
			$query2ac = mysqli_query( $GLOBALS['connect'], $query2ac)
			or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
			$num2ac = mysqli_num_rows ($query2ac);
			//echo 'seee';exit;
			
			if ($num2ac == 0 ) { // result not found
							
				$qB .= "INSERT INTO students_results (std_id, matric_no, level_id, stdcourse_id, std_mark, std_grade, cu, cp, std_cstatus, std_mark_custom1, std_mark_custom2, period, result_flag, examofficer, date_posted)
				VALUES ('$thestd_id', '$thematric_no', '$_SESSION[s_level]','$thecos_id', '$thestd_mark', '$mygrade', '$thec_unit', '$mycp', '$thecourse_custom1', '$any', '$_SESSION[yearsession]', '$season', '$result_flag', '$_SESSION[myexamofficer_id]', '$doc' );";
				
				
			} elseif( $num2ac != 0 ) { // result already exist, update
							
				$qB .= "UPDATE students_results SET std_mark='$thestd_mark', std_grade='$mygrade', cu='$thec_unit', cp='$mycp', level_id = '$_SESSION[s_level]', std_cstatus='$thecourse_custom1', period = '$season', result_flag = '$result_flag', examofficer = '$_SESSION[myexamofficer_id]', date_posted = '$doc'
				WHERE stdcourse_id = '$thecos_id' AND
				std_id = '$thestd_id' AND
				std_mark_custom1 = '$any' AND
				std_mark_custom2 = '$_SESSION[yearsession]' $ext;";
							
			}
			
		  /* NORMAL SESSIONAL ENTERING */
		  $return = 'add_result2p.php';
		  break;
		  
	  endswitch;
	

	}


}



$success = false;
if( !empty($qB) ) {
	$qB = substr($qB, 0, -1);
	
	if( mysqli_multi_query( $GLOBALS['connect'], $qB ) or die(mysqli_error($GLOBALS['connect'])) ) {
		
		$success = true;
		do {if ($results = mysqli_store_result($GLOBALS['connect'])) { while ($row = mysqli_fetch_row($results)) {} mysqli_free_result($results);}
		} while (mysqli_next_result($GLOBALS['connect']));
	}
}else{
	//echo "string";
}



if ( true === $success ) {
	
	header('HTTP/1.1 301 Moved Permanently');
	header("Location:{$return}?w_update=Result added/updated successfully.&season=$_POST[season]&yearsession=$_SESSION[yearsession]&s_level=$_SESSION[s_level]&s_semester=$_SESSION[s_semester]&cs_code=$thecos_id-$_SESSION[s_semester]-$fos_ids&pti=sel");
	exit;
}
else {
	header('HTTP/1.1 301 Moved Permanently');
	header("Location: {$return}?w_update=Result not successfully added.&season=$_POST[season]&yearsession=$_SESSION[yearsession]&s_level=$_SESSION[s_level]&s_semester=$_SESSION[s_semester]&cs_code=$_POST[xcos_id]-$_SESSION[s_semester]-$fos_ids&pti=sel");
	exit;
}

?>