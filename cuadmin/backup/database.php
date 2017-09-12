<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Stamba Backup & Restore</title>

<!-- CSS -->
<link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen" />
<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie6.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie7.css" /><![endif]-->

<!-- JavaScripts-->
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/jNice.js"></script>
</head>

<body>
	<div id="wrapper2">
    	<!-- h1 tag stays for the logo, you can use the a tag for linking the index page -->
    	<h1><a href="index.php"><span>Stamba Backup & Restore</span></a></h1>
        
        <!-- You can name the links with lowercase, they will be transformed to uppercase by CSS, we prefered to name them with uppercase to have the same effect with disabled stylesheet -->
        <ul id="mainNav2">
        	<li><a href="database.php" class="active">DASHBOARD</a></li> <!-- Use the "active" class for the active menu item  -->
        	<li><a href="backup1.php" target="_blank">BACKUP CATEGORY 1</a></li>
            <li><a href="backup2.php" target="_blank">BACKUP CATEGORY 2</a></li>
            <li><a href="backup3.php" target="_blank">BACKUP CATEGORY 3</a></li>
            <li><a href="backup4.php" target="_blank">BACKUP CATEGORY 4</a></li>
            <li><a href="backup5.php" target="_blank">BACKUP CATEGORY 5</a></li>
            <li><a href="backup6.php" target="_blank">BACKUP CATEGORY 6</a></li>
        	<!--<li><a href="restore.php">RESTORE</a></li>-->
        	<li class="logout"><a href="?logout=1">LOGOUT</a></li>
        </ul>
        <!-- // #end mainNav -->
        
        <div id="containerHolder">
			<div id="container2">
                
                <!-- h2 stays for breadcrumbs -->
                <h2><a href="#" class="active">Dashboard</a></h2>
                <h3>Available Backups</h3>
                <div id="main2">
                	<form action="" class="jNice">
			  <h3>Category 1</h3>
                    	<table cellpadding="0" cellspacing="0">
<?php
// List the files
$dir = opendir ("database_backup/category1"); 
while (false !== ($file = readdir($dir))) { 

	// Print the filenames that have .sql extension
	if (strpos($file,'.sql',1)) { 

	// Get time and date from filename
	$date = substr($file, 6, 10);
	$time = substr($file, 20, 9);

	// Remove the sql extension part in the filename
	$filenameboth = str_replace('.sql.gz', '', $file);
                        
	// Print the cells
		print("<tr>\n");
		print("  <td>" . $filenameboth . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " - " . $time .' ('. round(filesize('database_backup/category1/' . $file )/1048576 ,2) . " MB)</td>\n");
		print("  <td class='action'>");
		//print("<a href='restore.php?id=" . $filenameboth . "' class='edit'>Restore</a>\n");
		//print("<a href='download.php?f=" . $filenameboth . ".sql' class='view'>Download SQL</a>\n");
		//print("<a href='backup/" . $filenameboth . ".zip' class='view'>Download ZIP</a>\n");
		print("<a href='delete.php?a_file=" . $filenameboth . "' class='delete'>Delete</a></td>\n");
		print("</tr>\n");
	} 
} 
?>

				</table>
				<br />
                    </form>
                </div>


                <div id="main2">
                    <form action="" class="jNice">
                      <h3>Category 2</h3>
                        <table cellpadding="0" cellspacing="0">
<?php
// List the files
$dir = opendir ("database_backup/category2"); 
while (false !== ($file = readdir($dir))) { 

    // Print the filenames that have .sql extension
    if (strpos($file,'.sql',1)) { 

    // Get time and date from filename
    $date = substr($file, 6, 10);
    $time = substr($file, 20, 9);

    // Remove the sql extension part in the filename
    $filenameboth = str_replace('.sql.gz', '', $file);
                        
    // Print the cells
        print("<tr>\n");
        print("  <td>" . $filenameboth . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " - " . $time .' ('. round(filesize('database_backup/category2/' . $file )/1048576 ,2) . " MB)</td>\n");
        print("  <td class='action'>");
        //print("<a href='restore.php?id=" . $filenameboth . "' class='edit'>Restore</a>\n");
       // print("<a href='download.php?f=" . $filenameboth . ".sql' class='view'>Download SQL</a>\n");
        //print("<a href='backup/" . $filenameboth . ".zip' class='view'>Download ZIP</a>\n");
        print("<a href='delete.php?b_file=" . $filenameboth . "' class='delete'>Delete</a></td>\n");
        print("</tr>\n");
    } 
} 
?>

                </table>
                <br />
                    </form>
                </div>
                <!-- // #main -->
                
                <div class="clear"></div>

     <div id="main2">
                    <form action="" class="jNice">
              <h3>Category 3</h3>
                        <table cellpadding="0" cellspacing="0">
<?php
// List the files
$dir = opendir ("database_backup/category3"); 
while (false !== ($file = readdir($dir))) { 

    // Print the filenames that have .sql extension
    if (strpos($file,'.sql',1)) { 

    // Get time and date from filename
    $date = substr($file, 6, 10);
    $time = substr($file, 20, 9);

    // Remove the sql extension part in the filename
    $filenameboth = str_replace('.sql.gz', '', $file);
                        
    // Print the cells
        print("<tr>\n");
        print("  <td>" . $filenameboth . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " - " . $time .' ('. round(filesize('database_backup/category3/' . $file )/1048576 ,2) . " MB)</td>\n");
        print("  <td class='action'>");
        //print("<a href='restore.php?id=" . $filenameboth . "' class='edit'>Restore</a>\n");
       // print("<a href='download.php?f=" . $filenameboth . ".sql' class='view'>Download SQL</a>\n");
        //print("<a href='backup/" . $filenameboth . ".zip' class='view'>Download ZIP</a>\n");
        print("<a href='delete.php?c_file=" . $filenameboth . "' class='delete'>Delete</a></td>\n");
        print("</tr>\n");
    } 
} 
?>

                </table>
                <br />
                    </form>
                </div>

<div id="main2">
                    <form action="" class="jNice">
              <h3>Category 4</h3>
                        <table cellpadding="0" cellspacing="0">
<?php
// List the files
$dir = opendir ("database_backup/category4"); 
while (false !== ($file = readdir($dir))) { 

    // Print the filenames that have .sql extension
    if (strpos($file,'.sql',1)) { 

    // Get time and date from filename
    $date = substr($file, 6, 10);
    $time = substr($file, 20, 9);

    // Remove the sql extension part in the filename
    $filenameboth = str_replace('.sql.gz', '', $file);
                        
    // Print the cells
        print("<tr>\n");
        print("  <td>" . $filenameboth . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " - " . $time .' ('. round(filesize('database_backup/category4/' . $file )/1048576 ,2) . " MB)</td>\n");
        print("  <td class='action'>");
        //print("<a href='restore.php?id=" . $filenameboth . "' class='edit'>Restore</a>\n");
       // print("<a href='download.php?f=" . $filenameboth . ".sql' class='view'>Download SQL</a>\n");
        //print("<a href='backup/" . $filenameboth . ".zip' class='view'>Download ZIP</a>\n");
        print("<a href='delete.php?d_file=" . $filenameboth . "' class='delete'>Delete</a></td>\n");
        print("</tr>\n");
    } 
} 
?>

                </table>
                <br />
                    </form>
                </div>
                 <div class="clear"></div>

                  <div id="main2">
                    <form action="" class="jNice">
              <h3>Category 5</h3>
                        <table cellpadding="0" cellspacing="0">
<?php
// List the files
$dir = opendir ("database_backup/category5"); 
while (false !== ($file = readdir($dir))) { 

    // Print the filenames that have .sql extension
    if (strpos($file,'.sql',1)) { 

    // Get time and date from filename
    $date = substr($file, 6, 10);
    $time = substr($file, 20, 9);

    // Remove the sql extension part in the filename
    $filenameboth = str_replace('.sql.gz', '', $file);
                        
    // Print the cells
        print("<tr>\n");
        print("  <td>" . $filenameboth . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " - " . $time .' ('. round(filesize('database_backup/category5/' . $file )/1048576 ,2) . " MB)</td>\n");
        print("  <td class='action'>");
        //print("<a href='restore.php?id=" . $filenameboth . "' class='edit'>Restore</a>\n");
       // print("<a href='download.php?f=" . $filenameboth . ".sql' class='view'>Download SQL</a>\n");
        //print("<a href='backup/" . $filenameboth . ".zip' class='view'>Download ZIP</a>\n");
        print("<a href='delete.php?e_file=" . $filenameboth . "' class='delete'>Delete</a></td>\n");
        print("</tr>\n");
    } 
} 
?>

                </table>
                <br />
                    </form>
                </div>
                 <div id="main2">
                    <form action="" class="jNice">
                       <h3>Category 6</h3>
                        <table cellpadding="0" cellspacing="0">
<?php
// List the files
$dir = opendir ("database_backup/category6"); 
while (false !== ($file = readdir($dir))) { 

    // Print the filenames that have .sql extension
    if (strpos($file,'.sql',1)) { 

    // Get time and date from filename
    $date = substr($file, 6, 10);
    $time = substr($file, 20, 9);

    // Remove the sql extension part in the filename
    $filenameboth = str_replace('.sql.gz', '', $file);
                        
    // Print the cells
        print("<tr>\n");
    
        print("  <td>" . $filenameboth . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " - " . $time .' ('. round(filesize('database_backup/category6/' . $file )/1048576 ,2) . " MB)</td>\n");
        print("  <td class='action'>");
                

        //print("<a href='restore.php?id=" . $filenameboth . "' class='edit'>Restore</a>\n");
       // print("<a href='download.php?f=" . $filenameboth . ".sql' class='view'>Download SQL</a>\n");
        //print("<a href='backup/" . $filenameboth . ".zip' class='view'>Download ZIP</a>\n");
        print("<a href='delete.php?f_file=" . $filenameboth . "' class='delete'>Delete</a></td>\n");
        print("</tr>\n");
    } 
} 
?>

                </table>
                <br />
                    </form>
                </div>
                 <div class="clear"></div>
            </div>
            <!-- // #container -->
        </div>	
        <!-- // #containerHolder -->
        
     <!--   <p id="footer">Feel free to use and customize it, as you feel like. Credit & backlink is much appreciated but not obligatory! If you are using it for commercial purposes I kindly ask you to give some credit, but still it's your free will. <a href="http://campstamba.com">http://campstamba.com</a></p> -->
    </div>
    <!-- // #wrapper -->
</body>
</html>
