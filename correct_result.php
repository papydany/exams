<?php
	require_once 'inc/header.php';
    require_once 'config.php';
	require_once 'updates.php';
	
function cal_cp($unit, $G) {
	/*if ($$mysss >= 70 ) {
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
		}*/
		$G = ucwords($G);
	switch ($G) {
		case "A": 
		$cp = 5.00;// echo 'me';
		break;
		case "B": 
		$cp = 4.00;
		break;
		case "C": 
		$cp = 3.00;
		break;
		case "D": 
		$cp = 2.00;
		break;
		case "E": 
		$cp = 1.00;
		break;
		case "F": 
		$cp = 0.00;
		break;
		case "N": 
		$cp = 0.00;
		break;
		default : 
		$cp = 0.00;
		break;
		//return $cp;
		}
		//echo ucwords($G);
		return $cp * $unit;
}
?>

<?php

		
if ($_POST['submit']) {
	$s = 0; $semm1 = ' std_mark_custom1 IN ("First Semester","Second Semester")'; $ext = " AND period = '$period'";
	foreach ($_POST['newgrade'] as $k=>$v) {
		//$s += $score[$k]; //. '=' . $k . '-' . $v;//score[$th['thecourse_id']]
		$course_id = $k;
	/*	
		echo $_POST['newgrade'][$k];
		echo $_POST['type'][$k];
		echo $_POST['cunit'][$k];
		echo $_POST['mark'][$k];
		echo $_POST['semester'][$k];
		echo $_POST['period'];
		echo $_POST['session'];
		echo $_POST['std'];
		echo $ext;
	*/	
		$newgrade[$k] = $_POST['newgrade'][$k];
		$type[$k] = $_POST['type'][$k];
		$cunit[$k] = $_POST['cunit'][$k];
		$mark[$k] = $_POST['mark'][$k];
		$semester[$k] = $_POST['semester'][$k];
		$period = $_POST['period'];
		$session = $_POST['session'];
		$std_id = $_POST['std'];
		//echo $newgrade[$k];
		echo $type['k'];
		$query2ac = "SELECT *
				FROM students_results
				WHERE stdcourse_id = '$k' AND
				std_id = '$std_id' AND
				$semm1 AND
				std_mark_custom2 = '$session' $ext";
				//echo ucwords($_POST['$newgrade'][$k]);
		$strSQL = $query2ac;
			//echo $_POST['newgrade'][$k]; 
		if ($newgrade[$k] != ''){ // begin if result entered, ie; new result found
			//echo $newgrade[$k], ' ; grade = ', $score[$k], ' ; type = ', $type[$k], ' ; reason = ', $reason[$k], "<br>";
				$newgrade[$k] = ucwords($newgrade[$k]);
				$result_flag = 'Sessional';  //                     Note this flag
				/*$query2ac = "SELECT *
				FROM students_results
				WHERE stdcourse_id = '$k' AND
				std_id = '$std_id' AND
				$semm1 AND
				std_mark_custom2 = '$session' $ext";
				
				echo $query2ac;*/
				$query2ac = mysqli_query( $GLOBALS['connect'], $query2ac)
				or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
				$num2ac = mysqli_num_rows ($query2ac);
				
				$row = mysqli_fetch_assoc($query2ac);
					
				
			if ($num2ac == 0 ) { // begin result not found [ sessional | ommitted ]
			
					//echo $type[$k]."<br>";	
					if ($type[$k] == 'omitted'){ //begin omitted
					//omitted
						//$mycp = $cp * $cunit[$k];
						$mycp = cal_cp($cunit[$k], $newgrade[$k]);// exit;
						//$qBo = 'OMITTED: '.$type[$k];
						$qB .= "INSERT INTO students_results (stdresult_id, std_id, matric_no, level_id, stdcourse_id, std_mark, 
								std_grade, cu, cp, std_cstatus, std_mark_custom1, std_mark_custom2, period, result_flag) VALUES 
								('$stdresult_id', '$std_id', '$matric', '$_SESSION[s_level]','$course_id', '$mark[$k]', 
								'$newgrade[$k]', '$cunit[$k]', '$mycp', 'YES', '$semester[$k]', '$session', '$period', 'Omitted');";//$period /  $season $course_id
//echo $qBo,'<br>';	
					} elseif ($type[$k] == 'sessional'){ //begin sessional
					//seasonal
					$mycp = cal_cp($cunit[$k], $newgrade[$k]);
					//$qBs = 'SESIONAL: '.$type[$k];
						$qB .= "INSERT INTO students_results (stdresult_id, std_id, matric_no, level_id, stdcourse_id, std_mark,
								std_grade, cu, cp, std_cstatus, std_mark_custom1, std_mark_custom2, period, result_flag) VALUES 
								('$stdresult_id', '$std_id', '$matric', '$_SESSION[s_level]','$course_id', '$mark[$k]', 
								'$newgrade[$k]', '$cunit[$k]', '$mycp', 'YES', '$semester[$k]', '$session', '$period', 'Sessional');";
//echo $qBs;			
					} //end sessional

			} elseif( $num2ac != 0 ){ // begin result already exist [ resit | correctional ]
			
					if ($type[$k] == 'resit'){//begin resit
					//resit ------------------------------------------
						//echo 'error';
						//get the previous student result in order to insert into the backup database, before update
								$mycp = cal_cp($cunit[$k], $newgrade[$k]);
								//insert into student result backup
								$qB .= "INSERT INTO students_results_backup (stdresult_id, std_id, matric_no, level_id, stdcourse_id,
										std_mark, std_grade, cu, cp, std_cstatus, std_mark_custom1, std_mark_custom2, period, 
										reason_of_correction, date_of_correction, result_flag) VALUES ('".$row['stdresult_id']."',
										'".$row['std_id']."', '".$row['matric_no']."', '".$row['level_id']."','".
										$row['stdcourse_id']."', '".$row['std_mark']."', '".$row['std_grade']."', '".
										$row['cu']."', '".$row['cp']."', '".$row['std_cstatus']."', '".$row['std_mark_custom1']."', 
										'".$row['std_mark_custom2']."', '".$row['period']."', '".$row['reason_of_correction']."',
										'".$row['date_of_correction']."', '".$row['result_flag']."' );";
								
								//then Update student previous result
								$qB .= "UPDATE students_results SET std_mark='$mark[$k]', std_grade='$newgrade[$k]', cu='$cunit[$k]',
								cp='$mycp', level_id = '$_SESSION[s_level]', std_cstatus='YES', period = '$period', 
								result_flag = 'Resit' 
								WHERE stdcourse_id = '$k' AND
								std_id = '$std_id' AND
								$semm1 AND
								std_mark_custom2 = '$session' $ext;";
								//echo $qBr;			
							// end of resit ---------------------------------------
					} elseif ($type[$k] == 'correctional'){ //begin correctional
					//omitted
								$mycp = cal_cp($cunit[$k], $newgrade[$k]);
								//insert into student result backup
						$qB .= "INSERT INTO students_results_backup (stdresult_id, std_id, matric_no, level_id, stdcourse_id,
										std_mark, std_grade, cu, cp, std_cstatus, std_mark_custom1, std_mark_custom2, period, 
										reason_of_correction, date_of_correction, result_flag) VALUES ('".$row['stdresult_id']."',
										'".$row['std_id']."', '".$row['matric_no']."', '".$row['level_id']."','".
										$row['stdcourse_id']."', '".$row['std_mark']."', '".$row['std_grade']."', '".
										$row['cu']."', '".$row['cp']."', '".$row['std_cstatus']."', '".$row['std_mark_custom1']."', 
										'".$row['std_mark_custom2']."', '".$row['period']."', '".$row['reason_of_correction']."',
										'".$row['date_of_correction']."', '".$row['result_flag']."' );";
						echo $type[$k];
								echo 'sql: '.$strSQL;
						//then Update student previous result
						$qB .= "UPDATE students_results SET std_mark='$mark[$k]', std_grade='$newgrade[$k]', cu='$cunit[$k]',
								cp='$mycp', level_id = '$_SESSION[s_level]', std_cstatus='YES', period = '$period', 
								result_flag = 'Correctional' 
								WHERE stdcourse_id = '$k' AND
								std_id = '$std_id' AND
								$semm1 AND
								std_mark_custom2 = '$session' $ext;";
								echo $qB;
					} //end correctional
					
				} // end result found/exist [ resit | correctional ]
			
			}// end if result entered, ie; new result found - validation
			
	} // end foreach loop of the array

echo $qB; /*
// -------------------  EXECUTE QUERY LIST  -------------------------	
$success = false;
if( !empty($qB) ) {
	$qB = substr($qB, 0, -1);
	if( mysqli_multi_query( $GLOBALS['connect'], $qB ) ) {
		$success = true;
		do {if ($results = mysqli_store_result($GLOBALS['connect'])) { while ($row = mysqli_fetch_row($result)) {} mysqli_free_result($result);}
		} while (mysqli_next_result($GLOBALS['connect']));
	}
}
*/
//  -------------------------- END QUERY EXECUTION  ----------------------------
} // end form submit

?>
<style type="text/css">/
.ups{
-webkit-transform: rotate(-90deg);
-moz-transform: rotate(-90deg);
-o-transform: rotate(-90deg);
-khtml-transform: rotate(-90deg);

filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
height:70px;
text-align:center;
width:20px;
position:relative;
left:25px;
top:15px;
}
input {width:1px; size:1px;}
table.TABLE{ border:1px dotted #CCC; border-right:none; border-bottom:none; }
table.TABLE td, table.TABLE th{padding:2px; border-bottom:1px dotted #CCC; border-right:1px dotted #CCC; font-weight:normal; font-size:12px;}
table.TABLE input[type=text]{ border:none!important; background:transparent; width:15px;box-shadow:rgba(0,0,0,0.1) 0 0 0;
-moz-box-shadow:rgba(0,0,0,0.1) 0 0 0;
-webkit-box-shadow:rgba(0,0,0,0.1) 0 0 0;-webkit-border-radius:1px;
-khtml-border-radius:1px;
-moz-border-radius:1px;
border-radius:1px; width:100%}
</style>
<script type="text/javascript">
function killBackSpace(e) {
	e = e ? e : window.event;
	var t = e.target ? e.target : e.srcElement ? e.srcElement : null;
	
	if( t.tagName.toLowerCase() == 'input' && t.getAttribute("readonly") == "readonly" ){
		return false;
	}
	
	if (t && t.tagName && (t.type && /(password)|(text)|(file)/.test(t.type.toLowerCase())) || t.tagName.toLowerCase() == 'textarea')
		return true;
	
	var k = e.keyCode ? e.keyCode : e.which ? e.which : null;
	if (k == 8) {
		if (e.preventDefault)
			e.preventDefault();
		return false;
	};
	return true;
};

if (typeof document.addEventListener != 'undefined')
	document.addEventListener('keydown', killBackSpace, false);
else if (typeof document.attachEvent != 'undefined')
	document.attachEvent('onkeydown', killBackSpace);
else {
	if (document.onkeydown != null) {
		var oldOnkeydown = document.onkeydown;
		document.onkeydown = function (e) {
			oldOnkeydown(e);
			killBackSpace(e);
		};
	}
	else document.onkeydown = killBackSpace;
}

</script>


<?php
//adv_21.php
echo '<form action="" method="post" >';	
	function result( $list,$session, $std, $period ) {
		
		if( !empty($list) ) {

			$avail = array();
			$ae = mysqli_query( $GLOBALS['connect'], 'SELECT stdcourse_id, std_grade, std_mark FROM students_results WHERE std_id = '.$std.' && stdcourse_id IN ('.implode(',', $list).') && std_mark_custom2 = "'.$session.'" && period = "'.$period.'"');
			if( 0!=mysqli_num_rows ($ae) ) {
				
				while( $ds = mysqli_fetch_assoc($ae) ) {
					$avail[ $ds['stdcourse_id'] ] = array('grade'=>$ds['std_grade'],'mark'=>$ds['std_mark']);
				}
				mysqli_free_result($ae);
			}

			$return = array();
			$keyavail = array_keys($avail);
			
			$key = '';
			foreach( $list as $ed ) {
				$key = array_search($ed, $keyavail);
				//echo $avail[$ed]['grade'];
				if( $key!==false )
					$return[ $ed ] = array( 'std_grade'=>$avail[$ed]['grade'], 'std_mark'=>$avail[$ed]['mark'] );
				else
					$return[ $ed ] = array( 'std_grade'=>'', 'std_mark'=>'' );
					
			}
			
			return $return;
		}
				
	}
	
	
	function get_leveltitles() {
		
		$ae = mysqli_query( $GLOBALS['connect'], 'SELECT level_id, level_name, programme_id FROM level' );
		if( 0 != mysqli_num_rows($ae) ) {
			$result = array();
			while( $d=mysqli_fetch_assoc($ae) ) {
				switch( $d['programme_id'] ) {
					case '2':
						$result[ $d['level_id'] ] = 'Level '.$d['level_name'];
					break;
					case '1':
						$result[ $d['level_id'] ] = $d['level_name'].' - Diploma';
					break;
					case '7':
						$result[ $d['level_id'] ] = 'Contact '.substr($d['level_name'], 0, 1);
					break;
					case '6':
						$result[ $d['level_id'] ] = $d['level_name'];
					break;
				}
			}
			mysqli_free_result($ae);
			return $result;
		}
		return false;
		
	}
	
	function get_fullname( $std ) {
		$ae = mysqli_query( $GLOBALS['connect'], 'SELECT firstname, surname, othernames FROM students_profile WHERE std_id = "'.$std.'"');
		if( 0!=mysqli_num_rows ($ae) ) {
			$data = mysqli_fetch_assoc($ae);
			return $data;
		}
	}

	$session = $_GET['sess'];
	$std_id = $_GET['s'];
	$matric = $_GET['m'];
	$period = isset( $_GET['p'] ) ? $_GET['p'] : 'NORMAL';
	
	$fullname = get_fullname( $std_id );
	
	
	$level_titles = get_leveltitles();
	
	echo '<div style="padding:0 5px 10px; font-size:17px; font-family:arial"><b>',$fullname['surname'],',</b> ',$fullname['othernames'],'  -  ',$_GET['m'],'<br><font size="2">Session : <b>',$session,'</b></font><br><br><hr></div>';
	
echo <<<A
	<input name="std" type="hidden" value="{$std_id}" />
	<input name="mat" type="hidden" value="{$matric}" />
	<input name="period" type="hidden" value="{$period}" />
	<input name="session" type="hidden" value="$session" />
A;

//==========================================	
if ($_POST['submit']) {
if ( true === $success ) {
	echo '<div style="color:green;width:300px; padding:5px 40px;margin:5px 0;background:#E1FFE1; border:1px solid #B7FFB7;">Result Correction/Updated Successfully</div>';
	//header($_SERVER['QUERY_STRING']);
	/*header('HTTP/1.1 301 Moved Permanently');
	header("Location:{$return}?w_update=Result added/updated successfully.&season=$_POST[season]&s_session=$_SESSION[s_session]&s_level=$_SESSION[s_level]&s_semester=$_SESSION[s_semester]&cs_code=$thecos_id-$_SESSION[s_semester]&pti=sel");
	exit;*/
}
else {
	echo '<div style="width:300px; padding:5px 40px;margin:5px 0; color:red; border:1px solid red;">No Result Was Corrected</div>';
	/*header('HTTP/1.1 301 Moved Permanently');
	header("Location: {$return}?w_update=Result not successfully added.&season=$_POST[season]&s_session=$_SESSION[s_session]&s_level=$_SESSION[s_level]&s_semester=$_SESSION[s_semester]&cs_code=$_POST[xcos_id]-$_SESSION[s_semester]&pti=sel");
	exit;*/
}
}
//=================================================	
	$regd = mysqli_query( $GLOBALS['connect'], 'SELECT  `rs_id`,  `std_id`,  `sem`,  `ysession`,  `rslevelid`,  `season` FROM `registered_semester` WHERE std_id = "'.$std_id.'" && season = "'.$period.'" AND ysession = "'.$session.'" GROUP BY `ysession`');////
	//echo $session;
	//exit('see u');
	if( 0!=mysqli_num_rows($regd) ) {
		$ys = array();
		while( $ds = mysqli_fetch_assoc($regd) ) {
			$ys[] =$ds;
		}
		mysqli_free_result($regd);
		
		foreach( $ys as $y ) {

			$level_id = $y['rslevelid'];
			$session = $y['ysession'];

			//echo '<div style="display:block; width:100%; overflow:hidden; margin-bottom:10px;">',
					//'<div style="float:left; width:10%; font-size:15px;">',$level_titles[ $level_id ],'<span style="display:block; font-size:11px; overflow:hidden">',$session,'</span></div>',
					//'<div style="float:left; width:90%; overflow-x:auto">';

						$loadcr = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM course_reg WHERE std_id = '.$std_id.' && clevel_id = '.$level_id.' && cyearsession = "'.$session.'" && course_season = "'.$period.'" ORDER BY stdcourse_custom2');

							$lsB = array();
							$cid = array();
							$lsB[1] = array();
							$lsB[2] = array();
												
						if( 0!=mysqli_num_rows($loadcr) ) {
							
							while( $ds = mysqli_fetch_assoc($loadcr) ) {
								$cid[] = $ds['thecourse_id'];
								switch( $ds['csemester'] ) {
									case 'First Semester':
										$lsB[1][] = $ds;
									break;
									case 'Second Semester':
										$lsB[2][] = $ds; 
									break;
								}
							}
							

						} else {
							echo '&nbsp;</div>';
							continue;
						}
						
						$fs = count( $lsB[1] );
						
						$ss = count( $lsB[2] );
											
						
						
					$results = result( $cid, $session, $std_id, $period );
					//echo '<tr>';
					//$merge = array_merge( $lsB[1], $lsB[2] );
					$merge1 = $lsB[1]; $merge2 = $lsB[2];
					//echo $lsB[1]; echo $lsB[2];
					echo <<<FIR
						<table cellspacing="10" cellpadding="3" border="0" width="850">
						<tr align="center">
						<th width="15%" align="left">First Semester</th>
						<th width="10%">Course Code</th>
						<th width="10%">Credit Unit</th>
						<th width="5%">Grade</th>
						<th width="10%">Correct Result</th>
						<th width="10%">Mark</th>
						<th width="5%">Type</th>
						<th width="25%">Reason of Correction</th>
						</tr>
FIR;
						
						foreach( $merge1 as $th ) {
							
							$disabled = ( isset($_SESSION['myprogramme_id']) && $_SESSION['myprogramme_id'] == '1' ) || ( isset($_SESSION['myedit_permission']) && $_SESSION['myedit_permission'] == 1 ) ? '' : 'disabled="disabled"';
							//$grade = return_grade( $results[ $th['thecourse_id'] ]['std_mark'] );
							//<input type="checkbox" name="check['.$th[thecourse_id].']" id="check['.$th[thecourse_id].']" value="'.$th[thecourse_id].'" />
								echo '<tr align="center"><td>
								<input name="semester['.$th[thecourse_id].']" type="hidden" value="First Semester" />
								<input name="cunit['.$th[thecourse_id].']" type="hidden" value="' . $th['c_unit'] . '" /></td>';
								echo '<td> ' . $th['stdcourse_custom2'] . ' </td>';
								echo '<td> ' . $th['c_unit'] . ' </td>';
								echo '<td> ' . strtoupper($results[ $th['thecourse_id'] ]['std_grade']) . ' </td>';//['grade']
								echo '<td> <input type="text" name="newgrade['.$th[thecourse_id].']" style="width:50px; text-align:center" maxlength="1" value="" /></td>';
								echo '<td> <input type="text" name="mark['.$th[thecourse_id].']" style="width:50px; text-align:center"  maxlength="2" value="" /></td>'; // disabled="disabled"
								echo '<td> <select name="type['.$th[thecourse_id].']" style="width:120px" id="jumpMenu2" >
									<option value=""></option>
									<option value="resit">Resit</option>
									<option value="correctional">Correctional</option>
									<option value="sessional">Sessional Result</option>
									<option value="omitted">Omitted</option>
									</select></td>';
								echo '<td> <input type="text" name="reason['.$th[thecourse_id].']" style="width:200px; text-align:center"  maxlength="500" value="" /></td>';
						}
						echo '</tr>';
                       
                  echo <<<SEC
						<tr align="center">
						<th width="15%" align="left">Second Semester</th>
						<th width="10%"></th>
						<th width="10%"></th>
						<th width="5%"></th>
						<th width="10%"></th>
						<th width="10%"></th>
						<th width="5%"></th>
						<th width="25%"></th>
						</tr>
SEC;
						
									
						foreach( $merge2 as $th ) {
							
							$disabled = ( isset($_SESSION['myprogramme_id']) && $_SESSION['myprogramme_id'] == '1' ) || ( isset($_SESSION['myedit_permission']) && $_SESSION['myedit_permission'] == 1 ) ? '' : 'disabled="disabled"';
							//$grade = return_grade( $results[ $th['thecourse_id'] ]['std_mark'] );
							
								echo '<tr align="center"><td>
								<input name="semester['.$th[thecourse_id].']" type="hidden" value="Second Semester" />
								<input name="cunit['.$th[thecourse_id].']" type="hidden" value="' . $th['c_unit'] . '" /></td>';
								echo '<td> ' . $th['stdcourse_custom2'] . ' </td>';
								echo '<td> ' . $th['c_unit'] . ' </td>';
								echo '<td> ' . strtoupper($results[ $th['thecourse_id'] ]['std_grade']) . ' </td>';
								echo '<td> <input type="text" name="newgrade['.$th[thecourse_id].']" style="width:50px; text-align:center" maxlength="1" value="" /></td>';
								echo '<td> <input type="text" name="mark['.$th[thecourse_id].']" style="width:50px; text-align:center"  maxlength="2" value="" /></td>'; // disabled="disabled"
								echo '<td> <select name="type['.$th[thecourse_id].']" style="width:120px" id="jumpMenu2" >
									<option value=""></option>
									<option value="resit">Resit</option>
									<option value="correctional">Correctional</option>
									<option value="sessional">Sessional Result</option>
									<option value="omitted">Omitted</option>
									</select></td>';
								echo '<td> <input type="text" name="reason['.$th[thecourse_id].']" style="width:200px; text-align:center"  maxlength="500" value="" /></td>';
						}
						echo '</tr></table>';
                        //echo '</form>';
						//exit;
						
						/*
						$disable_keys = array();
						foreach( $merge as $th ) {
							$grade = return_grade( $results[ $th['thecourse_id'] ]['std_mark'] );
							if( empty($grade['grade']) ) {
								echo '<td><input name="b[',$session,'~',$th['thecourse_id'],'~',$level_id,'~',$th['c_unit'],'~',$th['csemester'],']" value="" type="text" maxlength="1" size="1" />',
								'</td>';
							} else {
								$disable_keys[] = $th;
								$readonly = ( isset($_SESSION['myprogramme_id']) && $_SESSION['myprogramme_id'] == '1' ) || ( isset($_SESSION['myedit_permission']) && $_SESSION['myedit_permission'] == 1 ) ? '' : 'readonly="readonly"';
								echo '<td><input maxlength="1" size="1" name="b[',$session,'~',$th['thecourse_id'],'~',$th['c_unit'],'~',$level_id,']"  value="',strtoupper($grade['grade']),'" type="text" ',$readonly,' />',
								'</td>';							
							}
						}
						
						echo '</tr>';
						//$merge = array_merge( $lsB[1], $lsB[2] );
						foreach( $merge as $th ) {
							if( in_array($th, $disable_keys) ) {
								$disabled = ( isset($_SESSION['myprogramme_id']) && $_SESSION['myprogramme_id'] == '1' ) || ( isset($_SESSION['myedit_permission']) && $_SESSION['myedit_permission'] == 1 ) ? '' : 'disabled="disabled"';
								echo '<td>',
										'<input ',$disabled,' name="d[',$session,'~',$th['thecourse_id'],'~',$level_id,']" type="checkbox" value="" />',
									'</td>';							
							} else {
								echo '<td>',
									'<input name="d[',$session,'~',$th['thecourse_id'],'~',$level_id,']" type="checkbox" value="" />',
								'</td>';
							}
						}					
										
					
					echo '</tr><tbody></table>';	
			  echo '</div>',
				'</div>'; */
		}
	}
	
	if( isset($_GET['s']) && !empty($_GET['s']) ) {
	
		echo '<div id="s" style=" width:890px;margin:0 auto; text-align:center;" class="block">
		<input type="submit" value="Correct Result" name="submit" style="margin:10px;" />
		</div>
		';	
		//<input type="submit" value="Delete Result" name="submit" style="margin:10px;" />
	}
	echo '</form>';
	
	unset($newgrade[$k]);// = $_POST['newgrade'][$k];
		unset($type[$k]);// = $_POST['type'][$k];
		unset($cunit[$k]);// = $_POST['cunit'][$k];
		unset($mark[$k]);// = $_POST['mark'][$k];
		unset($semester[$k]);// = $_POST['semester'][$k];
		unset($period);// = $_POST['period'];
		unset($session);// = $_POST['session'];
		unset($std_id);// = $_POST['std'];
?>




<!--<div id="s" style=" width:890px;margin:0 auto; text-align:center;" class="block">
<input type="submit" value="+ Register Repeat Result" name="submit" style="margin:10px; font-family:'Segoe UI', 'Myriad Pro', Tahoma" />
</div>
  
</form>-->
<script type="text/javascript">
//var tab = document.getElementById('tabily');
//addEvent(tab, 'click', function(){
//});


</script>


<?php require_once 'inc/footer.php'; ?>