<?php 
	require_once 'inc/header.php';
    require_once 'config.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
</style>
<?php
if( isset($_GET['cid']) ):
?>
<form action="effectCC.php?<?php echo $_SERVER['QUERY_STRING'] ?>" method="post" autocomplete="off">
  
<table width="880" border="0" cellpadding="0" cellspacing="0" style="padding:20px; margin:0 auto" class="field">
<?php

$l = mysqli_query( $GLOBALS['connect'], 'select * from `all_courses` where `all_courses`.`thecourse_id` = '.$_GET['cid'].' && `all_courses`.course_custom2 = '.$_GET['course'].' && `all_courses`.`course_custom5` = "'.$_GET['yearsession'].'" LIMIT 1');
$row = mysqli_fetch_assoc($l);

mysqli_free_result($l);
	?>
  <tr>
    <td>
		<span>Course Code</span>
		<p><input type="text" value="<?php echo $row['course_code'] ?>" name="cc[0]" /></p></td>
    <td>
	<span>Course Unit</span>
		<p><input type="text" value="<?php echo $row['course_unit'] ?>" name="cu[0]" /></p></td>
		<td>
	<span>Course Title</span>
		<p><input type="text" value="<?php echo $row['course_title'] ?>" name="ct[0]" />
		<input type="hidden" value="<?php echo $row['thecourse_id'] ?>" name="cid[0]" />
		</p>
		</td>
    <td>
	<span>Course Category</span>
		<p><select name="cs[0]">
        <?php
			$cs = array();
			$cs['c'] = '';
			$cs['e'] = '';
			if( $row['course_status']=='E' ) {
				$cs['e'] = 'selected';
			} else {
				$cs['c'] = 'selected';
			}
		?>
        <option <?php echo $cs['c']?>>C</option>
        <option <?php echo $cs['e']?>>E</option>
        </select></p></td>
		<td>

		</tr>
		<tr>
			<td colspan="3">
				<p>
				<input type="checkbox" value="<?php echo $row['course_title'] ?>" checked="checked" disabled="disabled" name="chk[0]" />
				<label>Affect Registered Courses and Results - ( Auto-selected )</label>
				</p>
			</td>
		</tr>
  <?php  
//}
?>
  <tr>
  <td colspan="3" ><input name="MODIFY" type="submit" value="Save Changes"></td>
  </tr>
</table>
</form>
<?php
endif;
?> 
  <!-- content ends here -->

  
<?php require_once 'inc/footer.php'; ?>