<?php
namespace Core;
class Images{
    private $path;
    private $imgName;
    private $imgFormat='jpg';
    
    function set_path($path){
        $this->path=$path;
        return $this;
    }
    
    function set_imgName(){
        $this->imgName=Utils::generate_random_name();
    }
    
    function set_imgFormat($ext){
        $this->imgFormat=$ext;
    }
    
    function upload($inputName){        
        $imgs = \WideImage::loadFromUpload($inputName);        
        return $this->save($imgs);
    }
    
    protected function save($imgs){
        $imgData=array();
        if(is_array($imgs)){
            foreach ($imgs as $img){
                $this->set_imgName();
                array_push($imgData, array(
                    'path'=>  $this->path,
                    'ext'=>  $this->imgFormat,
                    'name'=>  $this->imgName)
                );
                $img->saveToFile(dirname(__FILE__).'../../../'.$this->path.$this->imgName.'.'.$this->imgFormat);
            }
        }
        else {
            $this->set_imgName();
            $imgData['path']=$this->path;
            $imgData['ext']=$this->imgFormat;
            $imgData['name']=$this->imgName;
            $imgs->saveToFile(dirname(__FILE__).'../../../'.$this->path.$this->imgName.'.'.$this->imgFormat);
        }
        return $imgData;
    }
}

?>
