<?php
	require_once 'inc/header.php';
    require_once 'config.php';
	require_once 'include_report.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.us { width:875px; margin:0 auto;}
.us thead{ background:#666; color:#FFF;}
.us thead th, .us tbody td{ border-bottom:1px solid #FFF; padding:5px; text-align:left}
.i{
  margin:0 3px;
  font-style:normal;
  color:#bbb
}
.us thead th{ text-align:center; color:#222; border-bottom:1px solid #AAA;}
</style>

<?php
	$selected = 'selected="selected"';
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" autocomplete="off">
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1; margin:0 auto;">
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
      <select name="yearsession">
		<?php
			
			$chosen = '';
            for ($year = (date('Y')-1); $year >= 1998; $year--) {
				$chosen = $_GET['yearsession'] == $year ? 'selected="selected"' : '';
			
						$yearnext =$year+1;
						echo "<option value='$year' $chosen>$year/$yearnext</option>\n";
				
				}
  echo'</select></td>';
            } 
		 
        ?> 
    <td width="" class="td"><p>Level</p>
      <select name="level" id="select">
      <option value="">Select</option>
		<?php
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level` WHERE programme_id = '.$_SESSION['myprogramme_id'].' ORDER BY level_name');
		$level = isset($_GET['level']) ? $_GET['level'] : '';
		
		while( $l=mysqli_fetch_assoc($ac) ) {
			
			if( $level == $l['level_id'] ){
				echo '<option value=',$l['level_id'],' ',$selected,'>';
			} else {
				echo '<option value=',$l['level_id'],'>';	
			}
			
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
      
    

    <td width="" class="td"><p>Department</p><label for="select2"></label>
      <?php
	  	echo '<input name="department" type="hidden" value="',$_SESSION['mydepartment_id'],',',$_SESSION['myfaculty_id'],'">';
	  ?>      
      <select disabled="disabled">
	<?php
    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments` WHERE `departments_id` = '.$_SESSION['mydepartment_id'].' order by departments_name asc');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['departments_id'],',',$r['fac_id'],'">',$r['departments_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      </select></td>

      
    <td width="" class="td"><p>Programme</p><label for="select"></label>
    <input name="programme" type="hidden" value="<?php echo $_SESSION['myprogramme_id'] ?>">
      <select disabled="disabled">
	<?php
    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `programme_id`, `programme_name` FROM `programme` WHERE `programme_id` = '.$_SESSION['myprogramme_id'].'');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['programme_id'],'">',$r['programme_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      </select></td>


    <td width="" class="td"><p>Field Of Study</p><label for="select"></label>
      <select name="course" id="field">
      <option value="">Select</option>
		<?php

			$fos = load_fos( $_SESSION['myusername'] );
			foreach( $fos as $r ) {
				if( $_GET['course'] == $r['do_id'] ) {
					echo '<option value="',$r['do_id'],'" selected="selected">',$r['programme_option'],'</option>';
				} else {
					echo '<option value="',$r['do_id'],'">',$r['programme_option'],'</option>';
				}
			}
        ?>        
      </select></td>
      
    <td width="" class="td" style="padding-right:20px"><p>Season</p><label for="select"></label>
      <select name="season" id="season">
      <option value="NORMAL">NORMAL</option>
      <option value="VACATION">VACATION</option>
      </select></td>
            
  </tr>
  <tr>
  <td colspan="6" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="View Registered Students"></td>
  </tr>  
</table>
</form>

<?php

	
	
	
	if( isset($_GET['submit']) && !empty($_GET['department']) && !empty($_GET['level']) && !empty($_GET['course']) && !empty($_GET['yearsession']) ):
	
		list( $dept, $fac ) = explode(',', $_GET['department']);

	$thevalue = isset($_GET['month'])?$_GET['month']:'';
	if(!$thevalue == ""){
	$thevalue =$thevalue == 1?"April":"August";
}

	if($_SESSION['myprogramme_id'] == 7)
	{
		$year_session = $_GET['yearsession'];
		$year_session =substr_replace($year_session,"",4);
		$less_session =$year_session-1;

		$query='SELECT DISTINCT sr.std_id, sp.surname, sp.firstname, sp.othernames, sp.matric_no, sp.stdcourse FROM students_reg as sr LEFT JOIN students_profile as sp USING (std_id) WHERE sr.yearsession = "'.$_GET['yearsession'].'" && sr.programme_id = '.$_GET['programme'].' && sr.faculty_id='.$fac.' && sr.department_id = '.$dept.' && sp.stdcourse = '.$_GET['course'].' && sp.std_custome6 ="' . $thevalue  .'" && sr.level_id = '.$_GET['level'].' && sr.season = "'.$_GET['season'].'" ORDER BY sp.matric_no';


	}else
	{
		$less_session =$_GET['yearsession']-1;
		$query='SELECT DISTINCT sr.std_id, sp.surname, sp.firstname, sp.othernames, sp.matric_no, sp.stdcourse FROM students_reg as sr LEFT JOIN students_profile as sp USING (std_id) WHERE sr.yearsession = "'.$_GET['yearsession'].'" && sr.programme_id = '.$_GET['programme'].' && sr.faculty_id='.$fac.' && sr.department_id = '.$dept.' && sp.stdcourse = '.$_GET['course'].' &&  sr.level_id = '.$_GET['level'].' && sr.season = "'.$_GET['season'].'" ORDER BY sp.matric_no';
	}
	
		$a = mysqli_query( $GLOBALS['connect'], $query) or die(mysqli_error($GLOBALS['connect']));
		if( 0==mysqli_num_rows ($a) ) {
			echo '<div class="info">No Registered Student Found</div>';
		} else {
			
			$title = $_SESSION['myprogramme_id'] == 7 ? 'Level '.GetlLevel($_GET['level']) : 'Level - '.GetlLevel($_GET['level']);
			echo '<p style="font-size:16px; padding:7px 4px 4px; font-weight:700;" class="us">',$title,' ( Session - ',$_GET['yearsession'],' )</p>';
			$c = 0;
			
			echo '<form method="post" onSubmit="return OnSubmitForm(this);">';
			
			echo '<input name="lvl" type="hidden" value="',$_GET['level'],'" />
			<input name="sess" type="hidden" value="',$_GET['yearsession'],'" />
			<input name="period" type="hidden" value="',$_GET['season'],'" />
			<input name="querystring" type="hidden" value="',$_SERVER['QUERY_STRING'],'" />';
			
			echo '<table class="us" cellpadding="0" cellspacing="0"><thead>',
					'<th width="3%"><input name="" type="checkbox" value="" onchange="return checkUncheckAll(this)" /></th>',
					'<th width="3%">S/N</th>',
					'<th width="28%">FullNames</th>',
					//'<th width="10%">Edit Control</th>',
					'<th width="13%">Matric No</th>',
					'<th width="20%">Semester</th>',
					'<th width="38%" >Option</th>',			
				 '</thead><tbody>';
			$bg = '#F1F1F1';
			while( $r=mysqli_fetch_assoc($a) ) {
				//var_dump($r);

	if($_SESSION['myprogramme_id'] == 7)
	{
						$bg = ( $bg == '#FFFFFF' ) ? '#F1F1F1' : '#FFFFFF';
				$c++;

				
					
echo '<tr style=" background:',$bg,'">',
							'<td style="text-align:center;background:#F1f1f1; border-bottom:1px solid #EAEAEA"><input name="s[',$r['std_id'],']" type="checkbox" value="',$r['stdcourse'],'~',$r['matric_no'],'~',$r['surname'],'~',$r['othernames'],'" /></td>',
							'<td style="text-align:center">',$c,'</td>',
							'<td><b>',$r['surname'],',</b> ',trim($r['firstname'].' '.$r['othernames']),'</td>',
							//'<td>hhhhhh</td>',
							'<td style="text-align:center; background:#F1f1f1"><a href="set__adv_2.php?s=',$r['std_id'],'&m=',$r['matric_no'],'&p=',$_GET['season'],'">',$r['matric_no'],'</a></td>',
							'<td>First & Second Semester</td>',
							'<td style="text-align:center"><a href="correct_result.php?s=',$r['std_id'],'&m=',$r['matric_no'],'&p=',$_GET['season'],'&sess=',$_GET['yearsession'],'">Correctional Result</a> | <a href="del_reg.php?'.$_SERVER['QUERY_STRING'].'&amp;s='.$r['std_id'].'&amp;c='.$r['stdcourse'].'" onClick="return confirmLink(this, \'Delete This Student Registration.\')" >Delete Registration</a></td>',
						 '</tr>';

	}else{
				$welcome=welcomeback($r['std_id'],$_GET['yearsession']);
				$delay = test_result($r['std_id'], $_GET['level'], $less_session, 'delay');
				//if ($delay != 'true' && !in_array($r['std_id'],$welcome)) {
					//$delay_ls[] = $k;// echo 'id'.$k.'delay='.$delay.'---';
				//}
//var_dump($welcome);
				$bg = ( $bg == '#FFFFFF' ) ? '#F1F1F1' : '#FFFFFF';
				$c++;

				if ($delay != 'true'){
					
echo '<tr style=" background:',$bg,'">',
							'<td style="text-align:center;background:#F1f1f1; border-bottom:1px solid #EAEAEA"><input name="s[',$r['std_id'],']" type="checkbox" value="',$r['stdcourse'],'~',$r['matric_no'],'~',$r['surname'],'~',$r['othernames'],'" /></td>',
							'<td style="text-align:center">',$c,'</td>',
							'<td><b>',$r['surname'],',</b> ',trim($r['firstname'].' '.$r['othernames']),'</td>',
							//'<td>hhhhhh</td>',
							'<td style="text-align:center; background:#F1f1f1"><a href="set__adv_2.php?s=',$r['std_id'],'&m=',$r['matric_no'],'&p=',$_GET['season'],'">',$r['matric_no'],'</a></td>',
							'<td>First & Second Semester</td>',
							'<td style="text-align:center"><a href="correct_result.php?s=',$r['std_id'],'&m=',$r['matric_no'],'&p=',$_GET['season'],'&sess=',$_GET['yearsession'],'">Correctional Result</a> | <a href="del_reg.php?'.$_SERVER['QUERY_STRING'].'&amp;s='.$r['std_id'].'&amp;c='.$r['stdcourse'].'" onClick="return confirmLink(this, \'Delete This Student Registration.\')" >Delete Registration</a></td>',
						 '</tr>';	 
			}elseif($delay == 'true' && $_GET['season'] == 'VACATION' && $_GET['level'] =='3') {

echo '<tr style=" background:',$bg,'">',
							'<td style="text-align:center;background:#F1f1f1; border-bottom:1px solid #EAEAEA"><input name="s[',$r['std_id'],']" type="checkbox" value="',$r['stdcourse'],'~',$r['matric_no'],'~',$r['surname'],'~',$r['othernames'],'" /></td>',
							'<td style="text-align:center">',$c,'</td>',
							'<td><b>',$r['surname'],',</b> ',trim($r['firstname'].' '.$r['othernames']),'</td>',
							//'<td>hhhhhh</td>',
							'<td style="text-align:center; background:#F1f1f1"><a href="set__adv_2.php?s=',$r['std_id'],'&m=',$r['matric_no'],'&p=',$_GET['season'],'">',$r['matric_no'],'</a></td>',
							'<td>First & Second Semester</td>',
							'<td style="text-align:center"><a href="correct_result.php?s=',$r['std_id'],'&m=',$r['matric_no'],'&p=',$_GET['season'],'&sess=',$_GET['yearsession'],'">Correctional Result</a> | <a href="del_reg.php?'.$_SERVER['QUERY_STRING'].'&amp;s='.$r['std_id'].'&amp;c='.$r['stdcourse'].'" onClick="return confirmLink(this, \'Delete This Student Registration.\')">Delete Registration</a></td>',
						 '</tr>';	
			}
		}

		}

			echo '</tbody></table>';
			
		echo '<div style="width:875px; margin:0 auto; padding:5px 0; text-align:center">
		<input name="edit" type="submit" value="Edit Result" onClick="document.pressed=this.value" />
		<input name="deleter" type="submit" value="Delete Registration" onClick="document.pressed=this.value" />
		<input name="print" type="submit" value="Print Result" onClick="document.pressed=this.value" />
		</div>';			

		}
		
	endif;
	
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

function OnSubmitForm(form) {
	
	var chkbx = document.getElementsByTagName('input');
	var rgo = false;
	var size=chkbx.length;
	for( var i=0; i<size; i++) {
		if( rgo ){break;}
		if( chkbx[i].type == 'checkbox' && chkbx[i].checked == true ) {
			rgo = true;
		} else {
			rgo = false;
		}
	}
			
    if (document.pressed == 'Delete Registration') {
	
		if( !rgo ){ return false;}
		else { 
			if( confirm('Are You Sure You Want to.. Delete These Students Registration') ) {
				form.action = "del_reg.php";
			} else {return false } 		
		}
        
    } else if ( document.pressed == 'Print Result' ) {
		if( !rgo ){ return false;}
		else { 
			var xxx=document.getElementById("season").value;
		
			form.target = "_blank";
			form.action = "result_slip.php?period="+xxx;
		}		
	} else {
		if( !rgo ){ return false;}
		else { 
			form.action = "set__adv_4.php";
		}
    }
    return true;
}
</script>
  
<?php require_once 'inc/footer.php'; ?>
<script type="text/javascript" src="js/get_session_sandwich.js"></script>