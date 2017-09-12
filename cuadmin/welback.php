<?php
require_once './config.php';

$welcome_year=$_POST['ysess'];
if($welcome_year==""){
echo '<div style="margin-top:10px; padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600"> <p>Welcome back to full Academic  session for Student with ' .$_POST['mat_no'] . '  Matric No . </p></div>'; 
        }
}else{

if( isset($_POST['submit1']) && !empty($_POST['department']) && !empty($_POST['mat_no']) && !empty($_POST['ysess']) ){
	$d= $_POST['department'];
	$departments = explode(',',$d);
	$dept=$departments[0];
	$fac=$departments[1];
	
	
			
			while( $r=mysqli_fetch_assoc($b) ) {
				$std_id=$r['std_id'];
				$std_logid=$r['std_logid'];

         		$up=mysqli_query($GLOBALS['connect'], 'UPDATE studentstatus SET studentstatus="active", suspensionyear="" WHERE std_logid="'.$std_logid.'"') or die(mysqli_error($GLOBALS['connect']));
				
					
					$sql2="UPDATE suspend_student SET Welcome_back_session='$welcome_year', student_status='active' WHERE  login_id='$std_logid' && std_id='$std_id'"; 
                 
         $ss=mysqli_query($GLOBALS['connect'],$sql2 ) or die(mysqli_error($GLOBALS['connect']));
         if(mysqli_affected_rows($GLOBALS['connect']) > 0){

         		echo '<div style="margin-top:10px; padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600"> <p>Welcome back to full Academic  session for Student with ' .$_POST['mat_no'] . '  Matric No . </p></div>'; 
        }
         }
     }
 }