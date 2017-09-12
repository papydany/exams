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

<p style="font-size:16px; font-weight:700; padding:6px 10px 5px; background:#666; display:inline-block; color:#FFFFFF;">Edit Department</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" autocomplete="off">
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1">
  <tr>

	  

    <td width="" class="td"><p>Faculty</p><label for="select2"></label>
      <select name="faculty" id="select2">
      <option value="">--Select faculty--</option>
	<?php
    $l_fac = mysqli_query( $GLOBALS['connect'], 'SELECT `faculties_id`, `faculties_name`, `faculty_code` FROM `faculties` order by faculties_name asc');
    while( $r=mysqli_fetch_assoc($l_fac) ) {
    	echo '<option value="',$r['faculties_id'],'">',$r['faculties_name'],'</option>';
    }
	mysqli_free_result($l_fac);
    ?>
      </select></td>
  </tr>
  <tr>
  <td colspan="4" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="Select"></td>
  </tr>  
</table>
</form>


<?php
 //==== delete script for departments========

if (isset($_GET['delete']) && $_GET['delete']!='') {
	$sql = 'Delete From departments Where departments_id='.$_GET['delete'];
	
	$query = mysqli_query( $GLOBALS['connect'], $sql);
	if (mysqli_affected_rows($GLOBALS['connect'])){
		echo '<p><font color="#FF0000">Delete of selected departement Successful </font></p>';
	}
}
	//---------------------- display scripts for diffrent departments  -----------------------//
		
		if( isset($_POST['submit']) ) {

			if( !empty($_POST['faculty']) ) {
				
				$fac_id =  $_POST['faculty'];

				$fac = mysqli_query( $GLOBALS['connect'], 'SELECT  `faculties_name` FROM `faculties` Where `faculties_id`='.$fac_id);
				$f= mysqli_fetch_assoc($fac);
				
				 $dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments`Where `fac_id`='.$fac_id.' order by departments_name asc');

       $no = mysqli_num_rows($dept);
	
	$c = 0;
		if( 0!=$no ) {
			echo'<form method="post" action="edit_dept_process.php">'; 
			
                echo'<p style="margin-top:5px;margin-bottom:4px;"><b>Departments Under the Faculty Of</b><span style="color:red;margin-left:5px;">'.$f['faculties_name'].'</span>';
           echo' <table class="us" border="" cellspacing="0" cellpadding="0"><thead>
			 <tr align="center" style="font-weight:bold">
			 <th></th>
            	<th>S/No</th>
                <th>Department</th>
            	<th>Action</th>
                </tr><thead><tbody>';


    while( $r=mysqli_fetch_assoc($dept) ) { 
    	$bg = ( $bg == '#FFFFFF' ) ? '#F1F1F1' : '#FFFFFF';
    	$c++;
    	echo "<tr style='background:$bg'>";
    	     echo' <td style="text-align:center;background:#F1f1f1; border-bottom:1px solid #EAEAEA"><input name="s[',$r['departments_id'],']" type="checkbox" value="',$r['departments_name'],'~',$r['fac_id'],'~',$r['departments_id'],'" /></td>';
    	      echo"<td>",$c,"</td>
    	      <td>",$r['departments_name'],"</td>
    	      <td><a href=edit_dept.php?delete=".$r['departments_id'].">Delete</td>
    	      </tr>";
    }
	mysqli_free_result($dept);
	echo'</tbody></table>
	    <div style="width:500px;  padding:5px 0; text-align:center">
		<input name="submit" type="submit" value="EDIT" />
		</div></form>';
  }else{
  	echo'<p>No department found in <span style="color:red;margin-left:5px;">'.$f['faculties_name'].'</span> Faculty';
  }
}
}


			/*	$ins = mysqli_query( $GLOBALS['connect'], 'SELECT  dept_options (`dept_id`,  `programme_option`,  `duration`,  `prog_id`) 
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
	*/
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