<?php
namespace Modules\PicturesManager;
class LayoutPictures {
    
    private $folderPath='data/';
    
    public function set_folderPath($folderPath='data/'){
        $this->folderPath=$folderPath;
        return $this;
    }
    
    public function get_folderPath(){
        return $this->folderPath;
    }
    
    public function get_pictures($pictures_files){
        $pictures=array();
        foreach($pictures_files as $file){
            $name=  explode('.', $file);
            $name=$name[0];
            $pictures[$name]= LINKS_PRE.$this->folderPath.$file;
        }
        return $pictures;
    }
    
}

?>
