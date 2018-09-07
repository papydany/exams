<?php
	session_start();
	
	ob_start("ob_gzhandler");
	include_once './config.php';
	include_once './include_report.php';
	//include_once './include_report_ext.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sessional Report</title>
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
	$terminal =$c_duration + 2;
	//var_dump($special);
	$fetch_level = $l;
	$fetch_sess = $special ? $s - ($l-$status) : $s;
//------------------ Facuty of Agric and the like module setup
$_SESSION['mydept'] = $d;
$_SESSION['myfac'] = $f;
//$_SESSION['agric_setup'] = (($d == 25) || ($f == 6))? true: false;
$_SESSION['agric_setup'] = ($f == 6)? true: false;
$new_prob =2012;
$new_prob1 =2014;
//------------------- END SETUP------------------------
//report design setting

	if( $l > 1 )
	{

		
		$set['rpt'] = array(0=>'<th>REPEAT COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['carry'] = array(0=>'<th>CARRY OVER COURSES</th>', 1=>'<th></th>', 2=>'<th class="tB">CH</th>');
		$set['cpga'] = array(0=>'<th>CGPA</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		$set['chr'] = array(1=>'<th class="tB s9 bbt">Repeat/Carryover Result</th>', 2=>'<th class="tB"></th>');
		$set['plus'] = 1;
		$set['wrong_fix'] = '';
		
	} else {
		
		$set['rpt'] = array(0=>'', 1=>'', 2=>'');
		$set['carry'] = array(0=>'', 1=>'', 2=>'');
		$set['cpga'] = array(0=>'', 1=>'', 2=>'');
		$set['chr'] = array(1=>'', 2=>'');
		$set['plus'] = 0;
		$set['wrong_fix'] = '<p style=" text-align:right;">CH</p>';

	}
	
	if( $special ){
		
		$set['class'] = array(0=>'<th>CLASS OF DEGREE</th>', 1=>'<th></th>', 2=>'<th class="tB"></th>');
		
			
			if($terminal == $l){
          if($s >= 2013){
		$set['dr'] = 'TERMINAL DEGREE RESULTS';
	}
	}else{
		$set['dr'] = 'DEGREE RESULTS';
	}
		
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
		
		$set['dr'] = 'SESSIONAL RESULTS';	
		
		
		
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

//var_dump(fetch_courses( $d, $p, $fetch_level, 'second semester', $fetch_sess, $f, $fos ));
$year_of_study = $level_reps[$l].'/'.$c_duration;

	$xsession = $s.'/'.($s+1);

	$academic_semester ="FIRST & SECOND";
	$result_first_semester ="FIRST SEMESTER RESULTS";
	$result_second_semester ="SECOND SEMESTER RESULTS";


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
			<p>SEMESTER:', $academic_semester,'</p>
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
			'<th colspan="',$aa+$set['plus']+1,'">',$result_first_semester,'</th>',
			'<th colspan="',$ab+$set['plus']+1,'">',$result_second_semester,'</th>',
			'<th>GPA</th>',
			$set['cpga'][0],
			$set['class'][0],
			'<th>REMARKS</th>',
		'</tr>';
	

	echo '<tr class="thead">',
		 '<th></th>',
		 '<th></th>',
		 '<th></th>',
		 $set['rpt'][1],
		 $set['carry'][1];
	

	if( $aa != 0 || $ab != 0 ) {
		
		echo $set['chr'][1];
		
		$sizea = $aa; //+ 1;
		$sizeb =  $aa + 1 + $ab + 1;
	
		$k = (int)($aa + $ab) + 1 + 2; // additional 2 is for the two elective spaces
		
		$list = array_merge( $list_courses_first, array(1=>'elective'), array(1=>''), $list_courses_sec, array(1=>'elective') );
		

		for($i=0; $i<$k; $i++) {

			if( $i == $sizea ) {
				// input 1st elective
				echo '<th class="tB s9 bbt">Elective</th>';
				continue;
			}
			if( $i == $sizeb ) {
				// input 2nd elective
				echo '<th class="tB s9 bbt">Elective</th>';
				continue;
			}
			
			if( $i == ($aa + 1) )
				echo $set['chr'][1];
			else {
				echo '<th class="tB"><p class="ups">',isset($list[$i]['stdcourse_custom2']) ? strtoupper($list[$i]['stdcourse_custom2']) : '','</p></th>';
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
		echo $set['chr'][2];
		
		for($i=0; $i<$k; $i++) {

			if( $i == $sizea ) {
				// input 1st elective
				echo '<th class="tB s9"></th>';
				continue;
			}
			if( $i == $sizeb ) {
				// input 2nd elective
				echo '<th class="tB s9"></th>';
				continue;
			}
			
			if( $i == ($aa + 1) )
				echo $set['chr'][2];
			else
				echo '<th class="tB">',isset($list[$i]['course_unit']) ? $list[$i]['course_unit'] : '','</th>';
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


	$std_off_list= fetch_student_mat( $d, $p, $l, $f, $s, $fos, $special, $c_duration );


	
$c=0;


$std_off1 =$std_off_list;
$std1=array();



if($_GET["spxx"]=="Yes") //Displaying of special result
{

foreach( $std_off_list as $ind_std ) 
{
    $welcomeback = welcomeback($ind_std['std_id'],$s); // student on welcome back
     if(!$welcomeback){


	$fullname = $ind_std['surname'].' '.$ind_std['firstname'].' '.$ind_std['othernames'];
	if( $l > 1 )
			 {
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
					$first_semester = fetch_student_RESULT_sessional($ind_std['std_id'], $arr1, $s, $regdcourses);

					$first_semester = empty($first_semester) ? array('') : $first_semester;
					$second_semester = fetch_student_RESULT_sessional($ind_std['std_id'], $arr2, $s, $regdcourses);
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
continue;
}
						
						if( $i == ($aa + 1) ) {
							if( $l > 1 ) {

								
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
					
					
				}

		//echo '<td>',get_gpa($s, $ind_std['std_id']),'</td>';
		
				$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
				//$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
				$finalyear = ( $special ) ? true : false;
				// new sessional result thats why it has tru parameter
				$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true );
				
				$ignore = substr($remark,0,4) == 'PASS' ? false : true;
				
				if( $l > 1 )
					//echo '<td class="B">',$cgpa,'</td>';
			 	
				#class of degree section
				if( $special ) {
					//echo '<td class="B tc">',G_degree($cgpa, $ignore),'</td>';
				}
			 
$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true );
		

		if (strstr($remark, "PASS") || getO($remark))
		{
			$fullname = $ind_std['surname'].' '.$ind_std['firstname'].' '.$ind_std['othernames'];
			//echo " || $fullname.<br>";
			$std1[$c]=$ind_std;
					$c++;
		}
		//{
			
		}


}

$c=0;
$ck=0;

foreach( $std1 as $ind_std ) 
{

$c++;
		
 $welcomeback = welcomeback($ind_std['std_id'],$s); // student on welcome back
     if(!$welcomeback){

	$fullname = $ind_std['surname'].' '.$ind_std['firstname'].' '.$ind_std['othernames'];
	//var_dump($fullname);
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
				
				echo '<td class="s9">',$grc,
				'</td>',
				 	 '<td class="s9">',get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos),
				 	 '</td>';
			 }
			 	
				
			 	#gets student res 4 1st and 2nd semester then merge them
				if( $aa != 0 || $ab != 0 ) {
					
					if( $l > 1 )
						//NOTE:	echo '<td class="tB s9">',get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
						// ?????????????????????????????????????????????????????? FACULTY OF AGRIC F = 6
						if ((($f == 6) || ($d == 25)) && ($l >= 5 )) {
							//$s_take = get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ); //get drop courses in yr3
							//$s_take = $s_take == '' ? '' : '<br>'.$s_take; // new code
							echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'First Semester'),get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
							'</td>';
							//$s_take='';
						} else {
							echo '<td class="tB s9">',get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
						}
						// ????????????????????????????????????????????????????
					
					$regdcourses = fetch_Ireg($ind_std['std_id'], $s, $l);
					$first_semester = fetch_student_RESULT_sessional($ind_std['std_id'], $arr1, $s, $regdcourses);
					$first_semester = empty($first_semester) ? array('') : $first_semester;
					$second_semester = fetch_student_RESULT_sessional($ind_std['std_id'], $arr2, $s, $regdcourses);
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




/////End Of addition


							//echo '<td class="tB s9">',isset($electives[1]) ? $electives[1] : '','</td>';
							//NOTE:	echo '<td class="tB s9">',isset($go1) ? $go1 : '','</td>';
							echo '<td class="tB s9">',fetch_electives( $ind_std['std_id'], $s, $l, 1),'</td>';
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
							//NOTE:	echo '<td class="tB s9">',isset($go1) ? $go1 : '','</td>';
							echo '<td class="tB s9">',fetch_electives( $ind_std['std_id'], $s, $l, 2),'</td>';
							continue;


							//echo '<td class="tB s9">',isset($electives[2]) ? $electives[2] : '','</td>';
							//continue;
						}
						
						if( $i == ($aa + 1) ) {
							if( $l > 1 ) {

								#2 - for second semester and 1 for 1st semester
								//NOTE:	echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
								//??????????????????????????????????????????????????????????? FACULTY OF AGRIC F=6
								if ((($f == 6) || ($d == 25)) && ($l >= 5 )) {
									//$s_take = get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ); //get drop courses in yr3
									//$s_take = $s_take == '' ? '' : '<br>'.$s_take; // new code
									echo '<td class="tB s9">',
									 result_check(3, $ind_std['std_id'], 'Second Semester'),get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
									 '</td>';
									//$s_take='';
								} else {
									echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
								}
								//????????????????????????????????????????????????????????????
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
		
				
				
				if (($f == 6) || ($d == 25)) // FACULTY OF AGRIC CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
					{
						$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
						//$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
						$finalyear = ( $special ) ? true : false;
						// new sessional result thats why it has tru parameter
						$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true );
						
						if ($l > 5 ){
							$remark = result_check_pass_sessional($l, $ind_std['std_id'], $s, $cgpa, $take_ignore=false, $taketype='');
						} else if ( ($l == 5 ) || ($l == 3) ){ // ignor vac results in current level
							$remark = result_check_pass_2_sessional($l, $ind_std['std_id'], $s, $cgpa);
						} else {
							//$remark = result_check_pass($l, $ind_std['std_id']) ;
						}
						
						$ignore = substr($remark,0,4) == 'PASS' ? false : true;
						
						if( $l > 1 )
							echo '<td class="B">',$cgpa,'</td>';
						
						#class of degree section
						if( $special ) {
							echo '<td class="B tc">',G_degree($cgpa, $ignore),'</td>';
						}
						
					} else { // end faculty of agric CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
						$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
						
						//$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
						$finalyear = ( $special ) ? true : false;
						// new sessional result thats why it has tru parameter
						//$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true );
						
						if ( $special ) {
							$remark = result_check_pass_2_sessional($l, $ind_std['std_id'], $s, $cgpa); //ignore vac result in current level
						} else {
							$remark = result_check_pass_sessional($l, $ind_std['std_id'], $s, $cgpa, $take_ignore=false, $taketype='');
						}
						
						$ignore = substr($remark,0,4) == 'PASS' ? false : true;
						
						if( $l > 1 )
							echo '<td class="B">',$cgpa,'</td>';
						
						#class of degree section
						if( $special ) {
							echo '<td class="B tc">',G_degree($cgpa, $ignore),'</td>';
						}
						
					}
				
					echo '<td class="s9"><div class="dw">',$remark,'</div></td>',
			 '</tr>';

}
}
$ck=$c;

}
else
{
foreach( $std_off_list as $ind_std ) 
{
	 $welcomeback = welcomeback($ind_std['std_id'],$s); // student on welcome back
     if(!$welcomeback){
	// check out delay student for agri and ict student on three level
if(($l == 3) && ($f == 6 || $d == 25)){

	if (!( test_result($ind_std['std_id'], $l, ($s-1), 'delay') == 'true' )   )	{
		$cgpa = auto_cgpa(($s - 1), $ind_std['std_id'], $l, $c_duration, $year_of_study);
		if ( $cgpa >= 0.75 && $cgpa < 1.00 ) continue ; 
		//if ( $cum_gpa < 1.00 ) continue ;// probation function
	// end check
	$entry_year = get_entry_sesssion($ind_std['std_id']);
	if($entry_year['std_custome2'] >= $new_prob && $entry_year['std_custome2'] < $new_prob1){	
		
 if($cgpa >=1.00 && $cgpa <=1.49 || $fail_cu ==15) continue;

}elseif($entry_year['std_custome2'] >= $new_prob1)
{
if($cgpa >=1.5 && $fail_cu ==15) continue;
}
	
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
					if (($f == 6) || ($d == 25)) {
			$grc =	get_repeat_courses_reworked_agric($l, $s, $ind_std['std_id'], $d,$f);
			}else{
				$grc = get_repeat_courses_reworked($l, $s, $ind_std['std_id'], $d);
			}
				
				
				
					
					
					
				echo '<td class="s9">',$grc,'</td>',
				 	 '<td class="s9">',
				 	 get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos),
				 	//var_dump(get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos));
				 	 '</td>';
			 }

if( $aa != 0 || $ab != 0 ) {
					
					if( $l > 1 ){
								
						
						if (($f == 6) || ($d == 25)) {
							
							if ($l == 5 ) {
								//echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'First Semester'),get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
								echo '<td class="tB s9">', 
								result_check(3, $ind_std['std_id'], 'First Semester'),result_check22(3, $ind_std['std_id'], $d,'First Semester'),
								'</td>';
							
							} else if ($l > 5 ) {
								echo '<td class="tB s9">', 
								result_check(3, $ind_std['std_id'], 'First Semester'),result_check22(3, $ind_std['std_id'], $d,'First Semester'),
								'</td>';
							} else {
									echo '<td class="tB s9">',
									get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
									'</td>';
							}
						} else {
							echo '<td class="tB s9">',get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
						}
						// ????????????????????????????????????????????????????
					}


					$regdcourses = fetch_Ireg($ind_std['std_id'], $s, $l);
					$first_semester = fetch_student_RESULT_sessional($ind_std['std_id'], $arr1, $s, $regdcourses);
					$first_semester = empty($first_semester) ? array('') : $first_semester;
					$second_semester = fetch_student_RESULT_sessional($ind_std['std_id'], $arr2, $s, $regdcourses);
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
if ($pos == false) 
{
  $ec=$electives[2]."***";
} 
else
{
   $ec="++";
}

echo '<td class="tB s9">',fetch_electives( $ind_std['std_id'], $s, $l, 1),'</td>';
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
     

     echo '<td class="tB s9">',fetch_electives( $ind_std['std_id'], $s, $l, 2),'</td>';
							continue;


							//echo '<td class="tB s9">',isset($electives[2]) ? $electives[2] : '','</td>';
							//continue;
						}
						
						if( $i == ($aa + 1) ) {
							if( $l > 1 ) {

								#2 - for second semester and 1 for 1st semester
								//??????????????????????????????????????????????????????????? FACULTY OF AGRIC F=6
								if (($f == 6) || ($d == 25)) {
									//$s_take = get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ); //get drop courses in yr3
									//$s_take = $s_take == '' ? '' : '<br>'.$s_take; // new code
									if ($l == 5 ) {
										//echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'Second Semester'),get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
										echo '<td class="tB s9">', 
										result_check(3, $ind_std['std_id'], 'Second Semester'),result_check22(3, $ind_std['std_id'], $d,'Second Semester'),
										'</td>';
									
									} else if ($l > 5 ) {
										echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'Second Semester'),result_check22(3, $ind_std['std_id'], $d,'Second Semester'),'</td>';
									} else {
									echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
									}
									//$s_take='';
								} else {
									echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
								}
								//????????????????????????????????????????????????????????????
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
		
   if (($f == 6) || ($d == 25)) // FACULTY OF AGRIC CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
					{
						$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
						//$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
						$finalyear = ( $special ) ? true : false;
						// new sessional result thats why it has tru parameter
						//$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true );
						
						if ($l > 5 ){
							$remark = result_check_pass_sessional($l, $ind_std['std_id'], $s, $cgpa, $take_ignore=false, $taketype='');
						} else if ( ($l == 5 ) || ($l == 3) ){ // ignor vac results in current level
							$remark = result_check_pass_2_sessional($l, $ind_std['std_id'], $s, $cgpa);
						} else {
							$remark = result_check_pass_2_sessional($l, $ind_std['std_id'],$s,$cgpa);
						}
						
						$ignore = substr($remark,0,4) == 'PASS' ? false : true;
						
						if( $l > 1 )
							echo '<td class="B">',$cgpa,'</td>';
						
						#class of degree section
						if( $special ) {
							echo '<td class="B tc">',G_degree($cgpa, $ignore),'</td>';
						}
						
					} else { // end faculty of agric CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
						$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
							//$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
						$finalyear = ( $special ) ? true : false;
						// new sessional result thats why it has tru parameter
						//$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true );
						//$remark = result_check_pass($l, $ind_std['std_id'], $s, $cgpa);

if ( $special ) {
							$remark = result_check_pass_2_sessional($l, $ind_std['std_id'], $s, $cgpa); //ignore vac result in current level
						} else {
							$remark = result_check_pass_sessional($l,$ind_std['std_id'],$s,$cgpa,$take_ignore=false, $taketype='');
						}
						
						$ignore = substr($remark,0,4) == 'PASS' ? false : true;
						
						if( $l > 1 )
							echo '<td class="B">',$cgpa,'</td>';
						
						#class of degree section
						if( $special ) {
							echo '<td class="B tc">',G_degree($cgpa, $ignore),'</td>';
						}
					}
			
				
		echo '<td class="s9"><div class="dw">',$remark,
		'</div></td>',
			 '</tr>';


			}



		}else {

		$cgpa = auto_cgpa(($s - 1), $ind_std['std_id'], $l, $c_duration, $year_of_study);
		if ( $cgpa >= 0.75 && $cgpa < 1.00 ) continue ;
		$entry_year = get_entry_sesssion($ind_std['std_id']);
	if($entry_year['std_custome2'] >= $new_prob && $entry_year['std_custome2'] < $new_prob1){	
		
 if($cgpa >=1.00 && $cgpa <=1.49 || $fail_cu ==15) continue;

}elseif($entry_year['std_custome2'] >= $new_prob1)
{
if($cgpa >=1.5 && $fail_cu ==15) continue;
}

		
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
				if (($f == 6) || ($d == 25)) {
			$grc =	get_repeat_courses_reworked_agric($l, $s, $ind_std['std_id'], $d,$f);
			}else{
				$grc = get_repeat_courses_reworked($l, $s, $ind_std['std_id'], $d);
			}
				
				//$grc = get_repeat_courses_111($l, $s, $ind_std['std_id'], $d);
					
					//$testing = get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos);
					
				echo '<td class="s9">',$grc,
				'</td>',
				 	 '<td class="s9">',
				 	 get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos),
				 	 //var_dump(	 get_carryover_courses($l, $s, $p, $f, $d, $ind_std['std_id'], $fos));
				 	 '</td>';
			 }
			
				if( $aa != 0 || $ab != 0 ) {
					
					if( $l > 1 ){
								
						if (($f == 6) || ($d == 25)) {
							
							if ($l == 5 ) {


								if($d == 25){

									
								echo '<td class="tB s9">',result_check(3, $ind_std['std_id'], 'First Semester'),result_check22(3, $ind_std['std_id'], $d,'First Semester'),result_check44(4, $ind_std['std_id'], $d,'First Semester'),
								
								'</td>';

								}else{
									if($special =="spillover"){
                                    echo '<td class="tB s9">',
                                    get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
								'</td>';
									}else{
								//echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'First Semester'),get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
								echo '<td class="tB s9">',result_check(3, $ind_std['std_id'], 'First Semester'),result_check22(3, $ind_std['std_id'], $d,'First Semester'),result_check_rework_44(4, $ind_std['std_id'], $d,'First Semester'),
								
								//get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
								'</td>';
							}
						}
							
							} else if ($l > 5 ) {
								echo '<td class="tB s9">',// result_check_6(3, $ind_std['std_id'], 'First Semester'),
								//result_check66(3, $ind_std['std_id'], $d,'First Semester'),
								get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
								'</td>';
							} else {
									echo '<td class="tB s9">',
									get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
									'</td>';
							}
						} else {
							
							
							echo '<td class="tB s9">',get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
							'</td>';
						}
						// ????????????????????????????????????????????????????
					}
					$regdcourses = fetch_Ireg($ind_std['std_id'], $s, $l);
					$first_semester = fetch_student_RESULT_sessional($ind_std['std_id'], $arr1, $s, $regdcourses);
					$first_semester = empty($first_semester) ? array('') : $first_semester;
					$second_semester = fetch_student_RESULT_sessional($ind_std['std_id'], $arr2, $s, $regdcourses);
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

//$pos = strpos($bt, $el);

//echo $bt."**".$el."==".$go1."<hr>";


if ($pos == false) 
{
  $ec=$electives[2]."***";
} 
else
{
   $ec="++";
}
							echo '<td class="tB s9">',fetch_electives( $ind_std['std_id'], $s, $l, 1),'</td>';
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

							echo '<td class="tB s9">',fetch_electives( $ind_std['std_id'], $s, $l, 2),'</td>';
							continue;


							//echo '<td class="tB s9">',isset($electives[2]) ? $electives[2] : '','</td>';
							//continue;
						}
						
						if( $i == ($aa + 1) ) {
							if( $l > 1 ) {

								#2 - for second semester and 1 for 1st semester
								//??????????????????????????????????????????????????????????? FACULTY OF AGRIC F=6
								if (($f == 6) || ($d == 25)) {
									//$s_take = get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ); //get drop courses in yr3
									//$s_take = $s_take == '' ? '' : '<br>'.$s_take; // new code
									if ($l == 5 ) {



										if($d == 25){

									//echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'First Semester'),get_fake_chr( 1, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
								echo '<td class="tB s9">',result_check(3, $ind_std['std_id'], 'Second Semester'),result_check22(3, $ind_std['std_id'], $d,'Second Semester'),result_check44(4, $ind_std['std_id'], $d,'Second Semester'),
								'</td>';

								}else{

									if($special =="spillover"){
                                    echo '<td class="tB s9">',
                                    get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
								'</td>';
									}else{
										//echo '<td class="tB s9">', result_check(3, $ind_std['std_id'], 'Second Semester'),get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
										echo '<td class="tB s9">', 
										result_check(3, $ind_std['std_id'], 'Second Semester'),
										result_check22(3, $ind_std['std_id'], $d,'Second Semester'),result_check_rework_44(4, $ind_std['std_id'], $d,'Second Semester'),

										'</td>';
									}
								}
									
									} else if ($l > 5 ) {
										echo '<td class="tB s9">',// result_check_6(3, $ind_std['std_id'], 'Second Semester'),
										//result_check66(3, $ind_std['std_id'], $d,'Second Semester'),
										get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
										'</td>';
									} else {
									echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),'</td>';
									}
									//$s_take='';
								} else {
									echo '<td class="tB s9">',get_fake_chr( 2, $rpt_list, $carryov_list, $s, $ind_std['std_id'] ),
									
									'</td>';
								}
								//????????????????????????????????????????????????????????????
							}
						}
						else {
							
							if( isset($ll[$i]['std_grade']) ) { 

								if( $ll[$i]['std_grade'] == '&nbsp;&nbsp;' ) {
									echo '<td class="tB" style="background:yellow"></td>';
								} else {
									echo '<td class="tB">',$ll[$i]['std_grade'],
									'</td>';
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

		echo '<td>',get_gpa($s, $ind_std['std_id']),
		'</td>';
		
				
					
					if (($f == 6) || ($d == 25)) // FACULTY OF AGRIC CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
					{

						$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);

						$finalyear = ( $special ) ? true : false;
						// new sessional result thats why it has tru parameter
						//$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true );
						
						if ($l > 5 ){
							$remark = result_check_pass_2_sessional($l, $ind_std['std_id'], $s, $cgpa, $take_ignore=false, $taketype='');
						} else if ( ($l == 5 ) || ($l == 3) ){ // ignor vac results in current level
							$remark = result_check_pass_2_sessional($l, $ind_std['std_id'], $s, $cgpa);
						} else {
							$remark = result_check_pass_2_sessional($l, $ind_std['std_id'],$s,$cgpa);
						}
						
						$ignore = substr($remark,0,4) == 'PASS' ? false : true;
						
						if( $l > 1 )
							echo '<td class="B">',$cgpa,'</td>';
							//echo '<td class="B">',$cgpa,'</td>';
						
						#class of degree section
						if( $special ) {
							echo '<td class="B tc">',G_degree($cgpa, $ignore),
							'</td>';
						}
						
					} else { // end faculty of agric CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
						$cgpa = auto_cgpa($s, $ind_std['std_id'], $l, $c_duration, $year_of_study);
							//$fail_cu=get_fail_crunit($l,$ind_std['std_id'],$s);
						$finalyear = ( $special ) ? true : false;
						// new sessional result thats why it has tru parameter
						//$remark = get_remarks($p, $f, $d, $l, $s, $ind_std['std_id'], $cgpa, $fos, $finalyear, true );
						//$remark = result_check_pass($l, $ind_std['std_id'], $s, $cgpa);
						
						if ( $special ) {
                           // $remark = result_check_pass_2($l, $ind_std['std_id'], $s, $cgpa);
							$remark = result_check_pass_with_vac_normal($l, $ind_std['std_id'], $s, $cgpa); //vac and mormal result in current level
						} else {

							$remark = result_check_pass_sessional($l,$ind_std['std_id'],$s,$cgpa,$take_ignore=false, $taketype='');
						}
						
						$ignore = substr($remark,0,4) == 'PASS' ? false : true;
						
						if( $l > 1 )
							echo '<td class="B">',$cgpa,'</td>';
						
						#class of degree section
						if( $special ) {
							echo '<td class="B tc">',G_degree($cgpa, $ignore),
							'</td>';
						}
					}
				
				
				
		echo '<td class="s9"><div class="dw">',$remark,
		'</div></td>',
			 '</tr>';







}

//////////////////NO END
	}	

}
	}

echo '</tbody></table>';


	echo '<div class="sph block bl" style="margin-top:30px; ">
	<div style="border-bottom:1px solid #000; padding:4px 10px;" class="block B">STATISTICS</div>
	<div class="st block">
	<div><p class="a">No Of Students Registered</p> <p class="b">';
	$std_register = get_count_numstd_reg( $d, $s, $l, $c_duration, $fos );
	//if($std_register <= 1 )
	//{
echo $c;
	//}else{ echo $std_register; }
	echo'</p></div>
	<div><p class="a">No of Results Published</p> <p class="b">',$c,'</p></div>
	</div>
	</div>';






#signature placeholder

echo '<div class="sph block" style="margin-top:40px;">',$set['bottom'],'</div>';

echo '<div class="sph center" style="text-align:center; font-size:15px; font-weight:700;">Date of Senate Approval :  .......................................................................</div>';







	//if (mysqli_num_rows($r)!=0){ // found courses in the level
		
		//} 
		//return $fail;
	
	//$fail = $fail != ''? substr($fail,2): '';*/
	
	

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

$set = array();
unset($set,$course_duration, $cpga, $remark, $ignore);
mysqli_close($connect);
ob_end_flush();
?>
</body>
</html>

