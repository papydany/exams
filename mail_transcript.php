<?php
ini_set("Display_error","1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mail Students Transcript</title>
</head>

<body>

<?php

	$strTo = $_POST["txtTo"];
	$strSubject = $_POST["txtSubject"];
	$strMessage = nl2br($_POST["txtDescription"]);
	$strfrom = $_POST["txtFormName"];
	$mailfrom = $_POST["txtFormEmail"];
	//$mailfrom = $_POST["txtFormEmail"];
//echo $strMessage, '-------------',$strSubject;

	//*** Uniqid Session ***//
	$strSid = md5(uniqid(time()));
	$strHeader = "";
	$strHeader .= "From: $from < $mailfrom >\n Reply-To: $mailfrom ";

	$strHeader .= "MIME-Version: 1.0\n";
	$strHeader .= "Content-Type: multipart/mixed; boundary=\"".$strSid."\"\n\n";
	$strHeader .= "This is a multi-part message in MIME format.\n";
	$strHeader .= "--".$strSid."\n";
	$strHeader .= "Content-type: text/html; charset=windows-874\n"; // or UTF-8 //
	$strHeader .= "Content-Transfer-Encoding: 7bit\n\n";
	$strHeader .= $strMessage."\n\n";
	
	//echo $strHeader;

	//*** Zip Files ***//
	require_once("dZip.inc.php"); 
	$PathName = $_POST['mypath']; //"myfile";
	$ZipName = $_POST['mystdid'].'.zip';// "myzip.zip";
	$zip = new dZip($PathName."/".$ZipName); // New Class
	
	$zip->addFile(dirname($_SERVER['SCRIPT_FILENAME']) .'/'. $PathName.'/'.$_POST['mystdid'].'.pdf', $_POST['mystdid'].'-Transcript.pdf'); // note this modification
	
/*	for($i=0;$i<count($_FILES["fileAttach"]["name"]);$i++)
	{
		if($_FILES["fileAttach"]["name"][$i] != "")
		{
			$zip->addFile($_FILES["fileAttach"]["tmp_name"][$i],$_FILES["fileAttach"]["name"][$i]); // Source,Destination
			//echo $_FILES["fileAttach"]["tmp_name"][$i]. '------' .$_FILES["fileAttach"]["name"][$i];
			//echo dirname($_SERVER['SCRIPT_FILENAME']);
		}
	}*/

	$zip->save();

	//*** Attachment ***//
	if($ZipName != "")
	{
			$strFilesName = $ZipName;
			$strContent = chunk_split(base64_encode(file_get_contents($PathName."/".$ZipName))); 
			$strHeader .= "--".$strSid."\n";
			$strHeader .= "Content-Type: application/octet-stream; name=\"".$strFilesName."\"\n"; 
			$strHeader .= "Content-Transfer-Encoding: base64\n";
			$strHeader .= "Content-Disposition: attachment; filename=\"".$strFilesName."\"\n\n";
			$strHeader .= $strContent."\n\n";
	}
	

	$flgSend = mail($strTo,$strSubject,NULL,$strHeader);  // @ = No Show Error //
	if($flgSend)
	{
		echo "<h2>Email send completed.</h2>";
	}
	else
	{
		echo "<h2>Cannot send mail.</h2>";
	}
	


?>


</body>
</html>