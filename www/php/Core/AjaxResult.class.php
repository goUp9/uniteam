<?php
namespace Core;
class AjaxResult {
    public $status;
    public $msg;
    public $data;
    

    public function __construct($status, $msg='', $data=NULL) {
        $this->status=$status;        
        $this->msg=$msg;
        $this->data=$data;
    }
    
    public function to_JSON(){
        return json_encode($this->to_array());
    }
    
    private function to_array(){
        $array=array();
        $array['status']=  $this->status;        
        $array['msg']= $this->msg;        
        $array['data']= $this->data;
        
        return $array;
    }
    
}
