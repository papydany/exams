<?php
	require_once 'inc/header.php';
    require_once '../config.php';
?>

<?php
	if( isset($_POST['schk']) && !empty($_POST['schk']) ) {
		$std = array();
		foreach( $_POST['schk'] as $v ) {$std[] = $v;}
		

		$ls = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM registered_semester INNER JOIN students_profile USING(std_id) WHERE std_id IN ('.implode(',', $std).') GROUP BY registered_semester.std_id, registered_semester.ysession' );
		
		
		if( 0!=mysqli_num_rows($ls) ) {
			$datas = array();
			while( $ds = mysqli_fetch_assoc($ls) ) {
				$datas[ $ds['std_id'] ][] = $ds;
			}
			mysqli_free_result($ls);
			
			$levs = array();
			$ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level`');
			if( 0!=mysqli_num_rows($ac) ) {
				while( $d=mysqli_fetch_assoc($ac) ) {
					$levs[] = $d;
				}
				mysqli_free_result($ac);
			}
			
			echo '<form method="post" action="">';
			

			foreach( $datas as $key=>$data ) {
				echo '<div style="padding:3px 10px; font-size:15px; background:#EEE">',$data[0]['surname'],' ',$data[0]['firstname'],' ',$data[0]['othernames'],'</div>';
				echo '<table>';
				echo '<input type="hidden" name="std[]" value="',$data[0]['std_id'],'" />';
				foreach( $data as $d ) {
					
					echo '<tr>',
							'<td><span style="color:#555; font-size:15px; padding:3px;">',$d['ysession'],'</span>
							<input name="sess[',$d['std_id'],'][]" type="hidden" value="',$d['ysession'],'" /></td>',
							'<td><select name="lvl[',$d['std_id'],'][]">';
										foreach( $levs as $l ) {
											if( $d['rslevelid'] == $l['level_id'] ) {
												echo '<option value=',$l['level_id'],' selected="selected">';
											} else {
												echo '<option value=',$l['level_id'],'>';
											}
											if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {
												echo $l['level_name'].' - DIPLOMA';
											} elseif( $l['level_id'] > 12 ) {
												echo $l['level_name'].' - SANDWICH';
											} else
												echo $l['level_name'];
								
											echo '</option>';							
										}
							echo'</select></td>',
						'</tr>';
				}
				echo '</table>';
				
			}
			
		}
		
		echo '<input name="submit" type="submit" value="Save Changes" />';
		
		echo '</form>';
		
	}
?>


<?php

	require_once '../config.php';
	if( isset($_POST['std']) && isset($_POST['submit']) ):
		
		foreach( $_POST['std'] as $key=>$value ) {
			// Each student
			foreach( $_POST['sess'][$value] as $k=>$v ) {
				// Each Reg Sem

				$qB = 'UPDATE registered_semester SET registered_semester.rslevelid = "'.$_POST['lvl'][$value][$k].'" WHERE registered_semester.ysession = "'.$v.'" && registered_semester.std_id = "'.$value.'";';
				$qB .= 'UPDATE students_reg SET students_reg.level_id = "'.$_POST['lvl'][$value][$k].'" WHERE students_reg.yearsession = "'.$v.'" && students_reg.std_id = "'.$value.'";';
				$qB .= 'UPDATE course_reg SET course_reg.clevel_id = "'.$_POST['lvl'][$value][$k].'" WHERE course_reg.cyearsession = "'.$v.'" && course_reg.std_id = "'.$value.'";';
				$qB .= 'UPDATE students_results SET students_results.level_id = "'.$_POST['lvl'][$value][$k].'" WHERE students_results.std_mark_custom2 = "'.$v.'" && students_results.std_id ="'.$value.'"';
				
				if( mysqli_multi_query( $GLOBALS['connect'], $qB ) ) {	
					do {if ($result = mysqli_store_result($GLOBALS['connect'])) { while ($row = mysqli_fetch_row($result)) {} mysqli_free_result($result);}
					} while (mysqli_next_result($GLOBALS['connect']));
				}
				
			}
		}
	
		exit('<span style="font-size:15px;">S T U D E N T S&nbsp;&nbsp;&nbsp;R E C O R D S&nbsp;&nbsp;&nbsp;U P D A T E D</span>');
		
	endif;
	
	

?>


<?php require_once 'inc/footer.php'; ?>