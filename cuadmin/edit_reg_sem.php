<?php 
	require_once 'inc/header.php';
    require_once '../config.php';
?>

<?php
	if( isset($_GET['std']) ):
		
		$ls = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM registered_semester WHERE std_id = "'.$_GET['std'].'" GROUP BY registered_semester.ysession' );
		if( 0!=mysqli_num_rows($ls) ) {
			
			echo '<form method="post" action="',$_SERVER['PHP_SELF'],'" >';
			echo '<table>';
			echo '<input type="hidden" name="std" value="',$_GET['std'],'" />';
			$ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level`');
				while( $data = mysqli_fetch_assoc($ls) ) {
					echo '<tr>',
							'<td><span style="color:#555; font-size:15px; padding:3px;">',$data['ysession'],'</td>';?>
							<td><input name="reg[<?php echo $data['ysession'] ?>]" type="hidden" value="true"><select name="level[<?php echo $data['ysession'] ?>]">
								<?php
                                
                                while( $l=mysqli_fetch_assoc($ac) ) {
                                    if( $data['rslevelid'] == $l['level_id'] ) {
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
								mysqli_data_seek($ac, 0);
                                ?>
                            </select></td>
                            </tr>
                         <?php
				}
				mysqli_free_result($ac);
				echo '<input name="submit" type="submit" value="Update">';
			echo '</table>';
			echo '</form>';
		
		}
	endif;
?>




<?php
	if( isset($_POST['submit'],$_POST['std'],$_POST['reg']) ):
		
		$level = $_POST['level'];
		$std_id = $_POST['std'];
		foreach( $_POST['reg'] as $key=>$value ) {
						
			$qB = 'UPDATE registered_semester SET registered_semester.rslevelid = "'.$level[$key].'" WHERE registered_semester.ysession = "'.$key.'" && registered_semester.std_id = "'.$std_id.'";';
			$qB .= 'UPDATE students_reg SET students_reg.level_id = "'.$level[$key].'" WHERE students_reg.yearsession = "'.$key.'" && students_reg.std_id = "'.$std_id.'";';
			$qB .= 'UPDATE course_reg SET course_reg.clevel_id = "'.$level[$key].'" WHERE course_reg.cyearsession = "'.$key.'" && course_reg.std_id = "'.$std_id.'";';
			$qB .= 'UPDATE students_results SET students_results.level_id = "'.$level[$key].'" WHERE students_results.std_mark_custom2 = "'.$key.'" && students_results.std_id ="'.$std_id.'"';
			
			//echo $qB,'<br/><br/><br/>';
			//continue;
			
			if( mysqli_multi_query( $GLOBALS['connect'], $qB ) ) {	
				do {if ($result = mysqli_store_result($GLOBALS['connect'])) { while ($row = mysqli_fetch_row($result)) {} mysqli_free_result($result);}
				} while (mysqli_next_result($GLOBALS['connect']));
			}			
		
		}
		
		//header('HTTP/1.1 301 Moved Permanently');
		//header('Location: edit_reg_sem.php?std='.$std_id);
		exit('<a href="edit_reg_sem.php?std='.$std_id.'">S T U D E N T S&nbsp;&nbsp;&nbsp;R E C O R D S&nbsp;&nbsp;&nbsp;U P D A T E D</a>');
		
	endif;
?>
  
<?php require_once 'inc/footer.php'; ?>