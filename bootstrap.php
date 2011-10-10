<?php

	/**
	 * Show Warnings & Errors if DEV_MODE define was set to (bool) TRUE
	 */
	defined('DEV_MODE') or define('DEV_MODE', 0);
	error_reporting(DEV_MODE ? E_ALL & E_STRICT : 0);
	ini_set('display_errors', DEV_MODE ? 1 : 0);
	
	/*** Set Error Codes ***/
	define('ERRORCODE_TECHNICAL_DIFFICULTIES', 1001);

	/*** Include Logger Class ***/
	include_once (CORE_PATH . 'logger.class.php');
	/*** Initialize Core Object (that manages all child objects/classes) ***/
	include_once (OPENMVC_SYSTEM_PATH . 'core.class.php');
	$Core = new Core;
	

			/*** Each time a new lib class is instantiated/called - include_once it's source file ***/
			function __autoload($class_name) {
				
				/*** All NOT-system classes should be in OPENMVC_LIB_PATH and their names should begin with lowercased class name plus ".class.php" ***/
			    $filename = strtolower($class_name) . '.class.php';
			    $file = OPENMVC_LIB_PATH . $filename;
			    
			    /*** Check if file exists in OPENMVC_LIB_PATH ***/
			    if (!file_exists($file)) {
					
					/*** Look in MODEL PATH ***/
					$filename = strtolower($class_name) . '.class.php';
					$file = MODELS_PATH . $filename;
					
					if (!file_exists($file)) {
						throw new Exception('Can\'t find class ['.$class_name.'] ('.$filename.')', ERRORCODE_TECHNICAL_DIFFICULTIES);
					}
			    }
			    
			  	include_once ($file);
			}

	
	

	
	try {
	
		/*** Using "Router" try to set "View" components and run proper "Controller" that will prepare and run output ***/
		Router::loader();
	
	} catch (Exception $e) {
		
		/*** Some other error - load Error Controller, Error Layout and Content according to ErrorCode ***/
		$Core->Router->loadError();
		
	}
