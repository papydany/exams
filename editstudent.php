<?php 
	require_once 'inc/header.php';
    require_once 'config.php';
?>


<!-- content starts here -->
<style>
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
</style>
<?php
if( isset($_GET['sid']) ):
?>
<form action="action.php?<?php echo $_SERVER['QUERY_STRING'] ?>&comm=update" method="post" autocomplete="off">
  
<table width="880" border="0" cellpadding="0" cellspacing="0" style="padding:20px; margin:0 auto;" class="field">
<?php
$l = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM students_profile WHERE std_id = '.$_GET['sid'].' LIMIT 1');
$row = mysqli_fetch_assoc($l);
mysqli_free_result($l);

	?>
  <tr>
    <td><span>Surname</span><p><input type="text" value="<?php echo $row['surname'] ?>" name="sn[0]" /></p></td>
    <td><span>Firstname</span><p><input type="text" value="<?php echo $row['firstname'] ?>" name="fn[0]" /></p></td>
    <td><span>Othernames</span><p><input type="text" value="<?php echo $row['othernames'] ?>" name="on[0]" /></p></td>
    <td><span>Matric No</span><p><input type="text" value="<?php echo $row['matric_no'] ?>" name="mn[0]" />
    	                         <input type="hidden" value="<?php echo $row['std_id'] ?>" name="sp[0]" />
                                 <input type="hidden" value="<?php echo $row['std_logid'] ?>" name="lg[0]" /></p>
    </td>
  </tr>
   <tr>
    <td><span>Male/Female</span><p><select name="gender[0]" >
    		<option value="FEMALE" <?php if ('female' == strtolower($row['gender'])) { echo 'selected="selected"';}?> >Female</option>
            <option value="MALE" <?php if ('male' == strtolower($row['gender'])) { echo 'selected="selected"';}?> >Male</option>
        </select></p></td>
    <td><span>Place of Birth</span><p><input type="text" value="<?php echo $row['place_of_birth'] ?>" name="pob[0]" /></p></td>
    <td><span>Date of Birth [ yyyy-mm-dd ]</span><p><input type="text" value="<?php echo $row['birthdate'] ?>" name="dob[0]" /></p></td>
    <td><span>Marital Status</span><p><input type="text" value="<?php echo $row['marital_status'] ?>" name="ms[0]" /></p></td>
  </tr>
  <tr>
    <td><span>Parmanent Address</span><p><input type="text" value="<?php echo $row['contact_address'] ?>" name="pa[0]" /></p></td>
    <td><span>State of Origin</span><p><input type="text" value="<?php echo $row['state_of_origin'] ?>" name="soo[0]" /></p></td>
    <td><span>Division (LGA)</span><p><input type="text" value="<?php echo $row['local_gov'] ?>" name="lga[0]" /></p></td>
    <td><span>Parent's or Guardion's Name</span><p><input type="text" value="<?php echo $row['parents_name'] ?>" name="pn[0]" /></p></td>
  </tr>
   </tr>
  </tr>
   <tr>
    <td><span>Last Institution Attended</span><p><input type="text" value="<?php echo $row['last_institution'] ?>" name="lia[0]" /></p></td>
    <td><span>Date of Graduation [ yyyy-mm-dd ]</span><p><input type="text" value="<?php echo $row['date_of_graduation'] ?>" name="dog[0]" /></p></td>
    <td><span>Major</span><p><input type="text" value="<?php echo $row['major'] ?>" name="major[0]" /></p></td>
    <td><span>Minor</span><p><input type="text" value="<?php echo $row['minor'] ?>" name="minor[0]" /></p></td>
  </tr>
   </tr>
   <tr><td colspan="4">&nbsp;</td></tr>
   <tr>
    <td><span>School Certificate [ eg WAEC/NECO ]</span><p><input type="text" value="<?php echo $row['school_cert'] ?>" name="sc[0]" /></p></td>
    <td><span>Certificate Date [ eg JUNE, 2001 ]</span><p><input type="text" value="<?php echo $row['school_cert_yr'] ?>" name="cd[0]" /></p></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><span>Enter Subjects</span><p><input type="text" value="<?php echo $subject[0] ?>" name="subj[0]" /></p></td>
    <td><span>Select Grade</span><p><select name="grad[0]" >
    		<option value="<?php echo $grade[0] ?>" selected="selected" ><?php echo $grade[0] ?></option>
            <option value="A1" >A1</option>
            <option value="B2" >B2</option>
            <option value="B3" >B3</option>
            <option value="C4" >C4</option>
            <option value="C5" >C5</option>
            <option value="C6" >C6</option>
            <option value="D7" >D7</option>
            <option value="E8" >E8</option>
            <option value="F9" >F9</option>
    	</select>
    </p></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><p><input type="text" value="<?php echo $subject[1] ?>" name="subj[1]" /></p></td>
    <td><p><select name="grad[1]" >
    		<option value="<?php echo $grade[1] ?>" selected="selected" ><?php echo $grade[1] ?></option>
            <option value="A1" >A1</option>
            <option value="B2" >B2</option>
            <option value="B3" >B3</option>
            <option value="C4" >C4</option>
            <option value="C5" >C5</option>
            <option value="C6" >C6</option>
            <option value="D7" >D7</option>
            <option value="E8" >E8</option>
            <option value="F9" >F9</option>
    	</select>
    </p></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><p><input type="text" value="<?php echo $subject[2] ?>" name="subj[2]" /></p></td>
    <td><p><select name="grad[2]" >
    		<option value="<?php echo $grade[2] ?>" selected="selected" ><?php echo $grade[2] ?></option>
            <option value="A1" >A1</option>
            <option value="B2" >B2</option>
            <option value="B3" >B3</option>
            <option value="C4" >C4</option>
            <option value="C5" >C5</option>
            <option value="C6" >C6</option>
            <option value="D7" >D7</option>
            <option value="E8" >E8</option>
            <option value="F9" >F9</option>
    	</select>
    </p></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><p><input type="text" value="<?php echo $subject[3] ?>" name="subj[3]" /></p></td>
    <td><p><select name="grad[3]" >
    		<option value="<?php echo $grade[3] ?>" selected="selected" ><?php echo $grade[3] ?></option>
            <option value="A1" >A1</option>
            <option value="B2" >B2</option>
            <option value="B3" >B3</option>
            <option value="C4" >C4</option>
            <option value="C5" >C5</option>
            <option value="C6" >C6</option>
            <option value="D7" >D7</option>
            <option value="E8" >E8</option>
            <option value="F9" >F9</option>
    	</select>
    </p></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><p><input type="text" value="<?php echo $subject[4] ?>" name="subj[4]" /></p></td>
    <td><p><select name="grad[4]" >
    		<option value="<?php echo $grade[4] ?>" selected="selected" ><?php echo $grade[4] ?></option>
            <option value="A1" >A1</option>
            <option value="B2" >B2</option>
            <option value="B3" >B3</option>
            <option value="C4" >C4</option>
            <option value="C5" >C5</option>
            <option value="C6" >C6</option>
            <option value="D7" >D7</option>
            <option value="E8" >E8</option>
            <option value="F9" >F9</option>
    	</select>
    </p></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><p><input type="text" value="<?php echo $subject[5] ?>" name="subj[5]" /></p></td>
    <td><p><select name="grad[5]" >
    		<option value="<?php echo $grade[5] ?>" selected="selected" ><?php echo $grade[5] ?></option>
            <option value="A1" >A1</option>
            <option value="B2" >B2</option>
            <option value="B3" >B3</option>
            <option value="C4" >C4</option>
            <option value="C5" >C5</option>
            <option value="C6" >C6</option>
            <option value="D7" >D7</option>
            <option value="E8" >E8</option>
            <option value="F9" >F9</option>
    	</select>
    </p></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><p><input type="text" value="<?php echo $subject[6] ?>" name="subj[6]" /></p></td>
    <td><p><select name="grad[6]" >
    		<option value="<?php echo $grade[6] ?>" selected="selected" ><?php echo $grade[6] ?></option>
            <option value="A1" >A1</option>
            <option value="B2" >B2</option>
            <option value="B3" >B3</option>
            <option value="C4" >C4</option>
            <option value="C5" >C5</option>
            <option value="C6" >C6</option>
            <option value="D7" >D7</option>
            <option value="E8" >E8</option>
            <option value="F9" >F9</option>
    	</select>
    </p></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><p><input type="text" value="<?php echo $subject[7] ?>" name="subj[7]" /></p></td>
    <td><p><select name="grad[7]" >
    		<option value="<?php echo $grade[7] ?>" selected="selected" ><?php echo $grade[7] ?></option>
            <option value="A1" >A1</option>
            <option value="B2" >B2</option>
            <option value="B3" >B3</option>
            <option value="C4" >C4</option>
            <option value="C5" >C5</option>
            <option value="C6" >C6</option>
            <option value="D7" >D7</option>
            <option value="E8" >E8</option>
            <option value="F9" >F9</option>
    	</select>
    </p></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><p><input type="text" value="<?php echo $subject[8] ?>" name="subj[8]" /></p></td>
    <td><p><select name="grad[8]" >
    		<option value="<?php echo $grade[8] ?>" selected="selected" ><?php echo $grade[8] ?></option>
            <option value="A1" >A1</option>
            <option value="B2" >B2</option>
            <option value="B3" >B3</option>
            <option value="C4" >C4</option>
            <option value="C5" >C5</option>
            <option value="C6" >C6</option>
            <option value="D7" >D7</option>
            <option value="E8" >E8</option>
            <option value="F9" >F9</option>
    	</select>
    </p></td>
    <td></td>
    <td></td>
  </tr>
  <?php  
 
//}
?>
  <tr>
  <td colspan="3" ><input name="" type="submit" value="Save Changes"></td>
  </tr>
</table>
</form>
<?php  
endif;
?> 
  <!-- content ends here -->

  
<?php require_once 'inc/footer.php'; ?>