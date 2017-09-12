<?php

$mymstatus = $_SESSION['mymstatus'];
$bgcolor1 = "#4467B0";
$bgcolor2 = "#EAEAEA";

?>

<ul class="MM">	

<?php
if ($mymstatus == 5) {

	function unread_msgs_count() {
		$_ = mysqli_query( $GLOBALS['connect'], 'SELECT count(1) as unread FROM messaging WHERE messaging.m_to = "ADMIN" and messaging.unread = "Y"');
		$d = mysqli_fetch_assoc($_);
		mysqli_free_result($_);
		if( $d['unread'] == 0 ) {
			return '';
		} else {
			return '<span class="unread">'.$d['unread'].'</span>';
		}
	}
	
	$unread = unread_msgs_count();

?>


<li class="kn">Manage Result</li>
    <li><a href="../dump" target="_blank">Back Up(unicalnu_examx1)</a></li>
   <!-- <li><a href="backup/manage.php" target="_blank">Manage/Backup Result</a></li>
    <li><a href="backup/database.php" target="_blank">Backup Database</a></li>-->

	<li><a href="publish_result.php">Publish Result</a></li>




<li class="kn">Manage Accounts</li>

    <li><a href="createaccount_transcript.php" >Create Transcript Officer Account</a></li> 
    <li><a href="createaccount.php" >Create Officer Account</a></li> 
    <li><a href="add_lecturer.php" >Create Lecturer Account</a></li> 
    <li><a href="createdegree.php">Create Class Of Degree</a></li> 
    <li><a href="viewaccounts.php" >View Accounts</a></li>
    <li><a href="edit_rights.php">Modify Edit Rights</a></li>
   <li><a href="edit_users.php">Modify Users Rights</a></li>
    <?php /* <li><a href="edit_transcript.php">Modify Transcript Rights</a></li>	        
	*/ ?>
<li class="kn">Help Desk</li>
    
    <li><a href="#" onclick="return adminewmsg();">New Message</a></li>
    <li><a href="msg.php">View Message <?php echo $unread; ?></a></li>


<li class="kn">Manage Students</li>

    <li><a href="viewregstudent.php">Registered Students</a></li>
    <li><a href="results_spreadsheetx.php">View Report Sheet</a></li>
    <li><p class="sepm"></p></li>
    <li><a href="suspend_academic.php">Suspension Of Academic</a></li>
    <li><p class="sepm"></p></li>
    <li><a href="edit_std_rec.php">Search For Student</a></li>
    <li><a href="search_std_rec.php"> Last Option Search For Student</a></li>

<li class="kn">Manage Options</li>
	<li><a href="add_dept.php">Add Department</a></li>
    <li><a href="edit_dept.php">Edit Department</a></li>
    <li><a href="add_faculty.php">Add Faculty</a></li>
    <li><a href="cFOS.php">Field Of Study</a></li>
    <li><a href="eFOS.php">Edit Field Of Study</a></li>
    <li><p class="sepm"></p></li>
    <li><a href="transfer_stds.php">Transfer Students</a></li>
    <li><a href="transfer_courses.php">Transfer Courses</a></li>
    <li><p class="sepm"></p></li>
	<li><a href="transfer_stdreg.php">Transfer Registration</a></li>
	<li><a href="../mycourses.php" target="_blank">Mycourse link</a></li>

<?php
}
?>
</ul>