<?php
namespace Modules\PicturesManager;
class DefaultPictures extends Definitions{    
    
    public function get_all_pictures(){
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $data=$DataMngr->get_items($this->repository);
        return $data;
    }
    
    public function upload($callback=null, $args=null, $sizeFLAG=NULL){ 
        $Images=new \Core\Images();
        $imgsData=$Images->upload('file'); 
        
        foreach ($imgsData as $imgData){
            $ImageLdr=new ImageLoader();
            $ImageLdr->save_images_default($imgData,$callback, $args, $sizeFLAG);
        }
        foreach($imgsData as $item){
            $this->set_db($item['name'].'.'.$item['ext']);
        }
        return $imgData ;
    }
    
    
    
    private function set_db($filename){
        $data['picture']=$filename;
        $DataMngr=new \Modules\DataManager($this->Kernel->entityManager);
        $DataMngr->create_new_item($this->repository, $data);
    }
    
}

?>
