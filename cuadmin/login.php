<?php
session_start();
include_once("../lang_exams.php");

if( isset($_SESSION['loggeed']) && 22 == $_SESSION['logged'] ) {

	$sc = "JESUS IS LORD";
	$ksc = "$sc$mstatus";
	$tksc = md5($kcs);
	$ttksc = "a=$tksc&b=$mstatus&";
	$key = "?$ttksc";
		
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: index.php'.$key);
	exit;
	
}

require("inc/headerlogin.php");
 ?>

<div align="center" class="col-sm-6 col-sm-offset-3" style="margin-top: 90px;"> 
 <div class="panel panel-default">
  <div class="panel-heading">Admin Exam Officer Login</div>
  <div class="panel-body">
  <form class="form-horizontal" action="myloginquery.php" method="post" autocomplete=off>
<input type="hidden" name="redirect" value="<?php echo $_GET['redirect']; ?>">
  <?php echo isset($_GET['error']) ? $_GET['error'] : ''; ?>
  <div class="form-group">
    <label class="control-label col-sm-2"  for="email"><?php echo $exams_user_wd ?>:</label>
     <div class="col-sm-10">
  <input type="text" class="form-control" name="username">
  </div>
  </div>
 
 <div class="form-group">
    <label class="control-label col-sm-2" for="email"><?php echo $exams_pass_wd ?>:</label>
    <div class="col-sm-10">
<input class="form-control" type="password" name="password">
 </div>
 </div>
  <button type="submit" class="btn btn-success">Submit</button>
  </form>
 </div>
</div> 

</div>

<?php require("inc/footer.php"); ?>
