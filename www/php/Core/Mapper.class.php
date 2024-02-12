<?php
namespace Core;

class Mapper extends InjectionsFactory{ 
    
    public function __construct(Kernel $Kernel) {        
        $this->Kernel=$Kernel;        
    }
    
    function get_view($request){        
        $maps=Utils::read_json($_SERVER['DOCUMENT_ROOT'].'/'.MAPS_PATH);         
        if($request['prefix'].$request['controller']!==""){
            foreach($maps['routes'] as $map){            
                # for the current page                 
                if ($map['request']==$request['prefix'].$request['controller']){

                    $controller=$map['controller'];
                    
                    #separete controller from the method if the method exists                
                    $cData=$this->break_controller_method($map['controller']);                
                    if(count($cData)===2){                    
                        $classname=$cData[0];
                        $method=$cData[1];                    
                    }
                    else {
                        $classname=$controller;
                        $method=NULL;
                    }                    
                    
                    if($classname!="" && (file_exists($_SERVER['DOCUMENT_ROOT'].'/php/Bundle/Controllers/'.$classname.'.php')||file_exists($_SERVER['DOCUMENT_ROOT'].'/php/Controllers/'.$classname.'.php'))){                        
                        # obtain injections for this class             
                        $injections=$this->get_injections($classname);                

                        if(is_array($injections)){
                            array_unshift($injections, $this->Kernel);
                        }
                        else {
                            $injections[0]=$this->Kernel;
                        }
                        
                        #obtain includes
                        $this->get_dependancies($controller);
                        
                        # set forms
                        $this->set_forms($map, $this->Kernel);  
                        
                        # pass the request info to the main method of controller
                        $map['request']=$request;
                        
                        #set parameters and defaults to pass to the controller
                        $params=$this->set_pageData_params($map,$request);                
                        
                        # require the controller 
                        # linux
                        $classname=  str_replace('\\', DIRECTORY_SEPARATOR, $classname);
                       
                        if (strpos($request['prefix'].$request['controller'],'cwd-system')===FALSE){
                            require_once $_SERVER['DOCUMENT_ROOT'].'/php/Bundle/Controllers/'.$classname.'.php';
                            # get view
                            $this->Kernel=$this->init_controller($classname, $injections,$params, $method);                    
                        }
                        else {                    
                            require_once $_SERVER['DOCUMENT_ROOT'].'/php/Controllers/'.$classname.'.php';
                            # get view
                            $this->Kernel=$this->init_system_controller($classname, $injections,$params, $method);                    
                        }                         
                        $this->call_commons($map);                        
                    }                    
                    else {                         
                        #obtain includes
                        $this->get_dependancies($controller);
                        
                        # set forms
                        $this->set_forms($map, $this->Kernel);
                        
                        $this->call_commons($map);
                                                
                        return $this->Kernel;
                    }                    
                    return $this->Kernel;
                }
            }            
        }
        return false;
    }
    
    # gets the array of objects to inject
    private function get_injections($classname){
        $data=utils::read_json($_SERVER['DOCUMENT_ROOT']."/config/maps.json");
        $classname=  ucfirst($classname);
        foreach($data['routes'] as $d){
            if ($d['controller']===$classname){
                if(isset($d['injections'])){
                    $injections=$d['injections'];
                }
            }
        }
        $inject=array();
        if (!empty($injections)){
            for ($i=0; $i<count($injections);$i++){
                $this->generateInjections($injections[$i]);
                array_push($inject,$this->{$injections[$i]});
            }
        } 
        return $inject;
    }
    
    # gets the array of files to include
    private function get_dependancies($classname){        
        $data=utils::read_json($_SERVER['DOCUMENT_ROOT']."/config/maps.json");
        $classname=  ucfirst($classname);         
        foreach($data['routes'] as $d){ 
            if ($d['controller']===$classname){ 
                if(isset($d['dependancies'])){
                    $dependancies=$d['dependancies'];
                }
            }
        }
        if (!empty($dependancies)){            
            for ($i=0; $i<count($dependancies);$i++){
                include_once $_SERVER['DOCUMENT_ROOT'].'/php/Bundle/Controllers/'.$dependancies[$i].'.php';
            }
        }
    }
    
    # initilizes the controller and calls the function to get the view
    private function init_controller($classname, $injections, $params,$method=NULL){
        # linux
        $classname=  str_replace('/', '\\', $classname);
        $r=new \ReflectionClass('Bundle\\Controllers\\'.$classname);
        if (!empty($injections)){
            $controller=$r->newInstanceArgs($injections);
        }
        else {
            $controller=$r->newInstanceArgs();
        } 
        $this->Kernel=$this->class_business_logic($r, $controller, $params,$method);
        return $this->Kernel;
    }
    
    private function call_commons($map){
        if(isset($map['calls'])){            
            foreach($map['calls'] as $call){                
                $callData=$this->break_controller_method($call);                
                $path=  str_replace( '\\','/', SOURCE_ROOT_PATH.COMMONS_NAMESPACE);
                include_once $_SERVER['DOCUMENT_ROOT'].'/'.$path.$callData[0].'.php';
                $r=new \ReflectionClass(COMMONS_NAMESPACE.$callData[0]);                
                $controller=$r->newInstanceArgs(array($this->Kernel));
                call_user_func(array($controller,$callData[1]));
            }
        }
    }


    # init the in-built system controller
    private function init_system_controller($classname, $injections, $params,$method=NULL){ 
        # linux
        $classname=  str_replace('/', '\\', $classname);
        $r=new \ReflectionClass('Controllers\\'.$classname);
        if (!empty($injections)){
            $controller=$r->newInstanceArgs($injections);
        }
        else {
            $controller=$r->newInstanceArgs();
        } 
        $view=$this->class_business_logic($r, $controller, $params,$method);
        return $view;
    }
    
    #runs the logic of the class and returns view
    private function class_business_logic($r, $controller, $params, $method=NULL){        
        if($method!=NULL && $r->hasMethod($method)){
            if(is_callable(array($controller,$method))){                                
                    $this->Kernel=call_user_func_array(array($controller,$method), $params);     
            }
        }
        else if ($r->hasMethod('main')){
             if(is_callable(array($controller,'main'))){                                
                    $this->Kernel=call_user_func_array(array($controller,'main'), $params);     
            }
        }
        else {
            $this->Kernel=NULL;
        }
        return $this->Kernel;
    }
    
    private function break_controller_method($controller){
        $cData=explode(':', $controller);
        return $cData;        
    }
    
    private function set_pageData_params($map){        
        $params=array();
        $i=0;
        if (isset($map['params'])){
            foreach($map['params'] as $paramName=>$paramValue){                          
                if(isset($map['request']['params'][$i])){                
                    $params[$paramName]=$map['request']['params'][$i];
                }
                else{
                    $params[$paramName]=$paramValue;
                } 
                $i++;
            }            
        }        
        return $params; 
    }
    
    private function set_forms($map,$Kernel){
        if(!empty($map['forms'])){
            foreach($map['forms'] as $formName=>$data){
                $FormClass='Bundle\\Controllers\\'.$data['form class'];
                # linux
                $FormClass=  str_replace('/', '\\', $FormClass);
                
                if(isset($data['action'])){
                    if(!empty($data['action'])){
                        $formAction=$data['action'];
                        $this->Kernel->Content->insert_asset('link',$formAction);
                        $FormObj=new $FormClass($this->Kernel, 'form__'.$formName,LINKS_PRE.$formAction.'/');
                    }
                    else {
                        $FormObj=new $FormClass($this->Kernel, 'form__'.$formName,'');
                    }
                }
                else {
                    $FormObj=new $FormClass($this->Kernel, 'form__'.$formName);
                }
               
                
                $this->Kernel->Content->set_form($FormObj->form,$formName);
            }
        }
    }
    
}

?>
