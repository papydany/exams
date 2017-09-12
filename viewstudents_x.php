<?php
	require_once 'inc/header.php';
    require_once 'config.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.us { width:590px; margin:0 auto; border:1px solid #EEE; border-left:none;}
.us thead{ background:#666; color:#FFF;}
.us thead th, .us tbody td{ border-bottom:1px solid #F5f5f5; padding:5px; text-align:left}
.us tbody td{ border-left:1px solid #F5f5f5;padding:3px 5px; color:#444}
.i{
  margin:0 3px;
  font-style:normal;
  color:#bbb
}

#table h3 { font-size:11px!important; cursor:pointer; background:url(images/sort.gif) 7px center no-repeat; padding-left:18px}
#table .nosort h3{background:none;}
</style>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" autocomplete="off">
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1; margin:0 auto;">
  <tr>

  <?php
      if($_SESSION['myprogramme_id']==7){

      echo'<td width="" class="td"><p>Month Of Entery</p><label for="select"></label>
      <select name="month" id="month">
      <option value="">.. Pick Month ..</option>
      <option value="1">APRIL</option>
      <option value="2">AUGUST</option>
      </select></td>


      <td width="" class="td">
<span id="result"></span>
      <p>Year / Session</p><label for="select2"></label>
      <select name="yearsession" id="yearsession">
     <option value="">-------------</option>
      </select></td>'; 
    }else{
    ?>

    <td width="" class="td"><p>Year / Session</p><label for="select2"></label>
      <select name="yearsession">
		<?php
			
			$chosen = '';
            for ($year = (date('Y')-1); $year >= 1998; $year--) {
				$chosen = $_GET['yearsession'] == $year ? 'selected="selected"' : '';
			
						$yearnext =$year+1;
						echo "<option value='$year' $chosen>$year/$yearnext</option>\n";
				
				}
  echo'</select></td>';
            } 
		 
        ?> 
   

  <td width="" class="td"><p>Level</p>
      <select name="level" id="select">
      <option value="">Select</option>
		<?php
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level` WHERE programme_id = '.$_SESSION['myprogramme_id'].' ORDER BY level_name');
		
        while( $l=mysqli_fetch_assoc($ac) ) {
			
			if( $_GET['level'] == $l['level_id'] ) {
				echo '<option selected="selected" value=',$l['level_id'],'>';
			} else {
				echo '<option value=',$l['level_id'],'>';
			}
			
			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {
				echo $l['level_name'].' - DIPLOMA';
			} elseif( $l['level_id'] > 12 ) {
				//echo 'Contact '.substr($l['level_name'],0,1);
				echo $l['level_name'];
			} else
				echo $l['level_name'];

			echo '</option>';
        }
        mysqli_free_result($ac);
        ?>
      </select></td>

    <td width="" class="td"><p>Department</p><label for="select2"></label>
      <?php
	  	echo '<input name="department" type="hidden" value="',$_SESSION['mydepartment_id'],',',$_SESSION['myfaculty_id'],'">';
	  ?>
      <select disabled>
		<?php
        $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_name` FROM `departments` WHERE `departments_id` = '.$_SESSION['mydepartment_id'].'');
        $r=mysqli_fetch_assoc($l_dept);
        echo '<option>',$r['departments_name'],'</option>';
        mysqli_free_result($l_dept);
        ?>
      </select></td>

      
    <td width="" class="td"><p>Programme</p><label for="select"></label>
      <?php
	  	echo '<input name="programme" type="hidden" value="',$_SESSION['myprogramme_id'],'">';
	  ?>    
      <select disabled>
	<?php
		$l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `programme_name` FROM `programme` WHERE `programme_id` = '.$_SESSION['myprogramme_id'] );
		$r=mysqli_fetch_assoc($l_dept);
		echo '<option>',$r['programme_name'],'</option>';
		mysqli_free_result($l_dept);
    ?>
      </select></td>

    <td width="" class="td" style="padding-right:20px"><p>Field Of Study</p><label for="select"></label>
      <select name="course" id="field">
		<option value="">.. Pick Field ..</option>
		<?php
		
			$fos = load_fos( $_SESSION['myusername'] );
			foreach( $fos as $r ) {
				if( $_GET['course'] == $r['do_id'] ) {
					echo '<option value="',$r['do_id'],'" selected="selected">',$r['programme_option'],'</option>';
				} else {
					echo '<option value="',$r['do_id'],'">',$r['programme_option'],'</option>';
				}
			}
        ?>      
      </select></td>
  </tr>
  <tr>
  <td colspan="5" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="View Students"></td>
  </tr>  
</table>

<?php
	if( isset($_SESSION['info']) ) {
		switch( $_SESSION['info'] ) {
			case 1:
			case 11:
				echo '<div class="info">Action Completed</div>';				
			break;
			case 0:
			case 12:
				echo '<div class="info">Please Try Again</div>';
			break;
			
		}
		unset( $_SESSION['info'] );
	}
	$thevalue = isset($_GET['month'])?$_GET['month']:'';
	if(!$thevalue == ""){
	$thevalue =$thevalue == 1?"April":"August";
}

	

	if( isset($_GET['submit']) && !empty($_GET['department']) && !empty($_GET['level']) && !empty($_GET['course']) ):
		
		list( $dept, $fac ) = explode(',', $_GET['department']);
        if($_SESSION['myprogramme_id']==7){
        $aa = mysqli_query( $GLOBALS['connect'], 'SELECT COUNT(*) FROM students_profile WHERE stdprogramme_id = '.$_GET['programme'].' && stdfaculty_id='.$fac.' && stddepartment_id = '.$dept.' && stdcourse = '.$_GET['course'].' && std_custome1 = '.$_GET['level'].' && std_custome2 = "'.$_GET['yearsession'].'" && std_custome6 = "'.$thevalue.'"')or die(mysqli_error($GLOBALS['connect']));
        }else{
		$aa = mysqli_query( $GLOBALS['connect'], 'SELECT COUNT(*) FROM students_profile WHERE stdprogramme_id = '.$_GET['programme'].' && stdfaculty_id='.$fac.' && stddepartment_id = '.$dept.' && stdcourse = '.$_GET['course'].' && std_custome1 = '.$_GET['level'].' && std_custome2 = "'.$_GET['yearsession'].'"')or die(mysqli_error($GLOBALS['connect']));
		}
$r=mysqli_fetch_row($aa);


if($r[0] > 0)
{
$no = $r[0];
}else{
    $no=1;
}

// number of row per page
$rowperpage=300;
//find out total page
$totalpages=ceil($no/$rowperpage);
 //get current or set a fault
 if(isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])){
	// cast vas as int
	$currentpage= (int )$_GET['currentpage'];
 }else{
	 // defaut page
	 $currentpage= 1;
 }
 //if current page is greater than total page
 if($currentpage > $totalpages){
	 // set page to last
	 $currentpage =$totalpages;
 }
 //offset of the list,based on current page
 $offset=($currentpage - 1) * $rowperpage;

/*   End of pagination    */
//echo $totalpages;
//die();
	$c = 0;

 if($_SESSION['myprogramme_id']==7){
        $a = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM students_profile WHERE stdprogramme_id = '.$_GET['programme'].' && stdfaculty_id='.$fac.' && stddepartment_id = '.$dept.' && stdcourse = '.$_GET['course'].' && std_custome1 = '.$_GET['level'].' && std_custome2 = "'.$_GET['yearsession'].'" && std_custome6 = "'.$thevalue.'" ORDER BY matric_no ASC LIMIT '.$offset.','.$rowperpage) or die(mysqli_error($GLOBALS['connect']));
        }else{
		$a = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM students_profile WHERE stdprogramme_id = '.$_GET['programme'].' && stdfaculty_id='.$fac.' && stddepartment_id = '.$dept.' && stdcourse = '.$_GET['course'].' && std_custome1 = '.$_GET['level'].' && std_custome2 = "'.$_GET['yearsession'].'" ORDER BY matric_no ASC LIMIT '.$offset.','.$rowperpage) or die(mysqli_error($GLOBALS['connect']));
		}
		

		if( 0==mysqli_num_rows ($a) ) {
			echo '<div class="info">No Student Found</div>';
		} else {

		

			echo '<div style="background:#F1F1F1; margin:2px auto 0; width:580px; text-align:center;  padding:3px;" class="ar cussearch a s12">	
				<div id="tablewrapper">
					<div id="tableheader">
						<div class="search">
						<select id="columns" onchange="sorter.search(\'query\')"></select>
						<input type="text" id="query" onkeyup="sorter.search(\'query\')" class="itext" />
						</div>
					</div>
				</div>
			</div>';			
			echo '<table class="us tinytable" id="table" cellpadding="0" cellspacing="0"><thead>',
					'<th width="7%"><h3>S/N</h3></th>',
					'<th width="60%"><h3>FullNames</h3></th>',
					'<th><h3>Matric No</h3></th>';
				
					echo'<th class="nosort" style="border-right:1px solid #999"><h3>Actions</h3></th>',			
				 '</thead><tbody>';
			while( $r=mysqli_fetch_assoc($a) ) {
				
			
				

			
				echo '<tr>',
						'<td style="text-align:center">',++$c,'</td>',
						'<td><b>',$r['surname'],'</b>, ',trim($r['firstname'].' '.$r['othernames']),'</td>',
						'<td><a href="set__adv_5.php?s=',$r['std_id'],'&m=',$r['matric_no'],'">',$r['matric_no'],'</a></td>';
						
				
				echo'<td>';
				if ($_SESSION['trans_right'] != '1') {
					echo '<a href="action.php?',$_SERVER['QUERY_STRING'],'&sid=',$r['std_id'],'&lid=',$r['std_logid'],'&comm=delete" onClick="return confirmLink(this, \'Delete This Student.\')">Delete</a><span class="i">|';
				}
				echo '</span><a href="editstudent.php?',$_SERVER['QUERY_STRING'],'&sid=',$r['std_id'],'">Edit</a></td>',
					 '</tr>';
				
			}

			echo'<tr><td colspan="4">';
						/* pagination botton   */

if($currentpage > 1){
	 // get previous page num
	 $prevpage=$currentpage - 1;
	 // show < link to go back to page 1
	 
     echo"<a   
	 href='{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}&currentpage=$prevpage'><input type='button' value='PREVIOUS' class='btn btn-success'/>
	 </a>";
 
	}
     
      echo"</div> <div class='col-xs-6'>";
	  if($currentpage){
	 // get next page
	 $nextpage= $currentpage + 1;
	 //echo forward link for next page
	 echo"
	 
	";
	  echo"<a 
	  href='{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}&currentpage=$nextpage'><input type='button' value='NEXT' class='btn btn-success'/></a>";
	  
}
echo'</td><tr/>';
/*   end of pagination botton  */

			echo '</tbody></table>';


?>			
	<script type="text/javascript" src="js/packed.js"></script>
	<script type="text/javascript">
	var sorter = new TINY.table.sorter('sorter','table',{
		headclass:'head',
		ascclass:'sort',
		descclass:'sort2',
		evenclass:'evenrow',
		oddclass:'oddrow',
		evenselclass:'evenselected',
		oddselclass:'oddselected',
		paginate:true,
		size:500,
		colddid:'columns',
		currentid:'',
		totalid:'',
		startingrecid:'',
		endingrecid:'',
		totalrecid:'',
		hoverid:'',
		pageddid:'',
		navid:'',
		sortcolumn:0,
		init:true
	});
  </script>        
			
<?php
		}
		
	endif;
?>
  
</form>
  <!-- content ends here -->
  
<?php

	require_once './json.php';
	
	$json = new JSON_obj;
	
	$l_field = mysqli_query( $GLOBALS['connect'], 'SELECT `do_id`, `dept_id`, `programme_option`, `duration`, `prog_id` FROM `dept_options`');
	$sunny = array();
	while( $a=mysqli_fetch_assoc($l_field) ) {
		$sunny[$a['dept_id']][] = array('idi'=>$a['do_id'],'namey'=>$a['programme_option']);
	}
	$dump = $json->encode($sunny);
	
?>
<script type="text/javascript" defer="defer">

	var fieldofstudy = <?php echo $dump ?>;
	
	function load_field( str ) {
		
		var pos,did,dump,to_fill,first_element;
		
		pos = str.value.indexOf(',');
		did = str.value.substr(0, pos);
		
		dump = eval( fieldofstudy );
		
		fill_count = dump[did].length;
		to_fill = xid("field");
		
		for( var x = to_fill.options.length - 1; x >= 0; x-- ){
			to_fill.remove(x);
		}
		
		first_element = document.createElement('option');
		first_element.text = 'Select Field';
		first_element.value = 'true';
		to_fill.options.add(first_element);
				
		for( var i=0; i<fill_count; i++ ) {
			
			var a = dump[did][i];
			var b = surface_key( a );
			
			var create_clone = first_element.cloneNode(true);
			create_clone.text = dump[did][i]['namey'];
			create_clone.value = dump[did][i]['idi'];
			to_fill.options.add(create_clone);
	
		}		
	}
function surface_key( obj ) {
	for( key in obj )
		return key;
}
function xid(id) {
	return document.getElementById(id);
}
</script>
  
<?php require_once 'inc/footer.php'; ?>
<script type="text/javascript" src="js/get_session_sandwich.js"></script>