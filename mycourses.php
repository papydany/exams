<?php require_once 'config.php'; 
      require_once('./auth.inc.php');

if(isset($_POST['btnquery']))
{
if ($_POST['btnquery']) {
	$q = mysqli_query( $GLOBALS['connect'], $_POST['strq']) or die('Error: '. mysqli_error());
	if ($q)
	{
		echo  mysqli_affected_rows().'<br>';
		echo 'Execute query successful';
	}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>All My Courses</title>
</head>

<body>
<div >
<style type="text/css">
body { font-size:12px; }
</style>
<form action="mycourses.php" method="post">
	<h3>Run Query Script</h3>
    Enter Query : <textarea name="strq" cols="70" rows="5"><?php echo isset($_POST['strq'])?$_POST['strq']:''; ?></textarea> <input type="submit" value="Run Query" name="btnquery" /> <br /><hr />
</form>
<form action="mycourses.php" method="post">
	<h3>All My Courses / Results Check</h3>
    Enter students MATRIC NO : <input type="text" id="matno" name="matno" value="<?php if(isset($_POST['matno'])){echo $_POST['matno'];
    }?>" /> <input type="submit" value="Verify Course Registration/Result" name="submitc" /> <br /><hr />
</form> 
<form action="mycourses.php" method="post">
	<h3>My Course Code Result Check</h3>
     Enter students MATRIC NO : <input type="text" id="matno2" name="matno2" value="<?php if(isset($_POST['matno2'])){echo $_POST['matno2'];}?>" /> | 
    Enter students COURSE CODE : <input type="text" id="code" name="code" value="<?php if(isset($_POST['code'])){echo $_POST['code']; }?>" /> <input type="submit" value="Verify Result Entry" name="submitd" /> <br /><hr />
</form> 

<?php
if (isset($_GET['gid']) && $_GET['gid']!='') {
	$sql = 'Delete From students_results Where stdresult_id='.$_GET['gid'];
	
	$query = mysqli_query( $GLOBALS['connect'], $sql) or die ('Error:'.mysqli_error());
	if ($query){
		echo '<font color="#FF0000">Grade Delete Successful [ '.$_GET['gid'].']</font>';
	}
}

if (isset($_GET['cid']) && $_GET['cid']!='') {
	$sql = 'Delete From course_reg Where stdcourse_id='.$_GET['cid'];
	
	$query = mysqli_query( $GLOBALS['connect'], $sql);
	if (mysqli_affected_rows($GLOBALS['connect'])){
		echo '<font color="#FF0000">Delete Course Registration Successful </font>';
	}
}

if (isset($_GET['rid']) && $_GET['rid']!='') {
	$sql = 'Delete From students_results Where stdresult_id='.$_GET['rid'];
	
	$query = mysqli_query( $GLOBALS['connect'], $sql);
	if (mysqli_affected_rows($GLOBALS['connect'])){
		echo '<font color="#FF0000">Delete student grade Successful </font>';
	}
}


if (isset($_POST['submitc']) && $_POST['matno']!='') {
	$matno =trim( $_POST['matno']);
	//echo "MATRIC NO: <b>".$matno."</b><br>";
	
	//$sql = "Select *, ac.course_code, ac.course_title From course_reg cr LEFT JOIN all_courses ac ON (cr.thecourse_id = ac.course_id)";
	
	$sql = "Select cr.*, ac.thecourse_code, ac.thecourse_title From course_reg cr LEFT JOIN courses ac ON (cr.thecourse_id = ac.thecourse_id)
	WHERE cr.std_id = (Select std_id FROM students_profile where matric_no = '$matno' LIMIT 1) ORDER BY cyearsession, course_season";
	
	$query = mysqli_query( $GLOBALS['connect'], $sql);
	$no = mysqli_num_rows($query);
	echo 'Total records found: '.$no."<br>";
	$c = 0;
		if( 0!=$no ) {
			
			
$r = mysqli_fetch_assoc($query);


 get_name($matno, $r['std_id']);   
?>
            
            <table border="1" cellspacing="0" cellpadding="1">
			 <tr align="center" style="font-weight:bold">
            	<td>S/No</td>
                <td>Student ID</td>
            	<td>Course Registration ID</td>
                <td>Course Code I</td>
                <td>Course Code II</td>
            	
                <td>Title</td>
                <td>Unit</td>
                <td>Level</td>
                <td>Session</td>
                <td>Semester</td>
                <td>Course Status</td>
                <td>Season</td>
                <td>Result ID | Grade </td>
                 <td>Posted Date</td>
                <td>Posted By</td>
                <td>Action</td>
            </tr><?php 		

            while ($r = mysqli_fetch_assoc($query)) { 
            	//var_dump($r['thecourse_code']);
                $c++; ?>
            <tr>
            	<td><?php echo $c; ?></td>
            	<td><?php echo $r['std_id']?></td>
            	<td><?php echo $r['stdcourse_id'] .'-'.$r['thecourse_id']?></td>
                <td><?php echo $r['thecourse_code']?></td>
                <td><?php echo $r['stdcourse_custom2']?></td>
            	
                <td><?php echo $r['thecourse_title']?></td>
                <td><?php echo $r['c_unit']?></td>
                <td><?php echo $r['clevel_id']?></td>
                <td><?php echo $r['cyearsession']?></td>
                <td><?php echo $r['csemester']?></td>
                <td><?php echo $r['stdcourse_custom3']?></td>
                <td><?php echo $r['course_season'];?></td>
                <?php $grade = get_grade($r['std_id'],$r['thecourse_id'],$r['cyearsession'],$r['clevel_id'],$r['course_season']) ;
				
			   foreach($grade as $rr1){
				   
			   
                 echo"<td>". $rr1['stdresult_id'].' ~ '.$rr1['std_grade'].
               " </td>
				<td>".$rr1['date_posted']."</td>";
				
					
					
				if($officer =get_examofficer_name($rr1['examofficer'])){
					
					echo"<td>",$officer['eo_firstname'],
					"</td>";
				}else{
					echo"<td>
					</td>";
				}
				
			   
				?>
                
                <td><?php 
				//$g = array(); $g = explode('~', $grade);
				// echo '<a href="mycourses.php?cid='.$r['stdcourse_id'].'">Delete Grade</a>';
				echo '<a href="mycourses.php?gid='.$rr1['stdresult_id'].'">Delete Grade</a>';
			
				echo ' | <a href="mycourses.php?cid='.$r['stdcourse_id'].'">Delete Course Registration</a>';
				?></td>
            </tr><?php
        }
    }
			 ?>
            </table>
            
            <?php
			
	}	
	else {
			echo 'No Record(s) Found';
		}
	// ============================]]]]]]]]]]]]]]]]]]]]]]
	
}


if (isset($_POST['submitd']) && $_POST['code']!='' && $_POST['matno2']!='') {
	$code = $_POST['code'];
	$matno2 = $_POST['matno2'];
	echo "MATRIC NO: <b>".$matno2."</b><br>";
	echo "COURSE CODE: <b>".$code."</b><br>";
	
	$sql = "Select * From students_results
	WHERE stdcourse_id = (Select thecourse_id From course_reg Where stdcourse_custom2 = '$code' && std_id = (Select std_id FROM students_profile where matric_no = '$matno2' LIMIT 1) LIMIT 1) && std_id = (Select std_id FROM students_profile where matric_no = '$matno2' LIMIT 1) ORDER BY std_mark_custom2, period";
	//echo $sql;
	$query = mysqli_query( $GLOBALS['connect'], $sql);
	$no = mysqli_num_rows($query);
	echo 'Total records found: '.$no."<br>";
		if( 0!=$no ) {
			
			?>
            
            <table border="1" cellspacing="0" cellpadding="1">
			 <tr align="center" style="font-weight:bold">
            	<td>Student ID</td>
            	<td>Result Entry ID</td>
                <td>Matric No</td>
                <td>Level</td>
            	
                <td>Course Reg ID</td>
                <td>Mark</td>
                <td>Grade</td>
                <td>C Unit</td>
                <td>C Point</td>
                <td>Semester</td>
                <td>Session</td>
                <td>Period</td>
                 <td>Posted Date</td>
                <td>Posted By</td>
                <td>Action</td>
            </tr><?php 
			while ($r = mysqli_fetch_assoc($query)) { ?>
            <tr>
            	<td><?php echo $r['std_id']?></td>
            	<td><?php echo $r['stdresult_id']?></td>
                <td><?php echo $r['matric_no']?></td>
                <td><?php echo $r['level_id']?></td>
            	
                <td><?php echo $r['stdcourse_id']?></td>
                <td><?php echo $r['std_mark']?></td>
                <td><?php echo $r['std_grade']?></td>
                <td><?php echo $r['cu']?></td>
                <td><?php echo $r['cp']?></td>
                <td><?php echo $r['std_mark_custom1']?></td>
                <td><?php echo $r['std_mark_custom2'];?></td>
                <td><?php echo $r['period'] ;?>
                </td>
                <td><?php echo $r['date_posted'];?></td>
                <td>
                <?php if($officer =get_examofficer_name($r['examofficer'])){
					
					echo $officer['eo_firstname'];
				
			
				}
				
				?>
                </td>
                <td><?php echo '<a href="mycourses.php?rid='.$r['stdresult_id'].'">Delete Result Entry</a>';?></td>
            </tr><?php
			} ?>
            </table>
            
            <?php
			
		} else {
			echo 'No Record(s) Found';
		}
	// =====================================+   =============================
	echo '<h3>ALL result from result table</h3>';
	echo "MATRIC NO: <b>".$matno2."</b><br>";
	echo "COURSE CODE: <b>".$code."</b><br>";
	
	$sql = "Select * From students_results
	WHERE std_id = (Select std_id FROM students_profile where matric_no = '$matno2' LIMIT 1) ORDER BY std_mark_custom2, period";
	//echo $sql;
	$query = mysqli_query( $GLOBALS['connect'], $sql);
	$no = mysqli_num_rows($query);
	echo 'Total records found: '.$no."<br>";
		if( 0!=$no ) {
			
			?>
            
            <table border="1" cellspacing="0" cellpadding="1">
			 <tr align="center" style="font-weight:bold">
            	<td>Student ID</td>
            	<td>Result Entry ID</td>
                <td>Matric No</td>
                <td>Level</td>
            	
                <td>Course Reg ID</td>
                <td>Mark</td>
                <td>Grade</td>
                <td>C Unit</td>
                <td>C Point</td>
                <td>Semester</td>
                <td>Session</td>
                <td>Period</td>
                 <td>Posted Date</td>
                <td>Posted By</td>
                <td>Action</td>
            </tr><?php 
			while ($r = mysqli_fetch_assoc($query)) { ?>
            <tr>
            	<td><?php echo $r['std_id']?></td>
            	<td><?php echo $r['stdresult_id']?></td>
                <td><?php echo $r['matric_no']?></td>
                <td><?php echo $r['level_id']?></td>
            	
                <td><?php echo $r['stdcourse_id']?></td>
                <td><?php echo $r['std_mark']?></td>
                <td><?php echo $r['std_grade']?></td>
                <td><?php echo $r['cu']?></td>
                <td><?php echo $r['cp']?></td>
                <td><?php echo $r['std_mark_custom1']?></td>
                <td><?php echo $r['std_mark_custom2'];?></td>
                <td><?php echo $r['period'] ;?>
                </td>
                <td><?php echo $r['date_posted'];?></td>
                <td>
                <?php 
				if($officer =get_examofficer_name($r['examofficer'])){
					
					echo $officer['eo_firstname'];
				}
				?>
                </td>
                <td><?php echo '<a href="mycourses.php?rid='.$r['stdresult_id'].'">Delete Result Entry</a>';?></td>
            </tr><?php
			} ?>
            </table>
            
            <?php
			
		} else {
			echo 'No Record(s) Found';
		}
}


if (isset($_POST['btnquery']) && $_POST['strq']!='') {
$sql = $_POST['strq'];
if ( $r = mysqli_query( $GLOBALS['connect'], $sql))
{
	if (mysqli_num_rows($r)>0)
	{	$data = array();
		while ($row = mysqli_fetch_assoc($r))
		{
			$data[] = $row;
		}
	var_dump ( $data );
	}else{
		echo"no records of students found";
	}
}
}

function get_grade($stdid,$cid,$s,$l,$season) {
	$sql = "Select stdresult_id, std_grade, examofficer, date_posted From students_results Where std_id=$stdid && stdcourse_id=$cid && std_mark_custom2=$s && level_id=$l && period='$season'";
	$query = mysqli_query( $GLOBALS['connect'], $sql);
	//echo $sql;
	$gg=array();
	$r = mysqli_fetch_assoc($query);
	//return $r['stdresult_id'].'~'.$r['std_grade'];
	$gg[]=$r;
	return $gg;
}


function get_name($matric_no,$std_id){

$sql = 'SELECT * FROM  students_profile where matric_no="'.$matric_no.'" && std_id="'.$std_id.'"'; 

$result=mysqli_query($GLOBALS['connect'],$sql) or die(mysqli_error($GLOBALS['connect']));
//echo "$sql";
if(mysqli_num_rows($result) !=0){
    $fet=mysqli_fetch_assoc($result);

 $info= '<p> STUDENT NAME :<b>&nbsp;'.trim($fet['surname'].'&nbsp;'.$fet['firstname'].'&nbsp;'.$fet['othernames']).'</b></p>';
 echo  $info .='<p> MATRIC NO :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$fet['matric_no'].'</b></p>';

   
}


}


function get_examofficer_name($ex_id){
$query1 ='select * from exam_officers where examofficer_id="'.$ex_id.'"';
					
					$result=mysqli_query($GLOBALS['connect'],$query1) or die(mysqli_error($GLOBALS['connect']));
					if(mysqli_num_rows($result) != 0){
						$officer=mysqli_fetch_assoc($result);
					}
					return $officer;
					
					
				
			}

?>
</div>
</body>
</html>