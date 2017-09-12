<?php
	require_once 'inc/header.php';
    require_once 'config.php';
	require_once 'updates.php';
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

	echo '<form action="adv_2.php" method="post" >';	
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
						$result[ $d['level_id'] ] = 'Level '.$d['level_name'];
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

	
	$std_id = $_GET['s'];
	$matric = $_GET['m'];
	$period = isset( $_GET['p'] ) ? $_GET['p'] : 'NORMAL';
	
	$fullname = get_fullname( $std_id );
	
	
	$level_titles = get_leveltitles();
	
	echo '<div style="padding:0 5px 10px; font-size:17px; font-family:arial"><b>',$fullname['surname'],',</b> ',$fullname['othernames'],'  -  ',$_GET['m'],'</div>';
	
echo <<<A
	<input name="std" type="hidden" value="{$std_id}" />
	<input name="mat" type="hidden" value="{$matric}" />
	<input name="period" type="hidden" value="{$period}" />
A;
	
	
	$regd = mysqli_query( $GLOBALS['connect'], 'SELECT  `rs_id`,  `std_id`,  `sem`,  `ysession`,  `rslevelid`,  `season` FROM `registered_semester` WHERE std_id = "'.$std_id.'" && season = "'.$period.'" GROUP BY `ysession`');
	if( 0!=mysqli_num_rows($regd) ) {
		$ys = array();
		while( $ds = mysqli_fetch_assoc($regd) ) {
			$ys[] =$ds;
		}
		mysqli_free_result($regd);
		//var_dump($ys);
		foreach( $ys as $y ) {

			$level_id = $y['rslevelid'];
			$session = $y['ysession'];

			echo '<div style="display:block; width:100%; overflow:hidden; margin-bottom:10px;">',
					'<div style="float:left; width:10%; font-size:15px;">',$level_titles[ $level_id ],'<span style="display:block; font-size:11px; overflow:hidden">',$session,'</span></div>',
					'<div style="float:left; width:90%; overflow-x:auto">';

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
						
						echo <<<TABLESS
						<table cellspacing="0" class="TABLE" cellpadding="0" border="0" id="tabily">
						<thead>
						<tr>
						<th colspan="{$fs}">First Semester</th>
						<th colspan="{$ss}">Second Semester</th>
						</tr>
TABLESS;
					$results = result( $cid, $session, $std_id, $period );
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
				'</div>';
		}
	}
	
	if( isset($_GET['s']) && !empty($_GET['s']) ) {
	
		echo '<div id="s" style=" width:890px;margin:0 auto; text-align:center;" class="block">
		<input type="submit" value="Save Result" name="submit" style="margin:10px;" />
		<input type="submit" value="Delete Result" name="submit" style="margin:10px;" />
		</div>
		';	

	}
	echo '</form>';
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