<?php
session_start();
include_once(dirname(__FILE__).'/lang_exams.php');

if( isset($_SESSION['logged']) && 22 == $_SESSION['logged'] ) {
		
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: index.php');
	exit;
	
}

require("inc/headerlogin.php");
 ?>
<div align="center" class="col-sm-6 col-sm-offset-3" style="margin-top: 90px;"> 



 <div class="panel panel-default">
  <div class="panel-heading">Login In</div>
  <div class="panel-body">
  <form class="form-horizontal" action="myloginquery.php" method="post" autocomplete=off>
<input type="hidden" name="redirect" value="<?php echo $_GET['redirect']; ?>">
  <?php echo isset($_GET['error']) ? $_GET['error'] : ''; ?>
  <div class="form-group">
    <label class="control-label col-sm-2"  for="email">Username:</label>
     <div class="col-sm-10">
  <input type="text" class="form-control" name="username">
  </div>
  </div>
 
 <div class="form-group">
    <label class="control-label col-sm-2" for="email">Password:</label>
    <div class="col-sm-10">
<input class="form-control" type="password" name="password">
 </div>
 </div>
  <button type="submit" class="btn btn-success">Submit</button>
  </form>
 </div>
</div> 


</div>


</div></div>
<div style="width:100%; display:block; overflow:hidden; background:#2E8B57;">
<div style="padding:20px 0 30px; font-size:9px; text-align:center; color:#FFFFFF; width:100%">Powered By <a href="#" style="color:#FFF; text-decoration:none; font-weight:700;">UNICAL Database</a></div>
</div>
