<?php
require ("inc/header.php");
?>

<!-- content starts here -->
<script language="javascript" type="text/javascript">
function checkform ( form )
{
  if (document.form.yearsession.value == "") {
    alert( "Please select Session" );
    document.form.yearsession.focus();
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

}

function display(rid){

		document.getElementById("pti").value = rid.value;
		}
</script>

<?php
unset( $_SESSION['yearsession'],$_SESSION['s_semester'],$_SESSION['s_level'] );
?>

<form name="form" method="get" action="<?php if ( isset($_GET['p']) && $_GET['p'] == "1"){echo "add_result2.php"; } else { echo "add_result.php"; } ?>">
  <table width="40%" style="margin:0 auto" border="0" align="center" cellpadding="5" cellspacing="5">
    <tr>
        <?php
      if($_SESSION['myprogramme_id']==7){

      echo'<td width="" class="td"><p>Month Of Entery</p><label for="select"></label></td>
      <td><select name="month" id="month">
      <option value="">.. Pick Month ..</option>
      <option value="1">APRIL</option>
      <option value="2">AUGUST</option>
      </select></td></tr>


      <tr><td width="" class="td">
<span id="result"></span>
      <p>Session</p><label for="select2"></label></td>
      <td><select name="yearsession" id="yearsession">
     <option value="">-------------</option>
      </select></td>'; 
    }else{
    ?>
  
    <td width="" class="td"><p>Session</p><label for="select2"></label></td>
      <td><select name="yearsession" id="yearsession">
          <option value="">select section</option>
    <?php
            for ($year = (date('Y')-1); $year >= 1998; $year--) {

        
            $yearnext =$year+1;
            echo "<option value=\"$year\">$year/$yearnext</option>\n";
      
        }
 echo'</select></td>';
            } 
        ?> 
    </tr>
    <tr>
      <td>Semester</td>
      <td><select name="s_semester" id ="s_semester">
        <option value="" selected>Choose Semester</option>
        <option value="Both">First / Second Semester</option>
        <option value="vacation">Long Vacation</option>
		
		
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

        while( $l=mysqli_fetch_assoc($result) ) {
			
			$level_id = $l['level_id'];
			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {
				$level_name = $l['level_name'].' - DIPLOMA';
			} elseif( $l['level_id'] > 12 ) {
				//'Contact '.substr(,0,1);
       $level_name =  $l['level_name'];
			} else
				$level_name = $l['level_name'];

?>
        <option value="<?php echo $level_id ?>"><?php echo $level_name ?></option>
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
<script type="text/javascript" src="js/get_session_sandwich.js"></script>