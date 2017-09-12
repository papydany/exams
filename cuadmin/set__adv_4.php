<?php
	session_start();
	require_once 'inc/header.php';
    require_once '../config.php';
	require_once '../updates.php';
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
table.TABLE{ border:1px dotted #CCC; border-right:none; border-bottom:none; margin:0 auto; }
table.TABLE td, table.TABLE th{padding:2px; border-bottom:1px dotted #CCC; border-right:1px dotted #CCC; font-weight:normal; font-size:12px;}
table.TABLE input[type=text]{ border:none!important; background:transparent; width:15px;box-shadow:rgba(0,0,0,0.1) 0 0 0;
-moz-box-shadow:rgba(0,0,0,0.1) 0 0 0;
-webkit-box-shadow:rgba(0,0,0,0.1) 0 0 0;-webkit-border-radius:1px;
-khtml-border-radius:1px;
-moz-border-radius:1px;
border-radius:1px;}
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

	function result( $list,$session, $std, $period ) {
		if( !empty($list) ) {

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
			foreach( $list as $ed ) {
				
				$key = array_search($ed, $keyavail);
				if( false!==$key )
					$return[ $ed ] = array( 'std_grade'=>$avail[$ed]['grade'], 'std_mark'=>$avail[$ed]['mark'] );
				else
					$return[ $ed ] = array( 'std_grade'=>'', 'std_mark'=>'' );
			
			}

			return $return;
		}		
	}
	
	// declaring the variables
	$session = '';
	$level = '';
	$season = '';
	$sls = '';
	
	if( isset($_POST['s']) ) {
		$session = $_POST['sess'];
		$level = $_POST['lvl'];
		$season = isset( $_POST['period'] ) ? $_POST['period'] : 'NORMAL';
		$sls = $_POST['s'];
	} else if( isset($_SESSION['conclude']) ) {
		$session = $_SESSION['conclude']['session'];
		$level = $_SESSION['conclude']['level'];
		$season = $_SESSION['conclude']['season'];
		$sls = $_SESSION['conclude']['s'];
		
		unset( $_SESSION['conclude'] );
	} else {
		echo '<div class="info">Return to View Registered Student To Restart This Action <a href="viewregstudent.php">Click Here to Return</a></div>';
		exit;
	}

	
	echo '<p style="font-size:16px; padding:7px 4px 4px; font-weight:700;">Level - ',$level,' ( Session - ',$session,' )</p>';
	
	if( !empty($sls) ) {
			
			
		echo '<form action="adv_4.php" method="post">';
		
		echo '<input name="season" type="hidden" value="',$season,'">
			  <input name="level" type="hidden" value="',$level,'">
			  <input name="session" type="hidden" value="',$session,'">';
			
		$bgcolor = '';
		foreach( $sls as $std_id=>$v ):
			
			$ex = explode('~', $v);
			$fos = $ex[0];
			$matric_no = $ex[1];
			$surname = $ex[2];
			$othername = $ex[3];
			
			$bgcolor = $bgcolor == '#FBFCFD' ? '#FFFFFF' : '#FBFCFD';

			echo '<div style=" padding:7px 10px 10px; background:',$bgcolor,'">',
			'<div style="font-size:12px; padding:3px; display:block; overflow:hidden"><p style="width:100px; background:#EEF2F7; text-align:center; float:left">',$matric_no,'</p> <p style="float:left;text-align:center; width:80%">',$surname,' ',$othername,'</p></div>';	
			$loadcr = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM course_reg WHERE std_id = '.$std_id.' && clevel_id = '.$level.' && cyearsession = "'.$session.'" && course_season = "'.$season.'" ORDER BY stdcourse_custom2');
			
			if( 0!=mysqli_num_rows ($loadcr) ) {
				
				$lsB = array();
				$cid = array();
				$lsB[1] = array();
				$lsB[2]	= array();
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
				
				$fs = count( $lsB[1] );
				
				$ss = count( $lsB[2] );
				
				echo <<<TABLESS
				<table cellspacing="0" align="center" class="TABLE" cellpadding="0" border="0">
				<thead>
				<tr>
				<th colspan="{$fs}">First Semester</th>
				<th colspan="{$ss}">Second Semester</th>
				</tr>
TABLESS;


					$results = result( $cid, $session, $std_id, $season );
					echo '<tr>';
					$merge = array_merge( $lsB[1], $lsB[2] );
					foreach( $merge as $th ) {
						echo '<th><p class="ups">',
							strtoupper($th['stdcourse_custom2']),
						'</p></th>';
					}
				
					echo '</tr><tr>';
					//$merge = array_merge( $lsB[1], $lsB[2] );
					foreach( $merge as $th ) {
						echo '<th>',
							$th['c_unit'],
						'</th>';
					}					
					
					echo '</tr>','<thead><tbody><tr>';
					//$merge = array_merge( $lsB[1], $lsB[2] );
					$disable_keys = array();
					foreach( $merge as $th ) {
						$grade = return_grade( $results[ $th['thecourse_id'] ]['std_mark'] );
						
						$surname = empty($surname) ? '&nbsp;' : $surname;
						$othername = empty($othername) ? '&nbsp;' : $othername;
						
						if( empty($grade['grade']) ) {
							echo '<td><input name="b[',$std_id,'~',$matric_no,'~',$surname,'~',$othername,'~',$th['thecourse_id'],'~',$th['c_unit'],'~',$th['csemester'],']" value="" type="text" maxlength="1" size="1" />',
							'</td>';
						} else {
							$disable_keys[] = $th;
							echo '<td><input maxlength="1" size="1" name="b[',$std_id,'~',$matric_no,'~',$surname,'~',$othername,'~',$th['thecourse_id'],'~',$th['c_unit'],']" value="',strtoupper($grade['grade']),'" type="text" />',
   							'</td>';							
						}
					}
					
					echo '</tr>';
					//$merge = array_merge( $lsB[1], $lsB[2] );
					
					foreach( $merge as $th ) {
						if( in_array($th, $disable_keys) ) {
							echo '<td>',
								'<input name="d[',$std_id,'~',$matric_no,'~',$surname,'~',$othername,'~',$th['thecourse_id'],']" type="checkbox" value="" />',
							'</td>';
						} else {
							echo '<td>',
								'<input name="d[',$std_id,'~',$matric_no,'~',$surname,'~',$othername,'~',$th['thecourse_id'],']" type="checkbox" value="" />',
							'</td>';
						}
					}					
					
					
					
					echo '</tr><tbody></table>';
					
					
			}
				 
			echo '</div>';	 			
		endforeach;

	echo '<div id="s" style=" width:890px;margin:10px auto; text-align:center;" class="block">
	<input type="submit" value="Save Result" name="submit" style="margin:1px" />
	<input type="submit" value="Delete Result" name="submit" style="margin:1px" />
	</div>
	';		
		
		echo '</form>';
	}
	
?>



<?php require_once 'inc/footer.php'; ?>