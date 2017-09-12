<?php
	require_once 'inc/header.php';
    require_once 'config.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; padding:4px; margin-top:2px;}
.td{ padding:10px 2px 10px 10px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:10px}
</style>

<form action="form_addstudent.php" method="post" autocomplete="off" onsubmit="return valid(this);">

<?php

echo '<input type="hidden" name="certificate" value="',basename($_SERVER['SCRIPT_FILENAME']),'" />';


if( isset($_SESSION['failed'] ) ) {
	echo '<div class="info">The Following Students Could Not Be Added</div>';
	echo '<div style="color:red; margin-top:5px; padding:10px; font-size:13px;">';
	foreach( $_SESSION['failed'] as $l ) {
		echo $l['fullname'],'<br/>';
	}
	echo '</div>';
	unset( $_SESSION['failed'] );
}

	if( isset($_GET['i']) ) {
		switch( $_GET['i']) {
			case 1:
				echo '<div class="info">Students Successfully Added</div>';
			break;
			default:
			break;
		}
	}
?>

<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1; margin:2px auto;">
 

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
      <select name="yearsession" id="yearsession">
          <option value="">select section</option>
		<?php
            for ($year = (date('Y')-1); $year >= 1998; $year--) {

				
						$yearnext =$year+1;
						echo "<option value=\"$year\">$year/$yearnext</option>\n";
			
				}
 echo'</select></td>';
            } 
        ?> 
      
  
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
    
  

    <td width="" class="td"><p>Field Of Study</p><label for="select"></label>
      <select name="course" id="field">
		<option value="">.. Pick Field ..</option>
		<?php
			$fos = load_fos( $_SESSION['myusername'] );
			foreach( $fos as $r ) {
				echo '<option value="',$r['do_id'],'">',$r['programme_option'],'</option>';
			}
        ?>      
      </select></td>      
      
    <td width="" class="td" style="padding-right:20px"><p>Degree</p><label for="select"></label>
      <select name="degree" id="field">
      <option value="">.. Pick Degree ..</option>
	<?php
    $l = mysqli_query( $GLOBALS['connect'], 'SELECT `degree_id`, `degree_name`, `programme_id` FROM `degree` WHERE programme_id = '.$_SESSION['myprogramme_id'].' ORDER BY degree_name ASC');
    while( $r=mysqli_fetch_assoc($l) ) {
    	echo '<option value="',$r['degree_id'],'">',$r['degree_name'],'</option>';
    }
	mysqli_free_result($l);
    ?>
      </select></td>
  </tr>
</table>
  
<table width="880" border="0" cellpadding="0" cellspacing="0" style="padding:20px 0; margin:0 auto" class="field">
<?php
for($i=1; $i<51; $i++) {
	?>
  <tr>
    <td><span>Surname</span><p><input type="text" value="" name="sn[<?php echo $i ?>]" /></p></td>
    <td><span>Othernames</span><p><input type="text" value="" name="on[<?php echo $i ?>]" /></p></td>
    <td><span>Matric No</span><p><input type="text" value="" name="mn[<?php echo $i ?>]" /></p></td>
  </tr>
  <?php  
}
?>
  <tr>
  <td colspan="3" ><input name="" type="submit" value="Add Student"></td>
  </tr>
</table>
</form>
  <!-- content ends here -->
  
<?php

	require_once './json.php';
	
	$json = new JSON_obj;
	
	$l_field = mysqli_query( $GLOBALS['connect'], 'SELECT `do_id`, `dept_id`, `programme_option`, `duration`, `prog_id` FROM `dept_options`');
	$sunny = array();
	while( $a=mysqli_fetch_assoc($l_field) ) {
		$sunny[$a['dept_id']][] = array('idi'=>$a['do_id'],'namey'=>$a['programme_option']);
	}
	$dump = $json->encode($sunny);
	unset($l_field);
	
	$l_field = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments`');
	$sunny = array();
	while( $a=mysqli_fetch_assoc($l_field) ) {
		$sunny[$a['fac_id']][] = array('idi'=>$a['departments_id'],'namey'=>$a['departments_name'], 'fac'=>$a['fac_id']);
	}
	$dump2 = $json->encode($sunny);	
	
?>
<script type="text/javascript" src="validator.js" defer="defer"></script>
<script type="text/javascript" defer="defer">

function valid(f) {

    var err = '';

    err += checkDropdown(f.level, "Level is Needed");
    err += checkDropdown(f.course, 'Field Of Study Is Needed');
    err += checkDropdown(f.degree, 'Degree Is Needed');
  err += checkDropdown(f.yearsession, 'Session Is Needed');
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