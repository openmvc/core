<?php

Abstract class Controller extends Core {
	
	function __construct() {
		
	}
	
	public function loadDefaults()
	{
		
	}
	
	public function addCSS($files = array()) {
		if(@empty($files)) return false;
		
		$string = '';
		$content = '';
		foreach($files as $filename) {
		
			$file_size = filesize(ASSETS_PATH . 'css/' . $filename); 
			$handle    = fopen(ASSETS_PATH . 'css/' . $filename, 'rb') or die("error opening file"); 
            $content  .= fread($handle, $file_size) or die("error reading file"); 
			
            $string .= $filename;
            
		}
		
		$mergedFileName = md5($string) . '.css';
		
		$handle=fopen(STATIC_DIR .'css/' . $mergedFileName, 'wb') or die("error creating/opening merged file"); 
        fwrite($handle, $content) or die("error writing to merged file"); 
        
        $this->requireCss($mergedFileName);
	}
	
	public function addJs($files = array()) {
		if(@empty($files)) return false;
		
		$string = '';
		$content = '';
		foreach($files as $filename) {
		
			$file_size = filesize(ASSETS_PATH . 'js/' . $filename); 
			$handle    = fopen(ASSETS_PATH . 'js/' . $filename, 'rb') or die("error opening file"); 
            $content  .= fread($handle, $file_size) or die("error reading file"); 
			
            $string .= $filename;
            
		}
		
		$mergedFileName = md5($string) . '.js';
		
		$handle=fopen(STATIC_DIR .'js/' . $mergedFileName, 'wb') or die("error creating/opening merged file"); 
        fwrite($handle, $content) or die("error writing to merged file"); 
        
        $this->requireJS($mergedFileName);
	}
	
	
	public function requireStyle($css='') {
		if($css=='') return false;
		$this->View->vars['requiredStyle'][] = $css;
	}
	
	
	public function requireCss($cssFile)
	{
		$this->View->vars['requiredCss'][$cssFile] = array('show'=>true);
	}
	
	public function requireJs($jsFile)
	{
		$this->View->vars['requiredJs'][$jsFile] = array('show'=>true);
	}
	
	abstract function dispatch();

}