<?php
	require_once 'inc/header.php';
    require_once 'config.php';
?>


<!-- content starts here -->
<style>

select{ width:120px; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; padding:4px; margin-top:2px;}
.td{ padding:10px 3px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
</style>

<form action="form_addcourses.php" method="post" autocomplete="off" onsubmit="return valid(this);">
<?php

echo '<input type="hidden" name="certificate" value="',basename($_SERVER['SCRIPT_FILENAME']),'" />';

	if( isset($_GET['i']) ) {
		switch( $_GET['i']) {
			case 1:
				echo '<div class="info">Courses Successfully Added</div>';
			break;
			default:
			break;
		}
	}
?>
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1; margin:2px auto;">
  <tr>
    <td width="" class="td"><p>Level</p>
      <select name="level" id="select">
      <option value="">.. Pick Level ..</option>
		<?php
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level` WHERE programme_id = '.$_SESSION['myprogramme_id'].' ORDER BY level_name');
        while( $l=mysqli_fetch_assoc($ac) ) {
			echo '<option value=',$l['level_id'],'>';
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

    <td width="" class="td"><p>Faculty</p><label for="select2"></label>
      <?php
	  	echo '<input name="faculty" type="hidden" value="',$_SESSION['myfaculty_id'],'">';
	  ?>
      <select disabled>
		<?php
        $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `faculties_name` FROM `faculties` WHERE `faculties_id` = '.$_SESSION['myfaculty_id'].'');
        $r=mysqli_fetch_assoc($l_dept);
        echo '<option>',$r['faculties_name'],'</option>';
        mysqli_free_result($l_dept);
        ?>
      </select>
    </td>      

    <td width="" class="td"><p>Department</p><label for="select2"></label>
      <?php
	  	echo '<input name="department" type="hidden" value="',$_SESSION['mydepartment_id'],',',$_SESSION['myfaculty_id'],'">';
	  ?>
      <select disabled>
		<?php
        $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_name` FROM `departments` WHERE `departments_id` = '.$_SESSION['mydepartment_id'].'');
        $r=mysqli_fetch_assoc($l_dept);
        echo '<option>',$r['departments_name'],'</option>';
        mysqli_free_result($l_dept);
        ?>
      </select></td>
      
    
    <td width="" class="td"><p>Programme</p><label for="select"></label>
      <?php
	  	echo '<input name="programme" type="hidden" value="',$_SESSION['myprogramme_id'],'">';
	  ?>    
      <select disabled>
	<?php
		$l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `programme_name` FROM `programme` WHERE `programme_id` = '.$_SESSION['myprogramme_id'] );
		$r=mysqli_fetch_assoc($l_dept);
		echo '<option>',$r['programme_name'],'</option>';
		mysqli_free_result($l_dept);
    ?>
      </select></td>

    <td width="" class="td" ><p>Field Of Study</p><label for="select"></label>

      <select name="course" id="field">
		<option value="">.. Pick Field ..</option>
		<?php
			$fos = load_fos( $_SESSION['myusername'] );
     
			foreach( $fos as $r ) {
				echo '<option value="',$r['do_id'],'">',$r['programme_option'],'</option>';
			}
        ?>      
      </select></td>

      <?php
    if($_SESSION['myprogramme_id'] == 7){
    ?>
<td width="" class="td"><p>Contact</p><label for="select"></label>
      <select name="semester" id="select">
      <option value="">.. Pick Contact ..</option>
      <option value="First Semester">Contact 1</option>
      <option value="Second Semester">Contact 2</option>
      <option value="First Semester">Contact 3</option>
      <option value="Second Semester">Contact 4</option>
      <option value="First Semester">Contact 5</option>
      <option value="Second Semester">Contact 6</option>
       <option value="First Semester">Contact 7</option>
      <option value="Second Semester">Contact 8</option>
      </select></td>


      <td width="" class="td"><p>Month Of Entery</p><label for="select"></label>
      <select name="month" id="month">
      <option value="">.. Pick Month ..</option>
      <option value="1">APRIL</option>
      <option value="2">AUGUST</option>
      </select></td>


      <td width="" class="td">
<span id="result"></span>
      <p>Curricullum Year</p><label for="select2"></label>
      <select name="yearsession" id="yearsession">
     <option value="">-------------</option>
      </select></td>
      <?php
}else{
    ?>
    <td width="" class="td"><p>Semester</p><label for="select"></label>
      <select name="semester" id="select">
      <option value="">.. Pick Semester ..</option>
      <option value="First Semester">First Semester</option>
      <option value="Second Semester">Second Semester</option>
      </select></td>
    
      
     <td width="" class="td" style="padding-right:20px"><p>Curricullum Year</p><label for="select"></label>
      <select name="yearsession">
       <option value="">select section</option>
		<?php
            for ($year = (date('Y')-1); $year >= 1998; $year--) {

		      $yearnext =$year+1;
						echo "<option value='$year'>$year/$yearnext</option>\n";
		
				}
       echo'</select>
     </td>';
}
      
		 ?>
     
          
  </tr>
</table>
  
<table width="880" border="0" cellpadding="0" cellspacing="0" style="padding:20px 0; margin:0 auto" class="field">
<?php
for($i=1; $i<21; $i++) {
	?>
  <tr>
    <td><span>Course Code</span><p><input type="text" id="txt<?php echo $i?>" value="" name="cc[<?php echo $i ?>]" /></p></td>
    <td><span>Credit Unit</span><p><input type="text" value="" name="cu[<?php echo $i ?>]" /></p></td>
    <td><span>Course Title</span><p><input type="text" value="" name="ct[<?php echo $i ?>]" /></p></td>
    <td><span>Course Category</span><p><select name="cct[<?php echo $i ?>]" ><option>C</option><option>E</option></select></p></td>
  </tr>
  <?php  
}
?>
  <tr>
  <td colspan="3" ><input name="" type="submit" value="Add Course"></td>
  </tr>
</table>
</form>
  <!-- content ends here -->
  

<script type="text/javascript" src="validator.js" defer="defer"></script>
<script type="text/javascript" defer="defer">

function valid(f) {

	var re = new RegExp("^[a-zA-Z]{3}[0-9]{3,4}$");
	
	for(var i=1; i<21; i++) {
		var inpt = document.getElementById('txt'+i);
		if( inpt.value!='' ) {
			if( !re.test(inpt.value) ) {
				alert('Incorrect Course Code.. format is ( XXX000 or XXX0000 ). X - Alphabets and 0 - Numbers');
				inpt.focus();
				return false;
			}
		}
	}

    var err = '';

    err += checkDropdown(f.level, "Level is Needed");
    err += checkDropdown(f.semester, 'Semester Is Needed');	
    err += checkDropdown(f.course, 'Field Of Study Is Needed');
 err += checkDropdown(f.yearsession, 'Curricullum year Is Needed');
    if (err != '') {
       alert(err);
	   if( v_r != '' ){
       	v_r.focus();
	   }
       return false;
    }
    return true;	

}
</script>
  
<?php require_once 'inc/footer.php'; ?>
<script type="text/javascript" src="js/get_session_sandwich.js"></script>