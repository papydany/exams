<?php include_once './config.php';
       

if(isset($_POST["data"]) && $_POST["data"] !="")
{
	$id = $_POST["data"];
	$query = "SELECT * FROM level WHERE programme_id ='$id' ORDER BY programme_id,level_name";
$result = mysqli_query($GLOBALS['connect'], $query);
?>
<select name="s_level" id="s_level">
<option value=" ">--Choose Level---</option>
<?php
while($l = mysqli_fetch_assoc($result)){

$prog_id = $l['programme_id'];
			if( $prog_id == '1') {
				$level_name = $l['level_name'].' - DIPLOMA';
			} elseif( $prog_id == '2') {
				$level_name = $l['level_name'].' Degree';
			}elseif($prog_id == '10'){
				$level_name = $l['level_name'].' - Pre Degree';
				//$level_name = 'Contact '.substr($l['level_name'],0,1);
			}elseif($prog_id >= '3' && $prog_id <= 5){
				$level_name = $l['level_name'].' - Extension';;
			} elseif($prog_id == '7'){
				$level_name = $l['level_name'].' - Sandwich';
			}
$level_id = $l["level_id"];


echo "<option value=".$level_id.">$level_name</option>";

}
echo'</select>';


}

?>