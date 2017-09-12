<?php
	
	require_once '../config.php';
		
	$sem_title = array(1=>'First Semester', 2=>'Second Semester');
	$session = ' && r_publish.rp_session = "'.$_POST['ss'].'"';
	$faculty = empty($_POST['f']) ? '' : ' WHERE departments.fac_id ='.$_POST['f'];
	
	$faculty2 = empty($_POST['f']) ? '' : ' r_publish.rp_departmentid IN ( SELECT departments_id FROM departments WHERE fac_id = '.$_POST['f'].') &&';
	$semester = ' r_publish.rp_semester = "'.$sem_title[ $_POST['s'] ].'"';
	$level = ' && r_publish.rp_level = '.$_POST['l'];
	
	$pub_ls = array();
	
	
	$sql1 = 'SELECT rp_id, rp_departmentid, rp_status FROM r_publish WHERE'.$faculty2.$semester.$session.$level;
	
	$_r = $GLOBALS['connect']->query( $sql1 ) or die( 'dd'.mysqli_error($GLOBALS['connect']) );

	if( 0!=$_r->num_rows ) {

		while( $d=$_r->fetch_array() ) {
			$pub_ls[ $d['rp_departmentid'] ] = array($d['rp_status'], $d['rp_id']);
		}
		$_r->close();
	}
	
	
	$sql = 'SELECT departments.departments_id, departments.departments_name FROM departments '.$faculty;
	

	$_r = $GLOBALS['connect']->query( $sql );
	if( 0!=$_r->num_rows ) {
		
		
		$return = '<form action="modify_publish.php" method="post" onsubmit="return UPLOADER.submit(this, {\'onStart\' : s, \'onComplete\' : e})">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="newtable">
  <thead>
  <tr>
    <th width="5%"><input name="sync" type="checkbox" onchange="return checkUncheckAll(this)"></th>
    <th width="65%">Dept. Name</th>
    <th width="30%">Status</th>
  </tr>
  </thead><tbody>';
  		
		$i = 0;
		
		$return .= '<input name="f" type="hidden" value="'.$_POST['f'].'">
		<input name="s" type="hidden" value="'.$_POST['s'].'">
		<input name="l" type="hidden" value="'.$_POST['l'].'">
		<input name="ss" type="hidden" value="'.$_POST['ss'].'">';
		
		while( $d = $_r->fetch_array() ) {
			$i++;
			
			if( isset( $pub_ls[$d[0]][0] ) && !empty($pub_ls[$d[0]][0] ) ){
				$temp = '<input name="chk['.$i.']" type="checkbox" value="ping"><input name="pi['.$i.']" type="hidden" value="'.$pub_ls[$d[0]][1].'">';
				$output = '<span style="background:green;  padding:0 3px 1px; color:#FFF;">'.$pub_ls[$d[0]][0].'</span>';
				$tr = 'style="background:#FFF"';
			} else {
				$temp = '<input name="pi['.$i.']" type="checkbox" value="">';
				$output = '';
				$tr = '';
			}
			
			$return .= '<tr '.$tr.'>
							<td><input name="dept['.$i.']" type="hidden" value="'.$d[0].'">'.$temp.'</td>
							<td>'.$d[1].'</td>
							<td>'.$output.'</td>
						</tr>';
		}
		$_r->close();
		$return .= '  </tbody>
</table><p style="padding:5px 10px; text-align:right">
<input name="" type="submit" value="Modify Publishing">
</p></form>';
	}
	
	echo $return;
	//exit('The Power Of Love');

?>