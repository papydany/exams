<?php

	session_start();

	if ( !isset($_SESSION['logged']) ||	22 != $_SESSION['logged'] ) {
		
		$redirect = str_replace('&', 'amp;', $_SERVER['REQUEST_URI']);
		header( "Refresh: 2; URL=login.php?redirect=$redirect" );
		exit ( "<div style='font-family:arial; color:#555; font-size:14px; text-align:center'>You are being redirected to the logon page! or <a href=\"login.php?redirect=$redirect\" style=\"color:blue; text-decoration:none;\">Click Here</a></div>" );
		
	}

?>