<?php 
	require_once 'inc/header.php';
    require_once '../config.php';
	
	
	/* SUBMIT FORM SECTION */
	if( isset($_POST['opt'], $_POST['submit']) ) {
		
		$processed = 0;
		switch( $_POST['submit'] ) {
			case 'Reset Rights':
				//$_ = mysqli_query( $GLOBALS['connect'], 'UPDATE exam_officers SET edit_allow_logon = 0' );
				//if( mysqli_affected_rows($GLOBALS['connect'])>0 )
					//$processed++;
			break;
			case 'Save Changes':
				foreach( $_POST['opt'] as $k=>$v ) {
					$_ = mysqli_query( $GLOBALS['connect'], 'UPDATE exam_officers SET user_right = '.$v.' WHERE eo_username = "'.$k.'"');
					if( mysqli_affected_rows($GLOBALS['connect'])>0 )
						$processed++;
				}
			break;
		}
		
	}
	/* SUBMIT FORM SECTION */
	
	
	
	
	function vunulrable() {
		$_ = mysqli_query( $GLOBALS['connect'], 'SELECT dept_options.do_id, dept_options.programme_option FROM dept_options' );
		if( 0!=$_->num_rows ) {
			$r = array();
			while( $d = $_->fetch_array() ) {
				$r[ $d['do_id'] ] = $d['programme_option'];
			}
			mysqli_free_result($_);
			return $r;
		}
		return array();
	}
	
?>

<link href="sitemapstyler/sitemapstyler.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="sitemapstyler/sitemapstyler.js"></script>
<style type="text/css">
#sitemap li a{ text-decoration:none !important;color:#69C !important;}
</style>

<div style="background:#666; font-size:18px; font-weight:700; font-family:arial; letter-spacing:1px; color:#FFF; padding:7px 10px;">Modify User Rights</div>
<?php
	echo "1 - Desk Officer<br /><br />";
	echo "2 - Lecturer<br /><br />";
	echo "3 - Exams Officer<br /><br />";
	$a = mysqli_query( $GLOBALS['connect'], 'SELECT `exam_officers`.`examofficer_id`, `exam_officers`.`department_id`, `exam_officers`.`fos`, `exam_officers`.`eo_username`, `exam_officers`.`eo_password`, `exam_officers`.`eo_title`, `exam_officers`.`eo_surname`, `exam_officers`.`edit_allow_logon`, `exam_officers`.`eo_firstname`, `exam_officers`.`eo_othernames`,`exam_officers`.`user_right`, departments.`departments_name` FROM `exam_officers` LEFT JOIN departments ON departments.`departments_id` = `exam_officers`.`department_id` WHERE `exam_officers`.`eo_username` NOT IN (\'cadmin\', \'super\') ORDER BY `exam_officers`.`eo_username`');
	
	$fos_title = vunulrable();
	$list = array();
	if( 0!=mysqli_num_rows($a) ) {
		while( $r=mysqli_fetch_assoc($a) ) {
			$list[ strtolower($r['eo_username']) ][] = $r;
		}
		mysqli_free_result($a);
		
		echo '<form action="',$_SERVER['PHP_SELF'],'" method="post" >';
		echo '<ul id="sitemap">';
		foreach( $list as $k=>$v ) {
			
			echo '<li><a href="#">',ucfirst($k),'</a>';
			echo '<ul>';
			echo '<li><select name="opt[',$v[0]['eo_username'],']"><option>',$v[0]['user_right'],'</option><option>1</option><option>2</option><option>3</option></select></li>';
			echo '</ul>';
			echo '</li>';
			
		}
		echo '</ul>';
		
		echo '<div><input type="submit" name="submit" value="Save Changes" /> <input type="submit" name="submit" value="Reset Rights" /></div></form>';
	}
?>  
  
<?php require_once 'inc/footer.php'; ?>