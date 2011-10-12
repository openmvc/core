<?php

/**
 *
 * @Class logger
 *
 * @Purpose: Logs text to a log file specified in config
 *
 */


class Logger
{

	/**
	 *
	 * @Constructor is set to private to stop instantiation
	 *
	 */
	private function __construct()
	{
	}

	/**
	 *
	 * @write to the logfile
	 *
	 * @access public
	 *
	 * @param	string	$function The name of the function called
	 * @param 	array	$args	The array of args passed
	 * @return	int	The number of bytes written, false other wise
	 *
	 */
	public static function __callStatic($function, $args)
	{
		if ($fp = fopen(ERROR_LOG_FILE_PATH,'a')) {

			// construct the log message
			$log_msg = date("[Y-m-d H:i:s]") .
				" Code: $args[1] \n" .
				" Message: $args[0]\n" .
				" File: $args[2]\n" .
				" Line: $args[3]\n";

			fwrite($fp, $log_msg);

			fclose($fp);
		}
	}

	/**
	 *
	 * Clone is set to private to stop cloning
	 *
	 */
	private function __clone()
	{
	}
	
} // end of log class

?>
