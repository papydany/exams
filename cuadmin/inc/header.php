<?php
include_once("../auth.inc.php");
include_once("../config.php");
include_once("../lang_exams.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_name ?>: <?php echo $exams_name_wd ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<?php echo $exams_style_wd ?>

<script type="text/javaScript">

function guif(a){var b=document.getElementById(a);if(b.innerHTML=="YES"){b.innerHTML="NO"}else{b.innerHTML="YES"}};
function upd(e,f) {
	if( /[^abcdefn]/gi.test(e.value) ) {
		alert('Only Grade A - F & N is Allowed');
		e.value = '';
	} else {
		
		var f = document.getElementById(f);
		switch( e.value ) {
			case 'A':
			case 'a':
				f.value = '75';
			break;
			case 'B':
			case 'b':
				f.value = '65';
			break;
			case 'C':
			case 'c':
				f.value = '55';
			break;
			case 'D':
			case 'd':
				f.value = '47';
			break;
			case 'E':
			case 'e':
				f.value = '42';
			break;
			case 'F':
			case 'f':
				f.value = '20';
			break;
			case 'N':
			case 'n':
				f.value = '0';
			break;			
			default:
				f.value = '';
			break;						
		}
		
	}
}
function guif(a){var b=document.getElementById(a);if(b.innerHTML=="YES"){b.innerHTML="NO"}else{b.innerHTML="YES"}};

function updA(e,f,g) {
	if( /[^abcdefn]/gi.test(e.value) ) {
		alert('Only Grade A - F & N is Allowed');
		e.value = '';
	} else {
		
		var f = document.getElementById(f);
		var chk = document.getElementById(g);
		switch( e.value ) {
			case 'A':
			case 'a':
				f.value = '75';
			break;
			case 'B':
			case 'b':
				f.value = '65';
			break;
			case 'C':
			case 'c':
				f.value = '55';
			break;
			case 'D':
			case 'd':
				f.value = '47';
			break;
			case 'E':
			case 'e':
				f.value = '42';
			break;
			case 'F':
			case 'f':
				f.value = '20';
			break;
			case 'N':
			case 'n':
				f.value = '0';
			break;			
			default:
				f.value = '';
			break;					
		}
		
		if( f.value != '' ) {
			chk.checked = true;
		} else {
			chk.checked = false;
		}
		
	}
}

/**
 * Displays an confirmation box before to submit a "DROP DATABASE" query.
 * This function is called while clicking links
 *
 * @param   object   the link
 * @param   object   the sql query to submit
 *
 * @return  boolean  whether to run the query or not
 */
function confirmLinkDropDB(theLink, theSqlQuery)
{
    // Confirmation is not required in the configuration file
    // or browser is Opera (crappy js implementation)
    if (confirmMsg == '' || typeof(window.opera) != 'undefined') {
        return true;
    }

    var is_confirmed = confirm(confirmMsgDropDB + '\n' + confirmMsg + ' :\n' + theSqlQuery);
    if (is_confirmed) {
        theLink.href += '&is_js_confirmed=1';
    }

    return is_confirmed;
} // end of the 'confirmLink()' function

/**
 * Displays an confirmation box beforme to submit a "DROP/DELETE/ALTER" query.
 * This function is called while clicking links
 *
 * @param   object   the link
 * @param   object   the sql query to submit
 *
 * @return  boolean  whether to run the query or not
 */
function confirmLink(theLink, theSqlQuery)
{
    // Confirmation is not required in the configuration file
    // or browser is Opera (crappy js implementation)
    if (confirmMsg == '' || typeof(window.opera) != 'undefined') {
        return true;
    }

    var is_confirmed = confirm(confirmMsg + ' \n' + theSqlQuery);
    if (is_confirmed) {
        theLink.href += '&is_js_confirmed=1';
    }

    return is_confirmed;
} // end of the 'confirmLink()' function

    // js form validation stuff
    var confirmMsg  = 'Do you really want to ';

//-->
</script>

<!--<script language="JavaScript" src="xlp_picker.js"></script>-->

</head>

<body>

<div style="background:#000; padding:2px 10px;" class="block" >
    <div style="float:left;">
        <ul class="castle">       
        <li style="border:none;"><a style="border:none;" href="view_acct.php">Edit Account</a></li>
        </ul> 
    </div>
    
    <div style="float:left;padding-top:2px;"><span class="i">|</span><?php echo '<span style="color:#EEE; font-weight:700; position:relative; top:2px; margin-left:4px">Administrative Account Section</span>'; ?></div>

    <div style="float:right;">
            <ul class="castle">
                <li><a href="log_out.php">Log Out</a></li>
            </ul>
    </div>

</div>

<div style="background:#4CAF50; padding:5px 0 5px 0; color:#FFF; text-align:left; font-size:22px; border:4px solid #2E8B57; border-width:4px 0 0; width:100%; border-bottom:1px solid #4CAF50; font-weight:700;"><span style="padding:0 20px 0 20px"><img src="images/icon2.png" /></span><span style=" position:relative; top:-15px"><?php echo $website_name ?>: <?php echo $exams_name_wd ?></span></div>


<div style="width:100%; display:block; overflow:hidden;">

<div style="width:20%; display:block; overflow:hidden; float:left; background:#F9F9F9;">

	<div style="background:#90EE90; padding:5px 5px 6px; color:#006; font-weight:700; font-size:11px; text-align:center; border-bottom:1px solid #4CAF50">
	<?php
		echo "$_SESSION[myeo_fullname]";
	?>
	</div>
	<div style="padding:15px;" class="block"> 
	<?php require ("left_nav.php") ?>
	</div>

</div>  

<div style="width:80%; display:block; float:left; overflow:hidden;">
<div style="display:block; overflow:hidden; padding:10px;">  