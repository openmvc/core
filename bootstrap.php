<?php
/*** Turn DEBUG mode On/Off ***/
define('DEV_MODE', 1);
	error_reporting(DEV_MODE ? E_ALL : 0);
	ini_set('display_errors', DEV_MODE ? 1 : 0);

	
			####################### SET MAIN DEFINES ##########################
				/*** Set WWW Paths ***/
				define('WWW_PATH', 'http://' . str_replace('openmvc/index.php', '', $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']));
				define('WWW_CSS_PATH', WWW_PATH . 'css/');
				define('WWW_JS_PATH', WWW_PATH . 'js/');
				define('WWW_IMAGES_PATH', WWW_PATH . 'images/');
				
				define('STATIC_DIR', dirname(dirname(__FILE__)) . '/static_files/');
				
				/*** Set OpenMVC absolute Paths ***/
				define('OPENMVC_PATH', dirname(__FILE__). '/');
				define('OPENMVC_SYSTEM_PATH', OPENMVC_PATH . 'system/');
				define('OPENMVC_LIB_PATH', OPENMVC_PATH . 'lib/');
				
				/*** Set App absolute Paths ***/
				define('APP_PATH', dirname(dirname(__FILE__)). '/app/');
				define('CONTROLLERS_PATH', APP_PATH . 'controllers/');
				define('VIEWS_PATH', APP_PATH . 'views/');
				define('CONFIG_PATH', APP_PATH . 'config/');
				define('MODELS_PATH', APP_PATH . 'models/');
				define('ASSETS_PATH', APP_PATH . 'assets/');
				
				/*** Set Logs files paths ***/
				define('LOGS_PATH', APP_PATH . 'logs/');
				define('ERROR_LOG_FILE_PATH', LOGS_PATH . 'errors.log');
				
				/*** Set Error Codes ***/
				define('ERRORCODE_TECHNICAL_DIFFICULTIES', 1001);
			####################### SET MAIN DEFINES ##########################


	/*** Include Logger Class ***/
	include_once (OPENMVC_SYSTEM_PATH . 'logger.class.php');
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
		$Core->Router->loader();
	
	} catch (Exception $e) {
		
		/*** Some other error - load Error Controller, Error Layout and Content according to ErrorCode ***/
		$Core->Router->loadError();
		
	}