<?php require ("inc/header.php"); ?>
<!-- TWO STEPS TO INSTALL CHECK ALL:

  1.  Copy the coding into the HEAD of your HTML document
  2.  Add the last code into the BODY of your HTML document  -->

<!-- STEP ONE: Paste this code into the HEAD of your HTML document  -->



<SCRIPT LANGUAGE="JavaScript">
<!-- Modified By:  Steve Robison, Jr. (stevejr@ce.net) -->

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin
var checkflag = "false";
function check(field) {
if (checkflag == "false") {
for (i = 0; i < field.length; i++) {
field[i].checked = true;}
checkflag = "true";
return "Uncheck All"; }
else {
for (i = 0; i < field.length; i++) {
field[i].checked = false; }
checkflag = "false";
return "Check All"; }
}
//  End -->
</script>


<!-- STEP TWO: Copy this code into the BODY of your HTML document  -->



<center>
<form name=myform action="" method=post>
<table>
<tr><td>
<b>Your Favorite Scripts & Languages</b><br>
<input type=checkbox name=list value="1">Java<br>
<input type=checkbox name=list value="2">JavaScript<br>
<input type=checkbox name=list value="3">ASP<br>
<input type=checkbox name=list value="4">HTML<br>
<input type=checkbox name=list value="5">SQL<br>
<br>                                                    
<input type=button value="Check All" onClick="this.value=check(this.form.list)"> 
</td></tr>
</table>
</form>
</center>

<p><center>
<font face="arial, helvetica" size="-2">Free JavaScripts provided<br>
by <a href="http://javascriptsource.com">The JavaScript Source</a></font>
</center><p>

<?php require ("inc/footer.php"); ?>