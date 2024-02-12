<?php
namespace Core;
class Session {
    
    public function __construct() {
        $this->register_sessions();
    }
    
    public function register_sessions(){
        $this->access=(object)$_SESSION;      
    }
    
    public function set($data,$key){
        $_SESSION[$key]=$data;
        $this->access->$key=(object)$data;
    }  
    
    public function add ($data,$key){
        if(!isset($_SESSION[$key])){
            $_SESSION[$key]=array();
        }
        array_push($_SESSION[$key],$data);
        $this->access->$key=(array)$this->access->$key;
        array_push($this->access->$key,$data);
    }
    
}
