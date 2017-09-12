<?php include_once './config.php';
if(isset($_POST["data"]) && $_POST["data"] !="")
{
	$id = $_POST["data"];
?>
<select name='yearsession' id='yearsession'>
                              <option value="" selected>Choose A Session</option>
<?php
             for ($year = (date('Y')-1); $year >= 1998; $year--) {

				switch($id) {
					case 1: case '1':
					$ap_year =$year."-AP";
						echo "<option value='$ap_year'>$ap_year</option>\n";
					break;
					default:
					$ag_year =$year."-AG";
					echo "<option value='$ag_year '>$ag_year</option>\n";
					break;
				}

            }
?>
</select>
<?php
}

?>