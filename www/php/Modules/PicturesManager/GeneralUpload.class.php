<?php
namespace Modules\PicturesManager;
class GeneralUpload {
        
    private $uploadPath;
    
    public function set_uploadPath($path){
        $this->uploadPath=$path;
    }
    
    private $inputName='file';
    
    public function set_inputName($input_name){
        $this->inputName=$input_name;
    }
    
    public function upload($multiple=false, $callback=NULL,$args=NULL){
        $Images=new \Core\Images();
        $Images->set_path($this->uploadPath);
        $imgData=$Images->upload($this->inputName);
        
        if($args!=NULL){
            array_unshift($args, $imgData);
        }
        else {
            $args[0]=$imgData;
        }
        
        if(!empty($callback)){ 
            if(is_callable($callback)){
                $imgData=call_user_func_array($callback, $args);
            }
        }        
        return $imgData ;        
    }
    
}
