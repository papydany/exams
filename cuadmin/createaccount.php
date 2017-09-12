<?php 
	require_once 'inc/header.php';
    require_once '../config.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:240px; padding:3px; margin-top:2px;}
input[type="text"]{ width:230px; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.breed{ width:525px;}
</style>

<form action="cacct.php" method="post" autocomplete="off">

<?php
	if( isset($_GET['info']) ) {
		switch( $_GET['info']) {
			case 1:
			case '1':
				echo '<div style="padding:10px; font-size:14px; background:#FFF8E7; border:1px solid #FFEAB7; font-weight:700; color:#D79600">Account Successfully Added</div>';
			break;
			case 0:
			case '0':
				echo '<div style="padding:10px; font-size:14px; background:#FFF8E7; border:1px solid #FFEAB7; font-weight:700; color:#D79600">Failed to create Account</div>';
			break;
		}
	}
?>


<table border="0" cellpadding="0" cellspacing="0" style="background:#f1f1f1">

  <tr>


    <td width="" class="td" colspan="3" style="padding-right:20px; background:#CCC" ><p>Account Type</p>
    	<select name="type"  onchange="return interactive( this )" style="width:767px">
            <option value="3">Departmental Account</option>
        </select>
    </td>
    
  </tr>
   
  <tr id="HHH">

    <td width="" class="td" ><p>Faculty</p><label for="select2"></label>
      <select id="select2" name="fac" onchange="load_dept(this.value)">
      <option></option>
		<?php
        $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `faculties_id`, `faculties_name`, `faculty_code` FROM `faculties` order by `faculties_name` asc');
        while( $r=mysqli_fetch_assoc($l_dept) ) {
            echo '<option value="',$r['faculties_id'],'">',$r['faculties_name'],'</option>';
        }
        mysqli_free_result($l_dept);
        ?>
      </select></td>
      

    <td width="" class="td" style=" "><p>Department</p>
      <select name="department" id="dept" onchange="return load_field(this.value)">
      </select></td>
      
    <td width="" class="td" style="padding-right:21px; "><p>Programme</p>
      <select name="programme">
      	<option></option>
		<?php
        $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `programme_id`, `programme_name` FROM `programme` ORDER BY programme_name');
        while( $r=mysqli_fetch_assoc($l_dept) ) {
            echo '<option value="',$r['programme_id'],'">',$r['programme_name'],'</option>';
        }
        mysqli_free_result($l_dept);
        ?>      
      </select></td>      

  </tr>
  <tr>    

</table>

<table border="0" cellpadding="0" cellspacing="0" style="background:#F1f1f1">
  <td width="" class="td" style="padding-right:22px;"><p>Field Of Study</p>
      <select name="fos[]" id="field" multiple="multiple" style="width:765px;">
      
      </select></td>      

  
  </tr>
  </table>

<table border="0" cellpadding="0" cellspacing="0" style="background:#CCC">
  <tr>


    <td width="" class="td" ><p>FullName</p>
    	<input type="text" name="fullname" />
    </td>

    <td width="" class="td"><p>Username</p>
   		<input type="text" name="username" /> 
    </td>

    <td width="" class="td" style="padding-right:21px;"><p>Password</p>
    	<input type="text" name="password" />
    </td>
  </tr>
  
</table>

<table width="880" border="0" cellpadding="0" cellspacing="0" style="padding:20px 0;" class="field">
  <tr>
  	<td><input name="" type="submit" value="Create Account"></td>
  </tr>
</table>
</form>
  <!-- content ends here -->
  
<?php

	require_once 'json.php';
	
	$json = new JSON_obj;
	
	$l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments`');
	$sunny = array();
	while( $a=mysqli_fetch_assoc($l_dept) ) {
		$sunny[$a['fac_id']][] = array('idi'=>$a['departments_id'],'namey'=>$a['departments_name'],'fac'=>$a['fac_id']);
	}
	$dump = $json->encode($sunny);
	

	$l_field = mysqli_query( $GLOBALS['connect'], 'SELECT `do_id`, `dept_id`, `programme_option`, `duration`, `prog_id` FROM `dept_options`');
	$sunny = array();
	while( $a=mysqli_fetch_assoc($l_field) ) {
		$sunny[$a['dept_id']][] = array('idi'=>$a['do_id'],'namey'=>$a['programme_option']);
	}
	$dump2 = $json->encode($sunny);
	
?>
<script type="text/javascript" defer="defer">

	var fieldofstudy = <?php echo $dump ?>;
	var fieldofstudy2 = <?php echo $dump2 ?>;
	
	function load_dept( str ) {
		
		var pos,did,dump,to_fill,first_element;
		
		dump = eval( fieldofstudy );
		
		fill_count = dump[str].length;
		to_fill = xid("dept");
		
		for( var x = to_fill.options.length - 1; x >= 0; x-- ){
			to_fill.remove(x);
		}
		
		first_element = document.createElement('option');
		to_fill.options.add(first_element);
				
		for( var i=0; i<fill_count; i++ ) {
			
			var a = dump[str][i];
			var b = surface_key( a );
			
			var create_clone = document.createElement("option");
			create_clone.text = dump[str][i]['namey'];
			create_clone.value = dump[str][i]['idi'];
			to_fill.options.add(create_clone);
	
		}		
	}
	
	
	function load_field( str ) {
	
		var pos,did,dump,to_fill,first_element;
		
		//pos = str.value.indexOf(',');
		//did = str.value.substr(0, pos);
	
		dump = eval( fieldofstudy2 );
		
		fill_count = dump[str].length;
		to_fill = xid("field");
		
		for( var x = to_fill.options.length - 1; x >= 0; x-- ){
			to_fill.remove(x);
		}
		
		first_element = document.createElement('option');
		
				
		for( var i=0; i<fill_count; i++ ) {
			
			var a = dump[str][i];
			var b = surface_key( a );
			
			var create_clone = document.createElement("option");
			create_clone.text = dump[str][i]['namey'];
			create_clone.value = dump[str][i]['idi'];
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


	function interactive( a ) {
		var id = document.getElementById('HHH');
		if( a.value == '2' ) {
			id.style.display = "none";
		} else {
			id.style.display = "block";
		}
	}



</script>
  
<?php require_once 'inc/footer.php'; ?>