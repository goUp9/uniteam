<?php
namespace Core;
use Doctrine\Common\ClassLoader;
/**
 * Description of autoload
 * @package: core
 * @author: Anastasia Sitnina
 * @version: 2.0.0
 * 
 * Hanldes the autoloading of the PHP classes
 * Includes in-built system classes 
 * Both Core and Modules 
 */
class Autoload {    
    
    # reads the JSON file with the list of classes to include
    public static function read_config_json(){
        $configPath=dirname(__FILE__).'/../../'.'config/classes.json';
        $handle=fopen($configPath, 'r');
        $config=fread($handle, filesize($configPath));
        $config=  json_decode($config, true);      
        return $config;
    }
    
    #loads classes
    public static function load_classes($name){
        $config=autoload::read_config_json();        
        foreach ($config['classes'] as $class){
            # linux
            $name=  str_replace('\\', '/', $name);            
            if(file_exists(dirname(__FILE__).'/../../'.$class['path'].$name.'.class.php')){                
                include dirname(__FILE__).'/../../'.$class['path'].$name.'.class.php';
            }
            else if(file_exists(dirname(__FILE__).'/../../'.$class['path'].$name.'.trait.php')){
                include dirname(__FILE__).'/../../'.$class['path'].$name.'.trait.php';
            }
            else if(file_exists(dirname(__FILE__).'/../../'.$class['path'].$name.'.interface.php')){
                include dirname(__FILE__).'/../../'.$class['path'].$name.'.interface.php';
            }
        }
    }
    
    #autoload Twig classes
    public static function require_twig(){        
        \Twig_Autoloader::register();
    }
    
    #autoload Doctrine
    public static function autoload_doctrine(){
        # Doctrine        
        $classLoader = new ClassLoader('Doctrine', dirname(__FILE__).'/../../'.'/php/Vendors/');
        $classLoader->register();
        \Doctrine\ORM\Tools\Setup::registerAutoloadDirectory(dirname(__FILE__).'/../../php/Vendors/');
    }
    
    
    public static function register_autoload (){
        #main config
        include dirname(__FILE__).'/../../config/config.php';
        
        #main classes
        spl_autoload_register('self::load_classes', true,true);
        
        require $_SERVER['DOCUMENT_ROOT']."/php/Vendors/twitteroauth-0.6.4/autoload.php";
        
        self::require_twig();
        self::autoload_doctrine();
    }
 
    
}

?>
