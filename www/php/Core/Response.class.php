<?php
namespace Core;
class Response {

    public $template=NULL;
    public $pathToTemplate='';    
    public $mapsPath='config/maps.json'; 
    private $replace;
    
    public function __construct(Kernel $Kernel=NULL) {
        $this->Kernel=$Kernel;
    }
    
    function set_template($template=NULL){
        if(!isset($template)){
            $maps=\Core\Utils::read_json($this->mapsPath);
            $route=$this->Kernel->Request->route['full'];
            foreach($maps['routes'] as $map){
                # for the current page                
                if ($map['request']==$route){
                    if($map['tpl']!==''){
                        $this->template=$map['tpl'];
                    }
                    else {
                        $this->template=FALSE;
                    }
                    
                    
                    if(isset($map['tplBasePath'])&&$map['tplBasePath']!==''){
                        $this->pathToTemplate=$map['tplBasePath'];                        
                    }
                    else if($this->pathToTemplate===''){
                        $this->pathToTemplate=TEMPLATES_PATH;
                    }
                }
            } 
        }
        else {
            $this->template=$template;
        }
        return $this;
    }
    
    function add_header($type, $header){
        $this->Kernel->Meta->headers[$type]=$header;
    }
    
    function set_headers(){
        if(isset($this->Kernel->Meta->headers)){
            if (is_array($this->Kernel->Meta->headers)&&!empty($this->Kernel->Meta->headers)){
                foreach ($this->Kernel->Meta->headers as $key=>$value){
                    header($key.' : '.$value);
                }
            }
        }
    }
    
    function render(){ 
        $this->set_headers();
        $sessions=array();
        $meta['meta']=array();
        $content=array();
        if($this->Kernel!==NULL){
            $meta['meta']=(array)  $this->Kernel->Meta;
            $content=(array) $this->Kernel->Content;
            $sessions['session']=(array) $this->Kernel->Session->access;
        }
        $this->replace=array_merge($content, $meta, $sessions);
        $this->twig_it();
        if($this->view!==FALSE){
            echo $this->view;
        }
        return $this;
    }
    
    private function twig_it(){  
        if(!isset($this->template)){
            $this->set_template();
        }
        
        $path_to_templates=$_SERVER['DOCUMENT_ROOT']."/".$this->pathToTemplate;
        $loader=new \Twig_Loader_Filesystem($path_to_templates);
        
        if(isset($_SESSION['development_mode'])&&$_SESSION['development_mode']){
            $twig=new \Twig_Environment($loader,array( 
                    'debug'=>true, 
                    'charset'=>'utf-8'
            ));
            $twig->addExtension(new \Twig_Extension_Debug());
        }
        else {
            $cache=$_SERVER['DOCUMENT_ROOT'].'/cache/';
            $twig=new \Twig_Environment($loader,array(
                    'cache'=>$cache,
                    'autoreload'=>true,
                    'charset'=>'utf-8'
            )); 
        }
        if($this->template!==FALSE){
            $this->view=$twig->render($this->template, $this->replace);
        }
        else {
            $this->view=FALSE;
        }
        return $this;
    }
    
}

?>
