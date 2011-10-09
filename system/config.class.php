<?php

class Config extends Core {

	/*
	 * @var array $config_values; 
	 */
	public $config_values = array();

	/*
	* @var object $instance
	*/
	private static $instance = null;

	/**
	 *
	 * Return Config instance or create intitial instance
	 *
	 * @access public
	 *
	 * @return object
	 *
	 */
	public static function obtain()
	{
 		if(is_null(self::$instance))
 		{
 			self::$instance = new Config;
 		}
		return self::$instance;
	}


	/**
	 *
	 * @the constructor is set to private so
	 * @so nobody can create a new instance using new
	 *
	 */
	public function __construct()
	{
		$this->config_values = parse_ini_file(CONFIG_PATH . 'config.ini.php', true);
		$configArr = parse_ini_file(CONFIG_PATH . 'config.ini.php', true);
		foreach($configArr as $group=>$subArr) {
			if(!isset($this->$group)) $this->$group = $subArr;
		}
	}
	
	/**
	 * @get a config option by key
	 *
	 * @access public
	 *
	 * @param string $key:The configuration setting key
	 *
	 * @return string
	 *
	 */
	public function getValue($key)
	{
		return self::$config_values[$key];
	}


	/**
	 *
	 * @__clone
	 *
	 * @access private
	 *
	 */
	private function __clone()
	{
	}
}
