<p id="t_i" class="tc_">New  Help Message</p>
<div align="center" style="padding:5px;">
<form method="post" action="adminsnd_msg.php" style="width:500px" onSubmit="return UPLOADER.submit(this, {'onStart' : t, 'onComplete' : snt})" autocomplete="off">
<div style="float:left; width:40%">

	<?php
		require_once '../config.php';
		$user = mysqli_query( $GLOBALS['connect'], 'SELECT exam_officers.examofficer_id, exam_officers.eo_username FROM exam_officers GROUP BY exam_officers.eo_username' );
		if( 0!=mysqli_num_rows($user) ) {
			echo '<select name="users[]" multiple="multiple" style="height:200px">';
			while( $d=mysqli_fetch_array($user) ) {
				echo '<option value="',$d[0],'">',$d[1],'</option>';
			}
			echo '</select>';
		}
    ?>

</div>
<div style="float:left; width:60%">
<table width="60%" border="0" cellpadding="0" cellspacing="5" style="margin-bottom:5px">
  <tr>
    <td style="vertical-align:top"><div style="margin-bottom:3px;">Subjectt</div>
<input name="msubj" class="itext" type="text" style="width:250px">
</td>
  </tr>
  <tr>
    <td></td>
  </tr>
   <tr>
    <td><div style="margin-bottom:3px;">Body</div><textarea name="mbody" cols="" rows="" class="itextarea" style="width:251px; height:50px; resize:none;"></textarea></td>
  </tr>
  <tr>
  	<td>
    <div style="display:block;overflow:hidden;padding:8px 0;">
        <p style="float:left">
        <input name="submit" value="Send" id="ok" type="submit" /> 
        <input name="" type="reset" value="Reset" />
        <input name="button" value="Cancel" type="button" onclick="return TINY.box.hide()" />    
        </p>
    </div>
    
    </td>
  </tr>     
</table>
</div>
</form>
</div>