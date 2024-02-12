<?php
namespace Core;
/**
 * Routes the Request
 * 
 * 
 * @package: Core
 * @author: Anastasia Sitnina
 * @version: 3.0.0
 * 
 */
class Router {
    private $Init;
    private $Kernel;

    
    public function __construct(\Doctrine\ORM\EntityManager $entityManager) {
        $this->entityManager=$entityManager;
        
        #system initialization
        $this->Init=new Init();
        $this->Init->sessions();        
        $this->Kernel=new Kernel($entityManager);
        $this->Mapper=new Mapper($this->Kernel);
        
    }
    
    /*
     *  gets the request to the mapper and publishes view returned by the mapper
     *  @return: Kernel object
     */
    public function route(){
        $this->stack_routes($this->Kernel->Request->route); // save current route to route stack        
        $Kernel=$this->Mapper->get_view($this->Kernel->Request->route);
        return $Kernel;
    }
    
    /*
     *  saves the routes into the route stack to use as history
     *  @param: (array) $last_request - current route from the Kernel object
     */
    private function stack_routes($last_request){
        if(!is_array($_SESSION['routeStack'])){
            $_SESSION['routeStack']=array();
        }
        array_unshift($_SESSION['routeStack'], $last_request);
        if(count($_SESSION['routeStack'])>ROUTESTACK_SIZE){
            array_pop($_SESSION['routeStack']);
        }
    }
}

?>
