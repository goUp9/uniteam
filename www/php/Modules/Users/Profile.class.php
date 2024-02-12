<?php
namespace Modules\Users;
class Profile extends \Modules\Modules{
    public $repository="users";
    public $redirect_id="";
        
    function unlogged(){        
        $Login=new Login($this->Kernel); 
        if($this->redirect_id!==''){
            $redirectLink=$this->Kernel->Content->insert_asset('link',$this->redirect_id)['href'];
        }
        else {
            $redirectLink='';
        }
        if(!$Login->is_logged()){
            header('Location:'.LINKS_PRE.$redirectLink);
            die();
        }        
    }
    
    function get_user_info(){
        
        $DataMngr=new \Modules\DataManager($this->Kernel);
        $user=$DataMngr->get_item_by_id($this->get_repository()[0], $_SESSION['user']['id']);
        
        return $user;
    }
}
