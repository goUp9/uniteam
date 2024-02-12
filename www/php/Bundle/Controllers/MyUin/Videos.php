<?php
namespace Bundle\Controllers\MyUin;
class Videos extends \Modules\Users\Profile{
    public $repository=['MyUinVideos'];
    
    public function admin(){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        $this->Kernel->Response->pathToTemplate='/templates/admin_mod/'; 
        return $this->Kernel;
    }
    
    public function get_videos(){
        $Db=new \Modules\DataManager($this->Kernel);
        $data=$Db->get_items($this->get_repository()[0]);
        if(\Core\Utils::is_ajax()){
            echo json_encode($data);
        }
        return $data;
    }
    
    public function set_video($id=NULL){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        if(isset($this->Kernel->Request->post)&&!empty($this->Kernel->Request->post)){
            if($id===NULL){
                $Videos=new \Bundle\Doctrine\Entities\MyUinVideos();
            }
            else{
                $Videos=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($id);
            }
            if(is_object($Videos)){
                if(isset($this->Kernel->Request->post['title'])){
                    $Videos->setTitle($this->Kernel->Request->post['title']);
                }
                if(isset($this->Kernel->Request->post['video'])){
                    $Videos->setVideo($this->Kernel->Request->post['video']);
                }
                $this->Kernel->entityManager->persist($Videos);
                $this->Kernel->entityManager->flush();
            }
        }        
    }
    
    public function delete_video($id){
        $Unlogged=new \Bundle\Controllers\Admin\Login($this->Kernel);
        $Unlogged->unlogged();
        
        $Db=new \Modules\DataManager($this->Kernel);
        if(\Core\Utils::is_ajax()){
            $Db->delete_item_by_id($this->get_repository()[0], $id);
        }
    }
    
}
