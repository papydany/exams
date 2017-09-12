<?php
require ("inc/header.php");
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
  if (document.form.s_level.value == "") {
    alert( "Please select Level" );
    document.form.s_level.focus();
    return false ;
  }    

}

function display(rid){

document.getElementById("pti").value = rid.value;
	}
	
	
	function closewindow(){
var answer = confirm("Close window?")
if(answer){
	window.opener.location.reload(false);
window.close();
}else{

}
}


function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
	
	function getState(countryId) {		
		
		var strURL="ajax_department.php?country="+countryId;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('statediv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}

	
	
</script>

<?php
unset( $_SESSION['s_session'],$_SESSION['s_semester'],$_SESSION['s_level']);
?>
  <div class="col-sm-8 col-sm-offset-2">  
<form class="form" name="form" method="get" action="<?php if ( isset($_GET['p']) && $_GET['p'] == "1"){echo "add_result_plecturer.php"; } else { echo "add_result_lecturer.php"; } ?>">
  <table width="40%" style="margin:0 auto" border="0" align="center" cellpadding="5" cellspacing="5" class="table table-bordered">
   <tr>
      <td>Programme</td>
      <td><select name='programme' id='programme' class="form-control">
      <option value="" selected>select programme</option>
      <?php
      $query = "SELECT * FROM programme";
$result = mysqli_query($GLOBALS['connect'], $query) or die(mysqli_error($GLOBALS['connect']));

 while( $p=mysqli_fetch_assoc($result) ) {
  echo'<option value=',$p['programme_id'],'>',$p['programme_name'],'</option>';
 	}

?>
  
      </select>
      </td>
      </tr>
    <tr>
      <td>Department</td>
      <td><select name='department' id='department' class="form-control" required>
                              <option value="" selected>Choose A Department</option>
<?php
$lecturer_id = $_SESSION['myexamofficer_id'];
$query_get_for ="SELECT DISTINCT dept_id from assign_courses WHERE lecturer_id='$lecturer_id'";
$result = mysqli_query($GLOBALS['connect'],  $query_get_for) or die(mysqli_error($GLOBALS['connect']));
$fos_count = mysqli_num_rows ($result);
if($fos_count == 0)

{
echo"<p class='text-success'>You have not been assign to a course. contact your desk officer.<p/>";
die();	
}
while($do=mysqli_fetch_assoc($result))
{
	$do_ids [] =$do['dept_id'];
}

$dept_id = array();

foreach( $do_ids as $k=>$v ) {$dept_id[] = $v;}
$sql ="SELECT * from departments WHERE departments_id IN (".implode(',', $dept_id).")";
$result = mysqli_query($GLOBALS['connect'],  $sql) or die(mysqli_error($GLOBALS['connect']));
while($do=mysqli_fetch_assoc($result))
{
	echo "<option value='$do[departments_id]~$do[fac_id]'>$do[departments_name]</option>";
}
            
?>
</select></td>
    </tr>
        <tr>
      <td>Session</td>
      <td><select name='s_session' id='s_session' class="form-control">
                              <option value="" selected>Choose A Session</option>
<?php
             for ($year = (date('Y')-1); $year >= 1998; $year--) {

				switch( $_SESSION['myprogramme_id'] ) {
					case 0: case '0':
						echo "<option value='$year'>$year</option>\n";
					break;
					default:
						$yearnext =$year+1;

						echo "<option value='$year'>$year/$yearnext</option>\n";
					break;
				}

            }
?>
</select></td>
    </tr>
    <tr>
      <td>Semester</td>
      <td><select name="s_semester" id ="s_semester" class="form-control">
        <option value="" selected>Choose Semester</option>
        <option value="Both">First / Second Semester</option>
        <option value="vacation">Long Vacation</option>
      </select>
      </td>
    </tr>
    <tr>
      <td>Level</td>
      <td><select name="s_level" id="s_level" class="form-control">
        <option value="">Choose Level</option>

      </select></td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td><input class="btn btn-success" type="submit" name="Submit" value="Continue" onClick="return checkform(this);" ></td>
    </tr>
  </table>
</form>

<!-- content ends here -->



<?php require ("inc/footer.php"); ?>

<script type="text/javascript">

$(document).ready(function(){
$("#s_level").show();
$("#s_session").show();
$("#programme").change( function() {
     
$("#s_level").hide();
$("#s_session").hide();
$("#result").html('Retrieving …');
$.ajax({
type: "POST",
data: "data=" + $(this).val(),
url: "get_plectuerer.php",
success: function(msg){
if (msg != ''){
//$("#s_session").html(msg).show();	
$("#s_level").html(msg).show();
$("#result").html('');
}
else{
$("#result").html('<em>No item result</em>');
}
}
});

$.ajax({
type: "POST",
data: "data=" + $(this).val(),
url: "get_1plecturer.php",
success: function(msg){
if (msg != ''){
$("#s_session").html(msg).show();	
//$("#s_level").html(msg).show();
$("#result").html('');
}
else{
$("#result").html('<em>No item result</em>');
}
}
});
});
});
</script>	