<?php
	require("inc/header.php");
	include_once '../config.php';
	//include_once './include_report.php';
?>

<!-- content starts here -->
<script language="javascript" type="text/javascript">
function checkform ( form )
{
  if (document.form.s_session.value == "") {
    alert( "Please select Session" );
    document.form.s_session.focus();
    return false ;
  }
  if (document.form.course.value == "") {
    alert( "Please select Field Of Study" );
    document.form.course.focus();
    return false ;
  }
  if (document.form.rtype.value == "") {
    alert( "Please select Report Type" );
    document.form.rtype.focus();
    return false ;
  }
  if (document.form.s_level.value == "") {
    alert( "Please select Level" );
    document.form.s_level.focus();
    return false ;
  }
 
}

</script>
<?php
	unset($_SESSION['s_session'], $_SESSION['s_semester']);
?>

<form name="form" method="get" action="examreportx.php" target="_blank">
  <table width="40%" style="margin:0 auto" border="0" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td>Session</td>
      <td><select name='s_session' id='s_session'>
                              <option value="" selected>Choose A Session</option>
<?php

	for ($year = (date('Y')-1); $year >= 1998; $year--) {

		switch( $_SESSION['myprogramme_id'] ) {
			case 7: case '7':
				echo "<option value='$year'>$year</option>\n";
			break;
			default:
				$yearnext =$year+1;
				echo "<option value='$year'>$year/$yearnext</option>\n";
			break;
		}

	} 
  
?>
</select></td>
    </tr>
     <tr>
      <td>Programme</td>
      <td>
      <select name="programme" id="programme">
      <option value="">--</option>
	<?php
    $l_dept = mysqli_query($GLOBALS['connect'],'SELECT `programme_id`, `programme_name` FROM `programme`');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['programme_id'],'">',$r['programme_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      </select>
      </td>
    </tr>    
    <tr>
      <td>Faculty</td>
      <td>
      <select name="faculty" id="fac" onchange="loadD(this.value)">
      <option value="">Select Faculty</option>
		<?php
        $l_dept = mysqli_query($GLOBALS['connect'],'SELECT `faculties_id`, `faculties_name`, `faculty_code` FROM `faculties` order by `faculties_name` asc');
        while( $r=mysqli_fetch_assoc($l_dept) ) {
            echo '<option value="',$r['faculties_id'],'">',$r['faculties_name'],'</option>';
        }
        mysqli_free_result($l_dept);
        ?>
      </select>
      </td>
    </tr>
    <tr>
      <td>Department</td>
      <td><select name="department" id="dept" onchange="return loadF(this.value)">
      <option value="">--</option>
      </select></td>
    </tr>
     <tr>
      <td>Field of study</td>
      <td><select name="course" id="field" onchange="return loadL(this.value)">
      <option value="">--</option>
      </select></td>
    </tr>   
    <tr>
      <td>Result Type</td>
      <td><select name="rtype" id="rtype">
        <option value="" selected>--</option>
        <?php
				echo <<<REPORTTYPES
		<option value="2" disabled="disabled">Correction</option>
        <option value="4">Long Vacation</option>
        <option value="3">Omitted</option>        
        <option value="0">Probational</option>
        <option value="1">Sessional</option>
        <option value="5" disabled="disabled">Final Year - Regular Only</option>
REPORTTYPES;
		?>
		
      </select></td>
    </tr>
   
    <tr>
      <td>Level</td>
      <td>
      <select name="s_level" id="s_level">
      <option value="" selected>--</option>

</select></td>
    </tr>
        

      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Continue" onClick="return checkform(this);"></td>
    </tr>
  </table>
</form>

<!-- content ends here -->
<?php

	require_once './json.php';
	
	$json = new JSON_obj;
	
	$dump = '';
	$dump2 = '';
	$dump3 = '';
	$dump4 = '';
	
	$l_f = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM `level` ORDER BY `level_name`' );
	$rainy = array();
	if( 0!=mysqli_num_rows($l_f) ) {
		while( $d=mysqli_fetch_assoc($l_f) )  {
			switch( $d['programme_id'] ) {
				case '1':
					$rainy[ $d['programme_id'] ][] = array('idi'=>$d['level_id'], 'namey'=>$d['level_name'].' - Diploma' );
				break;
				case '2':
					$rainy[ $d['programme_id'] ][] = array('idi'=>$d['level_id'], 'namey'=>$d['level_name'] );
				break;
				case '7':
					$rainy[ $d['programme_id'] ][] = array('idi'=>$d['level_id'], 'namey'=>'Contact '.substr($d['level_name'],0,1) );
				break;
			}
			
		}
		mysqli_free_result($l_f);
		$dump4 = $json->encode( $rainy );
	
	}
	
	
	$l_field = mysqli_query( $GLOBALS['connect'], 'SELECT `do_id`, `dept_id`, `programme_option`, `duration`, `prog_id` FROM `dept_options` ORDER BY programme_option');
	$sunny = array();
	$game = array();
	if( 0!=mysqli_num_rows($l_field) ) {
		
		while( $a=mysqli_fetch_assoc($l_field) ) {
			$sunny[$a['dept_id']][] = array('idi'=>$a['do_id'],'namey'=>$a['programme_option']);
			$game[$a['do_id']][] = array('duration'=>$a['duration']);
		}
		mysqli_free_result($l_field);
		$dump = $json->encode($sunny);
		$dump3 = $json->encode($game);
		unset($l_field);
	
	}
	
	$l_field = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments`');
	$sunny = array();
	if( 0!=mysqli_num_rows($l_field) ) {
		
		while( $a=mysqli_fetch_assoc($l_field) ) {
			$sunny[$a['fac_id']][] = array('idi'=>$a['departments_id'],'namey'=>$a['departments_name'], 'fac'=>$a['fac_id']);
		}
		mysqli_free_result($l_field);
		$dump2 = $json->encode($sunny);
		
	}
		
	
?>

<script type="text/javascript" defer="defer">

	var departments = <?php echo $dump2 ?>;
	var fieldofstudy = <?php echo $dump ?>;
	var levels = <?php echo $dump3 ?>;
	var level_titles = <?php echo $dump4 ?>


	function loadD( str ) {
		
		var pos,did,dump,to_fill,first_element;
		
		dump = eval( departments );
		
		fill_count = dump[str].length;
		to_fill = xid("dept");
		
		for( var x = to_fill.options.length - 1; x >= 0; x-- ){
			to_fill.remove(x);
		}
		
		first_element = document.createElement('option');
		first_element.text = 'Select Dept';
		first_element.value = 'true';
		to_fill.options.add(first_element);
				
		for( var i=0; i<fill_count; i++ ) {
			
			var a = dump[str][i];
			var b = surface_key( a );
			
			var create_clone = document.createElement('option');
			create_clone.text = dump[str][i]['namey'];
			create_clone.value = dump[str][i]['idi'];
			to_fill.options.add(create_clone);
	
		}		
	}
	
			
	function loadF( str ) {

		var pos,did,dump,to_fill,first_element;
		
		dump = eval( fieldofstudy );
		
		fill_count = dump[str].length;
		to_fill = xid("field");
		
		for( var x = to_fill.options.length - 1; x >= 0; x-- ){
			to_fill.remove(x);
		}
		
		first_element = document.createElement('option');
		first_element.text = 'Select Field';
		first_element.value = 'true';
		to_fill.options.add(first_element);
				
		for( var i=0; i<fill_count; i++ ) {
			
			var a = dump[str][i];
			var b = surface_key( a );
			
			var create_clone = document.createElement('option');
			create_clone.text = dump[str][i]['namey'];
			create_clone.value = dump[str][i]['idi'];
			to_fill.options.add(create_clone);
	
		}
		
	}
	
	
	
	function loadL( str, progid ) {
		//alert( xid('programme').value );
		progid = xid('programme').value == '' ? 2 : xid('programme').value;
		var pos,did,dump,to_fill,first_element;
		
		dump = eval( levels );
		titles = eval( level_titles );
		
		
		fill_count = dump[str].length;
		to_fill = xid("s_level");
		
		for( var x = to_fill.options.length - 1; x >= 0; x-- ){
			to_fill.remove(x);
		}
		
		first_element = document.createElement('option');
		first_element.text = 'Select Level';
		first_element.value = '';
		to_fill.options.add(first_element);
		var duration = dump[str][0]['duration'];	
		
		var final_year = '';
		var final_year_text = '';
		
		for( var i=0; i<duration; i++) {
			
			var value = titles[progid][i]['idi'];
			var text = titles[progid][i]['namey'];
			
			if( i==(duration-1) ) {
				value = value + 'f';
				text = text + ' (Final)';
				final_year = titles[progid][i]['idi'];
				final_year_text = titles[progid][i]['namey'];
			}
		
			var create_clone = document.createElement('option');
			create_clone.text = text;
			create_clone.value = value;
			to_fill.options.add(create_clone);		
				
		}
			
			if( progid == '2' ) {
			
				var f = parseInt(final_year)+1,s=parseInt(final_year)+2;
				var create_clone = document.createElement('option');
				
				create_clone.text = parseInt(final_year)+1 + '00 (spillover)'; // incomplete tactics
				create_clone.value = f + 's';
				to_fill.options.add(create_clone);	
				
				var create_clone = document.createElement('option');
				
				create_clone.text = parseInt(final_year)+2 + '00 (spillover)'; // incomplete tactics			
				create_clone.value = s + 'x';
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



<?php
  require("inc/footer.php");
?>