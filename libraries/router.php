<?php

class Router extends Core {
	
	private static $instance;
	
	/*** data retrieved from database ***/
	public $pageArr = array();
	
	private $controllerLoaded;
	private $controller;
	private $controllerFilePath;
	
	/*** Singleton declaration ***/
	public static function obtain(){ 
	    if (!self::$instance){  
	        self::$instance = new Router();
	    }
	    return self::$instance;  
	}
	
	function __construct() {}
	
	/**
	 *
	 * @load the controller
	 *
	 * @access public
	 *
	 * @return void
	 *
	 */
	 public function loader() {
	 		
					/*** Search for route only if Controller and View are not set yet ***/
		 			if(!$this->controllerLoaded && !$this->View->viewLoaded) {
		 				
		 					/*** Extract exact URI we are looking for ***/
		 					$this->requestURI = str_replace(STATIC_DIR, '', $_GET['url']);
		 					
		 					/*** Avoid 404 error for favicon ***/
						 	if ($this->requestURI === 'favicon.ico') { die(); }
						 	
						 	$this->requestURIChain = explode('/', $this->requestURI);
						 	
						 	/*** If couldn't find controller AND could not load default controller - return 404 ***/
						 	if(    !$this->setController($this->requestURIChain[0])
						 		&& !$this->setController($this->Config->system['default_controller']) ) {
						 		
						 			throw new Exception ('Could not load default controller: `' . $this->Config->system['default_controller'] . '`', ERRORCODE_TECHNICAL_DIFFICULTIES);
						 	}
					
		 			}
	
		/*** include the Controller ***/
		include_once($this->controllerFilePath);
	
		/*** launch Controller ***/
		$controller = new $this->controller();
		$controller->dispatch();
	 }
	
	 /**
	 *
	 * @set the controller
	 *
	 * @access private
	 *
	 * @return void
	 *
	 */
	private function setController($controller) {
	
		$controller .= 'Controller';
		
		/*** set the file path ***/
		$this->controllerFilePath = CONTROLLERS_PATH . $controller . '.php';

		/*** if the file is not there diaf ***/
		if (is_readable($this->controllerFilePath) == false) {
			return false;
		}
		
		/*** update controller ***/
		$this->controller = $controller;
		
		/*** update controller Flag ***/
		$this->controllerLoaded = true;
		
		return true;
	}
	
	
	/**
	 *
	 * @sets Error controller, Error Layout, Error content and re-lauches Router's loader() method.
	 *
	 * @access public
	 *
	 * @return void
	 *
	 */
	public function loadError() {
		$this->setController($this->Config->system['error_controller']);
		$this->View->setView($this->Config->system['error_layout'], $this->Config->system['error_content']);
		$this->loader();
	}
	
}