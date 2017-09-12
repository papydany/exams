<?php


class db {
	
	private $db;
	private static $instance;
	private $result;
	private $last_result;
	private $last_error;
	private $row_count;
	
	
	private function __construct() { }
	private function __clone(){ }

	public static function instance() {
		if ( !self::$instance )
			self::$instance = new db();
		return self::$instance;
	}


	public function connecti($host, $user, $password, $db) {
		
		if( !is_resource($this->db) ) {
			
			$this->db = new mysqli($host, $user, $password, $db);
			if ( mysqli_connect_errno() ) {
				$this->last_error = mysqli_connect_error();
				return false;
			}
			
		}
		
		return $this->db;
		
	}
	
	
	public function connect($host, $user, $password, $db, $pers=false) {
		
		$this->db = $pers == false ? mysql_connect( $host, $user, $password ) : mysql_pconnect( $host, $user, $password );
		mysql_select_db( $db, $this->db );

		if ( mysql_errno() ) {
			$this->last_error = "<a href=\"snd_msg.php?m=".mysqli_error($GLOBALS['connect'])."\" style=\"color:#039; font-weight:700; font-family:'Lucida Console', Monaco, monospace\">Please ( R e p o r t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E r r o r )</a>";
			return false;
		}
		return true;
		
	}	
	
	
   public function close() {
        if ( is_resource($this->db) )
	            $this->db->close();
        return true;
   }
   
   
   public function is_error() {
		if ( isset($this->last_error) && !empty($this->last_error) )
			return $this->last_error;
		return false;
   }
  
  
   public function get_results($sql) {
		if ( !$this->query($sql) )
			return false;
		
		$num_rows = 0;
		while ( $row = $this->result->fetch_object() ) {
			$this->last_result[$num_rows] = $row;
			$num_rows++;
		}
		
		$this->result->close();
		
		return $this->last_result;
   }
	
	
   public function num_rows() {
		return (int)$this->row_count;
   }
   
   
   public function __destruct() { return $this->close(); }
   

}




function load_fos( $acct ) {

	$_ = mysqli_query( $GLOBALS['connect'], 'SELECT examofficer_id,fos,dept_options.programme_option, dept_options.do_id  FROM exam_officers INNER JOIN dept_options ON exam_officers.fos = dept_options.do_id WHERE eo_username = "'.$acct.'"' );

	if( 0!=mysqli_num_rows($_) ) {
		$r = array();
		while( $d=mysqli_fetch_assoc($_) )
			$r[] = $d;
		
		mysqli_free_result($_);
		return $r;
	}
	return array();
}





function dbi(){
	$db = db::instance();
	//return $db->connecti('localhost', 'root', '', 'unicalnu_examx2');
	//return $db->connecti('localhost', 'unicalex_root', 'password@**1', 'unicalex_unicalexams');
	return $db->connecti('localhost', 'root', '', 'unicalnu_examx1');
}

function cleansql($strg)
{

	$conn=dbi();
	$dresult = "";
	
	if(get_magic_quotes_gpc())
	{
		 $dresult = mysqli_real_escape_string(stripslashes($conn,$strg));
		 $dresult = trim($dresult);
		 $dresult = htmlspecialchars($dresult);
	}
	else
	{
	
		$dresult =  mysqli_real_escape_string($conn,$strg);
		$dresult = trim($dresult);
		$dresult = htmlspecialchars($dresult);
	}
	
	return $dresult;
}

$connect = dbi();
include_once( dirname(__FILE__)."/fxn.php" );

?>