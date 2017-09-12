<?php 
	require_once 'inc/header.php';
    require_once '../config.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.us { width:735px;}
.us thead{ background:#666; color:#FFF;}
.us thead th, .us tbody td{ border-bottom:1px solid #EEE; padding:5px; text-align:left}
.i{
  margin:0 3px;
  font-style:normal;
  color:#bbb
}
</style>

<p style="font-size:16px; font-weight:700; padding:6px 10px 5px; background:#666; display:inline-block; color:#FFFFFF;">New Field Of Study</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" autocomplete="off">
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1">
  <tr>

	  

    <td width="" class="td"><p>Department</p><label for="select2"></label>
      <select name="department" id="select2">
      <option value="">Select</option>
	<?php
    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments` order by departments_name asc');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['departments_id'],',',$r['fac_id'],'">',$r['departments_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      </select></td>

      
    <td class="td"><p>Programme</p><label for="select"></label>
      <select name="programme" id="select">
      <option value="">Select</option>
	<?php
    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `programme_id`, `programme_name` FROM `programme`');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['programme_id'],'">',$r['programme_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      </select></td>


    <td class="td"><p>Field Of Study Name</p>
    	<input type="text" name="fos">
    </td>
      
      
     <td class="td" style="padding-right:20px"><p>Duration</p>
     	<input type="text" name="fosD">
      </td>           

  </tr>
  <tr>
  <td colspan="4" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="Create Field Of Study"></td>
  </tr>  
</table>
</form>


<?php
	//----------------------  form_cFOS  -----------------------//
		
		if( isset($_POST['submit']) ) {

			if( !empty($_POST['fos']) && !empty($_POST['fosD']) && !empty($_POST['department']) && !empty($_POST['programme'])  ) {
				
				$x = explode(',', $_POST['department']);
				$dept = $x[0];
				$fosN = $_POST['fos'];
				$duration = $_POST['fosD'];
				$programme = $_POST['programme'];
				
				$ins = mysqli_query( $GLOBALS['connect'], 'INSERT INTO dept_options (`dept_id`,  `programme_option`,  `duration`,  `prog_id`) 
									VALUES 
									('.$dept.', "'.$fosN.'", "'.$duration.'", '.$programme.')');
				
				if( mysqli_affected_rows( $GLOBALS['connect'] )>0 ) {
					echo '<div style="padding:10px; width:710px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">Action Completed</div>';
				} else {
					echo '<div style="padding:10px; width:710px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">Action Failed</div>';
				}
				
			} else {
				echo '<div style="padding:10px; width:710px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">No Field Should Be EMpty</div>';
			}
			
		}
	
	//----------------------  form_cFOS  -----------------------//	
?>
  

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
	
?>
  
<?php require_once 'inc/footer.php'; ?>