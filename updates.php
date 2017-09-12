<?php

	function return_grade( $n ) {
		
		if( empty($n) ) {
			$return['grade'] = '';
			$return['point'] = '';
			return $return;	
		}
		$return = array();
		$n = floor($n);

		if ($n >= 70) {
		  $return['grade'] = "A";
		  $return['point'] = "5.00";
		} elseif (($n >= 60) and ($n < 70)) {
		  $return['grade'] = "B";
		  $return['point'] = "4.00";
		} elseif (($n >= 50) and ($n < 60)) {
		  $return['grade'] = "C";
		  $return['point'] = "3.00";
		} elseif (($n >= 45) and ($n < 50)) {
		  $return['grade'] = "D";
		  $return['point'] = "2.00";
		} elseif (($n >= 40) and ($n < 45)) {
		  $return['grade'] = "E";
		  $return['point'] = "1.00";
		} elseif (($n > 0) and ($n < 40)) {
		  $return['grade'] = "F";
		  $return['point'] = "0.00";
		} elseif( $n==0 ) {
		  $return['grade'] = "N";
		  $return['point'] = "0";
		}
		
		return $return;
		
	}

?>