<?php

set_time_limit(0);
ini_set('memory_limit', '256M');

require_once '../config.php';

/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
	
	
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysqli_query( $GLOBALS['connect'], 'SHOW TABLES');
		while($row = mysqli_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	
	$return = '';
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM '.$table);
		$num_fields = mysqli_num_fields($result);

		$return.= 'DROP TABLE IF EXISTS '.$table.';';
		$row2 = mysqli_fetch_row(mysqli_query( $GLOBALS['connect'], 'SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		
			if( mysqli_num_rows($result)>0 ) {
				
				$columns = '(';
				while( $list = mysqli_fetch_field($result) ) {
					$columns .= '`'.$list->name.'`,';
					
				}
				$columns = substr($columns, 0, -1);
				$columns .= ')';
				
				
				$return.= 'INSERT IGNORE INTO '.$table.' '.$columns.' VALUES'."\n";
				
				$counter = 0;
				while( $row = mysqli_fetch_row($result) )
				{
					$counter++;
					$return .= "(";
					for($j=0; $j<$num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						if ( $j<($num_fields-1) ) { $return.= ','; }
					}
					$return.= "),\n";
					if( $counter == 50 ) {
						$return = substr($return,0,-2);
						$return .= ';';
						$return .= "\n".'INSERT IGNORE INTO '.$table.' '.$columns.' VALUES'."\n";
						$counter = 0;
					}
				}
				$return = substr($return,0,-2);
				$return .= ';';
			
			}
		$return .= "\n\n\n";
		
	}

	//save file
	$new_file_name = 'db-backup-'.date('Y-m-d').time().'.sql';
	$handle = fopen( $new_file_name, 'w+' );
	fwrite($handle,$return);
	fclose($handle);
	return $new_file_name;

}





/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}



$returnfilename = backup_tables('localhost','unicalnu_userexm','jasonx','unicalnu_exams');
$files_to_zip = array($returnfilename);

//if true, good; if false, zip creation failed
$result = create_zip( $files_to_zip, $returnfilename.'.zip');
if( $result ) {
	
	@unlink($returnfilename);

	$download = $returnfilename.'.zip';
	$download_size = filesize( $download );

	
	  if (function_exists('mime_content_type')) {
		$mtype = mime_content_type( dirname(__FILE__).'/'.$download );
	  }
	  else if (function_exists('finfo_file')) {
		$finfo = finfo_open(FILEINFO_MIME); // return mime type
		$mtype = finfo_file($finfo, dirname(__FILE__).'/'.$download);
		finfo_close($finfo);  
	  }
	  if ($mtype == '') {
		$mtype = "application/force-download";
	  }
	
	
	
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
	readfile( dirname(__FILE__).'/'.$download );

}


?>