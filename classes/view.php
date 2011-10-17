<?php

class View extends Core {
	
	private static $instance;
	/*
	 * @Variables array
	 * @access private
	 */
	public $vars = array();
	private $viewInnerFilePath;
	private $viewLayoutFilePath;
	private $viewLoaded;
	
	public static function obtain() { 
	    if (!self::$instance){  
	        self::$instance = new View();
	    }
	    return self::$instance;  
	}
	
	function __construct() {}
	
	/**
	 *
	 * @set undefined vars
	 *
	 * @param string $index
	 *
	 * @param mixed $value
	 *
	 * @return void
	 *
	 */
	public function __set($index, $value) {
	    $this->vars[$index] = $value;
	}
	
	public function render() {
	
		/*** Load variables ***/
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}
		
		/*** Render inner page and load it into $viewInner variable for use in Layout ***/
		ob_start();
			include_once ($this->viewInnerFilePath);
			$viewInner = ob_get_contents();
		ob_end_clean();
	
		/*** Render View Layout ***/
		ob_start();
		include_once ($this->viewLayoutFilePath);
		$html = ob_get_contents();
		ob_end_clean();
		
		/*** Output HTML ***/
		echo $html;
	}
	
	
	/**
	 *
	 * @set the view
	 *
	 * @access private
	 *
	 * @return void
	 *
	 */
	public function setView($layout='', $inner='') {
	
		/*** set the file path ***/
		$this->viewLayoutFilePath = VIEWS_PATH . 'layouts/' . $layout . '.tpl';
		
		/*** check if file exists ***/
		if (is_readable($this->viewLayoutFilePath) == false) {
			throw new Exception ('Invalid view layout file: `' . $this->viewLayoutFilePath . '`');
		}
		
		
		/*** set the file path ***/
		$this->viewInnerFilePath = VIEWS_PATH . 'inners/' . $inner . '.tpl';
		
		/*** check if file exists ***/
		if (is_readable($this->viewInnerFilePath) == false) {
			throw new Exception ('Invalid view inner file: `' . $this->viewInnerFilePath . '`');
		}
		
		/*** update view Flag ***/
		$this->viewLoaded = true;
	
	}
	
	/**
	 *
	 * @include elements files. used by View Layouts
	 *
	 * @access private
	 *
	 * @return void
	 *
	 */
	private function includeBlocks($blocks) {
		
		/*** throw an exception if incorrect parameter was passed ***/
		if(empty($blocks) || !is_array($blocks)) {
			throw new Exception('Wrong parameter type passed to includeBlocks() function', ERRORCODE_TECHNICAL_DIFFICULTIES);
		}
		
		/*** Load variables for included elements to access them - am not happy about such workaround, should be fixed later ***/
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}
		
		foreach($blocks as $block) {
			$blockFilePath = VIEWS_PATH . 'blocks/' . $block . '.tpl';
			if(!file_exists($blockFilePath)) {
				throw new Exception('Can\'t find view block ['.$blockFilePath.']', ERRORCODE_TECHNICAL_DIFFICULTIES);
			}
			
			include_once ($blockFilePath);
		}
		
		
	}
	
}