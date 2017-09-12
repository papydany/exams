<?php 

require_once 'config.php';

$country=$_GET['country'];
 $ac = mysqli_query( $GLOBALS['connect'], "SELECT * FROM departments WHERE fac_id = '$country'");

?>
<select name="department" id="department">
<option value=" ">---Pick a Department---</option>
<?php  while( $l=mysqli_fetch_assoc($ac) )  { ?>
<option value="<?php echo $l['departments_id']; ?>"><?php echo $l['departments_name']; ?></option>
<?php } ?>
</select>
