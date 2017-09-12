<?php
// Get the filename to be deleted

$file=@$_GET['file'];

// Check if the file has needed args
if(isset($file)){
if ($file==NULL){
  print("<script type='text/javascript'>window.alert('You have not provided a file to delete.')</script>");
  print("<script type='text/javascript'>window.location='manage.php'</script>");
  print("You have not provided a file to delete.<br>Click <a href='manage.php'>here</a> if your browser doesn't automatically redirect you.");
  die();
}

// Delete the SQL file
if (!is_dir("backup/" . $file . '.sql')) {
if(unlink("backup/".$file .'.sql.gz')){
	echo 'success';
}else{
	echo'failure';
}
}else{
	echo 'ccc';
}

// Delete the ZIP file
/*if (is_dir("backup/" . $file . '.zip')) {
unlink("backup/" . $file . '.zip');
}*/

// Redirect
header("Location: manage.php");
}

$a_file=$_GET['a_file'];

// Check if the file has needed args
if(isset($a_file)){
if ($a_file==NULL){
  print("<script type='text/javascript'>window.alert('You have not provided a file to delete.')</script>");
  print("<script type='text/javascript'>window.location='manage.php'</script>");
  print("You have not provided a file to delete.<br>Click <a href='manage.php'>here</a> if your browser doesn't automatically redirect you.");
  die();
}

// Delete the SQL file
if (!is_dir("database_backup/category1/" . $a_file . '.sql.gz')) {
if(unlink("database_backup/category1/".$a_file .'.sql.gz')){
	echo 'success';
}else{
	echo'failure';
}
}else{
	echo 'ccc';
}
header("Location: database.php");
}

$b_file=@$_GET['b_file'];

// Check if the file has needed args
if(isset($b_file)){
if ($b_file==NULL){
  print("<script type='text/javascript'>window.alert('You have not provided a file to delete.')</script>");
  print("<script type='text/javascript'>window.location='manage.php'</script>");
  print("You have not provided a file to delete.<br>Click <a href='manage.php'>here</a> if your browser doesn't automatically redirect you.");
  die();
}

// Delete the SQL file
if (!is_dir("database_backup/category2/" . $b_file . '.sql.gz')) {
if(unlink("database_backup/category2/".$b_file .'.sql.gz')){
	echo 'success';
}else{
	echo'failure';
}
}else{
	echo 'ccc';
}
header("Location: database.php");
}

$c_file=@$_GET['c_file'];

// Check if the file has needed args
if(isset($c_file)){
if ($c_file==NULL){
  print("<script type='text/javascript'>window.alert('You have not provided a file to delete.')</script>");
  print("<script type='text/javascript'>window.location='manage.php'</script>");
  print("You have not provided a file to delete.<br>Click <a href='manage.php'>here</a> if your browser doesn't automatically redirect you.");
  die();
}

// Delete the SQL file
if (!is_dir("database_backup/category3/" . $c_file . '.sql.gz')) {
if(unlink("database_backup/category3/".$c_file .'.sql.gz')){
	echo 'success';
}else{
	echo'failure';
}
}else{
	echo 'ccc';
}
header("Location: database.php");
}


$d_file=@$_GET['d_file'];

// Check if the file has needed args
if(isset($d_file)){
if ($d_file==NULL){
  print("<script type='text/javascript'>window.alert('You have not provided a file to delete.')</script>");
  print("<script type='text/javascript'>window.location='manage.php'</script>");
  print("You have not provided a file to delete.<br>Click <a href='manage.php'>here</a> if your browser doesn't automatically redirect you.");
  die();
}

// Delete the SQL file
if (!is_dir("database_backup/category4/" . $d_file . '.sql.gz')) {
if(unlink("database_backup/category4/".$d_file .'.sql.gz')){
	echo 'success';
}else{
	echo'failure';
}
}else{
	echo 'ccc';
}
header("Location: database.php");
}

$e_file=@$_GET['e_file'];

// Check if the file has needed args
if(isset($e_file)){
if ($e_file==NULL){
  print("<script type='text/javascript'>window.alert('You have not provided a file to delete.')</script>");
  print("<script type='text/javascript'>window.location='manage.php'</script>");
  print("You have not provided a file to delete.<br>Click <a href='manage.php'>here</a> if your browser doesn't automatically redirect you.");
  die();
}

// Delete the SQL file
if (!is_dir("database_backup/category5/" . $e_file . '.sql.gz')) {
if(unlink("database_backup/category5/".$e_file .'.sql.gz')){
	echo 'success';
}else{
	echo'failure';
}
}else{
	echo 'ccc';
}
header("Location: database.php");
}

$f_file=@$_GET['f_file'];

// Check if the file has needed args
if(isset($f_file)){
if ($f_file==NULL){
  print("<script type='text/javascript'>window.alert('You have not provided a file to delete.')</script>");
  print("<script type='text/javascript'>window.location='manage.php'</script>");
  print("You have not provided a file to delete.<br>Click <a href='manage.php'>here</a> if your browser doesn't automatically redirect you.");
  die();
}

// Delete the SQL file
if (!is_dir("database_backup/category6/" . $f_file . '.sql.gz')) {
if(unlink("database_backup/category6/".$f_file .'.sql.gz')){
	echo 'success';
}else{
	echo'failure';
}
}else{
	echo 'ccc';
}
header("Location: database.php");
}
?>
