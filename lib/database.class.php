<?php
/**
 *
 * Database class
 *
 */

class Database extends Core {

	/**
	 * Holds an insance of self
	 * @var $instance
	 */
	//private static $instance = NULL;

	
	public function __construct() {}

	/**
	*
	* Return DB instance only if it was not created yet
	*
	* @return object (PDO)
	*
	* @access protected (should be called only from Core class)
	*
	*/
	public static function obtain($db_type=null, $db_hostname=null, $db_port=null, $db_name=null, $db_username=null, $db_password=null) {
		
		/*** set Connection parameters ***/
		$db_type = ($db_type!=null) ? $db_type : Core::$_objects['Config']->database['db_type'];
		$db_hostname = ($db_hostname!=null) ? $db_hostname : Core::$_objects['Config']->database['db_hostname'];
		$db_port = ($db_port!=null) ? $db_port : Core::$_objects['Config']->database['db_port'];
        $db_name = ($db_name!=null) ? $db_name : Core::$_objects['Config']->database['db_name'];
        $db_username = ($db_username!=null) ? $db_username : Core::$_objects['Config']->database['db_username'];
        $db_password = ($db_password!=null) ? $db_password : Core::$_objects['Config']->database['db_password'];
        
        /*** Initiate PDO object ***/
        $DBH = new PDO("$db_type:host=$db_hostname;port=$db_port;dbname=$db_name", $db_username, $db_password);
        $DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        
        return $DBH;
	}
	
	/**
	*
	* Like the constructor, we make __clone private
	* so nobody can clone the instance
	*
	*/
	private function __clone()
	{
	}

} // end of class
