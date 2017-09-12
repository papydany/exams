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

<p style="font-size:16px; font-weight:700; padding:6px 10px 5px; background:#666; display:inline-block; color:#FFFFFF;">Edit Field Of Study</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" autocomplete="off">
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

      
    <td class="td" style="padding-right:20px"><p>Programme</p><label for="select"></label>
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

  </tr>
  <tr>
  <td colspan="4" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="Load Field Of Study"></td>
  </tr>  
</table>
</form>


<?php
	//----------------------  form_cFOS  -----------------------//
		
		if( isset($_GET['submit']) ) {

			if( !empty($_GET['department']) && !empty($_GET['programme']) ) {
				
				$x = explode(',', $_GET['department']);
				$dept = $x[0];
				$programme = $_GET['programme'];
				
				$load = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM dept_options WHERE dept_id = '.$dept.' && prog_id = '.$programme.'');
				if( 0!=mysqli_num_rows($load) ) {
					
					echo '<form method="post" action="form_eFOS.php?q='.urlencode($_SERVER['QUERY_STRING']).'">';
					echo '<table class="us">';
					echo '<thead>',
							'<tr>',
								'<th>S/N</th>',
								'<th>Field Of Study Name</th>',
								'<th>Duration</th>',
							'</tr>',
						 '</thead><tbody>';
					
					$c = 0;
					while( $data=mysqli_fetch_assoc($load) ) {
						$c++;
						echo '<tr>',
								'<td>',$c,'<input name="do_id[]" type="hidden" value="',$data['do_id'],'"></td>',
								'<td><input name="fosN[]" type="text" value="',$data['programme_option'],'"></td>',
								'<td><select name="duration[]"><option>',$data['duration'],'</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option></select></td>',
							 '</tr>';
					}
					
					echo '<tr>',
							'<td colspan="3"><input name="save" type="submit" value="Save Changes"></td>',
						 '</tr>';
						 
					echo '</tbody></table>';
					echo '</form>';
					
					
					
				}
				
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