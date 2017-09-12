<?php
require( "inc/header.php" );
require( dirname(__FILE__)."/updates.php");
/*
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
}*/

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



<?php /*
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

*/

?>
<form action="report4new_transcript.php" method="post" name="frmtranscript"  autocomplete="off" target="_blank">
<table width="100%" border="0" align="center" cellspacing="10" cellpadding="10" id=title>
  <tr>
    <td class="" width="300"><b>Enter Student's Registration Number</b><br/></td><td><input type="text" id="txtmatricno" name="txtmatricno" />
    
  </tr>
  <tr><td>&nbsp;</td><td><input type="submit" name="btnsubmit" value="Generate Transcript" /></td></tr>
  
</table>
</form>



<?php
require( "inc/footer.php" );
?>