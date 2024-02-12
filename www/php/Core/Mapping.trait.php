<?php
namespace Core;
trait Mapping {
    
    /* 
     * gets the current map information
     * @return (array) $c_map - current map  
    */
    public function get_current_route_map(){        
        $maps=Utils::read_json($_SERVER['DOCUMENT_ROOT'].'/'.MAPS_PATH);
        $route=$this->Kernel->Request->route['full'];
        $c_map=FALSE;
        foreach($maps['routes'] as $map){
            # for the current page
            if ($map['request']==$route){
                $c_map=$map;
            }
        }
        try {
                # for the current page
                if ($c_map===FALSE){
                    throw new \Exception('The route doesn\'t exist. Page can\'t be loaded. 404');
                }
            } catch (\Exception $E) {
                if(\Dev\Debug::is_dev_mode()){
                    echo $E->getMessage();
                }
                else {
//                    production 404 error here
                }
            }
        return $c_map;
    }
    
    
}


?>
