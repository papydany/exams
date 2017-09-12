<?php
$website_name1 = "The University of Calabar";
$website_name = "The University of Calabar";
$bk_website_name = 'The University of Calabar';
$bk_title = 'Welcome To';
$bk_developed = 'Site Admin developed by NUCDB';
$bk_bgcolor = '#FFCC99';
$bglinkscolor = '#FFFFFF';
$bglinkscolor1 = '#FFCC99';
$alt_admin_banner = "powered by NUCDB";
#footer
$site_developed = "&lt;&lt; developed and managed by <a href='http://info@nucdb.com' target='_blank' style='font-size: 10px; color: brown'>NUCDB</a> &gt;&gt;";
$copyright = "copyright &copy 2006."; 
$footer_link = " ";
$website_cooperateemail = "info@nucdb.edu.ng";

if ($rs == 1) {
$std_type = "New Students";
$std_typeid = "1";
$rs = "1";
}
elseif ($rs == 2) {
$std_type = "Returning Students";
$std_typeid = "2";
$rs = "2";
}
else {
$std_type = "Application";
$std_typeid = "0";;
$rs = "0";
}
?>
