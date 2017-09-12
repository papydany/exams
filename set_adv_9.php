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
.block{ display:block; overflow:hidden;}
.bb{ font-family:"Segoe UI", "Myriad Pro", Tahoma; font-weight:700; font-size:11px}
.dd{ border:1px solid #999; width:385px; margin:10px 10px 0; height:500px; overflow-y:auto;}
</style>


<form action="adv_9.php" method="post" autocomplete="off" onsubmit="return chk(this);" name="register">


<?php
	if( isset($_GET['i']) ) {
		switch( $_GET['i']) {
			case 1:
				echo '<div class="info">Courses Successfully Registered</div>';
			break;
			default:
			break;
		}
	}
?>
<div style=" width:445px;margin:0 auto; background:#CCC" class="block">
<table border="0" cellpadding="0" cellspacing="0" >
  <tr>  
    <td width="" class="td"><p>Next Level</p>
      <select name="n_lv">
      <option value="">Select</option>
		<?php
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level` WHERE programme_id = '.$_SESSION['myprogramme_id'].' ORDER BY level_name');
        while( $l=mysqli_fetch_assoc($ac) ) {
			echo '<option value=',$l['level_id'],'>';
			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {
				echo $l['level_name'].' - DIPLOMA';
			} elseif( $l['level_id'] > 12 ) {
				echo $l['level_name'];
			} else
				echo $l['level_name'];

			echo '</option>';
        }
        mysqli_free_result($ac);
        ?>
      </select></td>

    <td width="" class="td"><p>Year / Session</p><label for="select2"></label>
      <select name="n_sess">
 		<?php 

            for ($year = (date('Y')-1); $year >= 1998; $year--) {
				switch( $_SESSION['myprogramme_id'] ) {
					case 7: case '7':
						echo "<option value='$year'>$year</option>\n";
					break;
					default:
						$yearnext =$year+1;
						echo "<option value='$year'>$year/$yearnext</option>\n";
					break;
				}

            }			
        ?>        
      </select></td>
      
    <td width="" class="td" style="padding-right:20px"><p>Season</p><label for="select2"></label>
      <select name="season" >
      <option value="NORMAL">NORMAL</option>
      <option value="VACATION">VACATION</option>
      </select></td>
            
  </tr>
</table>
</div>



<table border="0" cellpadding="0" cellspacing="0" style="background:#F6f6f6;margin:0 auto; font-size:11px;">
  <tr> 
        
      <td width="" class="td"><p>Students Level</p>
      <select id="sl" name="c_lv">
      <option value="">Select</option>
		<?php
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level` WHERE programme_id = '.$_SESSION['myprogramme_id'].' ORDER BY level_name');
        while( $l=mysqli_fetch_assoc($ac) ) {
			echo '<option value=',$l['level_id'],'>';
			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {
				echo $l['level_name'].' - DIPLOMA';
			} elseif( $l['level_id'] > 12 ) {
				echo 'Contact '.substr($l['level_name'],0,1);
			} else
				echo $l['level_name'];

			echo '</option>';
        }
        mysqli_free_result($ac);
        ?>
      </select></td>

    <td width="" class="td"><p>Year / Session</p><label for="select2"></label>
      <select name="c_sess" id="yearsession">
		<?php

            for ($year = (date('Y')-1); $year >= 1998; $year--) {
				switch( $_SESSION['myprogramme_id'] ) {
					case 7: case '7':
						echo "<option value='$year'>$year</option>\n";
					break;
					default:
						$yearnext =$year+1;
						echo "<option value='$year'>$year/$yearnext</option>\n";
					break;
				}

            }			
        ?>        
      </select></td>
      
      
    <td width="" class="td" style="padding-right:20px"><p>Course Of Study</p><label for="select2"></label>   
      <select id="sd" name="fos">
      	<option value="">Select</option>
			<?php
			$fos = load_fos( $_SESSION['myusername'] );
			foreach( $fos as $r ) {
				echo '<option value="',$r['do_id'],'">',$r['programme_option'],'</option>';
			}

            ?>
      </select></td>


      

  </tr>
</table>


<div id="s" style=" width:890px;margin:0 auto; text-align:center;" class="block">
<input type="submit" value="+ Register Take Result" name="submit" style="margin:10px; font-family:'Segoe UI', 'Myriad Pro', Tahoma" />
</div>
  
</form>
  <!-- content ends here -->
<script type="text/javascript">

function chk(m) {

	if(m.fos.value == '' ){
		alert('Please Fill The Course Of Study');
		return false;
	}
		return true;
	
}

function xid(id) {
	return document.getElementById(id);
}



</script>
<?php require_once 'inc/footer.php'; ?>