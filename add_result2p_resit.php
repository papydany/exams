<?php
require( "inc/header.php" );
require( dirname(__FILE__)."/updates.php");

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
    $_SESSION['orderBy'] = "ORDER BY students_profile.matric_no ASC, students_profile.surname ASC, students_profile.firstname ASC, students_profile.othernames ASC";
}

?>


<!-- content starts here -->
<script type="text/javascript">
function killBackSpace(e) {
	e = e ? e : window.event;
	var t = e.target ? e.target : e.srcElement ? e.srcElement : null;
	
	if( t.tagName.toLowerCase() == 'input' && t.getAttribute("readonly") == "readonly" ){
		return false;
	}
	
	if (t && t.tagName && (t.type && /(password)|(text)|(file)/.test(t.type.toLowerCase())) || t.tagName.toLowerCase() == 'textarea')
		return true;
	
	var k = e.keyCode ? e.keyCode : e.which ? e.which : null;
	if (k == 8) {
		if (e.preventDefault)
			e.preventDefault();
		return false;
	};
	return true;
};

if (typeof document.addEventListener != 'undefined')
	document.addEventListener('keydown', killBackSpace, false);
else if (typeof document.attachEvent != 'undefined')
	document.attachEvent('onkeydown', killBackSpace);
else {
	if (document.onkeydown != null) {
		var oldOnkeydown = document.onkeydown;
		document.onkeydown = function (e) {
			oldOnkeydown(e);
			killBackSpace(e);
		};
	}
	else document.onkeydown = killBackSpace;
}

</script>


<script language="javascript" type="text/javascript">

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

</script>



<?php
$_SESSION['s_session']  = $_GET['s_session'];

$_SESSION['s_level']    = $_GET['s_level'];
//$pti                    = $_POST['nation'];

$xyz = explode('-', $_GET['cs_code']);
$cs_code = $xyz[0];
$sem = $xyz[1];

$_SESSION['s_semester'] = $sem;
//$_GET['s_semester'] = $sem;


if( empty($_SESSION['s_semester']) ) {
	exit('Please Restart This Action');
}

$query = 'SELECT course_title, course_code FROM all_courses WHERE thecourse_id = '.$cs_code.' LIMIT 1';

$result = mysqli_query( $GLOBALS['connect'],  $query ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );

while ( $row = mysqli_fetch_assoc( $result ) ) {
    $stdcourse_custom1 = $row["course_title"];
    $stdcourse_custom2 = $row["course_code"];
}



?>
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="10" id=title>
  <tr>
    <td class="hder"><b>Student's Result Management (<?php echo $_SESSION['myprogramme_id'] == 7 ? ' Contact '.substr(GetlLevel($_GET['s_level']),0,1) : ' Level '.GetlLevel($_GET['s_level']); ?> )</u></b><br/>
	<?php
		echo $_SESSION['s_semester'].' '; 
		echo $_SESSION['myprogramme_id'] == 7 ? $_GET['s_session'] : $_GET['s_session'].'/'.($_GET['s_session'] + 1); ?> 
        &nbsp;&nbsp;<span>(<?php echo $stdcourse_custom2; echo !empty($stdcourse_custom1) ? '&nbsp;&nbsp;-&nbsp;&nbsp;'.$stdcourse_custom1 : ''; ?>)</span></td>
  </tr>
  

  
</table>


<?php
		echo '<form name="form1" method="post" action="result_insert.php" autocomplete="off">';		
?>
<?php
		
		if( $_GET['season']=='VACATION' ) {
			$season = 'VACATION';
			$s_sem = ' IN ("First Semester", "Second Semester")';
		} else {
			$season = 'NORMAL';
			$s_sem = ' IN ("First Semester", "Second Semester")';
		}

		$do_ids = load_fos( $_SESSION['myusername'] );
		$fos_ids = array();
		foreach( $do_ids as $k=>$v ) {$fos_ids[] = $v['do_id'];}


		$query3 = "SELECT course_reg.*, `dept_options`.`do_id`, `dept_options`.`programme_option`, students_profile.matric_no, students_profile.surname, students_profile.firstname, students_profile.othernames 
		FROM `dept_options`, course_reg 
		LEFT JOIN students_profile 
		ON (course_reg.std_id = students_profile.std_id) 
		WHERE `dept_options`.`dept_id` = students_profile.`stddepartment_id` 
		&& `dept_options`.`do_id` = `students_profile`.stdcourse 
		&& `course_reg`.clevel_id = $_GET[s_level] 
		&& course_reg.thecourse_id ='$cs_code' 
		AND course_reg.csemester $s_sem 
		AND course_reg.cyearsession = '$_GET[s_session]' 
		&& course_reg.course_season = '$season' 
		&& students_profile.stdfaculty_id = '$_SESSION[myfaculty_id]' 
		&& students_profile.stddepartment_id = '$_SESSION[mydepartment_id]' 
		&& `dept_options`.do_id IN (".implode(',', $fos_ids).")
		GROUP BY course_reg.std_id {$_SESSION['orderBy']}";



		$result3  = mysqli_query( $GLOBALS['connect'],  $query3 );
		$i = 0;
		
	if( isset($_GET['w_update']) ) {
		echo $_GET['w_update'] == 'Result not successfully added.' ? '<div style="width:300px; padding:5px 40px;margin:5px 0; color:red; border:1px solid red;">No Result Was Added</div>' : '<div style="color:green;width:300px; padding:5px 40px;margin:5px 0;background:#E1FFE1; border:1px solid #B7FFB7;">Result Added/Updated Successfully</div>'; 
	}


if( 0!=mysqli_num_rows ($result3) ) {
	echo <<<OPTIONS
		<div style="background:#CCC; display:block; overflow:hidden; margin-top:5px; padding:3px; text-align:center">
		<div style="float:left; "></div>
		<input name="result_type" type="hidden" value="resit" />   
		<select name="jumpMenu2" style="float:right" id="jumpMenu2" onchange="MM_jumpMenu('parent',this,0)">
		<option value="add_result2p_resit.php?{$_SERVER['QUERY_STRING']}">Resit</option>
		<option value="add_result2p_correctional.php?{$_SERVER['QUERY_STRING']}">Correctional</option>
		<option value="add_result2p.php?{$_SERVER['QUERY_STRING']}">Sessional Result</option>
		<option value="add_result2p_omitted.php?{$_SERVER['QUERY_STRING']}">Omitted</option>
		</select>
		
		</div>
OPTIONS;
} else {
	echo '<div style="width:300px; padding:5px 40px;margin:5px 0; color:red; border:1px solid red;">No Record Found</div>';
}
?>
<input name="cs_code" type="hidden" value="<?php echo $cs_code; ?>">
<input name="c_code" type="hidden" value="<?php echo $stdcourse_custom2; ?>">

  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableform">
    
	
	<?php
	
		if( $_GET['season']=='VACATION' ) {
			$season = 'VACATION';
			$s_sem = ' IN ("First Semester", "Second Semester")';
		} else {
			$season = 'NORMAL';
			$s_sem = ' IN ("First Semester", "Second Semester")';
		}

		$do_ids = load_fos( $_SESSION['myusername'] );
		$fos_ids = array();
		foreach( $do_ids as $k=>$v ) {$fos_ids[] = $v['do_id'];}


		$query3 = "SELECT course_reg.*, `dept_options`.`do_id`, `dept_options`.`programme_option`, students_profile.matric_no, students_profile.surname, students_profile.firstname, students_profile.othernames   
		FROM `dept_options`, course_reg 
		LEFT JOIN students_profile 
		ON (course_reg.std_id = students_profile.std_id) 
		WHERE `dept_options`.`dept_id` = students_profile.`stddepartment_id` 
		&& `dept_options`.`do_id` = `students_profile`.stdcourse 
		&& `course_reg`.clevel_id = $_GET[s_level] 
		&& course_reg.thecourse_id ='$cs_code' 
		AND course_reg.csemester $s_sem 
		AND course_reg.cyearsession = '$_GET[s_session]' 
		&& course_reg.course_season = '$season' 
		&& students_profile.stdfaculty_id = '$_SESSION[myfaculty_id]' 
		&& students_profile.stddepartment_id = '$_SESSION[mydepartment_id]' 
		&& `dept_options`.do_id IN (".implode(',', $fos_ids).")  
		GROUP BY course_reg.std_id {$_SESSION['orderBy']}";

		
		$result3  = mysqli_query( $GLOBALS['connect'],  $query3 );
		$i = 0;

if( 0!=mysqli_num_rows ($result3) ) {
	
	$num      = mysqli_num_rows ( $result3 );
	$thisrows = $num;

	$rowbegin = /*$rowstart +*/ 1;
	$rowend   = /*$rowstart +*/ $thisrows;
	$themembers = $num > 1 ? 'Students' : 'Student';

	$list = array();
	while( $r=mysqli_fetch_array($result3) ) {
		$list[ $r['do_id'] ][] = $r;
	}
	mysqli_free_result($result3);
	

		echo <<<TH
		  <tr style="color:#FFF; background:#333">
		  <th width="3%"> </th>
		  <th width="3%"><strong>S/No</strong></th>
		  <th width="12%"><strong>Surname</strong></th>
		  <th width="12%"><strong>First Name</strong></th>
		  <th width="12%"><strong>Othernames</strong></th>
		  <th width="12%"><strong>Matric No</strong></th>
		  <th width="5%"><b>Unit</b></th>
		  <th width="5%"><b>Type</b></th>
		  <th width="12%"><b>Counts In GPA</b></th>
		  <th width="12%"><b>Mark</b></th>
		  <th width="15%"><b>Reason</b></th>
		</tr>
TH;
	foreach( $list as $k=>$v ) {
		echo '<tr style="background:#E8EAEA"><td colspan="11" style="text-align:center;font-style:italic; font-weight:700;color:#AAA;">',
		$v[0]['programme_option'],'</td></tr>';		
		$bgcolor = '';
		foreach( $v as $kk ) {
			
			//$i++;  ================= ref line: 322
			$bgcolor = $bgcolor == '#FFFFFF' ? '#FAFAFA' : '#FFFFFF';
			$std_id            = $kk["std_id"];
			$course_id         = $kk["thecourse_id"];
			$c_unit            = $kk["c_unit"];
			$stdcourse_custom3 = $kk["stdcourse_custom3"];
			$matric_no         = $kk["matric_no"];
			$surname           = stripslashes( $kk["surname"] );
			$firstname         = stripslashes( $kk["firstname"] );
			$othernames        = stripslashes( $kk["othernames"] );
			$course_custom1 = 'YES';	
			
			$rsquery2 = "SELECT * FROM registered_semester WHERE sem $s_sem AND ysession = '$_GET[s_session]' AND std_id = '$std_id' && season = '$season'";
			$rsquery2 = mysqli_query( $GLOBALS['connect'],  $rsquery2 ) or die( "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>" );
			$numrs     = mysqli_num_rows ( $rsquery2 );
			$rowrs     = mysqli_fetch_array( $rsquery2 );
			$rslevelid = $rowrs["rslevelid"];			
			

			$query2ac = "SELECT * FROM students_results 
			WHERE std_id = '$std_id' 
			AND std_mark_custom1 $s_sem 
			AND	std_mark_custom2 = '$_GET[s_session]' 
			AND stdcourse_id = '$course_id' 
			&& period = '$season' 
			AND std_grade IN ('F', 'N')
			AND result_approved = 'Y' 
			ORDER BY stdresult_id DESC ";
			
			//$query2ac = "SELECT * FROM students_results WHERE std_id = '$std_id' AND std_mark_custom1 $s_sem AND
			//std_mark_custom2 = '$_GET[s_session]' AND stdcourse_id = '$course_id' && period = '$season' AND std_grade IN ('F', 'N')";
			//echo $query2ac;
//AND result_flag != 'Resit' since resit result is only allowed once, after that, it can not be taken again
			
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
			
			if ($num2ac != 0) { $i++;  // this where the filtering begins FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF Note: counter here
				
			if ( $std_id ) {
				/*
        				( $pti != "all" ) ? "<td><input type=\"checkbox\" name=\"check[$i]\" id=\"check[$i]\" value=\"$i\" /></td>" : "<td><input type=\"hidden\" name=\"check[$i]\" id=\"check[$i]\" value=\"$i\"></td>",
				 */
				$x = '0~~'.$_SESSION['s_semester'].'~~'.$_GET['s_level'];
				$stdid[$std_id] = $std_id; // get array counter for displayed results
				
				echo '<tr style="background:',$bgcolor,'">',
						'<td><input type="checkbox" name="check['.$i.']" id="check['.$i.']" value="'.$i.'" /></td>',
						'<td>',$i,'</td>',
						'<td><b>',strtoupper( $surname ),'</b></td>',
						'<td>',strtoupper( $firstname ),'</td>',
						'<td>',strtoupper( $othernames ),'</td>',
						'<td style="text-align:center;font-style:italic; font-weight:700; /*color:#FFF;*/ background:#E8EAEA">',$matric_no,'<input name="xcos_id" type="hidden" value="',$course_id,'"></td>',
						'<td>',$c_unit,'<input name="c_unit[',$i,']" type="hidden" value="',$c_unit,'"></td>',
						'<td>',$stdcourse_custom3,'</td>',
						'<td>',$course_custom1,'<input name="course_custom1[',$i,']" type="hidden" value="',$course_custom1,'">';

				if ( $num2ac == 0 ) {
				  echo <<<TD
						<td>
						<input style="width:30px; text-align:center" name="std_mark2[$i]" type="text" tabindex="$i"  value="" onKeyUp="updA(this, 'd$i', 'check[$i]')" size="1" maxlength="1">
						<input name="std_mark[$i]" style=" background:#f0f0f0;width:60px" type="text" id="d$i" onChange="if (this.value!='') document.getElementById('check[$i]').checked=true" value="$std_mark" size="5" maxlength="5">
						<input name="std_id[$i]" type="hidden" value="$std_id">
						<input name="cos_id[$i]" type="hidden" value="$course_id">
						<input name="matric_no[$i]" type="hidden" value="$matric_no">
						</td>
						<td>
						<input name="reason[$i]" style=" width:150px;" type="text" value="{$row2ac['reason_of_correction']}">
						</td>			
TD;
					} else {
			
								
						$grade = return_grade( $std_mark );
						echo <<<TD
						  <td>
						  <input style="width:30px; text-align:center" name="std_mark2[$i]" type="text" tabindex="$i"  value="$grade[grade]" onKeyUp="updA(this, 'd$i', 'check[$i]')" size="1" maxlength="1">
						  <input name="std_mark[$i]" style=" background:#f0f0f0; width:60px" type="text" id="d$i" onChange="if (this.value!='') document.getElementById('check[$i]').checked=true" value="$std_mark" size="5" maxlength="5">
						  <input name="std_id[$i]" type="hidden" value="$std_id">
						  <input name="cos_id[$i]" type="hidden" value="$course_id">
						  <input name="matric_no[$i]" type="hidden" value="$matric_no">
						  </td>
						  <td>
						  <input name="reason[$i]" style="width:150px;" type="text" value="{$row2ac['reason_of_correction']}">
						  </td>
TD;
			
					}
																				
			echo '</tr>';	
			
			} } // THE filtering ends here FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF
		
		}
	}

echo <<<BOTTOM
	<tr>
	<td colspan=11 style=" background:#CCC; padding:5px;">
	<a href="add_result.php?s_session=$_GET[s_session]&s_semester=$_GET[s_semester]&&s_level=$_GET[s_level]&Submit=Continue" class="ibutton" style="float:left; font-size:12px; color:#333; text-decoration:none;">< go back</a>
BOTTOM;

//if ( $priv_status != 'no' || $numca != 0 || $numcas <> 0 ) {

$rec = count($stdid);
if ($rec > 0) {
	echo <<<A
		<input type="submit" name="Submit" style="float:right" value="Save Results" onClick="return confirmLink(this, 'add these results? you may not be allowed to undo the action.')">
A;


$themembers = $rec > 1 ? 'Students' : 'Student';
echo <<<BB
		</td>
		</tr>
		<tr>
		<td colspan="11"><strong>Showing 1 - $rec of $rec $themembers</strong></td>
		</tr>
BB;
} else {
	echo <<<BB
		</td>
		</tr>
		<tr>
		<td colspan="11"><strong>No result found</strong></td>
		</tr>
BB;
}
// }



 

	
	echo '<input name="certificate" type="hidden" value="',basename($_SERVER['PHP_SELF']),'?',$_SERVER['QUERY_STRING'],'">';
	
	echo '<input type="hidden" name="season" value="',$_GET['season'],'" />',
         '<input name="subcount" type="hidden" value="',$num,'">',
		 '</table>',
		 '</form>';

}


?>
<!-- content ends here -->
</table>



<?php
require( "inc/footer.php" );
?>