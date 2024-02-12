<?php
namespace Core;
class Request {
    public $post;
    public $get;
    public $files;
    public $route;
    public $prefix;
    
    
        
    protected $prefixesPath='config/route_prefixes.json';
    
    public function __construct() {
        $this->post=  Utils::filter_input($_POST);
        $this->get=Utils::filter_input($_GET);
        $this->files=$_FILES;
        
        $requestURL=$this->parse_requestURL();        
        $request=$this->get_request($requestURL);        
        $this->route=$request;        
        
    }
    
    #parses request URL and makes an array out of it
    private function parse_requestURL(){        
        $this->check_prefixes($_SERVER['REQUEST_URI']); //checks if the address has prefixes and saves prefix        
        if($this->prefix!==''){ // if there is prefix - remove it
            $route=$this->remove_prefix($_SERVER['REQUEST_URI']);            
        }
        else {
            $route=$_SERVER['REQUEST_URI'];
        }        
        $requestURL=explode('/',$route);         
        array_shift($requestURL); array_pop($requestURL);        
        return $requestURL;
    }
    
    # breaks down request
    private function breakdown_request($requestURL){ 
        $request['controller']=$requestURL[0];
        unset($requestURL[0]);
        $request['params']=$requestURL;            
        $request['params']=array_values($request['params']); 
        $request['prefix']=$this->prefix; // if no prefix: prefix==='' 
        return $request;
    }
    
    private function get_request($requestURL){        
        # default home/index page controller
        if(count($requestURL)==0){            
            $request['controller']='home';            
            $request['prefix']=$this->prefix;
            #add the combined prefix+controller route
            if($this->prefix!=''){ 
                $request['full']=$this->prefix.'home';
            }
            else {
                $request['full']='home';
            }
        }
        # all the other pages
        else {
            $request=$this->breakdown_request($requestURL);            
            $request['full']=$request['prefix'].$request['controller'];//add the combined prefix+controller route            
        } 
        return $request;
    }
    
    private function check_prefixes($route){
        $prefixes=  Utils::read_json($this->prefixesPath);
        $flag=FALSE;
        foreach($prefixes as $prefix){
            $pos=strrpos($route, $prefix, 0);            
            if($pos===1){
                $flag=TRUE;
                $this->prefix=$prefix;
            }            
        }
        if($flag===FALSE){
            $this->prefix='';
        }        
        return $this;
    }
    
    #removes prefix for the router to deal with request first
    private function remove_prefix($route){
        $route=  str_replace($this->prefix,'', $route);      
        return $route;
    }
    
}

?>
