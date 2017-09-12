<?php 

	require_once 'inc/header.php';

    require_once '../config.php';

?>





<!-- content starts here -->

<style>

body{ font-family:"Segoe UI", Tahoma; font-size:12px;}

select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}

input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}

.td{ padding:10px 3px 10px 20px;}

.field tr{ margin-bottom:10px; display:block; overflow:hidden }

.field td{ padding-right:20px}

.us { width:732px; border:1px solid #F1F1f1;}

.us thead{ background:#555; color:#FFF;}

.us thead th, .us tbody td{ border-bottom:1px solid #EEE; padding:5px; text-align:left; border-right:1px solid #EEE;}
.us a{ color:#06C; text-decoration:none;}
.i{

  margin:0 3px;

  font-style:normal;

  color:#bbb

}

</style>



<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" autocomplete="off">

<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1">

  <tr>

 

    <td width="" class="td"><p>Year / Session</p><label for="select2"></label>

      <select name="ysess">

		<?php 

            for ($year = (date('Y')-1); $year >= 1998; $year--) { $yearnext =$year+1;

				if( $_GET['ysess'] == $year ) {

					echo "<option value='$year' selected=\"selected\">$year/$yearnext</option>\n";

				} else {

                	echo "<option value='$year'>$year/$yearnext</option>\n";

				}

            } 

        ?> 

      </select></td>



	  

    <td width="" class="td"><p>Level</p>

      <select name="level" id="select">

      <option value="">Select</option>

		<?php

        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level`');

		

        while( $l=mysqli_fetch_assoc($ac) ) {

			if( $_GET['level'] == $l['level_id'] ) {

				echo '<option value=',$l['level_id'],' selected="selected">';

			} else {

				echo '<option value=',$l['level_id'],'>';

			}

			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {

				echo $l['level_name'].' - DIPLOMA';

			} elseif( $l['level_id'] > 12 ) {

				echo $l['level_name'].' - SANDWICH';

			} else

				echo $l['level_name'];



			echo '</option>';

        }

        mysqli_free_result($ac);

        ?>

      </select></td>



    <td width="" class="td"><p>Department</p><label for="select2"></label>

      <select name="department" id="select2" onchange="return load_field(this)">

      <option value="">Select</option>

	<?php

    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments` order by departments_name asc');

    while( $r=mysqli_fetch_assoc($l_dept) ) {

    	if( $_GET['department'] == $r['departments_id'].','.$r['fac_id'] ) {

			echo '<option value="',$r['departments_id'],',',$r['fac_id'],'" selected="selected">',$r['departments_name'],'</option>';

		} else {

			echo '<option value="',$r['departments_id'],',',$r['fac_id'],'">',$r['departments_name'],'</option>';

		}

    }

	mysqli_free_result($l_dept);

    ?>

      </select></td>



      

    <td width="" class="td"><p>Programme</p><label for="select"></label>

      <select name="programme" id="select">

      <option value="">Select</option>

	<?php

    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `programme_id`, `programme_name` FROM `programme`');

    while( $r=mysqli_fetch_assoc($l_dept) ) {

		if( $_GET['programme'] == $r['programme_id'] ) {

			echo '<option value="',$r['programme_id'],'" selected="selected">',$r['programme_name'],'</option>';

		} else {

    		echo '<option value="',$r['programme_id'],'">',$r['programme_name'],'</option>';

		}

    }

	mysqli_free_result($l_dept);

    ?>

      </select></td>



    <td width="" class="td" style="padding-right:20px"><p>Field Of Study</p><label for="select"></label>

      <select name="course" id="field">

      <option value="">Select</option>

      </select></td>

  </tr>

  <tr>

  <td colspan="5" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="View Students"></td>

  </tr>  

</table>

</form>







<?php

	if( isset($_SESSION['info']) ) {

		switch( $_SESSION['info'] ) {

			case 1:

			case 11:

				echo '<div style="padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">Action Completed</div>';				

			break;

			case 0:

			case 12:

				echo '<div style="padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">Please Try Again</div>';

			break;

			

		}

		unset( $_SESSION['info'] );

	}

	

	

	if( isset($_GET['submit']) && !empty($_GET['department']) && !empty($_GET['level']) && !empty($_GET['course']) ):

		

		list( $dept, $fac ) = explode(',', $_GET['department']);

		$a = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM students_profile WHERE stdprogramme_id = '.$_GET['programme'].' && stdfaculty_id='.$fac.' && stddepartment_id = '.$dept.' && stdcourse = '.$_GET['course'].' && std_custome1 = '.$_GET['level'].' && std_custome2 = "'.$_GET['ysess'].'"');

		

		if( 0==mysqli_num_rows($a) ) {

			echo '<div style="padding:10px; width:567px; background:#FFF8E7; border:1px solid #FFEAB7; color:#D79600">No Student Found</div>';

		} else {



			$c = 0;

			echo '<form action="transfer_fos.php" method="post">';

			echo '<table class="us" cellpadding="0" cellspacing="0"><thead>',

					'<th width="3%"><input name="" onclick="return checkUncheckAll(this)" type="checkbox" value="" /></th>',

					'<th width="7%">S/N</th>',

					'<th width="70%">NAMES</th>',

					'<th width="20%">MATRIC NO</th>',			

				 '</thead><tbody>';

			while( $r=mysqli_fetch_assoc($a) ) {

				$c++;

				echo '<tr>',

					'<td style="background:#EEE;"><input name="schk[]" type="checkbox" value="',$r['std_id'],'" /></td>',				

						'<td style="text-align:center">',$c,'</td>',

						'<td>',$r['surname'],' ',trim($r['firstname'].' '.$r['othernames']),'</td>',

						'<td style=" background:#F5F5F5; border-right:none; text-align:center;">',$r['matric_no'],'</td>',

					 '</tr>';

				

			}

			echo '</tbody></table>';

			?>

        

        

<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1">

  <tr>

 

    <td width="" class="td"><p>Year / Session</p><label for="select2"></label>

      <select name="nsess">

		<?php 

            for ($year = (date('Y')-1); $year >= 1998; $year--) { $yearnext =$year+1;

                echo "<option value='$year'>$year/$yearnext</option>\n";

            } 

        ?> 

      </select></td>



    <td width="" class="td"><p>Level</p>

      <select name="nlevel" id="select2">

		<?php

        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level`');

		

        while( $l=mysqli_fetch_assoc($ac) ) {

			

			echo '<option value=',$l['level_id'],'>';

			

			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {

				echo $l['level_name'].' - DIPLOMA';

			} elseif( $l['level_id'] > 12 ) {

				echo $l['level_name'].' - SANDWICH';

			} else

				echo $l['level_name'];



			echo '</option>';

        }

        mysqli_free_result($ac);

        ?>

      </select></td>



	  

    <td width="" class="td"><p>Department</p><label for="select2"></label>

      <select name="ndepartment" id="select2" onchange="return load_field(this,2)">

	<option value="">Select</option>

	<?php

    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments` order by departments_name asc');

    while( $r=mysqli_fetch_assoc($l_dept) ) {

    	echo '<option value="',$r['departments_id'],',',$r['fac_id'],'">',$r['departments_name'],'</option>';

    }

	mysqli_free_result($l_dept);

    ?>

      </select></td>



      

    <td width="" class="td"><p>Programme</p><label for="select"></label>

      <select name="nprogramme" id="select">

      <option value="">Select</option>

	<?php

    $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `programme_id`, `programme_name` FROM `programme`');

    while( $r=mysqli_fetch_assoc($l_dept) ) {

    	echo '<option value="',$r['programme_id'],'">',$r['programme_name'],'</option>';

    }

	mysqli_free_result($l_dept);

    ?>

      </select></td>





    <td width="" class="td" style="padding-right:20px"><p>Field Of Study</p><label for="select"></label>

      <select name="ncourse" id="field2">

      </select></td>

  </tr>

  <tr>

  <td colspan="5" style="text-align:center; padding:10px;"><input name="submit" type="submit" value="Transfer Students"></td>

  </tr>  

</table>

        

            

            <?php

			

			

			echo '</form>';

		}

		

	endif;

?>

  



  <!-- content ends here -->

  

<?php



	require_once '../json.php';

	

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

	

	function load_field( str, field ) {

		

		var pos,did,dump,to_fill,first_element;

		

		pos = str.value.indexOf(',');

		did = str.value.substr(0, pos);

		

		dump = eval( fieldofstudy );

		

		fill_count = dump[did].length;

		to_fill = field == '2' ? xid("field2") : xid('field');

		

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

function checkUncheckAll(theElement) {

     var theForm = theElement.form, z = 0;

	 for(z=0; z<theForm.length;z++){

      if(theForm[z].type == 'checkbox'){

	  theForm[z].checked = theElement.checked;

	  }

     }

    }
</script>

  

<?php require_once 'inc/footer.php'; ?>