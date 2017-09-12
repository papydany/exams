<?php 
	require_once 'inc/header.php';
    require_once 'config.php';
?>


<!-- content starts here -->
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
   <?php
      if($_SESSION['myprogramme_id']==7){

      echo'<td width="" class="td"><p>Month Of Entery</p><label for="select"></label>
      <select name="month" id="month">
      <option value="">.. Pick Month ..</option>
      <option value="1">APRIL</option>
      <option value="2">AUGUST</option>
      </select></td>


      <td width="" class="td">
<span id="result"></span>
      <p>Year / Session</p><label for="select2"></label>
      <select name="yearsession" id="yearsession">
     <option value="">-------------</option>
      </select></td>'; 
    }else{
    ?>

    
   <td width="" class="td"><p>Year / Session</p><label for="select2"></label>
      <select name="yearsession">
		<?php
			
			$chosen = '';
            for ($year = (date('Y')-1); $year >= 1998; $year--) {
				$chosen = $_GET['yearsession'] == $year ? 'selected="selected"' : '';
			
						$yearnext =$year+1;
						echo "<option value='$year' $chosen>$year/$yearnext</option>\n";
				
				}
  echo'</select></td>';
            } 
		 
        ?> 
   
      
      
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
  <td colspan="5" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="View Courses"></td>
  </tr>  
</table>

<?php

	if( isset($_SESSION['info']) ) {
		switch( $_SESSION['info'] ) {
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
	
	if( isset($_GET['submit']) && !empty($_GET['department']) && !empty($_GET['level']) && !empty($_GET['course']) && !empty($_GET['programme']) ):
		
		list( $dept, $fac ) = explode(',', $_GET['department']);
		
		$a = mysqli_query( $GLOBALS['connect'], 'SELECT `course_id`,  `thecourse_id`,  `course_title`,  `course_code`,  `course_unit`, `course_semester`,  `course_status`,  `course_custom1`,  `course_custom2`,  `course_custom3`,  `course_custom4`,  `course_custom5`, dept_options.programme_option FROM all_courses INNER JOIN dept_options ON all_courses.course_custom2 = dept_options.do_id WHERE programme_id = '.$_GET['programme'].' && faculty_id='.$fac.' && department_id = '.$dept.' && course_custom2 = '.$_GET['course'].' && level_id = '.$_GET['level'].' && course_custom5 = "'.$_GET['yearsession'].'" ORDER BY course_status');
		
		if( 0==mysqli_num_rows ($a) ) {
			echo '<div class="info">No Course Found</div>';
		} else {
			
			$title = $_SESSION['myprogramme_id'] == 7 ? 'Contact '.substr(GetlLevel($_GET['level']),0,1) : 'Level - '.GetlLevel($_GET['level']);
			echo '<p class="us" style="font-size:16px;margin:0 auto; padding:7px 4px 4px; font-weight:700;">',$title,' ( Session - ',$_GET['yearsession'],' )</p>';
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
					$c++;
					echo '<tr>',
							'<td style="text-align:center">',$c,'</td>',
							'<td><b>',$r['course_code'],'</b></td>',
							'<td>',$r['course_title'],'</td>',
							'<td>',$r['course_unit'],'</td>',
							'<td>',$r['course_semester'],'</td>',
							'<td style="background:#EEE">',$r['course_status'],'</td>',
							'<td><a href="editcourse.php?',$_SERVER['QUERY_STRING'],'&cid=',$r['thecourse_id'],'">Edit Course</a> / <a href="delcourse.php?',$_SERVER['QUERY_STRING'],'&amp;d=',$r['thecourse_id'],'" onClick="return confirmLink(this, \'Are You Sure.. You want to Delete This Course.\')">Drop Course</a></td>',						
						 '</tr>';
				}

			}

			echo '</tbody></table>';

		}
		
	endif;
?>
  
</form>
  <!-- content ends here -->

  
<?php require_once 'inc/footer.php'; ?>
<script type="text/javascript" src="js/get_session_sandwich.js"></script>