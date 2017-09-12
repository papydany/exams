<?php
 
  include_once("auth.inc.php");
  include_once("config.php");
  

  if (is_array($check)) {
      $allcheck = array_values($check);
	  
		$s_session = $_POST['session_year'];

		$semm1 = 'std_mark_custom1 IN ("First Semester","Second Semester")';
		$ext = ' && period = \'NORMAL\'';
		$season = 'NORMAL';
	  
	  
	  
      foreach ($allcheck as $value) {
          $check = $value;
          $no = $check;
          
          $thecos_id = "$cos_id[$no]";
          $thestd_mark = "$std_mark[$no]";
          $thec_unit = "$c_unit[$no]";
		 $the_semester = $_POST['course_semester'][$no]; 
          $thecourse_custom1 = isset( $_POST['course_custom1'][$no] ) ? 'YES' : 'NO';
       		  
          $mysss = "thestd_mark";
          if ($$mysss >= 70) {
              $mygrade = "A";
              $cp = "5.00";
          } elseif (($$mysss >= 60) and ($$mysss < 70)) {
              $mygrade = "B";
              $cp = "4.00";
          } elseif (($$mysss >= 50) and ($$mysss < 60)) {
              $mygrade = "C";
              $cp = "3.00";
          } elseif (($$mysss >= 45) and ($$mysss < 50)) {
              $mygrade = "D";
              $cp = "2.00";
          } elseif (($$mysss >= 40) and ($$mysss < 45)) {
              $mygrade = "E";
              $cp = "1.00";
          } elseif (($$mysss >= 0) and ($$mysss < 40)) {
              $mygrade = "F";
              $cp = "0.00";
          } else {
              echo "<span style='color: red'>Error: Bad Mark Input, Check the mark</span>";
              exit;
          }
          
          $mycp = $cp * $thec_unit;
          
          $query2ac = "SELECT *
  FROM students_results
  WHERE stdcourse_id = $thecos_id AND
  std_id = $_POST[std_id] AND
  $semm1 AND
  std_mark_custom2 = '$s_session' $ext";
         
		  $query2ac = mysqli_query( $GLOBALS['connect'], $query2ac) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
          $num2ac = mysqli_num_rows ($query2ac);
          
          $query2ab = "SELECT *
  FROM students_results_backup
  WHERE stdcourse_id = $thecos_id AND
  std_id = $_POST[std_id] AND
  $semm1 AND
  std_mark_custom2 = '$s_session'";
          $query2ab = mysqli_query( $GLOBALS['connect'], $query2ab) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
          $num2ab = mysqli_num_rows ($query2ab);
          
          if ($num2ac == 0) {
              
			  $insert = "INSERT INTO students_results (stdresult_id, std_id, matric_no, level_id, stdcourse_id, std_mark, std_grade, cu, cp, std_cstatus, std_mark_custom1, std_mark_custom2, period) VALUES ('$stdresult_id', '$_POST[std_id]', '$matric_no', '$s_level','$thecos_id', '$thestd_mark', '$mygrade', '$thec_unit', '$mycp', '$thecourse_custom1', '$the_semester', '$s_session', '$season')";
              
               $results = mysqli_query( $GLOBALS['connect'], $insert) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
               $thisid = mysql_insert_id();
			  
          } elseif( $num2ac != 0 and ($_POST['Submit'] != 'Correct Result') ) {
              
			  $update = "UPDATE students_results SET std_mark='$thestd_mark', std_grade='$mygrade', cu='$thec_unit', cp='$mycp', level_id = '$s_level', std_cstatus='$thecourse_custom1'
  WHERE stdcourse_id = '$thecos_id' AND
  std_id = '$_POST[std_id]' AND
  std_mark_custom2 = '$s_session' $ext";
			$results = mysqli_query( $GLOBALS['connect'], $update);
			  		  	
		  }
          
		  
	
          if ( empty($num2ab) and ($_POST['Submit'] == 'Correct Result') ) {
				
				if( !empty($reason[$no]) ) {
					
					$bak = mysqli_query( $GLOBALS['connect'], "INSERT INTO students_results_backup(`stdresult_id`, `std_id`, `matric_no`, `level_id`, `stdcourse_id`, `std_ca`, `std_exam`, `std_mark`, `std_grade`, `cu`, `cp`, `std_cstatus`, `std_mark_custom1`, `std_mark_custom2`, `std_mark_custom3`, `period`) 
										SELECT * FROM students_results where 
										std_id = '$_POST[std_id]' and 
										std_mark_custom2 = \"{$_POST['session_year']}\"") or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
					
					$thecourse_custom1='YES';
					$results = mysqli_query( $GLOBALS['connect'], "UPDATE students_results_backup SET 
											std_mark='$thestd_mark', 
											std_grade='$mygrade', 
											cu='$thec_unit', 
											cp='$mycp', 
											level_id = '$s_level', 
											std_cstatus='$thecourse_custom1',
											reason_of_correction = '".addslashes($reason[$no])."'
											WHERE stdcourse_id = $thecos_id AND
											std_id = $_POST[std_id] AND
											std_mark_custom2 = '$s_session' $ext") or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
				}

          } elseif ( $_POST['Submit'] == 'Correct Result' ) {
				
				if( !empty($reason[$no]) ) {
					$results = mysqli_query( $GLOBALS['connect'], "UPDATE students_results_backup SET 
											std_mark='$thestd_mark', 
											std_grade='$mygrade', 
											cu='$thec_unit', 
											cp='$mycp', 
											level_id = '$s_level', 
											std_cstatus='$thecourse_custom1',
											reason_of_correction = '".addslashes($reason[$no])."'
											WHERE stdcourse_id = $thecos_id AND
											std_id = $_POST[std_id] AND
											std_mark_custom2 = '$s_session' $ext") or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
				}
		  //											std_mark_custom1 = '$s_semester' AND
		  }
          
      }
  }

  if( isset($_POST['certificate']) ) {

	header("Refresh: 1; URL=$_POST[certificate]");
	echo('<span style="font-family:tahoma;">Action Has Been Completed</span>');
	exit;

  } else {

	  if ( $results ) {
		  $typ = $_POST['Submit'] == 'Correct Result' ? 'correct' : 'update'; 
		  header("Refresh: 1; URL=_view_result.php?w_update=Result added/updated successfully.&s_session=$s_session&s_level=$s_level&s_semester=$s_semester&std_id=$_POST[std_id]&mn=$matric_no&look=$typ");
		  exit;
	  } else {
		  header("Refresh: 1; URL=_view_result.php?w_update=Result not successfully added.&s_session=$s_session&s_level=$s_level&s_semester=$s_semester&std_id=$_POST[std_id]&mn=$matric_no");
		  exit;
	  }
  
  }
?>