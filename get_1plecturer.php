<?php include_once './config.php';
if(isset($_POST["data"]) && $_POST["data"] !="")
{
	$id = $_POST["data"];
?>
<select name='s_session' id='s_session'>
                              <option value="" selected>Choose A Session</option>
<?php
             for ($year = (date('Y')-1); $year >= 1998; $year--) {

				switch($id) {
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
</select>
<?php
}

?>	