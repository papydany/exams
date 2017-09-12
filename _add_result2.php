<?php
require( "inc/header.php" );
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

}

</script>
<?php
$_SESSION['s_session']  = $s_session;
$_SESSION['s_semester'] = $s_semester;
$_SESSION['s_level']    = $s_level;

require_once './correction_mode.php';

$disp = 'First & Second Semester ';
?>
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="10" class="text" id=title>
  <tr>
    <td class="hder"><b>Student's Result Management (<?php
echo GetlLevel( $s_level );
?>)</b><br/> <?php
echo $disp;
?> <?php
echo $s_session;
?>/<?php
echo $s_session + 1;
?> </td>
  </tr>
</table>


 <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
   <tr bgcolor=#FFFFFF>
    <td colspan="9"><strong><?php
echo "$w_update";
?></strong></td>
  </tr>
<?php
//set the number of columns
$columns = 1;

if ( !$rowstart ) {
    $rowstart = 0;
}

$ending = 50;


$s_sem = ' IN ("First Semester", "Second Semester")';
$season = 'NORMAL';


$new_query = 'SELECT DISTINCT sr.std_id, sr.matric_no, sr.level_id, sp.surname, sp.firstname, sp.othernames
FROM students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.department_id = sp.stddepartment_id && sr.faculty_id = '.$_SESSION['myfaculty_id'].' && sr.department_id = '.$_SESSION['mydepartment_id'].' && sr.level_id = '.$s_level.' && sr.semester '.$s_sem.' && sr.yearsession = "'.$s_session.'" && sr.season = "'.$season.'" ORDER BY sp.surname ASC, sp.matric_no ASC';

$new_query = 'SELECT DISTINCT sr.std_id, sr.matric_no, sr.level_id, deo.`programme_option`, deo.`do_id`, sp.surname, sp.firstname, sp.othernames
FROM dept_options as deo,students_reg as sr LEFT JOIN students_profile as sp ON sr.std_id = sp.std_id WHERE sr.department_id = sp.stddepartment_id && sp.stdcourse = deo.`do_id` && sp.`stddepartment_id` = deo.`dept_id` && sr.faculty_id = '.$_SESSION['myfaculty_id'].' && sr.department_id = '.$_SESSION['mydepartment_id'].' && sr.level_id = '.$s_level.' && sr.semester '.$s_sem.' && sr.yearsession = "'.$s_session.'" && sr.season = "'.$season.'" ORDER BY sp.surname ASC, sp.matric_no ASC';


$new_r = mysqli_query( $GLOBALS['connect'],  $new_query ) or die( 'PLease Restart This Action'."<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
$row_count = mysqli_num_rows ( $new_r );

if( 0!=mysqli_num_rows ($new_r) ) {

	$list = array();
	while( $r=mysqli_fetch_array($new_r) ) {
		$list[ $r['do_id'] ][] = $r;
	}
	mysqli_free_result($new_r);

$rowbegin = $rowstart + 1;
$rowend   = $rowstart + $row_count;
	$themembers = $row_count>1 ? 'Students' : 'Student';
	echo <<<TR
		<tr><td colspan="9"><strong>Showing $rowbegin - $rowend of $allrows $themembers</strong></td></tr>
		<tr style="color:#FFFFFF; background:#333333">
		<td><strong>S/No</strong></td>
		<td><strong>Surname</strong></td>
		<td><strong>First Name</strong></td>
		<td><strong>Othernames</strong></td>
		<td><strong>Matric No</strong></td>
		<td><strong>Registration Status</strong></td>
		<td><strong>Result Status</strong></td>
		<td><strong>All Results Status</strong></td>
		<td><strong>Action</strong></td>
		</tr>
TR;

	$incre = 0;
	$bgcolor = '#FFFFFF';
	
	foreach( $list as $k=>$v ) {
		echo '<tr style="background:#888888"><td colspan="9" style="text-align:center;font-style:italic; font-weight:700; color:#FFF">',$v[0]['programme_option'],'</td></tr>';
		foreach( $v as $kk ) {
			$bgcolor = $bgcolor == '#FFFFFF' ? '#DBE2F0' : '#FFFFFF' ;
			$incre++;
			$surname 	= $kk['surname'];
			$othernames  = $kk['othernames'];
			$firstname 	= $kk['firstname'];
			$matric_no 	= $kk['matric_no'];
			$std_id 	= $kk['std_id'];
			$rslevelid	= $kk['level_id'];		

			$rsquery2 = "SELECT *
			FROM registered_semester
			WHERE sem $s_sem AND
			ysession = '$s_session' AND
			std_id = '$std_id' && season = '$season'";

			$rsquery2 = mysqli_query( $GLOBALS['connect'],  $rsquery2 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
			$numrs     = mysqli_num_rows ( $rsquery2 );
			$rowrs     = mysqli_fetch_array( $rsquery2 );
			$rslevelid = $rowrs["rslevelid"];

			$reg_status = ($numrs != 0) ? 'Registered' : 'Not Registered';

			$query2ac = "SELECT *
			FROM students_results
			WHERE std_id = '$std_id' AND
			std_mark_custom1 $s_sem AND
			std_mark_custom2 = '$s_session' && period = '$season'";


			$query2ac = mysqli_query( $GLOBALS['connect'],  $query2ac ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
			$num2ac   = mysqli_num_rows ( $query2ac );
			$row2ac   = mysqli_fetch_array( $query2ac );
			$std_mark = $row2ac["std_mark"];


			if( $reg_status == 'Not Registered' ) {
				$res_status = "<img src=\"../images/wrong.png\" width=\"12\" height=\"12\">";
				$radd       = "[<a href='maxregcourses.php' style='color: blue; font-size: 10px'>Register Student</a>]";		
			}
			elseif ( $num2ac == 0 ) {
				$res_status = "<img src=\"../images/wrong.png\" width=\"12\" height=\"12\">";
				$radd       = "[<a href='_add_result3.php?std_id=$std_id&amp;mt=$matric_no&amp;rslevelid=$rslevelid&amp;season=$season&amp;rowstart=$rowstart' style='color: blue; font-size: 10px'>add result</a>]";
			} else {
				$res_status = "<img src=\"../images/tick.png\" width=\"12\" height=\"12\">";
				$radd       = "[<a href='_add_result3.php?std_id=$std_id&amp;mt=$matric_no&amp;rslevelid=$rslevelid&amp;season=$season&amp;rowstart=$rowstart' style='color: blue; font-size: 10px'>edit result</a>]";
			}			
		
			echo '<tr style=" background:',$bgcolor,'">',
					'<td>',$incre,'</td>',
					'<td><b>',strtoupper( $surname ),'</b></td>',
					'<td>',strtoupper( $firstname ),'</td>',
					'<td>',strtoupper( $othernames ),'</td>',
					'<td style="text-align:center;font-style:italic; font-weight:700; color:#FFF; background:#888">',$matric_no,'</td>',
					'<td>',$reg_status,'</td>',
					'<td>',$res_status,'</td>',
					'<td>[<a href="_view_allresult.php?s_session=',$_GET['s_session'],'&amp;std_id=',$std_id,'&mt=',$matric_no,'&rslevelid=',$rslevelid,'&rowstart=',$rowstart,'" style="color: blue; font-size: 10px">view this student\'s whole result</a>]
					</td>',
					'<td>',$radd,'</td>',			
				 '</tr>';
		}
	}
}





/*$rowbegin = $rowstart + 1;
$rowend   = $rowstart + $row_count;


$themembers = $row_count>1 ? 'Students' : 'Student';


if ( $row_count != 0 ) {

    echo <<<TR
	<tr>
    <td colspan="9"><strong>Showing $rowbegin - $rowend of $allrows $themembers</strong></td>
    </tr>
    <tr style="color:#FFFFFF; background:#333333">
    <td><strong>S/No</strong></td>
    <td><strong>Surname</strong></td>
    <td><strong>First Name</strong></td>
    <td><strong>Othernames</strong></td>
    <td><strong>Matric No</strong></td>
    <td><strong>Registration Status</strong></td>
    <td><strong>Result Status</strong></td>
    <td><strong>All Results Status</strong></td>
    <td><strong>Action</strong></td>
    </tr>
TR;

}

for ( $i = 1; $i <= $row_count; $i++ ) {
	
    if ( $i % 2 == 0 ) {
        $bgcolor = '#DBE2F0';
    } else {
        $bgcolor = '#FFFFFF';
    }
	
	$row = mysqli_fetch_array( $new_r );
	
	$surname 	= $row['surname'];
	$othernames  = $row['othernames'];
	$firstname 	= $row['firstname'];
	$matric_no 	= $row['matric_no'];
	$std_id 	= $row['std_id'];
	$rslevelid	= $row['level_id'];
	
    
    
    $rsquery2 = "SELECT *
    FROM registered_semester
    WHERE sem $s_sem AND
    ysession = '$s_session' AND
    std_id = '$std_id' && season = '$season'";
	
    $rsquery2 = mysqli_query( $GLOBALS['connect'],  $rsquery2 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
    $numrs     = mysqli_num_rows ( $rsquery2 );
    $rowrs     = mysqli_fetch_array( $rsquery2 );
	$rslevelid = $rowrs["rslevelid"];
    
	
	$reg_status = ($numrs != 0) ? 'Registered' : 'Not Registered';
    
    
    $query2ac = "SELECT *
    FROM students_results
    WHERE std_id = '$std_id' AND
    std_mark_custom1 $s_sem AND
    std_mark_custom2 = '$s_session' && period = '$season'";

	
    $query2ac = mysqli_query( $GLOBALS['connect'],  $query2ac ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
    $num2ac   = mysqli_num_rows ( $query2ac );
    $row2ac   = mysqli_fetch_array( $query2ac );
    $std_mark = $row2ac["std_mark"];
    
	
	if( $reg_status == 'Not Registered' ) {
        $res_status = "<img src=\"../images/wrong.png\" width=\"12\" height=\"12\">";
        $radd       = "[<a href='maxregcourses.php' style='color: blue; font-size: 10px'>Register Student</a>]";		
	}
    elseif ( $num2ac == 0 ) {
        $res_status = "<img src=\"../images/wrong.png\" width=\"12\" height=\"12\">";
        $radd       = "[<a href='_add_result3.php?std_id=$std_id&amp;mt=$matric_no&amp;rslevelid=$rslevelid&amp;season=$season&amp;rowstart=$rowstart' style='color: blue; font-size: 10px'>add result</a>]";
    } else {
        $res_status = "<img src=\"../images/tick.png\" width=\"12\" height=\"12\">";
        $radd       = "[<a href='_add_result3.php?std_id=$std_id&amp;mt=$matric_no&amp;rslevelid=$rslevelid&amp;season=$season&amp;rowstart=$rowstart' style='color: blue; font-size: 10px'>edit result</a>]";
    }

    
    if ( $i % $columns == 0 ) {
        //if there is no remainder, we want to start a new row
        echo "<TR>";
    }
    //echo "<TD valign='top'>" ;
    
    if ( $std_id ) {
?>
 <tr bgcolor=<?php
        echo $bgcolor;
?>>
    <td><?php
        echo $i;
?></td>
    <td><b><?php
        echo strtoupper( $surname );
?></b></td>
    <td><?php
        echo strtoupper( $firstname );
?></td>
    <td><?php
        echo strtoupper( $othernames );
?></td>
    <td><?php
        echo $matric_no;
?></td>
    <td><?php
        echo $reg_status;
?></td>
    <td><?php
        echo $res_status;
?></td>
    <td>[<a href='_view_allresult.php?s_session=<?php echo $_GET['s_session']; ?>&amp;std_id=<?php echo $std_id ?>&mt=<?php echo $matric_no ?>&rslevelid=<?php echo $rslevelid ?>&rowstart=<?php echo $rowstart ?>' style='color: blue; font-size: 10px'>view this student's whole result</a>]
	</td>
   
	<td><?php echo $radd ?>
	</td>
  </tr>
<?php
    }
    
    
    "</TD>";
    if ( ( $i % $columns ) == ( $columns - 1 ) || ( $i + 1 ) == $num_rows ) {
        //if there is a remainder of 1, end the row
        //or if there is nothing left in our result set, end the row
        echo "</TR>";
    }
}*/
?>
</table>

<?php
if ( $row_count == 0 ) {

	echo <<<NORECORD
	<div style="border:1px solid #E1E1E1; padding:10px 20px; width:300px; background:#F1F1F1; color:#444;">No Record Found</div>
NORECORD;

}

?>

<br>
<!-- content ends here -->



<?php
require( "inc/footer.php" );
?> 