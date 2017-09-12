<?php

session_start();
set_time_limit(0);
require_once dirname(__FILE__) . '/config.php';
$con = mysqli_connect("localhost","rootunic_root","root1234","rootunic_exams");
//$con = mysql_connect("localhost","unicalex_root","password@**1");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("unicalnu_examx1", $con);



$do_ids = load_fos($_SESSION['myusername']);
$fos    = array();
foreach ($do_ids as $k => $v) {
    $fos[] = $v['do_id'];
}
//var_dump($fos);
function students_lists()
{
    global $fos;
	$return = '';
    
    if (empty($_GET['dept']) || empty($_GET['level']) || empty($_GET['yearsession']) || empty($fos)) {
        return 'This Action Cannot Be Performed Now.. Please Re-logon';
    }
	//echo $_GET['mtlevel'];
	if($_GET['level']=="3")
	{
		
		$o = mysqli_query($GLOBALS['connect'], 'SELECT sp.std_id, deo.`programme_option`, deo.`do_id`, sp.matric_no, sp.surname, sp.firstname, sp.othernames, sp.stdprogramme_id, sp.stdfaculty_id, sp.stddepartment_id FROM students_profile as sp, dept_options as deo, suspend_student as ss WHERE  sp.stdcourse = deo.`do_id` && sp.std_logid=ss.`login_id` && ss.student_status != "suspension" && ss.suspended_session <="'.$_GET['mtsess'].'" && sp.`stddepartment_id` = deo.`dept_id` && sp.stddepartment_id = ' . $_GET['dept'] . ' && sp.std_custome1 = "' . $_GET['level'] . '" && std_custome2 = "' . $_GET['yearsession'] . '" && deo.`do_id` IN (' . implode(',', $fos) . ')  ORDER BY surname ASC') or die (mysqli_error($GLOBALS['connect']));
		
	}
	else
	{
		
		$o = mysqli_query($GLOBALS['connect'], 'SELECT sp.std_id, deo.`programme_option`, deo.`do_id`, sp.matric_no, sp.surname, sp.firstname, sp.othernames, sp.stdprogramme_id, sp.stdfaculty_id, sp.stddepartment_id FROM students_profile as sp, dept_options as deo, suspend_student as ss WHERE sp.stdcourse = deo.`do_id` && sp.std_logid=ss.`login_id` && ss.student_status != "suspension" && ss.suspended_session <="'.$_GET['mtsess'].'"&& sp.`stddepartment_id` = deo.`dept_id` && sp.stddepartment_id = ' . $_GET['dept'] . ' && sp.std_custome1 = "' . $_GET['level'] . '" && std_custome2 = "' . $_GET['yearsession'] . '" && deo.`do_id` IN (' . implode(',', $fos) . ')  ORDER BY surname ASC') or die (mysqli_error($GLOBALS['connect']));
	
	}
	
    
  //  var_dump($o);
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
			if( $_GET['mtlevel'] != 1 )	{
				$mtl = ( $_GET['mtlevel'] - 1 );
				$mts = ( $_GET['mtsess'] - 1 );
			} else {
				$mtl = $_GET['mtlevel'];
				$mts = $_GET['mtsess'];
			}

			while( $r=mysqli_fetch_assoc($o) ) {
				
				$cgpa = adv_get_cgpa($mts, $r['std_id'], $mtl);
				if( /*$cgpa >= 0.75 &&*/ $cgpa != 'Credit Unit Is Zero' && $cgpa < 1.00 ) 
				//if( /*$cgpa >= 0.75 &&$cgpa != 'Credit Unit Is Zero' && */$cgpa < 1.00 ) 
					
				{}
				else 
				{
					$add[$r['do_id']][] = $r;
					//var_dump($r);
				}
			}
			
		} else {

			while ($r = mysqli_fetch_assoc($o)) {
				$add[$r['do_id']][] = $r;
			}
		}
		
		//var_dump($add);
		foreach ($add as $k => $optgrp) {
			$no = 0;
			$return .= '<optgroup label="' . $optgrp[0]['programme_option'] . '">';
			foreach ($optgrp as $r) {
				$no++;
if(($_GET['mtlevel']=="4") && ($_SESSION['myfaculty_id']=="6"))
	{
	

	$result = mysql_query("SELECT count(*),stdcourse_id,matric_no FROM students_results where std_id=".$r['std_id']." group by  stdcourse_id");
	$st=0;
	
	while($row = mysql_fetch_array($result))
	{
			$result2=mysql_query("select * from students_results where std_id='".$r['std_id']."' and stdcourse_id='".$row[1]."'");

		$set="F";

$setb=0;
$dum="";

		while($row2=mysql_fetch_array($result2))
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
	//echo $dum."<br>";
	/*echo "<br>$st--".$r['std_id']."...".$row[1]."<hr>";*/
		if($st< 3)
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
    global $fos;
    
    if (empty($_GET['dept']) || empty($_GET['level']) || empty($_GET['cyear']) || empty($fos)) {
        return 'This Action Cannot Be Performed Now.. Please Re-logon';
    }
    
    $o = mysqli_query($GLOBALS['connect'], 'SELECT all_courses.*, dept_options.* FROM all_courses, dept_options WHERE all_courses.course_custom2 = dept_options.do_id && all_courses.department_id = "' . $_GET['dept'] . '" && all_courses.level_id = "' . $_GET['level'] . '" && all_courses.course_custom5 = "' . $_GET['cyear'] . '" && do_id IN (' . implode(',', $fos) . ') ORDER BY course_status,course_semester, course_code');
    
    
    $return = '<select name="cyear" id="cyear">';
    for ($year = (date('Y') - 1); $year >= 1998; $year--) {
        $yearnext = $year + 1;
        if ($_GET['cyear'] == $year) {
            $return .= "<option value=\"$year\" selected=\"selected\">$year/$yearnext</option>\n";
        } else {
            $return .= "<option value=\"$year\">$year/$yearnext</option>\n";
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