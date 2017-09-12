<?php 
 require_once 'config.php';
$country=$_GET['country'];

$query = "SELECT departments_id,departments_name FROM departments WHERE fac_id = $country ORDER BY departments_id ";
$result = mysqli_query( $GLOBALS['connect'], $query)
or die("<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>");

?>
<select name="s_department" id="s_department" readonly="readonly">
<option value="" selected>Choose Department</option>
<?php //while($row = mysqli_fetch_array($result)) { ?>
<option value="<?php //echo $row['departments_id']; ?>"><?php //echo $row['departments_name']; ?></option>
<?php //} ?>
</select>
