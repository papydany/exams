<?php
require_once 'config.php';
$id =$_POST['id'];
$title=$_POST['title'];
$surname =$_POST['surname'];
$firstname=$_POST['firstname'];
$othernames =$_POST['othernames'];
$sql ="UPDATE exam_officers SET `eo_title`='$title', `eo_surname`='$surname', `eo_firstname`='$firstname',`eo_othernames`='$othernames' WHERE `examofficer_id`='$id'";

$query = mysqli_query( $GLOBALS['connect'],$sql) or die(mysqli_error($GLOBALS['connect']));
  header('Location: viewlecturers.php');
		exit;
?>