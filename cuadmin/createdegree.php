<?php 
	require_once 'inc/header.php';
    require_once '../config.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.us { width:735px;}
.us thead{ background:#666; color:#FFF;}
.us thead th, .us tbody td{ border-bottom:1px solid #EEE; padding:5px; text-align:left}
.i{
  margin:0 3px;
  font-style:normal;
  color:#bbb
}
</style>

<p style="font-size:16px; font-weight:700; padding:6px 10px 5px;margin:60px 0 0 50px; background:#666; display:inline-block; color:#FFFFFF;">create  Degree</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" autocomplete="off">
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1">
  <tr>

	  

    <td width="" class="td"><p>Programme</p><label for="select2"></label>
      <select name="programme" id="select2">
      <option value="">--Select Programme--</option>
	<?php
    $l_prog = mysqli_query( $GLOBALS['connect'], 'SELECT `programme_id`, `programme_name` FROM `programme` order by programme_name asc');
    while( $r=mysqli_fetch_assoc($l_prog) ) {
    	echo '<option value="',$r['programme_id'],'">',$r['programme_name'],'</option>';
    }
	mysqli_free_result($l_prog);
    ?>
      </select></td>
  
  	<td class="td"><p>Class of Degree</p>
  		<input name="degree" type="text" value=""/>
  	</td>
  </tr>
  <tr>
  <td colspan="4" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="Select"></td>
  </tr>  
</table>
</form>


<?php
 //==== delete script for departments========

if (isset($_POST['submit']) && $_POST['programme']!='' && $_POST['degree']!='') {
	$degree=$_POST['degree'];
	$programme=$_POST['programme'];

	$sql = "INSERT INTO  degree (degree_name, programme_id) VALUES('$degree','$programme')";
	
	$query = mysqli_query( $GLOBALS['connect'], $sql) or die (mysqli_error($GLOBALS['connect']));
	if($query) {
		echo '<p><font color="#FF0000">Class of Degree Created Successful </font></p>';
	}
}else{
	echo'<p><font color="#FF0000">Please fill the form completely </font></p>';
}
	




	//----------------------  form_cFOS  -----------------------//	
?>
  

  <!-- content ends here -->
  

<?php require_once 'inc/footer.php'; ?>