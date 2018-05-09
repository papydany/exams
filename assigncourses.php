<?php 
	require_once 'inc/header.php';
    require_once 'config.php';
    $fac =$_SESSION['myfaculty_id'];
$dept =$_SESSION['mydepartment_id'];
?>
<style type="text/css">
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.us { width:750px; margin:0 auto;}
.us thead{ background:#666; color:#FFF;}
.us thead th, .us tbody td{ border-bottom:1px solid #EEE; padding:5px; text-align:left}
</style>


<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" autocomplete="off">
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1; margin:0 auto;">
  <tr>
    
    <td width="" class="td" style="padding-right:20px"><p>Year / Session</p><label for="select"></label>
      <select name="cyear" id="cyear">
		<?php 

			$chosen = '';
            for ($year = (date('Y')-1); $year >= 1998; $year--) {
				$chosen = $_GET['cyear'] == $year ? 'selected="selected"' : '';
				switch( $_SESSION['myprogramme_id'] ) {
					case 7: case '7':
						echo "<option value='$year' $chosen>$year</option>\n";
					break;
					default:
						$yearnext =$year+1;
						echo "<option value='$year' $chosen>$year/$yearnext</option>\n";
					break;
				}

            }			
        ?>       
      </select></td>
      
      
    <td width="" class="td"><p>Level</p>
      <select name="level" id="select">
      <option value="">Select</option>
		<?php
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level` WHERE programme_id = '.$_SESSION['myprogramme_id'].' ORDER BY level_name');
		
        while( $l=mysqli_fetch_assoc($ac) ) {
			if( $_GET['level']==$l['level_id']) {
				echo '<option value=',$l['level_id'],' selected="selected">';
			} else {
				echo '<option value=',$l['level_id'],'>';
			}
			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {
				echo $l['level_name'].' - DIPLOMA';
			} elseif( $l['level_id'] > 12 ) {
				echo $l['level_name'];// 'Contact '.substr($l['level_name'],0,1);
			} else
				echo $l['level_name'];

			echo '</option>';
        }
        mysqli_free_result($ac);
        ?>
      </select></td>

    <td width="" class="td"><p>Department</p><label for="select2"></label>
      <?php
	  	echo '<input name="department" type="hidden" value="',$_SESSION['mydepartment_id'],',',$_SESSION['myfaculty_id'],'">';
	  ?>    
      <select disabled="disabled">
	<?php
    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments` WHERE `departments_id` = '.$_SESSION['mydepartment_id'].' order by departments_name asc');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['departments_id'],',',$r['fac_id'],'">',$r['departments_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      </select></td>

      
    <td width="" class="td"><p>Programme</p><label for="select"></label>
      <input name="programme" type="hidden" value="<?php echo $_SESSION['myprogramme_id'] ?>">
      <select id="select" disabled="disabled">
	<?php
    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `programme_id`, `programme_name` FROM `programme` WHERE `programme_id` = '.$_SESSION['myprogramme_id'].'');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['programme_id'],'">',$r['programme_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      </select></td>


    <td width="" class="td" style="padding-right:20px"><p>Field Of Study</p><label for="select"></label>
      <select name="course" id="field">
      <option value="">Select</option>
		<?php
			$fos = load_fos( $_SESSION['myusername'] );
			foreach( $fos as $r ) {
				if( $_GET['course'] == $r['do_id'] ) {
					echo '<option value="',$r['do_id'],'" selected="selected">',$r['programme_option'],'</option>';
				} else {
					echo '<option value="',$r['do_id'],'">',$r['programme_option'],'</option>';
				}
			}		
        ?>       
      </select></td>
  </tr>
  <tr>
  <td colspan="5"><input class="btn btn-danger" name="submit" type="submit" value="Select Courses"></td>
  </tr>  
</table>
</form>



<?php

	if( isset($_GET['info']) ) {
		switch( $_GET['info'] ) {
			case 1:
			case 11:
				echo '<div class="info">Action Completed</div>';				
			break;
			case 0:
			case 12:
				echo '<div class="info">Please Try Again</div>';
			break;
			
		}
		unset( $_SESSION['info'] );
	}
	?>
<form method="POST" action="assigncourses_query.php">

	<?php
	
	if( isset($_GET['submit']) && !empty($_GET['department']) && !empty($_GET['level']) && !empty($_GET['course']) && !empty($_GET['programme']) ):
		
		list( $dept, $fac ) = explode(',', $_GET['department']);
	?>
<input type="hidden" name="level" value="<?php echo $_GET['level']; ?>">
<input type="hidden" name="fos" value="<?php echo $_GET['course']; ?>">
<input type="hidden" name="year" value="<?php echo $_GET['cyear']; ?>">
<input type="hidden" name="dept" value="<?php echo $dept; ?>">
<input type="hidden" name="fac" value="<?php echo $fac; ?>">
<input type="hidden" name="programme_id" value="<?php echo $_GET['programme']; ?>">
	<?php

		
		$a = mysqli_query( $GLOBALS['connect'], 'SELECT `course_id`,  `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`, `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom3`,  `course_custom4`,  `course_custom5`, dept_options.programme_option FROM all_courses INNER JOIN dept_options ON all_courses.course_custom2 = dept_options.do_id WHERE programme_id = '.$_GET['programme'].' && faculty_id='.$fac.' && department_id = '.$dept.' && course_custom2 = '.$_GET['course'].' && level_id = '.$_GET['level'].' && course_custom5 = "'.$_GET['cyear'].'" ORDER BY course_status');
		
		if( 0==mysqli_num_rows ($a) ) {
			echo '<div class="info">No Course Found</div>';
		} else {
			
			$title = $_SESSION['myprogramme_id'] == 7 ? 'Contact '.substr(GetlLevel($_GET['level']),0,1) : 'Level - '.GetlLevel($_GET['level']);
			echo '<p class="us" style="font-size:16px;margin:0 auto; padding:7px 4px 4px; font-weight:700;">',$title,' ( Session - ',$_GET['cyear'],' )</p>';
			$c = 0;
			echo '<table class="us" cellpadding="0" cellspacing="0"><thead>',
					'<th width=3%>S/N</th>',
					'<th width=12%>Course Code</th>',
					'<th width="36%">Course Title</th>',
					'<th width="5%">Unit</th>',
					'<th width="15%">Course Semester</th>',
					'<th width="5%">Status</th>',
					'<th width="20%">Action</th>',
				 '</thead><tbody>';

			$grpin = array();
			while( $r = mysqli_fetch_assoc($a) ) {
				$grpin[ $r['programme_option'] ][] = $r;
			}
			mysqli_free_result($a);
			
			$c = 0;
			foreach( $grpin as $k=>$v ) {
				echo '<tr><td colspan="7" style="text-align:center; background:#EEE;"><i>',$k,'</i></td></tr>';
			
				foreach( $v as $sk=>$r ) {

					// check for if the courses has been assingn

$sql_c = 'SELECT * FROM assign_courses WHERE level ="'.$_GET['level'].'" && fac_id = "'.$fac.'" && dept_id = '.$dept.' && year = "'.$_GET['cyear'].'" && fos = "'.$_GET['course'].'" && programme_id = "'.$_GET['programme'].'" && thecourse_id="'.$r['thecourse_id'].'"';
	
	$r_c = mysqli_query($GLOBALS['connect'],  $sql_c ) or die(mysqli_error($GLOBALS['connect']));
	// if the course does not exist it can be display
if( 0==mysqli_num_rows ($r_c)) {
					$c++;
					?>
<input type="hidden" name="thecourse_id[<?php echo $c ?>]" value="<?php echo $r['thecourse_id']; ?>">
					<?php
					echo '<tr>',
							'<td style="text-align:center">',$c,'</td>',
							'<td><b>',$r['course_code'],'</b></td>',
							'<td>',$r['course_title'],'</td>',
							'<td>',$r['course_unit'],'</td>',
							'<td>',$r['course_semester'],'</td>',
							'<td style="background:#EEE">',$r['course_status'],'</td>',
							'<td>';
    $query = "SELECT * FROM exam_officers WHERE programme_id=0 and faculty_id ='$fac' and department_id ='$dept'  and eo_status =1";
$result = mysqli_query($GLOBALS['connect'], $query) or die(mysqli_error($GLOBALS['connect']));
?>
 <select class="form-control" name="lecturer_id[<?php echo $c ?>]">
 <option value="">Select lecturer</option>
 <?php
if(mysqli_num_rows ($result) > 0)
{
	while ($row = mysqli_fetch_assoc($result)) {
		$id =$row['examofficer_id'];
		echo '<option value="',$id,'">',$row['eo_surname']." ".$row['eo_firstname']." ".$row['eo_othernames'],'</option>';

	}
}
							echo'</select></td>',						
						 '</tr>';
				}

			}
		}
		?>
		<tr>
		<td>
<input type="submit" name="submit" value="Assign Courses" class="btn btn-success">
</td>
</tr>
<?php
			echo '</tbody></table>';

		}
		
	endif;
?>
  
</form>
<?php require_once 'inc/footer.php'; ?>