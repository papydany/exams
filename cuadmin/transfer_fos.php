<?php

	require_once '../config.php';
	
	if( $_POST['schk'] ) {
		
		$programme_id = $_POST['nprogramme'];
		list( $department_id, $faculty_id ) = explode(',', $_POST['ndepartment']);
		$stdcourse = $_POST['ncourse'];
		$year = $_POST['nsess'];
		$level = $_POST['nlevel'];
		
		$stds = array();
		foreach( $_POST['schk'] as $k=>$v ) { $stds[] = $v; }
		
		$std_sql = 'UPDATE students_profile SET students_profile.stdprogramme_id = "'.$programme_id.'", students_profile.stdfaculty_id = "'.$faculty_id.'", students_profile.stddepartment_id = "'.$department_id.'", students_profile.stdcourse = "'.$stdcourse.'", std_custome1 = "'.$level.'", students_profile.std_custome2 = "'.$year.'", students_profile.std_custome18 = "'.$year.'" WHERE students_profile.std_id IN ('.implode(',', $stds).')';
		
		$sr_sql = 'UPDATE students_reg SET students_reg.programme_id = "'.$programme_id.'", students_reg.faculty_id = "'.$faculty_id.'", students_reg.department_id = "'.$department_id.'" WHERE students_reg.std_id IN ('.implode(',', $stds).')';
		
		$r = mysqli_multi_query( $GLOBALS['connect'], $std_sql.';'.$sr_sql );
		if( $r ) {
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: transfer_stds.php?info=1');
			exit;
		}
		
	}
	
?>