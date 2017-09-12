<?php 
	require_once 'inc/header.php';
    require_once '../config.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:450px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 0px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.us { width:70%;}
.us thead{ background:#666; color:#FFF;}
.us thead th, .us tbody td{ border-bottom:1px solid #EEE; padding:5px; text-align:left}
.i{
  margin:0 3px;
  font-style:normal;
  color:#bbb
}

</style>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" autocomplete="off">
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1">
  <tr>
	       
    <td width="" class="td" style="padding-right:20px"><p>Matric No</p><label for="select"></label>
    	<input name="last" type="text" class="input" value="<?php echo @$_GET['last'] ?>">
    </td>

  </tr>
  <tr>
  <td colspan="5" style="text-align:center; padding:10px;"><input name="submit" type="submit" value=" Last Option Student Search"></td>
  </tr>  
</table>

<?php
	if( isset($_SESSION['info']) ) {
		switch( $_SESSION['info'] ) {
			case 1:
			case 11:
				echo '<div style="padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">Action Completed</div>';				
			break;
			case 0:
			case 12:
				echo '<div style="padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">Please Try Again</div>';
			break;
			
		}
		unset( $_SESSION['info'] );
	}


//      delete student profile --------------------
	
	if (isset($_GET['del']) && $_GET['del']!='') {
	$sql = 'Delete From students_profile Where std_id='.$_GET['del'];
	
	$query = mysqli_query( $GLOBALS['connect'], $sql) or die(mysqli_error($GLOBALS['connect']));
	if (mysqli_affected_rows($GLOBALS['connect'])){
		echo '<font color="#FF0000">Delete Of Student Profile Successful</font>';
	}else{
		echo '<font color="#FF0000">Delete Student Profile not Successful</font>';

	}
}

///////////////////////////////////////////////////////////////////////////////


	if( isset($_GET['submit']) && !empty($_GET['last']) ):
		
		
		$dept_title = array();
		$d = mysqli_query( $GLOBALS['connect'], 'SELECT departments_id, departments_name FROM departments' );
		if( 0!=mysqli_num_rows($d) ) {
			while( $dd=mysqli_fetch_assoc($d) ) {
				$dept_title[ $dd['departments_id'] ] = $dd['departments_name'];
			}
			mysqli_free_result($d);
		}
		
		$a = mysqli_query( $GLOBALS['connect'], 'select * from students_profile WHERE students_profile.matric_no like "'.trim($_GET['last']).'%" ');
		
		if( 0==mysqli_num_rows($a) ) {
			echo '<div style="padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">No Student Found</div>';
		} else {

			$c = 0;
			echo '<table class="us"><thead>',
					'<th width="5%">S/N</th>',
					'<th width="20%">FullNames</th>',
					'<th width="10%">Matric No</th>',
					'<th width="15%">Department</th>',
			
'<th width="15%">Action</th>',		
				 '</thead><tbody>';
			while( $r=mysqli_fetch_assoc($a) ) {
				$c++;
				echo '<tr>',
						'<td style="text-align:center">',$c,'</td>',
						'<td>',$r['surname'],' ',trim($r['firstname'].' '.$r['othernames']),'</td>',
						'<td>',$r['matric_no'],'</td>',
						'<td>',$dept_title[ $r['stddepartment_id'] ],'</td>',
						
'<td><a href="search_std_rec.php?del=',$r['std_id'],'">Delete</a></td>',
					 '</tr>';
				
			}
			echo '</tbody></table>';

		}
		
	endif;
?>
  
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
			
			var create_clone = first_element.cloneNode(true);
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
  
<?php require_once 'inc/footer.php'; ?>