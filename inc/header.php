<?php
	
	static $__file__;
	$__file__ = dirname(__FILE__);
	
	require_once('./auth.inc.php');
	require_once('./config.php');
	require_once('./lang_exams.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $website_name ?>: <?php echo $exams_name_wd ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<?php echo $exams_style_wd ?>

<script type="text/javaScript">
function guif(a){var b=document.getElementById(a);if(b.innerHTML=="YES"){b.innerHTML="NO"}else{b.innerHTML="YES"}};
function e__(e){if(/[^abcdefn]/gi.test(e.value)){alert('Only Grade A - F & N is Allowed');e.value='';}}
function upd(e,f){if(/[^abcdefn]/gi.test(e.value)){alert('Only Grade A - F & N is Allowed');e.value='';
}else{
	var f=document.getElementById(f);
	switch(e.value){
		case'A':case'a':f.value='75';break;case'B':case'b':f.value='65';break;case'C':case'c':f.value='55';break;
		case'D':case'd':f.value='47';break;case'E':case'e':f.value='42';break;case'F':case'f':f.value='20';break;
		case'N':case'n':f.value='0';break;default:f.value='';break;}}}
function guif(a){var b=document.getElementById(a);if(b.innerHTML=="YES"){b.innerHTML="NO"}else{b.innerHTML="YES"}};
function updA(e,f,g){if(/[^abcdefn]/gi.test(e.value)){alert('Only Grade A - F & N is Allowed');e.value='';}
else{var f=document.getElementById(f);var chk=document.getElementById(g);
switch(e.value){
	case'A':case'a':f.value='75';break;case'B':case'b':f.value='65';break;case'C':case'c':f.value='55';break;
	case'D':case'd':f.value='47';break;case'E':case'e':f.value='42';break;case'F':case'f':f.value='20';break;
	case'N':case'n':f.value='0';break;default:f.value='';break;}
if(f.value!=''){chk.checked=true;}else{chk.checked=false;}}}
function confirmLink(theLink,theSqlQuery){var confirmMsg='Do you really want to ';
if(confirmMsg==''||typeof(window.opera)!='undefined'){return true;}
var is_confirmed=confirm(confirmMsg+' \n'+theSqlQuery);if(is_confirmed){theLink.href+='&is_js_confirmed=1';}
return is_confirmed;}
function affirm(v, cb) {
	if( confirm(v) === true ){ return cb(); }
	return false;
}
function checkUncheckAll(theElement){
	var theForm=theElement.form,z=0;for(z=0;z<theForm.length;z++){if(theForm[z].type=='checkbox'){theForm[z].checked=theElement.checked;}}}
</script>


</head>

<body>
<?php
/* Help Message Counter */
	$msgs = '';
	$c = mysqli_query($GLOBALS['connect'],'SELECT count(1) as size FROM messaging WHERE m_to = "'.$_SESSION['myexamofficer_id'].'" && unread = "Y"');
	
	if( 0!=mysqli_num_rows($c) ) {
		$d = mysqli_fetch_assoc($c);
		mysqli_free_result($c);
		if( $d['size'] != 0 ) {
			$msgs = '<b class="coat">'.$d['size'].'</b>';
		}
	}
/* Help Message Counter */
?>
<div style="width:100%; display:block; overflow:hidden;background:#4CAF50;">
<div style="background:#4CAF50; 
padding:20px 10px 10px; color:#FFF; text-align:left; font-size:20px; 
border:2px solid #4CAF50; border-width:2px 0 0; width:100%; 
border-bottom:1px solid #4CAF50; border-bottom-color:#4CAF50; 
font-weight:700; text-align:center;"><span style="position:relative; 
text-align:center; margin-right:4px; top:2px;"></span>
<span style=" position:relative; top:-6px;">University Of Calabar: <?php echo $exams_name_wd ?></span></div>
</div>

<div style="width:100%; display:block; overflow:hidden; margin:0 auto;">

<div style="border-bottom:1px solid #4CAF50; display:block; overflow:hidden; padding:2px 0;background:#4CAF50;">
	<p style="font-weight:700; line-height:14px; padding:0 6px; color:#fff;"><?php echo strtoupper("$_SESSION[myeo_fullname]"); ?></p>
    <div style="display:block; overflow:hidden; line-height:14px;background:#4CAF50;">
    	
        <div id="menu2" class="lc">
					
                    <dl>
					<dt><p class="tdt"><a href="view_acct.php">Manage Account</a></p></dt>
					</dl>
                    
                    <dl>
					<dt><p class="tdt"><a href="log_out.php">Log Out</a></p></dt>
					</dl>
                    
                    <?php
					// -------------------------
					if ($_SESSION['trans_right'] == '1') {
					
					} else if ( $_SESSION['user_right'] == '2'){
						
						
					} else if ( $_SESSION['myprogramme_id'] != '1' ) {		
					
					echo <<<MORE
					<dl>
					<dt id="one_ddh" ><p class="tdt"><a href="#" class="bb" onmousedown="lm.dm('one',1)">More</a></p></dt>
					<dd id="one_ddc"><ul>
					<li><a href="set__adv_1.php">&rsaquo; Repeat Result</a></li>
                    <li><a href="set__adv_6.php">&rsaquo; Probation Courses</a></li>
					<li><a href="set__adv_3.php">&rsaquo; CarryOver Courses</a></li>
					<li><a href="set__adv_8.php">&rsaquo; Delayed Courses</a></li>
                    <li style="list-style: none; display: inline"><p class="sepm"></p></li>		
					<li><a href="viewregprobstudent.php">&rsaquo; Registered Probation</a></li>
					<li><a href="viewregdelaystudent.php">&rsaquo; Registered Delay</a></li>
                    
                    </ul></dd>
					</dl>
MORE;
					} else {
						
					echo <<<MORE
					<dl>
					<dt id="one_ddh" ><p class="tdt"><a href="#" class="bb" onmousedown="lm.dm('one',1)">More</a></p></dt>
					<dd id="one_ddc"><ul>
					<li><a href="set__adv_7.php">&rsaquo; Resit/Repeat Result</a></li>                    
                    </ul></dd>
					</dl>
MORE;
					}
					
					?>
               
					<dl>
					<dt id="two_ddh" ><p class="tdt"><a href="#" class="bb" onMouseDown="lm.dm('two',1)">Messaging<?php echo $msgs; ?></a></p></dt>
					<dd id="two_ddc"><ul>
					<li><a href="#" onClick="return newmsg()">&rsaquo; New Message</a></li>
                    <li style="list-style: none; display: inline"><p class="sepm"></p></li>
					<li><a href="msg.php">&rsaquo; View Messages</a></li>		
					</ul></dd>
					</dl>					    
				
				</div>
                
        <p style="font-weight:700; float:right; font-size:16px; margin-top:3px; letter-spacing:5px; color:#D1DDED;
         padding-right:6px"><?php echo GetDepartment ($_SESSION['mydepartment_id']); ?></p>
    </div>
</div>

<div style="width:16%; display:block; overflow:hidden; float:left;">

	<div style="width:190px; margin:5px 0px 10px 5px; border:1px solid #90EE90; ">

    	<?php require_once 'inc/left_nav.php'; 
			menu();
		?>

    </div>


</div>  

<div style="width:84%; display:block; float:left; overflow:hidden;">

<div style="display:block; overflow:hidden; background:#FBFCFD; padding:0 0 5px 0px; margin:0 0 0 5px;">  
<p style="border:1px solid #90EE90; border-width:0 0 1px 1px;
 background:#90EE90; font-size:20px; font-weight:700; padding:2px 20px 5px;" class="shadow">
	<?php echo pageTitle(); ?>
</p>

<div id="appcontainer" style=" padding:0 5px; overflow-y:auto;"><br>


