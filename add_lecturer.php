<?php
  require_once 'inc/header.php';
    require_once 'config.php';
?>

<style>

select{ width:120px; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
</style>


<form action="add_lecturer_query.php" method="post" autocomplete="off" onsubmit="return valid(this);">
<?php

echo '<input type="hidden" name="certificate" value="',basename($_SERVER['SCRIPT_FILENAME']),'"/>';

  if( isset($_GET['i']) ) {
    switch( $_GET['i']) {
      case 1:
        echo '<div class="info">Lecturer Successfully Added</div>';
      break;
       case 0:
        echo '<div class="info">lecturers Failed To Be Added</div>';
      break;
      default:
      break;
    }
  }
?>
<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1; margin:2px auto;">
  <tr>
 
    <td width="" class="td"><p>Faculty</p><label for="select2"></label>
      <?php
      echo '<input name="faculty" type="hidden" value="',$_SESSION['myfaculty_id'],'">';
    ?>
      <select disabled>
    <?php
        $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `faculties_name` FROM `faculties` WHERE `faculties_id` = '.$_SESSION['myfaculty_id'].'');
        $r=mysqli_fetch_assoc($l_dept);
        echo '<option>',$r['faculties_name'],'</option>';
        mysqli_free_result($l_dept);
        ?>
      </select>
    </td>      

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
      </tr>
</table>
  
<table width="1200" border="0" cellpadding="0" cellspacing="0" style="padding:20px 0; margin:0 auto" class="field">
<?php
for($i=1; $i<21; $i++) {
  ?>
  <tr>
    <td><span>Title</span>
    <p><select class="form-control" name="title[<?php echo $i ?>]" id="title<?php echo $i?>">
            <option value="">Pick a Title</option>
           <option value="Mr">Mr</option>
                                   <option value="Mrs">Mrs</option>
                                   <option value="Miss">Miss</option>
                                   <option value="Dr">Dr</option>
                                     <option value="Dr(Mrs)">Dr(Mrs)</option>
                                   <option value="Prof">Prof</option>
                                  <option value="Prof(Mrs)">Prof(Mrs)</option>
                                  <option value="Assoc Prof">Assoc Prof</option>
                                   <option value="Rev(Dr)">Rev(Dr)</option>
                                   <option value="Rev(Sis)">Rev(Sis)</option>
                                   <option value="Rev(prof)">Rev(Prof)</option>
                                   <option value="Barr">Barr</option>
                                   <option value="Barr Mrs">Barr Mrs</option>
          </select>
          </p></td>
    <td><span>Surname</span>
    <p><input type="text" class="form-control" name="surname[<?php echo $i ?>]" id="surname" value="">
    </p></td>
    <td><span>Firstname</span>
    <p><input class="form-control" type="text" name="firstname[<?php echo $i ?>]" id="firstname" value="">
    </p></td>
    <td><span>Othernames</span>
    <p><input class="form-control" type="text" name="othernames[<?php echo $i ?>]" id="othernames" value="">
    </p></td>
    <td><span>Username</span>
    <p><input class="form-control" type="text" name="username[<?php echo $i ?>]" id="username"></p>
    </td>
      <td><span>Password</span>
      <p><input class="form-control" type="text" name="password[<?php echo $i ?>]" id="password"></p></td>
  </tr>
  <?php  
}
?>
  <tr>
  <td colspan="3" ><input name="" class="btn btn-success" type="submit" value="Submit"></td>
  </tr>
</table>
</form>




<?php
require( "inc/footer.php" );
?>