<?php 
	require_once 'inc/header.php';
    require_once '../config.php';
?>


<!-- content starts here -->
<style>
a, a:link{ color:#036; text-decoration:none;}
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.us { width:875px; }
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
echo'<div style="margin-bottom:20px;margin-top:10px;"><h3>Students On Academic Suspension</h3></div>';
	echo '<div style="margin-left:338px; margin-bottom:10px; padding:10px; width:250px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">
	<h3><a href="suspend_academic.php">Back To Academic Suspention</a></h3></div>';
			$std_info=array();
		
		    
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM suspend_student WHERE student_status="suspension"') or die(mysqli_error($GLOBALS['connect']));
		if(mysqli_num_rows($ac)==0){
			echo '<div style="padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">No Student is on academic Suspension</div>';
		}else{
        while( $l=mysqli_fetch_assoc($ac) ) {

$std_info[]=$l;



			
       
    }
    $dept_title = array();
		$d = mysqli_query( $GLOBALS['connect'], 'SELECT departments_id, departments_name FROM departments' );
		if( 0!=mysqli_num_rows($d) ) {
			while( $dd=mysqli_fetch_assoc($d) ) {
				$dept_title[ $dd['departments_id'] ] = $dd['departments_name'];
			}
			mysqli_free_result($d);
		}
       $c=0;
     echo '<table class="us"><thead>',
					'<th width="3%">S/N</th>',
					'<th width="20%">FullNames</th>',
					'<th width="11%">Matric No</th>',
					'<th width="18%">Department</th>',
					'<th width="13%">Suspension Session</th>',
'<th width="14%">Status</th>',	
'<th width="9%">Action</th>',	
				 '</thead><tbody>';
	foreach ($std_info as $key => $value) {
		

$std_profile = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM `students_profile` WHERE `std_id`='.$value['std_id'].' && std_logid IN ( SELECT std_logid FROM studentstatus WHERE studentstatus="Suspension" )') or die(mysqli_error($GLOBALS['connect']));
   // echo $std_profile;

    while( $r=mysqli_fetch_assoc($std_profile) ) {
    	$c++;
    $bg = ( $bg == '#FFFFFF' ) ? '#F1F1F1' : '#FFFFFF';
  echo '<tr style=" background:',$bg,'">',
						'<td style="text-align:center;">',$c,'</td>',
						'<td>',$r['surname'],' ',trim($r['firstname'].' '.$r['othernames']),'</td>',
						'<td>',$r['matric_no'],'</td>',
						'<td>',$dept_title[ $r['stddepartment_id'] ],'</td>',
						'<td>',$value['suspended_session'],'</td>',
'<td>',$value['student_status'];if($value['welcome_back_session']!=""){echo '~'.$value['welcome_back_session'];}echo'</td>',
'<td><a href="">Welcome Back</a></td>',
					 '</tr>';

    }


	}
	
	echo '</tbody></table>';
   /* $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments` order by departments_name asc');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['departments_id'],',',$r['fac_id'],'">',$r['departments_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      
      
    
	<?php
    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `programme_id`, `programme_name` FROM `programme`');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['programme_id'],'">',$r['programme_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      </select></td>


    <td width="" class="td"><p>Field Of Study</p><label for="select"></label>
      <select name="course" id="field">
      <option value="">Select</option>
      </select></td>
      
    <td width="" class="td" style="padding-right:20px"><p>Season</p><label for="select"></label>
      <select name="season" >
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
if(isset($_POST['viewall'])){

$sql='SELECT  * FROM  students_profile  WHERE std_id IN (SELECT std_id FROM suspended_student WHERE student_status="Suspension" && std_logid IN (SELECT std_logid FROM studentstatus WHERE studentstatus="Suspension"))'; 
$a = mysqli_query( $GLOBALS['connect'],$sql); 
		




}

	
	
	if( isset($_GET['submit']) && !empty($_GET['department']) && !empty($_GET['level']) && !empty($_GET['course']) && !empty($_GET['ysess']) ):
	
		list( $dept, $fac ) = explode(',', $_GET['department']);
		$a = mysqli_query( $GLOBALS['connect'], 'SELECT DISTINCT sr.std_id, sp.surname, sp.firstname, sp.othernames, sp.matric_no, sp.stdcourse FROM students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.yearsession = "'.$_GET['ysess'].'" && sr.programme_id = '.$_GET['programme'].' && sr.faculty_id='.$fac.' && sr.department_id = '.$dept.' && sp.stdcourse = '.$_GET['course'].' && sr.level_id = '.$_GET['level'].' && sr.season = "'.$_GET['season'].'" ORDER BY sp.matric_no');
		
		if( 0==mysqli_num_rows ($a) ) {
			echo '<div style="padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">No Registered Student Found</div>';
		} else {
			
			echo '<p style="font-size:16px; padding:7px 4px 4px; font-weight:700;" class="us">Level - ',$_GET['level'],' ( Session - ',$_GET['ysess'],' )</p>';
			$c = 0;
			
			echo '<form method="post" onSubmit="return OnSubmitForm(this);">';
			
			echo '<input name="lvl" type="hidden" value="',$_GET['level'],'" />
			<input name="sess" type="hidden" value="',$_GET['ysess'],'" />
			<input name="period" type="hidden" value="',$_GET['season'],'" />
			<input name="querystring" type="hidden" value="',$_SERVER['QUERY_STRING'],'" />';
			
			echo '<table class="us" cellpadding="0" cellspacing="0"><thead>',
					'<th width="3%"></th>',
					'<th width="3%">S/N</th>',
					'<th width="40%">FullNames</th>',
					'<th width="15%">Matric No</th>',
					'<th width="22%">Semester</th>',
					'<th width="25%" >Option</th>',			
				 '</thead><tbody>';
			$bg = '#F1F1F1';
			while( $r=mysqli_fetch_assoc($a) ) {
				$bg = ( $bg == '#FFFFFF' ) ? '#F1F1F1' : '#FFFFFF';
				$c++;
				echo '<tr style=" background:',$bg,'">',
						'<td style="text-align:center;background:#F1f1f1; border-bottom:1px solid #EAEAEA"><input name="s[',$r['std_id'],']" type="checkbox" value="',$r['stdcourse'],'~',$r['matric_no'],'~',$r['surname'],'~',$r['othernames'],'" /></td>',
						'<td style="text-align:center">',$c,'</td>',
						'<td>',$r['surname'],' ',trim($r['firstname'].' '.$r['othernames']),'</td>',
						'<td style="text-align:center; background:#F1f1f1"><a href="set__adv_2.php?s=',$r['std_id'],'&m=',$r['matric_no'],'&p=',$_GET['season'],'">',$r['matric_no'],'</a></td>',
						'<td>First & Second Semester</td>',
						'<td style="text-align:center"><a href="del_reg.php?'.$_SERVER['QUERY_STRING'].'&amp;s='.$r['std_id'].'&amp;c='.$r['stdcourse'].'">Delete Registration</a></td>',
					 '</tr>';			 
				
			}
			echo '</tbody></table>';
			
		echo '<div style="width:875px; margin:0 auto; padding:5px 0; text-align:center">
		<input name="edit" type="submit" value="Edit Result" onClick="document.pressed=this.value" />
		<input name="deleter" type="submit" value="Delete Registration" onClick="document.pressed=this.value" />
		</div>';			

		}*/
		
	//endif;
	}
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
			
			//var create_clone = first_element.cloneNode(true);
			var create_clone = document.createElement("option");
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
    if (document.pressed == 'Delete Registration') {
        form.action = "del_reg.php";
    } else {
        form.action = "set__adv_4.php";
    }
    return true;
}
</script>
  
<?php require_once 'inc/footer.php'; ?>