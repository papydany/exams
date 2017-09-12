<?php
	//require_once 'inc/header.php';
	require_once './updates.php';
	require_once './config.php';
	require_once './include_report.php';
?>
<style type="text/css">
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
table.TABLE{ border:1px dotted #CCC; border-right:none; border-bottom:none; }
table.TABLE td, table.TABLE th{padding:2px; border-bottom:1px dotted #CCC; border-right:1px dotted #CCC; font-weight:normal; font-size:12px;}
table.TABLE input[type=text]{ border:none!important; background:transparent; width:14px;box-shadow:rgba(0,0,0,0.1) 0 0 0;
-moz-box-shadow:rgba(0,0,0,0.1) 0 0 0;
-webkit-box-shadow:rgba(0,0,0,0.1) 0 0 0;-webkit-border-radius:1px;
-khtml-border-radius:1px;
-moz-border-radius:1px;
border-radius:1px;}
</style>

<?php	

$pd = isset( $_GET['period'] ) ? $_GET['period'] : 'NORMAL';


	
	function result( $list,$session, $std, $period ) {
		
		if( !empty($list) ) {
          // var_dump($period);

			$avail = array();
			$ae = mysqli_query( $GLOBALS['connect'], 'SELECT stdcourse_id,std_grade, std_mark FROM students_results WHERE std_id = '.$std.' && stdcourse_id IN ('.implode(',', $list).') && std_mark_custom2 = "'.$session.'" && period = "'.$period.'"');
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
		$ae = mysqli_query($GLOBALS['connect'],'SELECT firstname, surname, othernames, stdcourse, stdfaculty_id, stddepartment_id FROM students_profile WHERE std_id = '.$std.'');
		if( 0!=mysqli_num_rows($ae) ) {
			$data = mysqli_fetch_assoc($ae);
			mysqli_free_result($ae);
			return $data;
		}
	}
	
	function get_facname( $fid ) {
		$ae_d = mysqli_query($GLOBALS['connect'],'SELECT faculties_name FROM faculties WHERE faculties_id = '.$fid.'');
		if( 0!=mysqli_num_rows($ae_d) ) {
			$data = mysqli_fetch_assoc($ae_d);
			mysqli_free_result($ae_d);
			return $data;
		}
	}
	
	function get_deptname( $did ) {
		$ae_d = mysqli_query($GLOBALS['connect'],'SELECT departments_name FROM departments WHERE departments_id = '.$did.'');
		if( 0!=mysqli_num_rows($ae_d) ) {
			$data = mysqli_fetch_assoc($ae_d);
			mysqli_free_result($ae_d);
			return $data;
		}
	}
	
	function get_cosname( $cid ) {
		$ae_d = mysqli_query($GLOBALS['connect'],'SELECT programme_option FROM dept_options WHERE do_id = '.$cid.'');
		if( 0!=mysqli_num_rows($ae_d) ) {
			$data = mysqli_fetch_assoc($ae_d);
			mysqli_free_result($ae_d);
			return $data;
		}
	}
	
	function c_duration( $C ) {
		$D = mysqli_query( $GLOBALS['connect'],'SELECT duration FROM dept_options WHERE do_id = '.$C.' LIMIT 1');
		if( 0!=mysqli_num_rows($D) ) {
			$daa = mysqli_fetch_assoc($D);
			mysqli_free_result($D);
			return $daa['duration'];
		}		
	}

function populate_result ($std_id, $matric,$pd)
{	
	//$std_id = $_GET['s'];
	//$matric = $_GET['m'];
	
	
	$stdcourse_duration = c_duration( $fullname['stdcourse'] );


	//var_dump($stdcourse_duration);
	
	$level_titles = get_leveltitles();
	
	
	
echo <<<A
	<input name="std" type="hidden" value="{$std_id}">
	<input name="mat" type="hidden" value="{$matric}">
A;
	
	$regd = mysqli_query($GLOBALS['connect'],'SELECT  `rs_id`,  `std_id`,  `sem`,  `ysession`,  `rslevelid`,  `season` FROM `registered_semester` WHERE std_id = '.$std_id.' GROUP BY `ysession`');
	if( 0!=mysqli_num_rows($regd) ) {
		$ys = array();
		while( $ds = mysqli_fetch_assoc($regd) ) {
			$ys[] =$ds;
		}
		mysqli_free_result($regd);
		
		foreach( $ys as $y ) {

			$level_id = $y['rslevelid'];
			$session = $y['ysession'];

			echo '<div style="display:block; width:100%; overflow:hidden; margin-bottom:10px;">',
					'<div style="float:left; margin-left:10px; overflow-x:auto">';
						
						

$query = 'SELECT * FROM course_reg WHERE std_id = '.$std_id.' && clevel_id = '.$level_id.' && course_season="'.$pd.'" && cyearsession = "'.$session.'" ORDER BY stdcourse_custom2';
						

						$loadcr = mysqli_query($GLOBALS['connect'],$query) or die(mysqli_error($GLOBALS['connect']));


						$lsB = array();
						$cid = array();
						$lsB[1] = array();
						$lsB[2]	= array();											
						if( 0!=mysqli_num_rows($loadcr) ) {

							while( $ds = mysqli_fetch_assoc($loadcr) ) {
								$cid[] = $ds['thecourse_id'];
								//var_dump($cid);
								switch( $ds['csemester'] ) {
									case 'First Semester':
										$lsB[1][] = $ds;
									break;
									case 'Second Semester':
										$lsB[2][] = $ds;
									break;
								}
							}
							

						}
						
						$fs = empty($lsB[1]) ? 1 : count( $lsB[1] );
						
						$ss = empty($lsB[2]) ? 1 : count( $lsB[2] );
						
						echo <<<TABLESS
						<table cellspacing="0" class="TABLE" cellpadding="0" border="0">
						<tr>
						<th><p style="font-size:12px; color:#333; padding:3px; font-weight:500;">Session</p></th>
						<th><p style="font-size:12px; padding:3px;color:#333; font-weight:500;">Level</p></th>
						<th colspan="{$fs}">First Semester</th>
						<th colspan="{$ss}">Second Semester</th>
						<th><p style="font-size:12px; padding:3px; color:#333;font-weight:500;">GPA</p></th>
						<th><p style="font-size:12px; padding:3px; font-weight:500;">CGPA</p></th>
						</tr>
TABLESS;

					$results = result( $cid, $session, $std_id, $pd );
			//	var_dump($results);
					echo '<tr>';
					echo '<td rowspan="3" style="vertical-align:middle; color:#333;text-align:center; font-weight:500;">',$session,'</td>';
					echo '<td rowspan="3" style="vertical-align:middle;color:#333; text-align:center; font-weight:500;">',$level_titles[ $level_id ],'</td>';
					$merge = array_merge( $lsB[1], $lsB[2] );
					foreach( $merge as $th ) {
						echo '<th><p class="ups">',
							strtoupper($th['stdcourse_custom2']),
						'</p></th>';
					}

					$cgpa = auto_cgpa($session, $std_id, $level_id, $stdcourse_duration, "$session/$session+1");
					echo '<td rowspan="3" style="vertical-align:middle;color:#333;text-align:center;font-weight:500;">',get_gpa($session, $std_id),'</td>';
					echo '<td rowspan="3" style="vertical-align:middle;text-align:center; font-size:12px;font-weight:500;">',$cgpa,'</td>';
				
					echo '</tr><tr>';
					$merge = array_merge( $lsB[1], $lsB[2] );
					foreach( $merge as $th ) {
						echo '<th>',
							$th['c_unit'],
						'</th>';
					}					
					
					echo '</tr>','<tr>';
					$merge = array_merge( $lsB[1], $lsB[2] );
					foreach( $merge as $th ) {
						$grade = return_grade( $results[ $th['thecourse_id'] ]['std_mark'] );
						//var_dump($grade);
						echo '<td style="text-align:center">',strtoupper($grade['grade']),'</td>';
					}
									
					
					
					
					echo '</table>';	
			  echo '</div>',
				'</div>';
		}
	}

}

/*$season=	$_GET['season'];
	$std_id = $_GET['s'];
	$ysession = $_GET['ysess'];
	$level = $_GET['level'];
	$fos = $_GET['c'];
	*/
	
	
if( isset($_POST['print']) && $_POST['print'] == 'Print Result' && !empty($_POST['s']) ):
		
		//$ysession = $_POST['sess'];
		//$level = $_POST['lvl'];
			//$season=	$_GET['season'];
			
		//$qB = '';
		
		foreach( $_POST['s'] as $k=>$v ) {
			
			$std_id = $k;
			//$ex = explode('~', $v);
			//$fos = $ex[0];
			?><div style="background: url(images/icon_trans10.jpg) left no-repeat; margin-left:50px;"> <?php
			echo '<div style="margin-left:10px; "><img src="images/logo.jpg" alt="" width="70" height="70" align="left" style="margin-right:20px;" /><strong>UNIVERSITY OF CALABAR, CALABAR</strong> <br>';
			
			list($std_c, $matric, $surname, $othernames) = explode('~', $v);
			$fullname = get_fullname( $std_id );
			$deptname = get_deptname( $fullname['stddepartment_id'] );
			$facname = get_facname( $fullname['stdfaculty_id'] );
			$cosname = get_cosname( $fullname['stdcourse'] );
			//print_r ($deptname);
			echo '<div style="padding:0 10px 10px; font-size:12px; font-family:arial;">';
			echo '<strong>FACULTY:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $facname['faculties_name'] .'<br>';
			echo '<strong>DEPARTMENT:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $deptname['departments_name'] .'<br>';
			echo '<strong>COURSE OF STUDY:</strong> &nbsp;&nbsp;&nbsp;' . $cosname['programme_option'] .'<br>';
			echo '<br><b>',$fullname['surname'],',</b> ',$fullname['othernames'],' - ( <strong>',$matric,'</strong> )<br>';
			echo '</div></div><div style="font-size:9px">';
			populate_result( $std_id, $matric ,$pd);
			echo '<span style="font-size:10px;">PLEASE SEE YOUR HEAD OF DEPARTMENT OR ACADEMIC ADVISER FOR ADVICE</span><br></div><div style="margin-left:150px">';//<img src="images/registrars_signature.jpg" alt="" />';
			echo '<br>---------------------------';
			echo '<br >&nbsp;&nbsp;<strong>Registrar signature</strong><br>
			</div>';
			?></div><br><br><?php
			
		}
		
endif;
	
 //require_once 'inc/footer.php'; ?>