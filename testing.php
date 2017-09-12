<?php
include_once './config.php';
/*$v ="2004-AP";
$v2 ="2007-AP";
if($v2 > $v)
{
	//echo "hiii<br/>";
}

$vv =substr_replace($v,"",4);


//echo $vv;

echo $c=strtok($v,"-");
$c =$c-2;
$d =substr_replace($v,$c,0,4);
echo "<br/>".$d;*/
function dan($l,$s,$std_id)
{
 $sql = "SELECT cu FROM students_results WHERE level_id ='$l' && std_mark_custom2 ='".$s."' && std_id ='$s_id' && std_grade ='F'";

$cu=array();
$cu1=array();
$tcu ='';
$tcu1 ='';
	$r = mysqli_query($GLOBALS['connect'], $sql )or die (mysqli_error($GLOBALS['connect']));
	if(mysqli_num_rows($r) > 0){
while($row = mysqli_fetch_assoc( $r )){
 $cu [] = $row['cu'];
}
$tcu=array_sum($cu);
}

$sql1 = "SELECT cu FROM students_results_backup WHERE level_id ='$l' && std_mark_custom2 ='".$s."' && std_id ='$s_id' && std_grade ='F'";
$r1 = mysqli_query($GLOBALS['connect'], $sql1 )or die (mysqli_error($GLOBALS['connect']));
	if(mysqli_num_rows($r1) > 0){
while($row = mysqli_fetch_assoc( $r1 )){
 $cu1 [] = $row['cu'];
}
$tcu1=array_sum($cu1);
}
$c =$tcu + $tcu1;
return $c;
}
$c =dan($l,$s,$std_id);

echo $c;

?>