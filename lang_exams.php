<?php
  //general_main
  $website_name = "The University of Calabar";
  $footer = "The University of Calabar";
  $website_cooperateemail = "info@unicalexams.net.ng";
  
  //general_admin
  $admin_footer = "
<table width=\"100%\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\" class=\"border\">
  <tr>
    <td align=\"center\" bgcolor=\"#508AC5\">powered by <a href=\"http://info@nucdb.com\" title=\"Unical database\" target=\"_blank\">UNICAL Database</a></td>
  </tr>
</table>";
  
  $gen_year = 2001;
  
  //administrative_admin
  
  $exams_welcome_note = "<div style='background:#E1FFE1; margin:0 15px; border:1px solid #B7FFB7; padding:10px 30px; font-size:13px; color:green'>Welcome to University of Calabar's Exams and Records Environment</div>";
  $exams_name_wd = "Examinations And Records Admin";
  $exams_style_wd = "<link href=\"styles/styles.css\" rel=\"stylesheet\" type=\"text/css\">";
  $exam_sessionid_wd = 3;
  $exams_invalid_wd = "Invalid Username and/or Password";
  $exams_user_wd = "Username";
  $exams_pass_wd = "Password";
  $exams_valid_wd = "<b style='font-size:17px'>Examinations Officer's Login Page</b>";
  $exams_homelink_wd = "<a href=\"../home.php\">Home Page</a> OR <a href=\"examofficer_register.php\">Examinations Officer's Register</a>";
  $exams_homelink_wd2 = "<a href=\"../home.php\">Home Page</a>";
  $exams_officers_reg_wd = "Examinations Officer's Registration";
  $exams_addcoures_wd = "Add Courses";
  $exams_editcoures_wd = "Edit Course";
  
  $exams_coursestatus_wd1 = "Core Course";
  $exams_coursestatus_wd2 = "Restricted Elective";
  $exams_coursestatus_wd3 = "Special Elective";
  
  $exams_coursesemester_wd1 = "First Semester";
  $exams_coursesemester_wd2 = "Second Semester";
  
  $exams_addprofile_wd = "Add Student's Profile";
  $exams_editprofile_wd = "Edit Student's Profile";
  
  //students_data
  
  $std_name_wd = "Student's Area";
  $fees_instruction = "Fees Instruction";
  
  //lecturers
  $lecture_homelink_wd2 = "<a href=\"../home.php\">Home Page</a>";
  $lecture_valid_wd = "<b><u>Lecturers' Login Page</u></b><br><br>You must be logged in to view this page";
  $lecture_invalid_wd = "Invalid Username and/or Password";
  $lecture_user_wd = "Username";
  $lecture_pass_wd = "Password";
  $lecture_name_wd = "Lecturers' Personalized Area";
  
  //schoolofficers_admin
  
  $exams_welcome_noteb = "Welcome";
  $exams_name_wdb = "School Officers' Admin";
  $exams_style_wdb = "<link href=\"styles/styles.css\" rel=\"stylesheet\" type=\"text/css\">";
  $exam_sessionid_wdb = 3;
  $exams_invalid_wdb = "Invalid Username and/or Password";
  $exams_user_wdb = "Username";
  $exams_pass_wdb = "Password";
  $exams_valid_wdb = "<b><u>School Officer's Login Page</u></b><br><br>You must be logged in to view this page";
  $exams_homelink_wdb = "<a href=\"../home.php\">Home Page</a> OR <a href=\"examofficer_register.php\">School Officer's Register</a>";
  $exams_homelink_wd2b = "<a href=\"../home.php\">Home Page</a>";
  $exams_officers_reg_wdb = "School Officer's Registration";
  $exams_addcoures_wdb = "Add Courses";
  $exams_editcoures_wdb = "Edit Course";
  
  $exams_coursestatus_wd1b = "Core Course";
  $exams_coursestatus_wd2b = "Restricted Elective";
  $exams_coursestatus_wd3b = "Special Elective";
  
  $exams_coursesemester_wd1b = "First Semester";
  $exams_coursesemester_wd2b = "Second Semester";
  
  $exams_addprofile_wdb = "Add Student's Profile";
  $exams_editprofile_wdb = "Edit Student's Profile";
  
  $rs = '';
  if ($rs == 1) {
      $std_type = "New Students";
      $std_typeid = "1";
      $rs = "1";
  } elseif ($rs == 2) {
      $std_type = "Returning Students";
      $std_typeid = "2";
      $rs = "2";
  } else {
      $std_type = "Application";
      $std_typeid = "0";
      $rs = "0";
  }
?>
