<?php
require( "inc/header.php" );
require_once './updates.php';


if ( isset( $_GET['sortby'] ) ) {
    $_SESSION['sortCriteria'] = $_GET['sortby'];
} elseif ( isset( $_POST['sortby'] ) ) {
    $_SESSION['sortCriteria'] = $_POST['sortby'];
} else {
    if ( !isset( $_SESSION['sortCriteria'] ) ) {
        $_SESSION['sortCriteria'] = "matric_no";
    }
}

$_SESSION['orderBy'] = "";
if ( ( isset( $_SESSION['sortCriteria'] ) ) && ( $_SESSION['sortCriteria'] == "matric_no" ) ) {
    $_SESSION['orderBy'] = "ORDER BY students_profile.matric_no";
} elseif ( ( isset( $_SESSION['sortCriteria'] ) ) && ( $_SESSION['sortCriteria'] == "name" ) ) {
    $_SESSION['orderBy'] = "ORDER BY students_profile.surname ASC, students_profile.firstname ASC, students_profile.othernames ASC";
}
?>

<!-- content starts here -->
<style type="text/css">
.edit tr td{ border-bottom:1px solid #CCC;}
.edit tr th{ padding:7px 5px; 
background:#333;
background: -moz-linear-gradient(top, #000000 0%, #004 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#000000), color-stop(100%,#004));
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#000000', endColorstr='#004',GradientType=0 );
}
.edit tr td input[ type=text]{ padding:1px 2px;}
.edit tr td a{ color:#900; text-decoration:none}
</style>
<?php
//$_SESSION['s_session']  = $s_session;
//$_SESSION['s_semester'] = $s_semester;
//$_SESSION['s_level']    = $s_level;
//$pti                    = $_POST['nation'];

$query = 'SELECT course_title, course_code FROM all_courses WHERE thecourse_id = '.$_GET['cs_code'].' LIMIT 1';

$result = mysqli_query( $GLOBALS['connect'],  $query ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

while ( $row = mysqli_fetch_assoc( $result ) ) {
    //$stdcourse_custom1 = $row["stdcourse_custom1"];
    //$stdcourse_custom2 = $row["stdcourse_custom2"];
    $stdcourse_custom1 = $row["course_title"];
    $stdcourse_custom2 = $row["course_code"];
}

?>
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="10" id=title>
  <tr>
    <td class="hder"><b >Edit Student's Result Management (<?php echo GetlLevel( $s_level ); ?>)</u></b>
    <br/><?php echo $_SESSION['s_semester'],' ',$_SESSION['s_session'],'/',$_SESSION['s_session'] + 1; ?> </td>
  </tr>
  
<?php
if( !empty($stdcourse_custom1) || !empty($stdcourse_custom2) ) { ?>  
    <tr>
    <td><br/><b>List of Students Offering (<?php echo $stdcourse_custom1,"&nbsp;-&nbsp;",$stdcourse_custom2;?>)</b></td>
    </tr>
 <?php
}
?>
  
</table>

<!-- action="_result_insertq.php" -->
<?php
//set the number of columns
$columns = 1;

if ( !$rowstart ) {
    $rowstart = 0;
}

$ending = 800;

if( $_GET['season']=='VACATION' ) {
	$season = 'VACATION';
	$s_sem = ' IN ("First Semester", "Second Semester")';
} else {
	$season = 'NORMAL';
	$s_sem = ' = "'.$_GET['s_semester'].'"';
}


	$query3 = "SELECT course_reg.*, `dept_options`.`do_id`, `dept_options`.`programme_option`, students_profile.matric_no, students_profile.surname, students_profile.firstname, students_profile.othernames 
	FROM `dept_options`, course_reg LEFT JOIN students_profile ON (course_reg.std_id = students_profile.std_id) WHERE `dept_options`.`dept_id` = students_profile.`stddepartment_id` && `dept_options`.`do_id` = `students_profile`.stdcourse && course_reg.thecourse_id ='$cs_code' AND course_reg.csemester $s_sem AND course_reg.cyearsession = '$_SESSION[s_session]' && course_reg.course_season = '$season' && students_profile.stdfaculty_id = '$_SESSION[myfaculty_id]' && students_profile.stddepartment_id = '$_SESSION[mydepartment_id]'
	GROUP BY course_reg.std_id {$_SESSION['orderBy']}
	$od LIMIT $rowstart,$ending";
	
	
$result3  = mysqli_query( $GLOBALS['connect'],  $query3 );
$i = 0;

if( 0!=mysqli_num_rows ($result3) ) {
	
?>
<form name="form1" method="post" autocomplete="off" onSubmit="return OnSubmitForm(this);">

<input name="cs_code" type="hidden" value="<?php echo $_GET['cs_code']; ?>">
<input name="c_code" type="hidden" value="<?php echo $stdcourse_custom2; ?>">

  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text edit">
    <tr>
      <td colspan="9"><strong>
	  <?php 
	  if( isset($_GET['w_update']) ) { 
	  	echo 0==$_GET['w_update'] ? '<div style="color; width:300px; padding:10px 40px; color:red">No Result Was Added</div>' : '<div>Result Added/Updated Successfully</div>'; 
	  }
	  ?></strong></td>
    </tr>
<?php
	
	$bgcolor == '#FFFFFF';
	$num      = mysqli_num_rows ( $result3 );
	$thisrows = mysqli_num_rows ( $result3 );
	//$allrows  = mysqli_num_rows ( $result2 );
	$rowbegin = $rowstart + 1;
	$rowend   = $rowstart + $thisrows;
	$themembers = $num > 1 ? 'Students' : 'Student';

	$list = array();
	while( $r=mysqli_fetch_array($result3) ) {
		$list[ $r['do_id'] ][] = $r;
	}
	mysqli_free_result($result3);
	
		echo <<<TH
		  <tr style="color:#FFF;">
		  <th> </th>
		  <th><strong>S/No</strong></th>
		  <th><strong>Options</strong></th>
		  <th><strong>Surname</strong></th>
		  <th><strong>First Name</strong></th>
		  <th><strong>Othernames</strong></th>
		  <th><strong>Matric No</strong></th>
		  <th><strong>Course Unit</strong></th>
		  <th><strong>Course Type</strong></th>
		  <th><strong>Counts In GPA</strong></th>
		  <th><strong>Mark</strong></th>
		</tr>
TH;
	foreach( $list as $k=>$v ) {
		echo '<tr style="background:#CCC"><td colspan="11" style="text-align:center;font-style:italic; font-weight:700; color:#004">',
		$v[0]['programme_option'],'</td></tr>';		
		foreach( $v as $kk ) {
			
			$i++;
			//$bgcolor = $bgcolor == '#FFFFFF' ? '#DBE2F0' : '#FFFFFF';
			$std_id            = $kk["std_id"];
			$course_id         = $kk["thecourse_id"];
			$c_unit            = $kk["c_unit"];
			$stdcourse_custom3 = $kk["stdcourse_custom3"];
			$matric_no         = $kk["matric_no"];
			$surname           = stripslashes( $kk["surname"] );
			$firstname         = stripslashes( $kk["firstname"] );
			$othernames        = stripslashes( $kk["othernames"] );
			$course_custom1 = 'YES';	
			
			$rsquery2 = "SELECT * FROM registered_semester WHERE sem $s_sem AND ysession = '$s_session' AND std_id = '$std_id' && season = '$season'";
			$rsquery2 = mysqli_query( $GLOBALS['connect'],  $rsquery2 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
			$numrs     = mysqli_num_rows ( $rsquery2 );
			$rowrs     = mysqli_fetch_array( $rsquery2 );
			$rslevelid = $rowrs["rslevelid"];			
			
			$query2ac = "SELECT * FROM students_results WHERE std_id = '$std_id' AND std_mark_custom1 $s_sem AND
			std_mark_custom2 = '$s_session' AND stdcourse_id = '$course_id' && period = '$season' ";
			
			$query2ac = mysqli_query( $GLOBALS['connect'],  $query2ac ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
			$num2ac   = mysqli_num_rows ( $query2ac );
			$row2ac   = mysqli_fetch_array( $query2ac );
			$std_mark = $row2ac["std_mark"];	
							
			if ( $num2ac == 0 ) {
				$res_status = "<img src=\"../images/wrong.png\" width=\"12\" height=\"12\" align=\"Not Added\">";
				$radd       = "add result";
			} else {
				$res_status = "<img src=\"../images/tick.png\" width=\"12\" height=\"12\" alt=\"Added\">";
				$radd       = "edit result";
			}
			
			if ( $std_id ) {
				
				$x = '0~~'.$_GET['s_semester'].'~~'.$_GET['s_level'];
				echo '<tr style="background:',$bgcolor,'">',
        				( $pti != "all" ) ? "<td><input type=\"checkbox\" name=\"check[$i]\" id=\"check[$i]\" value=\"$i\" /></td>" : "<td><input type=\"hidden\" name=\"check[$i]\" id=\"check[$i]\" value=\"$i\"></td>",
						'<input type="hidden" value="',$std_id,'" name="del[',$i,']" />',
						'<td>',$i,'</td>',
						'<td>[<a href="___delete2.php?std_id=',$std_id,'&cr_id=',$_GET['cs_code'],'&season=',$_GET['season'],'&x=',$x,'&action=delete" style="font-size: 10px" onClick="return confirmLink(this, \'DELETE this? Course - ',strtoupper($stdcourse_custom2),'\')">delete courses</a>]</td>',
						'<td><b>',strtoupper( $surname ),'</b></td>',
						'<td>',strtoupper( $firstname ),'</td>',
						'<td>',strtoupper( $othernames ),'</td>',
						'<td style="text-align:center;font-style:italic; font-weight:700; color:#004; background:#CCC">',$matric_no,'<input name="xcos_id" type="hidden" value="',$course_id,'"></td>',
						'<td>',$c_unit,'<input name="c_unit[',$i,']" type="hidden" value="',$c_unit,'"></td>',
						'<td>',$stdcourse_custom3,'</td>',
						'<td>',$course_custom1,'<input name="course_custom1[',$i,']" type="hidden" value="',$course_custom1,'">';

						$grade = return_grade( $std_mark );
						echo <<<TD
							<td>
							<input style="width:30px; text-align:center" name="std_mark2[$i]" type="text" tabindex="$i"  value="$grade[grade]" onKeyUp="updA(this, 'd$i', 'check[$i]')" size="1" maxlength="1">
							<input name="std_mark[$i]" style=" background:#f0f0f0" type="text" id="d$i" onChange="if (this.value!='') document.getElementById('check[$i]').checked=true" value="$std_mark" size="5" maxlength="5">
							<input name="std_id[$i]" type="hidden" value="$std_id">
							<input name="cos_id[$i]" type="hidden" value="$course_id">
							<input name="matric_no[$i]" type="hidden" value="$matric_no">
							</td>
TD;
																				
					 echo '</tr>';	
			
			}
		
		}
	}


echo <<<BOTTOM
	<tr>
	<th colspan=11 align=right>
	<div style=" float:left"><input type="submit" name="drop" value="Delete" onClick="document.pressed=this.value"></div>
	<a href="add_result.php?s_session=$s_session&s_semester=$s_semester&&s_level=$s_level&Submit=Continue" style=' color: #006699;border: 1px ridge #72A4D5;background-color: #F4F4F7; padding:4px 10px; text-decoration:none;'>&laquo; go back</a>
	<input type="submit" name="Submit" value="Save Changes" onClick="document.pressed=this.value">
BOTTOM;



echo <<<BB
		</td>
		</tr>
		<tr>
		<td colspan="11"><strong>Showing $rowbegin - $rowend of $thisrows $themembers</strong></td>
		</tr>
BB;

	echo '<input name="certificate" type="hidden" value="',basename($_SERVER['PHP_SELF']),'?',$_SERVER['QUERY_STRING'],'">',
		 '<input type="hidden" name="season" value="',$_GET['season'],'" />',
         '<input name="subcount" type="hidden" value="',$num,'">',
		 '<input type="hidden" name="session_year" value="',$_SESSION['s_session'],'" />',
		 '<input type="hidden" name="cs_code" value="',$_GET['cs_code'],'" />',
		 '<input type="hidden" name="level" value="',$_GET['s_level'],'" />',
		 '</table>',
		 '</form>';

}


?>

<!-- content ends here -->

<script type="text/javascript">
function OnSubmitForm(form) {

    if (document.pressed == 'Delete') {
		
		//var size = form.elements.check;
		//alert(size);
		//return false;
		///return false;
		///for( var i=0; i)
        form.action = "___delete2.php";		
    } else {
        form.action = "_result_insertq.php";
    }
    return true;
}
</script>

<?php
require( "inc/footer.php" );
?>