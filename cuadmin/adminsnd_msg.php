<?php
	
	session_start();
	
	require_once '../config.php';
	
	
	if( isset($_POST['mbody'], $_POST['msubj'], $_POST['users']) ) {
		
		$users = $_POST['users'];
		
		$qB = '';
		foreach( $users as $k=>$v ) {
			$qB .= '("'.$v.'", "ADMIN", "'.$_POST['msubj'].'", "'.$_POST['mbody'].'", CURDATE()),';
		}
		
		if( !empty($qB) ) {
			$qB = substr($qB, 0, -1);
			$qB = 'INSERT INTO messaging (`m_to`, `m_by`,  `m_subj`,  `m_bdy`, `m_dt`) VALUES '.$qB;
			if( $r = mysqli_multi_query( $GLOBALS['connect'], $qB ) ) {	
				do {if ($result = mysqli_store_result($GLOBALS['connect'])) { while ($row = mysqli_fetch_row($result)) {} mysqli_free_result($result);}
				} while (mysqli_next_result($GLOBALS['connect']));
			}
		}
		
		
		if( $r ) {
			exit('1');
		} else {
			exit('0');
		}
		
	}
	
	exit('0');

?>