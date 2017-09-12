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
<div style="margin-bottom:20px;margin-top:10px;"><h3>Suspension Of Academic</h3></div>
<div style="margin-left:338px; margin-bottom:10px; padding:10px; width:250px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">
	<h3><a href="view_suspended_student.php">View Student On Academic Suspention</a></h3></div>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" >
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1">
  <tr>
    <td width="" class="td"><p>Matric No</p>
      <input type="text" name="mat_no"></td>
      
    <td width="" class="td"><p>Year / Session</p><label for="select2"></label>
      <select name="ysess">
		<?php 
            for ($year = (date('Y')-1); $year >= 1998; $year--) { $yearnext =$year+1;
                echo "<option value='$year'>$year/$yearnext</option>\n";
            } 
        ?> 
      </select></td>    

    <td width="" class="td"><p>Department</p><label for="select2"></label>
      <select name="department" id="select2" onchange="return load_field(this)">
      <option value="">Select</option>
	<?php
    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments` order by departments_name asc');
    while( $r=mysqli_fetch_assoc($l_dept) ) {
    	echo '<option value="',$r['departments_id'],',',$r['fac_id'],'">',$r['departments_name'],'</option>';
    }
	mysqli_free_result($l_dept);
    ?>
      </select></td>

      
    <td width="" class="td"><p>Programme</p><label for="select"></label>
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
  <td colspan="2" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="Suspend Students"></td>
  <td colspan="2" style="text-align:center; padding:10px;"><input name="submit1" type="submit" value="Welcome back Students"></td>
  </tr>  
</table>
</form>


<?php

	
	
	if( isset($_POST['submit']) && !empty($_POST['department']) && !empty($_POST['mat_no']) && !empty($_POST['ysess']) ){
	$d= $_POST['department'];
	$departments = explode(',',$d);
	$dept=$departments[0];
	$fac=$departments[1];
	$suspend_year=$_POST['ysess'];
	$sql='SELECT  std_id, std_logid  FROM students_profile   WHERE stdprogramme_id = "'.$_POST['programme'].'" && stdfaculty_id='.$fac.' && stddepartment_id ='.$dept.' && matric_no="'.trim($_POST['mat_no']).'"';
		$a = mysqli_query( $GLOBALS['connect'],$sql ) or die(mysqli_error($GLOBALS['connect']));
		
		if( 0==mysqli_num_rows ($a) ) {
			echo '<div style="margin-top:10px; padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">No Registered Student with Matric N0 '.$_POST['mat_no'] . ' is Found</div>';

		} else {
			
			
			while( $r=mysqli_fetch_assoc($a) ) {
				$std_id=$r['std_id'];
				$std_logid=$r['std_logid'];

            $sup=mysqli_query($GLOBALS['connect'], 'SELECT * FROM studentstatus  WHERE std_logid='.$std_logid.' && studentstatus="'.suspension.'"') or die(mysqli_error($GLOBALS['connect']));
            if(mysqli_num_rows($sup)==0){

             
            

				$up=mysqli_query($GLOBALS['connect'], 'UPDATE studentstatus SET studentstatus="suspension", suspensionyear='.$suspend_year.' WHERE std_logid="'.$std_logid.'"') or die(mysqli_error($GLOBALS['connect']));
				if(mysqli_affected_rows($GLOBALS['connect']) != 0){
					
				

            $sup2=mysqli_query($GLOBALS['connect'], 'SELECT * FROM suspend_student WHERE std_id='.$std_id.' && login_id='.$std_logid) or die(mysqli_error($GLOBALS['connect']));
            if(mysqli_num_rows($sup2)!=0){


            	$up2=mysqli_query($GLOBALS['connect'], 'UPDATE suspend_student SET student_status="suspension",Welcome_back_session="" WHERE login_id="'.$std_logid.'" && std_id="'.$std_id.'" ') or die(mysqli_error($GLOBALS['connect']));

            	echo '<div style="margin-top:10px; padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">SUCCESSFULL: Academic Suspension for Student with ' .$_POST['mat_no'] . '  Matric No is successfull. </div>'; 

            }else{


					$sql2="INSERT INTO suspend_student(id, login_id, std_id, suspended_session, Welcome_back_session, student_status) VALUES('','$std_logid','$std_id','$suspend_year','','suspension')";

         $ss=mysqli_query($GLOBALS['connect'],$sql2 ) or die(mysqli_error($GLOBALS['connect']));

         	if(mysqli_affected_rows($GLOBALS['connect']) !=0){

         		echo '<div style="margin-top:10px; padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">SUCCESSFULL: Academic Suspension for Student with ' .$_POST['mat_no'] . '  Matric No is successfull. </div>'; 

         	}

				}
				
			}
			
		
		}else{
			echo '<div style="margin-top:10px; padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">  Student with ' .$_POST['mat_no'] . '  Matric No is Already on Academic Suspension. </div>'; 
		}
		
	}
}
}else{
	if( isset($_POST['submit'])){
	echo '<div style="margin-top:10px; padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600"> <h4> Fill the form completely.</h4> </div>'; 
}
		}

	
	//=========================              script for welcome back start here           ========================================

 if( isset($_POST['submit1']) && !empty($_POST['department']) && !empty($_POST['mat_no']) && !empty($_POST['ysess']) ){
	$d= $_POST['department'];
	$departments = explode(',',$d);
	$dept=$departments[0];
	$fac=$departments[1];
	$welcome_year=$_POST['ysess'];
	
	$sql='SELECT  std_id, std_logid  FROM students_profile   WHERE stdprogramme_id = "'.$_POST['programme'].'" && stdfaculty_id='.$fac.' && stddepartment_id ='.$dept.' && matric_no="'.trim($_POST['mat_no']).'"';
		$b = mysqli_query( $GLOBALS['connect'],$sql ) or die(mysqli_error($GLOBALS['connect']));
		
		if( 0==mysqli_num_rows ($b) ) {
			echo '<div style="margin-top:10px; padding:10px; width:567px; background:#FFF; border:1px solid #FFEAB7; color:#000">No Registered Student with Matric No '.$_POST['mat_no'] . ' is Found</div>';

		} else {
			
			
			while( $r=mysqli_fetch_assoc($b) ) {
				$std_id=$r['std_id'];
				$std_logid=$r['std_logid'];

            
         $sp=mysqli_query($GLOBALS['connect'], 'SELECT * FROM studentstatus  WHERE std_logid='.$std_logid.' && studentstatus="'.suspension.'"') or die(mysqli_error($GLOBALS['connect']));
            if(mysqli_num_rows($sp)==0){

        echo '<div style="margin-top:10px; padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">
         <p> The students with Matric No' .$_POST['mat_no'] . 'was never on academic suspension.<br/>so please check  . </p></div>'; 

            }else{

				$up=mysqli_query($GLOBALS['connect'], 'UPDATE studentstatus SET studentstatus="active", suspensionyear="" WHERE std_logid="'.$std_logid.'"') or die(mysqli_error($GLOBALS['connect']));
				
					
					$sql2="UPDATE suspend_student SET Welcome_back_session='$welcome_year', student_status='active' WHERE  login_id='$std_logid' && std_id='$std_id'"; 
                 
         $ss=mysqli_query($GLOBALS['connect'],$sql2 ) or die(mysqli_error($GLOBALS['connect']));
         if(mysqli_affected_rows($GLOBALS['connect']) > 0){

         		echo '<div style="margin-top:10px; padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600"> <p>Welcome back to full Academic  session for Student with ' .$_POST['mat_no'] . '  Matric No . </p></div>'; 
        }
         }
     }

         	

				}
				
			}
			else{
	if( isset($_POST['submit1'])){
	echo '<div style="margin-top:10px; padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600"> <h4> Fill the form completely.</h4> </div>'; 
}
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