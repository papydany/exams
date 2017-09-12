<?php 
	require_once 'inc/header.php';
    require_once 'config.php';
$fac =$_SESSION['myfaculty_id'];
$dept =$_SESSION['mydepartment_id'];
echo'<div class="col-sm-10 col-sm-offset-1">';

if(isset($_GET['i']) && $_GET['i'] == 1 ) 
{
	echo'<div class="alert alert-success">
  <strong>Success!</strong> Lecturer  sucessfull Deactivated.
</div>';
}

    $query = "SELECT * FROM exam_officers WHERE programme_id=0 and faculty_id ='$fac' and department_id ='$dept'  and eo_status =1";
$result = mysqli_query($GLOBALS['connect'], $query) or die(mysqli_error($GLOBALS['connect']));
if(mysqli_num_rows ($result) > 0)
{
?>
<table class="table table-striped table-bordered">
<tr>
<th>Title</th>
<th>Fullname</th>
<th>username</th>
<th>password</th>
<th>Action</th>
</tr>
<?php
	while ($row = mysqli_fetch_assoc($result)) {
		$id =$row['examofficer_id'];
?>
<tr>
<td><?php echo $row['eo_title'];?></td>
<td><?php echo $row['eo_surname']." ".$row['eo_firstname']." ".$row['eo_othernames']; ?></th>
<td><?php echo $row['eo_username']; ?></td>
<td><?php echo $row['eo_password']; ?></td>
<td><div class="dropdown">
  <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Action
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
   <?php echo'<li><a href="editlecturer.php?id='.$id.'">Edit</a></li';?>   
    <li class="divider"></li>
   <?php echo'<li><a href="deactivate_lecturer.php?id='.$id.'">De Activate</a></li>';?>
    
  </ul>
</div></td>
</tr>
<?php	
	}
	echo'</table>';

}else
{
	echo'<div class="alert alert-danger">
  <strong>Danger!</strong> No Lecturers information is available .
</div>';
}

echo'</div>';
?>

<?php require_once 'inc/footer.php'; ?>