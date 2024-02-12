<?php
namespace Core;

class InjectionsFactory {

    protected function generateInjections($injectionName){        
        if(class_exists('Core\\'.$injectionName)){
            $r=new \ReflectionClass('Core\\'.$injectionName);
            $this->$injectionName=$r->newInstanceArgs(); 
        }
    }
}

?>
