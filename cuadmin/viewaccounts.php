<?php 
	require_once 'inc/header.php';
    require_once '../config.php';
	
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

<div style="background:#666; font-size:18px; font-weight:700; font-family:arial; letter-spacing:1px; color:#FFF; padding:7px 10px;">All Accounts</div>
<?php
	
	$a = mysqli_query( $GLOBALS['connect'], 'SELECT `exam_officers`.`examofficer_id`, `exam_officers`.`department_id`, `exam_officers`.`fos`, `exam_officers`.`eo_username`, `exam_officers`.`eo_password`,`exam_officers`.`user_right`, `exam_officers`.`eo_title`, `exam_officers`.`eo_surname`, `exam_officers`.`eo_firstname`, `exam_officers`.`eo_othernames`, departments.`departments_name` FROM `exam_officers` LEFT JOIN departments ON departments.`departments_id` = `exam_officers`.`department_id` WHERE `exam_officers`.`eo_username` NOT IN (\'cuadmin\', \'super\') ORDER BY `exam_officers`.`eo_username`');
	
	$fos_title = vunulrable();
	$list = array();
	if( 0!=mysqli_num_rows($a) ) {
		while( $r=mysqli_fetch_assoc($a) ) {
			$list[ strtolower($r['eo_username']) ][] = $r;
		}
		mysqli_free_result($a);
		echo '<form action="',$_SERVER['PHP_SELF'],'" method="post" >';
		echo '<ul id="sitemap">';
		//var_dump($list);
		foreach( $list as $k=>$v ) {
		
			echo '<li><a href="#">',ucfirst($k),' &raquo; (',$v[0]['eo_password'],')</a>';
			echo '<ul>';
			foreach( $v as $kk ) {
				echo '<li><a href="#">',$kk['departments_name'],'</a>    ----    <a href="#">',$fos_title[ $kk['fos'] ],'</a><a href="vx.php?i=',$kk['examofficer_id'],'" style="color:red !important">Delete</a></li>';

			
			
			}
			echo '<li><select name="opt[',$v[0]['eo_username'],']"><option>',$v[0]['user_right'],'</option><option>2</option><option>4</option><option>6</option></select></li>';
			echo '</ul>';
			echo '</li>';

			
		}
		echo '</ul>';

		echo '<div><input type="submit" name="submit" value="Save Changes" /> <input type="submit" name="submit" value="Reset Rights" /></div></form>';
	}
?>  
  
<?php require_once 'inc/footer.php'; ?>