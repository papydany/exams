<?php
	
	ob_start("ob_gzhandler");
	include_once './config.php';
	include_once './include_report.php';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Delayed Report</title>
<link type="text/css" href="report.css" rel="stylesheet" />
<base target="_blank">
</head>
<body>

<?php

	set_time_limit(0);
	$d = (int)$_GET['department_id'];
	$l = (int)$_GET['s_level'];
	$f = (int)$_GET['faculty_id'];
	$s = (int)$_GET['s_session'];
	$fos = (int)$_GET['course'];
	$c_duration = get_course_duration( $fos );
	$p = empty($_GET['programme']) ? 2 : $_GET['programme'];	
	
	$special = isset($_GET['special']) ? $_GET['special'] : false;
	$status = isset( $_GET['state'] ) ? $_GET['state'] : $l;
	
	
	$level_reps = get_levelreps();
	$faculty_title = G_faculty($f);
	
	
	$fetch_level = $l;
	$fetch_sess = $special ? $s - ($l-$status) : $s;

	if (($f != 6) || ($l != 3 )) {
		echo 'Invalid Selection ';
		exit('Please Retry...');
	}

//report design setting

	if( $l > 1 )
	{

		
		$set['rpt'] = array(0=>'<th>REPEAT COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['carry'] = array(0=>'<th>CARRY OVER COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB">CH</th>');
		$set['cpga'] = array(0=>'<th>CGPA</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		//$set['chr'] = array(1=>'<th class="tB s9 bbt">Repeat/Carryover Result</th>', 2=>'<th class="tB"></th>');
		$set['chr'] = array(1=>'<th class="tB">xvzxvcxc</th>', 2=>'<th class="tB">xcvxcv</th>');
		$set['plus'] = 1;
		$set['wrong_fix'] = '';
		
	} else {
		
		$set['rpt'] = array(0=>'', 1=>'', 2=>'');
		$set['carry'] = array(0=>'', 1=>'', 2=>'');
		$set['cpga'] = array(0=>'', 1=>'', 2=>'');
		//$set['chr'] = array(1=>'', 2=>'');
		$set['plus'] = 0;
		$set['wrong_fix'] = '<p style=" text-align:right;">CH</p>';

	}
	
	if( $special ){
		
		$set['class'] = array(0=>'<th>CLASS OF DEGREE</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['dr'] = 'DEGREE RESULTS';
		
		$set['bottom'] = '<p style="margin-left:50px">
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(HEAD OF DEPT)</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  <p>	
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(DEAN OF '.strtoupper($faculty_title).')</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  <p>	
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(EXTERNAL EXAMINER)</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  
					  <p style="margin-right:0;">	
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(CHAIRMAN SERVC)</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>';
	
	} else {
		
		$set['class'] = array(0=>'', 1=>'', 2=>'');
		$set['dr'] = 'UNDERGRADUATE DELAYED RESULTS';
		
		$set['bottom'] = '<p style="margin-left:50px">
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(HEAD OF DEPT)</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  <p>	
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(DEAN OF '.strtoupper($faculty_title).')</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  <p>	
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(EXTERNAL EXAMINER)</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>
					  
					  <p style="margin-right:0;">	
						  <span>_________________________________</span>
						  <span style="color:#000; padding-left:3px"></span>
						  <span style="color:#000; padding-left:3px; font-size:10px;" class="B">(CHAIRMAN SERVC)</span>
						  <span style="color:#000; padding:20px 0 0 3px; font-size:10px;">DATE: .............................................................</span>
					  </p>';

						  
	}
	
	
//report design setting

	
	$list_courses_first = fetch_courses( $d, $p, $fetch_level, 'first semester', $fetch_sess, $f, $fos );
	$list_courses_sec = fetch_courses( $d, $p, $fetch_level, 'second semester', $fetch_sess, $f, $fos );

$arr1 = array();
$arr2 = array();



foreach( $list_courses_first as $lcf )
	$arr1[] = $lcf['thecourse_id'];

foreach( $list_courses_sec as $lcs )
	$arr2[] = $lcs['thecourse_id'];
	

$aa = count( $list_courses_first );
if( empty($aa) ) {
	$aa = 1;
	$list_courses_first = array('');
}


$ab = count( $list_courses_sec );
if( empty($ab) ) {
	$ab = 1;
	$list_courses_sec = array('');
}

$year_of_study = $level_reps[$l].'/'.$c_duration;
if( $p == 7 || $p == '7') {
	$xsession = $s;
} else {
	$xsession = $s.'/'.($s+1);
}

echo '<div class="bl">
	<div style="text-align:center; margin-bottom:2px;">
		<p class="br" style="font-size:18px; font-weight:700;">UNIVERSITY OF CALABAR</p>
		<p class="br" style="font-size:16px; font-weight:700;">CALABAR</p>
	</div>
	<div style="margin-bottom:2px; display:block; overflow:hidden;">
		<div class="fl">
			<p>FACULTY: ',$faculty_title,'</p>
			<p>DEPARTMENT: ',G_department($d),'</p>
			<p>PROGRAMME: ',G_programme($fos),'</p>
		</div>
		<div class="fr">
			<p>YEAR OF STUDY: ',$year_of_study,'</p>
			<p>SESSION: ',$xsession,'</p>
			<p>SEMESTER: FIRST & SECOND</p>
		</div>
	</div>
	<div style="color:#222;text-align:center; padding:3px 0; background:#f7f7fe; font-weight:700; font-size:14px;">
		<p>EXAMINATION REPORTING SHEET</p>
		<p>',$set['dr'],'</p>
	</div>
	</div>';
	

echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="TABLE">',
	'<thead>',
		'<tr class="thead">',
			'<th>S/N</th>',
			'<th>NAME</th>',
			'<th>REG NO</th>',
			$set['rpt'][0],
			$set['carry'][0],
			//'<th colspan="',$aa+$set['plus']+1,'">FIRST SEMESTER RESULTS</th>',
			//'<th colspan="',$ab+$set['plus']+1,'">SECOND SEMESTER RESULTS</th>',
			'<th colspan="',1,'">FIRST SEMESTER RESULTS</th>',
			'<th colspan="',1,'">SECOND SEMESTER RESULTS</th>',
			'<th>GPA</th>',
			$set['cpga'][0],
			$set['class'][0],
			'<th>REMARKS</th>',
		'</tr>';
	

	echo '<tr class="thead">',
		 '<th></th>',
		 '<th></th>',
		 '<th></th>',
		 '<th></th>';
		// $set['rpt'][1],
		// $set['carry'][1];
	

	if( $aa != 0 || $ab != 0 ) {
		
		//echo $set['chr'][1];
		
		$sizea = $aa; //+ 1;
		$sizeb =  $aa + 1 + $ab + 1;
	
		$k = (int)($aa + $ab) + 1 + 2; // additional 2 is for the two elective spaces
		$list = array_merge( $list_courses_first, array(1=>'elective'), array(1=>''), $list_courses_sec, array(1=>'elective') );
		

		for($i=0; $i<$k; $i++) {

			if( $i == $sizea ) {
				// input 1st elective
				//echo '<th class="tB s9 bbt">Elective</th>';
				continue;
			}
			if( $i == $sizeb ) {
				// input 2nd elective
				//echo '<th class="tB s9 bbt">Elective</th>';
				continue;
			}
			
			if( $i == ($aa + 1) ){
				//echo $set['chr'][1];
			}else {
				//echo '<th class="tB"><p class="ups">',isset($list[$i]['stdcourse_custom2']) ? strtoupper($list[$i]['stdcourse_custom2']) : '','</p></th>';
			}
		}
	
	} else {
		echo '<th></th>';
	}
	
	echo 
		'<th></th>',
		 $set['cpga'][1],
		 $set['class'][1],
		 '<th></th>',
		 '</tr>';		 

	echo '<tr class="thead">',
		 '<th class="tB"></th>',
		 '<th class="tB"></th>',
		 '<th class="tB">',$set['wrong_fix'],'</th>',
		 $set['rpt'][2],
		 $set['carry'][2];

	if( $aa != 0 || $ab != 0 ) {
		//echo $k, $sizea, $sizeb;
		//echo $set['chr'][2];
		
		for($i=0; $i<$k; $i++) {

			if( $i == $sizea ) {
				// input 1st elective
				echo '<th class="tB">Delayed Results</th>';
				continue;
			}
			if( $i == $sizeb ) {
				// input 2nd elective
				echo '<th class="tB">Delayed Results</th>';
				continue;
			}
			
			/*if( $i == ($aa + 1) )
				echo $set['chr'][2];
			else
				echo '<th class="tB">',isset($list[$i]['course_unit']) ? $list[$i]['course_unit'] : '','</th>';*/
		}
	
	} else
		echo '<th></th>';
	
	
	echo '<th class="tB"></th>',
		 $set['cpga'][2],
		 $set['class'][2],
		 '<th class="tB"></th>',
		 '</tr>';		 
	echo '</thead>';
	echo '<tbody>';


$std_off_list = fetch_student_mat( $d, $p, $l, $f, $s, $fos, $special, $c_duration );

$c=0;


$std_off1=$std_off_list;
$std1=array();

function getO($remarkz)
{
	 $remarkz=str_replace(", ",",",$remarkz);
	$rt=explode("TAKE",$remarkz);


if(count($rt)=="2")
{
	if(substr($rt[0], -1)==",")
	{
		$rt[0]=substr($rt[0], 0,-1);
	}
	if(substr($rt[1], -1)==",")
	{
		$rt[1]=substr($rt[1], 0,-1);
	}
	
	$tr=explode(",",$rt[0]);
	$tt=explode(",",$rt[1]);
	//echo count($rt)."**";
	
foreach ($tr as $key => $link)
{
    if ($tr[$key] == '')
    {
        unset($tr[$key]);
    }
}

foreach ($tt as $key => $link)
{
    if ($tt[$key] == '')
    {
        unset($tt[$key]);
    }
}	
	$ctr=count($tr)+0;
	$ctt=count($tt)+0;
}
else
{
	if(substr($rt[0], -1)==",")
	{
		$rt[0]=substr($rt[0], 0,-1);
	}
	
	$tr=explode(",",$rt[0]);
	$tt=0;
foreach ($tr as $key => $link)
{
    if ($tr[$key] == '')
    {
		//echo "empty";
        unset($tr[$key]);
    }
}
	$ctr=count($tr)+0;
	$ctt=0;
}

	//echo "<b>".$str." ".$ctt."</b>";

	if((($ctr+$ctt) <= 3) && !strstr($remarkz, "WITHDRAW"))
	{
		if((($ctr+$ctt) <= 2) && !strstr($remarkz, "WITHDRAW"))
		{
		
			return true;
		}
		if((($ctr+$ctt) == 3) && strstr($remarkz, "GSS") )
		{
			return true;
		}
		else
		{
		//echo "|$ctr :: $ctt |".($ctr+$ctt)." | $remarkz<hr>";
		}
		
	}
	else if((($ctr+$ctt) == 3) && strstr($remarkz, "GSS") )
	{
		return true;
	}
	else
	{
		//echo count($tr)."<hr>";
		return false;
	}
}

if($_GET["spxx"]=="Yes")
{

foreach( $std_off_list as $ind_std ) 
{



				
		
//echo "<hr><hr>";

	$fullname = $ind_std['surname'].' '.$ind_std['firstname'].' '.$ind_std['othernames'];
	//echo '<tr>';
		//echo '<td>',$c,'</td>',
		//	 '<td>',strtoupper($fullname),'</td>',
		//	 '<td>',$ind_std['matric_no'],'</td>';
			 
			 if( $l > 1 )
			 {
								
				#just to aid the get_fake_chr function
				$rpt_list = get_repeatresult_repeat( $l, $s, $ind_std['std_id'] );


				$carryov_list = get_repeatresult_carry_over($l, $s, $p, $f, $d, $ind_std['std_id'], $fos );
				
				//$carryov_list = array();
				
				$grc = get_repeat_courses_111($l, $s, $ind_std['std_id'], $d);
				
//				echo '<td class="s9">',$grc,'</td><td class="s9">',get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos),'</td>';
			 }
			 	
				
			 	#gets student res 4 1st and 2nd semester then merge them
				if( $aa != 0 || $ab != 0 ) 
				{
					
					if( $l > 1 )
							//echo $l."*+<br>";
						//echo '<td class="tB s9">**',get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
					
					$regdcourses = fetch_Ireg($ind_std['std_id'], $s, $l);
					$first_semester = fetch_student_RESULT($ind_std['std_id'], $arr1, $s, $regdcourses);
					$first_semester = empty($first_semester) ? array('') : $first_semester;
					$second_semester = fetch_student_RESULT($ind_std['std_id'], $arr2, $s, $regdcourses);
					$second_semester = empty($second_semester) ? array('') : $second_semester;
					$ll = array_merge($first_semester, array(1=>array()), array(1=>array()), $second_semester, array(1=>array()) );
					
					$electives = std_elective_result( $ind_std['std_id'], $s, $l, $fos );
					
					for($i=0; $i<$k; $i++) {
						
						if( $i == $sizea ) {
							// input 1st elective


///Addition to Remove Repeat Courses from Elective
$bt= (string)get_fake_chrx( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] );
$el=trim((string)str_replace(" ","",$electives[1]));
$el=trim((string)str_replace(","," ",$el));
$el=strip_tags($el);

$pc3=trim((string)str_replace("<br>","",$electives[1]));

$p1 = explode(" ", $bt);
$p2= explode(" ", $el);
$result = array_diff($p2,$p1);
$go1=implode("<br>", $result);

$pos = strpos($bt, $el);

//echo $bt."**".$el."==".$go1."<hr>";


if ($pos == false) 
{
  $ec=$electives[2]."***";
} 
else
{
   $ec="++";
}


/*
$el=trim((string)str_replace(" ","",$electives[1]));
$el=trim((string)str_replace("<br>","",$el));

$pc1=trim((string)str_replace("<br>","",$electives[1]));

$el=strip_tags($el);

$pieces = explode(" ", $bt);
$pc2= explode(" ", $pc1);

$result = array_diff($pieces,$pc2);


$bg="";
for($a=count($result)-2 ; $a>=0 ; $a--)
{
$bg.=" ".$result[$a];

}
*/

/////End Of addition


							//echo '<td class="tB s9">',isset($electives[1]) ? $electives[1] : '','</td>';
							//echo '<td class="tB s9">',isset($go1) ? $go1 : '','</td>';
							continue;
						}
						if( $i == $sizeb ) {
							// input 2nd elective
///Addition to Remove Repeat Courses from Elective

$bt= (string)get_fake_chrx( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] );
$el=trim((string)str_replace(" ","",$electives[2]));
$el=trim((string)str_replace(","," ",$el));
$el=strip_tags($el);

$pc3=trim((string)str_replace("<br>","",$electives[2]));

$p1 = explode(" ", $bt);
$p2= explode(" ", $el);
$result = array_diff($p2,$p1);
$go1=implode("<br>", $result);

$pos = strpos($bt, $el);

//echo $bt."**".$el."==".$go1."<hr>";


if ($pos == false) 
{
  $ec=$electives[2]."***";
} 
else
{
   $ec="++";
}

/////End Of addition


//				echo '<td class="tB s9">',isset($electives[2]) ? $electives[2].'*'.get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ) : '','</td>';
							//echo '<td class="tB s9">',isset($go1) ? $go1 : '','</td>';
							continue;


							//echo '<td class="tB s9">',isset($electives[2]) ? $electives[2] : '','</td>';
							//continue;
						}
						
						if( $i == ($aa + 1) ) {
							if( $l > 1 ) {

								#2 - for second semester and 1 for 1st semester
								//echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
							}
						}
						else {
							
							if( isset($ll[$i]['std_grade']) ) { 

								if( $ll[$i]['std_grade'] == '&nbsp;&nbsp;' ) {
									//echo '<td class="tB" style="background:yellow"></td>';
								} else {
									//echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
								}
							
							} else { //  Jst for GUI purpose
								//echo '<td class="tB"></td>';
							}
						}
					}	
					
				} else {
					
					//echo '<td></td>',
					//	 '<td></td>';
				
				}

		//echo '<td>',get_gpa($s, $ind_std['std_id']),'</td>';
		
				$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
				$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
				
				$finalyear = ( $special ) ? true : false;
				// new sessional result thats why it has tru parameter
				$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true,$fail_cu );
				
				$ignore = substr($remark,0,4) == 'PASS' ? false : true;
				
				if( $l > 1 )
					//echo '<td class="B">',$cgpa,'</td>';
			 	
				#class of degree section
				if( $special ) {
					//echo '<td class="B tc">',G_degree($cgpa, $ignore),'</td>';
				}
			 
		//echo '<td class="s9"><div class="dw">',fiTin( $remark, 200 ),'</div></td>', '</tr>';





$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true,$fail_cu);
		

		if (strstr($remark, "PASS") || getO($remark))
		{
			$fullname = $ind_std['surname'].' '.$ind_std['firstname'].' '.$ind_std['othernames'];
			//echo " || $fullname.<br>";
			$std1[$c]=$ind_std;
					$c++;
		}
		{
			
		}



//////////////////NO END
		



}


print_r($new_array);
$c=0;
$ck=0;

foreach( $std1 as $ind_std ) 
{
$c++;
		


	$fullname = $ind_std['surname'].' '.$ind_std['firstname'].' '.$ind_std['othernames'];
	
	echo '<tr>';
		echo '<td>',$c,'</td>',
			 '<td>',strtoupper($fullname),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>';
			 
			 if( $l > 1 )
			 {
								//echo $l;
				#just to aid the get_fake_chr function
				$rpt_list = get_repeatresult_repeat( $l, $s, $ind_std['std_id'] );


				$carryov_list = get_repeatresult_carry_over($l, $s, $p, $f, $d, $ind_std['std_id'], $fos );
				
				//$carryov_list = array();

				$grc = get_repeat_courses_111($l, $s, $ind_std['std_id'], $d);
				
				echo '<td class="s9">',$grc,'</td>',
				 	 '<td class="s9">',get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos),'</td>';
			 }
			 	
				
			 	#gets student res 4 1st and 2nd semester then merge them
				if( $aa != 0 || $ab != 0 ) {
					
					if( $l > 1 )
						echo '<td class="tB s9">',get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
					
					$regdcourses = fetch_Ireg($ind_std['std_id'], $s, $l);
					$first_semester = fetch_student_RESULT($ind_std['std_id'], $arr1, $s, $regdcourses);
					$first_semester = empty($first_semester) ? array('') : $first_semester;
					$second_semester = fetch_student_RESULT($ind_std['std_id'], $arr2, $s, $regdcourses);
					$second_semester = empty($second_semester) ? array('') : $second_semester;
					$ll = array_merge($first_semester, array(1=>array()), array(1=>array()), $second_semester, array(1=>array()) );
					
					$electives = std_elective_result( $ind_std['std_id'], $s, $l, $fos );
					
					for($i=0; $i<$k; $i++) {
						
						if( $i == $sizea ) {
							// input 1st elective


///Addition to Remove Repeat Courses from Elective
$bt= (string)get_fake_chrx( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] );
$el=trim((string)str_replace(" ","",$electives[1]));
$el=trim((string)str_replace(","," ",$el));
$el=strip_tags($el);

$pc3=trim((string)str_replace("<br>","",$electives[1]));

$p1 = explode(" ", $bt);
$p2= explode(" ", $el);
$result = array_diff($p2,$p1);
$go1=implode("<br>", $result);

$pos = strpos($bt, $el);

//echo $bt."**".$el."==".$go1."<hr>";


if ($pos == false) 
{
  $ec=$electives[2]."***";
} 
else
{
   $ec="++";
}


/*
$el=trim((string)str_replace(" ","",$electives[1]));
$el=trim((string)str_replace("<br>","",$el));

$pc1=trim((string)str_replace("<br>","",$electives[1]));

$el=strip_tags($el);

$pieces = explode(" ", $bt);
$pc2= explode(" ", $pc1);

$result = array_diff($pieces,$pc2);


$bg="";
for($a=count($result)-2 ; $a>=0 ; $a--)
{
$bg.=" ".$result[$a];

}
*/

/////End Of addition


							//echo '<td class="tB s9">',isset($electives[1]) ? $electives[1] : '','</td>';
							echo '<td class="tB s9">',isset($go1) ? $go1 : '','</td>';
							continue;
						}
						if( $i == $sizeb ) {
							// input 2nd elective
///Addition to Remove Repeat Courses from Elective

$bt= (string)get_fake_chrx( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] );
$el=trim((string)str_replace(" ","",$electives[2]));
$el=trim((string)str_replace(","," ",$el));
$el=strip_tags($el);

$pc3=trim((string)str_replace("<br>","",$electives[2]));

$p1 = explode(" ", $bt);
$p2= explode(" ", $el);
$result = array_diff($p2,$p1);
$go1=implode("<br>", $result);

$pos = strpos($bt, $el);

//echo $bt."**".$el."==".$go1."<hr>";


if ($pos == false) 
{
  $ec=$electives[2]."***";
} 
else
{
   $ec="++";
}

/////End Of addition


//				echo '<td class="tB s9">',isset($electives[2]) ? $electives[2].'*'.get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ) : '','</td>';
							echo '<td class="tB s9">',isset($go1) ? $go1 : '','</td>';
							continue;


							//echo '<td class="tB s9">',isset($electives[2]) ? $electives[2] : '','</td>';
							//continue;
						}
						
						if( $i == ($aa + 1) ) {
							if( $l > 1 ) {

								#2 - for second semester and 1 for 1st semester
								echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
							}
						}
						else {
							
							if( isset($ll[$i]['std_grade']) ) { 

								if( $ll[$i]['std_grade'] == '&nbsp;&nbsp;' ) {
									echo '<td class="tB" style="background:yellow"></td>';
								} else {
									echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
								}
							
							} else { //  Jst for GUI purpose
								echo '<td class="tB"></td>';
							}
						}
					}	
					
				} else {
					
					echo '<td></td>',
						 '<td></td>';
				
				}

		echo '<td>',get_gpa($s, $ind_std['std_id']),'</td>';
		
				$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
				$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
				
				$finalyear = ( $special ) ? true : false;
				// new sessional result thats why it has tru parameter
				$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true,$fail_cu  );
				
				$ignore = substr($remark,0,4) == 'PASS' ? false : true;
				
				if( $l > 1 )
					echo '<td class="B">',$cgpa,'</td>';
			 	
				#class of degree section
				if( $special ) {
					echo '<td class="B tc">',G_degree($cgpa, $ignore),'</td>';
				}
			 
		echo '<td class="s9"><div class="dw">',fiTin( $remark, 200 ),'</div></td>', '</tr>';









//////////////////NO END
		



}
$ck=$c;

}
else
{

foreach( $std_off_list as $ind_std ) 
{
//$c++;

/*
$querycusum1 = "SELECT stdresult_id,matric_no FROM students_results
WHERE students_results.std_grade IN ('F','N') 
AND students_results.std_mark_custom1 IN ('First Semester','Second Semester') 
AND students_results.matric_no = '".$ind_std['matric_no']."'
AND students_results.std_mark_custom2 =". $_GET['s_session']."
GROUP BY students_results.stdcourse_id";
	// echo $querycusum1."<br /><br />";
$resultcusum1 = mysqli_query( $GLOBALS['connect'],  $querycusum1 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

while($ccurow = mysqli_fetch_assoc($resultcusum1)){
	
	$custd=$custd.",".$ccurow['stdresult_id'];
}
	
		//echo $custd."<br /><br />";
		
		$querydsum = "SELECT sum(cu) as sumcu FROM students_results where matric_no = '".$ind_std['matric_no']."' AND std_mark_custom2 = ".$_GET['s_session']." AND stdresult_id IN (".substr($custd,1,strlen($custd)).")";
echo $querydsum."<br /><br />";

$resultdsum = mysqli_query( $GLOBALS['connect'],  $querydsum ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
//echo mysqli_num_rows($resultdsum);
$ddsum = mysqli_fetch_assoc($resultdsum);
*/
 //if( $ddsum['sumcu'] > 15 ){
/*
$a_ = mysqli_query( $GLOBALS['connect'], 'SELECT DISTINCT sr.std_id, sp.surname, sp.firstname, sp.othernames, sp.matric_no, sp.stdcourse FROM students_reg as sr LEFT JOIN students_profile as sp USING (std_id) WHERE sr.yearsession = "'.$s.'" && sr.programme_id = '.$p.' && sr.faculty_id='.$f.' && sr.department_id = '.$d.' && sp.stdcourse = '.$fos.' && sr.level_id = '.$l.' && sr.season = "NORMAL" && std_id IN ( SELECT std_id FROM students_reg WHERE students_reg.yearsession = "'.($s-1).'" && students_reg.level_id = '.$l.' && students_reg.department_id = '.$d.' ) ORDER BY sp.matric_no');
		
		
if( 0!=mysqli_num_rows ($a_) ) {
			//echo '<div class="info">No Registered Delay Student Found</div>';
		//}*/
if (( test_result($ind_std['std_id'], $l, ($s-1), 'delay') == 'true' ) && ($l == 3)) {  // this for delayed students
	
	$c++;
	$fullname = $ind_std['surname'].' '.$ind_std['firstname'].' '.$ind_std['othernames'];
	echo '<tr>';
		echo '<td>',$c,'</td>',
			 '<td>',strtoupper($fullname),'</td>',
			 '<td>',$ind_std['matric_no'],'</td>';
			 
			 if( $l > 1 )
			 {
				
				#just to aid the get_fake_chr function
				$rpt_list = get_repeatresult_repeat( $l, $s, $ind_std['std_id'] );


				$carryov_list = get_repeatresult_carry_over($l, $s, $p, $f, $d, $ind_std['std_id'], $fos );
				
				//$carryov_list = array();
				
				$grc = get_repeat_courses_111($l, $s, $ind_std['std_id'], $d);
				
				$delay = take_courses_delay_array($ind_std['std_id'], $l, ($s));
				
				echo '<td class="s9">',$grc,'</td>',
					'<td class="s9">',take_courses_delay($ind_std['std_id'], $l, ($s)),
					'</td>';
					
				 	// '<td class="s9">',get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos),'</td>';
			 }
			 	
				
			 	#gets student res 4 1st and 2nd semester then merge them
				if( $aa != 0 || $ab != 0 ) {
					
					if( $l > 1 )
						echo '<td class="tB s9">',get_fake_chr( 1, $rpt_list, $delay, $s, $ind_std['std_id'] ),
					'</td>';
					
					$regdcourses = fetch_Ireg($ind_std['std_id'], $s, $l);
					$first_semester = fetch_student_RESULT($ind_std['std_id'], $arr1, $s, $regdcourses);
					$first_semester = empty($first_semester) ? array('') : $first_semester;
					$second_semester = fetch_student_RESULT($ind_std['std_id'], $arr2, $s, $regdcourses);
					$second_semester = empty($second_semester) ? array('') : $second_semester;
					$ll = array_merge($first_semester, array(1=>array()), array(1=>array()), $second_semester, array(1=>array()) );
					
					$electives = std_elective_result( $ind_std['std_id'], $s, $l, $fos );
					

					for($i=0; $i<$k; $i++) {
						
						if( $i == $sizea ) {
							// input 1st elective


///Addition to Remove Repeat Courses from Elective
$bt= (string)get_fake_chrx( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] );
$el=trim((string)str_replace(" ","",$electives[1]));
$el=trim((string)str_replace(","," ",$el));
$el=strip_tags($el);

$pc3=trim((string)str_replace("<br>","",$electives[1]));

$p1 = explode(" ", $bt);
$p2= explode(" ", $el);
$result = array_diff($p2,$p1);
$go1=implode("<br>", $result);

@$pos = strpos($bt, $el);

//echo $bt."**".$el."==".$go1."<hr>";


if ($pos == false) 
{
  $ec=$electives[2]."***";
} 
else
{
   $ec="++";
}




							//echo '<td class="tB s9">',isset($electives[1]) ? $electives[1] : '','</td>';
							//echo '<td class="tB s9">',isset($go1) ? $go1 : '','</td>';
							continue;
						}
						if( $i == $sizeb ) {
							// input 2nd elective
///Addition to Remove Repeat Courses from Elective

$bt= (string)get_fake_chrx( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] );
$el=trim((string)str_replace(" ","",$electives[2]));
$el=trim((string)str_replace(","," ",$el));
$el=strip_tags($el);

$pc3=trim((string)str_replace("<br>","",$electives[2]));

$p1 = explode(" ", $bt);
$p2= explode(" ", $el);
$result = array_diff($p2,$p1);
$go1=implode("<br>", $result);

@$pos = strpos($bt, $el);

//echo $bt."**".$el."==".$go1."<hr>";


if ($pos == false) 
{
  $ec=$electives[2]."***";
} 
else
{
   $ec="++";
}

/////End Of addition


//				echo '<td class="tB s9">',isset($electives[2]) ? $electives[2].'*'.get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ) : '','</td>';
							//echo '<td class="tB s9">',isset($go1) ? $go1 : '','</td>';
							continue;


							//echo '<td class="tB s9">',isset($electives[2]) ? $electives[2] : '','</td>';
							//continue;
						}
						
						if( $i == ($aa + 1) ) {
							if( $l > 1 ) {

								#2 - for second semester and 1 for 1st semester
								echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $delay, $s, $ind_std['std_id'] ),'</td>';
							}
						}
						else {
							
							if( isset($ll[$i]['std_grade']) ) { 

								if( $ll[$i]['std_grade'] == '&nbsp;&nbsp;' ) {
									//echo '<td class="tB" style="background:yellow"></td>';
								} else {
									//echo '<td class="tB">',$ll[$i]['std_grade'],'</td>';
								}
							
							} else { //  Jst for GUI purpose
								//echo '<td class="tB"></td>';
							}
						}
					}	
					
				} else {
					
					echo '<td></td>',
						 '<td></td>';
				
				}

		echo '<td>',get_gpa($s, $ind_std['std_id']),'</td>';
		
				$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
			
				
				$finalyear = ( $special ) ? true : false;
				// new sessional result thats why it has tru parameter
				//$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true );
				$remark = result_check_pass($l, $ind_std['std_id'], $s, $cgpa, true,$taketype='');
				
				$ignore = substr($remark,0,4) == 'PASS' ? false : true;
				
				if( $l > 1 )
					echo '<td class="B">',$cgpa,'</td>';
			 	
				#class of degree section
				if( $special ) {
					echo '<td class="B tc">',G_degree($cgpa, $ignore),'</td>';
				}
				
			 
		echo '<td class="s9"><div class="dw">',$remark,'</div></td>',
			 '</tr>';









//////////////////NO END
 		}
	}	


}
	
echo '</tbody></table>';

$none = "";
	echo '<div class="sph block bl" style="margin-top:30px; ">
	<div style="border-bottom:1px solid #000; padding:4px 10px;" class="block B">STATISTICS</div>
	<div class="st block">
	<div><p class="a">No Of Students Registered</p> <p class="b">',get_count_numstd_reg( $d, $s, $l, $c_duration, $fos ),'</p></div>
	<div><p class="a">No of Results Published</p> <p class="b">',$c,'</p></div>
	</div>
	</div>';
// <div><p class="a">No of Results Published</p> <p class="b">',$none,'</p></div>



#signature placeholder
echo '<div class="sph block" style="margin-top:40px;">',$set['bottom'],'</div>';

echo '<div class="sph center" style="text-align:center; font-size:15px; font-weight:700;">Date of Senate Approval :  .......................................................................</div>';



$set = array();
unset($set,$course_duration, $cpga, $remark, $ignore);
mysqli_close($connect);
ob_end_flush();
?>
</body>
</html>

<?php

function take_courses_delay($stdid, $l, $s, $taketype='') 
{
	//$c_duration = get_course_duration( $fos );
	
	$fos = std_course( $stdid ); $take = '';
	//$sql = 'Select stdcourse_custom2 From course_reg Where std_id='.$stdid.' && clevel_id='.$l.' && cyearsession='.$s.' && thecourse_id NOT IN ( Select stdcourse_id From students_results Where std_id='.$stdid.' && level_id='.$l.' && std_mark_custom2='.$s.' )';
	//if (($l == 3) && ($_SESSION['agric_setup'] == true)) { // NEW MODULE: when students dont register for courses in that year
		// -----------------	 GET FOR TAKE FOR YR5 AND YR3 ( DROP COURSES )	
		//$l3 = 3; $s3 = $s - 1; // for year 3, assuming session (2009) - 1 = 2008

      $s3=$s-1; $s2=$s-2;// for two session
		//$sql = 'Select course_code From all_courses Where course_custom2 = '.$fos.' && level_id='.$l3.' && course_custom5='.$s3.' && course_status IN ("C") && thecourse_id NOT IN ( Select stdcourse_id From students_results Where std_id='.$stdid.' && level_id='.$l3.' && std_mark_custom2='.$s3.' )';
		$sql = 'Select course_code From all_courses Where course_custom2 = '.$fos.' && level_id ='.$l.' && course_custom5='.$s.'  && course_status IN ("C") && thecourse_id NOT IN ( Select stdcourse_id From students_results Where std_id='.$stdid.' && level_id <='.($l).' && (std_mark_custom2='.$s3.' || std_mark_custom2='.$s2.') )';
		$q = mysqli_query($GLOBALS['connect'], $sql );
		//$take = '';
		if (0!=mysqli_num_rows($q)) {
			while ($r = mysqli_fetch_assoc($q)){
				//$take .= ', '.substr($r['stdcourse_custom2'],0,3).' '.substr($r['stdcourse_custom2'],3,4);
				$take .= ', '.substr($r['course_code'],0,3).' '.substr($r['course_code'],3,4);
			}
		}
		// ----------------
		
	//}	
		return $take != '' ? substr($take,2) : '';
}
function take_courses_delay_array($stdid, $l, $s, $taketype='') 
{
	//$c_duration = get_course_duration( $fos );
	
	$fos = std_course( $stdid ); $take = array();
	//$sql = 'Select stdcourse_custom2 From course_reg Where std_id='.$stdid.' && clevel_id='.$l.' && cyearsession='.$s.' && thecourse_id NOT IN ( Select stdcourse_id From students_results Where std_id='.$stdid.' && level_id='.$l.' && std_mark_custom2='.$s.' )';
	//if (($l == 3) && ($_SESSION['agric_setup'] == true)) { // NEW MODULE: when students dont register for courses in that year
		// -----------------	 GET FOR TAKE FOR YR5 AND YR3 ( DROP COURSES )	
		//$l3 = 3; $s3 = $s - 1; // for year 3, assuming session (2009) - 1 = 2008

      $s3=$s-1; $s2=$s-2;// for two session
		//$sql = 'Select course_code From all_courses Where course_custom2 = '.$fos.' && level_id='.$l3.' && course_custom5='.$s3.' && course_status IN ("C") && thecourse_id NOT IN ( Select stdcourse_id From students_results Where std_id='.$stdid.' && level_id='.$l3.' && std_mark_custom2='.$s3.' )';
		$sql = 'Select course_code,course_semester From all_courses Where course_custom2 = '.$fos.' && level_id ='.$l.' && course_custom5='.$s.'  && course_status IN ("C") && thecourse_id NOT IN ( Select stdcourse_id From students_results Where std_id='.$stdid.' && level_id <='.($l).' && (std_mark_custom2='.$s3.' || std_mark_custom2='.$s2.') )';
		$q = mysqli_query($GLOBALS['connect'], $sql );
		//$take = '';
		if (0!=mysqli_num_rows($q)) {
			while ($r = mysqli_fetch_assoc($q)){
				//$take .= ', '.substr($r['stdcourse_custom2'],0,3).' '.substr($r['stdcourse_custom2'],3,4);
				$take[]=$r;
				//$take []= ', '.substr($r['course_code'],0,3).' '.substr($r['course_code'],3,4);
			}
			foreach( $take as $k=>$g ) {
				$sem = $g['course_semester'] == 'First Semester' ? 1 : 2;
				$return[] = $sem.' '.$g['course_code'];
			}
		}
		// ----------------
		
	//}	
		return $return;
}

?>