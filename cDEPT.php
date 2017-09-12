<?php 
	require_once 'inc/header.php';
    require_once 'config.php';
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

<p style="font-size:16px; font-weight:700; padding:6px 10px 5px; background:#666; display:inline-block; color:#FFFFFF;">Create New Department</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" autocomplete="off">
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1">
  <tr>


      
    <td class="td"><p>Faculty</p><label for="select"></label>
      <select name="fac_id" id="select">
      <option value="">Select</option>
	<?php
    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM `faculties`');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['faculties_id'],'">',$r['faculties_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      </select></td>

      
     <td class="td" style="padding-right:20px"><p>Department Name</p>
     	<input type="text" name="dept_name">
      </td>           

  </tr>
  <tr>
  <td colspan="4" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="Create Department"></td>
  </tr>  
</table>
</form>


<?php
	//----------------------  form_cDEPT  -----------------------//
		
		if( isset($_POST['submit']) ) {

			if( !empty($_POST['fac_id']) && !empty($_POST['dept_name']) ) {
				
				$fac_id = $_POST['fac_id'];
				$new_dept = $_POST['dept_name'];
				
				$ins = mysqli_query( $GLOBALS['connect'], 'INSERT INTO departments (`departments_name`, `fac_id`) 
									VALUES 
									("'.strtoupper($dept_name).'", "'.$fac_id.'")');
				
				if( mysqli_affected_rows( $GLOBALS['connect'] )>0 ) {
					echo '<div style="padding:10px; width:710px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">Action Completed</div>';
				} else {
					echo '<div style="padding:10px; width:710px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">Action Failed</div>';
				}
				
			} else {
				echo '<div style="padding:10px; width:710px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">No Field Should Be EMpty</div>';
			}
			
		}
	
	//----------------------  form_cDEPT  -----------------------//	
?>
  

  <!-- content ends here -->
  
<?php require_once 'inc/footer.php'; ?>