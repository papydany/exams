<?php
ini_set('max_execution_time','4400');
ini_set('memory_limit','1024M');
function clean_link($strtxt)
{
	//:|/\*?,>< ;
	//$c = array(':','|','/','\'',"\\",'*','?','-','>','<','&','=','%','.','(',')','{','}','[',']','!','#','~','@','£','$','^','¬','.',',',';' );
	$c = array('\'',"\\", "'","\"");
	$strtxt = stripslashes(strip_tags($strtxt));
	foreach ($c as $k=>$v)
	{
		$strtxt = str_replace($v, '' , trim($strtxt));
	}
	return $strtxt;
}

?>
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
	<div id="wrapper">
    	<!-- h1 tag stays for the logo, you can use the a tag for linking the index page -->
    	<h1><a href="index.php"><span>Stamba Backup & Restore</span></a></h1>
        
        <!-- You can name the links with lowercase, they will be transformed to uppercase by CSS, we prefered to name them with uppercase to have the same effect with disabled stylesheet -->
        <ul id="mainNav">
        	<li><a href="database.php">DASHBOARD</a></li> <!-- Use the "active" class for the active menu item  -->
        	<li><a href="backup3.php" class="active">BACKUP</a></li>
        	<!--<li><a href="restore.php">RESTORE</a></li>-->
        	<li class="logout"><a href="?logout=1">LOGOUT</a></li>
        </ul>
        <!-- // #end mainNav -->
        
        <div id="containerHolder">
			<div id="container">
                
                <!-- h2 stays for breadcrumbs -->
                <h2><a href="#" class="active">Create a Backup</a></h2>
                
                <div id="main">
                	<form action="" class="jNice">
					<h3>Backup Log Category 3</h3>
                    	<table cellpadding="0" cellspacing="0"><td>
<?php
// Include settings
include("config.php");

// Set the suffix of the backup filename
if ($table == '*') {
	$extname = 'all';
}else{
	$extname = str_replace(",", "_", $table);
	$extname = str_replace(" ", "_", $extname);
}
$tables=array('messaging','nationality','programme','programme_type','registered_semester');

// Generate the filename for the backup file
$filess = 'database_backup/category3/backup' . date("d_m_Y_H_i_s",Time()) . '_' . $extname;
//echo 'filename: '. $filess;
echo 'my file : '.$filess.'<br>';
// Call the backup function for all tables in a DB
backup_tables($DBhost,$DBuser,$DBpass,$DBName,$tables,$extname,$filess);

// Backup the table and save it to a sql file
	function backup_tables($host,$user,$pass,$name,$tables,$bckextname,$filess)
{
		$link = mysql_connect($host,$user,$pass) or die('Error: Connection not successful');
		mysql_select_db($name,$link) or die('Error: Database not successful');
	$return = "";	

		// Get all of the tables
		if($tables == '*') {
			$tables = array();
			$result = mysql_query('SHOW TABLES') or die(mysql_error());
			while($row = mysql_fetch_row($result)) {
				$tables[] = $row[0];
			}
		} else {
			/*if (is_array($tables)) {
				$tables = explode(',', $tables);
			}*/

			$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
echo 'No of tables = '.count($tables);
		// Cycle through each provided table
		foreach($tables as $table) {
			echo 'table: '. $table.'<br>';
			
			//$link = mysql_connect($host,$user,$pass) or die('Error: Connection not successful');
		//mysql_select_db($name,$link) or die('Error: Database not successful');
		
			$result = mysql_query('SELECT * FROM '.$table) or die(mysql_error());
			$num_fields = mysql_num_fields($result) or die(mysql_error());
		
		echo 'no of fields:' .$num_fields;
		echo 'No of records: '. mysql_num_rows($result);// or die(mysql_error());
		
			// First part of the output - remove the table
			$return .= "DROP TABLE " . $table . ";<|||||||>";

			// Second part of the output - create table
			$row2 = mysql_fetch_row(mysql_query("SHOW CREATE TABLE ".$table));// or die(mysql_error());
			$return .= "\n\n" . $row2[1] . ";<|||||||>\n\n";
//echo $return.'<br>';
			//echo 'row2:'. $row2[1];
			echo "BEGIN $table";
			// Third part of the output - insert values into new table
			for ($i = 0; $i < $num_fields; $i++) { //echo ' rec :'.$i.' - ';
				while($row = mysql_fetch_row($result)) {
					//echo "INSERT INTO ".$table;
					
					$return.= "INSERT INTO ".$table." VALUES(";
					for($j=0; $j < $num_fields; $j++) {
						 
						// echo 'field : '.$row[$j].' j='.$j;
						$row[$j] = addslashes($row[$j]);
						$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						
						//echo 'field : '.$row[$j].' j='.$j;
						//$row[$j] = clear_link($row[$j]);
						if (isset($row[$j])) { 
							$return .= "'" . $row[$j] . "'"; 
							} else { 
							$return .= "''"; 
							}
						if ($j<($num_fields-1)) { 
							$return.= ","; 
						}
						//echo ' END FOR';
					} 
					$return.= ");<|||||||>\n";
					//echo 'ENF WHILE';
				}
				//echo 'END FOR';
			}
			$return.="\n\n\n";
			
			echo "DONE! $table<br>";
			/*$handle = fopen($filess.".sql","a+");
			fwrite($handle,$return);
			fclose($handle);
			$return = '';*/

			$gzdata = gzencode($return, 9);
        $handle = fopen($filess.'.sql.gz','w+');
        fwrite($handle, $gzdata);
        fclose($handle);
			
	}

//echo 'files: ',$filess,'<br>',$return,'<br>';
		// Save the sql file
	//$handle = fopen($filess.'.sql','w+');
		//fwrite($handle,$return);
		//fclose($handle);
//file_put_contents($filess.'.sql', $return);
	// Close MySQL Connection
	
	echo 'FINAL DONE'.'<br/>';
	mysql_close();
} 

/*	require_once('pclzip.lib.php');
	$archive = new PclZip($filess.'.zip');
	$v_dir = dirname(getcwd()); // or dirname(__FILE__);
	$v_remove = $v_dir;
	$v_list = $archive->create($v_dir, PCLZIP_OPT_REMOVE_PATH, $v_remove);
	if ($v_list == 0) {
		die("Error : ".$archive->errorInfo(true));
	}
*/
      	// Print the message
	print('The backup has been created successfully.'. "\n");//<br />You can get <b>MySQL dump file</b> <a href="download.php?f=' . $filess . '//.sql" class="view">here</a>.<br>' . "\n");
	//print('You can get <b>Backed-up files archive</b> <a href="' . $filess . '.zip" class="view">here</a>.<br>' . "\n");
?>

				</td></table>
				<br />
                    </form>
                </div>
                <!-- // #main -->
                
                <div class="clear"></div>
            </div>
            <!-- // #container -->
        </div>	
        <!-- // #containerHolder -->
        
      <!--  <p id="footer">Feel free to use and customize it, as you feel like. Credit & backlink is much appreciated but not obligatory! If you are using it for commercial purposes I kindly ask you to give some credit, but still it's your free will. <a href="http://campstamba.com">http://campstamba.com</a></p> -->
    </div>
    <!-- // #wrapper -->
</body>
</html>

