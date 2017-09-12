<?php require ("inc/header.php"); ?>

<!- content starts here -->
<script language="javascript" type="text/javascript">
function checkform ( form )
{

 if (document.form.from_sem.value == "") {
    alert( "Please select Semester" );
    document.form.from_sem.focus();
    return false ;
  }
  
 if (document.form.from_ses.value == "") {
    alert( "Please select Session" );
    document.form.from_ses.focus();
    return false ;
  }  
   
if (document.form.to_sem.value == "")  {
    alert( "Please select Semester" );
    document.form.to_sem.focus();
    return false ;
  }

 if (document.form.to_ses.value == "") {
    alert( "Please select Session" );
    document.form.to_ses.focus();
    return false ;
  }  

  if (document.form.reason.value == "") {
    alert( "Please fill in the Reason" );
    document.form.reason.focus();
    return false ;
  }
  if (document.form.sus_type.value == "") {
    alert( "Please select Suspension Type" );
    document.form.sus_type.focus();
    return false ;
  }

}

</script>

<?php
$query22 = "SELECT *
	FROM suspensions
	WHERE std_id = '$std_id' AND
	sus_id = '$sus_id'";
$query22 = mysqli_query( $GLOBALS['connect'], $query22)
or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
$num22 = mysqli_num_rows ($query22);
$row22 = mysqli_fetch_array($query22);

$sus_id = $row22["sus_id"];
$std_id = $row22["std_id"];
$from_sem = $row22["from_sem"];
$from_ses = $row22["from_ses"];
$to_sem = $row22["to_sem"];
$to_ses = $row22["to_ses"];
$reason = $row22["reason"];
$sus_type = $row22["sus_type"];

$reason = stripslashes($reason);
?>

<form action="update_susp.php?programme_id=<?php echo $programme_id ?>&faculty_id=<?php echo $faculty_id ?>&department_id=<?php echo $department_id ?>" method="post" name="form" id="form">
<input name="std_id" type="hidden" value="<?php echo $std_id ?>">
<input name="sus_id" type="hidden" value="<?php echo $sus_id ?>">
  <table width="90%" border="0" align="center" cellpadding="10" cellspacing="0" class="text">
    <tr>
      <td colspan="2" class="border2"><strong style="color:#FF9900"><?php echo $w_update ?></strong></td>
    </tr>
    <tr>
      <td colspan="2"><b><u>Suspension</u></b> <br><br><?php echo StudentsNameMatric ($std_id) ?></td>
    </tr>
    <tr>
      <td>From</td>
      <td><select name="from_sem" id="from_sem">
       <option value="">Select</option>
        <option value="<?php echo $exams_coursesemester_wd1 ?>" <?php if ($from_sem == $exams_coursesemester_wd1) { echo "selected"; } ?>><?php echo $exams_coursesemester_wd1 ?></option>
        <option value="<?php echo $exams_coursesemester_wd2 ?>" <?php if ($from_sem == $exams_coursesemester_wd2) { echo "selected"; } ?>><?php echo $exams_coursesemester_wd2 ?></option>
      </select>

<select name="from_ses" id="from_ses">
       <option value="">Select</option>
<?php
$tillyear = $gen_year;
for ($year = date(Y); $year >= $tillyear; $year--) {
?>
    <option value="<?php echo $year ?>"  <?php if ($from_ses == $year) {echo "selected"; } ?>><?php echo $year ?>/<?php echo $year+1 ?></option>
<?php
}
?>
          </select>
</td>
    </tr>
    <tr>
      <td>To</td>
      <td><select name="to_sem" id="to_sem">
       <option value="">Select</option>
        <option value="<?php echo $exams_coursesemester_wd1 ?>" <?php if ($to_sem == $exams_coursesemester_wd1) { echo "selected"; } ?>><?php echo $exams_coursesemester_wd1 ?></option>
        <option value="<?php echo $exams_coursesemester_wd2 ?>" <?php if ($to_sem == $exams_coursesemester_wd2) { echo "selected"; } ?>><?php echo $exams_coursesemester_wd2 ?></option>
      </select>

<select name="to_ses" id="to_ses">
       <option value="">Select</option>
<?php
$tillyear = $gen_year;
for ($year = date(Y); $year >= $tillyear; $year--) {
?>
    <option value="<?php echo $year ?>"  <?php if ($to_ses == $year) {echo "selected"; } ?>><?php echo $year ?>/<?php echo $year+1 ?></option>
<?php
}
?>
          </select>
</td>
    </tr>
    <tr>
      <td>Reason</td>
      <td><textarea name="reason" cols="30" rows="4" id="reason"><?php echo $reason ?></textarea></td>
    </tr>
    <tr>
      <td>Suspension Type</td>
      <td><select name="sus_type" id="sus_type">
       <option value="">Select</option>
        <option value="Suspension" <?php if ($sus_type == "Suspension") {echo "selected"; } ?>>Suspension</option>
        <option value="Withdrawal" <?php if ($sus_type == "Withdrawal") {echo "selected"; } ?>>Withdrawal</option>
        <option value="Expulsion" <?php if ($sus_type == "Expulsion") {echo "selected"; } ?>>Expulsion</option>
      </select>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Update" onClick="return checkform(this)"> &nbsp;&nbsp;&nbsp;&nbsp;<a href="delete_susp.php?std_id=<?php echo $std_id ?>&sus_id=<?php echo $sus_id ?>&programme_id=<?php echo $programme_id ?>&faculty_id=<?php echo $faculty_id ?>&department_id=<?php echo $department_id ?>" style="font-family: Tahoma, Verdana, Arial; font-size: 11px; color: #006699;border: 1px ridge #72A4D5;background-color: #F4F4F7;padding-top: 2px; padding-right: 12px; padding-bottom: 2px; padding-left:12px; text-decoration: none">Delete</a></td>
    </tr>
  </table>
</form>

<!- content ends here -->



<?php require ("inc/footer.php"); ?>