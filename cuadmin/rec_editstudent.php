<?php 
	require_once 'inc/header.php';
    require_once '../config.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:260px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:250px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
</style>
<?php
if( isset($_GET['sid']) ):
?>
<div style="font-size:16px; font-weight:700">Edit Student Record</div>
<form action="action.php?<?php echo $_SERVER['QUERY_STRING'] ?>&comm=Pupdate" method="post" autocomplete="off">
  
<table width="880" border="0" cellpadding="0" cellspacing="0" style="padding:10px 20px 20px;" class="field">
<?php
$l = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM students_profile WHERE std_id = '.$_GET['sid'].' LIMIT 1');
$row = mysqli_fetch_assoc($l);
mysqli_free_result($l);
//for() {
	?>
  <tr>
    <td><span>Surname</span><p><input type="text" value="<?php echo $row['surname'] ?>" name="sn[0]" /></p></td>
  </tr>
    <tr><td><span>Firstname</span><p><input type="text" value="<?php echo $row['firstname'] ?>" name="fn[0]" /></p></td></tr>
    <tr><td><span>Othernames</span><p><input type="text" value="<?php echo $row['othernames'] ?>" name="on[0]" /></p></td></tr>
    <tr><td><span>Matric No</span><p><input type="text" value="<?php echo $row['matric_no'] ?>" name="mn[0]" />
    	                         <input type="hidden" value="<?php echo $row['std_id'] ?>" name="sp[0]" />
                                 <input type="hidden" value="<?php echo $row['std_logid'] ?>" name="lg[0]" /></p>
    </td></tr>
    <tr><td><span>Department</span><p><select name="department" id="select2" onchange="return load_field(this)">
    <?php
		$a = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM departments');
		if( 0!=mysqli_num_rows($a) ) {
			 while( $r=mysqli_fetch_assoc($a) ) {
				 if( $row['stddepartment_id']==$r['departments_id'] ) {
				 	echo '<option selected value="',$r['departments_id'],',',$r['fac_id'],'">',$r['departments_name'],'</option>';
				 } else {
				 	echo '<option value="',$r['departments_id'],',',$r['fac_id'],'">',$r['departments_name'],'</option>';
				 }
			 }
			 mysqli_free_result($a);
		}
	?>
    </select></p>
    </td></tr> 

    
    <tr> <td><span>Field Of Study</span><p>
    <select name="course" id="field">
    	<?php
			$a = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM dept_options WHERE do_id = '.$row['stdcourse'].'');
			if( 0!=mysqli_num_rows($a) ) {
				$data = mysqli_fetch_array($a);
				mysqli_free_result($a);
				echo '<option value="',$data['do_id'],'">',$data['programme_option'],'</option>';
			}
		?>
    </select></p>
    </td>    
       
  </tr>
  
    <tr> <td><span>Degree</span><p>
    <select name="degree">
    	<?php
			$a = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM degree');
			if( 0!=mysqli_num_rows($a) ) {
				while($data = mysqli_fetch_array($a) ) {
					
					if( $row['stddegree_id']==$data['degree_id'] ) {
						echo '<option selected value="',$data['degree_id'],'">',$data['degree_name'],'</option>';
					} else {
						echo '<option value="',$data['degree_id'],'">',$data['degree_name'],'</option>';
					}
					
				}
				mysqli_free_result($a);
			}
		?>
    </select></p>
    </td>    
  </tr>
    
  <?php  
//}
?>
  <tr>
  <td ><input name="" type="submit" value="Modify Changes"></td>
  </tr>
</table>
</form>
<?php
endif;
?> 

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
<script type="text/javascript" defer="defer">

	var fieldofstudy = <?php echo $dump ?>;
	
	function load_field( str ) {
		
		var pos,did,dump,to_fill,first_element;
		
		pos = str.value.indexOf(',');
		did = str.value.substr(0, pos);
		
		dump = eval( fieldofstudy );
		
		fill_count = dump[did].length;
		to_fill = xid("field");
		
		for( var x = to_fill.options.length - 1; x >= 0; x-- ){
			to_fill.remove(x);
		}
		
		first_element = document.createElement('option');
		first_element.text = 'Select Field';
		first_element.value = 'true';
		to_fill.options.add(first_element);
				
		for( var i=0; i<fill_count; i++ ) {
			
			var a = dump[did][i];
			var b = surface_key( a );
			
			var create_clone = document.createElement('option');
			create_clone.text = dump[did][i]['namey'];
			create_clone.value = dump[did][i]['idi'];
			to_fill.options.add(create_clone);
	
		}		
	}
function surface_key( obj ) {
	for( key in obj )
		return key;
}
function xid(id) {
	return document.getElementById(id);
}
</script>
  <!-- content ends here -->

  
<?php require_once 'inc/footer.php'; ?>