<?php
	session_start();
	
	require_once '../config.php';
	
	$sem_title = array(1=>'First Semester', 2=>'Second Semester');
	
	$session = $_POST['ss'];
	$_SESSION['myss'] = $_POST['ss'];
	$faculty = $_POST['f'];
	$_SESSION['myf'] = $_POST['f'];


	$f = $_POST['f'];
	$semester = $_POST['s'];
	$level = $_POST['l'];
	
	
	$publish = 'Published';
	$dept = $_POST['dept'];
	$qB = '';
	$ins = '';
	$del = '';
	$dpt1 = '';
		
	
	foreach( $_POST['pi'] as $k=>$v) {
		if( !empty($v) && isset($_POST['chk'][$k]) ) {
			$del .= $v.',';
		} else if( !empty($v) && !isset($_POST['chk'][$k]) ) {
			continue;
		}
		else {
			$dpt .= ", '".$dept[$k]. "' ";
			$ins .= '( "'.$sem_title[ $semester ].'", "'.$session.'", "'.$dept[$k].'", "'.$publish.'", "'.$level.'" ),';
		}
		
	}
	
	
	if( !empty($ins) ) { 
		$ins = 'INSERT INTO r_publish( `rp_semester`,  `rp_session`,  `rp_departmentid`,  `rp_status`,  `rp_level` ) VALUES '.$ins;
		$ins = substr($ins, 0, -1);
	}

	
	if( !empty($del) ) {
		$del = substr($del, 0, -1);
		$del = 'DELETE FROM r_publish WHERE rp_id IN ('.$del.')';
	}
	
	$qB = !empty($ins) && !empty($del) ? $ins.';'.$del : $ins.$del;
	
	
	$_e = mysqli_multi_query( $GLOBALS['connect'], $qB);


	if( !empty($dpt)){
	
		$incdept = "(".substr($dpt,1,strlen($dpt)).")";
	
		
		$mydate = date("Y-m-d");

/*	$queryfac = "SELECT departments_id,departments_name,departments.fac_id,r_publish.rp_level,r_publish.rp_session,r_publish.rp_semester,departments.fac_id
FROM departments,r_publish
WHERE departments.departments_id = r_publish.rp_departmentid
AND r_publish.rp_semester = '".$_POST['s']."'
AND departments.fac_id = ".$_POST['f']."
AND r_publish.rp_session = ". $_POST['ss'];
	*/
$queryfac = "SELECT departments.departments_id,departments.departments_name,departments.fac_id,r_publish.rp_level,r_publish.rp_semester,r_publish.rp_session
FROM departments,r_publish
WHERE departments.departments_id = r_publish.rp_departmentid 
AND departments.fac_id ='".$_SESSION['myf']."' AND r_publish.rp_session ='".$_SESSION['myss']."' AND r_publish.rp_level='".$level."' AND r_publish.rp_semester='". $sem_title[ $semester ]."' AND departments.departments_id IN $incdept";
//  echo $queryfac;
$dd22=array();
  $resultfac = mysqli_query( $GLOBALS['connect'], $queryfac) or die(mysqli_error($GLOBALS['connect']));

		
		while($fetchfac = mysqli_fetch_assoc($resultfac)){
  
  $dd22[]=$fetchfac;


  foreach ($dd22 as $key => $value) {

  	# code...
  
 /* $querysearch = "SELECT * FROM students_results, students_reg
WHERE students_results.matric_no = students_reg.matric_no
AND students_results.std_mark_custom2 = ".$value['rp_session']."
AND students_results.std_mark_custom1 = '".$value['rp_semester']."'
AND students_results.level_id = ".$value['rp_level']."
AND students_reg.department_id IN ".$incdept."
GROUP BY students_results.matric_no, students_results.stdcourse_id";

*/
  $querysearch = "SELECT students_results.stdresult_id,students_results.level_id,students_results.std_id,students_results.matric_no,students_results.stdcourse_id,
students_results.std_mark_custom1,students_results.std_mark_custom2,students_results.result_approved,students_results.approval_date
FROM students_results, students_reg
WHERE students_results.matric_no = students_reg.matric_no
AND students_results.std_mark_custom2 = ".$value['rp_session']."
AND students_results.std_mark_custom1 = '".$value['rp_semester']."'
AND students_results.level_id = ".$value['rp_level']."
AND students_reg.department_id IN ".$incdept."
GROUP BY students_results.matric_no, students_results.stdcourse_id";

//echo $querysearch."<br />";


$resultsearch = mysqli_query( $GLOBALS['connect'], $querysearch) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");

		
		while($fetchrow = mysqli_fetch_assoc($resultsearch)){
			//extract($fetchrow);
	
	
	$queryind = "UPDATE students_results set result_approved = 'Y', approval_date = '$mydate' WHERE stdresult_id = " . $fetchrow['stdresult_id'];
		
		
		mysqli_query( $GLOBALS['connect'], $queryind) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
		
			}


		}
	
}
		//}
	}
	



$incdept = "";
	
	if( $_e ) {
		exit('1');
	} else {
		exit('0');
	}
	
	
	

?>