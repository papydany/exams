<?php 

require_once 'config.php';

$country=$_GET['country'];
 $ac = mysqli_query( $GLOBALS['connect'], "SELECT * FROM faculties");

?>
<select name="faculty" id="faculty" onChange="getState(this.value)">
<option value=" ">---Pick a Faculty---</option>
<?php  while( $l=mysqli_fetch_assoc($ac) )  { ?>
<option value="<?php echo $l['faculties_id']; ?>"><?php echo $l['faculties_name']; ?></option>
<?php } ?>
</select>
