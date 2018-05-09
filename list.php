<?php
session_start();
set_time_limit(0);
ini_set('max_execution_time',0);
require_once dirname(__FILE__) . '/config.php';
//$con = mysqli_connect('localhost', 'rootunic_root', 'root1234', 'rootunic_exams');
//$con = mysqli_connect("localhost","unicalex_root","password@**1","unicalex_unicalexams");
$con = mysqli_connect("localhost","root","","unicalnu_examx1");

if (!$con)
  {
  die('Could not connect: ' . mysqli_connect_error());
  }

//mysqli_select_db("unicalex_unicalexams", $con);



/*$do_ids = load_fos($_SESSION['myusername']);
$fos    = array();
foreach ($do_ids as $k => $v) {
    $fos[] = $v['do_id'];
}*/
//$fos
//var_dump($fos);
function students_lists()
{
   // global $fos;
	$return = '';
	$fos =$_GET['fos'];
    
    if (empty($_GET['dept']) || empty($_GET['level']) || empty($_GET['yearsession']) || empty($fos)) {
        return 'This Action Cannot Be Performed Now.. Please Re-logon';
    }
	//echo $_GET['mtlevel'];



	if( $_SESSION['myprogramme_id'] == 7 ) {
// sanwish student 17 is 100 level
	$one_level =17;
				if($_GET['month'] == 1)
				{
                $apend ="-AP";
                $std_custom6="April";
                $new_prob ="2012".$apend;
                //$new_prob_2 ="2014".$apend;
				}else{
                  $apend ="-AG";
                  $std_custom6="August";
                  $new_prob ="2012".$apend;
                 // $new_prob_2 ="2014".$apend;
				}

			$mts_2 = 	$_GET['mtsess'].$apend;

	if( $_GET['mtlevel'] != 17 )	{
				$mtl = ( $_GET['mtlevel'] - 1 );
				$mts = ( $_GET['mtsess'] - 1 );
				$mts =$mts.$apend;
/*$o = mysqli_query($GLOBALS['connect'], 'SELECT sp.std_id, deo.`programme_option`, deo.`do_id`, sp.matric_no, sp.surname, sp.firstname, sp.othernames, sp.stdprogramme_id, sp.stdfaculty_id, sp.stddepartment_id, sp.std_custome2 FROM students_profile as sp, dept_options as deo WHERE 
           sp.stdcourse = deo.`do_id` && sp.`stddepartment_id` = deo.`dept_id` && sp.stddepartment_id = ' . $_GET['dept'] . '  && sp.std_custome1 = "' . $_GET['level'] . '" && std_custome2 = "' . $_GET['yearsession'] . '" && sp.std_custome6 = "' .  $std_custom6 . '" && deo.`do_id` IN (' . implode(',', $fos) . ') && sp.std_id IN (SELECT std_id FROM registered_semester WHERE ysession = "'.$mts.'" && rslevelid ="'.$mtl.'") && sp.std_logid NOT IN (SELECT std_logid FROM studentstatus WHERE studentstatus = "suspension" && suspensionyear <="'.$mts_2.'") ORDER BY surname ASC') or die (mysqli_error($GLOBALS['connect']));*/
				
$o = mysqli_query($GLOBALS['connect'], 'SELECT sp.std_id, deo.`programme_option`, deo.`do_id`, sp.matric_no, sp.surname, sp.firstname, sp.othernames, sp.stdprogramme_id, sp.stdfaculty_id, sp.stddepartment_id, sp.std_custome2 FROM students_profile as sp, dept_options as deo WHERE 
           sp.stdcourse = deo.`do_id` && sp.`stddepartment_id` = deo.`dept_id` && sp.stddepartment_id = ' . $_GET['dept'] . '  && sp.std_custome1 = "' . $_GET['level'] . '" && std_custome2 = "' . $_GET['yearsession'] . '" && sp.std_custome6 = "' .  $std_custom6 . '" && deo.`do_id` ='.$fos.'&& sp.std_id IN (SELECT std_id FROM registered_semester WHERE ysession = "'.$mts.'" && rslevelid ="'.$mtl.'") && sp.std_logid NOT IN (SELECT std_logid FROM studentstatus WHERE studentstatus = "suspension" && suspensionyear <="'.$mts_2.'") ORDER BY surname ASC') or die (mysqli_error($GLOBALS['connect']));




		
		}else
		{

$mtl = ( $_GET['mtlevel']);
				$mts = ( $_GET['mtsess']);
				$mts =$mts.$apend;

			$o = mysqli_query($GLOBALS['connect'], 'SELECT sp.std_id, deo.`programme_option`, deo.`do_id`, sp.matric_no, sp.surname, sp.firstname, sp.othernames, sp.stdprogramme_id, sp.stdfaculty_id, sp.stddepartment_id, sp.std_custome2 FROM students_profile as sp, dept_options as deo WHERE sp.stdcourse = deo.`do_id` && sp.`stddepartment_id` = deo.`dept_id` && sp.stddepartment_id = ' . $_GET['dept'] . ' && sp.std_custome1 = "' . $_GET['level'] . '" && std_custome2 = "' . $_GET['yearsession'] . '" &&  sp.std_custome6 = "' .  $std_custom6 . '" && deo.`do_id` ='.$fos.' && sp.std_logid NOT IN (SELECT std_logid FROM studentstatus WHERE studentstatus = "suspension" && suspensionyear <="'.$mts_2.'") ORDER BY surname ASC') or die (mysqli_error($GLOBALS['connect']));
		


		}

	


	}
elseif( $_SESSION['myprogramme_id'] ==1) {
	$one_level =11;
	$new_prob =2012;
	//$new_prob_2 =2014;

	if( $_GET['mtlevel'] != 11 )	{
				$mtl = ( $_GET['mtlevel'] - 1 );
				$mts = ( $_GET['mtsess'] - 1 );
				
$o = mysqli_query($GLOBALS['connect'], 'SELECT sp.std_id, deo.`programme_option`, deo.`do_id`, sp.matric_no, sp.surname, sp.firstname, sp.othernames, sp.stdprogramme_id, sp.stdfaculty_id, sp.stddepartment_id, sp.std_custome2 FROM students_profile as sp, dept_options as deo WHERE 
           sp.stdcourse = deo.`do_id` && sp.`stddepartment_id` = deo.`dept_id` && sp.stddepartment_id = ' . $_GET['dept'] . '  && sp.std_custome1 = "' . $_GET['level'] . '" && std_custome2 = "' . $_GET['yearsession'] . '" && deo.`do_id` ='.$fos.' && sp.std_id IN (SELECT std_id FROM registered_semester WHERE ysession = "'.$mts.'" && rslevelid ="'.$mtl.'") && sp.std_logid NOT IN (SELECT std_logid FROM studentstatus WHERE studentstatus = "suspension" && suspensionyear <="'.$_GET['mtsess'].'") ORDER BY surname ASC') or die (mysqli_error($GLOBALS['connect']));
		
		}else
		{
			$mtl = $_GET['mtlevel'];
			$mts = $_GET['mtsess'];

			$o = mysqli_query($GLOBALS['connect'], 'SELECT sp.std_id, deo.`programme_option`, deo.`do_id`, sp.matric_no, sp.surname, sp.firstname, sp.othernames, sp.stdprogramme_id, sp.stdfaculty_id, sp.stddepartment_id, sp.std_custome2 FROM students_profile as sp, dept_options as deo WHERE sp.stdcourse = deo.`do_id` && sp.`stddepartment_id` = deo.`dept_id` && sp.stddepartment_id = ' . $_GET['dept'] . ' && sp.std_custome1 = "' . $_GET['level'] . '" && std_custome2 = "' . $_GET['yearsession'] . '" && deo.`do_id`='.$fos.'&& sp.std_logid NOT IN (SELECT std_logid FROM studentstatus WHERE studentstatus = "suspension" && suspensionyear <="'.$_GET['mtsess'].'") ORDER BY surname ASC') or die (mysqli_error($GLOBALS['connect']));
		
		}

}


	else{
$one_level =1;
	
	$new_prob =2012;
   // $new_prob_2 =2014;
	if($_GET['level']=="3")
	{
	$mtl = $_GET['mtlevel'];
				
	$mts = $_GET['mtsess'];
					
		$o = mysqli_query($GLOBALS['connect'], 'SELECT sp.std_id, deo.`programme_option`, deo.`do_id`, sp.matric_no, sp.surname, sp.firstname, sp.othernames, sp.stdprogramme_id, sp.stdfaculty_id, sp.stddepartment_id, sp.std_custome2 FROM students_profile as sp, dept_options as deo WHERE  sp.stdcourse = deo.`do_id` && sp.`stddepartment_id` = deo.`dept_id` && sp.stddepartment_id = ' . $_GET['dept'] . ' && sp.std_custome1 = "' . $_GET['level'] . '" && std_custome2 = "' . $_GET['yearsession'] . '" && deo.`do_id`='.$fos.' && sp.std_logid NOT IN (SELECT std_logid FROM studentstatus WHERE studentstatus = "suspension" && suspensionyear <="'.$_GET['mtsess'].'") ORDER BY surname ASC') or die (mysqli_error($GLOBALS['connect']));
		
		
	}
	else
	{

	if( $_GET['mtlevel'] != 1 )	{
				$mtl = ( $_GET['mtlevel'] - 1 );
				$mts = ( $_GET['mtsess'] - 1 );
	 $sql ='SELECT sp.std_id, deo.`programme_option`, deo.`do_id`, sp.matric_no, sp.surname, sp.firstname, sp.othernames, sp.stdprogramme_id, sp.stdfaculty_id, sp.stddepartment_id, sp.std_custome2 FROM students_profile as sp, dept_options as deo WHERE 
           sp.stdcourse = deo.`do_id` && sp.`stddepartment_id` = deo.`dept_id` && sp.stddepartment_id = ' . $_GET['dept'] . '  && sp.std_custome1 = "' . $_GET['level'] . '" && std_custome2 = "' . $_GET['yearsession'] . '" && deo.`do_id`='.$fos.'&& sp.std_id IN (SELECT std_id FROM registered_semester WHERE ysession = "'.$mts.'" && rslevelid ="'.$mtl.'") && sp.std_logid NOT IN (SELECT std_logid FROM studentstatus WHERE studentstatus = "suspension" && suspensionyear <="'.$_GET['mtsess'].'") ORDER BY surname ASC';
           	
          	
$o = mysqli_query($GLOBALS['connect'],$sql) or die (mysqli_error($GLOBALS['connect']));
		
		}else
		{
			$mtl = $_GET['mtlevel'];
			$mts = $_GET['mtsess'];

			$o = mysqli_query($GLOBALS['connect'], 'SELECT sp.std_id, deo.`programme_option`, deo.`do_id`, sp.matric_no, sp.surname, sp.firstname, sp.othernames, sp.stdprogramme_id, sp.stdfaculty_id, sp.stddepartment_id, sp.std_custome2 FROM students_profile as sp, dept_options as deo WHERE sp.stdcourse = deo.`do_id` && sp.`stddepartment_id` = deo.`dept_id` && sp.stddepartment_id = ' . $_GET['dept'] . ' && sp.std_custome1 = "' . $_GET['level'] . '" && std_custome2 = "' . $_GET['yearsession'] . '" && deo.`do_id`='.$fos.'&& sp.std_logid NOT IN (SELECT std_logid FROM studentstatus WHERE studentstatus = "suspension" && suspensionyear <="'.$_GET['mtsess'].'") ORDER BY surname ASC') or die (mysqli_error($GLOBALS['connect']));
		
		}
		
	
	
	}
}

   
    $return .= '<input style="margin:10px 10px 0" class="bb" name="" type="button" value="Student Lists" onclick="return lss(this, \'s\')">';
	if( $_GET['ignoreprob'] == 'true' ) {
		$return .= '<label style="position:relative;top:3px;"><input name="prob" id="prob" type="checkbox" value="true" onchange="inform()" checked /><span id="chkinfo" style="background:#FEECDF;padding:0px 5px; position:relative; top:-2px; color:#808080">Hide Probation/Withdraw Student</span></label>';
	} else {
		$return .= '<label style="position:relative;top:3px;"><input name="prob" id="prob" type="checkbox" value="true" onchange="inform()" /><span id="chkinfo" style="background:green;padding:0px 5px; position:relative; top:-2px; color:#FFF">Show Probation/Withdraw Student</span></label>';
	}
	
    if (mysqli_num_rows($o) > 0) {
        $add = array();
      
		$return .= '<select name="stds[]" multiple="multiple" class="dd">';
		
		if( isset($_GET['ignoreprob']) && $_GET['ignoreprob'] == 'true' && isset($_GET['mtlevel'], $_GET['mtsess']) ) {
			
			require_once 'include_report.php';
           /*if( $_SESSION['myprogramme_id'] != 7 ) {


			if( $_GET['mtlevel'] != 1 )	{
				$mtl = ( $_GET['mtlevel'] - 1 );
				$mts = ( $_GET['mtsess'] - 1 );
			} else {
				$mtl = $_GET['mtlevel'];
				$mts = $_GET['mtsess'];
			}
		}*/

			while( $r=mysqli_fetch_assoc($o) ) {


if($_GET['mtlevel'] == $one_level)
{
	$add[$r['do_id']][] = $r;
}else{			
// if ur year of entry is less than 2012
if($r['std_custome2'] < $new_prob )

{
$cgpa = get_cgpa($mts, $r['std_id']);
	//$cgpa = adv_get_cgpa($mts, $r['std_id'], $mtl);
		//if($cgpa != 'Credit Unit Is Zero' && $cgpa < 1.00 ) 
		if( $cgpa < 1.00 ) 	
		{

		}else{
			$add[$r['do_id']][] = $r;
		}
	

}else{
	$cgpa = get_cgpa($mts, $r['std_id']);
	
	$fail_cu=get_fail_crunit($mtl,$r['std_id'],$mts);
	if($cgpa >=1.50 && $fail_cu < 15)
	{
	$add[$r['do_id']][] = $r;	
	}
}
}
}
			
		} else {

			while ($r = mysqli_fetch_assoc($o)) {
				$add[$r['do_id']][] = $r;
			}
		}

		
		foreach ($add as $k => $optgrp) {
			$no = 0;
			$return .= '<optgroup label="' . $optgrp[0]['programme_option'] . '">';
			foreach ($optgrp as $r) {
				$no++;
if(($_GET['mtlevel']=="40") && ($_SESSION['myfaculty_id']=="6"))
	{
	

	$result = mysqli_query($GLOBALS['connect'],"SELECT count(*),stdcourse_id,matric_no FROM students_results where std_id=".$r['std_id']." group by  stdcourse_id");
	$st=0;
	
	while($row = mysqli_fetch_array($result))
	{
			$result2=mysqli_query($GLOBALS['connect'],"select * from students_results where std_id='".$r['std_id']."' and stdcourse_id='".$row[1]."'");

		$set="F";

$setb=0;
$dum="";

		while($row2=mysqli_fetch_array($result2))
		{
/*echo $row2['std_grade']."***";*/
			if($row2['std_grade']=="F")
			{
				$set="F";
				$setb++;
			}
			else
			{
				$set="N";
				$setb++;
			}
		}
//echo $set."<hr>";

		if($set=="F")
		{
			//echo "Grade F";
			if($setb>=3)
			{
			
			}
			else
			{
				$st++;
				/*echo $row2[0].":".$st.":".$row[1]."<br>";*/
			}
		}
		else
		{
		//	$st++;
		}
	
	}
	$no++;
	//echo $dum."<br>";
	/*echo "<br>$st--".$r['std_id']."...".$row[1]."<hr>";*/
		if($st<3)
		{
						$return .= '<option value="' . $r['std_id'] . '~' . $r['stdprogramme_id'] . '~' . $r['stdfaculty_id'] . '~' . $r['stddepartment_id'] . '~' . $r['matric_no'] . '">' . $no . '. &nbsp;&nbsp;' . $r['matric_no'] . ' | ' . $r['surname'] . ' ' . $r['firstname'] . ' ' . $r['othernames'] . '</option>';	
		}
		else
		{
		//	$st++;
		}
	
}
else
{
	$return .= '<option value="' . $r['std_id'] . '~' . $r['stdprogramme_id'] . '~' . $r['stdfaculty_id'] . '~' . $r['stddepartment_id'] . '~' . $r['matric_no'] . '">' . $no . '. &nbsp;&nbsp;' . $r['matric_no'] . ' | ' . $r['surname'] . ' ' . $r['firstname'] . ' ' . $r['othernames'] . '</option>';	
}
			}
			$return .= '</optgroup>';
			
		}
  
        $return .= '</select>';
        
    } else {
        $return .= 'Empty Student List Found';
    }
    return $return;
    
}


function course_lists()
{
    //global $fos;
    $fos =$_GET['c_fos'];
   
    
    if (empty($_GET['dept']) || empty($_GET['level']) || empty($_GET['cyear']) || empty($fos)) {
        return 'This Action Cannot Be Performed Now.. Please Re-logon';
    }
    
    $o = mysqli_query($GLOBALS['connect'], 'SELECT all_courses.*, dept_options.* FROM all_courses, dept_options WHERE all_courses.course_custom2 = dept_options.do_id && all_courses.department_id = "' . $_GET['dept'] . '" && all_courses.level_id = "' . $_GET['level'] . '" && all_courses.course_custom5 = "' . $_GET['cyear'] . '" && do_id ='.$fos.' ORDER BY course_status,course_semester, course_code');
      $return =' <select name="c_fos" id="c_fd">';
		 $return .='<option value="">.. Pick Field ..</option>';
		
			$fos = load_fos( $_SESSION['myusername'] );
			foreach( $fos as $r ) {
				$return .='<option value="'.$r['do_id'].'">'.$r['programme_option'].'</option>';
			}
             
      $return .='</select>';
    
    $return .= '<select name="cyear" id="cyear">';
    for ($year = (date('Y') - 1); $year >= 1998; $year--) {
        $yearnext = $year + 1;
        if($_SESSION['myprogramme_id'] == 7)
        {
        	$y =$_GET['cyear'];
  if ($y) {
            $return .= "<option value=\"$y\" selected=\"selected\">$y</option>\n";
        } else {
            $return .= "<option value=\"$y\">$y</option>\n";
        }
        }else{

        if ($_GET['cyear'] == $year) {
            $return .= "<option value=\"$year\" selected=\"selected\">$year/$yearnext</option>\n";
        } else {
            $return .= "<option value=\"$year\">$year/$yearnext</option>\n";
        }
    }
    }
    $return .= "</select>";
    $return .= '<input style="margin:10px 10px 0" class="bb" name="" type="button" value="Courses Lists" onclick="return lss(this, \'c\')">';
    
    if (mysqli_num_rows($o) > 0) {
        $by_fos = array();
        $return .= '<select name="courses[]" multiple="multiple" class="dd">';
        
        while ($r = mysqli_fetch_assoc($o)) {
            $by_fos[$r['course_custom2']][] = $r;
        }
        mysqli_free_result($o);
        
        foreach ($by_fos as $fos) {
            $return .= '<optgroup label="' . $fos[0]['programme_option'] . '">';
            foreach ($fos as $v) {
                $return .= '<option value="' . $v['thecourse_id'] . '~' . $v['course_unit'] . '~' . $v['course_code'] . '~' . $v['course_semester'] . '~' . $v['course_status'] . '">' . substr($v['course_semester'], 0, 1) . ' &raquo; ' . $v['course_code'] . ' | ' . $v['course_unit'] . ' | ' . $v['course_status'] . ' | ' . $v['course_title'] . '</option>';
            }
            $return .= '</optgroup>';
            
        }
        
        
        $return .= '</select>';
        
    } else {
        $return .= 'Empty Courses List Found';
    }
    return $return;
    
}


switch ($_GET['a']) {
    case 1:
        echo students_lists();
        break;
    case 2:
        echo course_lists();
        break;
}

//mysqli_close($GLOBALS['connect']);

?>