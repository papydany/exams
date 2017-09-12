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

<div class="row">
<div class="col-sm-12">
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" autocomplete="off">
<table class="table">
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
	
<form method="POST" action="assigncourses_lecturer_query.php">
<div class="col-sm-7">
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
			echo '<table class="table table-bordered"><thead>',
					'<th>S/N</th>',
					'<th>Select</th>',
					'<th>Course Code</th>',
					
					'<th>Unit</th>',
					'<th>Course Semester</th>',
					'<th>Status</th>',
					
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

					<?php
					echo '<tr>',
					'<td>',$c,'</td>',
							'<td>
							<input type="checkbox" name="thecourse_id['.$c.']" value="'.$r[thecourse_id].'"></td>',
							'<td><b>',$r['course_code'],'</b></td>',
							
							'<td>',$r['course_unit'],'</td>',
							'<td>',$r['course_semester'],'</td>',
							'<td style="background:#EEE">',$r['course_status'],'</td></tr>';
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
  

</div>
<div class="col-sm-5">
<table class="table table-bordered">
<tr>
	<td>Select Department</td>
	<td> <select class="form-control" name="department_id" id="department_id">
   <option value="">-- Select --</option>';
<?php
    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments` order by departments_name asc');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['departments_id'],'">',$r['departments_name'],'</option>';
    }
	mysqli_free_result($l_dept);
	?>

     </select></td>
</tr>
<td>select lecturer</td>
<td>

 <select class="form-control" name="lecturer_id" id="lecturer_id">
 
</select></td>
	
</table>

</div>
</form>
</div>
</div>
<?php require_once 'inc/footer.php'; ?>

<script type="text/javascript">

$(document).ready(function(){

$("#department_id").change( function() {
$("#lecturer_id").hide();



$.ajax({
type: "POST",
data: "data=" + $(this).val(),
url: "get_lec.php",
success: function(msg){
if (msg != ''){

$("#lecturer_id").html(msg).show();
$("#result").html('');
}
else{
$("#result").html('<em>No item result</em>');
}
}
});


});
});
</script>	