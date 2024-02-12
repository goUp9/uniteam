<?php
namespace Core;
/**
 * The Kernel - inits all the required Core objects and performs the default actions
 * provides the acces to the Core objects to the Classes
 * An object of Kernel is always passed to the Controller
 * An Object of Kernel needs to be returned by a Controller in order to render a page
 * 
 * 
 * @package: Core
 * @author: Anastasia Sitnina
 * @version: 3.0.0
 * 
 */
class Kernel {
    
    # Core Objects
    public $Content;
    public $Meta;
    public $Request;
    public $Response;
    public $Server;
    public $Forms;
    public $FormsF;
    public $images;
    public $entityManager;
    public $Session;
    
    public function __construct(\Doctrine\ORM\EntityManager $entityManager) {
        # init Core objects
        $this->entityManager=$entityManager;
        $this->Session=new Session();
        $this->Request=new Request();
        $this->Content=new ContentFactory($this);
        $this->Meta=new MetadataFactory($this);        
        $this->Server=new Server();
        $this->Response=new Response($this); 
        $this->Forms=new Htmlforms();
        $this->Images=new Images();
        $this->FormsF=new FormsFactory($this);
        
        # perform default actions
        $this->compile_assets();        
    }
    
    /* 
     * prepares the all the autoload assets to be inserted into the page
     * 
     */
    public function compile_assets(){
        $this->Content->init_assets();
        $this->Meta->add_assets();
    }
    
    /*
     * Render the View by calling a pre-created Response object
     */
    public function publish_view(){        
        $this->Response->render();
    }
    
}

?>
