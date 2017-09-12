<?php
	require_once './config.php';
	if( isset($_POST['mid']) ) {
		
		$u = mysqli_query( $GLOBALS['connect'], 'UPDATE messaging SET unread = "N" WHERE m_id = '.$_POST['mid'].'' );
		$load = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM messaging WHERE m_id = "'.$_POST['mid'].'"');
		if( 0!=mysqli_num_rows($load) ) {
			$d = mysqli_fetch_assoc($load);
			mysqli_free_result($load);
		
			echo <<<M
					<div style="  padding:2px 0 2px 5px; display:block; overflow:hidden">
					
					<div style=" background:#F7F9FB; border-bottom:1px solid #EFF3F8; font-weight:700; padding:5px; display:block; font-size:15px; overflow:hidden; text-align:center">{$d['m_subj']}</div>
					<div style="padding:3px 5px; font-size:13px; color:#444">{$d['m_bdy']}</div>
					</div>			
M;
			exit;
		} else {
			exit('0');
		}
	}
	
	
	
	if( isset($_POST['del'], $_POST['chk']) ) {
		
		$qB = '';
		foreach( $_POST['chk'] as $v ) {
			$qB .= $v.',';
		}

		if( !empty($qB) ) {
			$qB = substr($qB, 0,-1);
			$d = mysqli_multi_query( $GLOBALS['connect'], 'DELETE FROM messaging WHERE m_id IN ('.$qB.');OPTIMIZE TABLE messaging' );
		}
		
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: msg.php');
		exit;
	
	}
	
	exit('O n c e&nbsp;&nbsp;&nbsp;L o s t&nbsp;&nbsp;&nbsp;B u t&nbsp;&nbsp;&nbsp;F o u n d&nbsp;&nbsp;&nbsp;<a href="msg.php">r e t u r n</a>');
	
	
?>