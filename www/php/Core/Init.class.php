<?php
namespace Core;
class Init {
    
    public function sessions(){
        if(!isset($_SESSION['tmp'])){
            $_SESSION['tmp']=array();
        }
        if(!isset($_SESSION['route'])){
            $_SESSION['route']=array(
                'controller'=>'',
                'prefix'=>'',
                'params'=>array()                
            );
        }
        if(!isset($_SESSION['routeStack'])){
            $_SESSION['routeStack']=array();
        }
        if(!isset($_SESSION['development_mode'])){
            $_SESSION['development_mode']=FALSE;
        }
    }
    
    public static function clear_session_route(){
        $_SESSION['route']=array(
                'controller'=>'',
                'prefix'=>'',
                'params'=>array()                
        );
    }
    
}

?>
