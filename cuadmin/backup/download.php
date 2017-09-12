<?php
$f = $_GET['f'];

if ($f==NULL){
  print("<script type='text/javascript'>window.alert('You have not provided a file to download.')</script>");
  print("<script type='text/javascript'>window.location='manage.php'</script>");
  print("You have not provided a file to download.<br>Click <a href='manage.php'>here</a> if your browser doesn't automatically redirect you.");
  die();
}

// if ($f != '') {
	
	//$download = 'backup/'. $f;
	//$download_size = filesize( 'backup/'. $f );
	$download =  $f;
	$download_size = filesize( $f );
	
	$mtype = "application/force-download";
		
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Type: $mtype");
	header("Content-Disposition: attachment; filename=\"$download\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".$download_size );

	// download
	readfile( dirname(__FILE__).$download );
// }
 
// Redirect
//header("Location: manage.php");
?>