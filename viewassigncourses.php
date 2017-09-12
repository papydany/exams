<?php
	require_once 'inc/header.php';
    require_once 'config.php';
	require_once 'include_report.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.us { width:875px; margin:0 auto;}
.us thead{ background:#666; color:#FFF;}
.us thead th, .us tbody td{ border-bottom:1px solid #FFF; padding:5px; text-align:left}
.i{
  margin:0 3px;
  font-style:normal;
  color:#bbb
}
.us thead th{ text-align:center; color:#222; border-bottom:1px solid #AAA;}
</style>

<?php
	$selected = 'selected="selected"';
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" autocomplete="off">
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1; margin:0 auto;">
  <tr>
    <td width="" class="td"><p>Level</p>
      <select name="level" id="select">
      <option value="">Select</option>
		<?php
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level` WHERE programme_id = '.$_SESSION['myprogramme_id'].' ORDER BY level_name');
		$level = isset($_GET['level']) ? $_GET['level'] : '';
		
		while( $l=mysqli_fetch_assoc($ac) ) {
			
			if( $level == $l['level_id'] ){
				echo '<option value=',$l['level_id'],' ',$selected,'>';
			} else {
				echo '<option value=',$l['level_id'],'>';	
			}
			
			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {
				echo $l['level_name'].' - DIPLOMA';
			} elseif( $l['level_id'] > 12 ) {
				//echo 'Contact '.substr($l['level_name'],0,1);
				echo $l['level_name'];
			} else
				echo $l['level_name'];

			echo '</option>';
        }
        mysqli_free_result($ac);
        ?>
      </select></td>
      
    <td width="" class="td"><p>Year / Session</p><label for="select2"></label>
      <select name="ysess">
		<?php
			
			$chosen = '';
            for ($year = (date('Y')-1); $year >= 1998; $year--) {
				$chosen = $_GET['ysess'] == $year ? 'selected="selected"' : '';
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
      <select disabled="disabled">
	<?php
    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `programme_id`, `programme_name` FROM `programme` WHERE `programme_id` = '.$_SESSION['myprogramme_id'].'');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['programme_id'],'">',$r['programme_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      </select></td>


    <td width="" class="td"><p>Field Of Study</p><label for="select"></label>
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
  <td colspan="6" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="View Assign Courses"></td>
  </tr>  
</table>
</form>

<?php
if(isset($_GET['i']) && $_GET['i'] == 1 ) 
{
  echo'<br/><div class="alert alert-success col-sm-8 col-sm-offset-2">
  <p><strong>Successfull!</strong></p>
</div>';
}

  
  if( isset($_GET['submit']) && !empty($_GET['department']) && !empty($_GET['level']) && !empty($_GET['course']) && !empty($_GET['ysess']) ):
  
    list( $dept, $fac ) = explode(',', $_GET['department']);
    $query='SELECT DISTINCT id,thecourse_id, lecturer_id FROM assign_courses  WHERE year = "'.$_GET['ysess'].'" && programme_id = '.$_GET['programme'].' && fac_id='.$fac.' && dept_id = '.$dept.' && fos = '.$_GET['course'].' && level = '.$_GET['level'];
    //echo $query;
    $a = mysqli_query($GLOBALS['connect'], $query) or die(mysqli_error($GLOBALS['connect']));
    if( 0==mysqli_num_rows ($a) ) {
      echo '<div class="info">No Assign Courses Found</div>';
    } else {
      
      $title = $_SESSION['myprogramme_id'] == 7 ? 'Level '.GetlLevel($_GET['level']) : 'Level - '.GetlLevel($_GET['level']);
      echo '<p style="font-size:16px; padding:7px 4px 4px; font-weight:700;" class="us">',$title,' ( Session - ',$_GET['ysess'],' )</p>';
      $c = 0;

      ?>
      <div class="col-sm-10 col-sm-offset-1">
<table class="table table-striped table-bordered">
<tr>
<td>Sn</td>
<td>Lecturer</td>
<td>course code</td>
<td>Semester</td>
<td>status</td>
<td>Action</td>
</tr>
      <?php
      while ($row = mysqli_fetch_assoc($a)) {

        $query_lecturer ="SELECT * From exam_officers WHERE examofficer_id =".$row['lecturer_id'];

       $query_all_courses ='SELECT * From all_courses WHERE level_id ="'.$_GET['level'].'" && faculty_id = "'.$fac.'" && department_id = '.$dept.' && course_custom5 = "'.$_GET['ysess'].'" && course_custom2 = "'.$_GET['course'].'" && programme_id = "'.$_GET['programme'].'" && thecourse_id="'.$row['thecourse_id'].'"';

$a11 = mysqli_query( $GLOBALS['connect'],$query_lecturer)or die(mysqli_error($GLOBALS['connect']));
$a1 = mysqli_fetch_assoc($a11);


$a22 = mysqli_query( $GLOBALS['connect'],$query_all_courses)or die(mysqli_error($GLOBALS['connect']));
$a2 = mysqli_fetch_assoc($a22);

$id = $row['id'];
echo'<td>',++$c,'</td>',
    '<td>',$a1['eo_surname']." ".$a1['eo_firstname']." ".$a1['eo_othernames'],'</td>',
    '<td>',$a2['course_code'],'</td>',
    '<td>',$a2['course_semester'],'</td>',
    '<td>',$a2['course_status'],'</td>',
    '<td><a href="remove.php?assign_course_id='.$id.'" class=btn btn-default">Remove</a></td>',
    '<tr>'; 



   
      }
      echo'</table></div>';
     
    }
    
  endif;
  
?>
 


<?php require_once 'inc/footer.php'; ?>