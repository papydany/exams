<?php
require( "inc/header.php" );
//require 'config.php';
?>

<!-- content starts here -->
<script language="javascript" type="text/javascript">
function checkform ( form )
{
  if (document.form.s_session.value == "") {
    alert( "Please select Session" );
    document.form.s_session.focus();
    return false ;
  }
  if (document.form.s_semester.value == "") {
    alert( "Please select Semester" );
    document.form.s_semester.focus();
    return false ;
  }
  if (document.form.s_level.value == "") {
    alert( "Please select Level" );
    document.form.s_level.focus();
    return false ;
  }    
  if( document.form.cs_code.value == "") {
  	alert("Please Select Course Code");
	document.form.cs_code.focus();
	return false;
  }

}

</script>
<script language="javascript" type="text/javascript">
function display(rid){
        document.getElementById("pti").value = rid.value;
        }
</script>
<?php

unset( $_SESSION['s_session'], $_SESSION['s_semester'], $_SESSION['s_level'] );

if( $_GET['s_semester'] == 'vacation' ) {
	
	$disp = 'Long Vacation';
	$s_semester = ' IN ("First Semester", "Second Semester")';
	$season = 'VACATION';
} else {
	$disp = 'First & Second Semester';
	$s_semester = ' IN ("First Semester", "Second Semester")';
	$season = 'NORMAL';
}

$xyz = explode('~', $_GET['department']);
$department = $xyz[0];
$fac = $xyz[1];
?>

<form name="form" method="get" action="view_result_lecturer2.php" target="_blank">

<input type="hidden" name="season" value="<?php echo $season ?>" />
<div class="col-sm-8 col-sm-offset-2">
  <table width="50%" style="margin:0 auto;" border="0" align="center" cellpadding="0" cellspacing="0" class="text table table-bordered">
        <tr>
      <td>Session</td>
      <td><?php
$queen = $_GET['programme'] == 7 ? $_GET['s_session'] : $_GET['s_session'].'/'.( $_GET['s_session']+1 );
echo $queen;
?></td>
    </tr>
    <tr>
      <td>Semester</td>
      <td><?php
echo $disp;
?></td>
    </tr>
    <tr>
    <?php
		$level_title = GetlLevel( $_GET['s_level'] );
		if( $_GET['programme'] == 7 ) {
			echo '<td>Contact</td><td>',substr($level_title,0,1),'</td>';
		} else {
			echo '<td>Level</td><td>',$level_title,'</td>';
		}
	?>
    </tr>
     
     <tr>  
<?php
//&& all_courses.department_id = $department &&
$lecturer_id = $_SESSION['myexamofficer_id'];
$query_get_for ="SELECT fos from assign_courses WHERE lecturer_id='$lecturer_id'";
$result = mysqli_query($GLOBALS['connect'],  $query_get_for) or die(mysqli_error($GLOBALS['connect']));
$fos_count = mysqli_num_rows ($result);
if($fos_count == 0)

{
echo"<p class='text-success'>You have not been assign to a course. contact your desk officer.<p/>";
die();	
}
while($do=mysqli_fetch_assoc($result))
{
	$do_ids [] =$do['fos'];
}

$fos_ids = array();
foreach( $do_ids as $k=>$v ) {$fos_ids[] = $v;}

		
$query = "SELECT DISTINCT all_courses.course_code , all_courses.course_status, dept_options.programme_option, all_courses.thecourse_id, all_courses.course_custom2, all_courses.course_semester
    FROM all_courses  JOIN assign_courses ON all_courses.thecourse_id = assign_courses.thecourse_id INNER JOIN dept_options ON all_courses.course_custom2 = dept_options.do_id WHERE assign_courses.lecturer_id =$lecturer_id && assign_courses.level = $_GET[s_level] && assign_courses.year = $_GET[s_session] && assign_courses.dept_id = $department && all_courses.course_semester $s_semester && all_courses.level_id = $_GET[s_level] && all_courses.department_id =$department && all_courses.course_custom2 IN (".implode(',', $fos_ids).") && all_courses.course_custom5 = $_GET[s_session]  ORDER BY all_courses.course_semester, all_courses.course_status, all_courses.course_code";


$result = mysqli_query( $GLOBALS['connect'],  $query ) or die(mysqli_error($GLOBALS['connect']));
$course_count = mysqli_num_rows ($result);

			
?>
      <td>Course Code</td>
      <td><select name="cs_code" id ="cs_code" class="form-control">
        <option value="">Choose Course</option>
      <?php

if( 0!=$course_count ) {

	$grpin = array();
		
		while( $row=mysqli_fetch_assoc($result) ){
		
			$grpin[ $row['programme_option'] ][] = $row;
			

		}
		mysqli_free_result($result);
		
		foreach( $grpin as $k=>$v ) {
			
			echo '<optgroup label="',$k,'">';
			
			$taken['first'] = false;
			$taken['second'] = false;
			$size = count($v);
			$ca = 0;
			
			foreach( $v as $sk=>$sv ) {
				$ca++;
				if( $taken['first'] === false && strtolower($sv['course_semester']) == 'first semester' ) {
					echo '<optgroup label="First Semester">';
					$taken['first'] = true;
				}
				
				if( $taken['second'] === false && strtolower($sv['course_semester']) == 'second semester' ) {
					if( $taken['first'] === true )
						echo '</optgroup>';
						
					echo '<optgroup label="Second Semester">';
					$taken['second'] = true;
				}				
				
				$course_code = $sv["course_code"];
				$course_id   = $sv["thecourse_id"];
				$fos_id   = $sv["course_custom2"];

				echo '<option value="',$course_id,'-',$sv['course_semester'],'-',$fos_id,'">',$course_code,' | ',$sv['course_status'],'</span></option>';
				if( $size == $ca ) {
					if( $taken['first'] === true || $taken['second'] === true )
						echo '</optgroup>';
				}
			}
			echo '</optgroup>';
		}

}
?>
       
      </select></td>
    </tr>
    <tr>
      <td colspan="2">
      
<?php
      if( 0!=$course_count ) {
		  echo <<<CC

		  <input type="hidden"  value="0"  name ="pti" id="pti"/>
		  <input type="hidden" value="$_GET[s_session]"  name ="s_session" id="s_session"/>
		  <input type="hidden" value="$_GET[s_semester]"  name ="s_semester" id="s_semester"/>
		  <input type="hidden" value="$fac"  name ="s_faculty" id="s_faculty"/>
		   <input type="hidden" value="$department"  name ="department" id="department"/>
		  <input type="hidden" value="$_GET[s_level]"  name ="s_level" id="s_level"/></td>
CC;
	  }
?>
        
    </tr>


   <tr>
      <td>&nbsp;</td>
      <td>
      <?php
	  	echo 0!=$course_count ? '<input type="submit" name="Submit" value="Continue" onClick="return checkform(this);">' : '<input type="button" value="Continue" disabled="disabled">';
	  ?>
      </td>
    </tr>
  </table>
  </div>
</form>

<!-- content ends here -->



<?php
require( "inc/footer.php" );
?> 