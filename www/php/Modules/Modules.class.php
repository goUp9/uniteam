<?php
namespace Modules;

abstract class Modules {
    public $Kernel;
    public $repository="";
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel; 
    }
    
    public function get_repository(){
        $repository=array();
        if($this->repository!==''){
            if(!is_array($this->repository)){ // only one repository for this class
                $repository[0]=DOCTRINE_ENTITIES_PATH.$this->repository;
            }
            else { // multiple repositories (e.g. joined tables)
                $repository=  $this->repository;
                foreach($repository as &$repo){
                    $repo=DOCTRINE_ENTITIES_PATH.$repo;
                }        
            }
        }
        else {            
            $repository=FALSE;
        }          
        return $repository;
    }
}
