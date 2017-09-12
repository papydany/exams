<?php

require( "inc/header.php" );
require_once './updates.php';


	if ( isset( $_GET['sortby'] ) ) {
		$_SESSION['sortCriteria'] = $_GET['sortby'];
	} elseif ( isset( $_POST['sortby'] ) ) {
		$_SESSION['sortCriteria'] = $_POST['sortby'];
	} else {
		if ( !isset( $_SESSION['sortCriteria'] ) ) {
			$_SESSION['sortCriteria'] = "name";
		}
	}
	
	$_SESSION['orderBy'] = "";
	if ( isset( $_SESSION['sortCriteria'] ) && $_SESSION['sortCriteria'] == "matric_no" ) {
		$_SESSION['orderBy'] = "ORDER BY students_profile.matric_no";
	} elseif ( isset( $_SESSION['sortCriteria'] ) && $_SESSION['sortCriteria'] == "name" ) {
		$_SESSION['orderBy'] = "ORDER BY students_profile.surname ASC, students_profile.firstname ASC, students_profile.othernames ASC";
	}

?>

<!-- content starts here -->
<script language="javascript" type="text/javascript">
function checkform ( form )
{
  if (document.form.s_session.value == "") {
    alert( "Please select Session" );
    document.form.s_session.focus();
    return false ;
  }
  if (document.form.s_semester.value == "") {
    alert( "Please select Semester" );
    document.form.s_semester.focus();
    return false ;
  }

// return < aheref
}

</script>
<?php
$_SESSION['s_session']  = $s_session;
$_SESSION['s_semester'] = $s_semester;
$_SESSION['s_level']    = $s_level;
$pti                    = $_POST['nation'];

//$query = "SELECT DISTINCT stdcourse_custom1, stdcourse_custom2 FROM course_reg  WHERE course_reg.thecourse_id='$cs_code'";
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
    <td class="hder"><b >Student's Result Management (<?php echo GetlLevel( $s_level ); ?>)</u></b><br/>
	<?php echo $s_semester,' ',$s_session,'/',$s_session + 1; ?> 
    </td>
  </tr>
  
<?php
if( !empty($stdcourse_custom1) || !empty($stdcourse_custom2) ) { ?>  
    <tr>
    <td><br/><b>List of Students Offering (<?php echo $stdcourse_custom1,"&nbsp;-&nbsp;",$stdcourse_custom2;?>)</b></td>
    </tr> 
 <?php
}

 if ( $num != 0 ) { ?>
 
    <tr>
    <td>
    <tr>
    <td><br/><b><a href="edit_add_result2p.php?<?php echo $_SERVER['QUERY_STRING'] ?>">Edit Mode</a></b></td>
    </tr> 	
	<br/>
    <form action="" method="post">
    
    <b>Sort By</b>
    <select name="sortby" id="sortby" onChange="document.getElementById('loading').style.display='block'; this.form.submit();">
    <option value="matric_no" <?php
    if ( $_SESSION['sortCriteria'] == "matric_no" )
    echo 'selected';
    ?>>Matric Number</option>
    <option value="name" <?php
    if ( $_SESSION['sortCriteria'] == "name" )
    echo 'selected';
    ?>>Names</option>
    </select>
    &nbsp;&nbsp;&nbsp;<span id="loading" style="display:none"><img src="ajax-loader1.gif" width="80" height="80" /> <em>Please Wait...</em></span>
    </form>
    </td>
    </tr>
<?php
}    ?>  
  
</table>


<?php
	if ( $_GET['pti'] == "all" ) {
		echo '<form name="form1" method="post" action="result_insertq.php">';
	} else {
		echo '<form name="form1" method="post" action="result_insert.php">';
	}
?>

<input name="cs_code" type="hidden" value="<?php echo $cs_code; ?>">
<input name="c_code" type="hidden" value="<?php echo $stdcourse_custom2; ?>">

  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
    <tr>
      <td colspan="9"><strong>
	  <?php 
	  if( isset($_GET['w_update']) ) { 
	  	echo empty($_GET['w_update']) ? '<div style="width:300px; padding:10px 40px; color:red; border:1px solid red;">No Result Was Added</div>' : '<div style="color:green;width:300px; padding:10px 40px;background:#E1FFE1; border:1px solid #B7FFB7;">Result Added/Updated Successfully</div>'; 
	  }
	  ?></strong></td>
    </tr>
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


if ( $editmode == 'yes' ) {
	
    $query3 = "SELECT course_reg.*, students_profile.matric_no
	FROM course_reg LEFT JOIN students_profile ON (course_reg.std_id = students_profile.std_id) WHERE course_reg.thecourse_id ='$cs_code' AND course_reg.csemester $s_sem AND course_reg.cyearsession  = '$s_session' AND course_reg.std_id='$std_id' && course_reg.course_season = '$season' && students_profile.faculty_id = '$_SESSION[myfaculty_id]' && students_profile.department_id = '$_SESSION[mydepartment_id]' && students_profile.level_id = '$_SESSION[s_level]'
	GROUP BY course_reg.std_id
	{$_SESSION['orderBy']}
	$od LIMIT $rowstart,$ending";

    $query2 = "SELECT course_reg.*, students_profile.matric_no
	FROM course_reg LEFT JOIN students_profile ON (course_reg.std_id = students_profile.std_id) WHERE course_reg.thecourse_id ='$cs_code' AND course_reg.csemester $s_sem AND course_reg.cyearsession ='$s_session' AND course_reg.std_id='$std_id' && course_reg.course_season = '$season' && students_profile.faculty_id = '$_SESSION[myfaculty_id]' && students_profile.department_id = '$_SESSION[mydepartment_id]' && students_profile.level_id = '$_SESSION[s_level]'
	GROUP BY course_reg.std_id {$_SESSION['orderBy']}
	$od
	";


} else {
    
	$query3 = "SELECT course_reg.*, students_profile.matric_no
	FROM course_reg LEFT JOIN students_profile ON (course_reg.std_id = students_profile.std_id) WHERE course_reg.thecourse_id ='$cs_code' AND course_reg.csemester $s_sem AND course_reg.cyearsession = '$s_session' && course_reg.course_season = '$season' && students_profile.stdfaculty_id = '$_SESSION[myfaculty_id]' && students_profile.stddepartment_id = '$_SESSION[mydepartment_id]'
	GROUP BY course_reg.std_id {$_SESSION['orderBy']}
	$od LIMIT $rowstart,$ending";

    $query2 = "SELECT course_reg.*, students_profile.matric_no
	FROM course_reg LEFT JOIN students_profile ON (course_reg.std_id = students_profile.std_id) WHERE course_reg.thecourse_id ='$cs_code' AND course_reg.csemester $s_sem AND course_reg.cyearsession ='$s_session' && course_reg.course_season = '$season' && students_profile.stdfaculty_id = '$_SESSION[myfaculty_id]' && students_profile.stddepartment_id = '$_SESSION[mydepartment_id]'
	GROUP BY course_reg.std_id {$_SESSION['orderBy']}
	$od
	";

}

//echo $query2;

$result3  = mysqli_query( $GLOBALS['connect'],  $query3 );
$result2  = mysqli_query( $GLOBALS['connect'],  $query2 );

$num_rows = mysqli_num_rows ( $result2 );
$num      = mysqli_num_rows ( $result3 );

$thisrows = mysqli_num_rows ( $result3 );
$allrows  = mysqli_num_rows ( $result2 );
$rowbegin = $rowstart + 1;
$rowend   = $rowstart + $thisrows;



$themembers = $num > 1 ? 'Students' : 'Student';

if ( $num != 0 ) {
	
    echo '<tr style="color:#FFF; background:#333">';
	$pti = $_GET['pti'];
	if ( $pti != 'all' ) {
		echo '<td>&nbsp;</td>';
	}

	echo <<<TH
	  <td><strong>S/No</strong></td>
	  <td><strong>Options</strong></td>
      <td><strong>Surname</strong></td>
      <td><strong>First Name</strong></td>
      <td><strong>Othernames</strong></td>
      <td><strong>Matric No</strong></td>
      <td><b>Course Unit</b></td>
      <td><b>Course Type</b></td>
      <td><b>Counts In GPA</b></td>
      <td><b>Mark</b></td>
    </tr>
TH;

}

	$bgcolor = '#DBE2F0';
for ( $i = 1; $i < $num_rows + 1; $i++ ) {

	$bgcolor = ( $bgcolor == '#DBE2F0' ) ? '#FFFFFF' : '#DBE2F0';

    $row3 = mysqli_fetch_array( $result3 );
    $row2 = mysqli_fetch_array( $result2 );

    $std_id            = $row3["std_id"];
    $course_id         = $row3["thecourse_id"];
    $c_unit            = $row3["c_unit"];
    $stdcourse_custom3 = $row3["stdcourse_custom3"];


    $course_custom1 = 'YES';

    $rsquery2 = "SELECT *
	FROM registered_semester
	WHERE sem $s_sem AND
	ysession = '$s_session' AND
	std_id = '$std_id' && season = '$season'";
	
    $rsquery2 = mysqli_query( $GLOBALS['connect'],  $rsquery2 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
    $numrs     = mysqli_num_rows ( $rsquery2 );
    $rowrs     = mysqli_fetch_array( $rsquery2 );
    $rslevelid = $rowrs["rslevelid"];

    $queryaa = "SELECT *
	FROM students_profile
	WHERE std_id = '$std_id' order by matric_no";

    $resultaa            = mysqli_query( $GLOBALS['connect'],  $queryaa );
    $row                 = mysqli_fetch_array( $resultaa );
    $std_id              = $row["std_id"];
    $matric_no           = $row["matric_no"];
    $surname             = $row["surname"];
    $firstname           = $row["firstname"];
    $othernames          = $row["othernames"];

    $levelid = $row["levelid"];

    $surname             = stripslashes( $surname );
    $firstname           = stripslashes( $firstname );
    $othernames          = stripslashes( $othernames );



    $query2ac = "SELECT *
	FROM students_results
	WHERE std_id = '$std_id' AND
	std_mark_custom1 $s_sem AND
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

		echo "<tr bgcolor=$bgcolor>";
        if ( $pti != "all" ) {
			echo "<td><input type=\"checkbox\" name=\"check[$i]\" id=\"check[$i]\" value=\"$i\" /></td>";
        }

		echo <<<CC
			<td>$i
			<a href='add_result4.php?pti=$pti&nation=$nation&std_id=$std_id&mt=$matric_no&rslevelid=$rslevelid&cs_code=$cs_code&s_semester=$s_semester' style='color: blue; font-size: 10px'> </a>
			</td>
CC;


$x = '1~~'.$_GET['s_semester'].'~~'.$_GET['s_level'];
?>
	  <td>[<a href='delete2.php?std_id=<?php echo $std_id ?>&cr_id=<?php echo $_GET['cs_code'] ?>&season=<?php echo $_GET['season']; ?>&x=<?php echo $x ?>&action=delete' style='color: blue; font-size: 10px' onClick="return confirmLink(this, 'DELETE this? Course - <?php echo strtoupper($stdcourse_custom2) ?>')">delete courses</a>]</td>
	  <td><b><?php echo strtoupper( $surname ); ?></b></td>
      <td><?php echo strtoupper( $firstname ); ?></td>
      <td><?php echo strtoupper( $othernames ); ?></td>
      <td><?php echo $matric_no; ?><input name="xcos_id" type="hidden" value="<?php echo $course_id; ?>"></td>
      <td><?php echo $c_unit; ?><input name="c_unit[<?php echo $i; ?>]" type="hidden" value="<?php echo $c_unit; ?>"></td>
      <td><?php echo $stdcourse_custom3; ?></td>
      <td><?php echo $course_custom1; ?><input name="course_custom1[<?php echo $i ?>]" type="hidden" value="<?php echo $course_custom1; ?>">
	  <?php

        if ( $pti == "all" ) {
			echo "<input type=\"hidden\" name=\"check[$i]\" id=\"check[$i]\" value=\"$i\">";
        }

	  ?>
</td>
      <?php
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
 
    }

    "</TD>";
    if ( ( $i % $columns ) == ( $columns - 1 ) || ( $i + 1 ) == $num_rows ) {
        //if there is a remainder of 1, end the row
        //or if there is nothing left in our result set, end the row
        echo "</TR>";
    }

}


if ( $num != 0 ) {

echo <<<TR
    <tr>
      <td colspan=11 bgcolor=#999999 align=right>
<a href="add_result.php?s_session=$s_session&s_semester=$s_semester&&s_level=$s_level&Submit=Continue" style=' color: #006699;border: 1px ridge #72A4D5;background-color: #F4F4F7; padding:4px 10px; text-decoration:none;'>&laquo; go back</a>
TR;

if ( $priv_status != 'no' || $numca != 0 || $numcas <> 0 ) {
	echo <<<A
		<input type="submit" name="Submit" value="Add Students' Results" onClick="return confirmLink(this, 'add these results? you may not be allowed to undo the action.')">
A;
 }



echo <<<BB
		</td>
		</tr>
		<tr>
		<td colspan="9"><strong>Showing $rowbegin - $rowend of $allrows $themembers</strong></td>
		</tr>
BB;

}


?>
	<input type="hidden" name="season" value="<?php echo $_GET['season'] ?>" />
    <input name="subcount" type="hidden" value="<?php echo $num ?>">
  </table>
</form>
 <br>

<?php

	if ( $num == 0 ) {

	echo <<<NORECORD
	<div style="border:1px solid #E1E1E1; padding:10px 20px; width:300px; background:#F1F1F1; color:#444;">No Record Found</div>
NORECORD;

	}


?>

<!-- content ends here -->



<?php
require( "inc/footer.php" );
?>