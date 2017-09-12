<?php
$mymstatus = $_SESSION['mymstatus'];
?>


<?php
$page_title = '';

function menu() {

global $page_title;
$pages = array();
$pages['index'] = '';
$pages['as'] = '';
$pages['ac'] = '';
$pages['ac1'] = '';
$pages['vs'] = '';
$pages['vr2s'] = '';
$pages['vc'] = '';
$pages['vl'] = '';
$pages['as'] = '';
$pages['vas'] = '';
$pages['mrc'] = '';
$pages['arp'] = '';
$pages['vrs'] = '';
$pages['dpt'] = '';
$pages['lec'] = '';
$pages['e_lec'] = '';
$pages['p_lec'] = '';
$pages['faculty'] = '';
$pages['prog'] = '';
$pages['vrst']='';
$pages['wcb'] = '';
$pages['wcp'] = '';
$pages['wco'] = '';
$pages['wcr'] = '';
$ruler ="";
$leclink ="";
$deptlink = "";
$faclink = "";
$proglink = "";
if ($_SESSION['myprogramme_id'] == '0') {
	$deptlink = "<a href=\"add_dept.php\">Create Department</a>";

	$faclink = "<a href=\"add_faculty.php\">Create Faculty</a>";

	$proglink = "<a href=\"add_programme.php\">Create Programme</a>";

	$ruler = "<li style=\"list-style:none; display:inline\" class=\"clearfix\"><p class=\sepm\"></p></li>";
}


if ($_SESSION['trans_right'] == '1') {
	$translink = "<a href=\"results_transcript.php\">View Student Transcript</a>";
}

//if ($_SESSION['myprogramme_id'] == '0') {
	$addclink = "<a href=\"addcourse_x.php\">Create Courses</a>";
//}

$leclink = "<a href=\"add_lecturer.php\">Create Lecturer</a>";

$filename = basename( $_SERVER['PHP_SELF']);
switch( $filename ) {
	case 'index.php':
		$pages['index'] = ' id="on"'; $page_title = 'Exams And Records';
	break;
	case 'add_dept.php':
		$pages['dpt'] = ' id="on"'; $page_title = 'Create New Department';
	break;
	case 'add_lecturer.php':
		$pages['lec'] = ' id="on"'; $page_title = 'Create New Lecturer Account';
	break;
	case 'editlecturer.php':
		$pages['e_lec'] = ' id="on"'; $page_title = 'Edit Lecturer Account';
	break;
	case 'printassigncourses.php':
		$pages['p_lec'] = ' id="on"'; $page_title = 'Print Assign Course';
	break;
	case 'add_faculty.php':
		$pages['faculty'] = ' id="on"'; $page_title = 'Create New Faculty';
	break;
	case 'add_programme.php':
		$pages['prog'] = ' id="on"'; $page_title = 'Create New Programme';
	break;
	case 'addcourse_x.php':
		$pages['ac'] = ' id="on"'; $page_title = 'Create New Course';
	break;	
	case 'addstudent_x.php':
		$pages['as'] = ' id="on"'; $page_title = 'Create New Student';
	break;
	case 'viewassigncourses.php':
		$pages['vas'] = ' id="on"'; $page_title = 'View Assign Courses';
	break;	
	case 'viewstudents_x.php':
	case 'editstudent.php':
		$pages['vs'] = ' id="on"'; $page_title = 'View Students';
	break;	
	case 'viewcourses.php':
	case 'editcourse.php':

		$pages['vc'] = ' id="on"'; $page_title = 'View Courses';
	
	break;
	case 'viewlecturers.php':
		$pages['vl'] = ' id="on"'; $page_title = 'View Lecturers';	
	break;
	case 'assigncourses.php':
		$pages['as'] = ' id="on"'; $page_title = 'Assign Courses To Lecturers';	
	break;			
	case 'maxregcourses.php':
		$pages['mrc'] = ' id="on"'; $page_title = 'Student Course Registration';
	break;	
	case 'regWelcome.php':
		$pages['wcb'] = ' id="on"'; $page_title = 'Welcome Back Student Course Registration';
	break;	
	case 'probregWelcome.php':
		$pages['wcp'] = ' id="on"'; $page_title = 'Welcome Back Student Probation Course Registration';
	break;	
	case 'add_result_p.php':
	case 'add_result.php':
	case 'add_result2p.php':
	$pages['arp'] = ' id="on"'; $page_title = 'Enter Students Sessional Result';
	break;
	case 'add_result2p_correctional.php':
	$pages['arp'] = ' id="on"'; $page_title = 'Enter Students Correctional Result';
	break;
	case 'add_result2p_omitted.php':
	$pages['arp'] = ' id="on"'; $page_title = 'Enter Students Omitted Result';
	break;
	case 'add_result2p_resit.php':
		$pages['arp'] = ' id="on"'; $page_title = 'Enter Students Resit Result';
	break;
	case 'correct_result.php':
		$page_title = 'Correct Students Result';
	break;
	case 'view_acct.php':
		$page_title = 'Edit Account Details';
	break;
	case 'results_spreadsheetx.php':
		$pages['vrs'] = ' id="on"'; $page_title = 'View Report Sheet';
	break;
	case 'results_transcript.php':
		$pages['vrst'] = ' id="on"'; $page_title = 'View Students Transcript';
	break;
	case 'viewregstudent.php':
		$pages['vr2s'] = ' id="on"'; $page_title = 'View Registered Students By Session';
	break;
	case 'viewregprobstudent.php':
		$page_title = 'View Registered Probation Students By Session';
	break;
	case 'viewregdelaystudent.php':
		$page_title = 'View Registered Delay Students By Session';
	break;	
	case 'set__adv_1.php':
		$page_title = 'Register Repeat Result or No Result Course';
	break;
	case 'set__adv_3.php':
		$page_title = 'Register Take or Carry Over Courses';
	break;
	case 'set__adv_4.php':
		$page_title = 'Students Result Management';
	break;	
	case 'set__adv_2.php':
		$page_title = 'View Enter Result Mode';
	break;
	case 'set__adv_5.php':
		$page_title = 'View Students Overview Result';
	break;
	case 'set__adv_6.php':
		$page_title = 'Register Probation Result';
	break;	
	case 'set__adv_7.php':
		$page_title = 'Register Resit/Repeat Result';
	break;	
	case 'set__adv_8.php':
		$page_title = 'Register Delay Result';
	break;

	case 'set_adv_9.php':
		$page_title = 'Register Take or Carry Over Courses For Welcome Back';
	break;	
	case 'set_adv_11.php':
		$page_title = 'Register Repeat Result or No Result Course For Welcome Back';
	break;
	case 'msg.php':
		$page_title = 'Messaging';
	break;	
}


if ($_SESSION['trans_right'] == '1') {
	$translink = "<a href=\"results_transcript.php\">View Student Transcript</a>";
	switch( $filename ) {
		case 'index.php':	
			$pages['index'] = ' id="on"'; $page_title = 'Exams And Records - Transcript';
		break;
	}
	echo <<<MENUT
    <ul id="menu">
        <li {$pages['index']}><a href="index.php">Home Page</a></li>
    <li style="list-style: none; display: inline" class="clearfix"><p class="sepm"></p></li>
		 <li {$pages['vs']}><a href="viewstudents_x.php">View / Update Students</a></li>
	 <li style="list-style: none; display: inline" class="clearfix"><p class="sepm"></p></li>
	  	<li {$pages['vrst']}><a href="results_transcript.php">View Students Transcript</a></li> 
	</ul>
MENUT;

} else if ($_SESSION['user_right'] == '2'){
	switch( $filename ) {
	case 'index.php':
		$pages['index'] = ' id="on"'; $page_title = 'Exams and Records - Lecturer';
	break;
	case 'add_result_plecturer.php':
		$pages['ac'] = ' id="on"'; $page_title = 'Enter Students Result';
	break;

	case 'view_result_lecturer.php':
	case 'view_result_lecturer1.php':
		$pages['ac1'] = ' id="on"'; $page_title = 'View Students Result';
	break;
	}
	//$page_title = "";//Result Entery System";
	//$pages['index'] = 'index.php';
	//$pages['ac'] = 'addstudent_x.php';//' id="on"';
echo <<<MENUL
		<ul id="menu">
        <li {$pages['index']}><a href="index.php">Home Page</a></li>
		<li style="list-style: none; display: inline" class="clearfix"><p class="sepm"></p></li> 
        <li {$pages['ac']}><a href="add_result_plecturer.php">Enter Students Result</a></li>
        <li {$pages['ac1']}><a href="view_result_lecturer.php">View Students Result</a></li>
		</ul>
MENUL;

}  else if ($_SESSION['user_right'] == '3'){
	switch( $filename ) {
	case 'index.php':
		$pages['index'] = ' id="on"'; $page_title = 'Exams and Records - Exam Officer / Lecturer';
	break;
	case 'addstudent_x.php':
		$pages['ac'] = ' id="on"'; $page_title = 'Enter Students Result';
	break;
	}
	
echo <<<MENUL
		<ul id="menu">
        <li {$pages['index']}><a href="index.php">Home Page</a></li>
		<li style="list-style: none; display: inline" class="clearfix"><p class="sepm"></p></li> 
		<li {$pages['vr2s']}><a href="viewregstudent.php">View Registered Students</a></li>
		<li style="list-style: none; display: inline" class="clearfix"><p class="sepm"></p></li> 
     
         <li {$pages['arp']}><a href="add_result_p.php">Enter Result</a></li> 
         <li style="list-style: none; display:inline" class="clearfix"><p class="sepm"></p></li>
        <li {$pages['vrs']}><a href="results_spreadsheetx.php">View Report Sheet</a></li> 
        <li style="list-style: none; display: inline" class="clearfix"><p class="sepm"></p></li>
		</ul>
MENUL;

}

else {

echo <<<MENU
    <ul id="menu">
        <li {$pages['index']}><a href="index.php">Home Page</a></li>
    <li style="list-style: none; display: inline" class="clearfix"><p class="sepm"></p></li> 
        <li {$pages['as']}><a href="addstudent_x.php">Create Students</a></li>
			<li {$pages['ac']}>{$addclink}</li>
			{$ruler}
			<li {$pages['lec']}>{$leclink}</li>
			{$ruler}
			<li {$pages['dpt']}>{$deptlink}</li>
			{$ruler}
			<li {$pages['faculty']}>{$faclink}</li>
			{$ruler}
			<li {$pages['prog']}>{$proglink}</li>
			{$ruler}        
    <li style="list-style: none; display: inline" class="clearfix"><p class="sepm"></p></li>       
        <li {$pages['vs']}><a href="viewstudents_x.php">View Students</a></li>
		<li {$pages['vr2s']}><a href="viewregstudent.php">View Registered Students</a></li>
        <li {$pages['vc']}><a href="viewcourses.php">View Courses</a></li>
         <li {$pages['vl']}><a href="viewlecturers.php">View Lecturer</a></li>
            <li {$pages['vas']}><a href="viewassigncourses.php">View Assign Courses</a></li>
            <li {$pages['p_lec']}><a href="printassigncourses.php">Print Assign Courses</a></li>
    <li style="list-style: none; display: inline" class="clearfix"><p class="sepm"></p></li>
        <li {$pages['mrc']}><a href="maxregcourses.php">Register Courses</a></li>

        <li style="list-style: none; display:inline" class="clearfix"><p class="sepm"></p></li>
        <li {$pages['as']}><a href="assigncourses.php">Assign Courses</a></li>
        <li {$pages['aso']}><a href="assigncourses_lecturer.php">Assign Courses(Lecturer)</a></li>
    <li style="list-style: none; display:inline" class="clearfix"><p class="sepm"></p></li>
        <li {$pages['arp']}><a href="add_result_p.php">Enter Result</a></li> 
    <li style="list-style: none; display:inline" class="clearfix"><p class="sepm"></p></li>
        <li {$pages['vrs']}><a href="results_spreadsheetx.php">View Report Sheet</a></li> 
        <li style="list-style: none; display: inline" class="clearfix"><p class="sepm"></p></li>
     <li style="text-align:center;padding-bottom:4px;background-color:#2E8B57;color:#fff"><b>Welcome Back Section</b></li>
        <li {$pages['wcb']}><a href="regWelcome.php">Register  Courses</a></li>  
         <li {$pages['wco']}><a href="set_adv_9.php">CarryOver  Courses</a></li> 
         <li {$pages['wcr']}><a href="set_adv_11.php">Repeat  Courses</a></li> 
         <li {$pages['wcp']}><a href="probregwelcome.php">Probation  Courses</a></li>  
         <li style="list-style: none; display: inline" class="clearfix"><p class="sepm"></p></li> 
      
		{$ruler} 
	
    </ul>
MENU;

}
//<li {$pages['vrst']}><a href="results_transcript.php">View Students Transcript</a></li> 
//  
}

//<li {$pages['ac']}><a href="addcourse_x.php">Create Courses</a></li> 
function pageTitle() {
	global $page_title;
	return $page_title;
}
?>