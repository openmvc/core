<?
/** 
 * Core class 
 * 
 * combined singleton & factory class & autoload 
 * holds all variables and object for all child classes 
 */ 
class Core { 
    private static $core_loaded = false; 
    protected  static $_objects = array(); 
	protected  static $_vars = array(); 
     
    /** 
     * __construct 
     * 
     * Check if the core objects are loaded, if not load them 
     * 
     * @access Public 
     */ 
    public function __construct() { 
        if(!Core::$core_loaded) { 
            Core::loadCore(); 
        }
    } 
     
    /** 
     * __set 
     * 
     * magic setter (PHP OOP Native) 
     * checks if first letter is Capital, if yes load the object and save, else save value 
     * 
     * @access Public 
     * @param string $key 
     * @param mixed $key 
     * @return boolean 
     */ 
    public function __set($key, $val) { 
        $fl = substr($key, 0, 1); 
        if(ord($fl) >= 65 && ord($fl) <= 90 ) { 
            Core::$_objects[$key] = $val; 
        } else { 
            Core::$_vars[$key] = $val; 
        } 
        return true; 
    } 

    /** 
     * __get 
     * 
     * magic getter (PHP OOP Native) 
     * checks if first letter is Capital, if yes retrieve the object and return, else return value 
     * 
     * @access Public 
     * @param string $key 
     * @return mixed 
     */ 
    public function __get($key) { 
        $fl = substr($key, 0, 1); 
        if(ord($fl) >= 65 && ord($fl) <= 90 ) { 
            if(!isset(Core::$_objects[$key])) { 
                Core::loadObject($key); 
            } 
            return Core::$_objects[$key]; 
        } else { 
            if(isset(Core::$_vars[$key])) { 
                return Core::$_vars[$key]; 
            } 
        } 
        return false; 
    } 
     
    /** 
     * loadCore 
     * 
     * loads all default (and required) classes 
     * 
     * @access Private 
     */ 
    private static function loadCore() {
        
        /*** Include Controller class ***/
        include_once (OPENMVC_SYSTEM_PATH . 'controller.class.php');
        
        /*** Load Router object ***/
        include_once (OPENMVC_SYSTEM_PATH . 'router.class.php');
        Core::$_objects['Router'] = Router::obtain();
        
        /*** Load Template object ***/
        include_once (OPENMVC_SYSTEM_PATH . 'view.class.php');
        Core::$_objects['View'] = View::obtain();
        
        /*** Load Config object ***/
        include_once (OPENMVC_SYSTEM_PATH . 'config.class.php');
        Core::$_objects['Config'] = Config::obtain();
        
        /*** set timezone ***/
        $timezone = Core::$_objects['Config']->system['timezone'];
        if(!@date_default_timezone_set($timezone)) {
        	die('<strong>System error:</strong> wrong timezone "'.$timezone.'"<br/>Recheck config file');
        }
        
        	Core::$core_loaded = true;
    } 

    /** 
     * loadObjects 
     * 
     * autoload method for classes 
     * 
     * @access Public 
     * @param string $class_name 
     * @param string $object_alias
     */ 
    private static function loadObject($class_name, $obtain = false) {
    	/*** exception for Database - Database is the only object that should be created statically as it is wrapper ***/
    	if($obtain === true) {
    		Core::$_objects[$class_name] = $class_name::obtain();
    	} else {
	        $file_name = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $class_name));
	        Core::$_objects[$class_name] = new $class_name();
    	}
    } 
     
    /** 
     * __destruct 
     * 
     * @access Public 
     */ 
    public function __destruct() {} 

}