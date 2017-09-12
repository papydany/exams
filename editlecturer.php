<?php 
	require_once 'inc/header.php';
    require_once 'config.php';

 if( isset($_GET['id']) ) {
 	$id = $_GET['id'];
    $sql ="SELECT * FROM exam_officers WHERE examofficer_id=".$id;
   
   $query = mysqli_query( $GLOBALS['connect'],$sql) or die(mysqli_error($GLOBALS['connect']));
    $r=mysqli_fetch_assoc($query);
   $num =mysqli_num_rows($query);
   if($num > 0){
   
    ?>

        
<div class="row">
<div class="col-sm-10 col-sm-offset-1">
<form action="updatelecturer.php" method="post">

<table class="table table-bordered">

  <tr>
    <td><span>Title</span>
    <p><select class="form-control" name="title" required>
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
    <p><input type="text" class="form-control" name="surname" id="surname" value="<?php echo $r['eo_surname']; ?>" required>
    <input type="hidden"  name="id"  value="<?php echo $r['examofficer_id']; ?>">
    </p></td>
    <td><span>Firstname</span>
    <p><input class="form-control" type="text" name="firstname" id="firstname" value="<?php echo $r['eo_firstname']; ?>" required>
    </p></td>
    <td><span>Othernames</span>
    <p><input class="form-control" type="text" name="othernames" id="othernames" value="<?php echo $r['eo_othernames']; ?>">
    </p></td>
   
  </tr>

  <tr>
  <td colspan="4" ><input class="btn btn-success" type="submit" value="Submit"></td>
  </tr>
</table>
</form>

</div>

</div>



<?php }

}

require_once 'inc/footer.php'; ?>    