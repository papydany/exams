<?php
require_once 'inc/header.php';
    require_once '../config.php';
//require( dirname(__FILE__)."/updates.php");

$deptname = strtoupper($_POST['deptname']);
$faculty = $_POST['faculty'];
$deptcode = strtoupper($_POST['deptcode']);

if(isset($_POST['adddept']) && ($deptname != " " && $faculty != " " && $deptcode != " ")){
	
	$queryab = "SELECT * FROM departments WHERE departments_name = '". $_POST['deptname']. "'";
	$sqlab = mysqli_query( $GLOBALS['connect'], $queryab) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	
	if(mysqli_num_rows($sqlab) < 1 ){
	
	$query2ab = "INSERT INTO departments(departments_name,fac_id) VALUES('$deptname','$faculty')";
	$query2ab = mysqli_query( $GLOBALS['connect'], $query2ab) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	
	//if ( 0!=mysqli_num_rows($query2ab) ) {
	$msg = '<div class="info">Department Successfully Added</div>';
	
	$_POST['deptname'] = " ";
	$_POST['deptcode'] = " ";
	
	}else{
		
		$msg = '<div class="info">Department already exists!</div>';
	}
	
} else {
	//$msg = '<div class="info">All fields are required!</div>';
}

?>
<!-- content starts here -->
<style>

select{ width:120px; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
</style>

<form action="" method="post" autocomplete="off" >
  <table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left"><?php echo $msg; ?>&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><table width="50%" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td width="22%">&nbsp;</td>
          <td width="78%">&nbsp;</td>
        </tr>
        <tr>
          <td><span>Department Name</span></td>
          <td><input type="text" name="deptname" id="deptname"></td>
        </tr>
        <tr>
          <td><span>Facaulty</span></td>
          <td>
          <select name="faculty" id="faculty">
          <option value="">---Pick a Faculty---</option>
       <?php
          $ac = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM faculties');
        while( $l=mysqli_fetch_assoc($ac) ) {
			
	?>
          <option value="<?php echo $l['faculties_id']; ?>"><?php echo $l['faculties_name']; ?></option>
          
    <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td height="50"><p>&nbsp;
            </p>
            <p>
              <input type="submit" name="adddept" id="adddept" onClick="return validate();" value="Add Department">
            </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
<script type="text/javascript" src="validator.js" defer="defer"></script>
<script type="text/javascript">
    function validate() 
    {
	//code here....
	var deptname = document.getElementById('deptname').value;
	var faculty = document.getElementById('faculty').value;
	//var deptcode = document.getElementById('deptcode').value;
	
	if (deptname == "") {
    alert("Department name is required!");
    return false;
} else if (faculty == "") {
    alert("Faculty selection is required!");
    return false;
//} else if (deptcode == "") {
  //  alert("Department code is required!");
  //  return false;
} else {
	
	return true;
}

}
</script>
<?php
require_once( "inc/footer.php" );
?>