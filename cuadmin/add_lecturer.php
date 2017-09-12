<?php
	require_once 'inc/header.php';
    require_once '../config.php';
//require( dirname(__FILE__)."/updates.php");

$title = $_POST['title'];
$surname = strtoupper($_POST['surname']);
$firstname = strtoupper($_POST['firstname']);
$othernames = strtoupper($_POST['othernames']);
$fos = '0';
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$programme = $_POST['programme'];
$faculty = $_POST['faculty'];
$department = $_POST['department'];
$editright = $_POST['editright'];
$mydate = date("Y-m-d");
//echo $firstname;

if(isset($_POST['addlec']) && ($surname != "" && $firstname != "" && $email != "" && $username != "" && $password != "" && $faculty != "" && $department != "" && $mydate != "")){
	if($_POST['email'] != ""){
		
$queryab = "SELECT * from exam_officers WHERE eo_email = '$email'";
$sqlab = mysqli_query( $GLOBALS['connect'], $queryab) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	
	if(mysqli_num_rows($sqlab) < 1){
	
	$query2ab = "INSERT INTO exam_officers(programme_id,faculty_id,department_id,fos,eo_username,eo_password,eo_title,eo_surname,eo_firstname,eo_othernames,eo_email,eo_date_reg,eo_status,mstatus,eo_course,edit_allow_logon,user_right) VALUES(0,$faculty,0,$fos,'$username','$password','$title','$surname','$firstname','$othernames','$email','$mydate',1,3,0,0,2)";
	//echo $query2ab;
	$query2ab = mysqli_query( $GLOBALS['connect'], $query2ab) or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");
	
	//if ( 0!=mysqli_num_rows($query2ab) ) {
		$msg = '<div style="padding:10px; font-size:14px; background:#FFF8E7; border:1px solid #FFEAB7; font-weight:700; color:#D79600">Examiner\'s Account Successfully Added</div>';
	//$msg = '<div class="info">Examiner\'s Account Successfully Added</div>';
	
$_POST['title'] = "";
$_POST['surname'] = "";
$surname = "";
$_POST['firstname'] = "";
$firstname = "";
$_POST['othernames'] = "";
$othernames = "";
$fos = "";
$_POST['email'] = "";
$_POST['username'] = "";
$_POST['password'] = "";
//$_POST['programme'] = "";
$_POST['faculty'] = "";
$_POST['department'] = "";
//$_POST['edithright'] = "";
$mydate = "";
$_POST['email1'] = "";

	}else{
		$msg = '<div style="padding:10px; font-size:14px; background:#FFF8E7; border:1px solid #FFEAB7; font-weight:700; color:#D79600">Sorry, Account Already Exists!</div>';
		//$msg = '<div class="info">Sorry, Account already exists!</div>';
	}
	
} else {
	$msg = '<div style="padding:10px; font-size:14px; background:#FFF8E7; border:1px solid #FFEAB7; font-weight:700; color:#D79600">Invalid Email addresss. Please enter a valid email address!</div>';
	//$msg = '<div class="info">Invalid Email address. Please enter a valid email address!</div>';
	}
}

?>
<!-- content starts here -->

<script type="text/javascript">
<!--
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
		
		var strURL="add_lecturer_department.php?country="+countryId;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('deptdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	

//-->
</script>

<style>

select{ width:120px; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
</style>

<form action="" method="post" autocomplete="off" id="form1" >
  <table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left"><?php echo $msg; ?>&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><table width="50%" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td width="26%">&nbsp;</td>
          <td width="74%">&nbsp;</td>
        </tr>
        <tr>
          <td width="26%">Title</td>
          <td width="74%">
          <select name="title" id="title">
            <option value="">---Pick a Title---</option>
            <option value="Mr">Mr</option>
            <option value="Mrs">Mrs</option>
            <option value="Dr">Dr</option>
            <option value="Miss">Miss</option>
            <option value="Prof">Prof</option>
          </select></td>
        </tr>
        <tr>
          <td><span>Surname</span></td>
          <td><input type="text" name="surname" id="surname" value="<?php echo $surname; ?>"></td>
        </tr>
        <tr>
          <td><span>Firstname</span></td>
          <td><input type="text" name="firstname" id="firstname" value="<?php echo $_POST['firstname']; ?>"></td>
        </tr>
        <tr>
          <td><span>Othernames</span></td>
          <td><input type="text" name="othernames" id="othernames" value="<?php echo $othernames; ?>"></td>
        </tr>
        <tr>
          <td>Email</td>
          <td><input type="text" name="email" id="email">
            <input type="hidden" name="email1" id="email1"></td>
        </tr>
        <tr>
          <td>Username</td>
          <td><input type="text" name="username" id="username"></td>
        </tr>
        <tr>
          <td>Password</td>
          <td><input type="password" name="password" id="password"></td>
        </tr>
        <tr>
          <td><span>Facaulty</span></td>
          <td>
          <select name="faculty" id="faculty" onChange="getState(this.value)">
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
          <td><span>Department</span></td>
          <td>
          <div id="deptdiv">
          <select name="department" id="department">
          <option value="">---Pick a Department---</option>
          </select>
          </div>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td height="50"><p>&nbsp;
            </p>
            <p>
              <input type="submit" name="addlec" id="addlec" onClick="return validate();" value="Add Lecturer">
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

function verifyEmail(){
	var email1 = document.getElementById('email').value;   
	var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var myposted = "1";
     if (email1.search(emailRegEx) == -1) {
          alert("Please enter a valid email address.");
		 document.getElementById('email1').value = "";
		 return false;
     }else{
		  document.getElementById('email1').value = "1";
	 }
	 
	if (myposted == email1){
		return true;
	}else{
		return false;
	}
}


    function validate() 
    {
	//code here....
	var email1  = document.getElementById('email1').value;
	//var programme = document.getElementById('programme').value;
	var faculty = document.getElementById('faculty').value;
	var department = document.getElementById('department').value;
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;
	var title = document.getElementById('title').value;
	var surname = document.getElementById('surname').value;
	var firstname = document.getElementById('firstname').value;
	var email = document.getElementById('email').value;
	var fos = document.getElementById('fos').value;
	//var editright = document.getElementById('editright').value;
	var email1 = document.getElementById('email1').value;
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	//var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	
	if (title == "" || title==null) {
    alert("Title selection is required!");
    return false;
} else if (surname == "" || surname==null) {
    alert("Surname is required!");
    return false;
} else if (firstname == "" || firstname==null) {
    alert("Firstname is required!");
    return false;
} else if (fos == "" || fos==null) {
    alert("Fos is rquired!");
    return false;
} else if (isNaN(fos)) {
    alert("FOS : Only integer values required!");
    return false;
} else if (email == "" || email==null) {
    alert("Email is required!");
    return false;
} else if (username == "" || username==null) {
    alert("Username is required!");
    return false;
} else if (password == "" || password==null) {
    alert("Password is required!");
    return false;
	/*
} else if (editright == "" || editright==null) {
    alert("Edit Right selection is required!");
    return false;
} else if (programme == "" || programme==null) {
    alert("Programme selection is required!");
    return false;
	*/
} else if (faculty == "" || faculty==null) {
    alert("Faculty selection is required!");
    return false;
} else if (department == "" || department==null) {
    alert("Department selection is required!");
    return false;
} else {
	//return true;
	verifyEmail();

}

}
</script>
<?php
require_once( "inc/footer.php" );
?>