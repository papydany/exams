<?php



function GetDepartment ($department_id) {

$query = "SELECT *
	FROM departments
	WHERE departments_id='$department_id'
	LIMIT 1";
$result = mysqli_query( $GLOBALS['connect'], $query);
$row = mysqli_fetch_array($result);
$num = mysqli_num_rows ($result);

$departments_id = $row["departments_id"];
$departments_name = $row["departments_name"];

$departments_name = stripslashes($departments_name);

return $departments_name;
}


function GetlLevel($level_id) {

$query = "SELECT *
	FROM level
	WHERE level_id='$level_id'
	LIMIT 1";
$result = mysqli_query( $GLOBALS['connect'], $query);
$row = mysqli_fetch_array($result);
$num = mysqli_num_rows($result);

$level_id = $row["level_id"];
$level_name = $row["level_name"];

$level_name = stripslashes($level_name);

return $level_name;
}


?>