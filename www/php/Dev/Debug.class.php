<?php
namespace Dev;
/**
 * Description of debug_class
 * @package:
 * @author: Ana Sitnina
 * @version: 
 */
class Debug{
    static private $start_time;
    static public $exec_time;


    
    public static function start_timer(){
        self::$start_time = microtime(true);
    }
    
    public static function get_time(){
        $time_end = microtime(true);

        //dividing with 60 will give the execution time in minutes other wise seconds
        self::$exec_time = ($time_end - self::$start_time);

        //execution time of the script
        return self::$exec_time.' seconds';
    }
    
    public static function set_errors_on(){
        error_reporting(E_ALL);
        ini_set('display_errors', true);
        ini_set('display_startup_errors',1);        
        error_reporting(-1);
    }
    
    public static function development_mode($on=true){
        if($on){
            $_SESSION['development_mode']=true;
        }
        else {
            if(isset($_SESSION['development_mode'])&&!empty($_SESSION['development_mode'])){
                unset($_SESSION['development_mode']);
            }
        }
    }
    
    public static function is_dev_mode(){
        if($_SESSION['development_mode']){
            return true;
        }
        else {
            return false;
        }
    }
    
    public static function dump($var){
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
    
}

?>
