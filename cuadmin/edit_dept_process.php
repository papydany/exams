<?php 
	require_once 'inc/header.php';
    require_once '../config.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:400px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.us { width:535px;margin-top:10px; }
.us thead{ background:#666; color:#FFF;}
.us thead th, .us tbody td{ border-bottom:1px solid #EEE; padding:5px; text-align:left}
.i{
  margin:0 3px;
  font-style:normal;
  color:#bbb
}
.info{
	padding-top:50px;
	
}
</style>
<?php

	
	
	if(isset($_POST['submit1'],$_POST['departments_id'])){
		$dpn=$_POST['departments_name'];
		//$fac_id=$_POST['fac_id'];
		$dp_id=$_POST['departments_id'];
	     
	     $affected_rows=0;

		foreach ($dp_id  as $key => $value) {
		 	
		 	$up = mysqli_query( $GLOBALS['connect'], 'UPDATE departments SET departments_name = "'.$dpn[$key].'" WHERE departments_id = "'.$value.'"') or die(mysqli_error());
			if( mysqli_affected_rows($GLOBALS['connect'])>0 ){
					$affected_rows++;
			}
		}
		if(	$affected_rows > 0){
				echo'<p> update successfull</p>
				     <p><a href="edit_dept.php">Click Here to Return</a></p>';
			}else{
				echo 'No Record was updated
				    <p> <a href="edit_dept.php">Click Here to Return</a></p>';
			}	
	
}

	$sls = '';
	
	if( isset($_POST['s']) ) {
		
		$sls = $_POST['s'];

}else {
		echo '<div class="info">Return to Edit Department To Restart This Action <a href="edit_dept.php">Click Here to Return</a></div>';
		exit;
	}
	if(!empty($sls)){
		$c=0;

    echo'<h3 style="font-size:16px; font-weight:700; padding:6px 10px 5px; background:#666; display:inline-block; color:#FFFFFF;">Edit Department</h3>
    
    

	<form method="post" action="'.$_SERVER['PHP_SELF'].'" >
	  <table class="us" border="" cellspacing="0" cellpadding="0"><thead>
			 <tr align="center" style="font-weight:bold">
            	<th>S/No</th>
                <th>Department</th>
                </tr><thead><tbody>';
	foreach ($sls as $key => $value) {

			$bgcolor = $bgcolor == '#FBFCFD' ? '#FFFFFF' : '#FBFCFD';

		$ex=explode('~', $value);
$c++;
    	echo "<tr style='background:$bgcolor'>";
    	     echo'<input name="departments_id[]	" type="hidden" value="',$ex[2],'" />
    	      <td>',$c,'</td>
    	      <td><input name="departments_name[]" type="text" value="',$ex[0],'" /></td>
    	      </tr>';
	}

	echo'</tbody></table>
	    <div style="width:500px;  padding:5px 0; text-align:center">
		<input name="submit1" type="submit" value="UPDATE" />
		<a href="edit_dept.php"><input type="botton" value="BACK"/></a>
		</div></form>';
	}
 

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