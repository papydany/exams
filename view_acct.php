<?php require ("inc/header.php"); ?>

<!- content starts here -->
<script language="javascript" type="text/javascript">
function checkform (form)
{
//  if (document.form.eo_surname.value == "") {
//    alert( "Please enter your Surname" );
//    document.form.eo_surname.focus();
//    return false ;
//  }
// if (document.form.eo_firstname.value == "") {
//    alert( "Please fill in your First Name" );
//    document.form.eo_firstname.focus();
//    return false ;
//  }
  
 if (document.form.eo_username.value == "") {
    alert( "Please fill in your Username" );
    document.form.eo_username.focus();
    return false ;
  }  
   
if (document.form.eo_password.value == "")  {
    alert( "Please enter your Password" );
    document.form.eo_password.focus();
    return false ;
  }
  
  if (document.form.eo_password2.value == "")  {
    alert( "Please confirm your Password" );
    document.form.eo_password2.focus();
    return false ;
  }
  
  if (document.form.eo_password.value != document.form.eo_password2.value)  {
    alert( "Your Passwords do not match" );
    document.form.eo_password.focus();
    return false ;
  }

//  if (document.form.eo_email.value == "")  {
//    alert( "Please enter your Email Address" );
//    document.form.eo_email.focus();
//    return false ;
//  }
//
//  if (document.form.eo_email2.value == "")  {
//    alert( "Please confirm your Email Address" );
//    document.form.eo_email2.focus();
//    return false ;
//  }
//  
//  if (document.form.eo_email.value != document.form.eo_email2.value)  {
//    alert( "Your Email Addresses do not match" );
//    document.form.eo_email.focus();
//    return false ;
//  }
  
  
// return < aheref 
}

</script>

<?php
$query = "SELECT *
	FROM exam_officers
	WHERE examofficer_id = '$_SESSION[myexamofficer_id]'
	LIMIT 1";
$result = mysqli_query( $GLOBALS['connect'], $query);
$num = mysqli_num_rows ($result);

$row = mysqli_fetch_array($result);

$examofficer_id = $row["examofficer_id"];
$programme_id = $row["programme_id"];
$faculty_id = $row["faculty_id"];
$department_id = $row["department_id"];
$eo_username = $row["eo_username"];
$eo_title = $row["eo_title"];
$eo_surname = $row["eo_surname"];
$eo_firstname = $row["eo_firstname"];
$eo_othernames = $row["eo_othernames"];
$eo_password = $row["eo_password"];
$eo_email = $row["eo_email"];
$eo_date_reg = $row["eo_date_reg"];

$eo_surname = stripslashes($eo_surname);
$eo_firstname = stripslashes($eo_firstname);
$eo_othernames = stripslashes($eo_othernames);

?>

<form action="update_acct.php" method="post" name="form" id="form">
  <table width="40%" border="0" align="center" cellpadding="10" cellspacing="0" class="text9" style="margin:0 auto">
    <tr>
    	<?php 
    		if( isset($w_update) ) {
    			echo '<div style="padding: 10px; font-size:14px; background:#FFF8E7; border:1px solid #FFEAB7; font-weight:700; color:#D79600">',$w_update,'</div>';
    		}
    	?>
    </tr>
    <tr>
      <td>Title</td>
      <td><select name="eo_title" id="eo_title">
        <option value="Mr." <?php if ($eo_title == "Mr.") { echo "selected"; } ?>>Mr</option>
        <option value="Mrs." <?php if ($eo_title == "Mrs.") { echo "selected"; } ?>>Mrs</option>
        <option value="Dr." <?php if ($eo_title == "Dr.") { echo "selected"; } ?>>Dr</option>
        <option value="Prof." <?php if ($eo_title == "Prof.") { echo "selected"; } ?>>Prof</option>
        <option value="Engr." <?php if ($eo_title == "Engr.") { echo "selected"; } ?>>Engr</option>
      </select>
      </td>
    </tr>
    <tr>
      <td>Surname </td>
      <td><input name="eo_surname" type="text" id="eo_surname" size="30" value="<?php echo $eo_surname ?>"></td>
    </tr>
    <tr>
      <td>First Name </td>
      <td><input name="eo_firstname" type="text" id="eo_firstname" size="30" value="<?php echo $eo_firstname ?>"></td>
    </tr>
	 <tr>
      <td>Othernames </td>
      <td><input name="eo_othernames" type="text" id="eo_othernames" size="30" value="<?php echo $eo_othernames ?>"></td>
    </tr>
<?php 
if ($_SESSION['myusername'] != 'admin') {
?>
    <tr>
      <td>Username</td>
      <td><input name="eo_username" type="text" id="eo_username" size="30" value="<?php echo $eo_username ?>" readonly="readonly"></td>
    </tr>
<?php 
}
else {
?>
    <tr>
      <td>Username</td>
      <td><input name="eo_username" type="text" id="eo_username" size="30" value="<?php echo $eo_username ?>" readonly> (please this cannot be editted)</td>
    </tr>
<?php 
}
?>
    <tr>
      <td>Password </td>
      <td><input name="eo_password" type="password" id="eo_password" size="30" value="<?php echo $eo_password ?>" readonly="readonly"></td>
    </tr>
    <tr>
      <td>Confirm Password </td>
      <td><input name="eo_password2" type="password" id="eo_password2" size="30" value="<?php echo $eo_password ?>" readonly="readonly"></td>
    </tr>
    <tr>
      <td>Email </td>
      <td><input name="eo_email" type="text" id="eo_email" size="30" value="<?php echo $eo_email ?>"></td>
    </tr>
    <tr>
      <td>Confirm Email </td>
      <td><input name="eo_email2" type="text" id="eo_email2" size="30" value="<?php echo $eo_email ?>"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Update" onClick="return checkform(this)"></td>
    </tr>
  </table>
</form>

<!- content ends here -->



<?php require ("inc/footer.php"); ?>