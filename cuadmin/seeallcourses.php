<?php 
	require_once 'inc/header.php';
	require_once '../config.php';

?>	
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:450px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 0px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.us { width:70%;}
.us thead{ background:#666; color:#FFF;}
.us thead th, .us tbody td{ border-bottom:1px solid #EEE; padding:5px; text-align:left}
.i{
  margin:0 3px;
  font-style:normal;
  color:#bbb
}

</style>
	
<?php	
	if( !isset($_GET['std']) || empty($_GET['std']) ) {
		exit('<div style="padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">Unknown Pattern of Work</div>');
	}
	
	echo '<p style="font-size:16px; padding:7px 4px 4px; font-weight:700;" class="us">MATRIC NO: ',$_GET['mn'],'</p>';
	
	$_ = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM course_reg WHERE std_id = '.$_GET['std'].' ORDER BY cyearsession' );
	
	if( 0 !== mysqli_num_rows($_) ) {
		
		echo '<form method="post" action="delseeallcourses.php">';
		
		echo '<input type="hidden" name="std" value="',$_GET['std'],'" />',
		'<input type="hidden" name="mn" value="',$_GET['mn'],'" />';
		
		$c = 0;
		echo '<table class="us"><thead>',
			'<th width="5%">&nbsp;</th>',
			'<th width="5%">S/N</th>',
			'<th width="15%">Course Code</th>',
			'<th width="10%">Level</th>',
			'<th width="10%">Session</th>',
			'<th width="60%">Action</th>',		
			'</thead><tbody>';
		while( $row = mysqli_fetch_assoc($_) ) {
		$c++;
			echo '<tr>',
			'<td><input type="checkbox" name="id[]" value="',$row['thecourse_id'],'~',$row['cyearsession'],'" /></td>',
			'<td>',$c,'</td>',
			'<td>',$row['stdcourse_custom2'],'</td>',
			'<td>',$row['clevel_id'],'</td>',
			'<td>',$row['cyearsession'],'</td>',
			'<td><a href="delseeallcourses.php?std=',$_GET['std'],'&cid=',$row['thecourse_id'],'&csess=',$row['cyearsession'],'&mn=',$_GET['mn'],'">Delete</a></td></tr>';
		}
		echo '<tr><td colspan="6"><input type="submit" name="DELETE" value="DELETE" /></td></tr>';
		echo '</tbody></table>';
		
		echo '</form>';
	}
?>