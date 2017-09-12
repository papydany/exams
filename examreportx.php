<?php

  $yearsession = $_GET['yearsession'];
  $faculty_id = $_GET['faculty'];
  $department_id = $_GET['department'];
  $rtype = $_GET['rtype'];
  $s_level = $_GET['s_level'];
  $course = $_GET['course'];
  $programme = $_GET['programme'];
 
  $month = $_GET['month'];

  $spxx=$_GET['specialxx'];
  
  $spillcheck = substr($s_level, -1);
  if($programme == 7)

  {
  	switch ($rtype) {
      case '0':

      switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
				header("location:sandwich_probational_report.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&month=$month&course=$course&programme=$programme&special=final");
			break;
			default:
				header("location:sandwich_probational_report.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&month=$month&course=$course&programme=$programme");
			break;
		  }
     
	  break;

	  case '01':
		  
		  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
				header("location:prob_witdrawer.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&month=$month&course=$course&programme=$programme&special=final");
			break;
			default:
				header("location:prob_witdrawer.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&month=$month&course=$course&programme=$programme");
			break;
		  }
     break;

 case '11':
	  	 switch( $spillcheck ) {
          
             case 's':
          
           	header( "Refresh: 2; URL=sandwichreport.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&month=$month&special=spillover&course=$course&programme=$programme&state=1" );
			  break;
           
			
			  case 'x':
		
           	header( "Refresh: 2; URL=sandwichreport.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&month=$month&special=spillover&course=$course&programme=$programme&state=2" );
			  break;
     			  
			  case 'f':
		
             header( "Refresh: 2; URL=sandwichreport.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&month=$month&special=regular&course=$course&programme=$programme&spxx=$spxx");
			  break;
     
     	 
			  default:
			     
           case '7':
           	header( "Refresh: 2; URL=sandwichreport.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&month=$month&course=$course&programme=$programme");
			  break;
         	}
          
      break;

       case '2':
	  		switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
         	header( "Refresh: 2; URL=sandwich_correctional_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&month=$month&course=$course&programme=$programme&special=final");
      		break;
			default:
			header( "Refresh: 2; URL=sandwich_correctional_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&month=$month&course=$course&programme=$programme");
      		break;
			}
		break;				
      case '3':
	  	  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
          		header( "Refresh: 2; URL=omitted_sandwich_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&month=$month&course=$course&programme=$programme&special=final");
      		break;
			default:
	  		 	header( "Refresh: 2; URL=omitted_sandwich_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&month=$month&course=$course&programme=$programme");
      		break;
		  }
		break;

		  case '4':
		  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
				header( "Refresh: 2; URL=sandwich_vacation_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&month=$month&course=$course&programme=$programme&special=final");
			break;
			default:
				header( "Refresh: 2; URL=sandwich_vacation_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&month=$month&course=$course&programme=$programme");
			break;
		  }	  
          
      break;

  }
} // end of sandwish
 else{
  switch ($rtype) {
      case '0':
		  
		  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
				header("location:probational_report.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme&special=final");
			break;
			default:
				header("location:probational_report.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme");
			break;
		  }
     
	  break;
	  case '01':
		  
		  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
				header("location:prob_witdrawer.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme&special=final");
			break;
			default:
				header("location:prob_witdrawer.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme");
			break;
		  }
     break;
	  
      case '1':
	  case '11':
	  		
		  $seed = $rtype == 1 ? '4' : '4new';
          
          switch( $spillcheck ) {
          
             case 's':
           
			  	header( "Refresh: 2; URL=report$seed.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&special=spillover&course=$course&programme=$programme&state=1" );
			  break;
			  case 'x':
			
			  	header( "Refresh: 2; URL=report$seed.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&special=spillover&course=$course&programme=$programme&state=2" );
			  break;			  
			  case 'f':
		
     	         header( "Refresh: 2; URL=report$seed.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&special=regular&course=$course&programme=$programme&spxx=$spxx");
			  break;
			  default:
	
              	header( "Refresh: 2; URL=report$seed.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme");
			  break;
			
		  }
          
      break;
      case '2':
	  		switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
         	header( "Refresh: 2; URL=correctional_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme&special=final");
      		break;
			default:
			header( "Refresh: 2; URL=correctional_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme");
      		break;
			}
		break;				
      case '3':
	  	  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
          		header( "Refresh: 2; URL=omitted_undergraduate_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme&special=final");
      		break;
			default:
	  		 	header( "Refresh: 2; URL=omitted_undergraduate_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme");
      		break;
		  }
		break;
		case '33':
	  	  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
          		header( "Refresh: 2; URL=omitted_vacation_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme&special=final");
      		break;
			default:
	  		 	header( "Refresh: 2; URL=omitted_vacation_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme");
      		break;
		  }
		break;
      case '4':
		  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
				header( "Refresh: 2; URL=vacation_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme&special=final");
			break;
			default:
				header( "Refresh: 2; URL=vacation_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme");
			break;
		  }	  
          
      break;
	  case '5':
	  	header( "Refresh: 2; URL=f_report4.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme" );
	  break;
	  case '20':
	  	header( "Refresh: 2; URL=resit_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=" .$s_level. "&course=$course&programme=$programme" );
	  break;
	  case '21':
	  	header( "Refresh: 2; URL=correctional_report_diploma.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=" .$s_level. "&course=$course&programme=$programme" );
	  break;
	  case '22':
	  	header( "Refresh: 2; URL=omitted_diploma_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=" .$s_level. "&course=$course&programme=$programme" );
	  break;
	  case '23':
	  switch( $spillcheck ) {
			  case 's':
			  	header( "Refresh: 2; URL=welcomebackresult.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&special=spillover&course=$course&programme=$programme&state=1" );
			  break;
			  case 'x':
			  	header( "Refresh: 2; URL=welcomebackresult.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&special=spillover&course=$course&programme=$programme&state=2" );
			  break;			  
			  case 'f':
     	         header( "Refresh: 2; URL=welcomebackresult.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&special=regular&course=$course&programme=$programme&spxx=$spxx");
			  break;
			  default:
              	header( "Refresh: 2; URL=welcomebackresult.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme");
			  break;
		  }
	  	//header( "Refresh: 2; URL=welcomebackresult.php?yearsession=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=" .$s_level. "&course=$course&programme=$programme" );
	  break;
	  
	   case '25':
	  	header( "Refresh: 2; URL=report4new_itready.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=" .$s_level. "&course=$course&programme=$programme" );
	  break;
	  case '26':
	  	header( "Refresh: 2; URL=report4new_vacation.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=" .$s_level. "&course=$course&programme=$programme" );
	  break;
	   case '27':
	  	header( "Refresh: 2; URL=report4new_delayed.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=" .$s_level. "&course=$course&programme=$programme" );
	  break;
	  case '6':
		  
		  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
				header( "Refresh: 2; URL=diploma_report.php?s_session=$yearsession&s_level=" .$s_level. "&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme&special=final" );
			break;
			default:
				header( "Refresh: 2; URL=diploma_report.php?s_session=$yearsession&s_level=" .$s_level. "&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme" );
			break;
		  }
	  break;
	 
	  case '55':
	  switch( $spillcheck ) {
	  	 case 's':
	  header( "Refresh: 2; URL=selectstudentresult.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&special=spillover&course=$course&programme=$programme&state=1" );
			  break;
			  case 'x':
			
			  	header( "Refresh: 2; URL=selectstudentresult.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&special=spillover&course=$course&programme=$programme&state=2" );
			  break;			  
			  case 'f':
		
     	         header( "Refresh: 2; URL=selectstudentresult.php?s_session=$yearsession&s_level=".substr($s_level, 0, -1). "&faculty_id=$faculty_id&department_id=$department_id&special=regular&course=$course&programme=$programme&spxx=$spxx");
			  break;
			  default:
			header( "Refresh: 2; URL=selectstudentresult.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=".$s_level."&course=$course&programme=$programme" );
	  break;
	}
	 break;
	case '56':
		  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
				header( "Refresh: 2; URL=selectstudentvacation_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme&special=final");
			break;
			default:
				header( "Refresh: 2; URL=selectstudentvacation_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme");
			break;
		  }	

        break;
    case '57':
		  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
			header("location:selectstudentprobational_report.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme&special=final");
			break;
			default:
				header("location:selectstudentprobational_report.php?s_session=$yearsession&s_level=$s_level&faculty_id=$faculty_id&department_id=$department_id&course=$course&programme=$programme");
				
			break;
		  }	

        break;
        case '59':
		  switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
			header( "Refresh: 2; URL=selectstudentomitted_undergraduate_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme&special=final");
      		break;
			default:
	  		 	header( "Refresh: 2; URL=selectstudentomitted_undergraduate_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme");
				
			break;
		  }	

        break;    
   case '58':
	  		switch( $spillcheck ) {
			case 's':
			case 'x':
			case 'f':
         	header( "Refresh: 2; URL=selectstudentcorrectional_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme&special=final");
      		break;
			default:
			header( "Refresh: 2; URL=selectstudentcorrectional_report.php?s_session=$yearsession&faculty_id=$faculty_id&department_id=$department_id&s_level=$s_level&course=$course&programme=$programme");
      		break;
			}
		break;

  }
  }
  exit('<span style="font-family:tahoma; font-size:13px;">Loading Result Report Environment ..</span>');
  
  
?>