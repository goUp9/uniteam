<?php
namespace Bundle\Controllers\MyUin;
class Personal extends \Modules\Users\Profile{
    use \Core\Mapping;
    public $repository="Users";
    public $redirect_id="";
    
    function main(){
        $this->unlogged();
        
        $userData=$this->get_user_info();
        
        $route=  $this->get_current_route_map();
        $this->Kernel->Content->set_data($route["request"],'route');
        
        $ProfileForm=new ProfileForm($this->Kernel,"form__personal",'',array($userData));
        $form=$ProfileForm->form;        
        $this->Kernel->Content->set_form($form,'personal');
        
        $VideosCtrl=new Videos($this->Kernel);
        $videos=$VideosCtrl->get_videos();
        $this->Kernel->Content->set_data($videos,'videos');
        
        return $this->Kernel;
    }
    
    function edit(){        
        $this->unlogged();
        
        if(\Core\Utils::is_ajax()){          
            $DataMngr=new \Modules\DataManager($this->Kernel);
            unset($this->Kernel->Request->post['password']);
            unset($this->Kernel->Request->post['salt']);
            $DataMngr->update_item_by_id($_SESSION['user']['id'], $this->get_repository()[0], $this->Kernel->Request->post);
        }
        
        return $this->Kernel;
    }
    
}
