<?php include_once './config.php';
if(isset($_POST["data"]) && $_POST["data"] !="")
{
	$id = $_POST["data"];

   $query = "SELECT * FROM exam_officers WHERE programme_id=0  and department_id ='$id'  and eo_status =1";
$result = mysqli_query($GLOBALS['connect'], $query) or die(mysqli_error($GLOBALS['connect']));
?>
 <select class="form-control" name="lecturer_id" id="lecturer_id">
 <option value="">Select lecturer</option>
 <?php
if(mysqli_num_rows ($result) > 0)
{
	while ($row = mysqli_fetch_assoc($result)) {
		$eid =$row['examofficer_id'];
		echo '<option value="',$eid,'">',$row['eo_surname']." ".$row['eo_firstname']." ".$row['eo_othernames'],'</option>';

	}
}
?>
</select>
<?php
}

?>