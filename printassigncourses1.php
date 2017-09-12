<?php session_start();
require_once 'config.php';
  require_once 'include_report.php';
  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> COURSES ASSIGN TO LECTURER</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<base target="_blank">
</head>
<body>
  <style type="text/css">
@media print {
  .www{width: 500;float: left;}
  .ww{width: 500;float: right;}
}
</style>
  <div class="row">
<div class="col-sm-10 col-sm-offset-1">
<table  class="table table-bordered">
<tr><td>
<p class="text-center" style="font-size:16px; font-weight:600;">UNIVERSITY OF CALABAR</p>
    <p class="text-center" style="font-size:14px; font-weight:600;">CALABAR</p>
    <p class="text-center" style="font-size:12px; font-weight:600;"> COURSES ASSIGNED TO LECTURER</p>
    <div class="col-sm-6 www">
    <?php
    echo'<p>FACULTY: ',G_faculty($_SESSION['myfaculty_id']),'</p>
      <p>DEPARTMENT: ',G_department($_SESSION['mydepartment_id']),'</p>';
      
      ?>
      </div>
  <div class="col-sm-6 ww">
      <p> <?php echo $_SESSION['myprogramme_id'] == 7 ? ' Contact '.substr(GetlLevel($_GET['level']),0,1) : ' Level '.GetlLevel($_GET['level']); ?> </u></p>
      <p>
  <?php
    
    echo "SESSION :";
    echo $_SESSION['myprogramme_id'] == 7 ? " ".$_GET['ysess'] : " ".$_GET['ysess'].'/'.($_GET['ysess'] + 1); ?> 
    </p>
        

    </div>

    </td></tr>
 
  
  
</table>
<?php
  if( isset($_GET['submit']) && !empty($_GET['department']) && !empty($_GET['level']) && !empty($_GET['course']) && !empty($_GET['ysess']) ):
  
    list( $dept, $fac ) = explode(',', $_GET['department']);
    $query='SELECT DISTINCT id,thecourse_id, lecturer_id FROM assign_courses  WHERE year = "'.$_GET['ysess'].'" && programme_id = '.$_GET['programme'].' && fac_id='.$fac.' && dept_id = '.$dept.' && fos = '.$_GET['course'].' && level = '.$_GET['level'];
    //echo $query;
    $a = mysqli_query($GLOBALS['connect'], $query) or die(mysqli_error($GLOBALS['connect']));
    if( 0==mysqli_num_rows ($a) ) {
      echo '<div class="info">No Assign Courses Found</div>';
    } else {
      
     
      $c = 0;

      ?>
<table class="table table-striped table-bordered">
<tr>
<th>Sn</th>
<th>Lecturer Name</th>
<th>course code</th>
<th>Semester</th>

</tr>
      <?php
      while ($row = mysqli_fetch_assoc($a)) {

        $query_lecturer ="SELECT * From exam_officers WHERE examofficer_id =".$row['lecturer_id'];

       $query_all_courses ='SELECT * From all_courses WHERE level_id ="'.$_GET['level'].'" && faculty_id = "'.$fac.'" && department_id = '.$dept.' && course_custom5 = "'.$_GET['ysess'].'" && course_custom2 = "'.$_GET['course'].'" && programme_id = "'.$_GET['programme'].'" && thecourse_id="'.$row['thecourse_id'].'"';

$a11 = mysqli_query( $GLOBALS['connect'],$query_lecturer)or die(mysqli_error($GLOBALS['connect']));
$a1 = mysqli_fetch_assoc($a11);


$a22 = mysqli_query( $GLOBALS['connect'],$query_all_courses)or die(mysqli_error($GLOBALS['connect']));
$a2 = mysqli_fetch_assoc($a22);

$id = $row['id'];
echo'<td>',++$c,'</td>',
    '<td>',strtoupper($a1['eo_surname']." ".$a1['eo_firstname']." ".$a1['eo_othernames']),'</td>',
    '<td>',$a2['course_code'],'</td>',
    '<td>',$a2['course_semester'],'</td>',
    
  
    '<tr>'; 



   
      }
      echo'</table></div></div>';
     
    }
    
  endif;
  
?>
</body>
</html>