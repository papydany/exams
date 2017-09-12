<?php require ("inc/header.php"); ?>

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
  if (document.form.s_level.value == "") {
    alert( "Please select Level" );
    document.form.s_level.focus();
    return false ;
  }    
// return < aheref 
}

</script>
<script language="javascript" type="text/javascript">
function display(rid){

		document.getElementById("pti").value = rid.value;
		}
</script>
<?php
unset( $_SESSION['s_session'], $_SESSION['s_semester'], $_SESSION['s_level'] );
?>

<table width="95%" border="0" align="center" cellspacing="0" cellpadding="10" class="text" id=title>
  <tr>
    <td class="hder"><b>Student's Result Management</b></td>
  </tr>
</table>

<form name="form" method="get" action="_add_result2.php">
  <table width="95%" border="0" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td>Session</td>
      <td><select name='s_session' id='s_session'>
                              <option value="" selected>Choose A Session</option>
<?php
for ($year = (date('Y')-1); $year >= 1998; $year--) { $yearnext =$year+1;
	echo "<option value='$year'>$year/$yearnext</option>\n";
} 
?>
</select></td>
    </tr>
    <tr>
      <td>Semester</td>
      <td><select name="s_semester" id ="s_semester">
        <option value="First Semester">First | Second Semester</option>
      </select>
      </td>
    </tr>



    <tr>
      <td>Level</td>
      <td><select name="s_level" id="s_level">
        <option value="">Choose Level</option>
        <?php
$query = "SELECT *
	FROM level
	WHERE programme_id = '$_SESSION[myprogramme_id]'
	ORDER BY level_name";
$result = mysqli_query( $GLOBALS['connect'], $query)
or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
while ($row = mysqli_fetch_assoc($result)) { 
$level_id = $row["level_id"];
$level_name = $row["level_name"];
?>
        <option value="<?php echo $level_id ?>" <?php if ($level_id == $level_id1) { echo "selected"; } ?>><?php echo $level_name ?></option>
        <?php
}
?>
      </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Continue" onClick="return checkform(this);"></td>
    </tr>
  </table>
</form>

<!-- content ends here -->



<?php require ("inc/footer.php"); ?>